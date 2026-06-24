<?php
session_start();
session_unset();
session_destroy();
$base_url = "http://" . $_SERVER['SERVER_NAME'] . "/uas_webservice";
header("Location: " . $base_url . "/login.php");
exit;
?>
