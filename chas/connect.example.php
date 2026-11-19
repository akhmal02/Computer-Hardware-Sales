<?php
/**
 * Oracle Database Connection Configuration Template
 * --------------------------------------------------
 * Copy this file to 'connect.php' and update with your local Oracle Database credentials.
 */

$user = "SYSTEM";               // Oracle Database Username (e.g., SYSTEM or CHAS_ADMIN)
$pass = "your_oracle_password";  // Oracle Database Password
$host = "localhost/XEPDB1";      // Oracle Host and Service Name (e.g., localhost/XE or localhost/XEPDB1)

$dbconn = oci_connect($user, $pass, $host);

if (!$dbconn) {
    $e = oci_error();
    trigger_error(
        htmlentities($e['message'], ENT_QUOTES),
        E_USER_ERROR
    );
}
?>
