<body>

  <div id="wrapper">

    <div id="page-wrapper">

      <div class="row">
        <div class="col-lg-12">
          <?php if(isset($id) && !isset($booking)) { ?>

            <h1><?php echo $title ?> <small> <?php echo $subtitle ?></small></h1>

          <?php }elseif(!isset($id) && isset($booking)) { ?>

            <h1><?php echo $totaldatapoli." Pasien" ?> <small>Terdaftar</small></h1>

          <?php } ?>

          <ol class="breadcrumb">
            <li><a href="<?php echo base_url('booking/dataBooking') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-plus"></i> <?php echo $title ?></li>
          </ol>

        <?php $this->load->view('templates/welcome') ?>
        <?php echo $this->session->flashdata('alert') ?>

        <div class="col-lg-6">
          <form method="post" action="<?php echo base_url('booking/dataAntrian/filterDataAntrianAksi') ?>" role="form">
            <div class="form-group">
              <label>Dokter</label>
              <select class="form-control" type="text" name="id_dokter" required="">
                <option value="">Pilih</option>
                <?php foreach($datadokter as $d): ?>
                  <option value="<?php echo $d->id_dokter ?>"><?php echo $d->nama_dokter ?></option>
                <?php endforeach; ?>
              </select>
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
            <div class="form-group">
              <label>Konter</label>
              <select class="form-control" type="text" name="konter" required="">
                <option value="">Pilih</option>
                <option value="1">Poli Obsgyn Selatan</option>
                <option value="3">Poli Obsgyn Utara</option>
                <option value="2">Poli Anak</option>
              </select>
            </div>
            <button type="submit" class="btn btn-success">Cari</button>
          </form>
        </div>

      </div>
    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

  <!--</div> /#wrapper -->