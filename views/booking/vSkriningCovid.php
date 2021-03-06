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
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <?php $this->load->view('templates/welcome') ?>
          <?php echo $this->session->flashdata('alert') ?>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <form method="post" action="<?php echo base_url('booking/dataSkrining/diagnosis') ?>" role="form">
            <h5 class="bluetext"><b>A. Gejala</b></h5>
            <ol>
              <div class="form-group">
                <label><li></li></label> Apakah pasien (termasuk pendamping) merasa demam >38&deg;C / riwayat demam <14 hari?
                <select class="form-control" type="text" name="a1" required>
                  <option value="">Pilih</option>
                  <option value='1'>Ya</option>
                  <option value='0'>Tidak</option>"
                </select>
              </div>
              <div class="form-group">
                <label><li></li></label> Apakah pasien (termasuk pendamping) merasa batuk / pilek / sakit tenggorokan / sesak nafas <14 hari?
                <select class="form-control" type="text" name="a2" required>
                  <option value="">Pilih</option>
                  <option value='1'>Ya</option>
                  <option value='0'>Tidak</option>"
                </select>
              </div>
              <div class="form-group">
                <label><li></li></label> Apakah pasien (termasuk pendamping) merasakan nafas cepat / terasa berat <14 hari?<br>
                  <select class="form-control" type="text" name="a3" required>
                    <option value="">Pilih</option>
                    <option value='1'>Ya</option>
                    <option value='0'>Tidak</option>"
                  </select>
                </div>
              </ol>
              <h5 class="bluetext"><b>B. Penyebab (Evaluasi DPJP)</b></h5>
              <ol>
                <div class="form-group">
                  <label><li></li></label> Tidak ada penyebab lain berdasarkan gambaran klinis yang meyakinkan
                  <select class="form-control" type="text" name="b1" required>
                    <option value='1' selected >Ya (Otomatis)</option>
                  </select>
                </div>
              </ol>
            </div>

            <div class="col-lg-6">
              <h5 class="bluetext"><b>C. Faktor Risiko</b></h5>
              <ol>
                <div class="form-group">
                  <label><li></li></label> Apakah pasien (termasuk pendamping) memiliki riwayat perjalanan / tinggal di luar negeri dalam waktu 14 hari sebelum timbul gejala?
                  <select class="form-control" type="text" name="c1" required>
                    <option value="">Pilih</option>
                    <option value='1'>Ya</option>
                    <option value='0'>Tidak</option>"
                  </select>
                </div>
                <div class="form-group">
                  <label><li></li></label> Apakah pasien (termasuk pendamping) memiliki riwayat bepergian dari area transmisi lokal di Indonesia / dari luar kota Yogyakarta / Indogrosir Yogyakarta, dalam waktu 14 hari sebelum timbul gejala?
                  <select class="form-control" type="text" name="c2" required>
                    <option value="">Pilih</option>
                    <option value='1'>Ya</option>
                    <option value='0'>Tidak</option>"
                  </select>
                </div>
                <div class="form-group">
                  <label><li></li></label> Apakah pasien (termasuk pendamping) memiliki riwayat kontak erat dengan pasien yang diduga maupun yang positif COVID-19?<br><br>
                  <select class="form-control" type="text" name="c3" required>
                    <option value="">Pilih</option>
                    <option value='1'>Ya</option>
                    <option value='0'>Tidak</option>"
                  </select>
                </div><br>
                <button type="submit" name="diagnosis" class="btn btn-success">Diagnosis</button><br><br>
              </ol>
            </form>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4">
            <p class="redtext"><b>Tindak Lanjut Curiga PDP :</b></p>
            <ol>
              <li>Siapkan rujukan ke RS rujukan, komunikasi risiko, identifikasi dan pemantauan kontak erat;</li>
            </ol>
          </div>
          <div class="col-lg-4">
            <p class="redtext"><b>Tindak Lanjut Curiga ODP :</b></p>
            <ol>
              <li>Beri masker pada ODP (jika tidak memakai masker);</li>
              <li>Edukasi isolasi di rumah selama 14 hari;</li>
              <li>Edukasi kepada pasien dan keluarga tentang PHBS;</li>
            </ol>
          </div>
          <div class="col-lg-4">
            <p class="redtext"><b>Tindak Lanjut Curiga OTG :</b></p>
            <ol>
              <li>Beri masker pada OTG (jika tidak memakai masker);</li>
              <li>Edukasi isolasi di rumah selama 14 hari;</li>
              <li>Edukasi kepada pasien dan keluarga tentang PHBS;</li>
            </ol>
          </div>
        </div>

      </div><!-- /.row -->

    </div><!-- /#page-wrapper -->

    <!--</div> /#wrapper -->
