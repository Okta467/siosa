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

    <meta name="description" content="Data Barang" />
    <meta name="author" content="" />
    <title>Barang - <?= SITE_NAME ?></title>
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
                <h1 class="mb-0">Barang</h1>
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
                  <i data-feather="grid" class="me-2 mt-1"></i>
                  Data Barang
                </div>
                <button class="btn btn-sm btn-primary toggle_modal_tambah" type="button"><i data-feather="plus-circle" class="me-2"></i>Tambah Data</button>
              </div>
              <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Kode barang</th>
                      <th>Nama Barang</th>
                      <th>Satuan</th>
                      <th>Harga</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $query_barang = mysqli_query(
                      $connection,
                      "SELECT id_barang, kode_barang, nama_barang, satuan_barang, harga_barang
                      FROM tbl_barang
                      ORDER BY id_barang DESC"
                    );

                    while ($barang = mysqli_fetch_assoc($query_barang)):
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($barang['kode_barang']) ?></td>
                        <td><?= htmlspecialchars($barang['nama_barang']) ?></td>
                        <td>
                          <?php if ($barang['satuan_barang'] === 'dus'): ?>
                            <span class="text-danger"><?= $barang['satuan_barang'] ?></span>
                          <?php elseif ($barang['satuan_barang'] === 'box'): ?>
                            <span class="text-primary"><?= $barang['satuan_barang'] ?></span>
                          <?php elseif ($barang['satuan_barang'] === 'pcs'): ?>
                            <span class="text-success"><?= $barang['satuan_barang'] ?></span>
                          <?php endif ?>
                        </td>
                        <td><?= 'Rp' . number_format($barang['harga_barang'], 0, ',', '.') ?></td>
                        <td>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_modal_ubah"
                            data-id_barang="<?= $barang['id_barang'] ?>"
                            data-kode_barang="<?= htmlspecialchars($barang['kode_barang']) ?>"
                            data-nama_barang="<?= htmlspecialchars($barang['nama_barang']) ?>"
                            data-satuan_barang="<?= $barang['satuan_barang'] ?>"
                            data-harga_barang="<?= $barang['harga_barang'] ?>">
                            <i class="fa fa-pen-to-square"></i>
                          </button>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_swal_hapus"
                            data-id_barang="<?= $barang['id_barang'] ?>"
                            data-nama_barang="<?= htmlspecialchars($barang['nama_barang']) ?>">
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

    <!--============================= MODAL INPUT BARANG =============================-->
    <div class="modal fade" id="ModalInputBarang" tabindex="-1" role="dialog" aria-labelledby="ModalInputBarangTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalInputBarangTitle">Modal title</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form>
            <div class="modal-body">

              <input type="hidden" id="xid_barang" name="xid_barang">

              <div class="mb-3">
                <label class="small mb-1" for="xkode_barang">Kode Barang</label>
                <input type="text" name="xkode_barang" maxlength="10" class="form-control" id="xkode_barang" placeholder="Enter kode barang" required />
                <small class="text-xs text-danger">Hanya diperbolehkan maks 10 digit!</small>
              </div>

              <div class="mb-3">
                <label class="small mb-1" for="xnama_barang">Nama Barang</label>
                <input type="text" name="xnama_barang" maxlength="128" class="form-control" id="xnama_barang" placeholder="Enter nama barang" required />
              </div>

              <div class="mb-3">
                <label class="small mb-1" for="xsatuan_barang">Satuan</label>
                <select name="xsatuan_barang" class="form-control select2" id="xsatuan_barang" required>
                  <option value="">-- Pilih --</option>
                  <option value="pcs">pcs</option>
                  <option value="box">box</option>
                  <option value="dus">dus</option>
                </select>
              </div>

              <label class="small mb-1" for="xharga_barang">Harga Barang</label>
              <div class="mb-3 input-group input-group-joined">
                <span class="input-group-text">
                  Rp
                </span>
                <input type="text" name="xharga_barang"  class="form-control ps-0" id="xharga_barang" placeholder="Enter harga barang" required />
              </div>

            </div>
            <div class="modal-footer">
              <button class="btn btn-light border" type="button" data-bs-dismiss="modal">Batal</button>
              <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--/.modal-input-barang -->

    <?php include '_partials/script.php' ?>
    <?php include '../helpers/sweetalert2_notify.php' ?>

    <!-- PAGE SCRIPT -->
    <script>
      $(document).ready(function() {
        var input_harga_barang = $('#xharga_barang');
        
        input_harga_barang.on('keyup', function(e) {
          input_harga_barang.val(formatCurrency(this.value));
        });

        
        $('.toggle_modal_tambah').on('click', function() {
          $('#ModalInputBarang .modal-title').html(`<i data-feather="plus-circle" class="me-2 mt-1"></i>Tambah Barang`);
          $('#ModalInputBarang form').attr({
            action: 'barang_tambah.php',
            method: 'post'
          });

          // Re-init all feather icons
          feather.replace();

          $('#ModalInputBarang').modal('show');
        });


        $('.toggle_modal_ubah').on('click', function() {
          const data = $(this).data();

          $('#ModalInputBarang .modal-title').html(`<i data-feather="edit" class="me-2 mt-1"></i>Ubah Barang`);
          $('#ModalInputBarang form').attr({
            action: 'barang_ubah.php',
            method: 'post'
          });

          $('#ModalInputBarang #xid_barang').val(data.id_barang);
          $('#ModalInputBarang #xkode_barang').val(data.kode_barang);
          $('#ModalInputBarang #xnama_barang').val(data.nama_barang);
          $('#ModalInputBarang #xsatuan_barang').val(data.satuan_barang).trigger('change');
          $('#ModalInputBarang #xharga_barang').val(formatCurrency(data.harga_barang));

          // Re-init all feather icons
          feather.replace();

          $('#ModalInputBarang').modal('show');
        });


        $('#datatablesSimple').on('click', '.toggle_swal_hapus', function() {
          const id_barang = $(this).data('id_barang');
          const nama_barang = $(this).data('nama_barang');
          const warning_html =
            `Hapus data barang: <strong>${nama_barang}?</strong>
            <div class="text-danger small mt-4">Data yang terhubung (pesanan)</div>
            <div class="text-danger small mt-1">juga akan dihapus!</div>`;

          Swal.fire({
            title: "Konfirmasi Tindakan?",
            html: warning_html,
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
                window.location = `barang_hapus.php?xid_barang=${id_barang}`;
              });
            }
          });
        });

      });
    </script>

  </body>

  </html>

<?php endif ?>