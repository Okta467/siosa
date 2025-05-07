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
    
    $id_barang = $_POST['xid_barang'];
    $kode_barang = htmlspecialchars($purifier->purify($_POST['xkode_barang']));
    $nama_barang = htmlspecialchars($purifier->purify($_POST['xnama_barang']));
    $satuan_barang = $_POST['xsatuan_barang'];
    $harga_barang_with_dot = htmlspecialchars($purifier->purify($_POST['xharga_barang']));
    $harga_barang = str_replace('.', '', $harga_barang_with_dot);
    $is_allowed_satuan = $satuan_barang && in_array($satuan_barang, ['pcs', 'box', 'dus']);

    if (!$is_allowed_satuan) {
        $_SESSION['msg'] = 'Satuan barang yang dipilih tidak diperbolehkan!';
        echo "<meta http-equiv='refresh' content='0;barang.php?go=barang'>";
        return;
    }

    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, "UPDATE tbl_barang SET kode_barang=?, nama_barang=?, satuan_barang=?, harga_barang=? WHERE id_barang=?");
    mysqli_stmt_bind_param($stmt, 'sssii', $kode_barang, $nama_barang, $satuan_barang, $harga_barang, $id_barang);

    $update = mysqli_stmt_execute($stmt);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;barang.php?go=barang'>";
?>