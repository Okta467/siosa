<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah admin?
    if (!isAccessAllowed('admin')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';
    
    $id_barang = $_GET['xid_barang'];

    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, "DELETE FROM tbl_barang WHERE id_barang=?");
    mysqli_stmt_bind_param($stmt, 'i', $id_barang);

    $delete = mysqli_stmt_execute($stmt);

    !$delete
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'delete_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;barang.php?go=barang'>";
?>