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

    <meta name="description" content="Data Kunjungan" />
    <meta name="author" content="" />
    <title>Kunjungan - <?= SITE_NAME ?></title>
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
                <h1 class="mb-0">Kunjungan</h1>
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
                  <i data-feather="coffee" class="me-2 mt-1"></i>
                  Data Kunjungan
                </div>
                <button class="btn btn-sm btn-primary toggle_modal_tambah" type="button"><i data-feather="plus-circle" class="me-2"></i>Tambah Kunjungan</button>
              </div>
              <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Aksi</th>
                      <th>Nama</th>
                      <th>JK</th>
                      <th>Alamat</th>
                      <th>Berkunjung?</th>
                      <th>Tanggal Berkunjung</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    $no = 1;
                    $id_customer = $_SESSION['id_customer'];
                    $nama_customer = $_SESSION['nama_customer'];
                    $query_customer = mysqli_query($connection, 
                      "SELECT
                        a.id_kunjungan, a.tanggal_kunjungan, a.is_visited,
                        b.id_customer, b.nama_customer, b.jenis_kelamin, b.alamat, b.tempat_lahir, b.tanggal_lahir,
                        f.id_pengguna, f.username, f.hak_akses
                      FROM tbl_kunjungan AS a
                      LEFT JOIN tbl_customer AS b
                        ON a.id_customer = b.id_customer
                      LEFT JOIN tbl_pengguna AS f
                        ON b.id_pengguna = f.id_pengguna
                      WHERE b.id_customer = {$id_customer}
                      ORDER BY a.id_kunjungan DESC");

                    while ($kunjungan = mysqli_fetch_assoc($query_customer)):
                      $formatted_hak_akses = ucwords(str_replace('_', ' ', $kunjungan['hak_akses']));
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-sm btn-outline-blue dropdown-toggle" id="dropdownFadeIn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
                            <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownFadeIn">

                              <button class="dropdown-item toggle_modal_ubah" type="button"
                                data-id_kunjungan="<?= $kunjungan['id_kunjungan'] ?>">
                                <i data-feather="edit" class="me-1"></i>
                                Ubah
                              </button>

                              <button class="dropdown-item text-danger toggle_swal_hapus" type="button"
                                data-id_kunjungan="<?= $kunjungan['id_kunjungan'] ?>"
                                data-nama_customer="<?= $kunjungan['nama_customer'] ?>">
                                <i data-feather="trash-2" class="me-1"></i>
                                Hapus
                              </button>

                              <div class="dropdown-divider"></div>
                              
                              <button class="dropdown-item toggle_modal_detail_customer" type="button"
                                data-id_customer="<?= $kunjungan['id_customer'] ?>"
                                data-nama_customer="<?= $kunjungan['nama_customer'] ?>"
                                data-username="<?= $kunjungan['username'] ?>"
                                data-hak_akses="<?= $kunjungan['hak_akses'] ? ucfirst(str_replace('_', ' ', $kunjungan['hak_akses'], )) : null ?>"
                                data-alamat="<?= $kunjungan['alamat'] ?>"
                                data-tempat_lahir="<?= $kunjungan['tempat_lahir'] ?>"
                                data-tanggal_lahir="<?= $kunjungan['tanggal_lahir'] ?>">
                                <i data-feather="list" class="me-1"></i>
                                Detail
                              </button>

                            </div>
                          </div>
                        </td>
                        <td>
                          <?= htmlspecialchars($kunjungan['nama_customer']); ?>
                        </td>
                        <td>
                          <?= $kunjungan['jenis_kelamin'] === 'l' ? 'Laki-Laki' : 'Perempuan' ?>
                        </td>
                        <td>
                          <div class="ellipsis toggle_tooltip" title="<?= $kunjungan['alamat'] ?>">
                            <?= $kunjungan['alamat'] ?>
                          </div>
                        </td>
                        <td>
                            
                          <?php if ($kunjungan['is_visited']): ?>
                            
                            <span class="badge bg-info-soft text-info">Berkunjung</span>

                          <?php else: ?>
                            
                            <span class="badge bg-red-soft text-red">Tidak Berkunjung</span>
                            
                          <?php endif ?>
                          
                        </td>
                        <td>
                          <?= $kunjungan['tanggal_kunjungan'] ?>
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
            <h5 class="modal-title"><i data-feather="compass" class="me-2"></i>Detail Customer</h5>
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
    <!--/.modal-detail-kunjungan -->
      
    <!--============================= MODAL INPUT KUNJUNGAN =============================-->
    <div class="modal fade" id="ModalInputKunjungan" tabindex="-1" role="dialog" aria-labelledby="ModalInputKunjunganTitle" aria-hidden="true" data-focus="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalInputKunjunganTitle">Modal title</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form>
            <div class="modal-body">
    
              <input type="hidden" name="xid_kunjungan" id="xid_kunjungan" required>
              
              <div class="mb-3">
                <label class="small mb-1" for="xid_customer">Nama Customer</label>
                <select name="xid_customer" class="form-control select2" id="xid_customer" required>
                  <option value="<?= $id_customer ?>"><?= $nama_customer ?></option>
                </select>
              </div>
    
              
              <div class="row gx-3">
          
                <div class="col-md-6">
                  <div class="form-check form-check-solid mb-3">
                    <input class="form-check-input" id="xis_visited_ya" type="radio" name="xis_visited" value="1" required>
                    <label class="form-check-label" for="xis_visited_ya">Berkunjung</label>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-check form-check-solid mb-3">
                    <input class="form-check-input" id="xis_visited_belum" type="radio" name="xis_visited" value="0" checked required>
                    <label class="form-check-label" for="xis_visited_belum">Belum Berkunjung</label>
                  </div>
                </div>
          
              </div>
    
    
              <div class="row gx-3">
                
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="small mb-1" for="xtanggal_kunjungan">Tanggal Kunjungan</label>
                    <input class="form-control" id="xtanggal_kunjungan" type="date" name="xtanggal_kunjungan" required>
                  </div>
                </div>
    
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="small mb-1" for="xjam_kunjungan">Jam Kunjungan</label>
                    <input class="form-control" id="xjam_kunjungan" type="time" step="1" name="xjam_kunjungan" required>
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
    <!--/.modal-input-kunjungan -->
    
    <?php include '_partials/script.php' ?>
    <?php include '../helpers/sweetalert2_notify.php' ?>

    <!-- PAGE SCRIPT -->
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
        
        const selectModalInputKunjungan = $('#ModalInputKunjungan .select2');
        initSelect2(selectModalInputKunjungan, {
          width: '100%',
          dropdownParent: "#ModalInputKunjungan .modal-content .modal-body"
        });

        
        $('.toggle_modal_tambah').on('click', function() {
          $('#ModalInputKunjungan .modal-title').html(`<i data-feather="coffee" class="me-2 mt-1"></i>Tambah Kunjungan`);
          $('#ModalInputKunjungan form').attr({action: 'kunjungan_tambah.php', method: 'post'});
          $('#ModalInputKunjungan #xpassword').attr('required', true);
          $('#ModalInputKunjungan #xpassword_help').html('');

          // Re-init all feather icons
          feather.replace();
          
          $('#ModalInputKunjungan').modal('show');
        });

        
        $('.toggle_modal_ubah').on('click', function() {
          const id_kunjungan = $(this).data('id_kunjungan');
          const id_customer = $(this).data('id_customer');
          
          $('#ModalInputKunjungan .modal-title').html(`<i data-feather="edit" class="me-2 mt-1"></i>Ubah Kunjungan`);
          $('#ModalInputKunjungan form').attr({action: 'kunjungan_ubah.php', method: 'post'});
        
          $.ajax({
            url: 'get_kunjungan.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
              'id_kunjungan': id_kunjungan
            },
            success: function(data) {
              const tanggal_kunjungan_obj = new Date(data[0].tanggal_kunjungan);
              const tanggal_kunjungan = tanggal_kunjungan_obj.toISOString().split('T')[0];
              const jam_kunjungan = tanggal_kunjungan_obj.toTimeString().split(' ')[0];
              
              $('#ModalInputKunjungan #xid_kunjungan').val(data[0].id_kunjungan);
              $('#ModalInputKunjungan #xid_customer').val(data[0].id_customer).trigger('change');
              $(`#ModalInputKunjungan input[name="xis_visited"][value="${data[0].is_visited}"]`).prop('checked', true);
              $('#ModalInputKunjungan #xtanggal_kunjungan').val(tanggal_kunjungan);
              $('#ModalInputKunjungan #xjam_kunjungan').val(jam_kunjungan);
              
              // Re-init all feather icons
              feather.replace();
              
              $('#ModalInputKunjungan').modal('show');
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
          const id_kunjungan = $(this).data('id_kunjungan');
          const nama_customer = $(this).data('nama_customer');
          
          Swal.fire({
            title: "Konfirmasi Tindakan?",
            html: `Hapus data kunjungan: <strong>${nama_customer}?</strong>`,
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
                window.location = `kunjungan_hapus.php?xid_kunjungan=${id_kunjungan}`;
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