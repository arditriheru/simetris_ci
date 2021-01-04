<?php 

class dataAntrian extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if($this->session->userdata('login') !='1')
		{
			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="5">Anda belum login!</font>
				</div>');
			redirect('booking/login');
		}

		$this->load->model("mSimetris");
		$this->load->library('form_validation');
	}

	public function filterDataAntrian($id)
	{
		$data['title'] 		= "Antrian";
		$data['subtitle'] 	= "Pasien";

		$data['id'] = $id;

		$data['datadokter'] = $this->db->query("SELECT id_dokter, nama_dokter FROM dokter WHERE status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vFilterAntrian',$data);
		$this->load->view('templates/footer',$data);
	}

	public function filterDataAntrianAksi()
	{
		$unset_session = array(

			'id_dokter',
			'id_sesi',
			'jadwal',
			'konter',
		);

		$this->session->unset_userdata($unset_session);

		$id_dokter 			= $this->input->post('id_dokter');
		$id_sesi 			= $this->input->post('id_sesi');
		$konter 			= $this->input->post('konter');
		$tanggal 			= getDateNow();
		$jam 				= getTimenow();
		$booking_tanggal 	= getDateNow();

		$count = $this->db->query("
			SELECT id_booking
			FROM booking
			WHERE booking.id_dokter='$id_dokter'
			AND booking.id_sesi='$id_sesi'
			AND booking.booking_tanggal='$booking_tanggal'
			");

		$where = array('id_dokter' => $id_dokter);

		$select = $this->mSimetris->selectData('dokter','id_unit',$where)->result();

		foreach ($select as $d) {
			$id_unit = $d->id_unit;
		}

		$cek = $count->num_rows();

		if($cek>0)
		{

			$where = array(
				'id_unit' => $id_unit,
				'konter' => $konter,
			);

			$this->mSimetris->deleteData('antrian',$where);

			$data = array(

				'id_dokter' => $id_dokter,
				'id_unit' 	=> $id_unit,
				'id_sesi' 	=> $id_sesi,
				'tanggal' 	=> $tanggal,
				'jam' 		=> $jam,
				'konter' 	=> $konter,

			);

			$this->mSimetris->insertData('antrian',$data);

			$antdata = array(
				'id_dokter'  		=> $id_dokter,
				'id_sesi'     		=> $id_sesi,
				'id_unit'     		=> $id_unit,
				'booking_tanggal'  	=> $booking_tanggal,
				'konter'     		=> $konter,
			);

			$this->session->set_userdata($antdata);

			$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="5">Data berhasil ditampilkan</font>
				</div>');

			redirect('booking/dataAntrian/dataAntrian');

		}else{

			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="5">Jadwal kosong, data tidak ditemukan</font>
				</div>');

			redirect('booking/dataAntrian/filterDataAntrian/1');

		}		

	}

	public function dataAntrian()
	{
		$data['title'] 		= "Antrian";

		$booking_tanggal 	= $this->session->userdata('booking_tanggal');
		$id_sesi 			= $this->session->userdata('id_sesi');
		$id_dokter 			= $this->session->userdata('id_dokter');

		$data['dataantrian'] = $this->db->query("
			SELECT *, @no:=@no+1 AS noant, dokter.nama_dokter, dokter.id_unit, sesi.nama_sesi,
			IF (booking.status='1', 'Datang', 'Belum Datang') AS status
			FROM booking, dokter, sesi
			JOIN (SELECT @no:=0) r
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.booking_tanggal = '$booking_tanggal'
			AND booking.id_sesi = '$id_sesi'
			AND booking.id_dokter='$id_dokter' ORDER BY booking.id_booking ASC")->result();

		$totalantrian = $this->db->query("
			SELECT @no:=@no+1 AS noant, booking.id_booking, booking.id_catatan_medik, booking.alamat, booking.nama, booking.aktif, booking.antrian, dokter.nama_dokter, dokter.id_unit, sesi.nama_sesi,
			IF (booking.status='1', 'Datang', 'Belum Datang') AS status
			FROM booking, dokter, sesi
			JOIN (SELECT @no:=0) r
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.booking_tanggal = '$booking_tanggal'
			AND booking.id_sesi = '$id_sesi'
			AND booking.id_dokter='$id_dokter' ORDER BY booking.id_booking ASC");

		foreach($totalantrian->result() as $d)
		{
			$data['subtitle'] = $d->nama_dokter;
			$id_unit = $d->id_unit;
		}

		$data['total'] = $totalantrian->num_rows();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vDataAntrian',$data);
		$this->load->view('templates/footer',$data);
	}

	public function aktifAksi($id)
	{
		$booking_tanggal 	= $this->session->userdata('booking_tanggal');
		$id_sesi 			= $this->session->userdata('id_sesi');
		$id_dokter 			= $this->session->userdata('id_dokter');

		$data1 = array('aktif' => '0');
		$where1 = array(
			'aktif' 			=> '1',
			'booking_tanggal' 	=> $booking_tanggal,
			'id_sesi' 			=> $id_sesi,
			'id_dokter' 		=> $id_dokter,
		);

		$data2 = array('aktif' => '1');
		$where2 = array('id_booking' => $id);

		$this->mSimetris->updateData('booking',$data1,$where1);
		$this->mSimetris->updateData('booking',$data2,$where2);

		$datapanggil= $this->db->query("
			SELECT id_booking, nama, FIND_IN_SET( id_booking, (    
			SELECT GROUP_CONCAT( id_booking
			ORDER BY id_booking ASC ) 
			FROM booking 
			WHERE booking_tanggal = '$booking_tanggal'
			AND id_dokter = '$id_dokter'
			AND id_sesi = '$id_sesi')
			) AS noant
			FROM booking
			WHERE id_booking = '$id'");

		foreach($datapanggil->result() as $d)
		{
			$noant = $d->noant;
		}

		$tcounter = array(
			'tcounter'  => $noant,
		);

		$this->session->set_userdata($tcounter);

		redirect('booking/dataAntrian/dataAntrian/'.$id);

	}

	public function selesaiAntrian()
	{
		$id_dokter 			= $this->session->userdata('id_dokter');
		$id_sesi 			= $this->session->userdata('id_sesi');

		$where = array(

			'id_dokter' => $id_dokter,
			'id_sesi' 	=> $id_sesi,

		);

		$this->mSimetris->deleteData('antrian',$where);

		$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Antrian diakhiri</font>
			</div>');
		redirect('booking/dataAntrian/filterDataAntrian/1');
	}

	public function dilayaniMulai($id)
	{
		$getTimenow = getTimenow();
		$data 		= array('mulai' => $getTimenow);
		$where 		= array('id_booking' => $id);

		$this->mSimetris->updateData('booking',$data,$where);
		redirect('booking/dataAntrian/dataAntrian');
	}

	public function dilayaniAkhir($id)
	{
		$getTimenow = getTimenow();
		$data 		= array('akhir' => $getTimenow);
		$where 		= array('id_booking' => $id);

		$this->mSimetris->updateData('booking',$data,$where);
		redirect('booking/dataAntrian/dataAntrian');
	}

} 

?>
