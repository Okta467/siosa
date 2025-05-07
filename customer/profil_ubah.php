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
    
    $id_customer   = $_SESSION['id_customer'];
    $alamat        = htmlspecialchars($purifier->purify($_POST['xalamat']));
    $tempat_lahir  = htmlspecialchars($purifier->purify($_POST['xtempat_lahir']));
    $tanggal_lahir = $_POST['xtanggal_lahir'];

    $stmt = mysqli_stmt_init($connection);
    $query = "UPDATE tbl_customer SET
        alamat = ?
        , tempat_lahir = ?
        , tanggal_lahir = ?
    WHERE id_customer = ?";

    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'sssi', $alamat, $tempat_lahir, $tanggal_lahir, $id_customer);
    mysqli_stmt_execute($stmt);

    $update = mysqli_stmt_execute($stmt);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;profil.php?go=profil'>";
?>
