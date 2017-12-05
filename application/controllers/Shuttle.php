<?php 
/**
* 
*/
class Shuttle extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("Shuttle_model","shuttleModel");
	}

	public function index()
	{
		$this->headerTitle("Shuttle","List");
		$breadcrumbs = array("Shuttle" => site_url("shuttle"));
		$this->breadcrumbs($breadcrumbs);

		$this->view();
	}

	public function info()
	{
		if ($this->isPost()) {
			self::ajax_list();
		}
	}
	
	public function ajax_list()
	{
		if ($this->isPost()) {
			$columns = array(null,"nama","kota");
			$search = array(
					"nama" => $this->input->post("search")["value"],
					"kota" => $this->input->post("search")["value"]
				);
			$data = $this->shuttleModel->findDataTableObject(false,false,$columns,$search);
			return $data;	
		}
	}

	public function rulesValidate()
	{
		$this->form_validation->set_rules("nama","Nama",'trim|required|max_length[50]');
		$this->form_validation->set_message('required','%s harus diisi !');
		$this->form_validation->set_rules("kota","Kota Shuttle",'trim|required');
		$this->form_validation->set_message('required','%s harus di pilih ya !');
	}

	public function formErrorMessage()
	{
		$this->response->errorNama = form_error("nama","<span style='color:red'>","</span>");
		$this->response->errorKota = form_error("kota","<span style='color:red'>","</span>");
	}

	public function add()
	{
		if ($this->isPost()) {
			
			$this->form_validation->set_rules("nama","Nama",'trim|required|max_length[50]');
			$this->form_validation->set_message('required','%s harus diisi !');
			$this->form_validation->set_rules("kota","Kota Shuttle",'trim|required');

			$checkName = $this->shuttleModel->getByWhere(array("nama" => $this->input->post("nama")));
			if ($checkName == null) {
				if ($this->form_validation->run() == true) {
						$data = array(
							"nama" => $this->input->post("nama"),
							"kota" => $this->input->post("kota"),
						);
						$insert = $this->shuttleModel->insert($data);
						if ($insert) {
							$this->response->status = true;
							$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i> <b>".$this->input->post("nama")."</b> berhasil ditambahkan.</div>";
						} 
				} else {
					$this->response->status = false;	
					$this->response->errorNama = form_error("nama","<span style='color:red'>","</span>");
					$this->response->errorKota = form_error("kota","<span style='color:red'>","</span>");
				}
			} else {
				$this->response->status = false;
				$this->response->message = "<div class='alert alert-warning'><i class='fa fa-warning'></i> Nama <b>".$this->input->post("nama")."</b> shuttle sudah terdaftar</div>";
			}
			return $this->json();
		}
	}

	public function edit_ajax($id)
	{
		$data = $this->shuttleModel->getById($id);
		$this->response->status = true;
		$this->response->data = $data;
		return $this->json();
	}

	public function update()
	{
		if ($this->isPost()) {
			
			$this->form_validation->set_rules("nama","Nama",'trim|required|max_length[50]');
			$this->form_validation->set_message('required','%s harus diisi !');
			$this->form_validation->set_rules("kota","Kota Shuttle",'trim|required');

			if ($this->form_validation->run() == true) {
				$data = array(
						"id"		=> $this->input->post("id"),
						"nama" 		=> $this->input->post("nama"),
						"kota"	=> $this->input->post("kota"),
					);
				
				$update = $this->shuttleModel->update($data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i> <b>".$this->input->post("nama")."</b> berhasil update.</div>";
				} else {
					$this->response->status = false;
					$this->response->message = "<div class='alert alert-danger'><i class='fa fa-ban'></i> <b>".validation_errors()."</b></div>";
				}
			} else {
				$this->response->status = false;	
				$this->formErrorMessage();
			}

			return $this->json();
		}		
	}

	public function delete($id)
	{
		$delete = $this->shuttleModel->delete($id);
		if ($delete) {
			$this->response->status = true;
			$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i> <b>Data berhasil di hapus...!</b></div>";
		}
		return $this->json();
	}

}