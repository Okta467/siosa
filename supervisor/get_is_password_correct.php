<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah supervisor?
    if (!isAccessAllowed('supervisor')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';
    
    $id_pengguna = $_POST['id_pengguna'];
    $password = $_POST['password'];
    
    $stmt = mysqli_stmt_init($connection);
    $query = "SELECT password FROM tbl_pengguna WHERE id=?";

    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_pengguna);
    mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
    $pengguna = mysqli_fetch_assoc($result);
    $is_password_correct = password_verify($password, $pengguna['password']);

    echo json_encode($is_password_correct);

?>