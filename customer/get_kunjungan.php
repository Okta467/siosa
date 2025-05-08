<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah customer?
    if (!isAccessAllowed('customer')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';
    
    $id_kunjungan = $_POST['id_kunjungan'];

    $stmt = mysqli_stmt_init($connection);
    $query = "SELECT * FROM tbl_kunjungan WHERE id_kunjungan=?";

    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_kunjungan);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $kunjungans = !$result
        ? array()
        : mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo json_encode($kunjungans);

?>