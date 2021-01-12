<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Expired Date Obat ".date('d/m/Y').".xls");
?>
<!DOCTYPE html>
<html>
<body>
  <table border="1" class="table table-bordered table-hover table-striped tablesorter">
    <thead>
      <h2 align="center">Rekap Data</h2>
      <h2 align="center">Expired Date Obat</h2>
      <h3 align="center"><?php echo $data ?></h3>
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
</body>
</html>