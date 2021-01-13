<body>

  <script>
    window.print();
  </script>

  <div id="wrapper">

    <div id="page-wrapper">

      <div class="row">
        <div class="col-lg-12">
          <table class="table table-bordered">
           <thead>
            <p align="center"><strong>Rekap Data</strong></p>
            <p align="center"><strong>Expired Date Obat</strong></p>
            <p align="center"><strong><?php echo $data ?></strong></p>
            <tr>
              <th><div align="center">No</div></th>
              <th><div align="center">Kode Obat</div></th>
              <th><div align="center">Nama Obat</div></th>
              <th><div align="center">Stok Ralan</div></th>
              <th><div align="center">Stok Ranap</div></th>
              <th><div align="center">Expired Date</div></th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($dataedobat as $d) : ?>
            <tr>
              <td><div align="center"><?php echo $no++; ?></div></td>
              <td><div align="center"><?php echo $d->no_urut ?></div></td>
              <td><div align="center"><?php echo $d->nama; ?></div></td>
              <td><div align="center"><?php echo $d->stok_poli; ?></div></td>
              <td><div align="center"><?php echo $d->stok_inap; ?></div></td>
              <td><div align="center"><?php echo $d->tgl_ed; ?></div></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div><!-- /.row -->

</div><!-- /#page-wrapper -->

  <!-- </div> /#wrapper  -->