<?php

/**
 * Check and redirect user to its own page if already logged in
 * 
 * @param string $hak_akses $_SESSION (usually $_SESSION['hak_akses'])
 */
function isAlreadyLoggedIn($hak_akses): bool {
	// alihkan user ke halamannya masing-masing
	switch ($hak_akses) {
		case 'admin':
			header("location:admin?go=dashboard");
			break;

		case 'sales':
			header("location:sales/?go=dashboard");
			break;

		case 'supervisor':
			header("location:supervisor/?go=dashboard");
			break;

		default:
			header("location:logout.php");
			break;
	}

    return true;
}

?>