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

    <meta name="description" content="Data Kunjungan" />
    <meta name="author" content="" />
    <title>Laporan Kunjungan <?= "({$dari_tanggal} s.d. {$sampai_tanggal})" ?></title>
  </head>

  <body class="bg-white">
    <?php
    $no = 1;

    $stmt_kunjungan = mysqli_stmt_init($connection);
    $query_kunjungan = 
    "SELECT
        a.id_kunjungan, a.tanggal_kunjungan, a.is_visited, a.created_at, a.updated_at,
        b.id_customer, b.nama_customer, b.jenis_kelamin, b.alamat, b.tempat_lahir, b.tanggal_lahir,
        f.id_pengguna, f.username, f.hak_akses
      FROM tbl_kunjungan AS a
      LEFT JOIN tbl_customer AS b
        ON a.id_customer = b.id_customer
      LEFT JOIN tbl_pengguna AS f
        ON b.id_pengguna = f.id_pengguna
      WHERE
        f.hak_akses = 'customer'
        AND (a.created_at BETWEEN ? AND ?)
      ORDER BY a.id_kunjungan DESC";
      
    mysqli_stmt_prepare($stmt_kunjungan, $query_kunjungan);
    mysqli_stmt_bind_param($stmt_kunjungan, 'ss', $dari_tanggal, $sampai_tanggal);
    mysqli_stmt_execute($stmt_kunjungan);

    $result = mysqli_stmt_get_result($stmt_kunjungan);
    $kunjungans = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt_kunjungan);
    mysqli_close($connection);
    ?>

    <h4 class="text-center mb-4">Laporan Kunjungan <?= "({$dari_tanggal} s.d. {$sampai_tanggal})" ?></h4>

    <table class="table table-striped table-bordered table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>JK</th>
          <th>Alamat</th>
          <th>Berkunjung?</th>
          <th>Tanggal Berkunjung</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$result->num_rows): ?>

          <tr>
            <td colspan="10"><div class="text-center">Tidak ada data</div></td>
          </tr>
        
        <?php else: ?>

          <?php foreach($kunjungans as $kunjungan) : ?>
            
            <tr>
              <td><?= $no++ ?></td>
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

          <?php endforeach ?>

        <?php endif ?>
      </tbody>
    </table>

  </body>

  </html>

<?php endif ?>