<?php 
/**
* 
*/
class Pemesanan_model extends MY_Model
{
	
	function __construct()
	{
		parent::__construct("transaksi_tiket");
	}

	public function findDataPemesanan($select,$orderBy,$join,$whereIn=false,$field="id")
	{
		$this->db->from($this->_table);
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
		if ($whereIn) { $this->db->where_in($field,$whereIn); }
		$query = $this->db->get();
		return $query->result();
	}
}