<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah customer?
if (!isAccessAllowed('customer')) :
  session_destroy();
  echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
else :
  include_once '../config/connection.php';
?>


  <!DOCTYPE html>
  <html lang="en">

  <head>
    <?php include '_partials/head.php' ?>

    <meta name="description" content="Data Barang" />
    <meta name="author" content="" />
    <title>Barang - <?= SITE_NAME ?></title>
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
                <h1 class="mb-0">Barang</h1>
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

            <!-- Main page content-->
            <div class="card card-header-actions mb-4 mt-5">
              <div class="card-header">
                <div>
                  <i data-feather="grid" class="me-2 mt-1"></i>
                  Data Barang
                </div>
              </div>
              <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Kode barang</th>
                      <th>Nama Barang</th>
                      <th>Satuan</th>
                      <th>Harga</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $query_barang = mysqli_query(
                      $connection,
                      "SELECT id_barang, kode_barang, nama_barang, satuan_barang, harga_barang
                      FROM tbl_barang
                      ORDER BY id_barang DESC"
                    );

                    while ($barang = mysqli_fetch_assoc($query_barang)):
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($barang['kode_barang']) ?></td>
                        <td><?= htmlspecialchars($barang['nama_barang']) ?></td>
                        <td>
                          <?php if ($barang['satuan_barang'] === 'dus'): ?>
                            <span class="text-danger"><?= $barang['satuan_barang'] ?></span>
                          <?php elseif ($barang['satuan_barang'] === 'box'): ?>
                            <span class="text-primary"><?= $barang['satuan_barang'] ?></span>
                          <?php elseif ($barang['satuan_barang'] === 'pcs'): ?>
                            <span class="text-success"><?= $barang['satuan_barang'] ?></span>
                          <?php endif ?>
                        </td>
                        <td><?= 'Rp' . number_format($barang['harga_barang'], 0, ',', '.') ?></td>
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

  </body>

  </html>

<?php endif ?>