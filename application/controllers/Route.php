<?php
/**
* 
*/
class Route extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("Route_model","routeModel");
		$this->load->model("Shuttle_model","shuttleModel");
		$this->load->model("Jam_model","jamModel");
		$this->load->model("Bus_model","busModel");
		$this->load->model("User_model","userModel");
	}

	public function index()
	{
		$this->headerTitle("Route Perjalanan","list panel");
		$breadcrumbs = array("Route" => $this->router->class);
		$this->breadcrumbs($breadcrumbs);

		$userData = $this->userModel->getById($this->user->id);
		$content = array(
				"jamkeberangkatan"	=>	$this->jamModel->getAll(false,false,array("jam ASC")),
				"bus_list"			=>	$this->busModel->getAll(false,false,array("nama_supir ASC")),
				"shuttle"			=>	$this->shuttleModel->getAll(false,false,array("nama ASC")),
				"name_shuttle"		=>	$this->shuttleModel->getById($userData->shuttleid)->nama,
			);
		$this->viewContent($content);

		$this->view();
	}


	public function ajax_list_admin($userId)
	{
		self::ajax_list($userId,"btnUpdateAdmin");
	}

	public function ajax_list($userId = "",$onclick = "btnUpdate")
	{
		if ($this->isPost()) {
			if ($userId == "" || $userId == null) {
				$where = false;
			} else {
				$userData = $this->userModel->getById($userId);
				$shuttleData = $this->shuttleModel->getById($userData->shuttleid);
				$where = array("asal_shuttle" => $shuttleData->nama);
			}
			
			$select = array("jam","no_polisi","nama_supir","bus_route.*","bus_route.id As id_route");
			$columns = array(null,"asal_shuttle","kota_asal","tujuan_shuttle","kota_tujuan","jam","no_polisi","nama_supir","harga_tiket");
			$search = array(
					"asal_shuttle"	=>	$this->input->post("search")["value"],
					"kota_asal"		=>	$this->input->post("search")["value"],
					"tujuan_shuttle"=>	$this->input->post("search")["value"],
					"kota_tujuan"	=>	$this->input->post("search")["value"],
					"jam"			=>	$this->input->post("search")["value"],
					"no_polisi"		=>	$this->input->post("search")["value"],
					"nama_supir"	=>	$this->input->post("search")["value"],
					"harga_tiket"	=>	$this->input->post("search")["value"],
				);
			$join = array(
					array("jamkeberangkatan","jamkeberangkatan.id = bus_route.jam_id","LEFT"),
					array("bus","bus.id = bus_route.bus_id","LEFT"),
				);


			$result = $this->routeModel->findDataTable($where,$select,$columns,$search,$join);
			$data = array();
			$no = $this->input->post("start");
			foreach ($result as $item) {
				$no++;
				$btnUpdate = '<button type="button" onclick="'.$onclick.'('.$item->id_route.')" class="btn btn-warning btn-xs btn-flat" title="Edit Route"><i class="fa fa-edit"></i></button>';
				$btnDelete = '<button type="button" onclick="btnDelete('.$item->id_route.')" class="btn btn-danger btn-xs btn-flat" title="Hapus Route"><i class="fa fa-trash-o"></i></button>';

				$asalShuttle = $this->shuttleModel->getByWhere(array("nama" => $item->asal_shuttle));
				$tujuanShuttle = $this->shuttleModel->getByWhere(array("nama" => $item->tujuan_shuttle));
				$labelAsal = "<strike style='color:red;'>".$item->asal_shuttle."</strike> <br> <i>sudah tidak ada</i>";
				$labelTujuan = "<strike style='color:red;'>".$item->tujuan_shuttle."</strike> <br> <i>sudah tidak ada</i>";

				$row = array();
				$row[] = $no;
				$row[] = $asalShuttle == null ? $labelAsal : $item->asal_shuttle;
				$row[] = "<b><i>".$item->kota_asal."</i></b>";
				$row[] = $tujuanShuttle == null ? $labelTujuan : $item->tujuan_shuttle;
				$row[] = "<b><i>".$item->kota_tujuan."</i></b>";
				$row[] = "Keberangkatan <b>".$item->jam."</b>";
				$row[] = $item->no_polisi;
				$row[] = $item->nama_supir;
				$row[] = "Rp.".number_format($item->harga_tiket,0,",",".");
			
				if (parent::user_role() == "admin") {
					$row[] = $btnUpdate."&nbsp;&nbsp;".$btnDelete;
				}
				
				$data[] = $row;
			}

			return $this->routeModel->findDataTableOutput($data,$where,$search,$join);
		}
	}

	public function checkRouteTujuan()	// for drop down asal shuttle
	{
		if ($this->isPost()) {
			$asal = $this->input->post("asal");
			$where = array(
					"asal_shuttle"	=>	$asal,
				);
			$select = array("tujuan_shuttle"); // for select and group By
			$checkRouteTujuan = $this->routeModel->getAll($where,$select,array("tujuan_shuttle ASC"),false,false,$select);
			if ($checkRouteTujuan) {
				$this->response->status = true;
				$this->response->message = "<span class='text-green'>ada ".count($checkRouteTujuan)."</span>";
				$this->response->data = $checkRouteTujuan;
			} else {
				$this->response->message = "<span style='color:red;'>Tidak ada tujuan, Silahkan check table route perjalanan.!</span>";
			}
			
			return $this->json();
		}
	}

	public function checkRouteJam()		// for drop down tujuan shuttle
	{
		if ($this->isPost()) {
			$asal = $this->input->post("asal");
			$tujuan = $this->input->post("tujuan");
			$where = array(
					"asal_shuttle"	=>	$asal,
					"tujuan_shuttle"	=>	$tujuan,
				);

			$join = array(
					array("bus","bus.id = bus_route.bus_id","LEFT"),
				);	
			$checkRouteJam = $this->routeModel->getAll($where,false,false,$join,false,array("jam_id"));
			if (!empty($checkRouteJam)) {
				$jam_id = array();
				foreach ($checkRouteJam as $val) {
					$jam_id[] = intval($val->jam_id);
				}

				$tanggal = $this->input->post("tanggal");
				if($tanggal == date("Y-m-d")) {
					$whereJam = array("jam >" => date("H:i"));
				} else if ($tanggal > date("Y-m-d") || $tanggal < date("Y-m-d")) {
					$whereJam = false;
				}
				
				$whereIn = array("id" => $jam_id);
				$jamkeberangkatan = $this->jamModel->getAll($whereJam,false,false,false,$whereIn);
		
				$this->response->status = true;
				$this->response->message = "<span class='text-green'>ada ".count($jamkeberangkatan)."</span>";
				$this->response->data = $jamkeberangkatan;
			} else {
				$this->response->message = "<span style='color:red;'>Tidak ada jam Keberangkatan, Silahkan check table route perjalanan.!.</span>";
			}
		
			return parent::json();
		}
	}

	public function checkRouteBus()		// for drop down jam keberangkatan and tanggal pemesanan
	{
		if ($this->isPost()) {
			$asal = $this->input->post("asal");
			$tujuan = $this->input->post("tujuan");
			$jam = $this->input->post("jamkeberangkatan");
			$bus = $this->input->post("bus");
			$where = array(
					"asal_shuttle"	=>	$asal,
					"tujuan_shuttle"	=>	$tujuan,
					"jam_id"		=>	$jam,
				);
			$select = array("bus_route.id AS route_id","bus_id","no_polisi","nama_supir","jumlah_kursi","harga_tiket");
			if ($bus != "") {
				$where["bus_id"] = $bus;
			}
		
			$join = array(
					array("bus","bus.id = bus_route.bus_id","LEFT"),
				);	

			$checkRouteBus = $this->routeModel->getAll($where,$select,false,$join);
			foreach ($checkRouteBus as $val) {
				$val->harga_tiket_int = intval($val->harga_tiket);
				$val->harga_tiket = "Rp.".number_format($val->harga_tiket,0,",",".");
			}
			
			if ($checkRouteBus) {
				$this->response->status = true;
				$this->response->message = "<span class='text-green'>ada ".count($checkRouteBus)."</span>";
				$this->response->data = $checkRouteBus;
			} else {
				$this->response->message = "<span style='color:red;'>Tidak ada bus.</span>";
			}	
			
			return parent::json();
		}
	}

	public function setRules()
	{
		$this->form_validation->set_rules("jam","Jam Keberangkatan","required");
		$this->form_validation->set_rules("bus","Bus","required");
		$this->form_validation->set_rules("asal_shuttle","Asal Shuttle","required");
		$this->form_validation->set_rules("tujuan_shuttle","Tujuan Shuttle","required");
		$this->form_validation->set_rules("harga_tiket","Harga Tiket","required");
		$this->form_validation->set_message("required","%s harus di isi");
	}

	public function errorForm()
	{
		$this->response->error = array(
						"jam"			=>	form_error("jam","<span style='color:red;'>","</span>"),
						"bus"			=>	form_error("bus","<span style='color:red;'>","</span>"),
						"asal_shuttle"	=>	form_error("asal_shuttle","<span style='color:red;'>","</span>"),
						"tujuan_shuttle"=>	form_error("tujuan_shuttle","<span style='color:red;'>","</span>"),
						"harga_tiket"	=>	form_error("harga_tiket","<span style='color:red;'>","</span>"),
					);
	}

	public function tujuanShuttle($adminId = "") // for add and update ajax
	{
		if ($this->isPost()) {
			if ($adminId != "") {
				$userData = $this->userModel->getById($adminId);
				$checkShuttle = $this->shuttleModel->getById($userData->shuttleid);
				$shuttleAdmin = $this->shuttleModel->getAll(array("kota !=" => $checkShuttle->kota),false,array("nama ASC"));
				if ($shuttleAdmin) {
					$this->response->status = true;
					$this->response->data = $shuttleAdmin;
				} else {
					$this->response->message = "<span style='color:red;'>Opps, Data shuttle Kosong.</span>";
				}
			} else {
				$shuttleAll = $this->shuttleModel->getAll(false,false,array("nama ASC"));
				if ($shuttleAll) {
					$this->response->status = true;
					$this->response->data = $shuttleAll;
				} else {
					$this->response->message = "<span style='color:red;'>Opps, Data shuttle Kosong.</span>";
				}
			}
			return parent::json();
		}
	}

	public function add()
	{
		if ($this->isPost()) {
			self::setRules();
			if ($this->form_validation->run() == true) {
				$jam = $this->input->post("jam");
				$bus = $this->input->post("bus");
				$asal_shuttle = $this->input->post("asal_shuttle");
				$tujuan_shuttle = $this->input->post("tujuan_shuttle");
				$harga_tiket = $this->input->post("harga_tiket");

				$getAsalShuttle = $this->shuttleModel->getByWhere(array("nama" => $asal_shuttle));
				$getTujuanShuttle = $this->shuttleModel->getByWhere(array("nama" => $tujuan_shuttle));

				$data = array(
						"jam_id"		=>	$jam,
						"bus_id"		=>	$bus,
						"asal_shuttle"	=>	$getAsalShuttle->nama,
						"kota_asal"		=>	$getAsalShuttle->kota,
					);
				$select = array("jam","no_polisi","nama_supir","bus_route.*","bus_route.id As id_route");
				$join = array(
						array("jamkeberangkatan","jamkeberangkatan.id = bus_route.jam_id","LEFT"),
						array("bus","bus.id = bus_route.bus_id","LEFT"),
					);

				$data["tujuan_shuttle"] =	$getTujuanShuttle->nama;
				$data["kota_tujuan"]	=	$getTujuanShuttle->kota;
				$checkBus = $this->routeModel->getByWhere($data,$select,$join);
				if ($checkBus) {
					$pesan = "Bus dengan No polisi=<b>".$checkBus->no_polisi."</b> Nama Supir=<b>".$checkBus->nama_supir."<b><br>";
					$pesan .= " Jam Keberangkatan=<b>".$checkBus->jam."<b> <br>";
					$pesan .= " Asal Shuttle=<b>".$checkBus->asal_shuttle."</b> [".$checkBus->kota_asal."] <br>";
					$pesan .= " Tujuan Shuttle=<b>".$checkBus->tujuan_shuttle."</b> [".$checkBus->kota_tujuan."] <br>";
					$pesan .= " Sudah Terdaftar. Silahkan pilih Bus yang lain..!!";
					$this->response->errorCheckBus = alertDanger($pesan);
				} else {
					$dataBus = array(
						"jam_id"		=>	$jam,
						"bus_id"		=>	$bus,
						"asal_shuttle"	=>	$getAsalShuttle->nama,
						"kota_asal"		=>	$getAsalShuttle->kota,
					);
					$validateBus = $this->routeModel->getByWhere($dataBus,$select,$join);
					if ($validateBus) {
						$pesan = "Opps, ada error <br>";
						$pesan .= "Bus dengan No polisi=<b>".$validateBus->no_polisi."</b> Nama Supir=<b>".$validateBus->nama_supir."<b><br>";
						$pesan .= " Asal Shuttle=<b>".$validateBus->asal_shuttle."</b> [".$validateBus->kota_asal."] <br>";
						$pesan .= "Dan Jam keberangkatan <b>".$validateBus->jam."</b> <br>";
						$pesan .= " sudah ada di Tujuan Shuttle <b>".$validateBus->tujuan_shuttle."</b> [".$validateBus->kota_tujuan."] <br>";
						$pesan .= "silahkan pilih tujuan shuttle atau jam keberangkatan yang lain nya.";
						$this->response->errorBentrok = alertDanger($pesan);
					} else {
						$data["harga_tiket"]	=	$harga_tiket;
						$insert = $this->routeModel->insert($data);
						if ($insert) {
							$this->response->status = true;
							$this->response->message = alertSuccess("Tambah Route Perjalanan berhasil..!");
						}
					}

				}
			} else {
				self::errorForm();
			}

			return parent::json();
		}	
	}

	public function getById($id)
	{
		if ($this->isPost()) {
			$select = array("jam","no_polisi","nama_supir","bus_route.*","bus_route.id As id_route");
			$join = array(
					array("jamkeberangkatan","jamkeberangkatan.id = bus_route.jam_id","LEFT"),
					array("bus","bus.id = bus_route.bus_id","LEFT"),
				);
			$result = $this->routeModel->getByWhere(array("bus_route.id" => $id),$select,$join);
			if ($result) {
				$this->response->status = true;
				$this->response->data = $result;
			} else {
				$this->response->message = alertDanger("Data yang di pilih tidak ada..!!");
			}
			return parent::json();
		}
	}

	public function update($id)
	{
		if ($this->isPost()) {
			self::setRules();
			if ($this->form_validation->run() == true) {
				$jam = $this->input->post("jam");
				$bus = $this->input->post("bus");
				$asal_shuttle = $this->input->post("asal_shuttle");
				$tujuan_shuttle = $this->input->post("tujuan_shuttle");
				$harga_tiket = $this->input->post("harga_tiket");

				$getAsalShuttle = $this->shuttleModel->getByWhere(array("nama" => $asal_shuttle));
				$getTujuanShuttle = $this->shuttleModel->getByWhere(array("nama" => $tujuan_shuttle));

				$data = array(
						"id"			=>	$id,
						"jam_id"		=>	$jam,
						"bus_id"		=>	$bus,
						"asal_shuttle"	=>	$getAsalShuttle->nama,
						"kota_asal"		=>	$getAsalShuttle->kota,
						"tujuan_shuttle"=>	$getTujuanShuttle->nama,
						"kota_tujuan"	=>	$getTujuanShuttle->kota,
						"harga_tiket"	=>	$harga_tiket,
					);

				$update = $this->routeModel->update($data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Update Route Perjalanan berhasil..!");
				}	
			} else {
				self::errorForm();
			}

			return parent::json();
		}
	}

	public function delete($id)
	{
		if ($this->isPost()) {
			$delete = $this->routeModel->delete($id);
			if ($delete) {
				$this->response->status = true;
				$this->response->message = alertSuccess("Delete Route Perjalanan berhasil..!");
			} else {
				$this->response->message = alertDanger("Opps, Data yang anda delete sudah tidak ada..!!");
			}
			return parent::json();
		}
	}
}