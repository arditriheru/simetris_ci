<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo getTopTitle()." - ".$title." ".$subtitle ?></title>

  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url() ?>assets/css/bootstrap.css" rel="stylesheet">

  <!-- Add custom CSS here -->
  <link href="<?php echo base_url() ?>assets/css/sb-admin.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/font-awesome/css/font-awesome.min.css">

  <style>
    #table {
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
      font-size: 13px;
    }

    #table td, #table th {
      border: 0px solid #ddd;
      padding: 8px;
    }

    #table tr:hover {
      background-color: #ddd;
    }

    #table th {
      padding-top: 10px;
      padding-bottom: 10px;
      text-align: left;
      background-color: #f2a154;
      color: white;
    }

    #table2 {
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
      font-size: 13px;
    }

    #table2 td, #table2 th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    #table2 tr:hover {
      background-color: #ddd;
    }

    #table2 th {
      padding-top: 10px;
      padding-bottom: 10px;
      text-align: left;
      background-color: #f2a154;
      color: white;
    }
  </style>
</head>
<body>
  <table id="table" width="100%">
   <tbody>
     <tr>
      <td style="text-align:left">
        <h3>RSKIA Rachmi Yogyakarta</h3>
        <p>Rumah Sakit Khusus Ibu dan Anak Rachmi Yogyakarta<br>
          Jalan KH Wachid Hayim No.47, Ngampilan, Kota Yogyakarta<br>
          D.I.Yogyakarta<br>
        (0274) 376717 / (0274) 415316</p>
      </td>
      <td style="text-align:right;"><h1>#INVOICE</h1></td>
    </tr>
  </tbody>
</table>
<?php foreach ($data as $d) :?>
  <table id="table" width="100%" style="text-align:left">
    <thead>
      <tr>
        <th style="text-align:center" colspan="4">PEMERIKSAAN SWAB ANTIGEN</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>Registrasi</strong></td>
        <td><?php echo formatDateIndo($d->tanggal); ?></td>
        <td><strong>DATE</strong></td>
        <td><?php echo getDateIndo(); ?></td>
      </tr>
      <tr>
        <td><strong>Jam</strong></td>
        <td><?php echo $d->jam.' WIB'; ?></td>
        <td><strong>NO. INVOICE</strong></td>
        <td><?php echo strtoupper($d->no_invoice); ?></td>
      </tr>
    </tbody>
  </table><br>
  <table id="table" width="100%" style="text-align:left">
    <thead>
      <tr>
        <th style="text-align:center" colspan="4">IDENTITAS PASIEN</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>No. Identitas</strong></td>
        <td><?php echo $d->no_identitas ?></td>
        <td><strong>Alamat</strong></td>
        <td><?php echo $d->alamat ?></td>
      </tr>
      <tr>
        <td><strong>Nama Pasien</strong></td>
        <td><?php echo $d->nama ?></td>
        <td><strong>Email</strong></td>
        <td><?php echo $d->email ?></td>
      </tr>
      <tr>
        <td><strong>Tgl. Lahir</strong></td>
        <td><?php echo formatDateIndo($d->tgl_lahir) ?></td>
        <td><strong>Kontak</strong></td>
        <td><?php echo $d->kontak ?></td>
      </tr>
    </tbody>
  </table><br>
  <table id="table2" width="100%" style="text-align:left">
    <thead>
      <tr>
       <th style="text-align:center"><b>DESKRIPSI</b></th>
       <th style="text-align:center"><b>SUB TOTAL</b></th>
     </tr>
   </thead>
   <tbody>
     <tr>
      <td style="text-align:center">Pembayaran : Pemeriksaan SWAB Antigen</td>
      <td style="text-align:center">IDR250.000</td>
    </tr>
    <tr>
      <td style="text-align:center"><b>TOTAL</b></td>
      <td style="text-align:center"><b>IDR250.000</b></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align:center"><b>STATUS : 
        <?php if($d->validasi==0){ ?>
          PENDING
        <?php }else{ ?>
          LUNAS
        <?php } ?>
      </b></td>
    </tr>
  </tbody>
</table>
<div align="right"><p><small>Printed : <?php echo getDateIndo(); ?></small></p></div>
<?php endforeach; ?>
</body>
</html>

<!-- <body>
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
                  <td><?php echo strtoupper($d->alamat) ?></td>
                </tr>
                <tr>
                  <td>Nama Pasien</td>
                  <td><?php echo strtoupper($d->nama) ?></td>
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
              <tr>
                <td colspan="2" class="text-center"><b>LUNAS</b></td>
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

        <!-- </div>/#page-wrapper -->

      <!-- </div> /#wrapper  -->