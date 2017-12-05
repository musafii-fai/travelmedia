<?php 
/**
* 
*/
class User extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("User_model","userModel");
		$this->load->model("Shuttle_model","shuttleModel");
	}

	public function login()
	{
		$this->view(false,false);
	}

	public function ajax_login()
	{
		if ($this->isPost()) {

			$this->form_validation->set_rules("username","Username","trim|required");
			$this->form_validation->set_rules("password","Password",'trim|required');
			$this->form_validation->set_message("required","%s harus di isi.");
			if ($this->form_validation->run() == true) {
				$username = $this->input->post("username");
				$password = $this->input->post("password");

				$data = $this->userModel->getByWhere(array("username" => $username,"password" => md5($password)));
				if ($data) {
					if ($data->status == 0) {
						$this->response->message = alertDanger("Opps Maaf,<br> Status anda telah di non active kan.! <br> Segera hubungi admin pusat untuk mengatifkan.");
					} else {
						$this->response->status = true;
						$this->response->message = "<span style='color:blue; font-size: 30px;'><i class='fa fa-spinner fa-spin'></i> Mohon tunggu ....</span>";
						$this->session->set_userdata("admin",$data);
					}
				} else {
					$this->response->message = alertDanger("Opps,<br> Username atau password salah atau tidak terdaftar..!!");
				}
			} else {
				$this->response->error = array(
						"username" => form_error("username","<span style='color:red'>","</span>"),
						"password" => form_error("password","<span style='color:red'>","</span>"),
					);
			}

			return $this->json();
		}
	}

	public function ajax_user_admin()
	{
		if ($this->isPost()) {
			$this->load->model("shuttle_model");
			$this->load->model("user_model");
			$user = $this->user_model->getById($this->user->id);
			$shuttle = $this->shuttle_model->getById($user->shuttleid);
			if ($shuttle) {
				$this->response->status = true;
				$this->response->data = array(
						"user_id"		=>	$user->id,
						"username"		=>	$user->username,
						"photo_admin"	=>	$user->photo,
						"nama_admin"	=> 	$user->nama,
						"shuttle"		=>	$shuttle->nama,
						"shuttle_id"	=>	$user->shuttleid,
						"kota"			=>	$shuttle->kota,
						"role_id"		=>	$user->users_roleid,
					);
			} else {
				$this->response->message = '<span style="color:red">Data not found.!</span>';
			}

			return $this->json();
		}
	}

	public function profile()
	{
		$this->headerTitle("Profile","settings");
		$breadcrumbs = array(
								"User Admin" => site_url("user"),
								$this->router->method => site_url("user/profile")
							);
		$this->breadcrumbs($breadcrumbs);

		parent::view();
	}

	public function changepass_profile()
	{
		if ($this->isPost()) {
			$this->form_validation->set_rules("passwordCurrent","Password saat ini","required");
			$this->form_validation->set_rules("passwordNew","Password baru","required");
			$this->form_validation->set_rules("confirmPassword","Confirm password","required");
			$this->form_validation->set_message("required","%s harus di isi.!");

			$id = $this->input->post("id");
			$passwordCurrent = $this->input->post("passwordCurrent");
			$passwordNew = $this->input->post("passwordNew");
			$confirmPassword = $this->input->post("confirmPassword");
			$user = $this->userModel->getById($id);
			if ($this->form_validation->run() == true) {
				if (md5($passwordCurrent) != $user->password) {
					$this->response->errorPasswordCurrent = alertDanger("Password saat ini salah.!");
				} else {
					if ($confirmPassword != $passwordNew) {
						$this->response->errorConfirmPassword = "<span style='color:red;'>Confirm password tidak sama dengan password baru.!</span>";
					} else {
						$data = array(
								"id" 		=> $id,
								"password" 	=> md5($this->input->post("passwordNew")),
							);
						$changePass = $this->userModel->update($data);
						if ($changePass) {
							$this->response->status = true;
							$this->response->message = alertSuccess("Password berhasil diganti. <br> Silahkan logout dan signin kembali.");
						}
					}	
				}
			} else {
				$this->response->error = array(
								"passwordCurrent"	=>	form_error("passwordCurrent","<span style='color:red'>","</span>"),
								"passwordNew"		=>	form_error("passwordNew","<span style='color:red'>","</span>"),
								"confirmPassword"	=>	form_error("confirmPassword","<span style='color:red'>","</span>"),
							);
			}
			
			return parent::json();
		}
	}

	public function logout()
	{
		$this->session->unset_userdata("admin");
		$this->session->sess_destroy();
		$redirect = $this->input->get("redirect");
		redirect("user/login?redirect=".$redirect);
	}

	public function index()
	{
		$this->headerTitle("User Admin","list table");
		$breadcrumbs = array("User Admin" => site_url("user"));
		$this->breadcrumbs($breadcrumbs);

		$shuttle = $this->shuttleModel->getAll();

		$this->shuttleModel->setTable("users_role"); // for users_role
		$users_role = $this->shuttleModel->getAll();
		
		$content = array(
						"shuttle" => $shuttle,
						"users_role" => $users_role,
					);
		$this->viewContent($content);


		$this->view();
	}

	public function info()
	{
		if ($this->isPost()) {
			$select = array(
								"users.*","users.nama as nama_user","users.shuttleid",
								"shuttle.nama AS nama_shuttle","kota",
								"users_role.role",
							);
			$join = array(
					array("shuttle","shuttle.id = users.shuttleid","LEFT"),
					array("users_role","users_role.id = users.users_roleid","LEFT"),
				);
			$columns = array(null,null,"users.nama","nama_shuttle","role");
			$search = array(
					"users.nama"	=>	$this->input->post("search")["value"],
					"shuttle.nama"	=>	$this->input->post("search")["value"],
					"role"			=>	$this->input->post("search")["value"],
				);
			$result = $this->userModel->findDataTable(array("status" => 1),$select,$columns,$search,$join);
			$data = array();
			$no = $this->input->post("start");
			foreach ($result as $item) {
				$no++;
				$source_photo = $item->photo == "" ? base_url('assets/image/user_image.png') : base_url("uploads/".$item->photo);
				$row = array(
						$no,
						$item->photo = "<img src='".$source_photo."' class='img-responsive' style='width:90px; height:50px;'>",
						$item->nama_user,
						$item->nama_shuttle." - <b>".$item->kota."</b>",
						$item->role
					);
				$data[] = $row;
			}
			return $this->userModel->findDataTableOutput($data,false,$search,$join);
		}
	}

	public function ajax_list()
	{
		if ($this->isPost()) {
			$select = array(
								"users.*","users.nama as nama_user","users.shuttleid",
								"shuttle.nama AS nama_shuttle","kota",
								"users_role.role",
							);
			$join = array(
					array("shuttle","shuttle.id = users.shuttleid","LEFT"),
					array("users_role","users_role.id = users.users_roleid","LEFT"),
				);
			$columns = array(null,null,"users.nama","nama_shuttle","role",null,null);
			$search = array(
					"users.nama"	=>	$this->input->post("search")["value"],
					"shuttle.nama"	=>	$this->input->post("search")["value"],
					"role"			=>	$this->input->post("search")["value"],
				);
			$result = $this->userModel->findDataTable(false,$select,$columns,$search,$join);
			$data = array();
			$no = $this->input->post("start");
			foreach ($result as $item) {
				$no++;
				$status = $item->status == 1 ? 'Active':'Non Active';
				$statusClass = $item->status == 1 ? 'btn-success':'btn-warning';
				$buttonStatus = '<button type="button" class="btn btn-xs btn-flat '.$statusClass.'" onclick="status('.$item->id.')">'.$status.'</button>';

				$btnChangePass = '<button type="type" class="btn btn-info btn-xs btn-flat" onclick="changePass('.$item->id.')">Change Pass</button>';
				$btnEdit = '<button type="type" class="btn btn-warning btn-xs btn-flat" onclick="edit('.$item->id.')">Update</button>';
				$btnDelete = '<button type="type" class="btn btn-danger btn-xs btn-flat" onclick="btnDelete('.$item->id.')" id="btnDelete" data-nama="'.$item->nama_user.'">Delete</button>';
				$buttonAction = $item->role == 'admin'?'':$btnChangePass." ".$btnEdit." ".$btnDelete;
				$photo_source = $item->photo == "" ? base_url('assets/image/user_image.png') : base_url("uploads/".$item->photo);
				$photo_user = "<img src='".$photo_source."' class='img-responsive' style='width:90px; height:50px;'>";
				$row = array(
						$no,
						$photo_user,
						$item->nama_user,
						$item->nama_shuttle." - <b>".$item->kota."</b>",
						$item->role,
						
					);

				if ($this->user_role() == "admin") {
					$row[] = $item->role == "admin" ? "" : $buttonStatus;
					$row[] = $buttonAction;
				} else {
					$active = '<span class="text-green"> Active </span>';
					$nonActive = '<span class="text-red"> Non Active </span>';
					$row[] = $item->status == 1 ? $active : $nonActive;
				}
				$data[] = $row;
			}
			return $this->userModel->findDataTableOutput($data,false,$search,$join);
		}
	}

	public function add()
	{
		if ($this->isPost()) {
			$this->form_validation->set_rules("username","Username","trim|required");
			$this->form_validation->set_rules("password","Password",'trim|required');
			$this->form_validation->set_rules("nama","Nama",'trim|required');
			$this->form_validation->set_rules("shuttle","Shuttle",'trim|required');
			$this->form_validation->set_rules("role","Role",'trim|required');
			$this->form_validation->set_message("required","%s Harus di isi.");

			if ($this->form_validation->run() == true) {
				$username = $this->input->post("username");
				$data = array();
				$data["username"] 		= 	$username;
				$data["password"]		=	md5($this->input->post("password"));
				$data["nama"]			=	$this->input->post("nama");
				$data["shuttleid"]		=	$this->input->post("shuttle");
				$data["users_roleid"]	=	$this->input->post("role");
				
				$usernameUnique = $this->userModel->getByWhere(array("username" => $username));
				if ($usernameUnique) {
					$this->response->message = "username unique error";
					$this->response->errorUsernameUnique =  alertDanger("Username sudah terdaftar, Silahkan ganti yang lain.!");
				} else {
					self::_do_upload(); // upload config
			        if (!empty($_FILES["photo"]["name"])) {
			        	$img = "photo";
						if ( ! $this->upload->do_upload($img)) {
				        	$this->response->message = "error_upload";
				        	$this->response->errorUpload = "<span style='color:red;'><b>Upload photo error</b> : ".$this->upload->display_errors()."</span>";
				        } else {
				        	$photoUser = $this->upload->data();
							$data["photo"] = $photoUser["file_name"];
				        	$insert = $this->userModel->insert($data);
							if ($insert) {
								$this->response->status = true;
								$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i>Berhasil di tambah.</div>";
							}
				        }
					} else {						
						$insert = $this->userModel->insert($data);
						if ($insert) {
							$this->response->status = true;
							$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i>Berhasil di tambah.</div>";
						}
					}
				}
			} else {
				$this->response->status = false;
				$this->response->error = array(
							"username" 	=> 	form_error("username","<span style='color:red'>","</span>"),
							"password"	=> 	form_error("password","<span style='color:red'>","</span>"),
							"nama"		=>	form_error("nama","<span style='color:red'>","</span>"),
							"shuttle"	=>	form_error("shuttle","<span style='color:red'>","</span>"),
							"role"		=>	form_error("role","<span style='color:red'>","</span>"),
						);
			}

			return $this->json();
		}
	}

	public function _do_upload()
	{
		$config['upload_path']      = 	'uploads/';
        $config['allowed_types']    = 	'gif|jpg|jpeg|png';
        $config['max_size']         = 	2048;
        $config['max_width']        = 	2000;
        $config['max_height']       =	1500;
        $config['encrypt_name']		=	true;

        $this->load->library('upload', $config);
        // $this->upload->initialize($config);
	}

	public function status($id)
	{
		if ($this->isPost()) {
			
			$bacaid = $this->userModel->getById($id);
			$data = array(
					"id" => $id,
				);
			$bacaid->status == 1 ? $data['status'] = 0 : $data['status'] = 1;
			$changePass = $this->userModel->update($data);
			if ($changePass) {
				$this->response->status = true;
				$this->response->message = "success";
				$this->response->data = $bacaid->status;
			}

			return $this->json();
		}
	}

	public function getbyid($id)
	{
		if ($this->isPost()) {
			$data = $this->userModel->getById($id);
			$this->response->status = true;
			$this->response->message = "data get By Id";
			$this->response->data = $data;
			return $this->json();
		}
	}

	public function changepass()
	{
		if ($this->isPost()) {
			$this->form_validation->set_rules("password","Password","required");
			$this->form_validation->set_message("required","%s Harus di isi.!");

			if ($this->form_validation->run() == true) {
				$data = array(
						"id" 		=> $this->input->post("id"),
						"password" 	=> md5($this->input->post("password")),
					);
				$changePass = $this->userModel->update($data);
				if ($changePass) {
					$this->response->status = true;
					$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i> Password berhasil diganti.</div>";
				}
			} else {
				$this->response->status = false;
				$this->response->message = form_error("password","<span style='color:red'>","</span>");
			}
			
			return $this->json();
		}
	}

	public function update()
	{
		if ($this->isPost()) {
			// $this->form_validation->set_rules("username","Username","trim|required");
			$this->form_validation->set_rules("nama","Nama",'trim|required');
			$this->form_validation->set_rules("shuttle","Shuttle",'trim|required');
			$this->form_validation->set_rules("role","Role",'trim|required');
			$this->form_validation->set_message("required","%s Harus di isi.");

			if ($this->form_validation->run() == true) {
				$data = array(
						"id"			=>	$this->input->post("id"),
						// "username" 		=> 	$this->input->post("username"),
						"nama"			=>	$this->input->post("nama"),
						"shuttleid"		=>	$this->input->post("shuttle"),
						"users_roleid"	=>	$this->input->post("role")
					);

				self::_do_upload(); // upload config
		        if (!empty($_FILES["photo"]["name"])) {
		        	$img = "photo";
					if ( ! $this->upload->do_upload($img)) {
			        	$this->response->message = "error_upload";
			        	$this->response->errorUpload = "<span style='color:red;'><b>Upload photo error</b> : ".$this->upload->display_errors()."</span>";
			        } else {
			        	$photoUser = $this->upload->data();
						$data["photo"] = $photoUser["file_name"];
			        	$dataPhoto = $this->userModel->getById($this->input->post("id"));
						if (file_exists("uploads/".$dataPhoto->photo) && $dataPhoto->photo) {
							unlink("uploads/".$dataPhoto->photo);
						}

			        	$update = $this->userModel->update($data);
						if ($update) {
							$this->response->status = true;
							$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i>Berhasil di update.</div>";
						}
			        }
				} else {	
					if ($this->input->post("is_delete") == 1) {
						$data["photo"] = "";
						$dataPhoto = $this->userModel->getById($this->input->post("id"));
						if (file_exists("uploads/".$dataPhoto->photo) && $dataPhoto->photo) {
							unlink("uploads/".$dataPhoto->photo);
						}	
					}
									
					$update = $this->userModel->update($data);
					if ($update) {
						$this->response->status = true;
						$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i>Berhasil di update.</div>";
					}
				}

			} else {
				$this->response->status = false;
				$this->response->error = array(
							// "username" 	=> 	form_error("username","<span style='color:red'>","</span>"),
							"nama"		=>	form_error("nama","<span style='color:red'>","</span>"),
							"shuttle"	=>	form_error("shuttle","<span style='color:red'>","</span>"),
							"role"		=>	form_error("role","<span style='color:red'>","</span>")
						);
			}

			return $this->json();
		}
	}

	public function delete($id)
	{
		if ($this->isPost()) {
			$data = $this->userModel->getById($id);
			$delete = $this->userModel->delete($id);
			if ($delete) {
				$this->response->status = true;
				$this->response->message = "<div class='alert alert-success'><i class='fa fa-check'></i>Berhasil di delete.</div>";
				if (file_exists("uploads/".$data->photo) && $data->photo) {
					unlink("uploads/".$data->photo);
				}
			} else {
				$this->response->status = false;
				$this->response->message = "<div class='alert alert-danger'><i class='fa fa-ban'></i> Opps, terjadi kesalahan.<br>Mungkin sudah dihapus admin lain.</div>";
			}
			return $this->json();
		}
	}
}