<?php
// Simulan ang session
session_start();

// I-destroy ang session para mag-log out ang user
session_unset();  // Tanggalin ang lahat ng session variables
session_destroy();  // I-destroy ang session

// I-redirect ang user pabalik sa homepage o login page pagkatapos mag-log out
header("Location: index.php");  // O "login.php" kung nais mo
exit;
?>
