<body>

  <div id="wrapper">

    <div id="page-wrapper">

      <div class="row">
        <div class="col-lg-12">

            <h1><?php echo $title ?> <small> <?php echo $subtitle ?></small></h1>

          <ol class="breadcrumb">
            <li><a href="<?php echo base_url('booking/dataBooking') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-calendar"></i> <?php echo $subtitle ?></li>
          </ol>

        <?php $this->load->view('templates/welcome') ?>
        <?php echo $this->session->flashdata('alert') ?>

        <?php if(isset($id) && $id==1 && !isset($anc)) { ?>

          <div class="col-lg-6">
            <form method="post" action="<?php echo base_url('booking/dataBooking/cariDataAncAksi') ?>" role="form">
              <div class="form-group">
                <label>Petugas</label>
                <select class="form-control" type="text" name="id_petugas" required="">
                  <option value="">Pilih</option>
                  <?php foreach($datapetugas as $d): ?>
                    <option value="<?php echo $d->id_petugas ?>"><?php echo $d->nama_petugas ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Jadwal</label>
                <input class="form-control" type="date" name="jadwal">
              </div>
              <div class="form-group">
                <label>Sesi</label>
                <select class="form-control" type="text" name="id_sesi" required="">
                  <option value="">Pilih</option>
                  <?php foreach($datasesi as $d): ?>
                    <option value="<?php echo $d->id_sesi ?>"><?php echo $d->nama_sesi ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <button type="submit" class="btn btn-success">Cari</button>
            </form>
          </div>

        <?php }elseif(isset($anc)) { ?>

          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped tablesorter">
              <thead>
                <tr>
                  <th class="text-center">#</i></th>
                  <th class="text-center">No.RM</i></th>
                  <th class="text-center">Nama</i></th>
                  <th class="text-center">Alamat</i></th>
                  <th class="text-center">Kontak</th>
                  <th class="text-center">Dokter</i></th>
                  <th class="text-center">Sesi</i></th>
                  <th class="text-center">Status</i></th>
                  <th class="text-center">Keterangan</i></th>
                  <th class="text-center">Action</i></th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach ($anc as $d) : ?>
                <tr class="active">
                  <td class="text-center"><?php echo $no++; ?></td>
                  <td class="text-center"><?php echo $d->id_catatan_medik;?></td>
                  <td class="text-center"><?php echo $d->nama;?></td>
                  <td class="text-center"><?php echo $d->alamat;?></td>
                  <td class="text-center"><?php echo $d->kontak;?></td>
                  <td class="text-center"><?php echo $d->nama_petugas;?></td>
                  <td class="text-center"><?php echo $d->nama_sesi;?></td>
                  <td class="text-center"><?php echo $d->status;?></td>
                  <td class="text-center"><?php echo $d->keterangan;?></td>
                  <td class="text-center">              
                    <a href="<?php echo base_url('booking/dataBooking/detailDataAnc/'.$d->id_anc) ?>"><button type="button" class="btn btn-warning"><i class='fa fa-folder-open'></i></button></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php }elseif(isset($id) && $id==2 && !isset($anc)) { ?>
        <div class="col-lg-6">
          <form method="get" action="<?php echo base_url('booking/dataBooking/tambahDataAncRm') ?>" role="form">
            <div class="form-group">
              <label>Nomor RM</label>
              <input class="form-control" type="text" name="rm" placeholder="Nomor Rekam Medik">
            </div><button type="submit" class="btn btn-success">Cari</button>
          </form>
        </div>
        <div class="col-lg-6">
          <form method="get" action="<?php echo base_url('booking/dataBooking/tambahDataAncCariNm') ?>" role="form">
            <div class="form-group">
              <label>Nama</label>
              <input class="form-control" type="text" name="nm" placeholder="Nama Pasien">
            </div><button type="submit" class="btn btn-success">Cari</button>
          </form>
        </div>
        <div class="col-lg-6"><br><br>
          <form method="post" action="<?php echo base_url('booking/dataBooking/tambahDataAncBaruAksi') ?>" role="form">
            <div class="form-group">
              <label>Nama Pasien ANC</label>
              <input class="form-control" type="hidden" name="id" value="<?php echo $id ?>">
              <input class="form-control" type="text" name="nama" placeholder="Masukkan.." required="">
            </div>
            <div class="form-group">
              <label>Alamat</label>
              <input class="form-control" type="text" name="alamat" placeholder="Masukkan.." required="">
            </div>
            <div class="form-group">
              <label>Kontak</label>
              <input class="form-control" type="text" name="kontak" placeholder="Masukkan.." required="">
            </div>
            <div class="form-group">
              <label>Petugas</label>
              <select class="form-control" type="text" name="id_petugas" required="">
                <option value="">Pilih</option>
                <?php foreach($datapetugas as $d): ?>
                  <option value="<?php echo $d->id_petugas ?>"><?php echo $d->nama_petugas ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Jadwal</label>
              <input class="form-control" type="date" name="jadwal" required="">
            </div>
            <div class="form-group">
              <label>Sesi</label><br>
              <small><b>Pagi :</b> 07.00 - 10.59 | <b>Siang :</b> 11.00 - 14.59 | <b>Sore :</b> 15.00 - 17.59 | <b>Malam :</b> 18.00 - selesai</small>
              <select class="form-control" type="text" name="id_sesi" required="">
                <option value="">Pilih</option>
                <?php foreach($datasesi as $d): ?>
                  <option value="<?php echo $d->id_sesi ?>"><?php echo $d->nama_sesi ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Keterangan</label>
              <input class="form-control" type="text" name="keterangan" placeholder="Masukkan..">
            </div>
            <button type="submit" name="submit" class="btn btn-success">Daftar</button>
          </form>
        </div>

      <?php } ?>
    </div>
  </div><!-- /.row -->

</div><!-- /#page-wrapper -->

<!--</div> /#wrapper -->
