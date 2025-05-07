<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah customer?
    if (!isAccessAllowed('customer')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';
    
    $id_barang = $_POST['id_barang'];

    $stmt = mysqli_stmt_init($connection);
    $query = 
      "SELECT
          a.id, a.kode_barang, a.nama_barang, a.satuan,
          IFNULL(b.total_masuk, 0) - IFNULL(c.total_keluar, 0) AS stok
        FROM tbl_barang AS a
        LEFT JOIN
        (
          SELECT id, SUM(jumlah) AS total_masuk, id_barang
          FROM tbl_barang_masuk
          GROUP BY id_barang
        ) AS b
          ON a.id = b.id_barang
        LEFT JOIN
        (
          SELECT id, SUM(jumlah) AS total_keluar, id_barang
          FROM tbl_barang_keluar
          GROUP BY id_barang
        ) AS c
          ON a.id = c.id_barang
        WHERE a.id=?
        GROUP BY a.id";

    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_barang);
    mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

    $barangs = !$result
        ? array()
        : mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo json_encode($barangs);

?>