<body>

  <div id="wrapper">

    <div id="page-wrapper">

      <div class="row">
        <div class="col-lg-12">

          <h1><?php echo $title ?> <small><?php echo $subtitle ?></small></h1>
          
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url('booking/dataBooking') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-plus"></i> <?php echo $title ?></li>
          </ol>
          <?php $this->load->view('templates/welcome') ?>
        </div>
        <div align="right" class="col-lg-6">
          <?php foreach ($anc as $d) : ?>
            <a href="<?php echo base_url('booking/dataBooking/deleteDataAnc/'.$d->id_anc) ?>"
              onclick="javascript: return confirm('Anda yakin hapus?')">
              <button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</button>
            </a><br><br>
            <div class="table-responsive">
              <table class="table table-bordered table-hover table-striped tablesorter">
                <tbody>
                  <tr>
                    <td><b>Nomor RM</b></td>
                    <td><?php echo $d->id_catatan_medik ?></td>
                  </tr>
                  <tr>
                    <td><b>Nama Pasien</b></td>
                    <td><?php echo $d->nama ?></td>
                  </tr>
                  <tr>
                    <td><b>Alamat</b></td>
                    <td><?php echo $d->alamat ?></td>
                  </tr>
                  <tr>
                    <td><b>Kontak</b></td>
                    <td><?php echo $d->kontak; ?></td>
                  </tr>
                  <tr>
                    <td><b>Dokter</b></td>
                    <td><?php echo $d->nama_petugas ?></td>
                  </tr>
                  <tr>
                    <td><b>Jadwal</b></td>
                    <td><?php echo formatDateIndo($d->jadwal) ?></td>
                  </tr>
                  <tr>
                    <td><b>Sesi</b></td>
                    <td><?php echo $d->nama_sesi ?></td>
                  </tr>
                  <tr>
                    <td><b>Registrasi</b></td>
                    <td><?php echo formatDateIndo($d->tanggal).' / '.$d->jam ?></td>
                  </tr>
                  <tr>
                    <td><b>Status</b></td>
                    <td><?php echo $d->status ?></td>
                  </tr>
                  <tr>
                    <td><b>Keterangan</b></td>
                    <td><?php echo $d->keterangan ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div><!-- /.row -->

    </div><!-- /#page-wrapper -->

    <!--</div> /#wrapper -->