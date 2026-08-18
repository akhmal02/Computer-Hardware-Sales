<?php
session_start();
include("../connect.php"); // Oracle DB connection

if (isset($_POST["add"])) {
    $name = $_POST["name"];
    $brand = $_POST["brand"];
    $desc = $_POST["desc"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $supplier = $_POST["supplier"] ?? null;

    // Basic validation
    if (empty($name) || empty($brand) || empty($desc) || empty($price)) {
        $_SESSION['status'] = "Name, Brand, Description, and Price are required.";
        header("Location: add_prod.php");
        exit;
    }

    if (!is_numeric($price) || $price < 0) {
        $_SESSION['status'] = "Price must be a valid non-negative number.";
        header("Location: add_prod.php");
        exit;
    }

    // Insert into PRODUCT table
    $sql = "INSERT INTO PRODUCT (prodID, prodName, prodBrand, prodDesc, prodPrice, prodCategory)
            VALUES (PRODUCT_SEQ.NEXTVAL, :prodName, :prodBrand, :prodDesc, :prodPrice, :prodCategory)";
    $stid = oci_parse($dbconn, $sql);
    oci_bind_by_name($stid, ":prodName", $name);
    oci_bind_by_name($stid, ":prodBrand", $brand);
    oci_bind_by_name($stid, ":prodDesc", $desc);
    oci_bind_by_name($stid, ":prodPrice", $price);
    oci_bind_by_name($stid, ":prodCategory", $category);
    $result = oci_execute($stid, OCI_NO_AUTO_COMMIT);

    if (!$result) {
        $e = oci_error($stid);
        $_SESSION['status'] = "Error adding product: " . htmlentities($e['message']);
        oci_rollback($dbconn);
        header("Location: add_prod.php");
        exit;
    }

    // Get the last inserted prodID
    $get_id = oci_parse($dbconn, "SELECT PRODUCT_SEQ.CURRVAL AS ID FROM DUAL");
    oci_execute($get_id);
    $row = oci_fetch_assoc($get_id);
    $new_prod_id = $row['ID'] ?? null;
    oci_free_statement($get_id);

    // Insert into PRODUCT_SUPPLIER if supplier selected
    if (!empty($supplier) && $new_prod_id) {
        $sql_sup = "INSERT INTO PRODUCT_SUPPLIER (prodID, supID) VALUES (:prodID, :supID)";
        $stid_sup = oci_parse($dbconn, $sql_sup);
        oci_bind_by_name($stid_sup, ":prodID", $new_prod_id);
        oci_bind_by_name($stid_sup, ":supID", $supplier);
        $result_sup = oci_execute($stid_sup, OCI_NO_AUTO_COMMIT);
        oci_free_statement($stid_sup);

        if (!$result_sup) {
            $e = oci_error($stid_sup);
            $_SESSION['status'] = "Error linking supplier: " . htmlentities($e['message']);
            oci_rollback($dbconn);
            header("Location: add_prod.php");
            exit;
        }
    }

    oci_commit($dbconn);
    oci_free_statement($stid);
    oci_close($dbconn);

    $_SESSION['status'] = "Product added successfully.";
    header("Location: view_prod.php");
    exit;
} elseif (isset($_POST["edit"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $brand = $_POST["brand"];
    $desc = $_POST["desc"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $supplier = $_POST["supplier"] ?? null;

    // Validation
    if (empty($name) || empty($brand) || empty($desc) || empty($price)) {
        $_SESSION['status'] = "All fields are required.";
        header("Location: update.php?id=$id");
        exit;
    }

    if (!is_numeric($price) || $price < 0) {
        $_SESSION['status'] = "Price must be a valid non-negative number.";
        header("Location: update.php?id=$id");
        exit;
    }

    // Update product data
    $sql = "UPDATE PRODUCT SET 
                prodName = :prodName, 
                prodBrand = :prodBrand, 
                prodDesc = :prodDesc, 
                prodPrice = :prodPrice, 
                prodCategory = :prodCategory 
            WHERE prodID = :prodID";
    $stid = oci_parse($dbconn, $sql);
    oci_bind_by_name($stid, ":prodID", $id);
    oci_bind_by_name($stid, ":prodName", $name);
    oci_bind_by_name($stid, ":prodBrand", $brand);
    oci_bind_by_name($stid, ":prodDesc", $desc);
    oci_bind_by_name($stid, ":prodPrice", $price);
    oci_bind_by_name($stid, ":prodCategory", $category);
    $result = oci_execute($stid, OCI_NO_AUTO_COMMIT);

    if (!$result) {
        $e = oci_error($stid);
        $_SESSION['status'] = "Error updating product: " . htmlentities($e['message']);
        oci_rollback($dbconn);
        header("Location: update.php?id=$id");
        exit;
    }

    // Check current supplier
    $cur_stid = oci_parse($dbconn, "SELECT supID FROM PRODUCT_SUPPLIER WHERE prodID = :prodID");
    oci_bind_by_name($cur_stid, ":prodID", $id);
    oci_execute($cur_stid);
    $cur_row = oci_fetch_assoc($cur_stid);
    $current_supplier = $cur_row['SUPID'] ?? null;
    oci_free_statement($cur_stid);

    // Only update supplier if it changed
    if ($supplier !== $current_supplier) {
        // Delete old supplier link
        $del_sup = oci_parse($dbconn, "DELETE FROM PRODUCT_SUPPLIER WHERE prodID = :prodID");
        oci_bind_by_name($del_sup, ":prodID", $id);
        oci_execute($del_sup, OCI_NO_AUTO_COMMIT);
        oci_free_statement($del_sup);

        // Insert new supplier if provided
        if (!empty($supplier)) {
            $sql_sup = "INSERT INTO PRODUCT_SUPPLIER (prodID, supID) VALUES (:prodID, :supID)";
            $stid_sup = oci_parse($dbconn, $sql_sup);
            oci_bind_by_name($stid_sup, ":prodID", $id);
            oci_bind_by_name($stid_sup, ":supID", $supplier);
            oci_execute($stid_sup, OCI_NO_AUTO_COMMIT);
            oci_free_statement($stid_sup);
        }
    }

    oci_commit($dbconn);
    oci_free_statement($stid);
    oci_close($dbconn);

    $_SESSION['status'] = "Product updated successfully.";
    header("Location: view_prod.php");
    exit;
} else {
    $_SESSION['status'] = "Invalid request.";
    header("Location: view_prod.php");
    exit;
}
