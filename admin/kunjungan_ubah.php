<?php
    include_once '../helpers/isAccessAllowedHelper.php';

    // cek apakah user yang mengakses adalah admin?
    if (!isAccessAllowed('admin')) {
        session_destroy();
        // echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    require_once '../vendor/htmlpurifier/HTMLPurifier.auto.php';
    include_once '../config/connection.php';

    // to sanitize user input
    $config   = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    
    $id_kunjungan = htmlspecialchars($purifier->purify($_POST['xid_kunjungan']));
    $id_customer = htmlspecialchars($purifier->purify($_POST['xid_customer']));
    $is_visited = $_POST['xis_visited'];
    $tanggal_kunjungan = $_POST['xtanggal_kunjungan'];
    $jam_kunjungan = $_POST['xjam_kunjungan'];
    $tanggal_dan_jam_kunjungan = "{$tanggal_kunjungan} {$jam_kunjungan}";
    $is_allowed_is_visited = isset($is_visited) && in_array($is_visited, ['1', '0']);

    if (!$is_allowed_is_visited) {
        $_SESSION['msg'] = 'Hanya boleh memilih Berkunjung atau Belum Berkunjung!';
        echo "<meta http-equiv='refresh' content='0;kunjungan.php?go=kunjungan'>";
        return;
    }
    
    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, "UPDATE tbl_kunjungan SET id_kunjungan=?, id_customer=?, tanggal_kunjungan=?, is_visited=? WHERE id_kunjungan=?");
    mysqli_stmt_bind_param($stmt, 'iisii', $id_kunjungan, $id_customer, $tanggal_dan_jam_kunjungan, $is_visited, $id_kunjungan);

    $update = mysqli_stmt_execute($stmt);

    !$update
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'update_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;kunjungan.php?go=kunjungan'>";
?>