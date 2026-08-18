<?php
$user = "system";
$pass = "oracle";
$host = "localhost/FREEPDB1";
$dbconn = oci_connect($user, $pass, $host);
if (!$dbconn) {
    $e = oci_error();
    trigger_error(
        htmlentities($e['message'], ENT_QUOTES),
        E_USER_ERROR
    );
} else {
    echo "";
}
