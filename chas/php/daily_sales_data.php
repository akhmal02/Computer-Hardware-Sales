<?php
include '../connect.php';

$month = $_GET['month'] ?? '';
$year = $_GET['year'] ?? '';

$sql = "SELECT TO_CHAR(receiptDate, 'DD-MON') AS receipt_day,
               COUNT(receiptID) AS total_receipts,
               SUM(totalAmount) AS total_sales,
               MAX(receiptDate) AS full_date
        FROM RECEIPT
        WHERE 1=1";

if ($month !== '') {
    $sql .= " AND TO_CHAR(receiptDate, 'MM') = :month";
}
if ($year !== '') {
    $sql .= " AND TO_CHAR(receiptDate, 'YYYY') = :year";
}

$sql .= " GROUP BY TO_CHAR(receiptDate, 'DD-MON')
          ORDER BY full_date DESC";

$stid = oci_parse($dbconn, $sql);

if ($month !== '') oci_bind_by_name($stid, ":month", $month);
if ($year !== '') oci_bind_by_name($stid, ":year", $year);

oci_execute($stid);

$labels = [];
$sales = [];

while ($row = oci_fetch_assoc($stid)) {
    $labels[] = $row['RECEIPT_DAY']; // Still DD-MON format
    $sales[] = (float)$row['TOTAL_SALES'];
}

oci_free_statement($stid);
oci_close($dbconn);

header('Content-Type: application/json');
echo json_encode([
    'labels' => array_reverse($labels),
    'sales' => array_reverse($sales)
]);
