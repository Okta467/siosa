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

    <meta name="description" content="Data Pengguna" />
    <meta name="author" content="" />
    <title>Pengguna - <?= SITE_NAME ?></title>
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
                <h1 class="mb-0">Pengguna</h1>
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

            <div class="card card-icon mb-4">
              <div class="row g-0">
                <div class="col-auto card-icon-aside bg-primary"><i data-feather="alert-triangle" class="me-1 text-white-50"></i></div>
                <div class="col">
                  <div class="card-body py-5">
                    <h5 class="card-title">Catatan untuk akun Customer dan Supervisor</h5>
                    <p class="card-text">Penambahan akun Customer dan Supervisor hanya dapat dilakukan pada masing-masing halaman. Klik salah satu tombol di bawah ini untuk menuju ke halaman tersebut.</p>
            
                    <a class="btn btn-primary btn-sm me-2" href="customer.php?go=customer">
                      <i data-feather="external-link" class="me-1"></i>
                      Customer
                    </a>
                    <a class="btn btn-primary btn-sm" href="supervisor.php?go=supervisor">
                      <i data-feather="external-link" class="me-1"></i>
                      Supervisor
                    </a>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Main page content-->
            <div class="card card-header-actions mb-4 mt-5">
              <div class="card-header">
                <div>
                  <i data-feather="users" class="me-2 mt-1"></i>
                  Data Pengguna
                </div>
                <button class="btn btn-sm btn-primary toggle_modal_tambah" type="button"><i data-feather="user-plus" class="me-2"></i>Tambah Pengguna</button>
              </div>
              <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Pengguna</th>
                      <th>Username</th>
                      <th>Hak Akses</th>
                      <th>Tanggal Bergabung</th>
                      <th>Login Terakhir</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
  
                    <?php
                    $no = 1;
                    $query_pengguna = mysqli_query($connection,
                      "SELECT 
                        a.id_pengguna, a.username, a.hak_akses, a.created_at, a.last_login,
                        b.id_customer, b.nama_customer, b.jenis_kelamin AS jenis_kelamin_customer,
                        c.id_supervisor, c.nama_supervisor, c.jenis_kelamin AS jenis_kelamin_supervisor
                      FROM tbl_pengguna AS a
                      LEFT JOIN tbl_customer AS b
                        ON a.id_pengguna = b.id_pengguna
                      LEFT JOIN tbl_supervisor AS c
                        ON a.id_pengguna = c.id_pengguna
                      ORDER BY a.id_pengguna DESC");
  
                    while ($pengguna = mysqli_fetch_assoc($query_pengguna)) :

                      $formatted_hak_akses = ucwords(str_replace('_', ' ', $pengguna['hak_akses']));

                      $tanggal_bergabung = isset($pengguna['created_at'])
                        ? date('d M Y', strtotime($pengguna['created_at']))
                        : '<small class="text-muted">Tidak ada</small>';
                      
                      $last_login = isset($pengguna['last_login'])
                        ? date('d M Y H:i:s', strtotime($pengguna['last_login']))
                        : '<small class="text-muted">Tidak ada</small>';
                    ?>
  
                      <tr>
                        <td><?= $no++ ?></td>
                        <td>
                          <img src="<?= base_url('assets/img/illustrations/profiles/profile-' . rand(1, 6) . '.png') ?>" alt="Image User" class="avatar me-2">
                          <?= $pengguna['hak_akses'] === 'admin' ? 'Admin' : '' ?>
                          <?= $pengguna['hak_akses'] === 'customer' ? $pengguna['nama_customer'] : '' ?>
                          <?= $pengguna['hak_akses'] === 'supervisor' ? $pengguna['nama_supervisor'] : '' ?>
                        </td>
                        <td><?= $pengguna['username'] ?></td>
                        <td>
                            
                            <?php if ($pengguna['hak_akses'] === 'admin'): ?>
                              
                              <span class="badge bg-red-soft text-red"><?= $formatted_hak_akses ?></span>
                            
                            <?php elseif ($pengguna['hak_akses'] === 'customer'): ?>
                              
                              <span class="badge bg-info-soft text-info"><?= $formatted_hak_akses ?></span>
                              
                            <?php elseif ($pengguna['hak_akses'] === 'supervisor'): ?>
                              
                              <span class="badge bg-success-soft text-success"><?= $formatted_hak_akses ?></span>
  
                            <?php else: ?>
  
                              <small class="text-muted">Tidak ada</small>
                              
                            <?php endif ?>
                          
                        </td>
                        <td><?= $tanggal_bergabung ?></td>
                        <td><?= $last_login ?></td>
                        <td>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_modal_ubah"
                            data-id_pengguna="<?= $pengguna['id_pengguna'] ?>"
                            data-id_customer="<?= $pengguna['id_customer'] ?>"
                            data-id_supervisor="<?= $pengguna['id_supervisor'] ?>"
                            data-username="<?= $pengguna['username'] ?>"
                            data-hak_akses="<?= $pengguna['hak_akses'] ?>">
                            <i class="fa fa-pen-to-square"></i>
                          </button>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_swal_hapus"
                            data-id_pengguna="<?= $pengguna['id_pengguna'] ?>"
                            data-username="<?= $pengguna['username'] ?>"
                            data-nama_customer="<?= $pengguna['nama_customer'] ?>"
                            data-hak_akses="<?= $pengguna['hak_akses'] ?>">
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
    
    <!--============================= MODAL INPUT PENGGUNA =============================-->
    <div class="modal fade" id="ModalInputPengguna" tabindex="-1" role="dialog" aria-labelledby="ModalInputPenggunaTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalInputPenggunaTitle">Modal title</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form>
            <div class="modal-body">
              
              <input type="hidden" class="xid_pengguna" id="xid_pengguna" name="xid_pengguna">
              
              <div class="mb-3">
                <label class="small mb-1" for="xhak_akses">Hak Akses <span class="text-danger fw-bold">*</span></label>
                <select name="xhak_akses" class="form-control select2 xhak_akses" id="xhak_akses" required>
                  <option value="">-- Pilih --</option>
                </select>
              </div>
              
              <div class="mb-3 xid_customer">
                <label class="small mb-1" for="xid_customer">customer <span class="text-danger fw-bold">*</span></label>
                <select name="xid_customer" class="form-control select2 xid_customer" id="xid_customer" required></select>
                <small class="text-muted xid_customer_help"></small>
              </div>
              
              <div class="mb-3">
                <label class="small mb-1" for="xusername">Username <span class="text-danger fw-bold">*</span></label>
                <input class="form-control mb-1 xusername" id="xusername" type="username" name="xusername" placeholder="Enter username" required>
                <small class="text-muted">Hanya berupa huruf dan angka.</small>
              </div>
              
              <div class="mb-3">
                <label class="small mb-1" for="xpassword">Password <span class="text-danger fw-bold">*</span></label>
                <div class="input-group input-group-joined mb-1">
                  <input class="form-control xpassword" id="xpassword" type="password" name="xpassword" placeholder="Enter password" autocomplete="new-password" required>
                  <button class="input-group-text btn xpassword_toggle" id="xpassword_toggle" type="button"><i class="fa-regular fa-eye"></i></button>
                </div>
                <small class="text-danger xpassword_help">Pilih hak akses terlebih dahulu!</small>
                <small class="text-muted xpassword_help2">Kosongkan jika tidak ingin mengubah password.</small>
              </div>

            </div>
            <div class="modal-footer">
              <button class="btn btn-light border" type="button" data-bs-dismiss="modal">Batal</button>
              <button class="btn btn-primary" type="submit" id="toggle_swal_submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--/.modal-input-pengguna -->
    
    <?php include '_partials/script.php' ?>
    <?php include '../helpers/sweetalert2_notify.php' ?>
    
    <!-- PAGE SCRIPT -->
    <script>
      $(document).ready(function() {


        const toggleSelectRequiredAndDisplay = function(showcustomer = false) {

          if (!showcustomer) {
            // Hide and set required to false to select customer
            $('#ModalInputPengguna div.xid_customer').hide();
            $('#ModalInputPengguna select.xid_customer').prop('required', false);
          } else {
            // Hide and set required to false to select customer
            $('#ModalInputPengguna div.xid_customer').show();
            $('#ModalInputPengguna select.xid_customer').prop('required', true);
          }

        }


        const toggleUsernamePassword = function(disableUsername = true, disablePassword = true, usernameVal = null, passwordRequired = true) {
          if (!disableUsername) {
            $('#ModalInputPengguna .xusername_help').hide();
            $('#ModalInputPengguna .xusername').attr('disabled', false);
          } else {
            $('#ModalInputPengguna .xusername_help').show();
            $('#ModalInputPengguna .xusername').attr('disabled', true);
          }

          if (!disablePassword) {
            $('#ModalInputPengguna .xpassword_toggle').removeClass('btn disabled');
            $('#ModalInputPengguna .xpassword_help').hide();
            $('#ModalInputPengguna .xpassword').attr('disabled', false);
          } else {
            $('#ModalInputPengguna .xpassword_toggle').addClass('btn disabled');
            $('#ModalInputPengguna .xpassword_help').show();
            $('#ModalInputPengguna .xpassword').attr('disabled', true);
          }

          if (!passwordRequired) {
            $('#ModalInputPengguna .xpassword').prop('required', false);
          } else {
            $('#ModalInputPengguna .xpassword').prop('required', true);
          }

          if (usernameVal) {
            $('#ModalInputPengguna .xusername').val(usernameVal)
          }
        }


        let showcustomer = false;
        toggleSelectRequiredAndDisplay(showcustomer);
      
        
        // Define hak_akses function for change handler
        // so you can use this for `on` and `off` event
        const handleHakAksesChange = function(tipe_pengguna = 'with_no_user', id_customer = null) {
          return function(e) {
            const hak_akses = $('#xhak_akses').val();
          
            if (!hak_akses) {
              let showcustomer = false;
              toggleSelectRequiredAndDisplay(showcustomer);

              let disableUsername = true;
              let disablePassword = true;
              let usernameVal = '';
              toggleUsernamePassword(disableUsername, disablePassword, usernameVal);

            } else {
              
              let disableUsername = false;
              let disablePassword = false;
              let usernameVal = '';
              toggleUsernamePassword(disableUsername, disablePassword, usernameVal);
            }
          
            if (hak_akses.toLowerCase() === 'admin') {
              let showcustomer = false;
              toggleSelectRequiredAndDisplay(showcustomer);
              return;
            }
          
            
            if (hak_akses.toLowerCase() === 'supervisor') {
              let url_ajax = tipe_pengguna === 'with_no_user'
                ? 'get_supervisor_with_no_user.php'
                : 'get_supervisor.php';
            
              $.ajax({
                url: url_ajax,
                method: 'POST',
                dataType: 'JSON',
                data: {
                  'id_supervisor': id_supervisor
                },
                success: function(data) {
                  let showsupervisor = true;
                  toggleSelectRequiredAndDisplay(showsupervisor);
            
                  // Transform the data to the format that Select2 expects
                  const transformedData = data.map(item => ({
                    id: item.id_supervisor,
                    text: item.nama_supervisor
                  }));
                  
                  const supervisorSelect = $('select.xid_supervisor');
                  
                  supervisorSelect.html(null);
                  
                  initSelect2(supervisorSelect, {
                    data: transformedData,
                    width: '100%',
                    dropdownParent: ".modal-content .modal-body"
                  })

                  supervisorSelect.trigger('change');
                },
                error: function(request, status, error) {
                  // console.log("ajax call went wrong:" + request.responseText);
                  console.log("ajax call went wrong:" + error);
                }
              });
          
            }
          
            
            if (hak_akses.toLowerCase() === 'customer') {
              let url_ajax = tipe_pengguna === 'with_no_user'
                ? 'get_customer_with_no_user.php'
                : 'get_customer.php';
            
              $.ajax({
                url: url_ajax,
                method: 'POST',
                dataType: 'JSON',
                data: {
                  'id_customer': id_customer
                },
                success: function(data) {
                  let showcustomer = true;
                  toggleSelectRequiredAndDisplay(showcustomer);
            
                  // Transform the data to the format that Select2 expects
                  const transformedData = data.map(item => ({
                    id: item.id_customer,
                    text: item.nama_customer
                  }));
                  
                  const customerSelect = $('select.xid_customer');
                  
                  customerSelect.html(null);
                  
                  initSelect2(customerSelect, {
                    data: transformedData,
                    width: '100%',
                    dropdownParent: ".modal-content .modal-body"
                  })

                  customerSelect.trigger('change');
                },
                error: function(request, status, error) {
                  // console.log("ajax call went wrong:" + request.responseText);
                  console.log("ajax call went wrong:" + error);
                }
              });
          
            }
          }
        };
      
        
        $('.toggle_modal_tambah').on('click', function() {
          $('#ModalInputPengguna .modal-title').html(`<i data-feather="user-plus" class="me-2 mt-1"></i>Tambah Pengguna`);
          $('#ModalInputPengguna form').attr({action: 'pengguna_tambah.php', method: 'post'});

          $('#ModalInputPengguna .xid_customer_help').html('Nama customer yang muncul yaitu yang tidak memiliki user.');
          $('#ModalInputPengguna .xpassword').prop('required', true);
          $('#ModalInputPengguna .xpassword_help2').hide();
          $('#ModalInputPengguna select.xhak_akses').prop('disabled', false)
        
          // Detach (off) hak akses change event to avoid error and safely repopulate its select option
          const hakAksesSelect = $('#xhak_akses');
          hakAksesSelect.off('change');
          hakAksesSelect.empty();
          
          // Re-Initialize default select2 options (because in toggle_modal_ubah it's changed)
          const data = [
            {id: '', text: '-- Pilih --'},
            {id: 'admin', text: 'Admin'},
            // {id: 'supervisor', text: 'Supervisor'},
            // {id: 'customer', text: 'customer'},
          ];
          
          // Append options to the select element
          data.forEach(function(item) {
            const option = new Option(item.text, item.id, item.selected, item.selected);
            hakAksesSelect.append(option);
          });
  
          // Initialize Select2
          initSelect2(hakAksesSelect, {
            width: '100%',
            dropdownParent: ".modal-content .modal-body"
          });
          
          // $('#xhak_akses').on('change', handleHakAksesChange());
          
          // Re-init all feather icons
          feather.replace();
        
          $('#ModalInputPengguna').modal('show');
        });
      
        
        $('.toggle_modal_ubah').on('click', function() {
          const data = $(this).data();
        
          $('#ModalInputPengguna .modal-title').html(`<i data-feather="user-check" class="me-2 mt-1"></i>Ubah Pengguna`);
          $('#ModalInputPengguna form').attr({action: 'pengguna_ubah.php', method: 'post'});
          
          // Detach (off) the change handler for repopulating options
          $('#xhak_akses').off('change');
        
          const hakAksesSelect = $('#xhak_akses');
          hakAksesSelect.empty();
        
          if (data.hak_akses === 'admin') {
            data_hak_akses = [
              {id: 'admin', text: 'Admin', selected: true}
            ];
          }
          else if (data.hak_akses === 'supervisor') {
            data_hak_akses = [
              {id: 'supervisor', text: 'Supervisor'},
            ];
          }
          else if (data.hak_akses === 'customer') {
            data_hak_akses = [
              {id: 'customer', text: 'Customer'},
            ];
          }
          
          // Append options to the select element
          data_hak_akses.forEach(function(item) {
            const option = new Option(item.text, item.id, item.selected, item.selected);
            hakAksesSelect.append(option);
          });
          
          // Initialize Select2
          initSelect2(hakAksesSelect, {
            width: '100%',
            dropdownParent: "#ModalInputPengguna .modal-content .modal-body"
          });
        
          $('#ModalInputPengguna select.xhak_akses').val(data.hak_akses).trigger('change');
          $('#ModalInputPengguna .xid_customer_help').html('Nama customer hanya dapat diubah pada halaman customer.');
          $('#ModalInputPengguna .xid_pengguna').val(data.id_pengguna);

          let disableUsername  = false;
          let disablePassword  = false;
          let usernameVal      = data.username;
          let passwordRequired = false;
          toggleUsernamePassword(disableUsername, disablePassword, usernameVal, false);
          
          // $('#xhak_akses').on('change', handleHakAksesChange('with_user', data.id_customer));
          
          // Re-init all feather icons
          feather.replace();
        
          $('#ModalInputPengguna').modal('show');
        });
        

        $('#datatablesSimple').on('click', '.toggle_swal_hapus', function() {
          const data = $(this).data();
          const nama_pengguna = data.hak_akses === 'admin'
            ? data.username
            : `${data.nama_customer} (${data.username})`;
          
          Swal.fire({
            title: "Konfirmasi Tindakan?",
            html: `<div class="mb-1">Hapus data pengguna: </div><strong>${nama_pengguna}?</strong>`,
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
                window.location = `pengguna_hapus.php?xid_pengguna=${data.id_pengguna}`;
              });
            }
          });
        });
        

        const formSubmitBtn = $('#toggle_swal_submit');
        const eventName = 'click';
        toggleSwalSubmit(formSubmitBtn, eventName);
        
      });
    </script>

  </body>

  </html>

<?php endif ?>