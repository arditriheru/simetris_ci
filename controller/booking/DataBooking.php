<?php 

class dataBooking extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if($this->session->userdata('login') !='1')
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
		$data['title'] 		= "Dashboard";
		$data['subtitle'] 	= "Poliklinik";

		$getDateNow = getDateNow();

		$data['totaldatapoli'] = $this->db->query("SELECT id_booking FROM booking
			WHERE booking.booking_tanggal='$getDateNow'")->num_rows();
		$data['totaldatatumbang'] = $this->db->query("SELECT id_tumbang FROM tumbang
			WHERE tumbang.jadwal='$getDateNow'")->num_rows();
		$data['totaldataanc'] = $this->db->query("SELECT id_anc FROM anc
			WHERE anc.jadwal='$getDateNow'")->num_rows();

		$data['dokterpoli'] = $this->db->query("
			SELECT booking.id_dokter, dokter.nama_dokter
			FROM booking, dokter
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.booking_tanggal='$getDateNow'
			GROUP BY booking.id_dokter")->result();
		$data['doktertumbang'] = $this->db->query("
			SELECT tumbang.id_petugas, mr_petugas.nama_petugas
			FROM tumbang, mr_petugas
			WHERE tumbang.id_petugas=mr_petugas.id_petugas
			AND tumbang.jadwal='$getDateNow'
			GROUP BY tumbang.id_petugas")->result();
		$data['dokteranc'] = $this->db->query("
			SELECT anc.id_petugas, mr_petugas.nama_petugas
			FROM anc, mr_petugas
			WHERE anc.id_petugas=mr_petugas.id_petugas
			AND anc.jadwal='$getDateNow'
			GROUP BY anc.id_petugas")->result();

		$data['poli'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi,
			IF (booking.status='1', 'Datang', 'Belum Datang') AS status
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.booking_tanggal= '$getDateNow'
			ORDER BY booking.id_sesi, dokter.id_dokter, booking.nama ASC")->result();

		$data['tumbang'] = $this->db->query("
			SELECT *, mr_petugas.nama_petugas, sesi.nama_sesi,
			IF (tumbang.status='1', 'Datang', 'Belum Datang') AS status
			FROM tumbang, mr_petugas, sesi
			WHERE tumbang.id_petugas=mr_petugas.id_petugas
			AND tumbang.id_sesi=sesi.id_sesi
			AND tumbang.jadwal='$getDateNow'
			ORDER BY tumbang.id_sesi, tumbang.id_petugas, tumbang.nama ASC")->result();

		$data['anc'] = $this->db->query("
			SELECT *, mr_petugas.nama_petugas, sesi.nama_sesi,
			IF (anc.status='1', 'Datang', 'Belum Datang') AS status
			FROM anc, mr_petugas, sesi
			WHERE anc.id_petugas=mr_petugas.id_petugas
			AND anc.id_sesi=sesi.id_sesi
			AND anc.jadwal='$getDateNow'
			ORDER BY anc.id_sesi, anc.id_petugas, anc.nama ASC")->result();

		// autodelete
		$this->db->query("DELETE FROM booking WHERE DATEDIFF(CURDATE(), booking_tanggal) > 7");
		$this->db->query("DELETE FROM anc WHERE DATEDIFF(CURDATE(), jadwal) > 7");
		$this->db->query("DELETE FROM tumbang WHERE DATEDIFF(CURDATE(), jadwal) > 7");
		$this->db->query("DELETE FROM dokter_jadwal_libur WHERE DATEDIFF(CURDATE(), tanggal) > 1");

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vDataBooking',$data);
		$this->load->view('templates/footer',$data);
	}

	public function ubahDatangPoli($id)
	{
		$data = array('status' => '1');
		$where = array('id_booking' => $id);

		$this->mSimetris->updateData('booking',$data,$where);
		redirect('booking/dataBooking/');
	}

	public function ubahBelumDatangPoli($id)
	{
		$data = array('status' => '2');
		$where = array('id_booking' => $id);

		$this->mSimetris->updateData('booking',$data,$where);
		redirect('booking/dataBooking/');
	}

	public function ubahDatangTumbang($id)
	{
		$data = array('status' => '1');
		$where = array('id_tumbang' => $id);

		$this->mSimetris->updateData('tumbang',$data,$where);
		redirect('booking/dataBooking/');
	}

	public function ubahBelumDatangTumbang($id)
	{
		$data = array('status' => '0');
		$where = array('id_tumbang' => $id);

		$this->mSimetris->updateData('tumbang',$data,$where);
		redirect('booking/dataBooking/');
	}

	public function ubahDatangAnc($id)
	{
		$data = array('status' => '1');
		$where = array('id_anc' => $id);

		$this->mSimetris->updateData('anc',$data,$where);
		redirect('booking/dataBooking/');
	}

	public function ubahBelumDatangAnc($id)
	{
		$data = array('status' => '0');
		$where = array('id_anc' => $id);

		$this->mSimetris->updateData('anc',$data,$where);
		redirect('booking/dataBooking/');
	}

	public function detailDataPoli($id)
	{
		$data['title'] 		= "Detail";
		$data['subtitle'] 	= "Poliklinik";

		$where = array('id_booking' => $id);
		$data['poli'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi,
			IF (booking.status='1', 'Datang', 'Belum Datang') AS status
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.id_booking='$id'")->result();
		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vDataDetailPoli',$data,$id);
		$this->load->view('templates/footer',$data);
	}

	public function detailDataTumbang($id)
	{
		$data['title'] 		= "Detail";
		$data['subtitle'] 	= "Tumbuh Kembang";

		$where = array('id_tumbang' => $id);
		$data['tumbang'] = $this->db->query("
			SELECT *, mr_petugas.nama_petugas, sesi.nama_sesi,
			IF (tumbang.status='1', 'Datang', 'Belum Datang') AS status
			FROM tumbang, mr_petugas, sesi
			WHERE tumbang.id_petugas=mr_petugas.id_petugas
			AND tumbang.id_sesi=sesi.id_sesi
			AND tumbang.id_tumbang='$id'")->result();
		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vDataDetailTumbang',$data,$id);
		$this->load->view('templates/footer',$data);
	}

	public function detailDataAnc($id)
	{
		$data['title'] 		= "Detail";
		$data['subtitle'] 	= "ANC";

		$where = array('id_anc' => $id);
		$data['anc'] = $this->db->query("
			SELECT *, mr_petugas.nama_petugas, sesi.nama_sesi,
			IF (anc.status='1', 'Datang', 'Belum Datang') AS status
			FROM anc, mr_petugas, sesi
			WHERE anc.id_petugas=mr_petugas.id_petugas
			AND anc.id_sesi=sesi.id_sesi
			AND anc.id_anc='$id'")->result();
		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vDataDetailAnc',$data,$id);
		$this->load->view('templates/footer',$data);
	}

	public function updateDataPoli($id)
	{
		$data['title'] 		= "Update";
		$data['subtitle'] 	= "Poliklinik";

		$data['id'] = $id;
		$data['datapoli'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.id_booking = '$id'")->result();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vUpdateDataPoli',$data);
		$this->load->view('templates/footer',$data);
	}

	public function updateDataPoliAksi()
	{
		$id 		= $this->input->post('id');
		$nama 		= $this->input->post('nama');
		$alamat 	= $this->input->post('alamat');
		$kontak 	= $this->input->post('kontak');
		$keterangan = $this->input->post('keterangan');

		$data = array(

			'nama' 		 => $nama,
			'alamat' 	 => $alamat,
			'kontak' 	 => $kontak,
			'keterangan' => $keterangan,

		);

		$where = array(
			'id_booking' 		=> $id
		);

		$this->mSimetris->updateData('booking',$data,$where);
		$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Data berhasil diupdate</font>
			</div>');
		redirect('booking/dataBooking/');

	}

	public function deleteDataPoli($id)
	{
		$where = array('id_booking' => $id);
		$this->mSimetris->deleteData('booking',$where);
		$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Data berhasil dihapus</font>
			</div>');
		redirect('booking/dataBooking');

	}

	public function deleteDataTumbang($id)
	{
		$where = array('id_tumbang' => $id);
		$this->mSimetris->deleteData('tumbang',$where);
		$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Data berhasil dihapus</font>
			</div>');
		redirect('booking/dataBooking');

	}

	public function deleteDataAnc($id)
	{
		$where = array('id_anc' => $id);
		$this->mSimetris->deleteData('anc',$where);
		$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Data berhasil dihapus</font>
			</div>');
		redirect('booking/dataBooking');

	}

	public function tabDataPoli($id)
	{
		$data['title'] 		= "Tab";
		$data['subtitle'] 	= "Poliklinik";

		$getDateNow = getDateNow();

		$data['totaldatapoli'] = $this->db->query("SELECT id_booking FROM booking
			WHERE booking.booking_tanggal='$getDateNow' AND booking.id_dokter='$id'")->num_rows();
		$data['totaldatapoli1'] = $this->db->query("SELECT id_booking FROM booking
			WHERE booking.booking_tanggal='$getDateNow' AND booking.id_dokter='$id' AND id_sesi=1")->num_rows();
		$data['totaldatapoli2'] = $this->db->query("SELECT id_booking FROM booking
			WHERE booking.booking_tanggal='$getDateNow' AND booking.id_dokter='$id' AND id_sesi=2")->num_rows();
		$data['totaldatapoli3'] = $this->db->query("SELECT id_booking FROM booking
			WHERE booking.booking_tanggal='$getDateNow' AND booking.id_dokter='$id' AND id_sesi=3")->num_rows();
		$data['totaldatapoli4'] = $this->db->query("SELECT id_booking FROM booking
			WHERE booking.booking_tanggal='$getDateNow' AND booking.id_dokter='$id' AND id_sesi=4")->num_rows();


		$data['dokterpoli'] = $this->db->query("
			SELECT booking.id_dokter, dokter.nama_dokter
			FROM booking, dokter
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.booking_tanggal='$getDateNow'
			GROUP BY booking.id_dokter")->result();

		$where = array('id_booking' => $id);
		$data['poli'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi,
			IF (booking.status='1', 'Datang', 'Belum Datang') AS status
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.booking_tanggal='$getDateNow'
			AND booking.id_dokter='$id'
			ORDER BY booking.id_sesi, booking.id_booking ASC")->result();
		$data['poli1'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi,
			IF (booking.status='1', 'Datang', 'Belum Datang') AS status
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.booking_tanggal='$getDateNow'
			AND booking.id_dokter='$id'
			AND booking.id_sesi=1
			ORDER BY booking.id_sesi, booking.id_booking ASC")->result();
		$data['poli2'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi,
			IF (booking.status='1', 'Datang', 'Belum Datang') AS status
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.booking_tanggal='$getDateNow'
			AND booking.id_dokter='$id'
			AND booking.id_sesi=2
			ORDER BY booking.id_sesi, booking.id_booking ASC")->result();
		$data['poli3'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi,
			IF (booking.status='1', 'Datang', 'Belum Datang') AS status
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.booking_tanggal='$getDateNow'
			AND booking.id_dokter='$id'
			AND booking.id_sesi=3
			ORDER BY booking.id_sesi, booking.id_booking ASC")->result();
		$data['poli4'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi,
			IF (booking.status='1', 'Datang', 'Belum Datang') AS status
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.booking_tanggal='$getDateNow'
			AND booking.id_dokter='$id'
			AND booking.id_sesi=4
			ORDER BY booking.id_sesi, booking.id_booking ASC")->result();
		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vDataTabPoli',$data,$id);
		$this->load->view('templates/footer',$data);
	}

	public function dataRegister()
	{
		$data['title'] 		= "Registrasi";
		$data['subtitle'] 	= "Poliklinik";

		$getDateNow = getDateNow();

		$data['poli'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.tanggal='$getDateNow'
			ORDER BY booking.id_booking DESC")->result();

		$data['tumbang'] = $this->db->query("
			SELECT *, mr_petugas.nama_petugas, sesi.nama_sesi FROM tumbang, mr_petugas, sesi
			WHERE tumbang.id_petugas=mr_petugas.id_petugas
			AND tumbang.id_sesi=sesi.id_sesi
			AND tumbang.tanggal = '$getDateNow'
			ORDER BY tumbang.id_tumbang DESC")->result();

		$data['anc'] = $this->db->query("
			SELECT *, mr_petugas.nama_petugas, sesi.nama_sesi FROM anc, mr_petugas, sesi
			WHERE anc.id_petugas=mr_petugas.id_petugas
			AND anc.id_sesi=sesi.id_sesi
			AND anc.tanggal = '$getDateNow'
			ORDER BY anc.id_anc DESC")->result();

		$data['mandiri'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.tanggal='$getDateNow'
			AND booking.mandiri='1'
			ORDER BY booking.id_booking DESC")->result();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vDataRegister',$data);
		$this->load->view('templates/footer',$data);
	}

// proses tambah data booking poliklinik

	public function tambahDataPoli($id)
	{
		$data['id'] = $id;

		if($id==1){
			$data['title'] 		= "Lihat";
		}else{
			$data['title'] 		= "Tambah";
		}
		$data['subtitle'] 	= "Poliklinik";

		$data['datadokter'] = $this->db->query("SELECT id_dokter, nama_dokter FROM dokter WHERE status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$data['record'] = $this->mSimetris->getData('mr_pasien');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataPoli',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataPoliRm()
	{
		$data['title'] 		= "Tambah";
		$data['subtitle'] 	= "Poliklinik";

		$id = $this->input->get('rm');

		$where = array('id_catatan_medik' => $id);

		$data['datapasien'] = $this->mSimetris->selectData('mr_pasien','id_catatan_medik,nama,alamat,telp',$where)->result();

		$data['datadokter'] = $this->db->query("SELECT id_dokter, nama_dokter FROM dokter WHERE status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$data['record'] = $this->mSimetris->getData('mr_pasien');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataPoliRm',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataPoliCariNm()
	{
		$data['title'] 		= "Tambah";
		$data['subtitle'] 	= "Poliklinik";

		$id = $this->input->get('nm');

		$keyword = array('nama' => $id);

		$data['caripasien'] = $this->mSimetris->cariNamaPasienData('mr_pasien',$keyword)->result();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataPoliCariNm',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataPoliNm($id)
	{
		$data['title'] 		= "Tambah";
		$data['subtitle'] 	= "Poliklinik";

		$where = array('id_catatan_medik' => $id);

		$data['datapasien'] = $this->mSimetris->selectData('mr_pasien','id_catatan_medik,nama,alamat,telp',$where)->result();

		$data['datadokter'] = $this->db->query("SELECT id_dokter, nama_dokter FROM dokter WHERE status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$data['record'] = $this->mSimetris->getData('mr_pasien');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataPoliNm',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataPoliBaruAksi()
	{

		$id 			  = $this->input->post('id');
		$nama             = $this->input->post('nama');
		$alamat           = $this->input->post('alamat');
		$kontak           = $this->input->post('kontak');
		$id_catatan_medik = '0';
		$booking_tanggal  = $this->input->post('booking_tanggal');
		$tanggal          = getDatenow();
		$jam              = getTimenow();
		$status           = '2';
		$keterangan       = $this->input->post('keterangan');
		$id_dokter        = $this->input->post('id_dokter');
		$id_sesi          = $this->input->post('id_sesi');
		$mandiri          = '0';
		$antrian          = '0';
		$aktif 			  = '0';
		$tgl1      		  = new DateTime();
		$tgl2             = new DateTime("$booking_tanggal");
		$selisih          = $tgl1->diff($tgl2)->format("%a");

		if($selisih>30)
		{

			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="5">Lebih dari 30 hari</font></div>');
			redirect('booking/dataBooking/tambahDataPoli/'.$id);

		}else{

			$hari = date('w', strtotime($booking_tanggal));

			$where2 = array(
				'id_dokter' => $id_dokter,
				'id_sesi' 	=> $id_sesi,
				'hari' 		=> $hari,
			);

			$cekjadwal = $this->mSimetris->countData('dokter_jadwal',$where2);

			if($cekjadwal>0)
			{

				$where3 = array(
					'id_dokter' => $id_dokter,
					'id_sesi' 	=> $id_sesi,
					'tanggal' 	=> $booking_tanggal,
				);

				$ceklibur = $this->mSimetris->countData('dokter_jadwal_libur',$where3);

				if($ceklibur>0)
				{

					$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<font size="5">Jadwal Dokter Cuti</font></div>');
					redirect('booking/dataBooking/tambahDataPoli/'.$id);

				}else{

					$hari = date('w', strtotime($booking_tanggal));

					$where4 = array(
						'booking_tanggal' 	=> $booking_tanggal,
						'id_dokter' 		=> $id_dokter,
						'id_sesi' 			=> $id_sesi,
					);

					$count = $this->mSimetris->countData('booking',$where4);
					$noant = $count+1;

					$where5 = array(
						'id_dokter' => $id_dokter,
						'id_sesi' 	=> $id_sesi,
						'hari' 		=> $hari,
					);

					$kuota = $this->mSimetris->selectData('dokter_jadwal','kuota',$where5);
					foreach($kuota->result() as $d)
					{
						$cekkuota = $d->kuota;
					}

					if($noant>$cekkuota)
					{

						$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<font size="5">Kuota Penuh</font></div>');
						redirect('booking/dataBooking/tambahDataPoli/'.$id);

					}else{

						$data = array(

							'nama' 				=> $nama,
							'alamat' 			=> $alamat,
							'kontak' 			=> $kontak,
							'id_catatan_medik' 	=> $id_catatan_medik,
							'booking_tanggal' 	=> $booking_tanggal,
							'tanggal' 			=> $tanggal,
							'jam' 				=> $jam,
							'status' 			=> $status,
							'keterangan' 		=> $keterangan,
							'id_dokter' 		=> $id_dokter,
							'id_sesi' 			=> $id_sesi,
							'mandiri' 			=> $mandiri,
							'antrian' 			=> $antrian,
							'aktif' 			=> $aktif,
						);

						$this->mSimetris->insertData('booking',$data);
						$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Berhasil mendaftar, <font size="5"><b>Nomor Antrian : 
							'.$noant.'</b></font></div>');
						redirect('booking/dataBooking/tambahDataPoli/'.$id);

					}

				}

			}else{

				$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<font size="5">Jadwal Dokter Kosong</font></div>');
				redirect('booking/dataBooking/tambahDataPoli/'.$id);

			}
		}
	}

	public function tambahDataPoliLamaAksi()
	{

		$id 			  = '2';
		$nama             = $this->input->post('nama');
		$alamat           = $this->input->post('alamat');
		$kontak           = $this->input->post('kontak');
		$id_catatan_medik = $this->input->post('id_catatan_medik');
		$booking_tanggal  = $this->input->post('booking_tanggal');
		$tanggal          = getDatenow();
		$jam              = getTimenow();
		$status           = '2';
		$keterangan       = $this->input->post('keterangan');
		$id_dokter        = $this->input->post('id_dokter');
		$id_sesi          = $this->input->post('id_sesi');
		$mandiri          = '0';
		$antrian          = '0';
		$aktif 			  = '0';
		$tgl1      		  = new DateTime();
		$tgl2             = new DateTime("$booking_tanggal");
		$selisih          = $tgl1->diff($tgl2)->format("%a");

		if($selisih>30)
		{

			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="5">Lebih dari 30 hari</font></div>');
			redirect('booking/dataBooking/tambahDataPoli/'.$id);

		}else{

			$where1 = array(
				'id_catatan_medik' 	=> $id_catatan_medik,
				'id_dokter' 		=> $id_dokter,
				'booking_tanggal' 	=> $booking_tanggal,
				'id_sesi' 			=> $id_sesi,
			);

			$cekdaftar = $this->mSimetris->countData('booking',$where1);

			if($cekdaftar>0)
			{

				$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<font size="5">Sudah Mendaftar Sebelumnya</font></div>');
				redirect('booking/dataBooking/tambahDataPoli/'.$id);

			}else{

				$hari = date('w', strtotime($booking_tanggal));

				$where2 = array(
					'id_dokter' => $id_dokter,
					'id_sesi' 	=> $id_sesi,
					'hari' 		=> $hari,
				);

				$cekjadwal = $this->mSimetris->countData('dokter_jadwal',$where2);

				if($cekjadwal>0)
				{

					$where3 = array(
						'id_dokter' => $id_dokter,
						'id_sesi' 	=> $id_sesi,
						'tanggal' 	=> $booking_tanggal,
					);

					$ceklibur = $this->mSimetris->countData('dokter_jadwal_libur',$where3);

					if($ceklibur>0)
					{

						$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<font size="5">Jadwal Dokter Cuti</font></div>');
						redirect('booking/dataBooking/tambahDataPoli/'.$id);

					}else{

						$hari = date('w', strtotime($booking_tanggal));

						$where4 = array(
							'booking_tanggal' 	=> $booking_tanggal,
							'id_dokter' 		=> $id_dokter,
							'id_sesi' 			=> $id_sesi,
						);

						$count = $this->mSimetris->countData('booking',$where4);
						$noant = $count+1;

						$where5 = array(
							'id_dokter' => $id_dokter,
							'id_sesi' 	=> $id_sesi,
							'hari' 		=> $hari,
						);

						$kuota = $this->mSimetris->selectData('dokter_jadwal','kuota',$where5);
						foreach($kuota->result() as $d)
						{
							$cekkuota = $d->kuota;
						}

						if($noant>$cekkuota)
						{

							$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<font size="5">Kuota Penuh</font></div>');
							redirect('booking/dataBooking/tambahDataPoli/'.$id);

						}else{

							$data = array(

								'nama' 				=> $nama,
								'alamat' 			=> $alamat,
								'kontak' 			=> $kontak,
								'id_catatan_medik' 	=> $id_catatan_medik,
								'booking_tanggal' 	=> $booking_tanggal,
								'tanggal' 			=> $tanggal,
								'jam' 				=> $jam,
								'status' 			=> $status,
								'keterangan' 		=> $keterangan,
								'id_dokter' 		=> $id_dokter,
								'id_sesi' 			=> $id_sesi,
								'mandiri' 			=> $mandiri,
								'antrian' 			=> $antrian,
								'aktif' 			=> $aktif,
							);

							$this->mSimetris->insertData('booking',$data);
							$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								Berhasil mendaftar <font size="5"><b>Nomor Antrian : 
								'.$noant.'</b></font></div>');
							redirect('booking/dataBooking/tambahDataPoli/'.$id);

						}

					}

				}else{

					$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<font size="5">Jadwal Dokter Kosong</font></div>');
					redirect('booking/dataBooking/tambahDataPoli/'.$id);

				}
			}

		}

	}

	public function cariDataPoliAksi()
	{
		$id_dokter 			= $this->input->post('id_dokter');
		$id_sesi 			= $this->input->post('id_sesi');
		$booking_tanggal 	= $this->input->post('booking_tanggal');

		$totaldata = $this->db->query("
			SELECT id_booking
			FROM booking
			WHERE booking_tanggal = '$booking_tanggal'
			AND id_sesi = '$id_sesi'
			AND id_dokter='$id_dokter'"); 

		$data['booking'] = $this->db->query("
			SELECT *, dokter.nama_dokter, sesi.nama_sesi,
			IF (booking.status='1', 'Datang', 'Belum Datang') AS status
			FROM booking, dokter, sesi
			WHERE booking.id_dokter=dokter.id_dokter
			AND booking.id_sesi=sesi.id_sesi
			AND booking.booking_tanggal = '$booking_tanggal'
			AND booking.id_sesi = '$id_sesi'
			AND booking.id_dokter='$id_dokter'
			ORDER BY booking.id_booking ASC")->result();

		if(isset($id_dokter)) 
		{
			$data['title'] 		= $totaldata->num_rows()." Pasien";
			$data['subtitle'] 	= formatDateIndo($booking_tanggal);

		}

		$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Data berhasil ditampilkan</font>
			</div>');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataPoli',$data);
		$this->load->view('templates/footer',$data);
	}

	// proses tambah data booking tumbuh kembang

	public function tambahDataTumbang($id)
	{
		$data['id'] = $id;

		if($id==1){
			$data['title'] 		= "Lihat";
		}else{
			$data['title'] 		= "Tambah";
		}
		$data['subtitle'] 	= "Tumbuh Kembang";

		$data['datapetugas'] = $this->db->query("SELECT id_petugas, nama_petugas FROM mr_petugas WHERE pelayanan='1' AND status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$data['record'] = $this->mSimetris->getData('mr_pasien');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataTumbang',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataTumbangRm()
	{
		$data['title'] 		= "Tambah";
		$data['subtitle'] 	= "Tumbuh Kembang";

		$id = $this->input->get('rm');

		$where = array('id_catatan_medik' => $id);

		$data['datapasien'] = $this->mSimetris->selectData('mr_pasien','id_catatan_medik,nama,alamat,telp',$where)->result();

		$data['datapetugas'] = $this->db->query("SELECT id_petugas, nama_petugas FROM mr_petugas WHERE pelayanan='1' AND status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$data['record'] = $this->mSimetris->getData('mr_pasien');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataTumbangRm',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataTumbangCariNm()
	{
		$data['title'] 		= "Tambah";
		$data['subtitle'] 	= "Tumbuh Kembang";

		$id = $this->input->get('nm');

		$keyword = array('nama' => $id);

		$data['caripasien'] = $this->mSimetris->cariNamaPasienData('mr_pasien',$keyword)->result();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataTumbangCariNm',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataTumbangNm($id)
	{
		$data['title'] 		= "Tambah";
		$data['subtitle'] 	= "Tumbuh Kembang";

		$where = array('id_catatan_medik' => $id);

		$data['datapasien'] = $this->mSimetris->selectData('mr_pasien','id_catatan_medik,nama,alamat,telp',$where)->result();

		$data['datapetugas'] = $this->db->query("SELECT id_petugas, nama_petugas FROM mr_petugas WHERE pelayanan='1' AND status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$data['record'] = $this->mSimetris->getData('mr_pasien');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataTumbangNm',$data);
		$this->load->view('templates/footer',$data);
	}

	public function cariDataTumbangAksi()
	{
		$id_petugas 		= $this->input->post('id_petugas');
		$id_sesi 			= $this->input->post('id_sesi');
		$jadwal 			= $this->input->post('jadwal');

		$totaldata = $this->db->query("
			SELECT id_tumbang
			FROM tumbang
			WHERE jadwal = '$jadwal'
			AND id_sesi = '$id_sesi'
			AND id_petugas ='$id_petugas '");

		$data['tumbang'] = $this->db->query("
			SELECT *, mr_petugas.nama_petugas, sesi.nama_sesi,
			IF (tumbang.status='1', 'Datang', 'Belum Datang') AS status
			FROM tumbang, mr_petugas, sesi
			WHERE tumbang.id_petugas=mr_petugas.id_petugas
			AND tumbang.id_sesi=sesi.id_sesi
			AND tumbang.jadwal = '$jadwal'
			AND tumbang.id_sesi = '$id_sesi'
			AND tumbang.id_petugas='$id_petugas'
			ORDER BY tumbang.id_tumbang ASC")->result();

		if(isset($id_petugas)) 
		{
			$data['title'] 		= $totaldata->num_rows()." Pasien";
			$data['subtitle'] 	= formatDateIndo($jadwal);

		}

		$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Data berhasil ditampilkan</font>
			</div>');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataTumbang',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataTumbangBaruAksi()
	{

		$id 			  = '2';
		$nama             = $this->input->post('nama');
		$alamat           = $this->input->post('alamat');
		$kontak           = $this->input->post('kontak');
		$id_catatan_medik = '0';
		$jadwal 		  = $this->input->post('jadwal');
		$tanggal          = getDatenow();
		$jam              = getTimenow();
		$status           = '2';
		$keterangan       = $this->input->post('keterangan');
		$id_petugas       = $this->input->post('id_petugas');
		$id_sesi          = $this->input->post('id_sesi');
		$tgl1      		  = new DateTime();
		$tgl2             = new DateTime("$jadwal");
		$selisih          = $tgl1->diff($tgl2)->format("%a");

		if($selisih>30)
		{

			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="5">Lebih dari 30 hari</font></div>');
			redirect('booking/dataBooking/tambahDataTumbang/'.$id);

		}else{

			$where2 = array(
				'jadwal' 			=> $jadwal,
				'id_petugas' 		=> $id_petugas,
				'id_sesi' 			=> $id_sesi,
			);

			$count = $this->mSimetris->countData('tumbang',$where2);
			$noant = $count+1;

			$data = array(

				'nama' 				=> $nama,
				'alamat' 			=> $alamat,
				'kontak' 			=> $kontak,
				'id_catatan_medik' 	=> $id_catatan_medik,
				'jadwal' 			=> $jadwal,
				'tanggal' 			=> $tanggal,
				'jam' 				=> $jam,
				'status' 			=> $status,
				'keterangan' 		=> $keterangan,
				'id_petugas' 		=> $id_petugas,
				'id_sesi' 			=> $id_sesi,

			);

			$this->mSimetris->insertData('tumbang',$data);
			$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				Berhasil mendaftar <font size="5"><b>Nomor Antrian : 
				'.$noant.'</b></font></div>');
			redirect('booking/dataBooking/tambahDataTumbang/'.$id);

		}

	}

	public function tambahDataTumbangLamaAksi()
	{

		$id 			  = '2';
		$nama             = $this->input->post('nama');
		$alamat           = $this->input->post('alamat');
		$kontak           = $this->input->post('kontak');
		$id_catatan_medik = $this->input->post('id_catatan_medik');
		$jadwal 		  = $this->input->post('jadwal');
		$tanggal          = getDatenow();
		$jam              = getTimenow();
		$status           = '2';
		$keterangan       = $this->input->post('keterangan');
		$id_petugas       = $this->input->post('id_petugas');
		$id_sesi          = $this->input->post('id_sesi');
		$tgl1      		  = new DateTime();
		$tgl2             = new DateTime("$jadwal");
		$selisih          = $tgl1->diff($tgl2)->format("%a");

		if($selisih>30)
		{

			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="5">Lebih dari 30 hari</font></div>');
			redirect('booking/dataBooking/tambahDataTumbang/'.$id);

		}else{

			$where1 = array(
				'id_catatan_medik' 	=> $id_catatan_medik,
				'id_petugas' 		=> $id_petugas,
				'jadwal' 			=> $jadwal,
				'id_sesi' 			=> $id_sesi,
			);

			$cekdaftar = $this->mSimetris->countData('tumbang',$where1);

			if($cekdaftar>0)
			{

				$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<font size="5">Sudah Mendaftar Sebelumnya</font></div>');
				redirect('booking/dataBooking/tambahDataTumbang/'.$id);

			}else{

				$where2 = array(
					'jadwal' 			=> $jadwal,
					'id_petugas' 		=> $id_petugas,
					'id_sesi' 			=> $id_sesi,
				);

				$count = $this->mSimetris->countData('tumbang',$where2);
				$noant = $count+1;

				$data = array(

					'nama' 				=> $nama,
					'alamat' 			=> $alamat,
					'kontak' 			=> $kontak,
					'id_catatan_medik' 	=> $id_catatan_medik,
					'jadwal' 			=> $jadwal,
					'tanggal' 			=> $tanggal,
					'jam' 				=> $jam,
					'status' 			=> $status,
					'keterangan' 		=> $keterangan,
					'id_petugas' 		=> $id_petugas,
					'id_sesi' 			=> $id_sesi,

				);

				$this->mSimetris->insertData('tumbang',$data);
				$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Berhasil mendaftar <font size="5"><b>Nomor Antrian : 
					'.$noant.'</b></font></div>');
				redirect('booking/dataBooking/tambahDataTumbang/'.$id);

			}
		}

	}

// proses tambah data booking ANC terpadu

	public function tambahDataAnc($id)
	{
		$data['id'] = $id;

		if($id==1){
			$data['title'] 		= "Lihat";
		}else{
			$data['title'] 		= "Tambah";
		}
		$data['subtitle'] 	= "ANC Terpadu";

		$data['datapetugas'] = $this->db->query("SELECT id_petugas, nama_petugas FROM mr_petugas WHERE pelayanan='2' AND status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$data['record'] = $this->mSimetris->getData('mr_pasien');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataAnc',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataAncRm()
	{
		$data['title'] 		= "Tambah";
		$data['subtitle'] 	= "ANC Terpadu";

		$id = $this->input->get('rm');

		$where = array('id_catatan_medik' => $id);

		$data['datapasien'] = $this->mSimetris->selectData('mr_pasien','id_catatan_medik,nama,alamat,telp',$where)->result();

		$data['datapetugas'] = $this->db->query("SELECT id_petugas, nama_petugas FROM mr_petugas WHERE pelayanan='2' AND status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$data['record'] = $this->mSimetris->getData('mr_pasien');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataAncRm',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataAncCariNm()
	{
		$data['title'] 		= "Tambah";
		$data['subtitle'] 	= "ANC Terpadu";

		$id = $this->input->get('nm');

		$keyword = array('nama' => $id);

		$data['caripasien'] = $this->mSimetris->cariNamaPasienData('mr_pasien',$keyword)->result();

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataAncCariNm',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataAncNm($id)
	{
		$data['title'] 		= "Tambah";
		$data['subtitle'] 	= "ANC Terpadu";

		$where = array('id_catatan_medik' => $id);

		$data['datapasien'] = $this->mSimetris->selectData('mr_pasien','id_catatan_medik,nama,alamat,telp',$where)->result();

		$data['datapetugas'] = $this->db->query("SELECT id_petugas, nama_petugas FROM mr_petugas WHERE pelayanan='2' AND status='1'")->result();
		$data['datasesi'] = $this->db->query("SELECT id_sesi, nama_sesi FROM sesi")->result();

		$data['record'] = $this->mSimetris->getData('mr_pasien');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataAncNm',$data);
		$this->load->view('templates/footer',$data);
	}

	public function cariDataAncAksi()
	{
		$id_petugas 		= $this->input->post('id_petugas');
		$id_sesi 			= $this->input->post('id_sesi');
		$jadwal 			= $this->input->post('jadwal');

		$totaldata = $this->db->query("
			SELECT id_anc
			FROM anc
			WHERE jadwal = '$jadwal'
			AND id_sesi = '$id_sesi'
			AND id_petugas ='$id_petugas '");

		$data['anc'] = $this->db->query("
			SELECT *, mr_petugas.nama_petugas, sesi.nama_sesi,
			IF (anc.status='1', 'Datang', 'Belum Datang') AS status
			FROM anc, mr_petugas, sesi
			WHERE anc.id_petugas=mr_petugas.id_petugas
			AND anc.id_sesi=sesi.id_sesi
			AND anc.jadwal = '$jadwal'
			AND anc.id_sesi = '$id_sesi'
			AND anc.id_petugas='$id_petugas'
			ORDER BY anc.id_anc ASC")->result();

		if(isset($id_petugas)) 
		{
			$data['title'] 		= $totaldata->num_rows()." Pasien";
			$data['subtitle'] 	= formatDateIndo($jadwal);

		}

		$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="5">Data berhasil ditampilkan</font>
			</div>');

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vTambahDataAnc',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahDataAncBaruAksi()
	{

		$id 			  = '2';
		$nama             = $this->input->post('nama');
		$alamat           = $this->input->post('alamat');
		$kontak           = $this->input->post('kontak');
		$id_catatan_medik = '0';
		$jadwal 		  = $this->input->post('jadwal');
		$tanggal          = getDatenow();
		$jam              = getTimenow();
		$status           = '2';
		$keterangan       = $this->input->post('keterangan');
		$id_petugas       = $this->input->post('id_petugas');
		$id_sesi          = $this->input->post('id_sesi');
		$tgl1      		  = new DateTime();
		$tgl2             = new DateTime("$jadwal");
		$selisih          = $tgl1->diff($tgl2)->format("%a");

		if($selisih>30)
		{

			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="5">Lebih dari 30 hari</font></div>');
			redirect('booking/dataBooking/tambahDataAnc/'.$id);

		}else{

			$where2 = array(
				'jadwal' 			=> $jadwal,
				'id_petugas' 		=> $id_petugas,
				'id_sesi' 			=> $id_sesi,
			);

			$count = $this->mSimetris->countData('anc',$where2);
			$noant = $count+1;

			$data = array(

				'nama' 				=> $nama,
				'alamat' 			=> $alamat,
				'kontak' 			=> $kontak,
				'id_catatan_medik' 	=> $id_catatan_medik,
				'jadwal' 			=> $jadwal,
				'tanggal' 			=> $tanggal,
				'jam' 				=> $jam,
				'status' 			=> $status,
				'keterangan' 		=> $keterangan,
				'id_petugas' 		=> $id_petugas,
				'id_sesi' 			=> $id_sesi,

			);

			$this->mSimetris->insertData('anc',$data);
			$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				Berhasil mendaftar <font size="5"><b>Nomor Antrian : 
				'.$noant.'</b></font></div>');
			redirect('booking/dataBooking/tambahDataAnc/'.$id);

		}

	}

	public function tambahDataAncLamaAksi()
	{

		$id 			  = '2';
		$nama             = $this->input->post('nama');
		$alamat           = $this->input->post('alamat');
		$kontak           = $this->input->post('kontak');
		$id_catatan_medik = $this->input->post('id_catatan_medik');
		$jadwal 		  = $this->input->post('jadwal');
		$tanggal          = getDatenow();
		$jam              = getTimenow();
		$status           = '2';
		$keterangan       = $this->input->post('keterangan');
		$id_petugas       = $this->input->post('id_petugas');
		$id_sesi          = $this->input->post('id_sesi');
		$tgl1      		  = new DateTime();
		$tgl2             = new DateTime("$jadwal");
		$selisih          = $tgl1->diff($tgl2)->format("%a");

		if($selisih>30)
		{

			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="5">Lebih dari 30 hari</font></div>');
			redirect('booking/dataBooking/tambahDataAnc/'.$id);

		}else{

			$where1 = array(
				'id_catatan_medik' 	=> $id_catatan_medik,
				'id_petugas' 		=> $id_petugas,
				'jadwal' 			=> $jadwal,
				'id_sesi' 			=> $id_sesi,
			);

			$cekdaftar = $this->mSimetris->countData('anc',$where1);

			if($cekdaftar>0)
			{

				$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<font size="5">Sudah Mendaftar Sebelumnya</font></div>');
				redirect('booking/dataBooking/tambahDataAnc/'.$id);

			}else{

				$where2 = array(
					'jadwal' 			=> $jadwal,
					'id_petugas' 		=> $id_petugas,
					'id_sesi' 			=> $id_sesi,
				);

				$count = $this->mSimetris->countData('anc',$where2);
				$noant = $count+1;

				$data = array(

					'nama' 				=> $nama,
					'alamat' 			=> $alamat,
					'kontak' 			=> $kontak,
					'id_catatan_medik' 	=> $id_catatan_medik,
					'jadwal' 			=> $jadwal,
					'tanggal' 			=> $tanggal,
					'jam' 				=> $jam,
					'status' 			=> $status,
					'keterangan' 		=> $keterangan,
					'id_petugas' 		=> $id_petugas,
					'id_sesi' 			=> $id_sesi,

				);

				$this->mSimetris->insertData('anc',$data);
				$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Berhasil mendaftar <font size="5"><b>Nomor Antrian : 
					'.$noant.'</b></font></div>');
				redirect('booking/dataBooking/tambahDataAnc/'.$id);

			}
		}

	}

	public function dataRedZone()
	{
		$data['title'] 		= "Redzone";
		$data['subtitle'] 	= "COVID-19";

		$this->load->view('templates/header',$data);
		$this->load->view('booking/vMenu',$data);
		$this->load->view('booking/vRedZone',$data);
		$this->load->view('templates/footer',$data);
	}

	public function _rules()
	{
		$this->form_validation->set_rules('id_catatan_medik','nomor rekam medik','required');
		$this->form_validation->set_rules('id_dokter','nama dokter','required');
		$this->form_validation->set_rules('id_sesi','nama sesi','required');
	}

}

?>
