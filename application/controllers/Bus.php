<?php 
/**
* 
*/
class Bus extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("Bus_model","busModel");
	}

	public function index()
	{
		$this->headerTitle("Bus","list Table");
		$breadcrumbs = array("Bus" => site_url("bus"));
		$this->breadcrumbs($breadcrumbs);

		parent::view();
	}

	public function ajax_list()
	{
		if ($this->isPost()) {
			$columns = array(null,"no_polisi","nama_supir","nama_kenek","jumlah_kursi");
			$search = array(
					"no_polisi"		=>	$this->input->post("search")["value"],
					"nama_supir"	=>	$this->input->post("search")["value"],
					"nama_kenek"	=>	$this->input->post("search")["value"],
					"jumlah_kursi"	=>	$this->input->post("search")["value"],
				);

			return $this->busModel->findDataTableObject(false,false,$columns,$search);
		}
	}

	public function setRules()
	{
		$this->form_validation->set_rules("no_polisi","Nomor Polisi","trim|required|max_length[10]");
		$this->form_validation->set_rules("nama_supir","Nama Supir","trim|required");
		$this->form_validation->set_rules("nama_kenek","Nama Kenek","trim|required");
		// $this->form_validation->set_rules("jumlah_kursi","Jumlah Kursi","trim|required");
		$this->form_validation->set_message("required","%s Harus di isi.!");
	}

	public function setError()
	{
		$this->response->error = array(
						"no_polisi"		=>	form_error("no_polisi","<span style='color:red'>","</span>"),
						"nama_supir"	=>	form_error("nama_supir","<span style='color:red'>","</span>"),
						"nama_kenek"	=>	form_error("nama_kenek","<span style='color:red'>","</span>"),
						// "jumlah_kursi"	=>	form_error("jumlah_kursi","<span style='color:red'>","</span>"),
					);
	}

	public function add()
	{
		if ($this->isPost()) {
			self::setRules();
			$inputNoPolisi = strtoupper($this->input->post("no_polisi"));
			$noPolisi = $this->busModel->getByWhere(array("no_polisi" => $inputNoPolisi));
			if ($noPolisi == null) {
				if ($this->form_validation->run() == true) {
					$data = array(
							"no_polisi"		=>	$inputNoPolisi,
							"nama_supir"	=>	$this->input->post("nama_supir"),
							"nama_kenek"	=>	$this->input->post("nama_kenek"),
							"jumlah_kursi"	=>	12,
						);
					$insert = $this->busModel->insert($data);
					if ($insert) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil di Tambah.");
					} else {
						$this->response->message = alertDanger("Opss, ada masalah ketika di Tambah.");
					}
				} else {
					self::setError();
				}
			} else {
				$this->response->message = alertDanger("Nomor Polisi ".$inputNoPolisi." sudah terdaftar.!");
			}
			return $this->json();
		}
	}

	public function getbyid($id)
	{
		if ($this->isPost()) {
			$getById = $this->busModel->getById($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Opss, data tidak ada.");
			}
			return $this->json();
		}
	}

	public function update()
	{
		if ($this->isPost()) {
			self::setRules();
			if ($this->form_validation->run() == true) {
				$data = array(
						"id"			=>	$this->input->post("id"),
						"no_polisi"		=>	strtoupper($this->input->post("no_polisi")),
						"nama_supir"	=>	$this->input->post("nama_supir"),
						"nama_kenek"	=>	$this->input->post("nama_kenek"),
						"jumlah_kursi"	=>	12,
					);
				$update = $this->busModel->update($data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil di update.");
				} else {
					$this->response->message = alertDanger("Opss, ada masalah ketika di update.");
				}
			} else {
				self::setError();
			}
			return $this->json();
		}
	}

	public function delete($id)
	{
		if ($this->isPost()) {
			$delete = $this->busModel->delete($id);
			if ($delete) {
				$this->response->status = true;
				$this->response->message = alertSuccess("Berhasil di Hapus.");
			} else {
				$this->response->message = alertDanger("Opss, ada masalah ketika di Hapus.");
			}
			return $this->json();
		}
	}
}