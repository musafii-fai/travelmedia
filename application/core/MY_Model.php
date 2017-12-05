<?php 
/**
* @package		:	Codeigniter
* @subpackage	:	Model
* @author 	    :	Musafi'i (musafii.fai@gmail.com)	
* @copyright	:	2017
* Defaut parent model class
*/
class MY_Model extends CI_Model
{
	protected $_table;
	protected $_primary_key = "id";
	
	function __construct($tableName)
	{
		parent::__construct();
		$this->_table = $tableName;
	}

	public function setTable($tbl)
	{
		$this->_table = $tbl;
	}

	/**
	* @return for show table
	* @param  $where = array("id" => $id,"name" => $name); // if true
	* @param  $select = "name,title,date"; or array("name","title","date"); // if true
	* @param  $orderBy = "id DESC,name ASC"; or array("id DESC","name ASC"); // if true
	* @param  $search = array("title" => $search,"name" => $search); // if true
	* @param  $all = if(true){ result all }else{ result row };
	* @param  $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* @param  $whereIn = array("id" => array(1,3,3,2,4,5));
	* @param  $limit = integer
	* @param  $offset = integer
	* @param  $groupBy = array("age","salary") or string;
	* @param  $result_array = if(true){ result array() }else{ result object}
	*/
	public function findData($where=false,$select=false,$orderBy=false,$search=false,$limit=0,$offset=0,$all=true,$join=false,$whereIn=false,$groupBy=false,$result_array = false)
	{
		$this->db->from($this->_table);

		if ($where) { $this->db->where($where); }
		if ($select) { 
			$select = is_array($select) ? implode(",", $select) : $select;
			$this->db->select($select);
		}
		if ($orderBy) {
		   $orderBy = is_array($orderBy) ? implode(",", $orderBy) : $orderBy;
		   $this->db->order_by($orderBy);
		}
		if($search) {
			if($where || $whereIn) {
				$this->db->group_start()
						 ->or_like($search)
						 ->group_end();
			}else{
				if(count($search) > 1) {
					$this->db->or_like($search);
				}else{
					$this->db->like($search);
				}
			}
		}
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
		if($join){
			foreach ($join as $j) {
				$this->db->join($j[0],$j[1],isset($j[2]) ? $j[2] : "INNER");
			}
		} 
		if ($groupBy) { $this->db->group_by($groupBy); }
		if ($all) {
			if ($limit == 0) {
				$query = $this->db->get();
				/*result_array is true or false*/
				return ($result_array) ? $query->result_array() : $query->result();
			} else {
				$this->db->limit($limit,$offset);
				$query = $this->db->get();
				/*result_array is true or false*/
				return ($result_array) ? $query->result_array() : $query->result();
			}
		} else {
			$query = $this->db->get();
			/*result_array is true or false*/
			return ($result_array) ? $query->row_array() : $query->row();
		}
	}

	/*
	*	$where = array("id" => $id,"name" => $name); // if true
	*	$select = "name,title,date"; or array("name","title","date"); // if true
	*	$columnsOrderBy = array(null,"name","age");
	* 	$search = array("title" => $search,"name" => $search); // if true
	* 	$join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* 	$whereIn = array("id" => array(1,3,3,2,4,5));
	*/
	public function findDataTableObject($where=false,$select=false,$columnsOrderBy=false,$search=false,$join=false,$whereIn=false)
	{
		$input = $this->input;
		$result = self::findDataTable($where,$select,$columnsOrderBy,$search,$join,$whereIn);
		$data = array();
		$no = $input->post("start");
		foreach ($result as &$item) {
			$no++;
			$item->no = $no;
			$data[] = $item;
		}
		self::findDataTableOutput($data,$where,$search,$join,$whereIn);
	}

	/*
	*	$where = array("id" => $id,"name" => $name); // if true
	*	$select = "name,title,date"; or array("name","title","date"); // if true
	*	$columnsOrderBy = array(null,"name","age");
	* 	$search = array("title" => $search,"name" => $search); // if true
	* 	$join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* 	$whereIn = array("id" => array(1,3,3,2,4,5));
	*/
	public function findDataTable($where=false,$select=false,$columnsOrderBy=false,$search=false,$join=false,$whereIn=false)
	{
		$input = $this->input;
		$orderBy = false;

		if ($columnsOrderBy) {	
			if (isset($_POST['order'])) {
				$valColumnName = $columnsOrderBy[$_POST['order']['0']['column']];
				$valKeyword = $_POST['order']['0']['dir'];
				$orderBy = array($valColumnName." ".$valKeyword);
			}
		}

		$data = $this->findData($where,$select,$orderBy,$search,$input->post("length"),$input->post("start"),true,$join,$whereIn);
		return $data;
	}

