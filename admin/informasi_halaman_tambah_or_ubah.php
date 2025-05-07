<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah admin?
if (!isAccessAllowed('admin')) :
  session_destroy();
  echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
else :
  include_once '../config/connection.php';

  $id_informasi = $_GET['id_informasi'] ?? null;

  if ($id_informasi) {
    $stmt_informasi = mysqli_stmt_init($connection);
    $query_informasi = "SELECT * FROM tbl_informasi WHERE id_informasi=?";

    mysqli_stmt_prepare($stmt_informasi, $query_informasi);
    mysqli_stmt_bind_param($stmt_informasi, 'i', $id_informasi);
    mysqli_stmt_execute($stmt_informasi);

    $result = mysqli_stmt_get_result($stmt_informasi);
    $informasi = mysqli_fetch_assoc($result);
  }

  $form_action = $id_informasi ? 'informasi_ubah.php' : 'informasi_tambah.php';

  $judul_informasi = $informasi['judul_informasi'] ?? null;
  $isi_informasi   = $informasi['isi_informasi'] ?? null;
  $tgl_informasi   = $informasi['created_at'] ?? null;
?>


  <!DOCTYPE html>
  <html lang="en">

  <head>
    <?php include '_partials/head.php' ?>

    <meta name="description" content="Judul Informasi" />
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
          <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
              <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                  <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                      <div class="page-header-icon"><i data-feather="file-plus"></i></div>
                      Informasi Kerja Baru
                    </h1>
                  </div>
                  <div class="col-12 col-xl-auto mb-3">
                    <a class="btn btn-sm btn-light text-primary" href="informasi.php?go=informasi">
                      <i class="me-1" data-feather="arrow-left"></i>
                      Kembali ke Informasi
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </header>
          <!-- Main page content-->
          <div class="container-fluid px-4">
            <form action="<?= $form_action ?>" method="POST">
              <div class="row gx-4">
              
                <div class="col-lg-8">

                  <!-- Judul Informasi -->
                  <div class="card mb-4">
                    <div class="card-header">Judul Informasi <span class="text-danger">*</span></div>
                    <div class="card-body">

                      <input type="hidden" name="xid_informasi" value="<?= $id_informasi ?>">
                      
                      <div class="mb-3">
                        <input type="text" name="xjudul_informasi" value="<?= $judul_informasi ?>" class="form-control" id="xjudul_informasi" placeholder="Enter judul informasi" required>
                      </div>
                  
                    </div>
                  </div>
                  
                  <!-- Isi Informasi -->
                  <div class="card card-header-actions mb-4 mb-lg-0">
                    <div class="card-header">
                      <span>
                        Isi Informasi <span class="text-danger">*</span>
                      </span>
                      <i class="text-muted" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="left" title=""></i>
                    </div>
                    <div class="card-body">
                      <p class="small text-danger">Teks maksimal 5000 karakter!</p>
                      <textarea name="xisi_informasi" id="isiInformasiEditor"></textarea>
                    </div>
                  </div>

                </div>


                <div class="col-lg-4">
                  <div class="card">
                    <div class="card-header">
                      Publish
                    </div>
                    <div class="card-body text-center p-5">
                      <div class="d-grid">
                        <button class="fw-500 btn btn-primary" id="toggle_swal_submit" type="submit">Simpan Informasi</button>
                      </div>
                    </div>
                  </div>
                </div>
              
              </div>
            </form>
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
        var easyMDEDetailInformasi = new EasyMDE({
          element: document.getElementById('isiInformasiEditor'),
          toolbar: ['bold', 'italic', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', 'preview', 'guide'],
        });


        const select2 = $('.select2');

        initSelect2(select2, {
          width: '100%',
          dropdownParent: 'body'
        });
        

        const formSubmitBtn = $('#toggle_swal_submit');
        const eventName = 'click';
        const formElement = formSubmitBtn.parents('div.container-fluid').find('form');

        toggleSwalSubmit(formSubmitBtn, eventName, formElement);


        const detail = `<?= htmlspecialchars($isi_informasi, ENT_QUOTES, 'UTF-8') ?>`;
        const sanitizedDetail = DOMPurify.sanitize(`<?= "{$isi_informasi}" ?>`, { USE_PROFILES: { html: true } });
        
        easyMDEDetailInformasi.value(sanitizedDetail)
      });
    </script>

  </body>

  </html>

<?php endif ?>