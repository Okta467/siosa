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

    <meta name="description" content="Data Profil" />
    <meta name="author" content="" />
    <title>Profil - <?= SITE_NAME ?></title>
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

        <?php
        $id_customer = $_SESSION['id_customer'];

        if ($id_customer) {
          $stmt_customer = mysqli_stmt_init($connection);
          $query_customer = 
            "SELECT
              a.id_customer, a.nama_customer, a.jenis_kelamin, a.alamat, a.tempat_lahir, a.tanggal_lahir, a.path_to_foto_profil,
              f.id_pengguna, f.username, f.hak_akses
            FROM tbl_customer AS a
            LEFT JOIN tbl_pengguna AS f
              ON a.id_pengguna = f.id_pengguna
            WHERE a.id_customer=?";
          
          mysqli_stmt_prepare($stmt_customer, $query_customer);
          mysqli_stmt_bind_param($stmt_customer, 'i', $id_customer);
          mysqli_stmt_execute($stmt_customer);

          $result = mysqli_stmt_get_result($stmt_customer);
          $customer = mysqli_fetch_assoc($result);

          $hak_akses = $customer['hak_akses'] ? ucwords(str_replace('_', ' ', $customer['hak_akses'])) : null;
          $tanggal_lahir = date('d M Y', strtotime($customer['tanggal_lahir']));
          $path_foto_profil = $customer['path_to_foto_profil']
            ? base_url_return("assets/uploads/path_to_foto_profil/{$customer['path_to_foto_profil']}")
            : base_url_return('assets/img/illustrations/profiles/profile-5.png');
        }

        $path_foto_profil = $path_foto_profil ?? base_url_return('assets/img/illustrations/profiles/profile-5.png');
        ?>

        <main>
          <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
              <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                  <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                      <div class="page-header-icon"><i data-feather="user"></i></div>
                      Pengaturan Akun - Profil
                    </h1>
                  </div>
                </div>
              </div>
            </div>
          </header>
          <!-- Main page content-->
          <div class="container-xl px-4 mt-4">
            <!-- Account page navigation-->
            <nav class="nav nav-borders">
              <a class="nav-link active ms-0" href="profil.php?go=profil">Profil</a>
              <a class="nav-link" href="profil_password.php?go=profil">Password</a>
            </nav>
            <hr class="mt-0 mb-4">
            <div class="row">
              <div class="col-xl-4">
                <!-- Foto Profil card-->
                <div class="card mb-4 mb-xl-0">
                  <div class="card-header">Foto Profil</div>
                  <div class="card-body text-center">
                    <form action="profil_foto_profil_ubah.php" method="post" enctype="multipart/form-data">
                      <!-- Foto Profil image-->
                      <img class="img-account-profile rounded-circle mb-2" src="<?= $path_foto_profil ?>" alt="">
                      <!-- Foto Profil help block-->
                      <div class="small font-italic text-muted mb-4">JPG atau PNG tidak lebih dari 500 KB</div>
                      <!-- Foto Profil upload button-->
                      <button class="btn btn-primary" type="button" onclick="document.getElementById('xfoto_profil').click()">Unggah foto profil baru</button>
                      <input class="form-control d-none" id="xfoto_profil" type="file" name="xfoto_profil" accept=".jpg,.png" required>
                      <!-- Form Group (Halaman Saat ini)-->
                      <input class="form-control d-none" id="xhalaman_saat_ini" type="text" name="xhalaman_saat_ini" value="profil">
                    </form>
                    <hr>
                    <div class="mt-4 text-start">
                      <!-- Detail Akun (Nama Customer) -->
                      <div class="small mt-1">
                        <i data-feather="user" class="me-3"></i>
                        <?= $customer['nama_customer'] ?? '<small class="text-muted">Tidak ada</small>' ?>
                      </div>
                      <!-- Detail Akun (Hak Akses) -->
                      <div class="small mt-1">
                        <i data-feather="key" class="me-3"></i>
                        <?= $hak_akses ?? '<small class="text-muted">Tidak ada</small>' ?>
                      </div>
                      <!-- Detail Akun (Alamat) -->
                      <div class="small mt-1">
                        <i data-feather="home" class="me-3"></i>
                        <?= $customer['alamat'] ?? '<small class="text-muted">Tidak ada</small>' ?>
                      </div>
                      <!-- Detail Akun (Tempat, Tanggal Lahir) -->
                      <div class="small mt-1">
                        <i data-feather="gift" class="me-3"></i>
                        <?= isset($customer['tempat_lahir']) ? "{$customer['tempat_lahir']}, " : '<small class="text-muted">Tidak ada, </small>' ?>
                        <?= $tanggal_lahir ?? '<small class="text-muted">xx xx xxxx</small>' ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-8" id="detail_akun">
                <!-- Detail Akun card-->
                <div class="card mb-4">
                  <div class="card-header">Detail Akun</div>
                  <div class="card-body">
                    <form action="profil_ubah.php" method="post">
          
                      <!-- Form Group (Alamat)-->
                      <div class="mb-3">
                        <label class="small mb-1" for="xalamat">Alamat <span class="text-danger fw-bold">*</span></label>
                        <input class="form-control" id="xalamat" type="text" name="xalamat" value="<?= $customer['alamat'] ?? '' ?>" placeholder="Enter alamat" required>
                      </div>
          
                      <!-- Form Row-->
                      <div class="row gx-3 mb-3">
                        <!-- Form Group (Tempat Lahir)-->
                        <div class="col-md-6">
                          <label class="small mb-1" for="xtempat_lahir">Tempat Lahir <span class="text-danger fw-bold">*</span></label>
                          <input class="form-control" id="xtempat_lahir" type="text" name="xtempat_lahir" value="<?= $customer['tempat_lahir'] ?? '' ?>" placeholder="Enter tempat lahir" required>
                        </div>
                        <!-- Form Group (Tanggal Lahir)-->
                        <div class="col-md-6">
                          <label class="small mb-1" for="xtanggal_lahir">Tanggal Lahir <span class="text-danger fw-bold">*</span></label>
                          <input class="form-control" id="xtanggal_lahir" type="date" name="xtanggal_lahir" value="<?= $customer['tanggal_lahir'] ?? '' ?>" required>
                        </div>
                      </div>
          
                      <!-- Simpan button-->
                      <button class="btn btn-primary toggle_swal_submit" type="button">Simpan</button>
                    </form>
                  </div>
                </div>
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
        
        const selectDetailAkun = $('#detail_akun .select2');
        initSelect2(selectDetailAkun, {
          width: '100%',
          dropdownParent: "#detail_akun"
        });
        

        const formSubmitBtn = $('.toggle_swal_submit');
        const eventName = 'click';
        const form = $('#detail_akun form');
        
        toggleSwalSubmit(formSubmitBtn, eventName, form);


        $('#xfoto_profil').on('input', function() {
          const form = $(this).parents('form');
          
          form.submit();
        });
        
        
        $('#xid_pendidikan').on('change', function() {
          const id_pendidikan = $(this).val();
          const nama_pendidikan = $(this).find('option:selected').text();
        
          $.ajax({
            url: 'get_jurusan_pendidikan.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
              'id_pendidikan' : id_pendidikan,
            },
            success: function(data) {
              if (!id_pendidikan) {
                $('#detail_akun span.xid_jurusan').html('Pilih pendidikan terlebih dahulu!');
                $('#detail_akun span.xid_jurusan').removeClass('form-control'); // to make sure there's no form-control before adding new one
                $('#detail_akun span.xid_jurusan').addClass('form-control');
                return;
              }
        
              if (['tidak_sekolah', 'sd', 'smp', 'sltp'].includes(nama_pendidikan.toLowerCase())) {
                $('#detail_akun span.xid_jurusan').html('Tidak perlu diisi.');
                $('#detail_akun span.xid_jurusan').removeClass('form-control'); // to make sure there's no form-control before adding new one
                $('#detail_akun span.xid_jurusan').addClass('form-control');
                return;
              }
        
              // set select html for select2 initialization
              const jurusan_select2_html = `<select name="xid_jurusan" class="form-control form-control-sm select2 xid_jurusan" id="xid_jurusan" required style="width: 100%"></select>`;
              
              // Clear text and specific style for span id jurusan
              $('#detail_akun span.xid_jurusan').html(jurusan_select2_html);
              $('#detail_akun span.xid_jurusan').removeClass('form-control');
        
              // Transform the data to the format that Select2 expects
              var transformedData = data.map(item => ({
                id: item.id_jurusan,
                text: item.nama_jurusan
              }));
              
              const jurusanSelect = $('select#xid_jurusan');
              jurusanSelect.select2({ 'width': '100%' });
              
              // Clear the select element
              jurusanSelect.html(null);
              
              // Set the transformed data to the select element using select2 method
              initSelect2(jurusanSelect, {
                width: '100%',
                dropdownParent: "#detail_akun",
                data: transformedData
              });
              
            },
            error: function(request, status, error) {
              // console.log("ajax call went wrong:" + request.responseText);
              console.log("ajax call went wrong:" + error);
            }
          })
        });

        $('#xid_pendidikan').trigger('change');
        
      });
    </script>

  </body>

  </html>

<?php endif ?>