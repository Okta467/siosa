<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah admin?
if (!isAccessAllowed('admin')) :
  session_destroy();
  echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
else :
  include_once '../config/connection.php';
?>


  <!DOCTYPE html>
  <html lang="en">

  <head>
    <?php include '_partials/head.php' ?>

    <meta name="description" content="Data Informasi" />
    <meta name="author" content="" />
    <title>Informasi - <?= SITE_NAME ?></title>
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
                <h1 class="mb-0">Informasi</h1>
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
                  <i data-feather="compass" class="me-2"></i>
                  Data Informasi
                </div>
                <a class="btn btn-sm btn-primary" href="informasi_halaman_tambah_or_ubah.php?go=informasi"><i data-feather="plus-circle" class="me-2"></i>Tambah Data</a>
              </div>
              <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Judul</th>
                      <th>Isi Informasi</th>
                      <th>Tanggal Dibuat</th>
                      <th>Tanggal Diubah</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $query_informasi = mysqli_query($connection, "SELECT * FROM tbl_informasi ORDER BY id_informasi DESC");

                    while ($informasi = mysqli_fetch_assoc($query_informasi)):
                      $link_ubah_informasi = "informasi_halaman_tambah_or_ubah.php?go=informasi";
                      $link_ubah_informasi .= "&id_informasi={$informasi['id_informasi']}";
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td>
                          <div class="ellipsis toggle_tooltip" title="<?= $informasi['judul_informasi'] ?>">
                            <?= $informasi['judul_informasi'] ?>
                          </div>
                        </td>
                        <td>
                          <div class="ellipsis toggle_tooltip" title="<?= $informasi['isi_informasi'] ?>">
                            <?= $informasi['isi_informasi'] ?>
                          </div>
                        </td>
                        <td><?= $informasi['created_at'] ?></td>
                        <td><?= $informasi['updated_at'] ?></td>
                        <td>
                          <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="<?= $link_ubah_informasi ?>">
                            <i class="fa fa-pen-to-square"></i>
                          </a>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_swal_hapus"
                            data-id_informasi="<?= $informasi['id_informasi'] ?>" 
                            data-judul_informasi="<?= $informasi['judul_informasi'] ?>">
                            <i class="fa fa-trash-can"></i>
                          </button>
                        </td>
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
        $('#datatablesSimple').on('click', '.toggle_swal_hapus', function() {
          const id_informasi   = $(this).data('id_informasi');
          const judul_informasi = $(this).data('judul_informasi');
          
          Swal.fire({
            title: "Konfirmasi Tindakan?",
            html: `Hapus data informasi: <strong>${judul_informasi}?</strong>`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, konfirmasi!"
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: "Tindakan Dikonfirmasi!",
                text: "Halaman akan di-reload untuk memproses.",
                icon: "success",
                timer: 3000
              }).then(() => {
                window.location = `informasi_hapus.php?xid_informasi=${id_informasi}`;
              });
            }
          });
        });
        
      });
    </script>

  </body>

  </html>

<?php endif ?>