<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah supervisor?
if (!isAccessAllowed('supervisor')) :
  session_destroy();
  echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
else :
  include_once '../config/connection.php';
?>


  <!DOCTYPE html>
  <html lang="en">

  <head>
    <?php include '_partials/head.php' ?>

    <meta name="description" content="Data Pesanan" />
    <meta name="author" content="" />
    <title>Pesanan - <?= SITE_NAME ?></title>
  </head>

  <body class="nav-fixed">
    <!--============================= TOPNAV =============================-->
    <?php include '_partials/topnav.php' ?>
    <!--//END TOPNAV -->
    <div id="layoutSidenav">
      <div id="layoutSidenav_nav">
        <!--============================= SIDEBAR =============================-->
        <?php include '_partials/sidebar.php' ?>
        <!--//END SIDEBAR -->
      </div>
      <div id="layoutSidenav_content">
        <main>
          <!-- Main page content-->
          <div class="container-xl px-4 mt-5">

            <!-- Custom page header alternative example-->
            <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
              <div class="me-4 mb-3 mb-sm-0">
                <h1 class="mb-0">Pesanan</h1>
                <div class="small">
                  <span class="fw-500 text-primary"><?= date('D') ?></span>
                  &middot; <?= date('M d, Y') ?> &middot; <?= date('H:i') ?> WIB
                </div>
              </div>

              <!-- Date range picker example-->
              <div class="input-group input-group-joined border-0 shadow w-auto">
                <span class="input-group-text"><i data-feather="calendar"></i></span>
                <input class="form-control ps-0 pointer" id="litepickerRangePlugin" value="Tanggal: <?= date('d M Y') ?>" readonly />
              </div>

            </div>
            
            <!-- Tools Cetak Pengumuman -->
            <div class="card mb-4 mt-5">
              <div class="card-header">
                <div>
                  <i data-feather="settings" class="me-2 mt-1"></i>
                  Tools Cetak Laporan
                </div>
              </div>
              <div class="card-body">
                <div class="row gx-3">
                  <div class="col-md-2 mb-3">
                    <label class="small mb-1" for="xdari_tanggal">Dari Tanggal</label>
                    <input class="form-control" id="xdari_tanggal" type="date" name="xdari_tanggal" required>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label class="small mb-1" for="xsampai_tanggal">Sampai Tanggal</label>
                    <input class="form-control" id="xsampai_tanggal" type="date" name="xsampai_tanggal" required>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label class="small mb-1 invisible" for="xcetak_laporan">Filter Button</label>
                    <button class="btn btn-primary w-100" id="xcetak_laporan" type="button">
                      <i data-feather="printer" class="me-1"></i>
                      Cetak
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Main page content-->
            <div class="card card-header-actions mb-4 mt-5">
              <div class="card-header">
                <div>
                  <i data-feather="shopping-bag" class="me-2 mt-1"></i>
                  Data Pesanan
                </div>
              </div>
              <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Kode</th>
                      <th>Customer</th>
                      <th>Nama Barang</th>
                      <th>Jumlah</th>
                      <th>Satuan</th>
                      <th>Harga</th>
                      <th>Total</th>
                      <th>Tanggal Pesanan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $query_pesanan = mysqli_query($connection,
                      "SELECT
                        a.id_pesanan, a.tanggal_pesanan, a.jumlah_pesanan, a.created_at AS created_at_pesanan, a.updated_at AS updated_as_peasnan,
                        b.id_barang, b.kode_barang, b.nama_barang, b.satuan_barang, b.harga_barang, b.created_at AS created_at_barang, b.updated_at AS updated_at_barang,
                        c.id_customer, c.nama_customer, c.jenis_kelamin, c.alamat, c.tempat_lahir, c.tanggal_lahir, c.created_at AS created_at_customer, c.updated_at AS updated_at_customer,
                        d.id_pengguna, d.username, d.hak_akses, d.created_at AS created_at_pengguna, d.last_login
                      FROM tbl_pesanan AS a
                      LEFT JOIN tbl_barang AS b
                        ON a.id_barang = b.id_barang
                      LEFT JOIN tbl_customer AS c
                        ON a.id_customer = c.id_customer
                      LEFT JOIN tbl_pengguna AS d
                        ON c.id_pengguna = d.id_pengguna
                      ORDER BY a.id_pesanan DESC"
                    );

                    while ($pesanan = mysqli_fetch_assoc($query_pesanan)):
                      $harga_barang = 'Rp'.number_format($pesanan['harga_barang'], 0, ',', '.');
                      $total_harga = $pesanan['jumlah_pesanan'] * $pesanan['harga_barang'];
                      $total_harga = 'Rp'.number_format($total_harga);
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $pesanan['id_pesanan'] ?></td>
                        <td>
                          <div class="ellipsis toggle_tooltip" title="<?= $pesanan['nama_customer'] ?>">
                            <?= htmlspecialchars($pesanan['nama_customer']); ?>
                          </div>
                        </td>
                        <td><?= $pesanan['nama_barang'] ?></td>
                        <td><?= $pesanan['jumlah_pesanan'] ?></td>
                        <td>
                          <?php if ($pesanan['satuan_barang'] === 'dus'): ?>
                            <span class="text-danger"><?= $pesanan['satuan_barang'] ?></span>
                          <?php elseif ($pesanan['satuan_barang'] === 'box'): ?>
                            <span class="text-primary"><?= $pesanan['satuan_barang'] ?></span>
                          <?php elseif ($pesanan['satuan_barang'] === 'pcs'): ?>
                            <span class="text-success"><?= $pesanan['satuan_barang'] ?></span>
                          <?php endif ?>
                        </td>
                        <td><?= $harga_barang ?></td>
                        <td><?= $total_harga ?></td>
                        <td><?= $pesanan['tanggal_pesanan'] ?></td>
                      </tr>

                    <?php endwhile ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </main>

        <!--============================= FOOTER =============================-->
        <?php include '_partials/footer.php' ?>
        <!--//END FOOTER -->

      </div>
    </div>

    <?php include '_partials/script.php' ?>
    <?php include '../helpers/sweetalert2_notify.php' ?>
    
    <!-- PAGE SCRIPT -->
    <script>
      $(document).ready(function() {
        $('#xcetak_laporan').on('click', function() {
          const dari_tanggal = $('#xdari_tanggal').val();
          const sampai_tanggal = $('#xsampai_tanggal').val();
          
          const url = `laporan_pesanan.php?dari_tanggal=${dari_tanggal}&sampai_tanggal=${sampai_tanggal}`;
          
          printExternal(url);
        });
        
      });
    </script>

  </body>

  </html>

<?php endif ?>