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
    
    $username             = htmlspecialchars($purifier->purify($_POST['xusername']));
    $password             = password_hash($_POST['xpassword'], PASSWORD_DEFAULT);
    $is_allowed_hak_akses = in_array($_POST['xhak_akses'], ['admin']); 
    $hak_akses            = $is_allowed_hak_akses ? $_POST['xhak_akses'] : NULL;

    if (!$is_allowed_hak_akses) {
        $_SESSION['msg'] = 'Hak akses yang diinput tidak diperbolehkan!';
        echo "<meta http-equiv='refresh' content='0;pengguna.php?go=pengguna'>";
        return;
    }
        
    $stmt_pengguna = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt_pengguna, "INSERT INTO tbl_pengguna (username, password, hak_akses) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt_pengguna, 'sss', $username, $password, $hak_akses);

    $insert = mysqli_stmt_execute($stmt_pengguna);

    !$insert
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'save_success';

    mysqli_stmt_close($stmt_pengguna);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;pengguna.php?go=pengguna'>";
?>