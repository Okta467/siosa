<?php
$current_page = $_GET['go'] ?? '';
$user_logged_in = $_SESSION['nama_customer'] ?? $_SESSION['nama_guest'] ?? $_SESSION['username'];
?>

<nav class="sidenav shadow-right sidenav-light">
  <div class="sidenav-menu">
    <div class="nav accordion" id="accordionSidenav">
      <!-- Sidenav Menu Heading (Core)-->
      <div class="sidenav-menu-heading">Core</div>

      <a class="nav-link <?php if ($current_page === 'dashboard') echo 'active' ?>" href="index.php?go=dashboard">
        <div class="nav-link-icon"><i data-feather="activity"></i></div>
        Dashboard
      </a>

      <div class="sidenav-menu-heading">Profil</div>

      <a class="nav-link <?php if ($current_page === 'profil') echo 'active' ?>" href="profil.php?go=profil">
        <div class="nav-link-icon"><i data-feather="users"></i></div>
        Profil
      </a>

      <div class="sidenav-menu-heading">Master</div>

      <a class="nav-link <?php if ($current_page === 'barang') echo 'active' ?>" href="barang.php?go=barang">
        <div class="nav-link-icon"><i data-feather="package"></i></div>
        Barang
      </a>

      <a class="nav-link <?php if ($current_page === 'pesanan') echo 'active' ?>" href="pesanan.php?go=pesanan">
        <div class="nav-link-icon"><i data-feather="shopping-cart"></i></div>
        Pesanan
      </a>

      <a class="nav-link <?php if ($current_page === 'informasi') echo 'active' ?>" href="informasi.php?go=informasi">
        <div class="nav-link-icon"><i data-feather="info"></i></div>
        Informasi
      </a>

      <div class="sidenav-menu-heading">Lainnya</div>

      <a class="nav-link" href="<?= base_url('logout.php') ?>">
        <div class="nav-link-icon"><i data-feather="log-out"></i></div>
        Keluar
      </a>

    </div>
  </div>
  <!-- Sidenav Footer-->
  <div class="sidenav-footer">
    <div class="sidenav-footer-content">
      <div class="sidenav-footer-subtitle">Anda masuk sebagai:</div>
      <div class="sidenav-footer-title"><?= ucwords($user_logged_in) ?></div>
    </div>
  </div>
</nav>