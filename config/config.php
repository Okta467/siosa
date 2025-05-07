<?php 
	function base_url($path = '') {
		echo "/siosa/" . $path;
	}

	function base_url_return($path = '') {
		return "/siosa/" . $path;
	}

    date_default_timezone_set("Asia/Bangkok");
	
	DEFINE("SITE_NAME", "Optik Rosa Palembang");
	DEFINE("SITE_NAME_SHORT", "SIOSA");
?>