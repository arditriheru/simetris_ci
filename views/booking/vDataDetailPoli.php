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
      </div>

      <div class="row">
        <div class="col-lg-6">
          <?php foreach ($poli as $d) : ?>
            <?php if($this->session->userdata('booking_akses')=='Admin'){ ?>
              <div align="left" class="col-lg-6">
                <a href="<?php echo base_url('booking/dataBooking/updateDataPoli/'.$d->id_booking) ?>">
                  <button type="button" class="btn btn-success"><i class="fa fa-edit"></i> Edit</button>
                </a>
              </div>
              <div align="right" class="col-lg-6">
                <a href="<?php echo base_url('booking/dataBooking/deleteDataPoli/'.$d->id_booking) ?>"
                  onclick="javascript: return confirm('Anda yakin hapus?')">
                  <button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</button>
                </a>
              </div><br><br>
            <?php } ?>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
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
                    <td><?php echo $d->nama_dokter ?></td>
                  </tr>
                  <tr>
                    <td><b>Jadwal</b></td>
                    <td><?php echo formatDateIndo($d->booking_tanggal) ?></td>
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
                    <td><?php echo $d->nama_status ?></td>
                  </tr>
                  <tr>
                    <td><b>Keterangan</b></td>
                    <td><?php echo $d->keterangan ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-bordered table-hover table-striped tablesorter">
                <tbody>
                  <tr>
                    <td><b>HBsAg</b></td>
                    <td>
                      <?php 

                      if(isset($d->id_catatan_medik_hbsag)){ ?>
                        Positif <a href="<?php echo base_url('booking/dataAlergi/hapusHbsag/'.$d->id_booking.'/'.$d->id_catatan_medik) ?>" onclick="javascript: return confirm('Anda yakin ubah negatif?')">(edit)</a>
                      <?php }else{ ?>
                        Negatif <a href="<?php echo base_url('booking/dataAlergi/tambahHbsag/'.$d->id_booking.'/'.$d->id_catatan_medik) ?>" onclick="javascript: return confirm('Anda yakin ubah positif?')">(edit)</a>
                      <?php } ?>
                    </td>
                  </tr>
                  <tr>
                    <td><b>Alergi Makanan</b></td>
                    <td>
                      <?php 

                      if(isset($d->id_catatan_medik_alergi_makanan)){ ?>
                        <?php echo $d->nama_makanan ?>
                        <a href="<?php echo base_url('booking/dataAlergi/dataAlergiMakanan/2/'.$d->id_catatan_medik.'?id_booking='.$id) ?>"> (edit)</a> 
                      <?php }else{ ?>
                        Tidak
                        <a href="<?php echo base_url('booking/dataAlergi/dataAlergiMakanan/1/'.$d->id_catatan_medik.'?id_booking='.$id) ?>"> (edit)</a>
                      <?php } ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div><!-- /.row -->

    </div><!-- /#page-wrapper -->

    <!--</div> /#wrapper -->
