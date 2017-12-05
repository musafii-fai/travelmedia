<?php 
/**
* 
*/
class Traveller extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("Traveller_model","travellerModel");
		$this->load->model("Pemesanan_model","pemesananModel");
	}

	public function index()
	{
		$this->headerTitle("Traveller","list");
		$breadcrumbs = array( "Traveller" => "traveller" );
		$this->breadcrumbs($breadcrumbs);

		// $this->viewContent(array("user" => $this->user->id));

		$this->view();
	}

	public function getById($id)
	{
		if ($this->isPost()) {
			$getById = $this->travellerModel->getById($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "data row Traveller";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Opps, Data Traveller yang anda pilih sudah tidak ada atau sudah di hapus..!<br> Silahkan klik tombol refresh..!");
				$this->response->data = $getById;
			}
			return parent::json();
		}
	}

	public function update($id)
	{
		if ($this->isPost()) {
			$this->form_validation->set_rules("nama","Nama","required");
			$this->form_validation->set_rules("umur","Umur","required");
			$this->form_validation->set_rules("jenis_kelamin","Jenis kelamin","required");
			$this->form_validation->set_rules("no_hp","No hp","required");
			$this->form_validation->set_message("required","%s harus di isi.!");
			if ($this->form_validation->run() == true) {
				$data = array(
						"id"	=>	$id,
						"nama"	=> 	$this->input->post("nama"),
						"umur"	=>	$this->input->post("umur"),
						"jenis_kelamin"	=>	$this->input->post("jenis_kelamin"),
						"no_hp"	=>	$this->input->post("no_hp"),
					);
				$update = $this->travellerModel->update($data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data..!");
				} else {
					$this->response->message = alertDanger("Gagal update data..!");
				}
			} else {
				$this->response->error = array(
						"nama"	=>	form_error("nama","<span style='color:red'>","</span>"),
						"umur"	=>	form_error("umur","<span style='color:red'>","</span>"),
						"jenis_kelamin"	=>	form_error("jenis_kelamin","<span style='color:red'>","</span>"),
						"no_hp"	=>	form_error("no_hp","<span style='color:red'>","</span>"),
					);
			}
			return parent::json();
		}
	}

	public function ajax_list_admin()
	{
		return self::ajax_list($this->user->id);
	}

	public function ajax_list($where=false)
	{
		if ($where) {
			$where = array("users.id" => $where);
		} else {
			$where = false;
		}
		if ($this->isPost()) {
			$select = array("traveller_id","users.id AS id_user","users.nama AS admin",
							"traveller.tanggal_input","traveller.nama","umur","jenis_kelamin",
							"no_hp","jam_keberangkatan","asal_shuttle","tujuan_shuttle");
			$orderBy = array(null,"admin","tanggal_input","nama","umur","jenis_kelamin","no_hp","jam_keberangkatan","asal_shuttle","tujuan_shuttle");
			$search = array(
					"users.nama"	=>	$this->input->post("search")["value"],
					"traveller.nama"	=>	$this->input->post("search")["value"],
					"traveller.tanggal_input" =>	$this->input->post("search")["value"],
					"umur"	=>	$this->input->post("search")["value"],
					"jenis_kelamin"	=>	$this->input->post("search")["value"],
					"no_hp"	=>	$this->input->post("search")["value"],
					"jam_keberangkatan"	=>	$this->input->post("search")["value"],
					"asal_shuttle"	=>	$this->input->post("search")["value"],
					"tujuan_shuttle" =>	$this->input->post("search")["value"],
				);
			$join = array(
					array("users","users.id = transaksi_tiket.user_id","LEFT"),
					array("traveller","traveller.id = transaksi_tiket.traveller_id","LEFT"),
					array("perjalanan","perjalanan.id = traveller.perjalanan_id","LEFT"),
				);

			$resultData = $this->pemesananModel->findDataTable($where,$select,$orderBy,$search,$join);
			$data = array();
			$no = $this->input->post("start");
			foreach ($resultData as &$item) {	// for datatable object output
				$no++;
				$item->no = $no;
				$item->tanggal_input = date_ind("l, d F Y, H:i:s",$item->tanggal_input);
				$item->jam_keberangkatan = 'jam_keberangkatan <b>'.$item->jam_keberangkatan.'</b>';
				$item->jenis_kelamin = ucfirst($item->jenis_kelamin);
				$data[] = $item;
			}

			return $this->pemesananModel->findDataTableOutput($resultData,$where,$search,$join);
		}
	}

}