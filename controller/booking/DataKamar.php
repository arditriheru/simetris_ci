<?php 

class dataKamar extends CI_Controller
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
		$data['title'] 		= "Kamar";
		$data['subtitle'] 	= "Tersedia";
		
		$data['datakamar'] 	= $this->db->query("
			SELECT mr_tt.kelas, mr_unit.nama_unit, mr_tt.no_bed, mr_tt.ket_antri,
			IF(mr_tt.no_bed='1', 'A', 'B') AS bed
			FROM mr_tt, mr_unit
			WHERE mr_tt.id_unit = mr_unit.id_unit
			AND mr_tt.id_unit IN(6,29,24,26,7,28,27,31,30,25)
			ORDER BY mr_unit.nama_unit ASC")->result();


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
		$this->load->view('booking/vDataKamar',$data);
		$this->load->view('templates/footer',$data);
	}

} 

?>