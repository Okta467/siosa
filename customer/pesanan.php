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

    <meta name="description" content="Data Pesanan" />
    <meta name="author" content="" />
    <title>Pesanan - <?= SITE_NAME ?></title>
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
                <h1 class="mb-0">Pesanan</h1>
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
                  <i data-feather="shopping-cart" class="me-2 mt-1"></i>
                  Data Pesanan
                </div>
                <button class="btn btn-sm btn-primary toggle_modal_tambah" type="button"><i data-feather="plus-circle" class="me-2"></i>Tambah Data</button>
              </div>
              <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Kode</th>
                      <th>Customer</th>
                      <th>Nama Barang</th>
                      <th>Jumlah</th>
                      <th>Satuan</th>
                      <th>Harga</th>
                      <th>Total</th>
                      <th>Tanggal Pesanan</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $id_customer = $_SESSION['id_customer'];
                    $nama_customer = $_SESSION['nama_customer'];
                    $query_pesanan = mysqli_query($connection,
                      "SELECT
                        a.id_pesanan, a.tanggal_pesanan, a.jumlah_pesanan, a.created_at AS created_at_pesanan, a.updated_at AS updated_as_peasnan,
                        b.id_barang, b.kode_barang, b.nama_barang, b.satuan_barang, b.harga_barang, b.created_at AS created_at_barang, b.updated_at AS updated_at_barang,
                        c.id_customer, c.nama_customer, c.jenis_kelamin, c.alamat, c.tempat_lahir, c.tanggal_lahir, c.created_at AS created_at_customer, c.updated_at AS updated_at_customer,
                        d.id_pengguna, d.username, d.hak_akses, d.created_at AS created_at_pengguna, d.last_login
                      FROM tbl_pesanan AS a
                      LEFT JOIN tbl_barang AS b
                        ON a.id_barang = b.id_barang
                      LEFT JOIN tbl_customer AS c
                        ON a.id_customer = c.id_customer
                      LEFT JOIN tbl_pengguna AS d
                        ON c.id_pengguna = d.id_pengguna
                      WHERE c.id_customer={$id_customer}
                      ORDER BY a.id_pesanan DESC"
                    );

                    while ($pesanan = mysqli_fetch_assoc($query_pesanan)):
                      $harga_barang = 'Rp'.number_format($pesanan['harga_barang'], 0, ',', '.');
                      $total_harga = $pesanan['jumlah_pesanan'] * $pesanan['harga_barang'];
                      $total_harga = 'Rp'.number_format($total_harga);
                    ?>

                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $pesanan['id_pesanan'] ?></td>
                        <td>
                          <div class="ellipsis toggle_tooltip" title="<?= $pesanan['nama_customer'] ?>">
                            <?= htmlspecialchars($pesanan['nama_customer']); ?>
                          </div>
                        </td>
                        <td><?= $pesanan['nama_barang'] ?></td>
                        <td><?= $pesanan['jumlah_pesanan'] ?></td>
                        <td>
                          <?php if ($pesanan['satuan_barang'] === 'dus'): ?>
                            <span class="text-danger"><?= $pesanan['satuan_barang'] ?></span>
                          <?php elseif ($pesanan['satuan_barang'] === 'box'): ?>
                            <span class="text-primary"><?= $pesanan['satuan_barang'] ?></span>
                          <?php elseif ($pesanan['satuan_barang'] === 'pcs'): ?>
                            <span class="text-success"><?= $pesanan['satuan_barang'] ?></span>
                          <?php endif ?>
                        </td>
                        <td><?= $harga_barang ?></td>
                        <td><?= $total_harga ?></td>
                        <td><?= $pesanan['tanggal_pesanan'] ?></td>
                        <td>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_modal_ubah"
                            data-id_pesanan="<?= $pesanan['id_pesanan'] ?>"
                            data-id_customer="<?= $pesanan['id_customer'] ?>"
                            data-id_barang="<?= $pesanan['id_barang'] ?>"
                            data-jumlah_pesanan="<?= $pesanan['jumlah_pesanan'] ?>"
                            data-tanggal_pesanan="<?= $pesanan['tanggal_pesanan'] ?>">
                            <i class="fa fa-pen-to-square"></i>
                          </button>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark me-2 toggle_swal_hapus"
                            data-id_pesanan="<?= $pesanan['id_pesanan'] ?>"
                            data-nama_barang="<?= htmlspecialchars($pesanan['nama_barang']) ?>">
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

    <!--============================= MODAL INPUT PESANAN =============================-->
    <div class="modal fade" id="ModalInputPesanan" tabindex="-1" role="dialog" aria-labelledby="ModalInputPesananTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalInputPesananTitle">Modal title</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form>
            <div class="modal-body">

              <input type="hidden" id="xid_pesanan" name="xid_pesanan">
    
              
              <div class="row gx-3">
          
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="small mb-1" for="xid_customer">Customer</label>
                    <select name="xid_customer" class="form-control select2" id="xid_customer" readonly>
                      <option value="<?= $id_customer ?>"><?= $nama_customer ?></option>
                    </select>
                  </div>
                </div>
          
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="small mb-1" for="xtanggal_pesanan">Tanggal Pesanan</label>
                    <input class="form-control" id="xtanggal_pesanan" type="date" name="xtanggal_pesanan" required>
                  </div>
                </div>
          
              </div>


              <div class="mb-3">
                <label class="small mb-1" for="xid_barang">Nama Barang</label>
                <select name="xid_barang" class="form-control select2" id="xid_barang" required>
                  <option value="">-- Pilih --</option>
                  <?php $query_barang = mysqli_query($connection, "SELECT * FROM tbl_barang ORDER BY nama_barang ASC") ?>
                  <?php while ($barang = mysqli_fetch_assoc($query_barang)) : ?>

                    <option value="<?= $barang['id_barang'] ?>"><?= "({$barang['satuan_barang']}) {$barang['nama_barang']}" ?></option>

                  <?php endwhile ?>
                </select>
              </div>
              
              <label class="small mb-1" for="xharga_barang">Harga Barang</label>
              <div class="mb-3 input-group input-group-joined">
                <span class="input-group-text">
                  Rp
                </span>
                <input type="text" name="xharga_barang"  class="form-control ps-0" id="xharga_barang" placeholder="Enter harga barang" readonly />
              </div>
              
              <div class="mb-3">
                <label class="small mb-1" for="xjumlah_pesanan">Jumlah Pesanan</label>
                <input type="number" name="xjumlah_pesanan" min="0" class="form-control" id="xjumlah_pesanan" placeholder="Enter jumlah pesanan" required />
              </div>
              
              <label class="small mb-1" for="xtotal_harga">Total Harga</label>
              <div class="mb-3 input-group input-group-joined">
                <span class="input-group-text">
                  Rp
                </span>
                <input type="text" name="xtotal_harga"  class="form-control ps-0" id="xtotal_harga" placeholder="Enter harga barang" readonly />
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
    <!--/.modal-input-pesanan -->

    <?php include '_partials/script.php' ?>
    <?php include '../helpers/sweetalert2_notify.php' ?>

    <!-- PAGE SCRIPT -->
    <script>
      $(document).ready(function() {
        // var input_harga_barang = $('#xharga_barang');
        
        // input_harga_barang.on('keyup', function(e) {
        //   input_harga_barang.val(formatCurrency(this.value));
        // });


        var input_harga_barang = $('#ModalInputPesanan #xharga_barang');
        var input_jumlah_pesanan = $('#ModalInputPesanan #xjumlah_pesanan');
        var input_total_harga = $('#ModalInputPesanan #xtotal_harga');
        
        input_jumlah_pesanan.on('keyup change', function(e) {
          const harga_barang = input_harga_barang.val();
          const jumlah_pesanan = input_jumlah_pesanan.val();
          const total_harga = harga_barang * jumlah_pesanan;
          
          input_total_harga.val(total_harga);
        });

        
        $('#xid_barang').on('change', function() {
          const id_barang = $(this).val();
          const jumlah_pesanan = input_jumlah_pesanan.val();
        
          $.ajax({
            url: 'get_barang.php',
            method: 'POST',
            dataType: 'JSON',
            data: {
              'id_barang': id_barang
            },
            success: function(data) {
              const harga_barang = data[0].harga_barang;

              $('#ModalInputPesanan #xharga_barang').val(harga_barang);

              if (jumlah_pesanan > 0) {
                const total_harga = harga_barang * jumlah_pesanan;
                $('#ModalInputPesanan #xtotal_harga').val(total_harga);
              }
            },
            error: function(request, status, error) {
              // console.log("ajax call went wrong:" + request.responseText);
              console.log("ajax call went wrong:" + error);
            }
          });
        });
        
        
        $('.toggle_modal_tambah').on('click', function() {
          $('#ModalInputPesanan .modal-title').html(`<i data-feather="plus-circle" class="me-2 mt-1"></i>Tambah Pesanan`);
          $('#ModalInputPesanan form').attr({
            action: 'pesanan_tambah.php',
            method: 'post'
          });

          // Re-init all feather icons
          feather.replace();

          $('#ModalInputPesanan').modal('show');
        });


        $('.toggle_modal_ubah').on('click', function() {
          const data = $(this).data();

          $('#ModalInputPesanan .modal-title').html(`<i data-feather="edit" class="me-2 mt-1"></i>Ubah Pesanan`);
          $('#ModalInputPesanan form').attr({
            action: 'pesanan_ubah.php',
            method: 'post'
          });

          $('#ModalInputPesanan #xid_pesanan').val(data.id_pesanan);
          $('#ModalInputPesanan #xid_customer').val(data.id_customer).trigger('change');
          $('#ModalInputPesanan #xid_barang').val(data.id_barang).trigger('change');
          $('#ModalInputPesanan #xjumlah_pesanan').val(data.jumlah_pesanan);
          $('#ModalInputPesanan #xtanggal_pesanan').val(data.tanggal_pesanan);

          // To triggern event auto-fill total harga input field above
          $('#xid_barang').trigger('change');

          // Re-init all feather icons
          feather.replace();

          $('#ModalInputPesanan').modal('show');
        });


        $('#datatablesSimple').on('click', '.toggle_swal_hapus', function() {
          const id_pesanan = $(this).data('id_pesanan');
          const nama_barang = $(this).data('nama_barang');
          const warning_html =
            `Hapus data pesanan: <strong>${nama_barang}?</strong>`;
          const warning_html_ver2 =
            `Hapus data pesanan: <strong>${nama_barang}?</strong>
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
                window.location = `pesanan_hapus.php?xid_pesanan=${id_pesanan}`;
              });
            }
          });
        });

      });
    </script>

  </body>

  </html>

<?php endif ?>