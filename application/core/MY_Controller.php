<?php 

/**
* @package	  : Codeigniter
* @subpackage : Controller
* @author 	  : Musafi'i (musafii.fai@gmail.com)
* @copyright  : 2017
* Default parent Controller Class
*/

class MY_Controller extends CI_Controller
{

	private $templates = "templates";
	// private $subfolder = array();
	
	var $data = array(
			"all"	 => array(),
			"header" => array(),
			"view" => array(),
			"footer" => array()
		);	// for view data
	protected $response;
	var $user;
	var $user_role;

	function __construct()
	{
		parent::__construct();
		$this->data['header']["title"] = $this->router->class;
		$this->user = $this->session->admin;
		date_default_timezone_set("Asia/Jakarta");

		if (!isset($this->user)) {
			$redirect = $this->input->get("redirect");
			if (!isset($redirect) && !($this->router->class == "user" && $this->router->method == "login" || $this->router->method == "ajax_login")) {
				
					$redirect = implode("/", array(
							"controller" => $this->router->class,
							"method"	 => $this->router->method
						));
					
				redirect("user/login?redirect=".base_url($redirect));
			}
		} else {
			$this->load->model("User_model");
			$userLocked = $this->User_model->getById($this->user->id);

			if ($this->router->class == "user" && $this->router->method == "login" || $this->router->method == "ajax_login") {
				if ($userLocked->locked == "0") {
					echo "<script> window.location.href = '".site_url('dashboard/lockscreen')."'; </script>";
				} else {
					redirect(base_url());
				}
			}

			if ($userLocked->locked == "0" ) {
				$redirect = $this->input->get("redirect");
				if (!isset($redirect) && !($this->router->class == "dashboard" && $this->router->method == "lockscreen") && !($this->router->class == "dashboard" && $this->router->method == "logout_Locked") && !($this->router->class == "dashboard" && $this->router->method == "unLocked")) {
						$redirect = implode("/", array(
								"controller" => $this->router->class,
								"method"	 => $this->router->method
							));
						echo "<script> window.location.href = '".site_url('dashboard/lockscreen?redirect=').$redirect."'; </script>";
				}
			} else {
				if (($this->router->class == "dashboard" && $this->router->method == "lockscreen") || ($this->router->class == "dashboard" && $this->router->method == "logout_Locked") || ($this->router->class == "dashboard" && $this->router->method == "unLocked")) {
					echo "<script> window.location.href = '".base_url()."'; </script>";
				}
			}

			$this->user_role = $this->user_role();
		}
		
		$this->response = new stdClass();
		$this->response->status = false;
		$this->response->message = "";
		$this->response->data = new stdClass();
	}

	public function view($directory = false,$use_layout = true)
	{	
		/*$folder = array("test","coba");
		$this->subfolder = implode("/", $folder);*/ // for content subfolder view

		if ($directory) {
			$subfolder = implode("/", $directory);
		}

		$className  = $this->router->fetch_class();		// 	for folder directory name view
		$methodName = $this->router->fetch_method();	//	for files name view

		if ($use_layout) {
			// for view header
			$header = implode("/", array(
					$this->templates,"header"
				));
			$this->load->view($header,array_merge($this->data["all"],$this->data["header"]));

			//for view data content
			$content = implode("/", array(
					isset($subfolder)?$subfolder:"",$className,$methodName
				));
			// $this->viewContent(array("openBox" => $this->openBox()));
			$this->load->view($content,array_merge($this->data["all"],$this->data["view"]));

			// for view footer
			$footer = implode("/", array(
					$this->templates,"footer"
				));
			$this->load->view($footer,array_merge($this->data["all"],$this->data["footer"]));
		} else {
			//for view data content
			$content = implode("/", array(
					isset($subfolder)?$subfolder:"",$this->router->class,$this->router->method
				));
			$this->load->view($content,$this->data["view"]);
		}
	}

	/*
	* $title = "Dashboard example";
	* $small_title = "control panel";
	*/
	public function headerTitle($title,$small_title = false)
	{
		$this->data["header"]["content_title"] = $title;
		if ($small_title) {
			$this->data["header"]["small_title"] = $small_title;
		}
	}

	/*
	* $content = array("list_all" => "list all data","test" => "test all","data" => $data);
	*/
	public function viewContent($content)
	{
		$this->data['view'] = $content;
	}

	/*
	* $item = array("index" => site_url());
	*/
	public function breadcrumbs($item)
	{
		$this->data["header"]["breadcrumbs"] = $item;
	}

	public function isPost()
	{
		if (strtoupper($this->input->server("REQUEST_METHOD")) == "POST") {
			return true;
		} else {
			// $this->response->status = false;
			$this->response->message = "Not allowed get request!";
			$this->response->data = null;
			return false;
		}
	}

	public function json($data = null)
	{
    	$this->output->set_header("Content-Type: application/json; charset=utf-8");
    	$data = isset($data) ? $data : $this->response;
    	echo json_encode($data);
	}
	
	public function user_role()
	{
		$this->load->model("User_model","userModel");
		$this->load->model("Users_role_model","userRoleModel");
		$user = $this->userModel->getById($this->user->id);

		$role = $this->userRoleModel->getById($user->users_roleid)->role;
		return $role;
	}
}