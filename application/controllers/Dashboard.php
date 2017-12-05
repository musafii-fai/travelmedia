<?php
/**
* 
*/
class Dashboard extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("User_model");
	}

	public function index()
	{
		$this->load->model("Jam_model");
		$this->load->model("Shuttle_model");

		parent::headerTitle("Dashboard","Control Panel");

		$content = array(
						"jam_count"		=>	$this->Jam_model->getCount(),
						"shuttle_count" =>	$this->Shuttle_model->getCount(),
						"user_count"	=>	$this->User_model->getCount(),
					);
		parent::viewContent($content);
		parent::view();
	}

	public function chartPemesanan($tahun=false)
	{
		if ($tahun == false) {
			$tahun = date("Y");
		}
		$this->load->model("Pemesanan_model","pemesananModel");
		if ($this->isPost()) {
			$formatMonth = "DATE_FORMAT(tanggal_input,'%M')";
			$where = array(
						"DATE_FORMAT(tanggal_input,'%Y')" => $tahun,
					);
			$select = array(
						"SUM($formatMonth = 'January') AS Januari",
						"SUM($formatMonth = 'February') AS Februari",
						"SUM($formatMonth = 'March') AS Maret",
						"SUM($formatMonth = 'April') AS April",
						"SUM($formatMonth = 'May') AS Mei",
						"SUM($formatMonth = 'June') AS Juni", 
						"SUM($formatMonth = 'July') AS Juli", 
						"SUM($formatMonth = 'August') AS Agustus", 
						"SUM($formatMonth = 'September') AS September", 
						"SUM($formatMonth = 'October') AS Oktober", 
						"SUM($formatMonth = 'November') AS Nopember", 
						"SUM($formatMonth = 'December') AS Desember"
					);
			$pemesanan = $this->pemesananModel->getAll($where,$select);
			if ($pemesanan) {
				$data = null;
				foreach ($pemesanan as $key => $val) {
					$row = array();
					$row[] = [ "Januari", $val->Januari ];
					$row[] = [ "Februari", $val->Februari ];
					$row[] = [ "Maret", $val->Maret ];
					$row[] = [ "April", $val->April ];
					$row[] = [ "Mei", $val->Mei ];
					$row[] = [ "Juni", $val->Juni ];
					$row[] = [ "Juli", $val->Juli ];
					$row[] = [ "Agustus", $val->Agustus ];
					$row[] = [ "September", $val->September ];
					$row[] = [ "Oktober", $val->Oktober ];
					$row[] = [ "Nopember", $val->Nopember ];
					$row[] = [ "Desember", $val->Desember ];
					$data = $row;
				}
				$this->response->status = true;
				$this->response->data = $data;
				$this->response->color = "#3c8dbc";
				$this->response->count = $this->pemesananModel->getCount();
			} else {
				$this->response->message = alertDanger("Data not found.!");
			}

			return parent::json();
		}
	}

	public function update()
	{
		if ($this->isPost()) {
			$this->form_validation->set_rules("harga","Harga Tiket","required");
			$this->form_validation->set_message("required","%s harus di isi.!");
			if ($this->form_validation->run() == true) {
				$data = array(
						"id"	=>	1,
						"harga"	=>	$this->input->post("harga"),
					);
				$update = $this->hargaModel->update($data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = "<code>Berhasil di update.</code>";
				}
			} else {
				$this->response->message = form_error("harga","<span style='color:red;'>","</span>");
			}
			return parent::json();
		}
	}

	public function lockscreen()
	{
		$this->load->model("User_model");
		$user = $this->User_model->getById($this->user->id);

		$src_image = $user->photo == "" ? base_url('assets/image/user_image.png') : base_url('uploads/').$user->photo;

		parent::viewContent(array("src_image" => $src_image));
		parent::view(false,false);
	}

	public function lockedPhoto()
	{
		if ($this->isPost()) {
			$this->load->model("User_model");
			$user = $this->User_model->getById($this->user->id);
			if ($user) {
				$this->response->status = true;
				$this->response->data = array(
						"id"			=>	$user->id,
						"nama_locked"	=>	$user->nama,
						"photo_locked"	=>	$user->photo,
					);
			} else {
				$this->response->message = '<span style="color:red">Data not found.!</span>';
			}

			return $this->json();
		}
	}

	public function locked()
	{
		if ($this->isPost()) {
			$data = array(
					"id"	=>	$this->user->id,
					"locked"	=>	0,
				);
			$update = $this->User_model->update($data);
			if ($update) {
				$this->response->status = true;
				$this->response->message = "Terkunci";
			}
			return parent::json();
		}
	}

	public function unLocked()
	{
		if ($this->isPost()) {
			$this->form_validation->set_rules("password","Password","required");
			$this->form_validation->set_message("required","%s harus di isi.!");
			if ($this->form_validation->run() == true) {
				$locked = $this->User_model->getById($this->user->id);
				$password = $this->input->post("password");
				if (md5($password) == $locked->password) {
					$data = array(
							"id"	=>	$this->user->id,
							"locked"	=>	1,
						);
					$update = $this->User_model->update($data);
					if ($update) {
						$this->response->status = true;
						$this->response->message = "Kunci terbuka";
					}
				} else {
					$this->response->message = '<span style="color:red">Opps, Password nya salah..!</span>';
				}
			} else {
				$this->response->message = form_error("password","<span style='color:red;'>","<span>");
			}
				
			return parent::json();
		}
	}

	public function logout_Locked()
	{
		$this->load->model("User_model");
		if ($this->isPost()) {
			$checkUser = $this->User_model->getById($this->user->id);
			if ($checkUser) {
				$this->response->status = true;
				$this->response->message = "logout locked";

				$data = array(
							"id"		=>	$this->user->id,
							"locked"	=>	1,
						);
				$this->User_model->update($data);
				$this->session->unset_userdata("admin");
				$this->session->sess_destroy();
			} else {
				$this->response->message = "Data not found.";
			}
			return parent::json();
		}
	}
}