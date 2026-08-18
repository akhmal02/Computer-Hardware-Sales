<?php
session_start();
include("../connect.php"); // Include Oracle connection

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Prepare the DELETE query
    $sql = "DELETE FROM receipt WHERE receiptID = :receiptID";
    $stid = oci_parse($dbconn, $sql);
    if (!$stid) {
        $e = oci_error($dbconn);
        $_SESSION['status'] = "Error parsing query: " . htmlentities($e['message']);
        header("Location: view_receipt.php");
        exit;
    }

    // Bind the empID parameter
    oci_bind_by_name($stid, ':receiptID', $id);

    // Execute the query
    $result = oci_execute($stid);
    if ($result) {
        $_SESSION['status'] = "Receipt record deleted successfully.";
    } else {
        $e = oci_error($stid);
        $_SESSION['status'] = "Error deleting record: " . htmlentities($e['message']);
    }

    // Clean up
    oci_free_statement($stid);
    oci_close($dbconn);
} else {
    $_SESSION['status'] = "Invalid request.";
}

header("Location: view_receipt.php");
exit;
?>