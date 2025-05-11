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
    
    $id_kunjungan = htmlspecialchars($purifier->purify($_POST['xid_kunjungan']));
    $id_customer_logged_in = $_SESSION['id_customer'];
    $is_visited = 0; // belum berkunjung
    $tanggal_kunjungan = $_POST['xtanggal_kunjungan'];
    $jam_kunjungan = $_POST['xjam_kunjungan'];
    $tanggal_dan_jam_kunjungan = "{$tanggal_kunjungan} {$jam_kunjungan}";
    $is_allowed_is_visited = isset($is_visited) && in_array($is_visited, ['1', '0']);

    if (!$is_allowed_is_visited) {
        $_SESSION['msg'] = 'Hanya boleh memilih Berkunjung atau Belum Berkunjung!';
        echo "<meta http-equiv='refresh' content='0;kunjungan.php?go=kunjungan'>";
        return;
    }
    
    $stmt_current_kunjungan = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt_current_kunjungan, "SELECT * FROM tbl_kunjungan WHERE id_kunjungan=?");
    mysqli_stmt_bind_param($stmt_current_kunjungan, 'i', $id_kunjungan);
    mysqli_stmt_execute($stmt_current_kunjungan);

    $result_current_kunjungan = mysqli_stmt_get_result($stmt_current_kunjungan);
    $kunjungan = mysqli_fetch_assoc($result_current_kunjungan);
    $id_customer_in_current_kunjungan = $kunjungan['id_customer'];

    if ($id_customer_logged_in != $id_customer_in_current_kunjungan) {
        $_SESSION['msg'] = 'ID Customer kunjungan tidak sama dengan yang login saat ini!';
        echo "<meta http-equiv='refresh' content='0;kunjungan.php?go=kunjungan'>";
        return;
    }
    
    $stmt_kunjungan = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt_kunjungan, "UPDATE tbl_kunjungan SET id_customer=?, tanggal_kunjungan=?, is_visited=? WHERE id_kunjungan=?");
    mysqli_stmt_bind_param($stmt_kunjungan, 'isii', $id_customer_logged_in, $tanggal_dan_jam_kunjungan, $is_visited, $id_kunjungan);

    $update = mysqli_stmt_execute($stmt_kunjungan) or die (mysqli_stmt_error($stmt_kunjungan));

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt_current_kunjungan);
    mysqli_stmt_close($stmt_kunjungan);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;kunjungan.php?go=kunjungan'>";
?>