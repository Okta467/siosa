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
    
    $nama_supervisor = htmlspecialchars($purifier->purify($_POST['xnama_supervisor']));
    $username        = htmlspecialchars($purifier->purify($_POST['xusername']));
    $password        = $_POST['xpassword'] ? password_hash($_POST['xpassword'], PASSWORD_DEFAULT) : null;
    $jenis_kelamin   = $_POST['xjenis_kelamin'];
    $hak_akses       = 'supervisor';
    $alamat          = htmlspecialchars($purifier->purify($_POST['xalamat']));
    $tempat_lahir    = htmlspecialchars($purifier->purify($_POST['xtempat_lahir']));
    $tanggal_lahir   = $_POST['xtanggal_lahir'];

    mysqli_autocommit($connection, false);

    $success = true;

    try {
        // Pengguna statement preparation and execution
        $stmt_pengguna  = mysqli_stmt_init($connection);
        $query_pengguna = "INSERT INTO tbl_pengguna (username, password, hak_akses) VALUES (?, ?, ?)";
        
        if (!mysqli_stmt_prepare($stmt_pengguna, $query_pengguna)) {
            $_SESSION['msg'] = 'Statement Pengguna preparation failed: ' . mysqli_stmt_error($stmt_pengguna);
            echo "<meta http-equiv='refresh' content='0;supervisor.php?go=supervisor'>";
            return;
        }
        
        mysqli_stmt_bind_param($stmt_pengguna, 'sss', $username, $password, $hak_akses);
        
        if (!mysqli_stmt_execute($stmt_pengguna)) {
            $_SESSION['msg'] = 'Statement Pengguna preparation failed: ' . mysqli_stmt_error($stmt_pengguna);
            echo "<meta http-equiv='refresh' content='0;supervisor.php?go=supervisor'>";
            return;
        }

        // Supervisor statement preparation and execution
        $stmt_supervisor  = mysqli_stmt_init($connection);
        $query_supervisor = "INSERT INTO tbl_supervisor 
        (
            id_pengguna
            , nama_supervisor
            , jenis_kelamin
            , alamat
            , tempat_lahir
            , tanggal_lahir
        ) 
        VALUES (?, ?, ?, ?, ?, ?)";
        
        if (!mysqli_stmt_prepare($stmt_supervisor, $query_supervisor)) {
            $_SESSION['msg'] = 'Statement Supervisor preparation failed: ' . mysqli_stmt_error($stmt_supervisor);
            echo "<meta http-equiv='refresh' content='0;supervisor.php?go=supervisor'>";
            return;
        }
        
        $id_pengguna = mysqli_insert_id($connection) !== 0 ? mysqli_insert_id($connection) : NULL;
        mysqli_stmt_bind_param($stmt_supervisor, 'isssss', $id_pengguna, $nama_supervisor, $jenis_kelamin, $alamat, $tempat_lahir, $tanggal_lahir);
        
        if (!mysqli_stmt_execute($stmt_supervisor)) {
            $_SESSION['msg'] = 'Statement Supervisor preparation failed: ' . mysqli_stmt_error($stmt_supervisor);
            echo "<meta http-equiv='refresh' content='0;supervisor.php?go=supervisor'>";
            return;
        }

        // Commit the transaction if all statements succeed
        if (!mysqli_commit($connection)) {
            $_SESSION['msg'] = 'Transaction commit failed: ' . mysqli_stmt_error($stmt_supervisor);
            echo "<meta http-equiv='refresh' content='0;supervisor.php?go=supervisor'>";
            return;
        }

    } catch (Exception $e) {
        // Roll back the transaction if any statement fails
        $success = false;
        mysqli_rollback($connection);
    }

    mysqli_stmt_close($stmt_supervisor);

    mysqli_autocommit($connection, true);
    mysqli_close($connection);

    !$success
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'save_success';

    echo "<meta http-equiv='refresh' content='0;supervisor.php?go=supervisor'>";
?>
