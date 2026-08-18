<?php
session_start();
include("../connect.php"); // Include Oracle connection

if (isset($_POST["add"])) {
    // Retrieve form data for adding a new employee
    $name = $_POST["name"];
    $ic = $_POST["ic"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $hireDate = $_POST["hireDate"];
    $supervisorID = $_POST["supervisorID"];

    // Basic validation
    if (empty($name) || empty($ic) || empty($phone) || empty($email)|| empty($hireDate)) {
        $_SESSION['status'] = "Name, IC, Phone, Email and Hire Date are required.";
        header("Location: add_emp.php");
        exit;
    }

    // Prepare the INSERT query (assuming empID is auto-generated)
    $sql = "INSERT INTO employee (empID, empName, empIC, empPhone, empEmail, empAddress, empHireDate, supervisorID) 
    VALUES (EMPLOYEE_SEQ.NEXTVAL, :empName, :empIC, :empPhone, :empEmail, :empAddress, TO_DATE(:empHireDate, 'YYYY-MM-DD'), :supervisorID)";

    $stid = oci_parse($dbconn, $sql);
    if (!$stid) {
        $e = oci_error($dbconn);
        $_SESSION['status'] = "Error parsing query: " . htmlentities($e['message']);
        header("Location: add_emp.php");
        exit;
    }

    // Bind parameters
    oci_bind_by_name($stid, ':empName', $name);
    oci_bind_by_name($stid, ':empIC', $ic);
    oci_bind_by_name($stid, ':empPhone', $phone);
    oci_bind_by_name($stid, ':empEmail', $email);
    oci_bind_by_name($stid, ':empAddress', $address);
    oci_bind_by_name($stid, ':empHireDate', $hireDate);
    oci_bind_by_name($stid, ':supervisorID', $supervisorID);

    // Execute the query
    $result = oci_execute($stid);
    if ($result) {
        $_SESSION['status'] = "Employee added successfully.";
    } else {
        $e = oci_error($stid);
        $_SESSION['status'] = "Error adding employee: " . htmlentities($e['message']);
        header("Location: add_emp.php");
    }

    // Clean up
    oci_free_statement($stid);
    oci_close($dbconn);
    header("Location: view_emp.php");
    exit;

} elseif (isset($_POST["edit"])) {
    // Retrieve form data for editing an employee
    $id = $_POST["id"];
    $name = $_POST["name"];
    $ic = $_POST["ic"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $hireDate = $_POST["hireDate"];
    $supervisorID = $_POST["supervisorID"];

    // Basic validation
    if (empty($name) || empty($ic) || empty($phone) || empty($email)) {
        $_SESSION['status'] = "Name, IC, Phone, and Email are required.";
        header("Location: update.php?id=$id");
        exit;
    }

    // Build dynamic SQL depending on hireDate
    if (!empty($hireDate)) {
        $sql = "UPDATE employee SET 
                empName = :empName, 
                empIC = :empIC, 
                empPhone = :empPhone, 
                empEmail = :empEmail, 
                empAddress = :empAddress, 
                empHireDate = TO_DATE(:empHireDate, 'YYYY-MM-DD'), 
                supervisorID = :supervisorID 
                WHERE empID = :empID";
    } else {
        $sql = "UPDATE employee SET 
                empName = :empName, 
                empIC = :empIC, 
                empPhone = :empPhone, 
                empEmail = :empEmail, 
                empAddress = :empAddress, 
                supervisorID = :supervisorID 
                WHERE empID = :empID";
    }

    $stid = oci_parse($dbconn, $sql);
    if (!$stid) {
        $e = oci_error($dbconn);
        $_SESSION['status'] = "Error parsing query: " . htmlentities($e['message']);
        header("Location: update.php?id=$id");
        exit;
    }

    // Bind parameters
    oci_bind_by_name($stid, ':empID', $id);
    oci_bind_by_name($stid, ':empName', $name);
    oci_bind_by_name($stid, ':empIC', $ic);
    oci_bind_by_name($stid, ':empPhone', $phone);
    oci_bind_by_name($stid, ':empEmail', $email);
    oci_bind_by_name($stid, ':empAddress', $address);
    oci_bind_by_name($stid, ':supervisorID', $supervisorID);
    if (!empty($hireDate)) {
        oci_bind_by_name($stid, ':empHireDate', $hireDate);
    }

    // Execute the query
    $result = oci_execute($stid);
    if ($result) {
        $_SESSION['status'] = "Employee record updated successfully.";
    } else {
        $e = oci_error($stid);
        $_SESSION['status'] = "Error updating record: " . htmlentities($e['message']);
        header("Location: update.php?id=$id");
    }

    // Clean up
    oci_free_statement($stid);
    oci_close($dbconn);
    header("Location: view_emp.php");
    exit;
}
