<?php
include("../connect.php");
session_start();

// Only accept form with 'cartData'
if (!isset($_POST['cartData'])) {
    die("Invalid access.");
}

$cartData = json_decode($_POST['cartData'], true);
$receiptDate = $_POST['receiptDate'];
$totalAmount = $_POST['totalAmount'];
$custName = $_POST['custName'];
$custAddress = $_POST['custAddress'];
$payMethod = $_POST['payMethod']; // Not stored, unless you add in DB
$empID = $_POST['empID'];

// Begin transaction
oci_execute(oci_parse($dbconn, "BEGIN"), OCI_NO_AUTO_COMMIT);

// Insert into RECEIPT
$receiptID = null;
$receiptSql = "INSERT INTO RECEIPT (receiptID, receiptDate, totalAmount, custName, custAddress, empID)
               VALUES (receipt_seq.NEXTVAL, TO_DATE(:receiptDate, 'YYYY-MM-DD'), :totalAmount, :custName, :custAddress, :empID)
               RETURNING receiptID INTO :receiptID";
$receiptStmt = oci_parse($dbconn, $receiptSql);

oci_bind_by_name($receiptStmt, ":receiptDate", $receiptDate);
oci_bind_by_name($receiptStmt, ":totalAmount", $totalAmount);
oci_bind_by_name($receiptStmt, ":custName", $custName);
oci_bind_by_name($receiptStmt, ":custAddress", $custAddress);
oci_bind_by_name($receiptStmt, ":empID", $empID);
oci_bind_by_name($receiptStmt, ":receiptID", $receiptID, 32);

if (!oci_execute($receiptStmt, OCI_NO_AUTO_COMMIT)) {
    oci_rollback($dbconn);
    die("Failed to insert receipt.");
}

// Insert each product into RECEIPT_PRODUCT
foreach ($cartData as $item) {
    $prodID = $item['prodID'];
    $qty = $item['qty'];
    $price = $item['price'];

    $bridgeSql = "INSERT INTO RECEIPT_PRODUCT (recProdID, qty, price, prodID, receiptID)
                  VALUES (receipt_product_seq.NEXTVAL, :qty, :price, :prodID, :receiptID)";
    $bridgeStmt = oci_parse($dbconn, $bridgeSql);

    oci_bind_by_name($bridgeStmt, ":qty", $qty);
    oci_bind_by_name($bridgeStmt, ":price", $price);
    oci_bind_by_name($bridgeStmt, ":prodID", $prodID);
    oci_bind_by_name($bridgeStmt, ":receiptID", $receiptID);

    if (!oci_execute($bridgeStmt, OCI_NO_AUTO_COMMIT)) {
        oci_rollback($dbconn);
        die("Failed to insert receipt product.");
    }
}

// Commit transaction
oci_commit($dbconn);

// Redirect or show success message
header("Location: view_receipt.php?success=1");
exit;
?>
