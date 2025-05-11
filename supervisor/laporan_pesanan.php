<?php
include '../helpers/isAccessAllowedHelper.php';

// cek apakah user yang mengakses adalah supervisor?
if (!isAccessAllowed('supervisor')) :
  session_destroy();
  echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
else :
  include_once '../config/connection.php';

  $dari_tanggal = $_GET['dari_tanggal'] ?? null;
  $sampai_tanggal = $_GET['sampai_tanggal'] ?? null;

  if (!$dari_tanggal || !$sampai_tanggal) {
    echo 'Input dari dan sampai tanggal harus diisi!';
    return;
  }
?>


  <!DOCTYPE html>
  <html lang="en">

  <head>
    <?php include '_partials/head.php' ?>

    <meta name="description" content="Data Pesanan" />
    <meta name="author" content="" />
    <title>Laporan Pesanan <?= "({$dari_tanggal} s.d. {$sampai_tanggal})" ?></title>
  </head>

  <body class="bg-white">
    <?php
    $no = 1;

    $stmt_pesanan = mysqli_stmt_init($connection);
    $query_pesanan = 
      "SELECT
        a.id_pesanan, a.tanggal_pesanan, a.jumlah_pesanan, a.status_pesanan, a.created_at AS created_at_pesanan, a.updated_at AS updated_as_peasnan,
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
      WHERE a.created_at BETWEEN ? AND ?
      ORDER BY a.id_pesanan DESC";
      
    mysqli_stmt_prepare($stmt_pesanan, $query_pesanan);
    mysqli_stmt_bind_param($stmt_pesanan, 'ss', $dari_tanggal, $sampai_tanggal);
    mysqli_stmt_execute($stmt_pesanan);

    $result = mysqli_stmt_get_result($stmt_pesanan);
    $pesanans = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt_pesanan);
    mysqli_close($connection);
    ?>

    <h4 class="text-center mb-4">Laporan Pesanan <?= "({$dari_tanggal} s.d. {$sampai_tanggal})" ?></h4>

    <table class="table table-striped table-bordered table-sm">
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
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$result->num_rows): ?>

          <tr>
            <td colspan="10"><div class="text-center">Tidak ada data</div></td>
          </tr>
        
        <?php else: ?>

          <?php
          foreach($pesanans as $pesanan) :
            $harga_barang = 'Rp'.number_format($pesanan['harga_barang'], 0, ',', '.');
            $total_harga = $pesanan['jumlah_pesanan'] * $pesanan['harga_barang'];
            $total_harga = 'Rp'.number_format($total_harga);
            $status_pesanan = ucwords(str_replace('_', ' ', $pesanan['status_pesanan']));
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
              <td class="text-center"><?= $pesanan['jumlah_pesanan'] ?></td>
              <td class="text-center">
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
                <?php if ($status_pesanan === 'Belum Diproses'): ?>
                  <span class="text-danger"><?= $status_pesanan ?></span>
                <?php elseif ($status_pesanan === 'Diproses'): ?>
                  <span class="text-info"><?= $status_pesanan ?></span>
                <?php elseif ($status_pesanan === 'Sudah Diproses'): ?>
                  <span class="text-success"><?= $status_pesanan ?></span>
                <?php endif ?>
              </td>
            </tr>

          <?php endforeach ?>

        <?php endif ?>
      </tbody>
    </table>

  </body>

  </html>

<?php endif ?>