	/*
	*	$data = result for findDataTablesCore
	*	$where = array("id" => $id,"name" => $name); // if true
	*	$select = "name,title,date"; or array("name","title","date"); // if true
	*	$columnsOrderBy = array(null,"name","age");
	* 	$search = array("title" => $search,"name" => $search); // if true
	* 	$join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* 	$whereIn = array("id" => array(1,3,3,2,4,5));
	*/
	public function findDataTableOutput($data=null,$where=false,$search=false,$join=false,$whereIn=false)
	{
		$input = $this->input;
		$response = new stdClass();

		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $this->getCount($where,$search,$join,$whereIn);
		$response->recordsFiltered = $this->getCount($where,$search,$join,$whereIn);
		$response->data = $data;

		self::json($response);
	}

	private function json($data = null)
	{
    	$this->output->set_header("Content-Type: application/json; charset=utf-8");
    	$data = isset($data) ? $data : $this->response;
    	echo json_encode($data);
	}

	/*
	* for option select or combobox data
	* $where = array("id" => $id,"name" => $name); // if true
	* $select = "name,title,date"; or array("name","title","date"); // if true
	* $orderBy = "id DESC,name ASC"; or array("id DESC","name ASC"); // if true
	* $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* $whereIn = array("id" => array(1,3,3,2,4,5));
	* $groupBy = array("age","salary") or string;
	* $result_array = if(true){ result array() }else{ result object}
	*/
	public function getAll($where=false,$select=false,$orderBy=false,$join=false,$whereIn=false,$groupBy=false,$result_array=false)
	{
		if ($where) {
			$this->db->where($where);
		}
		if ($select) { 
			$select = is_array($select) ? implode(",", $select) : $select;
			$this->db->select($select);
		}
		if ($orderBy) {
		   $orderBy = is_array($orderBy) ? implode(",", $orderBy) : $orderBy;
		   $this->db->order_by($orderBy);
		}
		if($join){
			foreach ($join as $j) {
				$this->db->join($j[0],$j[1],isset($j[2]) ? $j[2] : "INNER");
			}
		} 
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
		if ($groupBy) { $this->db->group_by($groupBy); }
		$query = $this->db->get($this->_table);
		if ($result_array) {
			return $query->result_array();
		} else {
			return $query->result();
		}
	}

	/*
	* $where = array("id" => $id,"name" => $name); // if true
	* $search = array("title" => $search,"name" => $search); // if true
	* $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* $whereIn = array("id" => array(1,3,3,2,4,5));
	*/
	public function getCount($where=false,$search=false,$join=false,$whereIn = false)
	{
		$this->db->from($this->_table);
		if($where) {
			$this->db->where($where);
		}
		if($search) {
			if($where || $whereIn) {
				$this->db->group_start()
						 ->or_like($search)
						 ->group_end();
			}else{
				if(count($search) > 1) {
					$this->db->or_like($search);
				}else{
					$this->db->like($search);
				}
			}
		}
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
		if($join){
			foreach ($join as $j) {
				$this->db->join($j[0],$j[1],isset($j[2]) ? $j[2] : "INNER");
			}
		} 
		return $this->db->count_all_results();
	}

	/*
	* $id = for url $this->uri->segment(3); // example
	* $select = "name,title,date"; or array("name","title","date"); // if true
	* $row_array = if(true){ row array() }else{ row object}
	*/
	public function getById($id,$select = false,$row_array = false)
	{
		$this->db->where($this->_primary_key,$id);
		if ($select) {
			if (is_array($select)) {
				$this->db->select(implode(",", $select));
			} else {
				$this->db->select($select);
			}
		}

		$query = $this->db->get($this->_table);
		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

	/*
	* $where = array("id" => $id,"name" => $name);
	* $select = "name,title,date"; or array("name","title","date"); // if true
	* $join = array(array("table2","table2.id = table1.id",[LEFT])); // if true
	* $row_array = if(true){ row array() }else{ row object}
	*/
	public function getByWhere($where,$select = false,$join=false,$row_array = false)
	{
		$this->db->where($where);
		if ($select) {
			if (is_array($select)) {
				$this->db->select(implode(",", $select));
			} else {
				$this->db->select($select);
			}
		}
		if($join){
			foreach ($join as $j) {
				$this->db->join($j[0],$j[1],isset($j[2]) ? $j[2] : "INNER");
			}
		} 
		$query = $this->db->get($this->_table);
		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

	/*
	* $data = array("name" => $name,"age" => $age);
	*/
	public function insert($data)
	{
		if ($this->db->insert($this->_table,$data)) {
			return $this->db->insert_id();
		}
	}

	/*
	* $data = array("id" => $id,"name" => $name);
	*/
	public function update($data)
	{
		$this->db->where($this->_primary_key,$data[$this->_primary_key]);
		return $this->db->update($this->_table,$data);
	}

	/*
	* $where = array("id" => $id,"name" => $name);
	* $data = array(name" => $name,"age" => $age);
	*/
	public function updateWhere($where,$data)
	{
		$this->db->where($where);
		return $this->db->update($this->_table,$data);
	}

	/*
	* $id = for url $this->uri->segment(3); // example
	*/
	public function delete($id)
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->delete($this->_table);
		return $this->db->affected_rows();
	}

	/*
	* $where = array("id" => $id,"name" => $name);
	*/
	public function deleteWhere($where)
	{
		$this->db->where($where);
		$this->db->delete($this->_table);
		return $this->db->affected_rows();
	}


}