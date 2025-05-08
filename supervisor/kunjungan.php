<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah supervisor?
if (!isAccessAllowed('supervisor')) :
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
            
            <!-- Tools Cetak Pengumuman -->
            <div class="card mb-4 mt-5">
              <div class="card-header">
                <div>
                  <i data-feather="settings" class="me-2 mt-1"></i>
                  Tools Cetak Laporan
                </div>
              </div>
              <div class="card-body">
                <div class="row gx-3">
                  <div class="col-md-2 mb-3">
                    <label class="small mb-1" for="xdari_tanggal">Dari Tanggal</label>
                    <input class="form-control" id="xdari_tanggal" type="date" name="xdari_tanggal" required>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label class="small mb-1" for="xsampai_tanggal">Sampai Tanggal</label>
                    <input class="form-control" id="xsampai_tanggal" type="date" name="xsampai_tanggal" required>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label class="small mb-1 invisible" for="xcetak_laporan">Filter Button</label>
                    <button class="btn btn-primary w-100" id="xcetak_laporan" type="button">
                      <i data-feather="printer" class="me-1"></i>
                      Cetak
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Main page content-->
            <div class="card card-header-actions mb-4 mt-5">
              <div class="card-header">
                <div>
                  <i data-feather="coffee" class="me-2 mt-1"></i>
                  Data Kunjungan
                </div>
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
                      WHERE f.hak_akses = 'customer'
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
                            
                            <span class="badge bg-blue-soft text-blue">Berkunjung</span>

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
    <!--/.modal-detail-kunjungan -->
    
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

        
        $('#xcetak_laporan').on('click', function() {
          const dari_tanggal = $('#xdari_tanggal').val();
          const sampai_tanggal = $('#xsampai_tanggal').val();
          
          const url = `laporan_kunjungan.php?dari_tanggal=${dari_tanggal}&sampai_tanggal=${sampai_tanggal}`;
          
          printExternal(url);
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
        
      });
    </script>

  </body>

  </html>

<?php endif ?>