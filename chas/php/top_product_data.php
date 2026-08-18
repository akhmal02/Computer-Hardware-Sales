<?php
include '../connect.php';

$month = $_GET['month'] ?? '';
$year = $_GET['year'] ?? '';

// SQL query with month/year filter and total_revenue only
$sql = "SELECT p.prodName, 
               SUM(rp.qty * rp.price) AS total_revenue
        FROM PRODUCT p
        JOIN RECEIPT_PRODUCT rp ON p.prodID = rp.prodID
        JOIN RECEIPT r ON rp.receiptID = r.receiptID
        WHERE 1=1";

if ($month !== '') {
    $sql .= " AND TO_CHAR(r.receiptDate, 'MM') = :month";
}
if ($year !== '') {
    $sql .= " AND TO_CHAR(r.receiptDate, 'YYYY') = :year";
}

$sql .= " GROUP BY p.prodName
          ORDER BY total_revenue DESC";

$stid = oci_parse($dbconn, $sql);

if ($month !== '') oci_bind_by_name($stid, ":month", $month);
if ($year !== '') oci_bind_by_name($stid, ":year", $year);

oci_execute($stid);

$labels = [];
$totalRevenue = [];

while ($row = oci_fetch_assoc($stid)) {
    $labels[] = $row['PRODNAME'];
    $totalRevenue[] = (float)$row['TOTAL_REVENUE'];
}

oci_free_statement($stid);
oci_close($dbconn);

header('Content-Type: application/json');
echo json_encode([
    'labels' => $labels,
    'totalRevenue' => $totalRevenue
]);
