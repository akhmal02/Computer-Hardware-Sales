<?php
include("../connect.php");

// Get filter values from GET parameters
$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Base SQL query
$sql = "SELECT p.prodCategory, SUM(rp.qty) AS total_qty
        FROM PRODUCT p
        JOIN RECEIPT_PRODUCT rp ON p.prodID = rp.prodID
        JOIN RECEIPT r ON rp.receiptID = r.receiptID
        WHERE 1=1";

// Apply filters if provided
if (!empty($month)) {
    $sql .= " AND TO_CHAR(r.receiptDate, 'MM') = :month";
}
if (!empty($year)) {
    $sql .= " AND TO_CHAR(r.receiptDate, 'YYYY') = :year";
}

$sql .= " GROUP BY p.prodCategory ORDER BY total_qty DESC";

// Prepare and bind
$stid = oci_parse($dbconn, $sql);
if (!empty($month)) {
    oci_bind_by_name($stid, ":month", $month);
}
if (!empty($year)) {
    oci_bind_by_name($stid, ":year", $year);
}
oci_execute($stid);

// Build result
$labels = [];
$values = [];

while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
    $labels[] = $row['PRODCATEGORY'];
    $values[] = (int)$row['TOTAL_QTY'];
}

oci_free_statement($stid);
oci_close($dbconn);

// Return JSON
echo json_encode(['labels' => $labels, 'values' => $values]);
