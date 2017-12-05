<?php 
/**
* 
*/
class Jamkeberangkatan extends MY_Controller
{
	
	var	$column_order = array(null,"jam");

	function __construct()
	{
		parent::__construct();
		$this->load->model("Jam_model","jamModel");
	}

	public function index()
	{
		$this->headerTitle("Jam Keberangkatan","List");
		$breadcrumbs = array("Jam Keberangkatan" => site_url("jamkeberangkatan"));
		$this->breadcrumbs($breadcrumbs);

		$this->load->model("bus_model","busModel");
		$this->viewContent(array("bus_result" => $this->busModel->getAll()));

		$this->view();
	}

	public function info()
	{
		if ($this->isPost()) {
			$input = $this->input;
			$columns = array(null,"jam");
			$search = array(
						"jam" => $input->post("search")["value"]
					);

			$result= $this->jamModel->findDataTable(false,false,$columns,$search);
			$data = array();
			$no = $input->post("start");
			foreach ($result as &$item) {
				$no++;
				$row = array();

				$row[] = $no;
				$row[] = "Jam Keberangkatan <b>".$item->jam."</b>";	

				$data[] = $row;
			}
			return $this->jamModel->findDataTableOutput($data,false,$search);
		}
	}

	public function ajax_list()
	{
		if ($this->isPost()) {
			$input = $this->input;
			$columns = array(null,"jam");
			$search = array(
						"jam" => $input->post("search")["value"],
					);
			$result= $this->jamModel->findDataTable(false,false,$columns,$search);
			$data = array();
			$no = $input->post("start");
			foreach ($result as &$item) {
				$no++;
				$row = array();

				$row[] = $no;
				$row[] = "Jam Keberangkatan <b>".$item->jam."</b>";
				$btnEdit = '<button type="button" id="btnEdit" onclick="edit_jam('.$item->id.')" class="btn btn-xs btn-warning btn-flat">Edit</button> || ';		
				$btnHapus = '<button type="button" id="btnHapus" onclick="delete_jam('.$item->id.')" class="btn btn-xs btn-danger btn-flat">Hapus</button>';
				if($this->user_role() == "admin")	{
					$row[] = $btnEdit."".$btnHapus;
				}
				$data[] = $row;
			}
			return $this->jamModel->findDataTableOutput($data,false,$search);
		}
	}

	public function add()
	{
		if ($this->isPost()) {
			$this->form_validation->set_rules("jam","Jam Keberangkatan","required");
			$this->form_validation->set_message("required","%s harus di isi.!");

			if ($this->form_validation->run() == true) {
				$data = array(
						"jam" => $this->input->post("jam"),
					);
				$insert = $this->jamModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i> <b>".$this->input->post("jam")."</b> berhasil ditambahkan.</div>";
				}
			} else {
				$this->response->error = array(
						"jam" => form_error("jam","<span style='color:red'>","</span>"),
					);
			}

			return $this->json();
		}
	}

	public function edit_id($id)
	{
		if ($this->isPost()) {
			$data = $this->jamModel->getById($id);
			$this->response->status = true;
			$this->response->message = "Fetch row data";
			$this->response->data = $data;
			return $this->json();
		}
	}

	public function update()
	{
		if ($this->isPost()) {
			$this->form_validation->set_rules("jam","Jam Keberangkatan","required");
			$this->form_validation->set_message("required","%s harus di isi.!");

			if ($this->form_validation->run() == true) {
				$data = array(
						"id" => $this->input->post("idJam"),
					);
				$update = $this->jamModel->update($data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i> <b>".$this->input->post("jam")."</b> berhasil di update.</div>";
				}
			} else {
				$this->response->error = array(
						"jam" => form_error("jam","<span style='color:red'>","</span>"),
					);
			}

			return $this->json();
		}
	}

	public function delete($id)
	{
		if ($this->isPost()) {
			$data = $this->jamModel->delete($id);
			$this->response->status = true;
			$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i> Berhasil di hapus.</div>";
			return $this->json();
		}
	}

}