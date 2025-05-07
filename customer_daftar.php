<!DOCTYPE html>
<html lang="en">

<head>
  <?php session_start() ?>
  <?php include 'config/config.php' ?>
  <?php include 'login_head.php' ?>
  <?php include 'helpers/isAccessAllowedHelper.php' ?>
  <?php include 'helpers/isAlreadyLoginHelper.php' ?>

  <?php isset($_SESSION['hak_akses']) ? isAlreadyLoggedIn($_SESSION['hak_akses']) : '' ?>

  <meta name="author" content="" />
  <meta name="Description" content="Halaman Login">
  <title>Login - <?= SITE_NAME ?></title>
</head>

<body class="bg-dark">
  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container-xl px-4">
          <div class="row justify-content-center">
            <div class="col-lg-5">
              <!-- Basic registration form-->
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header justify-content-center d-flex flex-column align-items-center">
                  <img class="img-fluid" src="<?= base_url('assets/img/optic-rosa.png') ?>" style="max-width: 10rem">
                  <h3 class="fw-light my-2">Buat Akun Customer</h3>
                </div>
                <div class="card-body">
                  <!-- Registration form-->
                  <form action="customer_daftar_simpan.php" method="post">
                    <!-- Form Row-->

                    <div class="mb-3">
                      <label class="small mb-1" for="xnama_customer">Nama Customer <span class="text-danger fw-bold">*</span></label>
                      <input class="form-control" id="xnama_customer" type="text" name="xnama_customer" placeholder="Enter nama customer" required>
                    </div>

                    <div class="mb-3">
                      <label class="small mb-1" for="xusername">Username <span class="text-danger fw-bold">*</span></label>
                      <input class="form-control" id="xusername" type="text" name="xusername" placeholder="Enter username" required>
                    </div>

                    <div class="mb-3">
                      <label class="small mb-1" for="xpassword">Password <span class="text-danger fw-bold">*</span></label>
                      <div class="input-group input-group-joined mb-1">
                        <input class="form-control mb-1" id="xpassword" type="password" name="xpassword" placeholder="Enter password" autocomplete="new-password" required>
                        <button class="input-group-text" id="xpassword_toggle" type="button"><i class="fa-regular fa-eye"></i></button>
                      </div>
                      <small class="text-muted" id="xpassword_help"></small>
                    </div>


                    <div class="row gx-3">

                      <div class="col-md-6">
                        <div class="form-check form-check-solid mb-3">
                          <input class="form-check-input" id="xjenis_kelamin_l" type="radio" name="xjenis_kelamin" value="l" checked required>
                          <label class="form-check-label" for="xjenis_kelamin_l">Laki-laki</label>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-check form-check-solid mb-3">
                          <input class="form-check-input" id="xjenis_kelamin_p" type="radio" name="xjenis_kelamin" value="p" required>
                          <label class="form-check-label" for="xjenis_kelamin_p">Perempuan</label>
                        </div>
                      </div>

                    </div>


                    <div class="mb-3">
                      <label class="small mb-1" for="xalamat">Alamat <span class="text-danger fw-bold">*</span></label>
                      <input class="form-control" id="xalamat" type="text" name="xalamat" placeholder="Enter alamat" required>
                    </div>


                    <div class="row gx-3">

                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="small mb-1" for="xtempat_lahir">Tempat Lahir</label>
                          <input class="form-control" id="xtempat_lahir" type="text" name="xtempat_lahir" placeholder="Enter tempat lahir" required>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="small mb-1" for="xtanggal_lahir">Tanggal Lahir</label>
                          <input class="form-control" id="xtanggal_lahir" type="date" name="xtanggal_lahir" required>
                        </div>
                      </div>

                    </div>


                    <div class="row gx-3">

                      <div class="col-md-6">
                        <button class="btn btn-light border w-100" type="button" onClick="window.location.assign('<?= base_url_return() ?>')">Batal</button>
                      </div>

                      <div class="col-md-6">
                        <button name="xsubmit" id="toggle_swal_submit" type="submit" class="btn btn-red w-100">Simpan</button>
                      </div>

                    </div>

                  </form>
                </div>
                <div class="card-footer text-center">
                  <div class="small"><a href="index.php">Sudah punya akun? klik untuk login.</a></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <div id="layoutAuthentication_footer">
      <footer class="footer-admin mt-auto footer-dark">
        <div class="container-xl px-4">
          <div class="row">
            <div class="col-md-6 small">Copyright &copy; <?= SITE_NAME_SHORT . ' ' . date('Y') ?></div>
            <div class="col-md-6 text-md-end small">
              <a href="#!">Privacy Policy</a>
              &middot;
              <a href="#!">Terms & Conditions</a>
            </div>
          </div>
        </div>
      </footer>
    </div>

  </div>

  <?php include_once 'login_script.php' ?>
  <?php include_once 'helpers/sweetalert2_notify.php' ?>

  <!-- PAGE SCRIPT -->
  <script>
    let password = document.getElementById('xpassword');
    let passwordToggle = document.getElementById('xpassword_toggle');
    let passwordHelp = document.getElementById('xpassword_help');

    passwordToggle.addEventListener('click', function() {
      initTogglePassword(password, passwordToggle);
    });
  </script>

</body>

</html>