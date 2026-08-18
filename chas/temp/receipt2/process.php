<?php
session_start();
include("../connect.php"); // Include Oracle connection

if (isset($_POST["add"])) {
    // Retrieve form data for adding a receipt
    $receiptDate = $_POST["receiptDate"];
    $totalAmount = $_POST["totalAmount"];
    $custName = $_POST["custName"];
    $custAddress = $_POST["custAddress"];
    $payMethod = $_POST["payMethod"];
    $empID = $_POST["empID"];

    // Basic validation
    if (empty($custName) || empty($totalAmount) || empty($payMethod)) {
        $_SESSION['status'] = "Customer Name, Total Amount, and Payment Method are required.";
        header("Location: add_receipt.php");
        exit;
    }

    // Validate numeric fields
    if (!is_numeric($totalAmount) || !is_numeric($empID)) {
        $_SESSION['status'] = "Total Amount and Employee ID must be valid numbers.";
        header("Location: add_receipt.php");
        exit;
    }

    // Prepare the INSERT query with auto-incremented RECEIPTID
    $sql = "INSERT INTO RECEIPT (RECEIPTID, RECEIPTDATE, TOTALAMOUNT, CUSTNAME, CUSTADDRESS, PAYMETHOD, EMPID) 
            VALUES (receipt_seq.NEXTVAL, TO_DATE(:receiptDate, 'YYYY-MM-DD'), :totalAmount, :custName, :custAddress, :payMethod, :empID)";
    
    $stid = oci_parse($conn, $sql);
    if (!$stid) {
        $e = oci_error($conn);
        $_SESSION['status'] = "Error parsing query: " . htmlentities($e['message']);
        header("Location: add_receipt.php");
        exit;
    }

    // Bind parameters
    oci_bind_by_name($stid, ':receiptDate', $receiptDate);
    oci_bind_by_name($stid, ':totalAmount', $totalAmount, -1, SQLT_NUM);
    oci_bind_by_name($stid, ':custName', $custName);
    oci_bind_by_name($stid, ':custAddress', $custAddress);
    oci_bind_by_name($stid, ':payMethod', $payMethod);
    oci_bind_by_name($stid, ':empID', $empID, -1, SQLT_NUM);

    // Execute the query
    $result = oci_execute($stid);
    if ($result) {
        $_SESSION['status'] = "Receipt added successfully.";
    } else {
        $e = oci_error($stid);
        $_SESSION['status'] = "Error adding receipt: " . htmlentities($e['message']);
        header("Location: add_receipt.php");
    }

    // Clean up
    oci_free_statement($stid);
    oci_close($conn);
    header("Location: view_receipts.php");
    exit;

} elseif (isset($_POST["edit"])) {
    // Retrieve form data for updating a receipt
    $id = $_POST["id"];
    $receiptDate = $_POST["receiptDate"];
    $totalAmount = $_POST["totalAmount"];
    $custName = $_POST["custName"];
    $custAddress = $_POST["custAddress"];
    $payMethod = $_POST["payMethod"];
    $empID = $_POST["empID"];

    // Basic validation
    if (empty($custName) || empty($totalAmount) || empty($payMethod)) {
        $_SESSION['status'] = "Customer Name, Total Amount, and Payment Method are required.";
        header("Location: update.php?id=$id");
        exit;
    }

    // Validate numeric fields
    if (!is_numeric($totalAmount) || !is_numeric($empID)) {
        $_SESSION['status'] = "Total Amount and Employee ID must be valid numbers.";
        header("Location: update.php?id=$id");
        exit;
    }

    // Prepare the UPDATE query
    $sql = "UPDATE RECEIPT SET 
            RECEIPTDATE = TO_DATE(:receiptDate, 'YYYY-MM-DD'), 
            TOTALAMOUNT = :totalAmount, 
            CUSTNAME = :custName, 
            CUSTADDRESS = :custAddress, 
            PAYMETHOD = :payMethod, 
            EMPID = :empID 
            WHERE RECEIPTID = :receiptID";

    $stid = oci_parse($conn, $sql);
    if (!$stid) {
        $e = oci_error($conn);
        $_SESSION['status'] = "Error parsing query: " . htmlentities($e['message']);
        header("Location: update.php?id=$id");
        exit;
    }

    // Bind parameters
    oci_bind_by_name($stid, ':receiptID', $id);
    oci_bind_by_name($stid, ':receiptDate', $receiptDate);
    oci_bind_by_name($stid, ':totalAmount', $totalAmount, -1, SQLT_NUM);
    oci_bind_by_name($stid, ':custName', $custName);
    oci_bind_by_name($stid, ':custAddress', $custAddress);
    oci_bind_by_name($stid, ':payMethod', $payMethod);
    oci_bind_by_name($stid, ':empID', $empID, -1, SQLT_NUM);

    // Execute the query
    $result = oci_execute($stid);
    if ($result) {
        $_SESSION['status'] = "Receipt updated successfully.";
    } else {
        $e = oci_error($stid);
        $_SESSION['status'] = "Error updating receipt: " . htmlentities($e['message']);
        header("Location: update.php?id=$id");
    }

    // Clean up
    oci_free_statement($stid);
    oci_close($conn);
    header("Location: view_receipt.php");
    exit;

} else {
    $_SESSION['status'] = "Invalid request.";
    header("Location: view_receipt.php");
    exit;
}
?>