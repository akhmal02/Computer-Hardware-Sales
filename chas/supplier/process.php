<?php
session_start();
include("../connect.php");

if (isset($_POST["add"])) {
    $supName = $_POST["supName"];
    $supPhone = $_POST["supPhone"];
    $supEmail = $_POST["supEmail"];
    $supAddress = $_POST["supAddress"];
    $supType = $_POST["supType"];

    $taxTIN = $_POST["taxTIN"] ?? null;
    $importLicenseNum = $_POST["importLicenseNum"] ?? null;
    $country = $_POST["country"] ?? null;

    if (empty($supName) || empty($supPhone) || empty($supEmail) || empty($supType)) {
        $_SESSION['status'] = "Name, Phone, Email, and Type are required.";
        header("Location: add_supplier.php");
        exit;
    }

    // Insert into SUPPLIER
    $sql = "INSERT INTO supplier (supID, supName, supPhone, supEmail, supAddress, supType)
            VALUES (SUPPLIER_SEQ.NEXTVAL, :supName, :supPhone, :supEmail, :supAddress, :supType)";
    $stid = oci_parse($dbconn, $sql);
    oci_bind_by_name($stid, ':supName', $supName);
    oci_bind_by_name($stid, ':supPhone', $supPhone);
    oci_bind_by_name($stid, ':supEmail', $supEmail);
    oci_bind_by_name($stid, ':supAddress', $supAddress);
    oci_bind_by_name($stid, ':supType', $supType);
    $result = oci_execute($stid);
    oci_free_statement($stid);

    if (!$result) {
        $e = oci_error($stid);
        $_SESSION['status'] = "Error adding supplier: " . htmlentities($e['message']);
        header("Location: add_supplier.php");
        exit;
    }

    // Get latest SUPID
    $seqStid = oci_parse($dbconn, "SELECT SUPPLIER_SEQ.CURRVAL AS SUPID FROM dual");
    oci_execute($seqStid);
    $row = oci_fetch_array($seqStid, OCI_ASSOC);
    $supID = $row['SUPID'];
    oci_free_statement($seqStid);

    // Insert into child table
    if ($supType == 'L' && !empty($taxTIN)) {
        $sqlLocal = "INSERT INTO LOCAL (supID, taxTIN) VALUES (:supID, :taxTIN)";
        $stidLocal = oci_parse($dbconn, $sqlLocal);
        oci_bind_by_name($stidLocal, ':supID', $supID);
        oci_bind_by_name($stidLocal, ':taxTIN', $taxTIN);
        oci_execute($stidLocal);
        oci_free_statement($stidLocal);
    } elseif ($supType == 'I' && !empty($importLicenseNum) && !empty($country)) {
        $sqlIntl = "INSERT INTO INTERNATIONAL (supID, importLicenseNum, country)
                    VALUES (:supID, :importLicenseNum, :country)";
        $stidIntl = oci_parse($dbconn, $sqlIntl);
        oci_bind_by_name($stidIntl, ':supID', $supID);
        oci_bind_by_name($stidIntl, ':importLicenseNum', $importLicenseNum);
        oci_bind_by_name($stidIntl, ':country', $country);
        oci_execute($stidIntl);
        oci_free_statement($stidIntl);
    }

    oci_close($dbconn);
    $_SESSION['status'] = "Supplier added successfully.";
    header("Location: view_supplier.php");
    exit;
} elseif (isset($_POST['edit'])) {
    $supID = $_POST['id'];
    $supName = $_POST['supName'];
    $supPhone = $_POST['supPhone'];
    $supEmail = $_POST['supEmail'];
    $supAddress = $_POST['supAddress'];
    $supType = $_POST['supType']; // should be locked in form (sent via hidden input)

    $taxTIN = $_POST['taxTIN'] ?? null;
    $importLicenseNum = $_POST['importLicenseNum'] ?? null;
    $country = $_POST['country'] ?? null;

    // Update SUPPLIER (excluding type modification)
    $sql = "UPDATE SUPPLIER SET SUPNAME = :name, SUPPHONE = :phone, SUPEMAIL = :email,
            SUPADDRESS = :addr WHERE SUPID = :id";
    $stid = oci_parse($dbconn, $sql);
    oci_bind_by_name($stid, ':name', $supName);
    oci_bind_by_name($stid, ':phone', $supPhone);
    oci_bind_by_name($stid, ':email', $supEmail);
    oci_bind_by_name($stid, ':addr', $supAddress);
    oci_bind_by_name($stid, ':id', $supID);
    oci_execute($stid);

    // Update child table
    if ($supType == 'L') {
        $check = oci_parse($dbconn, "SELECT 1 FROM LOCAL WHERE SUPID = :id");
        oci_bind_by_name($check, ':id', $supID);
        oci_execute($check);

        if (oci_fetch($check)) {
            $update = oci_parse($dbconn, "UPDATE LOCAL SET TAXTIN = :tin WHERE SUPID = :id");
        } else {
            $update = oci_parse($dbconn, "INSERT INTO LOCAL (SUPID, TAXTIN) VALUES (:id, :tin)");
        }

        oci_bind_by_name($update, ':id', $supID);
        oci_bind_by_name($update, ':tin', $taxTIN);
        oci_execute($update);
    } elseif ($supType == 'I') {
        $check = oci_parse($dbconn, "SELECT 1 FROM INTERNATIONAL WHERE SUPID = :id");
        oci_bind_by_name($check, ':id', $supID);
        oci_execute($check);

        if (oci_fetch($check)) {
            $update = oci_parse($dbconn, "UPDATE INTERNATIONAL SET importLicenseNum = :license, COUNTRY = :country WHERE SUPID = :id");
        } else {
            $update = oci_parse($dbconn, "INSERT INTO INTERNATIONAL (SUPID, importLicenseNum, COUNTRY) VALUES (:id, :license, :country)");
        }

        oci_bind_by_name($update, ':id', $supID);
        oci_bind_by_name($update, ':license', $importLicenseNum);
        oci_bind_by_name($update, ':country', $country);
        oci_execute($update);
    }

    $_SESSION['status'] = "Supplier updated successfully!";
    header("Location: view_supplier.php");
    exit;
}
