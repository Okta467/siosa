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
          <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
              <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                  <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                      <div class="page-header-icon"><i data-feather="info"></i></div>
                      Informasi - Detail
                    </h1>
                  </div>
                </div>
              </div>
            </div>
          </header>

          <!-- Main page content-->
          <div class="container-xl px-4">

            <?php
            $id_informasi = $_GET['id_informasi'];
            $query = "SELECT * FROM tbl_informasi WHERE id_informasi=?";
            $stmt = mysqli_stmt_init($connection);

            mysqli_stmt_prepare($stmt, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_informasi);
            mysqli_stmt_execute($stmt);
        
            $result = mysqli_stmt_get_result($stmt);
            $informasi = mysqli_fetch_assoc($result);

            $isi_informasi = htmlspecialchars($informasi['isi_informasi']);
            ?>
            
            <!-- Knowledge base article-->
            <div class="card mb-4">
              <div class="card-header d-flex align-items-center">
                <a class="btn btn-transparent-dark btn-icon" href="informasi.php?go=informasi">
                  <i data-feather="arrow-left"></i>
                </a>
                <div class="ms-3">
                  <h2 class="my-3 xjudul_informasi"><?= $informasi['judul_informasi'] ?></h2>
                </div>
              </div>
              <div class="card-body">
                <div class="p-3" id="detail_proyek"></div>
              </div>
            </div>
          
            <!-- Knowledge base rating-->
            <div class="text-center mt-5">
              <h4 class="mb-3">Apakah informasi ini bermanfaat?</h4>
              <div class="mb-3">
                <button class="btn btn-primary mx-2 px-3" role="button">
                  <i data-feather="thumbs-up" class="me-2"></i> Ya
                </button>
                <button class="btn btn-primary mx-2 px-3" role="button">
                  <i data-feather="thumbs-down" class="me-2"></i> Tidak
                </button>
              </div>
              <div class="text-small text-muted"><em>29 orang merasa informasi ini bermanfaat!</em></div>
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
        const html_text = `<?= $isi_informasi ?>`;

        const sanitized_html_text = DOMPurify.sanitize(html_text, { USE_PROFILES: { html: true } });
        const parsed_html_text = marked.parse(sanitized_html_text);
  
        $('#detail_proyek').html(parsed_html_text);
      });
    </script>

  </body>

  </html>

<?php endif ?>