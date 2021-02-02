<body>

  <script>
    window.print();
  </script>

  <div id="wrapper">

    <div id="page-wrapper">

      <div class="row">
        <div class="col-lg-12">
          <div align="left">
            <img class="img-responsive" src="<?php echo base_url('assets/images/logo-rachmi-akreditasi-kars.png') ?>" width="60%" alt="Kop Surat">
          </div>
        </div><br>
      </div>
      <?php foreach ($data as $d) :?>
        <div class="row">
          <div class="col-lg-12">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td class="text-center" colspan="2"><b>Pemeriksaan SWAB Antigen</b></td>
                  <td class="text-center" colspan="2"><b>#INVOICE</b></td>
                </tr>
                <tr>
                  <td>Registrasi</td>
                  <td><?php echo formatDateIndo($d->tanggal).' / '.$d->jam; ?></td>
                  <td>DATE</td>
                  <td><?php echo getDateIndo(); ?></td>
                </tr>
                <tr>
                  <td>Tempat</td>
                  <td>RSKIA Rachmi Yogyakarta</td>
                  <td>INVOICE</td>
                  <td><?php echo strtoupper($d->no_invoice); ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-lg-12">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td>No. Identitas</td>
                  <td><?php echo $d->no_identitas ?></td>
                  <td>Alamat</td>
                  <td><?php echo $d->alamat ?></td>
                </tr>
                <tr>
                  <td>Nama Pasien</td>
                  <td><?php echo $d->nama ?></td>
                  <td>Email</td>
                  <td><?php echo $d->email ?></td>
                </tr>
                <tr>
                  <td>Tgl. Lahir</td>
                  <td><?php echo formatDateIndo($d->tgl_lahir) ?></td>
                  <td>Kontak</td>
                  <td><?php echo $d->kontak ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-lg-12">
            <table class="table table-bordered table-hover table-striped tablesorter">
              <thead>
                <tr>
                  <td class="text-center"><b>Deskripsi</b></td>
                  <td class="text-center"><b>Sub Total</b></td>
                </tr>
              </thead>
              <tbody>
               <tr>
                <td class="text-center">Pembayaran : Pemeriksaan SWAB Antigen</td>
                <td class="text-center">IDR250.000</td>
              </tr>
              <tr>
                <td class="text-center"><b>TOTAL</b></td>
                <td class="text-center"><b>IDR250.000</b></td>
              </tr>
            </tbody>
            </table><?php endforeach; ?>
            <br><br><br>
            <b>Sekretariat :</b><br>
            <p>Rumah Sakit Khusus Ibu dan Anak Rachmi Yogyakarta<br>
              Jalan KH Wachid Hayim No.47, Ngampilan, Kota Yogyakarta<br>
              D.I.Yogyakarta<br>
            (0274) 376717 / (0274) 415316</p>
          </div>
        </div><!-- /.row -->

      </div><!-- /#page-wrapper -->

      <!-- </div> /#wrapper  -->
