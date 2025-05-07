<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah customer?
    if (!isAccessAllowed('customer')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';
    
    $id_pesanan = $_GET['xid_pesanan'];
    $id_customer_logged_in = $_SESSION['id_customer'];
    
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

    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, "DELETE FROM tbl_pesanan WHERE id_pesanan=?");
    mysqli_stmt_bind_param($stmt, 'i', $id_pesanan);

    $delete = mysqli_stmt_execute($stmt);

    !$delete
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'delete_success';

    mysqli_stmt_close($stmt_current_pesanan);
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;pesanan.php?go=pesanan'>";
?>