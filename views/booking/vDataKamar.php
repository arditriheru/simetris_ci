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
          <?php echo $this->session->flashdata('alert') ?>

          <div class="table-responsive">
            <div class="col-lg-8">
              <table class="table table-bordered table-hover table-striped tablesorter">
                <thead>
                  <tr>
                    <th class="text-center">No</i></th>
                    <th class="text-center">No.Bed</th>
                    <th class="text-center">Kamar</i></th>
                    <th class="text-center">Kelas</i></th>
                    <th class="text-center">Keterangan</i></th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($datakamar as $d) : ?>
                  <tr class="active">
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td class="text-center"><?php echo 'Bed '.$d->no_bed; ?></td>
                    <td class="text-center"><?php echo $d->nama_unit; ?></td>
                    <td class="text-center"><?php echo $d->kelas; ?></td>
                    <td class="text-center">
                      <?php
                      if($d->ket_antri==3){ ?>
                        Kosong
                      <?php }else{ ?>
                        <font color="red">Terpakai</font>
                      <?php } ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

  <!--</div> /#wrapper -->
