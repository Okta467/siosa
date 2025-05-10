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

    <meta name="description" content="Data Supervisor" />
    <meta name="author" content="" />
    <title>Supervisor - <?= SITE_NAME ?></title>
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
                <h1 class="mb-0">Supervisor</h1>
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
                  Data Supervisor
                </div>
                <button class="btn btn-sm btn-primary toggle_modal_tambah" type="button"><i data-feather="user-plus" class="me-2"></i>Tambah Supervisor</button>
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
                    $query_supervisor = mysqli_query($connection, 
                      "SELECT
                        a.id_supervisor, a.nama_supervisor, a.jenis_kelamin, a.alamat, a.tempat_lahir, a.tanggal_lahir,
                        f.id_pengguna, f.username, f.hak_akses
                      FROM tbl_supervisor AS a
                      LEFT JOIN tbl_pengguna AS f
                        ON a.id_pengguna = f.id_pengguna
                      WHERE f.hak_akses = 'supervisor'
                      ORDER BY a.id_supervisor DESC");

                    while ($supervisor = mysqli_fetch_assoc($query_supervisor)):
                      $formatted_hak_akses = ucwords(str_replace('_', ' ', $supervisor['hak_akses']));
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-sm btn-outline-blue dropdown-toggle" id="dropdownFadeIn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
                            <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownFadeIn">

                              <button class="dropdown-item toggle_modal_ubah" type="button"
                                data-id_supervisor="<?= $supervisor['id_supervisor'] ?>">
                                <i data-feather="edit" class="me-1"></i>
                                Ubah
                              </button>

                              <button class="dropdown-item text-danger toggle_swal_hapus" type="button"
                                data-id_supervisor="<?= $supervisor['id_supervisor'] ?>"
                                data-nama_supervisor="<?= $supervisor['nama_supervisor'] ?>">
                                <i data-feather="trash-2" class="me-1"></i>
                                Hapus
                              </button>

                              <div class="dropdown-divider"></div>
                              
                              <button class="dropdown-item toggle_modal_detail_supervisor" type="button"
                                data-id_supervisor="<?= $supervisor['id_supervisor'] ?>"
                                data-nama_supervisor="<?= $supervisor['nama_supervisor'] ?>"
                                data-username="<?= $supervisor['username'] ?>"
                                data-hak_akses="<?= $supervisor['hak_akses'] ? ucfirst(str_replace('_', ' ', $supervisor['hak_akses'], )) : null ?>"
                                data-alamat="<?= $supervisor['alamat'] ?>"
                                data-tempat_lahir="<?= $supervisor['tempat_lahir'] ?>"
                                data-tanggal_lahir="<?= $supervisor['tanggal_lahir'] ?>">
                                <i data-feather="list" class="me-1"></i>
                                Detail
                              </button>

                            </div>
                          </div>
                        </td>
                        <td>
                          <?= htmlspecialchars($supervisor['nama_supervisor']); ?>
                        </td>
                        <td>
                          <?= htmlspecialchars($supervisor['username']); ?>
                        </td>
                        <td>
                          <?= $supervisor['jenis_kelamin'] === 'l' ? 'Laki-Laki' : 'Perempuan' ?>
                        </td>
                        <td>
                          <div class="ellipsis toggle_tooltip" title="<?= $supervisor['alamat'] ?>">
                            <?= $supervisor['alamat'] ?>
                          </div>
                        </td>
                        <td>
                          <?= $supervisor['tempat_lahir'] . ', ' . $supervisor['tanggal_lahir'] ?>
                        </td>
                        <td>
                            
                          <?php if ($supervisor['hak_akses'] === 'supervisor'): ?>
                            
                            <span class="badge bg-info-soft text-info"><?= $formatted_hak_akses ?></span>
                            
                          <?php elseif ($supervisor['hak_akses'] === 'supervisor'): ?>
                            
                            <span class="badge bg-info-soft text-info"><?= $formatted_hak_akses ?></span>

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
    
    <!--============================= MODAL DETAIL SUPERVISOR =============================-->
    <div class="modal fade" id="ModalDetailSupervisor" tabindex="-1" role="dialog" aria-labelledby="ModalDetailSupervisor" aria-hidden="true">
      <div class="modal-dialog" role="document" style="max-width: 600px;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i data-feather="compass" class="me-2"></i>Detail Supervisor</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            
            <div class="p-4">
              <h4><i data-feather="star" class="me-2"></i>Supervisor</h4>
              <p class="mb-0 xnama_supervisor"></p>
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
              <p class="mb-0 xtempat_tanggal_lahir"></p>
            </div>
          
          </div>
          <div class="modal-footer">
            <button class="btn btn-light border" type="button" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <!--/.modal-detail-supervisor -->
      
    <!--============================= MODAL INPUT SUPERVISOR =============================-->
    <div class="modal fade" id="ModalInputSupervisor" tabindex="-1" role="dialog" aria-labelledby="ModalInputSupervisorTitle" aria-hidden="true" data-focus="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalInputSupervisorTitle">Modal title</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form>
            <div class="modal-body">
    
              <input type="hidden" name="xid_supervisor" id="xid_supervisor" required>
              <input type="hidden" name="xid_pengguna" id="xid_pengguna" required>
    
              <div class="mb-3">
                <label class="small mb-1" for="xnama_supervisor">Nama Supervisor <span class="text-danger fw-bold">*</span></label>
                <input class="form-control" id="xnama_supervisor" type="text" name="xnama_supervisor" placeholder="Enter nama supervisor" required>
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
    <!--/.modal-input-supervisor -->
    
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
        
        const selectModalInputSupervisor = $('#ModalInputSupervisor .select2');
        initSelect2(selectModalInputSupervisor, {
          width: '100%',
          dropdownParent: "#ModalInputSupervisor .modal-content .modal-body"
        });

        
        $('.toggle_modal_tambah').on('click', function() {
          $('#ModalInputSupervisor .modal-title').html(`<i data-feather="plus-circle" class="me-2 mt-1"></i>Tambah Supervisor`);
          $('#ModalInputSupervisor form').attr({action: 'supervisor_tambah.php', method: 'post'});
          $('#ModalInputSupervisor #xpassword').attr('required', true);
          $('#ModalInputSupervisor #xpassword_help').html('');

          // Re-init all feather icons
          feather.replace();
          
          $('#ModalInputSupervisor').modal('show');
        });

        
        $('.toggle_modal_ubah').on('click', function() {
          const id_supervisor   = $(this).data('id_supervisor');
          const nama_supervisor = $(this).data('nama_supervisor');
          
          $('#ModalInputSupervisor .modal-title').html(`<i data-feather="edit" class="me-2 mt-1"></i>Ubah Supervisor`);
          $('#ModalInputSupervisor form').attr({action: 'supervisor_ubah.php', method: 'post'});
          $('#ModalInputSupervisor #xpassword').attr('required', false);
          $('#ModalInputSupervisor #xpassword_help').html('Kosongkan jika tidak ingin ubah password.');
        
          $.ajax({
            url: 'get_supervisor.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
              'id_supervisor': id_supervisor
            },
            success: function(data) {
              $('#ModalInputSupervisor #xid_supervisor').val(data[0].id_supervisor);
              $('#ModalInputSupervisor #xid_pengguna').val(data[0].id_pengguna);
              $('#ModalInputSupervisor #xnama_supervisor').val(data[0].nama_supervisor);
              $('#ModalInputSupervisor #xusername').val(data[0].username);
              $('#ModalInputSupervisor #xhak_akses').val(data[0].hak_akses).trigger('change');
              $(`#ModalInputSupervisor input[name="xjenis_kelamin"][value="${data[0].jenis_kelamin}"]`).prop('checked', true)
              $('#ModalInputSupervisor #xalamat').val(data[0].alamat);
              $('#ModalInputSupervisor #xtempat_lahir').val(data[0].tempat_lahir);
              $('#ModalInputSupervisor #xtanggal_lahir').val(data[0].tanggal_lahir);
              
              // Re-init all feather icons
              feather.replace();
              
              $('#ModalInputSupervisor').modal('show');
            },
            error: function(request, status, error) {
              // console.log("ajax call went wrong:" + request.responseText);
              console.log("ajax call went wrong:" + error);
            }
          });
        });

        
        $('.toggle_modal_detail_supervisor').on('click', function() {
          const data = $(this).data();
        
          $('#ModalDetailSupervisor .xnama_supervisor').html(data.nama_supervisor);
          $('#ModalDetailSupervisor .xusername').html(data.username || 'Tidak ada (akun belum dibuat)');
          $('#ModalDetailSupervisor .xhak_akses').html(data.hak_akses || 'Tidak ada (akun belum dibuat)');
          $('#ModalDetailSupervisor .xalamat').html(data.alamat);
          $('#ModalDetailSupervisor .xtempat_tanggal_lahir').html(`${data.tempat_lahir}, ${moment(data.tanggal_lahir).format("DD MMMM YYYY")}`);
        
          $('#ModalDetailSupervisor').modal('show');
        });
        

        $('#datatablesSimple').on('click', '.toggle_swal_hapus', function() {
          console.log('aa')
          const id_supervisor   = $(this).data('id_supervisor');
          const id_pengguna  = $(this).data('id_pengguna');
          const nama_supervisor = $(this).data('nama_supervisor');
          
          Swal.fire({
            title: "Konfirmasi Tindakan?",
            html: `Hapus data supervisor: <strong>${nama_supervisor}?</strong>`,
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
                window.location = `supervisor_hapus.php?xid_supervisor=${id_supervisor}&xid_pengguna=${id_pengguna}`;
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