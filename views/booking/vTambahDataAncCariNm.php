<body>

  <div id="wrapper">

    <div id="page-wrapper">

      <div class="row">
        <div class="col-lg-12">
          <h1><?php echo $title ?> <small> <?php echo $subtitle ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url('covid/dataBooking') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-plus"></i> <?php echo $title ?></li>
          </ol>

          <?php $this->load->view('templates/welcome') ?>
          <?php echo $this->session->flashdata('alert') ?>

          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped tablesorter">
              <thead>
                <tr>
                  <th class="text-center">No.RM</i></th>
                  <th class="text-center">Nama</i></th>
                  <th class="text-center">Tempat, Tanggal Lahir</i></th>
                  <th class="text-center">Action</i></th>
                </tr>
              </thead>
              <tbody>

                <?php foreach ($caripasien as $d) : ?>

                  <tr>
                    <td class="text-center"><?php echo $d->id_catatan_medik;?></td>
                    <td class="text-center"><?php echo $d->nama;?></td>
                    <td class="text-center"><?php echo $d->tempat.', '.date("d F Y", strtotime($d->tgl_lahir));?></td>
                    <td class="text-center">              
                      <a href="<?php echo base_url('booking/dataBooking/tambahDataAncNm/'.$d->id_catatan_medik) ?>"><button type="button" class="btn btn-success"><i class='fa fa-plus'></i> Daftar</button></a>
                    </td>
                  </tr>

                <?php endforeach; ?>

              </tbody>
            </table>
          </div>
        </div><!-- /.row -->

      </div><!-- /#page-wrapper -->

      <!--</div> /#wrapper -->
