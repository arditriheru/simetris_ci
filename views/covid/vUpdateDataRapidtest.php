<body>

  <div id="wrapper">

    <div id="page-wrapper">

      <div class="row">
        <div class="col-lg-12">
          <h1><?php echo $title ?> <small> <?php echo $subtitle ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url('covid/dataRapidTest') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-plus"></i> <?php echo $title ?></li>
          </ol>
          <?php $this->load->view('templates/welcome') ?>
          <?php echo $this->session->flashdata('update_success') ?>
        </div>
        <div class="col-lg-6">
          <div class="table-responsive">
            <?php foreach ($rapidtest as $d): ?>
              <form method="post" action="<?php echo base_url('covid/dataRapidtest/updateDataAksi') ?>" role="form">
                <div class="form-group">
                  <label>Nomor Rekam Medik</label>
                  <input class="form-control" type="hidden" value="<?php echo $d->id_rapidtest ?>" name="id_rapidtest">
                  <input class="form-control" type="text" value="<?php echo $d->id_catatan_medik ?>" name="id_catatan_medik" readonly="">
                </div>
                <div class="form-group">
                  <label>Nama</label>
                  <input class="form-control" type="text" id="nama" name="nama" 
                  value="<?php echo $d->nama ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Dokter</label>
                  <select class="form-control" type="text" name="id_dokter" required="">
                    <option value="<?php echo $d->id_dokter ?>"><?php echo $d->nama_dokter ?></option>
                    <?php foreach($datadokter as $a): ?>
                      <option value="<?php echo $a->id_dokter ?>"><?php echo $a->nama_dokter ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Asal</label>
                  <select class="form-control" type="text" name="id_unit" required="">
                    <option value="<?php echo $d->id_unit ?>"><?php echo $d->nama_unit ?></option>
                    <?php foreach($dataunit as $a): ?>
                      <option value="<?php echo $a->id_unit ?>"><?php echo $a->nama_unit ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Tanggal Periksa</label>
                  <input class="form-control" type="date" name="tgl_periksa" value="<?php echo $d->tgl_periksa ?>" required="">
                </div>
                <div class="form-group">
                  <label>Jam Periksa</label>
                  <input class="form-control" type="text" name="jam_periksa" value="<?php echo $d->jam_periksa ?>" required="">
                </div>
                <div class="form-group">
                  <label>IgM</label>
                  <select class="form-control" type="text" name="igm" required="">
                    <option value="<?php echo $d->igm ?>"><?php echo $d->nama_igm ?></option>
                    <option value="3">On Process</option>
                    <option value="1">Reaktif</option>
                    <option value="0">Non Reaktif</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>IgG</label>
                  <select class="form-control" type="text" name="igg" required="">
                    <option value="<?php echo $d->igg ?>"><?php echo $d->nama_igg ?></option>
                    <option value="3">On Process</option>
                    <option value="1">Reaktif</option>
                    <option value="0">Non Reaktif</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Pemeriksa</label>
                  <input class="form-control" type="text" value="<?php echo $d->pemeriksa ?>" name="pemeriksa" readonly="">
                </div>
                <button type="submit" name="submit" class="btn btn-success">Update</button>
              </form>
            <?php endforeach ?>
          </div>
        </div>
      </div><!-- /.row -->

    </div><!-- /#page-wrapper -->

    <!--</div> /#wrapper -->
