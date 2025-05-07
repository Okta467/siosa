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
    
    $id_customer   = $_POST['xid_customer'];
    $id_pengguna   = $_POST['xid_pengguna'];
    $nama_customer = htmlspecialchars($purifier->purify($_POST['xnama_customer']));
    $username      = htmlspecialchars($purifier->purify($_POST['xusername']));
    $password      = $_POST['xpassword'] ? password_hash($_POST['xpassword'], PASSWORD_DEFAULT) : null;
    $jenis_kelamin = $_POST['xjenis_kelamin'];
    $alamat        = htmlspecialchars($purifier->purify($_POST['xalamat']));
    $tempat_lahir  = htmlspecialchars($purifier->purify($_POST['xtempat_lahir']));
    $tanggal_lahir = $_POST['xtanggal_lahir'];

    mysqli_autocommit($connection, false);

    $success = true;

    try {
        $stmt_pengguna = mysqli_stmt_init($connection);
        
        if (!$password) {
            // Set default password for insertion into tbl_pengguna if data is not exists
            $password = password_hash('123456', PASSWORD_DEFAULT);        
            
            $query_pengguna = 
                "INSERT INTO tbl_pengguna (username, password)
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE username=VALUES(username)";
        } else {
            $query_pengguna = 
                "INSERT INTO tbl_pengguna (username, password)
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE username=VALUES(username), password=VALUES(password)";
        }
        
        if (!mysqli_stmt_prepare($stmt_pengguna, $query_pengguna)) {
            $_SESSION['msg'] = 'Statement Pengguna preparation failed: ' . mysqli_stmt_error($stmt_pengguna);
            echo "<meta http-equiv='refresh' content='0;customer.php?go=customer'>";
            return;
        }
        
        mysqli_stmt_bind_param($stmt_pengguna, 'ss', $username, $password);

        if (!mysqli_stmt_execute($stmt_pengguna)) {
            $_SESSION['msg'] = 'Statement Pengguna preparation failed: ' . mysqli_stmt_error($stmt_pengguna);
            echo "<meta http-equiv='refresh' content='0;customer.php?go=customer'>";
            return;
        }
        
        $id_pengguna = !$id_pengguna ? mysqli_insert_id($connection) : $id_pengguna;
        
        // Customer statement preparation and execution
        $stmt_customer  = mysqli_stmt_init($connection);
        $query_customer = "UPDATE tbl_customer SET
            id_pengguna = ?
            , nama_customer = ?
            , jenis_kelamin = ?
            , alamat = ?
            , tempat_lahir = ?
            , tanggal_lahir = ?
        WHERE id_customer = ?";
        
        if (!mysqli_stmt_prepare($stmt_customer, $query_customer)) {
            $_SESSION['msg'] = 'Statement Customer preparation failed: ' . mysqli_stmt_error($stmt_customer);
            echo "<meta http-equiv='refresh' content='0;customer.php?go=customer'>";
            return;
        }
        
        mysqli_stmt_bind_param($stmt_customer, 'isssssi', $id_pengguna, $nama_customer, $jenis_kelamin, $alamat, $tempat_lahir, $tanggal_lahir, $id_customer);
        
        if (!mysqli_stmt_execute($stmt_customer)) {
            $_SESSION['msg'] = 'Statement Customer preparation failed: ' . mysqli_stmt_error($stmt_customer);
            echo "<meta http-equiv='refresh' content='0;customer.php?go=customer'>";
            return;
        }

        // Commit the transaction if all statements succeed
        if (!mysqli_commit($connection)) {
            $_SESSION['msg'] = 'Transaction commit failed: ' . mysqli_stmt_error($stmt_pengguna);
            echo "<meta http-equiv='refresh' content='0;customer.php?go=customer'>";
            return;
        }

    } catch (Exception $e) {
        // Roll back the transaction if any statement fails
        $success = false;
        mysqli_rollback($connection);
    }

    mysqli_stmt_close($stmt_customer);

    mysqli_autocommit($connection, true);
    mysqli_close($connection);

    !$success
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'save_success';

    echo "<meta http-equiv='refresh' content='0;customer.php?go=customer'>";
?>
