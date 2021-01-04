<?php 

class dataRapidtest extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if($this->session->userdata('login') !='1')
		{
			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="4"><b>Anda belum login!</b></font>
				</div>');
			redirect('covid/login');
		}

		$this->load->model("mSimetris");
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data['title'] 		= "Dashboard";
		$data['subtitle'] 	= "Rapidtest";

		$data['nonreaktif'] = $this->db->query("SELECT id_catatan_medik FROM rapidtest WHERE igg=0 OR igm=0")->num_rows();
		$data['iggreaktif'] = $this->db->query("SELECT id_catatan_medik FROM rapidtest WHERE igg=1")->num_rows();
		$data['igmreaktif'] = $this->db->query("SELECT id_catatan_medik FROM rapidtest WHERE igm=1")->num_rows();
		$data['totaldata'] = $this->db->query("SELECT * FROM rapidtest ORDER BY id_rapidtest DESC")->num_rows();

		// $data['datarapidtest'] = $this->mSimetris->getData('rapidtest')->result();
		$data['rapidtest'] = $this->db->query("
			SELECT rapidtest.id_rapidtest, rapidtest.id_catatan_medik, rapidtest.tanggal, rapidtest.jam, mr_pasien.nama, mr_dokter.nama_dokter,
			CASE
			WHEN rapidtest.igm='0' THEN 'Non Reaktif'
			WHEN rapidtest.igm='1' THEN 'Reaktif'
			WHEN rapidtest.igm='3' THEN 'On Process'
			END AS nama_igm,
			CASE
			WHEN rapidtest.igg='0' THEN 'Non Reaktif'
			WHEN rapidtest.igg='1' THEN 'Reaktif'
			WHEN rapidtest.igg='3' THEN 'On Process'
			END AS nama_igg
			FROM rapidtest, mr_pasien, mr_dokter
			WHERE rapidtest.id_catatan_medik=mr_pasien.id_catatan_medik
			AND rapidtest.id_dokter=mr_dokter.id_dokter
			ORDER BY rapidtest.id_rapidtest DESC LIMIT 50")->result();
		
		$this->load->view('templates/header',$data);
		$this->load->view('covid/vMenu',$data);
		$this->load->view('covid/vDataRapidtest',$data);
		$this->load->view('templates/footer',$data);
	}

	public function tambahData()
	{
		$data['title'] 		= "Tambah";
		$data['subtitle'] 	= "Rapidtest";

		$data['datadokter'] = $this->db->query("SELECT id_dokter, nama_dokter FROM mr_dokter")->result();
		$data['dataunit'] 	= $this->db->query("SELECT id_unit, nama_unit FROM mr_unit")->result();

		$data['record']=  $this->mSimetris->getData('mr_pasien');

		$this->load->view('templates/header',$data);
		$this->load->view('covid/vMenu',$data);
		$this->load->view('covid/vTambahDataRapidtest',$data);
		$this->load->view('templates/footer',$data);
	}

	public function cari(){
		$id_catatan_medik=$_GET['id_catatan_medik'];
		$cari =$this->mSimetris->cari($id_catatan_medik)->result();
		echo json_encode($cari);
	} 

	public function tambahDataAksi()
	{
		$this->_rules();

		if($this->form_validation->run() == FALSE)
		{
			$this->tambahData();
		}else{
			$id_catatan_medik 	= $this->input->post('id_catatan_medik');
			$id_dokter 			= $this->input->post('id_dokter');
			$id_unit 			= $this->input->post('id_unit');
			$tanggal 			= getDatenow();
			$jam 				= getTimenow();
			$sampel 			= 'Darah';
			$pemeriksaan 		= 'SARS CoV-2 Antibody';
			$igm 				= '3';
			$nilai_rujukan 		= '0';
			$metode 			= 'ICT';
			$pemeriksa 			= $this->session->userdata('nama_petugas');
			$tgl_periksa 		= $this->input->post('tgl_periksa');
			$jam_periksa 		= $this->input->post('jam_periksa');
			$igg 				= '3';

			$data = array(

				'id_catatan_medik' 	=> $id_catatan_medik,
				'id_dokter' 		=> $id_dokter,
				'id_unit' 			=> $id_unit,
				'tanggal' 			=> $tanggal,
				'jam' 				=> $jam,
				'sampel' 			=> $sampel,
				'pemeriksaan' 		=> $pemeriksaan,
				'igm' 				=> $igm,
				'nilai_rujukan' 	=> $nilai_rujukan,
				'metode' 			=> $metode,
				'pemeriksa' 		=> $pemeriksa,
				'tgl_periksa' 		=> $tgl_periksa,
				'jam_periksa' 		=> $jam_periksa,
				'igg' 				=> $igg,
			);

			$this->mSimetris->insertData('rapidtest',$data);
			$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="4"><b>Data berhasil ditambahkan!</b></font>
				</div>');
			redirect('covid/dataRapidtest/tambahData');

		}
	}

	public function updateData($id)
	{
		$data['title'] 		= "Update";
		$data['subtitle'] 	= "Rapidtest";

		$data['datadokter'] = $this->db->query("SELECT id_dokter, nama_dokter FROM mr_dokter")->result();
		$data['dataunit'] 	= $this->db->query("SELECT id_unit, nama_unit FROM mr_unit")->result();

		$where = array('id_rapidtest' => $id);
		$data['rapidtest'] = $this->db->query("
			SELECT *, mr_pasien.nama, mr_dokter.nama_dokter, mr_unit.nama_unit,
			CASE
			WHEN rapidtest.igm='0' THEN 'Non Reaktif'
			WHEN rapidtest.igm='1' THEN 'Reaktif'
			WHEN rapidtest.igm='3' THEN 'On Process'
			END AS nama_igm,
			CASE
			WHEN rapidtest.igg='0' THEN 'Non Reaktif'
			WHEN rapidtest.igg='1' THEN 'Reaktif'
			WHEN rapidtest.igg='3' THEN 'On Process'
			END AS nama_igg
			FROM rapidtest, mr_pasien, mr_dokter, mr_unit
			WHERE rapidtest.id_catatan_medik=mr_pasien.id_catatan_medik
			AND rapidtest.id_dokter=mr_dokter.id_dokter
			AND rapidtest.id_unit=mr_unit.id_unit
			AND id_rapidtest = '$id'")->result();
		$this->load->view('templates/header',$data);
		$this->load->view('covid/vMenu',$data);
		$this->load->view('covid/vUpdateDataRapidtest',$data);
		$this->load->view('templates/footer',$data);
	}

	public function updateDataAksi()
	{
		$this->_rules();

		if($this->form_validation->run() == FALSE)
		{
			$this->updateData();
		}else{
			$id 				= $this->input->post('id_rapidtest');
			$id_catatan_medik 	= $this->input->post('id_catatan_medik');
			$id_dokter 			= $this->input->post('id_dokter');
			$id_unit 			= $this->input->post('id_unit');
			$igm 				= $this->input->post('igm');
			$pemeriksa 			= $this->input->post('pemeriksa');
			$tgl_periksa 		= $this->input->post('tgl_periksa');
			$jam_periksa 		= $this->input->post('jam_periksa');
			$igg 				= $this->input->post('igg');

			$data = array(

				'id_catatan_medik' 	=> $id_catatan_medik,
				'id_dokter' 		=> $id_dokter,
				'id_unit' 			=> $id_unit,
				'igm' 				=> $igm,
				'pemeriksa' 		=> $pemeriksa,
				'tgl_periksa' 		=> $tgl_periksa,
				'jam_periksa' 		=> $jam_periksa,
				'igg' 				=> $igg,
			);

			$where = array(
				'id_rapidtest' 		=> $id
			);

			$this->mSimetris->updateData('rapidtest',$data,$where);
			$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<font size="4"><b>Data berhasil diupdate!</b></font>
				</div>');
			redirect('covid/dataRapidtest/');

		}
	}

	public function printData($id)
	{
		$data['title'] 		= "Print";
		$data['subtitle'] 	= "Rapidtest";

		$where = array('id_rapidtest' => $id);
		$data['rapidtest'] = $this->db->query("
			SELECT *, rapidtest.tanggal, mr_pasien.nama AS nama_pasien, mr_pasien.alamat AS alamat_pasien, mr_pasien.tgl_lahir AS tgl_lahir, mr_dokter.nama_dokter, mr_unit.nama_unit,
			IF(mr_pasien.sex='1', 'Laki-laki', 'Perempuan') AS nama_sex,
			CASE
			WHEN rapidtest.igm='0' THEN 'Non Reaktif'
			WHEN rapidtest.igm='1' THEN 'Reaktif'
			WHEN rapidtest.igm='3' THEN 'On Process'
			END AS hasil_igm,
			CASE
			WHEN rapidtest.igg='0' THEN 'Non Reaktif'
			WHEN rapidtest.igg='1' THEN 'Reaktif'
			WHEN rapidtest.igg='3' THEN 'On Process'
			END AS hasil_igg,
			IF(rapidtest.nilai_rujukan='1', 'Reaktif', 'Non Reaktif') AS nama_nilai_rujukan
			FROM rapidtest, mr_pasien, mr_dokter, mr_unit
			WHERE rapidtest.id_dokter=mr_dokter.id_dokter
			AND rapidtest.id_catatan_medik=mr_pasien.id_catatan_medik
			AND rapidtest.id_unit=mr_unit.id_unit
			AND rapidtest.id_rapidtest='$id'
			")->result();

		$this->load->view('templates/header',$data);
		$this->load->view('covid/vMenu',$data);
		$this->load->view('covid/vPrintDataRapidtest',$data);
		$this->load->view('templates/footer',$data);
	}

	public function deleteData($id)
	{

		$where = array('id_rapidtest' => $id);
		$this->mSimetris->deleteData('rapidtest',$where);
		$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="4"><b>Data berhasil dihapus!</b></font>
			</div>');
		redirect('covid/dataRapidtest');

	}

	public function lapCariData()
	{
		$data['title'] 		= "Pencarian";
		$data['subtitle'] 	= "Rapidtest";

		$this->load->view('templates/header',$data);
		$this->load->view('covid/vMenu',$data);
		$this->load->view('covid/vLapDataRapidtest',$data);
		$this->load->view('templates/footer',$data);
	}

	public function lapCariDataAksi()
	{
		$data['title'] 		= "Pencarian";
		$data['subtitle'] 	= "Rapidtest";

		$awal 	= $this->input->post('awal');
		$akhir 	= $this->input->post('akhir');
		$data['awal'] 	= $awal;
		$data['akhir'] 	= $akhir;

		$data['rapidtest'] = $this->db->query("
			SELECT *, rapidtest.tanggal, mr_pasien.nama AS nama_pasien, mr_pasien.alamat AS alamat_pasien, mr_pasien.tgl_lahir AS tgl_lahir, mr_dokter.nama_dokter, mr_unit.nama_unit,
			IF(mr_pasien.sex='1', 'Laki-laki', 'Perempuan') AS nama_sex,
			CASE
			WHEN rapidtest.igm='0' THEN 'Non Reaktif'
			WHEN rapidtest.igm='1' THEN 'Reaktif'
			WHEN rapidtest.igm='3' THEN 'On Process'
			END AS hasil_igm,
			CASE
			WHEN rapidtest.igg='0' THEN 'Non Reaktif'
			WHEN rapidtest.igg='1' THEN 'Reaktif'
			WHEN rapidtest.igg='3' THEN 'On Process'
			END AS hasil_igg,
			IF(rapidtest.nilai_rujukan='1', 'Reaktif', 'Non Reaktif') AS nama_nilai_rujukan
			FROM rapidtest, mr_pasien, mr_dokter, mr_unit
			WHERE rapidtest.id_dokter=mr_dokter.id_dokter
			AND rapidtest.id_catatan_medik=mr_pasien.id_catatan_medik
			AND rapidtest.id_unit=mr_unit.id_unit
			AND rapidtest.tanggal BETWEEN '$awal' AND '$akhir'")->result();

		$this->session->set_flashdata('alert','<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<font size="4"><b>Data berhasil ditampilkan!</b></font>
			</div>');

		$this->load->view('templates/header',$data);
		$this->load->view('covid/vMenu',$data);
		$this->load->view('covid/vLapDataRapidtest',$data);
		$this->load->view('templates/footer',$data);
	}

	public function excelData()
	{
		$data['title'] 		= "Pencarian";
		$data['subtitle'] 	= "Rapidtest";

		$awal 	= $this->input->post('awal');
		$akhir 	= $this->input->post('akhir');
		$data['awal'] 	= $awal;
		$data['akhir'] 	= $akhir;

		$data['rapidtest'] = $this->db->query("
			SELECT *, rapidtest.tanggal, mr_pasien.nama AS nama_pasien, mr_pasien.alamat AS alamat_pasien, mr_pasien.tgl_lahir AS tgl_lahir, mr_dokter.nama_dokter, mr_unit.nama_unit,
			IF(mr_pasien.sex='1', 'Laki-laki', 'Perempuan') AS nama_sex,
			CASE
			WHEN rapidtest.igm='0' THEN 'Non Reaktif'
			WHEN rapidtest.igm='1' THEN 'Reaktif'
			WHEN rapidtest.igm='3' THEN 'On Process'
			END AS hasil_igm,
			CASE
			WHEN rapidtest.igg='0' THEN 'Non Reaktif'
			WHEN rapidtest.igg='1' THEN 'Reaktif'
			WHEN rapidtest.igg='3' THEN 'On Process'
			END AS hasil_igg,
			IF(rapidtest.nilai_rujukan='1', 'Reaktif', 'Non Reaktif') AS nama_nilai_rujukan
			FROM rapidtest, mr_pasien, mr_dokter, mr_unit
			WHERE rapidtest.id_dokter=mr_dokter.id_dokter
			AND rapidtest.id_catatan_medik=mr_pasien.id_catatan_medik
			AND rapidtest.id_unit=mr_unit.id_unit
			AND rapidtest.tanggal BETWEEN '$awal' AND '$akhir'")->result();

		$this->load->view('templates/header',$data);
		$this->load->view('covid/vMenu',$data);
		$this->load->view('covid/vExcelDataRapidtest',$data);
		$this->load->view('templates/footer',$data);
	}

	public function _rules()
	{
		$this->form_validation->set_rules('id_catatan_medik','nomor rekam medik','required');
		$this->form_validation->set_rules('id_dokter','nama dokter','required');
		$this->form_validation->set_rules('id_unit','nama unit','required');
		$this->form_validation->set_rules('tgl_periksa','tanggal periksa','required');
		$this->form_validation->set_rules('jam_periksa','jam periksa','required');
	}

}

?>
