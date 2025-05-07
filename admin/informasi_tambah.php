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
    
    $judul_informasi = htmlspecialchars($purifier->purify($_POST['xjudul_informasi']));
    $isi_informasi   = str_replace('`', '\`', $purifier->purify($_POST['xisi_informasi']));

    $stmt = mysqli_stmt_init($connection);
    $query = 
        "INSERT INTO tbl_Informasi 
        (
            judul_informasi
            , isi_informasi
        )
        VALUES (?, ?)";

    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $judul_informasi, $isi_informasi);

    $insert = mysqli_stmt_execute($stmt);

    !$insert
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'save_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;Informasi.php?go=Informasi'>";
?>