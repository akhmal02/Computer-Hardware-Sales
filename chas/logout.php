<?php
session_start();
session_unset();
session_destroy();

// Redirect to login_auth.php with alert flag
header("Location: login_auth.php?logout=success");
exit();
