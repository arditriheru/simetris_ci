<?php

class dataJadwal extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if($this->session->userdata('booking_login') !='1')
		{
			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="5">Anda belum login</font>
				</div>');
			redirect('booking/login');
		}

		$this->load->model("mSimetris");
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data['title'] 		= "Jadwal";
		$data['subtitle'] 	= "Dokter";

		$data['datadokter'] = $this->db->query("SELECT id_dokter, nama_dokter FROM dokter WHERE status='1'")->result();

		$data['datajadwal'] 	= $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi,
			IF (dokter_jadwal.ims='1', ' + Imunisasi','') AS ims,
			CASE
			WHEN dokter.id_unit='1' THEN 'Anak'
			WHEN dokter.id_unit='2' THEN 'Obsgyn'
			WHEN dokter.id_unit='3' THEN 'Bedah'
			END AS nama_unit,
			CASE
			WHEN dokter_jadwal.hari=1 THEN 'Senin'
			WHEN dokter_jadwal.hari=2 THEN 'Selasa'
			WHEN dokter_jadwal.hari=3 THEN 'Rabu'
			WHEN dokter_jadwal.hari=4 THEN 'Kamis'
			WHEN dokter_jadwal.hari=5 THEN 'Jumat'
			WHEN dokter_jadwal.hari=6 THEN 'Sabtu'
			WHEN dokter_jadwal.hari=0 THEN 'Minggu'
			END AS nama_hari
			FROM dokter_jadwal
			JOIN dokter
			ON dokter_jadwal.id_dokter=dokter.id_dokter
			join sesi
			ON dokter_jadwal.id_sesi=sesi.id_sesi
			ORDER BY dokter_jadwal.hari ASC")->result();


		$data['datajadwallibur'] 	= $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi
			FROM dokter_jadwal_libur
			JOIN dokter
			ON dokter_jadwal_libur.id_dokter=dokter.id_dokter
			JOIN sesi
			ON dokter_jadwal_libur.id_sesi=sesi.id_sesi
			ORDER BY dokter_jadwal_libur.tanggal ASC")->result();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vDataJadwal',$data);
		$this->load->view('templates/footer',$data);
	}

	public function dataJadwalTab($id)
	{
		$data['title'] 		= "Jadwal";
		$data['subtitle'] 	= "Dokter";

		$data['id'] = $id;

		$data['datadokter'] = $this->db->query("SELECT id_dokter, nama_dokter FROM dokter WHERE status='1'")->result();

		$data['datajadwal'] 	= $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi,
			IF (dokter_jadwal.ims='1', ' + Imunisasi','') AS ims,
			CASE
			WHEN dokter.id_unit='1' THEN 'Anak'
			WHEN dokter.id_unit='2' THEN 'Obsgyn'
			WHEN dokter.id_unit='3' THEN 'Bedah'
			END AS nama_unit,
			CASE
			WHEN dokter_jadwal.hari=1 THEN 'Senin'
			WHEN dokter_jadwal.hari=2 THEN 'Selasa'
			WHEN dokter_jadwal.hari=3 THEN 'Rabu'
			WHEN dokter_jadwal.hari=4 THEN 'Kamis'
			WHEN dokter_jadwal.hari=5 THEN 'Jumat'
			WHEN dokter_jadwal.hari=6 THEN 'Sabtu'
			WHEN dokter_jadwal.hari=0 THEN 'Minggu'
			END AS nama_hari
			FROM dokter_jadwal
			JOIN dokter
			ON dokter_jadwal.id_dokter=dokter.id_dokter
			join sesi
			ON dokter_jadwal.id_sesi=sesi.id_sesi
			WHERE dokter_jadwal.id_dokter='$id'
			ORDER BY dokter_jadwal.hari ASC")->result();


		$data['datajadwallibur'] 	= $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi
			FROM dokter_jadwal_libur
			JOIN dokter
			ON dokter_jadwal_libur.id_dokter=dokter.id_dokter
			JOIN sesi
			ON dokter_jadwal_libur.id_sesi=sesi.id_sesi
			ORDER BY dokter_jadwal_libur.tanggal ASC")->result();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vDataJadwal',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataJadwal($id)
	{
		$data['id'] = $id;

		$data['title'] 			= "Tambah";

		if($id==1){
			$data['subtitle'] 	= "Libur";
		}else{
			$data['subtitle'] 	= "Jadwal";
		}

		$data['datadokter'] = $this->db->query("SELECT id_dokter, nama_dokter FROM dokter WHERE status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataJadwal',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataJadwalLiburAksi()
	{
		$id_dokter 	= $this->input->post('id_dokter');
		$id_sesi    = $this->input->post('id_sesi');
		$tanggal    = $this->input->post('tanggal');

		$data = array(

			'id_dokter' 	=> $id_dokter,
			'id_sesi' 		=> $id_sesi,
			'tanggal' 		=> $tanggal,

		);

		$this->mSimetris->insertData('dokter_jadwal_libur',$data);
		$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Berhasil menambahkan</font></div>');
		redirect('booking/dataJadwal');

	}

	public function tambahDataJadwalAksi()
	{
		$id_dokter 	= $this->input->post('id_dokter');
		$id_sesi    = $this->input->post('id_sesi');
		$hari    	= $this->input->post('hari');
		$jam    	= $this->input->post('jam');
		$kuota    	= $this->input->post('kuota');
		$ims    	= $this->input->post('ims');

		$data = array(

			'id_dokter' 	=> $id_dokter,
			'id_sesi' 		=> $id_sesi,
			'hari' 			=> $hari,
			'jam' 			=> $jam,
			'kuota' 		=> $kuota,
			'ims' 			=> $ims,

		);

		$this->mSimetris->insertData('dokter_jadwal',$data);
		$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Berhasil menambahkan</font></div>');
		redirect('booking/dataJadwal');

	}

	public function deleteDataJadwalLibur($id)
	{
		$where = array('id_jadwal_libur' => $id);
		$this->mSimetris->deleteData('dokter_jadwal_libur',$where);
		$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Data berhasil dihapus</font>
			</div>');
		redirect('booking/dataJadwal');

	}

	public function deleteDataJadwal($id)
	{
		$where = array('id_jadwal' => $id);
		$this->mSimetris->deleteData('dokter_jadwal',$where);
		$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Data berhasil dihapus</font>
			</div>');
		redirect('booking/dataJadwal');

	}

	public function updateDataJadwal($id)
	{
		$data['title'] 		= "Update";
		$data['subtitle'] 	= "Jadwal";

		$data['datadokter'] = $this->db->query("SELECT id_dokter, nama_dokter FROM dokter WHERE status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$data['datajadwal'] = $this->db->query("
			SELECT *, dokter_jadwal.kuota, dokter.nama_dokter, sesi.nama_sesi,
			CASE
			WHEN dokter_jadwal.hari='1' THEN 'Senin'
			WHEN dokter_jadwal.hari='2' THEN 'Selasa'
			WHEN dokter_jadwal.hari='3' THEN 'Rabu'
			WHEN dokter_jadwal.hari='4' THEN 'Kamis'
			WHEN dokter_jadwal.hari='5' THEN 'Jumat'
			WHEN dokter_jadwal.hari='6' THEN 'Sabtu'
			WHEN dokter_jadwal.hari='0' THEN 'Minggu'
			END AS nama_hari,
			IF (dokter_jadwal.ims='1', 'Ya','Tidak') AS nama_ims
			FROM dokter_jadwal, dokter, sesi
			WHERE dokter_jadwal.id_dokter=dokter.id_dokter
			AND dokter_jadwal.id_sesi=sesi.id_sesi
			AND dokter_jadwal.id_jadwal = '$id'")->result();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vUpdateDataJadwal',$data);
		$this->load->view('templates/footer',$data);
	}

	public function updateDataJadwalAksi()
	{
		$id 		= $this->input->post('id_jadwal');
		$id_dokter 	= $this->input->post('id_dokter');
		$id_sesi 	= $this->input->post('id_sesi');
		$hari 	 	= $this->input->post('hari');
		$jam 		= $this->input->post('jam');
		$kuota 	 	= $this->input->post('kuota');
		$ims 	 	= $this->input->post('ims');

		$data = array(

			'id_dokter' 		=> $id_dokter,
			'id_sesi' 			=> $id_sesi,
			'hari' 				=> $hari,
			'jam' 				=> $jam,
			'kuota' 			=> $kuota,
			'ims' 				=> $ims,

		);

		$where = array(
			'id_jadwal' 		=> $id
		);

		$this->mSimetris->updateData('dokter_jadwal',$data,$where);
		$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Data berhasil diupdate!</font>
			</div>');
		redirect('booking/dataJadwal/');

	}

	

}

?>