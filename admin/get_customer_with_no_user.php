<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah admin?
    if (!isAccessAllowed('admin')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';
    
    $stmt1 = mysqli_stmt_init($connection);
    $query = 
        "SELECT
            a.id_customer, a.nama_customer, a.jenis_kelamin, a.alamat, a.tempat_lahir, a.tanggal_lahir,
            f.username
        FROM tbl_customer AS a
        LEFT JOIN tbl_pengguna AS f
            ON a.id_pengguna = f.id_pengguna
        WHERE f.id_pengguna IS NULL";

    mysqli_stmt_prepare($stmt1, $query);
    mysqli_stmt_execute($stmt1);

	$result = mysqli_stmt_get_result($stmt1);

    $customers = !$result
        ? array()
        : mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt1);
    mysqli_close($connection);

    echo json_encode($customers);

?>