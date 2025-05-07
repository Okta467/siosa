<?php
	session_start();
    include_once 'helpers/isAccessAllowedHelper.php';
    
	// cek apakah tombol submit ditekan sebelum memproses verifikasi login
	if (!isset($_POST['xsubmit'])) {
		$_SESSION['msg'] = 'other_error';
		echo "<meta http-equiv='refresh' content='0;customer_daftar.php'>";
		return;
	}

    require_once 'vendor/htmlpurifier/HTMLPurifier.auto.php';
    include_once 'config/connection.php';

    // to sanitize user input
    $config   = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    
    $nama_customer = htmlspecialchars($purifier->purify($_POST['xnama_customer']));
    $username      = htmlspecialchars($purifier->purify($_POST['xusername']));
    $password      = $_POST['xpassword'] ? password_hash($_POST['xpassword'], PASSWORD_DEFAULT) : null;
    $jenis_kelamin = $_POST['xjenis_kelamin'];
    $hak_akses     = 'customer';
    $alamat        = htmlspecialchars($purifier->purify($_POST['xalamat']));
    $tempat_lahir  = htmlspecialchars($purifier->purify($_POST['xtempat_lahir']));
    $tanggal_lahir = $_POST['xtanggal_lahir'];

    mysqli_autocommit($connection, false);

    $success = true;

    try {
        // Pengguna statement preparation and execution
        $stmt_pengguna  = mysqli_stmt_init($connection);
        $query_pengguna = "INSERT INTO tbl_pengguna (username, password, hak_akses) VALUES (?, ?, ?)";
        
        if (!mysqli_stmt_prepare($stmt_pengguna, $query_pengguna)) {
            $_SESSION['msg'] = 'Statement Pengguna preparation failed: ' . mysqli_stmt_error($stmt_pengguna);
            echo "<meta http-equiv='refresh' content='0;customer_daftar.php'>";
            return;
        }
        
        mysqli_stmt_bind_param($stmt_pengguna, 'sss', $username, $password, $hak_akses);
        
        if (!mysqli_stmt_execute($stmt_pengguna)) {
            $_SESSION['msg'] = 'Statement Pengguna preparation failed: ' . mysqli_stmt_error($stmt_pengguna);
            echo "<meta http-equiv='refresh' content='0;customer_daftar.php'>";
            return;
        }

        // Customer statement preparation and execution
        $stmt_customer  = mysqli_stmt_init($connection);
        $query_customer = "INSERT INTO tbl_customer 
        (
            id_pengguna
            , nama_customer
            , jenis_kelamin
            , alamat
            , tempat_lahir
            , tanggal_lahir
        ) 
        VALUES (?, ?, ?, ?, ?, ?)";
        
        if (!mysqli_stmt_prepare($stmt_customer, $query_customer)) {
            $_SESSION['msg'] = 'Statement Customer preparation failed: ' . mysqli_stmt_error($stmt_customer);
            echo "<meta http-equiv='refresh' content='0;customer_daftar.php'>";
            return;
        }
        
        $id_pengguna = mysqli_insert_id($connection) !== 0 ? mysqli_insert_id($connection) : NULL;
        mysqli_stmt_bind_param($stmt_customer, 'isssss', $id_pengguna, $nama_customer, $jenis_kelamin, $alamat, $tempat_lahir, $tanggal_lahir);
        
        if (!mysqli_stmt_execute($stmt_customer)) {
            $_SESSION['msg'] = 'Statement Customer preparation failed: ' . mysqli_stmt_error($stmt_customer);
            echo "<meta http-equiv='refresh' content='0;customer_daftar.php'>";
            return;
        }

        // Commit the transaction if all statements succeed
        if (!mysqli_commit($connection)) {
            $_SESSION['msg'] = 'Transaction commit failed: ' . mysqli_stmt_error($stmt_customer);
            echo "<meta http-equiv='refresh' content='0;customer_daftar.php'>";
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

    echo "<meta http-equiv='refresh' content='0;index.php'>";
?>
