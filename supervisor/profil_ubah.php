<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah supervisor?
    if (!isAccessAllowed('supervisor')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    require_once '../vendor/htmlpurifier/HTMLPurifier.auto.php';
    include_once '../config/connection.php';

    // to sanitize user input
    $config   = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    
    $id_supervisor = $_SESSION['id_supervisor'];
    $alamat        = htmlspecialchars($purifier->purify($_POST['xalamat']));
    $tempat_lahir  = htmlspecialchars($purifier->purify($_POST['xtempat_lahir']));
    $tanggal_lahir = $_POST['xtanggal_lahir'];

    $stmt = mysqli_stmt_init($connection);
    $query = "UPDATE tbl_supervisor SET
        alamat = ?
        , tempat_lahir = ?
        , tanggal_lahir = ?
    WHERE id_supervisor = ?";

    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'sssi', $alamat, $tempat_lahir, $tanggal_lahir, $id_supervisor);
    mysqli_stmt_execute($stmt);

    $update = mysqli_stmt_execute($stmt);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;profil.php?go=profil'>";
?>
