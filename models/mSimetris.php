<?php 

class mSimetris extends CI_Model
{

/*
|--------------------------------------------------------------------------
|
| Model public
|
|--------------------------------------------------------------------------
*/

public function getData($table)
{
	return $this->db->get($table);
}

function cari($id)
{
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

	$query = $this->db->where('nama_user',$username)
	-> where('password',md5($password))
	-> limit('1')
	-> get('psdi_petugas');

	if($query->num_rows()>0)
	{
		return $query->row();
	}else{
		return FALSE;
	}
}

public function cekLogin2($username,$password,$id_unit)
{
	$query = $this->db->where('username',$username)
	-> where('password',md5($password))
	-> where('id_unit',$id_unit)
	-> limit('1')
	-> get('admin');

	if($query->num_rows()>0)
	{
		return $query->row();
	}else{
		return FALSE;
	}
}

public function hakAkses($username,$password,$id)
{
	$query = $this->db->where('psdi_petugas.nama_user',$username)
	-> where('psdi_petugas.password',md5($password))
	-> where('psdi_riw_aplikasi.id_aplikasi',$id)
	-> limit('1')
	-> join('psdi_riw_aplikasi','psdi_petugas.id_petugas=psdi_riw_aplikasi.id_petugas')
	-> get('psdi_petugas');

	if($query->num_rows()>0)
	{
		return $query->row();
	}else{
		return FALSE;
	}
}

function countData($table,$where)
{
	$this->db->where($where);
	$query = $this->db->get($table);
	return $query->num_rows();
}

function selectData($table,$column,$where)
{
	$this->db->select($column);
	$this->db->where($where);
	$query = $this->db->get($table);
	return $query;
}

function cariNamaPasienData($table,$keyword)
{
	$this->db->like($keyword);
	$this->db->order_by('nama', 'ASC');
	$query = $this->db->get($table);
	return $query;
}

function getMax($table,$column)
{
	$this->db->select_max($column);
	$query = $this->db->get($table);
	return $query; 
}

// function dataDokterAll()
// {
// 	$query = $this->db->query("
// 		SELECT id_dokter, nama_dokter FROM mr_dokter");
// 	return $query;
// }

// function dataDokterUnit($id)
// {
// 	$query = $this->db->query("
// 		SELECT id_dokter, nama_dokter FROM dokter WHERE id_unit='$id' AND status=1");
// 	return $query;
// }

function dataDokter($table,$where,$orderby)
{
	$this->db->select('id_dokter, nama_dokter');
	$this->db->from($table);
	$this->db->where($where);
	$this->db->order_by($orderby);
	return $this->db->get();
}

function dataPetugas($table,$where,$orderby)
{
	$this->db->select('id_petugas, nama_petugas');
	$this->db->from($table);
	$this->db->where($where);
	$this->db->order_by($orderby);
	return $this->db->get();
}

// function dataUnit()
// {
// 	$query = $this->db->query("
// 		SELECT id_unit, nama_unit FROM mr_unit");
// 	return $query;
// }

/*
|--------------------------------------------------------------------------
|
| Model booking
|
|--------------------------------------------------------------------------
*/

function dataAntrian($id_dokter,$id_sesi,$booking_tanggal)
{
	$query = $this->db->query("
		SELECT *, @no:=@no+1 AS noant, dokter.nama_dokter, dokter.id_unit, sesi.nama_sesi,
		IF (booking.status='1', 'Datang', 'Belum Datang') AS status
		FROM booking, dokter, sesi
		JOIN (SELECT @no:=0) r
		WHERE booking.id_dokter=dokter.id_dokter
		AND booking.id_sesi=sesi.id_sesi
		AND booking.booking_tanggal = '$booking_tanggal'
		AND booking.id_sesi = '$id_sesi'
		AND booking.id_dokter='$id_dokter' ORDER BY booking.id_booking ASC");
	return $query;
}

function dataPanggil($id_booking,$id_dokter,$id_sesi,$booking_tanggal)
{
	$query = $this->db->query("
		SELECT id_booking, nama, FIND_IN_SET( id_booking, (    
		SELECT GROUP_CONCAT( id_booking
		ORDER BY id_booking ASC ) 
		FROM booking 
		WHERE booking_tanggal = '$booking_tanggal'
		AND id_dokter = '$id_dokter'
		AND id_sesi = '$id_sesi')
		) AS noant
		FROM booking
		WHERE id_booking = '$id_booking'");
	return $query;
}

function dataDokterPoli($where)
{
	$this->db->select('*');
	$this->db->from('booking');
	$this->db->join('dokter','booking.id_dokter=dokter.id_dokter');
	$this->db->where($where);
	$this->db->group_by('booking.id_dokter');
	return $this->db->get();
}

function dataDokterTumbang($where)
{
	$this->db->select('*');
	$this->db->from('tumbang');
	$this->db->join('mr_petugas','tumbang.id_petugas=mr_petugas.id_petugas');
	$this->db->where($where);
	$this->db->group_by('tumbang.id_petugas');
	return $this->db->get();
}

function dataDokterAnc($where)
{
	$this->db->select('*');
	$this->db->from('anc');
	$this->db->join('mr_petugas','anc.id_petugas=mr_petugas.id_petugas');
	$this->db->where($where);
	$this->db->group_by('anc.id_petugas');
	return $this->db->get();
}

function dataPoli($where,$orderby)
{
	$this->db->select("booking.*, dokter.nama_dokter, sesi.nama_sesi, mr_alergi.id_catatan_medik AS id_catatan_medik_alergi, mr_alergi.nama_obat, mr_alergi.keterangan AS alergi_keterangan, IF (booking.status='1', 'Datang', 'Belum Datang') AS status");
	$this->db->from('booking');
	$this->db->join('dokter','booking.id_dokter=dokter.id_dokter');
	$this->db->join('sesi','booking.id_sesi=sesi.id_sesi');
	$this->db->join('mr_alergi','booking.id_catatan_medik = mr_alergi.id_catatan_medik','left');
	$this->db->where($where);
	$this->db->order_by($orderby);
	return $this->db->get();
}

function dataTumbang($where)
{
	$this->db->select("tumbang.*, mr_petugas.nama_petugas, sesi.nama_sesi, mr_alergi.id_catatan_medik AS id_catatan_medik_alergi, IF (tumbang.status='1', 'Datang', 'Belum Datang') AS status");
	$this->db->from('tumbang');
	$this->db->join('mr_petugas','tumbang.id_petugas=mr_petugas.id_petugas');
	$this->db->join('sesi','tumbang.id_sesi=sesi.id_sesi');
	$this->db->join('mr_alergi','tumbang.id_catatan_medik = mr_alergi.id_catatan_medik','left');
	$this->db->where($where);
	$this->db->order_by('tumbang.id_tumbang ASC');
	return $this->db->get();
}

function dataAnc($where)
{
	$this->db->select("anc.*, mr_petugas.nama_petugas, sesi.nama_sesi, mr_alergi.id_catatan_medik AS id_catatan_medik_alergi, IF (anc.status='1', 'Datang', 'Belum Datang') AS status");
	$this->db->from('anc');
	$this->db->join('mr_petugas','anc.id_petugas=mr_petugas.id_petugas');
	$this->db->join('sesi','anc.id_sesi=sesi.id_sesi');
	$this->db->join('mr_alergi','anc.id_catatan_medik = mr_alergi.id_catatan_medik','left');
	$this->db->where($where);
	$this->db->order_by('anc.id_anc ASC');
	return $this->db->get();
}

// function detailDataPoli($id_booking)
// {
// 	$query = $this->db->query("
// 		SELECT booking.id_booking, booking.id_catatan_medik, booking.nama, booking.alamat, booking.booking_tanggal, booking.tanggal, booking.jam, booking.keterangan, booking.kontak, booking.status, dokter.nama_dokter, sesi.nama_sesi, mr_alergi.id_catatan_medik AS id_catatan_medik_alergi, mr_alergi.nama_obat, mr_alergi.keterangan AS alergi_keterangan,
// 		IF (booking.status='1', 'Datang', 'Belum Datang') AS nama_status
// 		FROM booking
// 		INNER JOIN dokter
// 		ON booking.id_dokter=dokter.id_dokter
// 		INNER JOIN sesi
// 		ON booking.id_sesi=sesi.id_sesi
// 		LEFT JOIN mr_alergi
// 		ON booking.id_catatan_medik = mr_alergi.id_catatan_medik
// 		WHERE booking.id_booking='$id_booking'
// 		ORDER BY booking.id_sesi, dokter.id_dokter, booking.nama ASC");
// 	return $query;
// }

function detailDataTumbang($table,$where)
{
	$this->db->select("tumbang.*,mr_petugas.nama_petugas, sesi.nama_sesi,IF (tumbang.status='1', 'Datang', 'Belum Datang') AS status");
	$this->db->from($table);
	$this->db->join("mr_petugas","tumbang.id_petugas = mr_petugas.id_petugas");
	$this->db->join("sesi","tumbang.id_sesi = sesi.id_sesi");
	$this->db->where($where);
	return $this->db->get();
}

function detailDataAnc($table,$where)
{
	$this->db->select("anc.*,mr_petugas.nama_petugas, sesi.nama_sesi,IF (anc.status='1', 'Datang', 'Belum Datang') AS status");
	$this->db->from($table);
	$this->db->join("mr_petugas","anc.id_petugas = mr_petugas.id_petugas");
	$this->db->join("sesi","anc.id_sesi = sesi.id_sesi");
	$this->db->where($where);
	return $this->db->get();
}

function tabDataPoli($where)
{
	$this->db->select("booking.*,dokter.nama_dokter, sesi.nama_sesi,IF (booking.status='1', 'Datang', 'Belum Datang') AS status");
	$this->db->from("booking");
	$this->db->join("dokter","booking.id_dokter = dokter.id_dokter");
	$this->db->join("sesi","booking.id_sesi = sesi.id_sesi");
	$this->db->where($where);
	$this->db->order_by("booking.id_sesi, booking.id_booking","ASC");
	return $this->db->get();
}

function dataRegisterPoli($where)
{
	$this->db->select('booking.*,dokter.nama_dokter, sesi.nama_sesi');
	$this->db->from('booking');
	$this->db->join('dokter','booking.id_dokter=dokter.id_dokter');
	$this->db->join('sesi','booking.id_sesi=sesi.id_sesi');
	$this->db->where($where);
	$this->db->order_by('booking.id_booking DESC');
	return $this->db->get();
}

function dataRegisterTumbang($where)
{
	$this->db->select('tumbang.*,mr_petugas.nama_petugas, sesi.nama_sesi');
	$this->db->from('tumbang');
	$this->db->join('mr_petugas','tumbang.id_petugas=mr_petugas.id_petugas');
	$this->db->join('sesi','tumbang.id_sesi=sesi.id_sesi');
	$this->db->where($where);
	$this->db->order_by('tumbang.id_tumbang DESC');
	return $this->db->get();
}

function dataRegisterAnc($where)
{
	$this->db->select('anc.*,mr_petugas.nama_petugas, sesi.nama_sesi');
	$this->db->from('anc');
	$this->db->join('mr_petugas','anc.id_petugas=mr_petugas.id_petugas');
	$this->db->join('sesi','anc.id_sesi=sesi.id_sesi');
	$this->db->where($where);
	$this->db->order_by('anc.id_anc DESC');
	return $this->db->get();
}

function dataJadwal($where)
{
	$this->db->select("dokter_jadwal.*,dokter.nama_dokter, sesi.nama_sesi,
		IF (dokter_jadwal.ims='1', ' + Imunisasi','') AS plus_ims, IF (dokter_jadwal.ims='1', 'Ya','Tidak') AS nama_ims,
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
		END AS nama_hari");
	$this->db->from('dokter_jadwal');
	$this->db->join('dokter','dokter_jadwal.id_dokter=dokter.id_dokter');
	$this->db->join('sesi','dokter_jadwal.id_sesi=sesi.id_sesi');
	$this->db->where($where);
	$this->db->order_by('dokter_jadwal.hari ASC');
	return $this->db->get();

	// $query = $this->db->query("
	// 	SELECT *, dokter.nama_dokter, sesi.nama_sesi,
	// 	IF (dokter_jadwal.ims='1', ' + Imunisasi','') AS ims,
	// 	CASE
	// 	WHEN dokter.id_unit='1' THEN 'Anak'
	// 	WHEN dokter.id_unit='2' THEN 'Obsgyn'
	// 	WHEN dokter.id_unit='3' THEN 'Bedah'
	// 	END AS nama_unit,
	// 	CASE
	// 	WHEN dokter_jadwal.hari=1 THEN 'Senin'
	// 	WHEN dokter_jadwal.hari=2 THEN 'Selasa'
	// 	WHEN dokter_jadwal.hari=3 THEN 'Rabu'
	// 	WHEN dokter_jadwal.hari=4 THEN 'Kamis'
	// 	WHEN dokter_jadwal.hari=5 THEN 'Jumat'
	// 	WHEN dokter_jadwal.hari=6 THEN 'Sabtu'
	// 	WHEN dokter_jadwal.hari=0 THEN 'Minggu'
	// 	END AS nama_hari
	// 	FROM dokter_jadwal
	// 	JOIN dokter
	// 	ON dokter_jadwal.id_dokter=dokter.id_dokter
	// 	join sesi
	// 	ON dokter_jadwal.id_sesi=sesi.id_sesi
	// 	ORDER BY dokter_jadwal.hari ASC");
	// return $query;
}

function dataJadwalLibur()
{
	$query = $this->db->query("
		SELECT *, dokter.nama_dokter, sesi.nama_sesi
		FROM dokter_jadwal_libur
		JOIN dokter
		ON dokter_jadwal_libur.id_dokter=dokter.id_dokter
		JOIN sesi
		ON dokter_jadwal_libur.id_sesi=sesi.id_sesi
		ORDER BY dokter_jadwal_libur.tanggal ASC");
	return $query;
}

function dataKamar($where)
{
	$this->db->select("mr_tt.*, mr_unit.nama_unit, IF(mr_tt.no_bed='1', 'A', 'B') AS bed");
	$this->db->from('mr_tt');
	$this->db->join('mr_unit','mr_tt.id_unit = mr_unit.id_unit');
	$this->db->where_in('mr_unit.id_unit',$where);
	$this->db->order_by('mr_tt.kelas ASC');
	return $this->db->get();
}

// function cariDataPoli($where)
// {
// 	$this->db->select("booking.*, dokter.nama_dokter, sesi.nama_sesi,
// 		IF (booking.status='1', 'Datang', 'Belum Datang') AS status");
// 	$this->db->from('booking');
// 	$this->db->join('dokter','booking.id_dokter=dokter.id_dokter');
// 	$this->db->join('sesi','booking.id_sesi=sesi.id_sesi');
// 	$this->db->where($where);
// 	$this->db->order_by('booking.id_booking ASC');
// 	return $this->db->get();
// }

// function cariDataTumbang($where)
// {
// 	$this->db->select("tumbang.*, mr_petugas.nama_petugas, sesi.nama_sesi,
// 		IF (tumbang.status='1', 'Datang', 'Belum Datang') AS status");
// 	$this->db->from('tumbang');
// 	$this->db->join('mr_petugas','tumbang.id_petugas=mr_petugas.id_petugas');
// 	$this->db->join('sesi','tumbang.id_sesi=sesi.id_sesi');
// 	$this->db->where($where);
// 	$this->db->order_by('tumbang.id_tumbang ASC');
// 	return $this->db->get();
// }

// function cariDataAnc($where)
// {
// 	$this->db->select("anc.*, mr_petugas.nama_petugas, sesi.nama_sesi,
// 		IF (anc.status='1', 'Datang', 'Belum Datang') AS status");
// 	$this->db->from('anc');
// 	$this->db->join('mr_petugas','anc.id_petugas=mr_petugas.id_petugas');
// 	$this->db->join('sesi','anc.id_sesi=sesi.id_sesi');
// 	$this->db->where($where);
// 	$this->db->order_by('anc.id_anc ASC');
// 	return $this->db->get();
// }


/*
|--------------------------------------------------------------------------
|
| Model rapidtest
|
|--------------------------------------------------------------------------
*/

function dataRapidtest()
{
	$query = $this->db->query("
		SELECT rapidtest.id_rapidtest, rapidtest.id_catatan_medik, rapidtest.tanggal, rapidtest.jam, rapidtest.igm, rapidtest.igg, mr_pasien.nama, mr_dokter.nama_dokter,
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
		ORDER BY rapidtest.id_rapidtest DESC LIMIT 50");
	return $query;
}

function detailDataRapidtest($where)
{
	$query = $this->db->query("
		SELECT *, rapidtest.tanggal, mr_pasien.nama AS nama_pasien, mr_pasien.alamat AS alamat_pasien, mr_pasien.tgl_lahir AS tgl_lahir, mr_dokter.nama_dokter, mr_unit.nama_unit,
		IF(mr_pasien.sex='1', 'Laki-laki', 'Perempuan') AS nama_sex,
		CASE
		WHEN rapidtest.igm='0' THEN 'Non Reaktif'
		WHEN rapidtest.igm='1' THEN 'Reaktif'
		WHEN rapidtest.igm='3' THEN 'On Process'
		END AS nama_igm,
		CASE
		WHEN rapidtest.igg='0' THEN 'Non Reaktif'
		WHEN rapidtest.igg='1' THEN 'Reaktif'
		WHEN rapidtest.igg='3' THEN 'On Process'
		END AS nama_igg,
		IF(rapidtest.nilai_rujukan='1', 'Reaktif', 'Non Reaktif') AS nama_nilai_rujukan
		FROM rapidtest, mr_pasien, mr_dokter, mr_unit
		WHERE rapidtest.id_dokter=mr_dokter.id_dokter
		AND rapidtest.id_catatan_medik=mr_pasien.id_catatan_medik
		AND rapidtest.id_unit=mr_unit.id_unit
		AND rapidtest.id_rapidtest='$where'");
	return $query;
}

function ExcelDataRapidtest($awal,$akhir)
{
	$query = $this->db->query("
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
		AND rapidtest.tanggal BETWEEN '$awal' AND '$akhir'");
	return $query;
}

/*
|--------------------------------------------------------------------------
|
| Model inventaris
|
|--------------------------------------------------------------------------
*/

function dataInventaris()
{
	$query = $this->db->query("
		SELECT *, inventaris_ruangan.nama_ruangan, inventaris_jenis.nama_jenis
		FROM inventaris
		INNER JOIN inventaris_ruangan
		ON inventaris.kode_ruangan = inventaris_ruangan.kode_ruangan
		INNER JOIN inventaris_jenis
		ON inventaris.kode_jenis = inventaris_jenis.kode_jenis
		ORDER BY inventaris.kode_registrasi DESC
		LIMIT 50");
	return $query;
}

function cariDataNomor($where)
{
	$query = $this->db->query("
		SELECT *, inventaris_ruangan.nama_ruangan, inventaris_jenis.nama_jenis,
		IF (inventaris.kondisi='1', 'Baik', 'Rusak') AS nama_kondisi,
		IF (inventaris.status='1', 'Baru', 'Bekas') AS nama_status
		FROM inventaris
		INNER JOIN inventaris_ruangan
		ON inventaris.kode_ruangan = inventaris_ruangan.kode_ruangan
		INNER JOIN inventaris_jenis
		ON inventaris.kode_jenis = inventaris_jenis.kode_jenis
		WHERE inventaris.nomor_inventaris = '$where'
		ORDER BY inventaris.kode_registrasi ASC");
	return $query;
}

function cariDataKondisi($where)
{
	$query = $this->db->query("
		SELECT *, inventaris_ruangan.nama_ruangan, inventaris_jenis.nama_jenis,
		IF (inventaris.kondisi='1', 'Baik', 'Rusak') AS nama_kondisi,
		IF (inventaris.status='1', 'Baru', 'Bekas') AS nama_status
		FROM inventaris
		INNER JOIN inventaris_ruangan
		ON inventaris.kode_ruangan = inventaris_ruangan.kode_ruangan
		INNER JOIN inventaris_jenis
		ON inventaris.kode_jenis = inventaris_jenis.kode_jenis
		WHERE inventaris.kondisi = '$where'
		ORDER BY inventaris.kode_registrasi ASC");
	return $query;
}

function cariDataStatus($where)
{
	$query = $this->db->query("
		SELECT *, inventaris_ruangan.nama_ruangan, inventaris_jenis.nama_jenis,
		IF (inventaris.kondisi='1', 'Baik', 'Rusak') AS nama_kondisi,
		IF (inventaris.status='1', 'Baru', 'Bekas') AS nama_status
		FROM inventaris
		INNER JOIN inventaris_ruangan
		ON inventaris.kode_ruangan = inventaris_ruangan.kode_ruangan
		INNER JOIN inventaris_jenis
		ON inventaris.kode_jenis = inventaris_jenis.kode_jenis
		WHERE inventaris.status = '$where'
		ORDER BY inventaris.kode_registrasi ASC");
	return $query;
}

function cariDataRuanganAll()
{
	$query = $this->db->query("
		SELECT *, inventaris_ruangan.nama_ruangan, inventaris_jenis.nama_jenis,
		IF (inventaris.kondisi='1', 'Baik', 'Rusak') AS nama_kondisi,
		IF (inventaris.status='1', 'Baru', 'Bekas') AS nama_status
		FROM inventaris
		INNER JOIN inventaris_ruangan
		ON inventaris.kode_ruangan = inventaris_ruangan.kode_ruangan
		INNER JOIN inventaris_jenis
		ON inventaris.kode_jenis = inventaris_jenis.kode_jenis
		ORDER BY inventaris.kode_registrasi ASC");
	return $query;
}

function cariDataRuangan($where)
{
	$query = $this->db->query("
		SELECT *, inventaris_ruangan.nama_ruangan, inventaris_jenis.nama_jenis,
		IF (inventaris.kondisi='1', 'Baik', 'Rusak') AS nama_kondisi,
		IF (inventaris.status='1', 'Baru', 'Bekas') AS nama_status
		FROM inventaris
		INNER JOIN inventaris_ruangan
		ON inventaris.kode_ruangan = inventaris_ruangan.kode_ruangan
		INNER JOIN inventaris_jenis
		ON inventaris.kode_jenis = inventaris_jenis.kode_jenis
		WHERE inventaris.kode_ruangan = '$where'
		ORDER BY inventaris.kode_registrasi ASC");
	return $query;
}


function dataJenisInventaris()
{
	$query = $this->db->query("
		SELECT * FROM inventaris_jenis ORDER BY nama_jenis ASC");
	return $query;
}

function dataRuanganInventaris()
{
	$query = $this->db->query("
		SELECT * FROM inventaris_ruangan ORDER BY nama_ruangan ASC");
	return $query;
}

function detailDataInventaris($where)
{
	$query = $this->db->query("
		SELECT *, inventaris_jenis.nama_jenis, inventaris_ruangan.nama_ruangan,
		IF (inventaris.kondisi='1', 'Baik', 'Rusak') AS nama_kondisi,
		IF (inventaris.status='1', 'Baru', 'Bekas') AS nama_status
		FROM inventaris, inventaris_jenis, inventaris_ruangan
		WHERE inventaris.kode_jenis=inventaris_jenis.kode_jenis
		AND inventaris.kode_ruangan=inventaris_ruangan.kode_ruangan
		AND inventaris.kode_registrasi = '$where'");
	return $query;
}

/*
|--------------------------------------------------------------------------
|
| Model register
|
|--------------------------------------------------------------------------
*/

function dataDokterTab($bln,$thn)
{
	$query = $this->db->query("
		SELECT hpl_register.id_dokter, dokter.nama_dokter FROM hpl_register, dokter WHERE hpl_register.id_dokter=dokter.id_dokter AND MONTH(hpl_register.tgl_hpl)='$getMonthNow' AND YEAR(hpl_register.tgl_hpl)='$getYearNow' AND dokter.status=1 GROUP BY hpl_register.id_dokter");
	return $query;
}

function dataBulanTab($thn)
{
	$query = $this->db->query("
		SELECT MONTH(tgl_hpl) AS id_bulan,
		CASE
		WHEN MONTH(tgl_hpl)='1' THEN 'Januari'
		WHEN MONTH(tgl_hpl)='2' THEN 'Februari'
		WHEN MONTH(tgl_hpl)='3' THEN 'Maret'
		WHEN MONTH(tgl_hpl)='4' THEN 'April'
		WHEN MONTH(tgl_hpl)='5' THEN 'Mei'
		WHEN MONTH(tgl_hpl)='6' THEN 'Juni'
		WHEN MONTH(tgl_hpl)='7' THEN 'Juli'
		WHEN MONTH(tgl_hpl)='8' THEN 'Agustus'
		WHEN MONTH(tgl_hpl)='9' THEN 'September'
		WHEN MONTH(tgl_hpl)='10' THEN 'Oktober'
		WHEN MONTH(tgl_hpl)='11' THEN 'November'
		WHEN MONTH(tgl_hpl)='12' THEN 'Desember'
		END AS nama_bulan
		FROM hpl_register
		WHERE YEAR(tgl_hpl)='$thn'
		GROUP BY id_bulan");
	return $query;
}

function dataHplTab($bln,$thn)
{
	$query = $this->db->query("
		SELECT hpl_register.id_hpl_register, hpl_register.id_catatan_medik, hpl_register.tgl_hpl, mr_pasien.nama, mr_pasien.telp, dokter.nama_dokter, psdi_petugas.nama AS nama_petugas
		FROM hpl_register, mr_pasien, dokter, psdi_petugas
		WHERE hpl_register.id_catatan_medik=mr_pasien.id_catatan_medik
		AND hpl_register.id_dokter=dokter.id_dokter
		AND hpl_register.id_petugas=psdi_petugas.id_petugas
		AND hpl_register.id_dokter='$id'
		AND MONTH(hpl_register.tgl_hpl)='$bln'
		AND YEAR(hpl_register.tgl_hpl)='$thn'
		ORDER BY hpl_register.tgl_hpl ASC");
	return $query;
}

function dataHplRegister($id)
{
	$query = $this->db->query("
		SELECT *, mr_pasien.nama, mr_pasien.telp, mr_pasien.alamat, dokter.nama_dokter
		FROM hpl_register
		INNER JOIN mr_pasien ON hpl_register.id_catatan_medik=mr_pasien.id_catatan_medik
		INNER JOIN dokter ON hpl_register.id_dokter=dokter.id_dokter
		WHERE hpl_register.tanggal='$id'
		ORDER BY hpl_register.id_hpl_register DESC");
	return $query;
}

function dataHpl($bln,$thn)
{
	$query = $this->db->query("
		SELECT *, mr_pasien.nama, mr_pasien.telp, mr_pasien.alamat, dokter.nama_dokter
		FROM hpl_register
		INNER JOIN mr_pasien ON hpl_register.id_catatan_medik=mr_pasien.id_catatan_medik
		INNER JOIN dokter ON hpl_register.id_dokter=dokter.id_dokter
		WHERE MONTH(hpl_register.tgl_hpl)='$bln'
		AND YEAR(hpl_register.tgl_hpl)='$thn'
		ORDER BY hpl_register.tgl_hpl ASC");
	return $query;
}

function dataVaksinRm($id)
{
	$query = $this->db->query("
		SELECT *, mr_pasien.nama AS nama_pasien, far_stok.nama AS nama_vaksin, dokter.nama_dokter
		FROM vaksin_register
		JOIN mr_pasien
		ON vaksin_register.id_catatan_medik = mr_pasien.id_catatan_medik
		JOIN far_stok
		ON vaksin_register.kode_vaksin = far_stok.no_urut
		JOIN dokter
		ON vaksin_register.id_dokter = dokter.id_dokter
		WHERE vaksin_register.id_catatan_medik = '$id'
		ORDER BY id_vaksin_register DESC");
	return $query;
}

function dataVaksinPeriode($bln,$thn)
{
	$query = $this->db->query("
		SELECT *, mr_pasien.nama AS nama_pasien, far_stok.nama AS nama_vaksin, dokter.nama_dokter
		FROM vaksin_register
		JOIN mr_pasien
		ON vaksin_register.id_catatan_medik = mr_pasien.id_catatan_medik
		JOIN far_stok
		ON vaksin_register.kode_vaksin = far_stok.no_urut
		JOIN dokter
		ON vaksin_register.id_dokter = dokter.id_dokter
		WHERE MONTH(vaksin_register.tanggal)= '$bln'
		AND  YEAR(vaksin_register.tanggal)= '$thn'
		ORDER BY id_vaksin_register ASC");
	return $query;
}

function dataNamaVaksin()
{
	$query = $this->db->query("
		SELECT no_urut, nama FROM far_stok WHERE kode_lain=13 ORDER BY nama ASC");
	return $query;
}

}

?>