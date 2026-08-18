<?php
header('Content-Type: application/json');
include("../connect.php");

$sql = "
    SELECT
        (SELECT COUNT(PRODID) FROM PRODUCT) AS total_products,
        (SELECT COUNT(SUPID) FROM SUPPLIER) AS total_suppliers,
        (SELECT COUNT(EMPID) FROM EMPLOYEE) AS total_staff,
        (SELECT COUNT(RECEIPTID) FROM RECEIPT WHERE TRUNC(receiptDate) = TRUNC(SYSDATE)) AS today_receipts
    FROM DUAL
";

$parse = oci_parse($dbconn, $sql);
oci_execute($parse);


$data = array_change_key_case(oci_fetch_assoc($parse), CASE_LOWER);
echo json_encode($data);

oci_free_statement($parse);
oci_close($dbconn);
?>
