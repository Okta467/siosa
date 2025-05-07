<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah customer?
    if (!isAccessAllowed('customer')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    require_once '../vendor/htmlpurifier/HTMLPurifier.auto.php';
    include_once '../config/connection.php';

    // to sanitize user input
    $config   = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    
    $id_pesanan            = $_POST['xid_pesanan'];
    $id_barang             = $_POST['xid_barang'];
    $id_customer_logged_in = $_SESSION['id_customer'];
    $tanggal_pesanan       = $_POST['xtanggal_pesanan'];
    $jumlah_pesanan        = $_POST['xjumlah_pesanan'];
    
    $stmt_current_pesanan = mysqli_stmt_init($connection);
    $query_current_pesanan = "SELECT * FROM tbl_pesanan WHERE id_pesanan=?";

    mysqli_stmt_prepare($stmt_current_pesanan, $query_current_pesanan);
    mysqli_stmt_bind_param($stmt_current_pesanan, 'i', $id_pesanan);
    mysqli_stmt_execute($stmt_current_pesanan);

    $result_current_pesanan = mysqli_stmt_get_result($stmt_current_pesanan);
    $pesanan = mysqli_fetch_assoc($result_current_pesanan);
    $id_customer_in_current_pesanan = $pesanan['id_customer'];

    if ($id_customer_logged_in != $id_customer_in_current_pesanan) {
        $_SESSION['msg'] = 'ID Customer pesanan tidak sama dengan yang login saat ini!';
        echo "<meta http-equiv='refresh' content='0;pesanan.php?go=pesanan'>";
        return;
    }
    
    $stmt_pesanan = mysqli_stmt_init($connection);
    $query_current_pesanan = "UPDATE tbl_pesanan SET
        id_barang = ?
        , tanggal_pesanan = ?
        , jumlah_pesanan = ?
    WHERE id_pesanan =?";

    mysqli_stmt_prepare($stmt_pesanan, $query_current_pesanan);
    mysqli_stmt_bind_param($stmt_pesanan, 'isii', $id_barang, $tanggal_pesanan, $jumlah_pesanan, $id_pesanan);

    $update = mysqli_stmt_execute($stmt_pesanan);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt_current_pesanan);
    mysqli_stmt_close($stmt_pesanan);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;pesanan.php?go=pesanan'>";
?>