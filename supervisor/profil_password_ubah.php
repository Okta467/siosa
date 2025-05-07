<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah supervisor?
    if (!isAccessAllowed('supervisor')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    include_once '../config/connection.php';

    $id_pengguna          = $_SESSION['id_pengguna'];
    $password_saat_ini    = $_POST['xpassword_saat_ini'];
    $password_baru        = $_POST['xpassword_baru'];
    $password_konfirmasi  = $_POST['xpassword_konfirmasi'];
    $password_baru_hashed = password_hash($_POST['xpassword_baru'], PASSWORD_DEFAULT);

    if ($password_baru !== $password_konfirmasi) {
        $_SESSION['msg'] = 'Password baru dan konfirmasi tidak sama!';
        echo "<meta http-equiv='refresh' content='0;profil_password.php?go=profil'>";
        return;
    }

    $stmt_pengguna = mysqli_stmt_init($connection);
    
    mysqli_stmt_prepare($stmt_pengguna, "SELECT password FROM tbl_pengguna WHERE id_pengguna=?");
    mysqli_stmt_bind_param($stmt_pengguna, 'i', $id_pengguna);
    mysqli_stmt_execute($stmt_pengguna);
    
    $result = mysqli_stmt_get_result($stmt_pengguna);
    $pengguna = mysqli_fetch_assoc($result);

    if (!$pengguna) {
        $_SESSION['msg'] = 'other_error';
        echo "<meta http-equiv='refresh' content='0;profil_password.php?go=profil'>";
        return;
    }
    
	if (!password_verify($password_saat_ini, $pengguna['password'])) {
        $_SESSION['msg'] = 'wrong_password';
        echo "<meta http-equiv='refresh' content='0;profil_password.php?go=profil'>";
        return;
    }
    
    $stmt_update = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt_update, "UPDATE tbl_pengguna SET password=? WHERE id_pengguna=?");
    mysqli_stmt_bind_param($stmt_update, 'si', $password_baru_hashed, $id_pengguna);

    $update = mysqli_stmt_execute($stmt_update);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt_pengguna);
    mysqli_stmt_close($stmt_update);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;profil_password.php?go=profil'>";
?>