<?php
	session_start();
	include_once 'config/connection.php';

	// cek apakah tombol submit ditekan sebelum memproses verifikasi login
	if (!isset($_POST['xsubmit'])) {
		$_SESSION['msg'] = 'other_error';
		echo "<meta http-equiv='refresh' content='0;index.php'>";
		return;
	}

	$username = $_POST['xusername'];
	$password = $_POST['xpassword'];


	// jalankan mysql prepare statement untuk mencegah SQL Inject
	$stmt = mysqli_stmt_init($connection);

	mysqli_stmt_prepare($stmt, "SELECT * FROM tbl_pengguna WHERE username=?");
	mysqli_stmt_bind_param($stmt, 's', $username);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$user   = mysqli_fetch_assoc($result);

	mysqli_stmt_close($stmt);


	// redirect ke halaman login jika pengguna tidak ditemukan
	if (!$user) {
		$_SESSION['msg'] = 'user_not_found';
		echo "<meta http-equiv='refresh' content='0;index.php'>";
		return;
	}

	// cek apakah passwordnya benar?
	if (!password_verify($password, $user['password'])) {
		$_SESSION['msg'] = 'wrong_password';
		echo "<meta http-equiv='refresh' content='0;index.php'>";
		return;
	}

    // Get id_customer if hak akses is customer
    if ($user['hak_akses'] === 'customer') {
        $query_customer = mysqli_query($connection, "SELECT id_customer, nama_customer FROM tbl_customer WHERE id_pengguna = {$user['id_pengguna']} LIMIT 1");
        $customer = mysqli_fetch_assoc($query_customer);
    }

    // Get id_supervisor if hak akses is supervisor
    if ($user['hak_akses'] === 'supervisor') {
        $query_supervisor = mysqli_query($connection, "SELECT id_supervisor, nama_supervisor FROM tbl_supervisor WHERE id_pengguna = {$user['id_pengguna']} LIMIT 1");
        $supervisor = mysqli_fetch_assoc($query_supervisor);
    }

	// set sesi user sekarang
	$_SESSION['id_pengguna']     = $user['id_pengguna'];
	$_SESSION['id_customer']     = $customer['id_customer'] ?? null;
	$_SESSION['nama_customer']   = $customer['nama_customer'] ?? null;
	$_SESSION['id_supervisor']   = $supervisor['id_supervisor'] ?? null;
	$_SESSION['nama_supervisor'] = $supervisor['nama_supervisor'] ?? null;
	$_SESSION['username']        = $user['username'];
	$_SESSION['hak_akses']       = $user['hak_akses'];
	$_SESSION['email']           = 'default@gmail.com';

	// Update last login user
	$last_login = date('Y-m-d H:i:s');
	$query_update = mysqli_query($connection, "UPDATE tbl_pengguna SET last_login = '{$last_login}' WHERE id_pengguna = {$user['id_pengguna']}");

	// alihkan user ke halamannya masing-masing
	switch ($user['hak_akses']) {
		case 'admin':
			header("location:admin?go=dashboard");
			break;

		case 'customer':
			header("location:customer/?go=dashboard");
			break;

		case 'supervisor':
			header("location:supervisor/?go=dashboard");
			break;
		
		default:
			header("location:logout.php");
			break;
	}
?>