<?php
session_start();
include("../connect.php"); // Oracle DB connection

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === "add") {
    $receiptDate = $_POST['receiptDate']; // Already in YYYY-MM-DD
    $totalAmount = $_POST['totalAmount'];
    $custName = $_POST['custName'];
    $custAddress = $_POST['custAddress'];
    $payMethod = $_POST['payMethod'];
    $empID = $_POST['empID'];
    $cartData = json_decode($_POST['cartData'], true); // Decode cart JSON

    if (empty($custName) || empty($custAddress) || empty($payMethod) || empty($empID) || empty($cartData)) {
        $_SESSION['status'] = "Please fill all required fields and ensure cart is not empty.";
        header("Location: purchase.php");
        exit;
    }

    // Start transaction
    oci_execute(oci_parse($dbconn, "BEGIN"), OCI_NO_AUTO_COMMIT);

    // 1. Insert into RECEIPT table
    $receiptInsertSQL = "INSERT INTO RECEIPT (
        RECEIPTID, RECEIPTDATE, TOTALAMOUNT, CUSTNAME, CUSTADDRESS, PAYMETHOD, EMPID
    ) VALUES (
        receipt_seq.NEXTVAL,
        TO_DATE(:receiptDate, 'YYYY-MM-DD'),
        :totalAmount,
        :custName,
        :custAddress,
        :payMethod,
        :empID
    ) RETURNING RECEIPTID INTO :receiptID";

    $stmt = oci_parse($dbconn, $receiptInsertSQL);

    oci_bind_by_name($stmt, ":receiptDate", $receiptDate);
    oci_bind_by_name($stmt, ":totalAmount", $totalAmount);
    oci_bind_by_name($stmt, ":custName", $custName);
    oci_bind_by_name($stmt, ":custAddress", $custAddress);
    oci_bind_by_name($stmt, ":payMethod", $payMethod);
    oci_bind_by_name($stmt, ":empID", $empID);
    oci_bind_by_name($stmt, ":receiptID", $receiptID, 10); // RETURNING value

    $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

    if (!$result) {
        $e = oci_error($stmt);
        $_SESSION['status'] = "Error inserting receipt: " . htmlentities($e['message']);
        oci_rollback($dbconn);
        header("Location: purchase.php");
        exit;
    }

    // 2. Insert each product into RECEIPT_PRODUCT
    $productInsertSQL = "INSERT INTO RECEIPT_PRODUCT (
        RECPRODID, QTY, PRICE, RECEIPTID, PRODID
    ) VALUES (
        receipt_product_seq.NEXTVAL,
        :qty,
        :price,
        :receiptID,
        :prodID
    )";

    $productStmt = oci_parse($dbconn, $productInsertSQL);

    foreach ($cartData as $item) {
        oci_bind_by_name($productStmt, ":qty", $item['qty']);
        oci_bind_by_name($productStmt, ":price", $item['price']);
        oci_bind_by_name($productStmt, ":receiptID", $receiptID);
        oci_bind_by_name($productStmt, ":prodID", $item['prodID']);

        $result = oci_execute($productStmt, OCI_NO_AUTO_COMMIT);
        if (!$result) {
            $e = oci_error($productStmt);
            $_SESSION['status'] = "Error inserting product: " . htmlentities($e['message']);
            oci_rollback($dbconn);
            header("Location: purchase.php");
            exit;
        }
    }

    // 3. Commit all
    oci_commit($dbconn);
    oci_free_statement($stmt);
    oci_free_statement($productStmt);
    oci_close($dbconn);

    $_SESSION['status'] = "Receipt added successfully!";
    header("Location: view_receipt.php");
    exit;
}
