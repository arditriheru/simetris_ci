<?php 

class mSimetris extends CI_Model
{

	public function getData($table)
	{
		return $this->db->get($table);
	}

	function cari($id){
		$query= $this->db->get_where('mr_pasien',array('id_catatan_medik'=>$id));
		return $query;
	}

	public function insertData($table,$data)
	{
		$this->db->insert($table,$data);
	}

	public function updateData($table,$data,$where)
	{
		$this->db->update($table,$data,$where);
	}

	public function deleteData($table,$where)
	{
		$this->db->where($where);
		$this->db->delete($table);
	}

	public function cekLogin()
	{
		$username = set_value('username');
		$password = set_value('password');

		$result = $this->db->where('nama_user',$username)
		-> where('password',md5($password))
		-> limit('1')
		-> get('psdi_petugas');

		if($result->num_rows()>0)
		{
			return $result->row();
		}else{
			return FALSE;
		}
	}

	public function cekLogin2($username,$password,$id_unit)
	{
		$result = $this->db->where('username',$username)
		-> where('password',md5($password))
		-> where('id_unit',$id_unit)
		-> limit('1')
		-> get('admin');

		if($result->num_rows()>0)
		{
			return $result->row();
		}else{
			return FALSE;
		}
	}

	public function hakAkses($username,$password,$id)
	{
		$result = $this->db->where('psdi_petugas.nama_user',$username)
		-> where('psdi_petugas.password',md5($password))
		-> where('psdi_riw_aplikasi.id_aplikasi',$id)
		-> limit('1')
		-> join('psdi_riw_aplikasi','psdi_petugas.id_petugas=psdi_riw_aplikasi.id_petugas')
		-> get('psdi_petugas');

		if($result->num_rows()>0)
		{
			return $result->row();
		}else{
			return FALSE;
		}
	}

	function countData($table,$where){
		$this->db->where($where);
		$query = $this->db->get($table);
		return $query->num_rows();
	}

	function selectData($table,$column,$where){
		$this->db->select($column);
		$this->db->where($where);
		$query = $this->db->get($table);
		return $query;
	}

	function cariNamaPasienData($table,$keyword){
		$this->db->like($keyword);
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get($table);
		return $query;
	}

	function getMax($table,$column){
		$this->db->select_max($column);
		$query = $this->db->get($table);
		return $query; 
	}

}

?>
