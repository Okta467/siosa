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

    <meta name="description" content="Data Customer" />
    <meta name="author" content="" />
    <title>Customer - <?= SITE_NAME ?></title>
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
                <h1 class="mb-0">Customer</h1>
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
                  <i data-feather="user" class="me-2 mt-1"></i>
                  Data Customer
                </div>
                <button class="btn btn-sm btn-primary toggle_modal_tambah" type="button"><i data-feather="user-plus" class="me-2"></i>Tambah Customer</button>
              </div>
              <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Aksi</th>
                      <th>Nama</th>
                      <th>Username</th>
                      <th>JK</th>
                      <th>Alamat</th>
                      <th>TTL</th>
                      <th>Hak Akses</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    $no = 1;
                    $query_customer = mysqli_query($connection, 
                      "SELECT
                        a.id_customer, a.nama_customer, a.jenis_kelamin, a.alamat, a.tempat_lahir, a.tanggal_lahir,
                        f.id_pengguna, f.username, f.hak_akses
                      FROM tbl_customer AS a
                      LEFT JOIN tbl_pengguna AS f
                        ON a.id_pengguna = f.id_pengguna
                      WHERE f.hak_akses = 'customer'
                      ORDER BY a.id_customer DESC");

                    while ($customer = mysqli_fetch_assoc($query_customer)):
                      $formatted_hak_akses = ucwords(str_replace('_', ' ', $customer['hak_akses']));
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-sm btn-outline-blue dropdown-toggle" id="dropdownFadeIn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
                            <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownFadeIn">

                              <button class="dropdown-item toggle_modal_ubah" type="button"
                                data-id_customer="<?= $customer['id_customer'] ?>">
                                <i data-feather="edit" class="me-1"></i>
                                Ubah
                              </button>

                              <button class="dropdown-item text-danger toggle_swal_hapus" type="button"
                                data-id_customer="<?= $customer['id_customer'] ?>"
                                data-nama_customer="<?= $customer['nama_customer'] ?>">
                                <i data-feather="trash-2" class="me-1"></i>
                                Hapus
                              </button>

                              <div class="dropdown-divider"></div>
                              
                              <button class="dropdown-item toggle_modal_detail_customer" type="button"
                                data-id_customer="<?= $customer['id_customer'] ?>"
                                data-nama_customer="<?= $customer['nama_customer'] ?>"
                                data-username="<?= $customer['username'] ?>"
                                data-hak_akses="<?= $customer['hak_akses'] ? ucfirst(str_replace('_', ' ', $customer['hak_akses'], )) : null ?>"
                                data-alamat="<?= $customer['alamat'] ?>"
                                data-tempat_lahir="<?= $customer['tempat_lahir'] ?>"
                                data-tanggal_lahir="<?= $customer['tanggal_lahir'] ?>">
                                <i data-feather="list" class="me-1"></i>
                                Detail
                              </button>

                            </div>
                          </div>
                        </td>
                        <td>
                          <?= htmlspecialchars($customer['nama_customer']); ?>
                        </td>
                        <td>
                          <?= htmlspecialchars($customer['username']); ?>
                        </td>
                        <td>
                          <?= $customer['jenis_kelamin'] === 'l' ? 'Laki-Laki' : 'Perempuan' ?>
                        </td>
                        <td>
                          <div class="ellipsis toggle_tooltip" title="<?= $customer['alamat'] ?>">
                            <?= $customer['alamat'] ?>
                          </div>
                        </td>
                        <td>
                          <?= $customer['tempat_lahir'] . ', ' . $customer['tanggal_lahir'] ?>
                        </td>
                        <td>
                            
                          <?php if ($customer['hak_akses'] === 'supervisor'): ?>
                            
                            <span class="badge bg-blue-soft text-blue"><?= $formatted_hak_akses ?></span>
                            
                          <?php elseif ($customer['hak_akses'] === 'customer'): ?>
                            
                            <span class="badge bg-purple-soft text-purple"><?= $formatted_hak_akses ?></span>

                          <?php else: ?>

                            <small class="text-muted">Tidak ada</small>
                            
                          <?php endif ?>
                          
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
    
    <!--============================= MODAL DETAIL CUSTOMER =============================-->
    <div class="modal fade" id="ModalDetailCustomer" tabindex="-1" role="dialog" aria-labelledby="ModalDetailCustomer" aria-hidden="true">
      <div class="modal-dialog" role="document" style="max-width: 600px;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i data-feather="info" class="me-2"></i>Detail Customer</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            
            <div class="p-4">
              <h4><i data-feather="star" class="me-2"></i>Customer</h4>
              <p class="mb-0 xnama_customer"></p>
            </div>
            
            <div class="p-4">
              <h4><i data-feather="key" class="me-2"></i>Username</h4>
              <p class="mb-0 xusername"></p>
            </div>
            
            <div class="p-4">
              <h4><i data-feather="key" class="me-2"></i>Hak Akses</h4>
              <p class="mb-0 xhak_akses"></p>
            </div>
            
            <div class="p-4">
              <h4><i data-feather="home" class="me-2"></i>Alamat</h4>
              <p class="mb-0 xalamat"></p>
            </div>
            
            <div class="p-4">
              <h4><i data-feather="gift" class="me-2"></i>Tempat, Tanggal Lahir</h4>
              <p class="mb-0 xtmp_tanggal_lahir"></p>
            </div>
          
          </div>
          <div class="modal-footer">
            <button class="btn btn-light border" type="button" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <!--/.modal-detail-customer -->
      
    <!--============================= MODAL INPUT CUSTOMER =============================-->
    <div class="modal fade" id="ModalInputCustomer" tabindex="-1" role="dialog" aria-labelledby="ModalInputCustomerTitle" aria-hidden="true" data-focus="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalInputCustomerTitle">Modal title</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form>
            <div class="modal-body">
    
              <input type="hidden" name="xid_customer" id="xid_customer" required>
              <input type="hidden" name="xid_pengguna" id="xid_pengguna" required>
    
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
    
            </div>
            <div class="modal-footer">
              <button class="btn btn-light border" type="button" data-bs-dismiss="modal">Batal</button>
              <button class="btn btn-primary" type="submit" id="toggle_swal_submit">Simpan</button>
            </div>
          </form>
        </div>
      </di>
    </div>
    <!--/.modal-input-customer -->
    
    <?php include '_partials/script.php' ?>
    <?php include '../helpers/sweetalert2_notify.php' ?>

    <!-- PAGE SCRIPT -->
    <script>
      let password = document.getElementById('xpassword');
      let passwordToggle = document.getElementById('xpassword_toggle');
      let passwordHelp = document.getElementById('xpassword_help');
      
      passwordToggle.addEventListener('click', function() {
        initTogglePassword(password, passwordToggle);
      });
    </script>

    <script>
      $(document).ready(function() {
        // Datatables initialise
        $('.datatables').DataTable({
          responsive: true,
          pageLength: 5,
          lengthMenu: [
            [3, 5, 10, 25, 50, 100],
            [3, 5, 10, 25, 50, 100],
          ]
        });
        
        const selectModalInputCustomer = $('#ModalInputCustomer .select2');
        initSelect2(selectModalInputCustomer, {
          width: '100%',
          dropdownParent: "#ModalInputCustomer .modal-content .modal-body"
        });

        
        $('.toggle_modal_tambah').on('click', function() {
          $('#ModalInputCustomer .modal-title').html(`<i data-feather="plus-circle" class="me-2 mt-1"></i>Tambah Customer`);
          $('#ModalInputCustomer form').attr({action: 'customer_tambah.php', method: 'post'});
          $('#ModalInputCustomer #xpassword').attr('required', true);
          $('#ModalInputCustomer #xpassword_help').html('');

          // Re-init all feather icons
          feather.replace();
          
          $('#ModalInputCustomer').modal('show');
        });

        
        $('.toggle_modal_ubah').on('click', function() {
          const id_customer   = $(this).data('id_customer');
          const nama_customer = $(this).data('nama_customer');
          
          $('#ModalInputCustomer .modal-title').html(`<i data-feather="edit" class="me-2 mt-1"></i>Ubah Customer`);
          $('#ModalInputCustomer form').attr({action: 'customer_ubah.php', method: 'post'});
          $('#ModalInputCustomer #xpassword').attr('required', false);
          $('#ModalInputCustomer #xpassword_help').html('Kosongkan jika tidak ingin ubah password.');
        
          $.ajax({
            url: 'get_customer.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
              'id_customer': id_customer
            },
            success: function(data) {
              $('#ModalInputCustomer #xid_customer').val(data[0].id_customer);
              $('#ModalInputCustomer #xid_pengguna').val(data[0].id_pengguna);
              $('#ModalInputCustomer #xnama_customer').val(data[0].nama_customer);
              $('#ModalInputCustomer #xusername').val(data[0].username);
              $('#ModalInputCustomer #xhak_akses').val(data[0].hak_akses).trigger('change');
              $(`#ModalInputCustomer input[name="xjenis_kelamin"][value="${data[0].jenis_kelamin}"]`).prop('checked', true)
              $('#ModalInputCustomer #xalamat').val(data[0].alamat);
              $('#ModalInputCustomer #xtempat_lahir').val(data[0].tempat_lahir);
              $('#ModalInputCustomer #xtanggal_lahir').val(data[0].tanggal_lahir);
              
              // Re-init all feather icons
              feather.replace();
              
              $('#ModalInputCustomer').modal('show');
            },
            error: function(request, status, error) {
              // console.log("ajax call went wrong:" + request.responseText);
              console.log("ajax call went wrong:" + error);
            }
          });
        });

        
        $('.toggle_modal_detail_customer').on('click', function() {
          const data = $(this).data();
        
          $('#ModalDetailCustomer .xnama_customer').html(data.nama_customer);
          $('#ModalDetailCustomer .xusername').html(data.username || 'Tidak ada (akun belum dibuat)');
          $('#ModalDetailCustomer .xhak_akses').html(data.hak_akses || 'Tidak ada (akun belum dibuat)');
          $('#ModalDetailCustomer .xalamat').html(data.alamat);
          $('#ModalDetailCustomer .xtmp_tanggal_lahir').html(`${data.tempat_lahir}, ${moment(data.tanggal_lahir).format("DD MMMM YYYY")}`);
        
          $('#ModalDetailCustomer').modal('show');
        });
        

        $('#datatablesSimple').on('click', '.toggle_swal_hapus', function() {
          console.log('aa')
          const id_customer   = $(this).data('id_customer');
          const id_pengguna  = $(this).data('id_pengguna');
          const nama_customer = $(this).data('nama_customer');
          
          Swal.fire({
            title: "Konfirmasi Tindakan?",
            html: `Hapus data customer: <strong>${nama_customer}?</strong>`,
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
                window.location = `customer_hapus.php?xid_customer=${id_customer}&xid_pengguna=${id_pengguna}`;
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