<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah admin?
    if (!isAccessAllowed('admin')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    require_once '../vendor/htmlpurifier/HTMLPurifier.auto.php';
    include_once '../config/connection.php';

    // to sanitize user input
    $config   = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    
    $id_barang       = $_POST['xid_barang'];
    $id_customer     = $_POST['xid_customer'];
    $tanggal_pesanan = $_POST['xtanggal_pesanan'];
    $jumlah_pesanan  = $_POST['xjumlah_pesanan'];
    
    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, "INSERT INTO tbl_pesanan (id_barang, id_customer, tanggal_pesanan, jumlah_pesanan) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'iisi', $id_barang, $id_customer, $tanggal_pesanan, $jumlah_pesanan);

    $insert = mysqli_stmt_execute($stmt);

    !$insert
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'save_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;pesanan.php?go=pesanan'>";
?>