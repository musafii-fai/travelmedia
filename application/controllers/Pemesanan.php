<?php 
/**
* 
*/
class Pemesanan extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("Pemesanan_model","pemesananModel");
		$this->load->model("Shuttle_model","shuttleModel");
		$this->load->model("Jam_model","jamModel");
		$this->load->model("User_model","userModel");
		$this->load->model("Traveller_model","travellerModel");
	}

	public function index()
	{
		$this->headerTitle("Pemesanan","list");
		$breadcrumbs = array("Pemesanan" => site_url("pemesanan"));
		$this->breadcrumbs($breadcrumbs);
		$user = $this->userModel->getById($this->user->id);
		$data = array(
					"shuttle_admin" => $this->shuttleModel->getById($user->shuttleid)
				);
		$this->viewContent($data);

		$this->view();
	}

	public function tujuan($kota = "")
	{
		$content = array();
		if (isset($kota)) {
			$this->headerTitle("Pemesanan","Tujuan ke ".ucfirst($kota));
			$breadcrumbs = array(
									"pemesanan" => site_url("pemesanan"),
									"tujuan" => " ",
								);
			$this->breadcrumbs($breadcrumbs);

			$user = $this->userModel->getById($this->user->id);
			$shuttle_admin =$this->shuttleModel->getById($user->shuttleid);
			if ($this->user_role() != "admin") {
				if ($kota == strtolower($shuttle_admin->kota)) {
					$kotaTujuan = $shuttle_admin->kota == "Medan" ? "Riau" : "Medan";
					$content["forbiddenTujuan"] = "Anda tidak mempunyai hak untuk pemesanan yang beda dengan kota asal shuttle anda.!<br>
					Pemesanan anda hanya boleh di <u>".$shuttle_admin->kota."</u> tujuan <u>".$kotaTujuan."</u>
					<br>Silahkan kembali ke pemesanan sesuai dengan kota shuttle anda..!";
				}
			}

			if ($kota == "medan") {
				$content["keberangkatan"] = $this->shuttleModel->getAll(array("kota" => "riau"));
				$content["asalText"]	=	$shuttle_admin->nama;
				$content["tujuan"]		  = $this->shuttleModel->getAll(array("kota" => "medan"));
			} else {
				$content["keberangkatan"] = $this->shuttleModel->getAll(array("kota" => "medan"));
				$content["asalText"]	=	$shuttle_admin->nama;
				$content["tujuan"]		  = $this->shuttleModel->getAll(array("kota" => "riau"));
			}

			if ($kota != "medan" && $kota != "riau") {	
				$content["tujuanSalah"] = "Tujuan Anda nyasar atau Tidak terdaftar..!!";
			}
			$content["jamkeberangkatan"] = $this->jamModel->getAll(/*array("jam >" => date("H:i"))*/false,false,array("jam ASC"));
		} else {
			$content["tujuanSalah"] = "Tujuan Anda nyasar atau Tidak terdaftar..!!";
		}

		$this->viewContent($content);

		$this->view();
	}

	public function jumlah_kursi()
	{
		$this->load->model("Bus_model","busModel");
		if ($this->isPost()) {
			$asal = $this->input->post("asal");
			$tujuan = $this->input->post("tujuan");
			$tanggal = $this->input->post("tanggal");
			$jamkeberangkatan = $this->input->post("jamkeberangkatan");
			$bus = $this->input->post("bus");

			
			$dataBus = $this->busModel->getById($bus);

			$wherePemesanan = array(
					"asal_shuttle"	=>	$asal,
					"tujuan_shuttle"	=>	$tujuan,
					"tanggal_pemesanan"	=>	$tanggal,
					"jam_id"	=>	$jamkeberangkatan,
					"bus_id"	=>	$bus,
				);
			$join = array(
					array("bus_route","bus_route.id = transaksi_tiket.route_id","LEFT"),
					array("jamkeberangkatan","jamkeberangkatan.id = bus_route.jam_id","LEFT"),
					array("bus","bus.id = bus_route.bus_id","LEFT"),
					array("traveller","traveller.id = transaksi_tiket.traveller_id","LEFT"),
				);
			$jumlahKursiTerpesan = $this->pemesananModel->getCount($wherePemesanan,false,$join);
			$isiKursiSudahDipesan = $this->pemesananModel->getAll($wherePemesanan,array("nokursi","nama","harga_tiket"),false,$join);
			$rowKursi = array();
			foreach ($isiKursiSudahDipesan as $val) {
				$rowKursi[] = $val->nokursi;
			}

			$this->response->status = true;
			$this->response->data = array(
					"sisa_kursi" =>	intval($dataBus->jumlah_kursi) - $jumlahKursiTerpesan,
					"isi_kursi"	=>	$isiKursiSudahDipesan,
				);
			// $this->response->bus = $dataBus;
			$this->response->kursi = array(
					"pilih1"	=>	in_array("1", $rowKursi) ? "1" : "",
					"pilih2"	=>	in_array("2", $rowKursi) ? "2" : "",
					"pilih3"	=>	in_array("3", $rowKursi) ? "3" : "",
					"pilih4"	=>	in_array("4", $rowKursi) ? "4" : "",
					"pilih5"	=>	in_array("5", $rowKursi) ? "5" : "",
					"pilih6"	=>	in_array("6", $rowKursi) ? "6" : "",
					"pilih7"	=>	in_array("7", $rowKursi) ? "7" : "",
					"pilih8"	=>	in_array("8", $rowKursi) ? "8" : "",
					"pilih9"	=>	in_array("9", $rowKursi) ? "9" : "",
					"pilih10"	=>	in_array("10", $rowKursi) ? "10" : "",
					"pilih11"	=>	in_array("11", $rowKursi) ? "11" : "",
					"pilih12"	=>	in_array("12", $rowKursi) ? "12" : "",
				);
			return $this->json();
		}
	}

	function kotaShuttle($where = false)	// for prosess_ajax method
	{
		return $this->shuttleModel->getByWhere($where);
	}

	public function prosess_ajax()
	{
		if ($this->isPost()) {
			$this->load->model("Perjalanan_model","perjalananModel");
			$this->load->model("Route_model","routeModel");
			$this->load->model("History_model","historyModel");

			$this->form_validation->set_rules("tanggal","Tanggal","required");
			$this->form_validation->set_rules("asal","Keberangkatan atau asal shuttle","required");
			$this->form_validation->set_rules("tujuan","Tujuan","required");
			$this->form_validation->set_rules("jamkeberangkatan","Jam Keberangkatan","required");
			$this->form_validation->set_rules("bus","Bus","required");
			$this->form_validation->set_rules("nohp","No Hp","trim|required");

			$nama = $this->input->post("nama");
			if (isset($nama)) {
				$no = 0;
				foreach ($nama as $nm) {
					$this->form_validation->set_rules("nama[".$no."]","Nama ".($no + 1),"required");
					$this->form_validation->set_rules("jenis_kelamin[".$no."]","Jenis Kelamin ".($no + 1),"required");
					$this->form_validation->set_rules("umur[".$no."]","Umur ".($no + 1),"required");
					$no++;
				}
			}

			$this->form_validation->set_message("required","%s Harus di isi.!");

			$asal = $this->input->post("asal");
			$tujuan = $this->input->post("tujuan");
			$tanggal = $this->input->post("tanggal");
			$jamkeberangkatan = $this->input->post("jamkeberangkatan");
			$bus = $this->input->post("bus");

			$jk = $this->input->post("jenis_kelamin");
			$umur = $this->input->post("umur");
			$kursi = $this->input->post("kursi");

			if ($this->form_validation->run() == true) {
				if (!isset($nama)) {
					$this->response->message = "jumlah_orang";
					$this->response->errorJumlah = alertDanger("Jumlah orang belum ada, silahkan klik tombol tambah jumlah orang");
				} else {
					if (count($kursi) != count($nama)) {
						$pesan = "Jumlah kursi tidak sama dengan jumlah penumpang, silahkan klik salah satu gambar kursi.!";
						$this->response->message = alertDanger($pesan);
						$this->response->errorKursi = "<span style='color: red;'>".$pesan."</span>";
					} else {
						if ($tanggal < date("Y-m-d")) {
							$this->response->message = "<span style='color: red;'>Tanggal expired.! </span>";
							$this->response->errorTanggalPemesanan = alertDanger("Tanggal pemesanan sudah lewat,<br> Silahkan pilih tanggal hari ini atau tanggal yang kedepan nya.!");
						} else {
							$whereRoute = array(
									"asal_shuttle"	=>	$asal,
									"tujuan_shuttle"	=>	$tujuan,
									"jam_id"	=>	$jamkeberangkatan,
									"bus_id"	=>	$bus,
								);
							$selectRoute = array("bus_route.id AS route_id","harga_tiket","jam","no_polisi","nama_supir");
							$joinRoute = array(
								array("jamkeberangkatan","jamkeberangkatan.id = bus_route.jam_id","LEFT"),
								array("bus","bus.id = bus_route.bus_id","LEFT"),
							);
							// check data bus_route atau route perjalanan
							$routeData = $this->routeModel->getByWhere($whereRoute,$selectRoute,$joinRoute);

							$dataTransaksi = array();
							$dataTransaksi["user_id"] = $this->user->id; // harus di ganti dengan id user yang login
							$dataTransaksi["tanggal_pemesanan"] = $tanggal;
							$dataTransaksi["route_id"] = $routeData->route_id;
							$dataTransaksi["harga"] = $routeData->harga_tiket;

							$dataPerjalanan = array(
									"asal_shuttle"	=>	$asal,
									"kota_asal"		=>	self::kotaShuttle(array("nama" => $asal))->kota,
									"tujuan_shuttle"=>	$tujuan,
									"kota_tujuan"	=>	self::kotaShuttle(array("nama" => $tujuan))->kota,
									"tanggal_pemesanan"	=>	$tanggal,
									"jam_keberangkatan"	=>	$routeData->jam,
									"harga_tiket"	=>	$routeData->harga_tiket,
									"bus_no_polisi"	=>	$routeData->no_polisi,
									"bus_nama_supir"	=>	$routeData->nama_supir,
								);
							$perjalanan_id = $this->perjalananModel->insert($dataPerjalanan); // from perjalanan table
							
							$transaksi_tiket_id = array();
							for($i = 0; $i < count($nama); $i++) {
								$dataTransaksi["nokursi"] = $kursi[$i];
								$dataTraveller = array(
										"nama" => $nama[$i],
										"jenis_kelamin" => $jk[$i],
										"umur"	=>	$umur[$i],
										"no_hp"	=>	$this->input->post("nohp"),
										"perjalanan_id" => $perjalanan_id,
									);
								$traveller_id = $this->travellerModel->insert($dataTraveller); // from traveller table
								$dataTransaksi["traveller_id"] = $traveller_id;
								$transaksi_id = $this->pemesananModel->insert($dataTransaksi); // from transaksi_tiket table
								$transaksi_tiket_id[] = $transaksi_id;
							}
							$dataHistory = array(
									"user_id"		=>	$this->user->id,
									"perjalanan_id" => $perjalanan_id,
									"transaksi_tiket_id" => implode(" ", $transaksi_tiket_id),
								);
							$idHistory = $this->historyModel->insert($dataHistory); // from history table
							$this->response->status = true;
							$this->response->message = alertSuccess("Prosses pemesanan berhasil.");
							$this->response->id_history = $idHistory;
						}	
					}
				}
			} else {
				$this->response->error = array(
						"tanggal" => form_error("tanggal","<span style='color:red;'>","<span>"),
						"asal" => form_error("asal","<span style='color:red;'>","<span>"),
						"tujuan" => form_error("tujuan","<span style='color:red;'>","<span>"),
						"jamkeberangkatan" => form_error("jamkeberangkatan","<span style='color:red;'>","<span>"),
						"bus" => form_error("bus","<span style='color:red;'>","<span>"),
						"nohp" => form_error("nohp","<span style='color:red;'>","<span>"),
					);
				
				if (!isset($nama)) {
					$this->response->errorPenumpang = alertDanger("Jumlah orang belum ada, silahkan klik tombol tambah jumlah orang");
				} else {
					$this->response->errorPenumpang = alertDanger(validation_errors());
				}

				if (!isset($kursi)) {
					$pesan = "Kursi belum di pilih, silahkan klik salah satu gambar kursi.!";
					$this->response->errorKursi = "<span style='color: red;'>".$pesan."</span>";
				}
				
			}

			return $this->json();
		}
	}

	public function ajax_list_admin()
	{
		return self::ajax_list($this->user->id);
	}

	public function ajax_list($userId = "")
	{
		if ($this->isPost()) {
			$select = array(
							"transaksi_tiket.id AS id_pemesanan","transaksi_tiket.harga","transaksi_tiket.tanggal_pemesanan","users.nama as admin","jam",
							"asal_shuttle","tujuan_shuttle","nokursi","traveller.nama as nama_traveller","jenis_kelamin",
							"umur","no_hp",
						);
			$join = array(
					array("users","users.id = transaksi_tiket.user_id","LEFT"),
					array("bus_route","bus_route.id = transaksi_tiket.route_id","LEFT"),
					array("jamkeberangkatan","jamkeberangkatan.id = bus_route.jam_id"),
					array("traveller","traveller.id = transaksi_tiket.traveller_id","LEFT"),	
				);
			$columns = array(null,"users.nama","tanggal_pemesanan","jam","asal_shuttle","tujuan_shuttle","nokursi","traveller.nama","harga",null);

			$search = array(
					"users.nama" 		=> 	$this->input->post("search")["value"],
					"tanggal_pemesanan"	=> 	$this->input->post("search")["value"],
					"jam"	 			=> 	$this->input->post("search")["value"],
					"asal_shuttle"		=> 	$this->input->post("search")["value"],
					"tujuan_shuttle"	=> 	$this->input->post("search")["value"],
					"nokursi" 			=> 	$this->input->post("search")["value"],
					"traveller.nama" 	=> 	$this->input->post("search")["value"],
					"harga"			 	=> 	$this->input->post("search")["value"],
				);
			$where = $userId != "" ? array("user_id" => $userId) : false;
			$result = $this->pemesananModel->findDataTable($where,$select,$columns,$search,$join);

			$data = array();
			$no = $this->input->post("start");
			foreach ($result as $item) {
				$no++;
				$item->no = $no;
				$item->tanggal_pemesanan = date_ind("l, d F Y", $item->tanggal_pemesanan);
				$item->jam = "Keberangkatan <b>".$item->jam."</b>";
				$item->harga = "Rp.".number_format($item->harga,0,',','.');
				$data[] = $item;
			}
			return $this->pemesananModel->findDataTableOutput($data,$where,$search,$join);
		}
	}

	public function detail($id)
	{
		if ($this->isPost()) {
			$where = array("transaksi_tiket.id" => $id);
			$select = array(
							"transaksi_tiket.*","users.nama as admin","bus_route.*","no_polisi","nama_supir","jam","nokursi","traveller.*","traveller.nama as nama_pemesan",
						);
			$join = array(
					array("users","users.id = transaksi_tiket.user_id","LEFT"),
					array("bus_route","bus_route.id = transaksi_tiket.route_id","LEFT"),
					array("bus","bus.id = bus_route.bus_id","LEFT"),
					array("jamkeberangkatan","jamkeberangkatan.id = bus_route.jam_id","LEFT"),
					array("traveller","traveller.id = transaksi_tiket.traveller_id","LEFT"),	
				);
			$detailResult = $this->pemesananModel->getByWhere($where,$select,$join);
			$detailResult->tanggal_pemesanan = date_ind("l, d F Y", $detailResult->tanggal_pemesanan);
			$detailResult->tanggal_input = date_ind("l, d F Y, H:i:s", $detailResult->tanggal_input);
			$detailResult->harga = "Rp.".number_format($detailResult->harga,0,",",".");

			if ($detailResult != null) {
				$this->response->status = true;
				$this->response->message = "Detail get by id";
				$this->response->data = $detailResult;
			} else {
				$this->response->message = alertDanger("Opps, Data Pemesanan yang anda pilih sudah tidak ada atau sudah di hapus..!<br> Silahkan klik tombol refresh..!");
			}
			return parent::json();
		}
	}

	public function delete($id)
	{
		if ($this->isPost()) {

			$pemesanan = $this->pemesananModel->getById($id);
			if ($pemesanan) {
				$this->response->status = true;
				$this->response->message = alertSuccess("Data berhasil di hapus..!");
				$this->pemesananModel->delete($pemesanan->id);
				$this->travellerModel->delete($pemesanan->traveller_id);
			} else {
				$this->response->message = alertDanger("Opps, Data Pemesanan yang anda pilih sudah tidak ada atau sudah di hapus..!<br> Silahkan klik tombol refresh..!");
			}
			return parent::json();
		}
	}

}