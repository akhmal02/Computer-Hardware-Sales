<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 56px 0 0;
            background-color: #212529;
            box-shadow: inset -1px 0 0 rgba(255, 255, 255, .1);
        }
        @media (max-width: 767.98px) {
            .sidebar {
                top: 56px;
            }
        }
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 56px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        .sidebar .nav-link,
        .sidebar .dropdown-toggle {
            font-weight: 500;
            color: #ced4da;
        }
        .sidebar .nav-link .bi,
        .sidebar .dropdown-toggle .bi {
            margin-right: 4px;
            color: #6c757d;
        }
        .sidebar .nav-link:hover,
        .sidebar .dropdown-toggle:hover {
            color: #ffffff;
            background-color: #343a40;
        }
        .sidebar .nav-link.active {
            color: #0d6efd;
            background-color: #343a40;
        }
        .sidebar .nav-link:hover .bi,
        .sidebar .nav-link.active .bi,
        .sidebar .dropdown-toggle:hover .bi {
            color: inherit;
        }
        .sidebar-heading {
            font-size: .75rem;
            text-transform: uppercase;
            color: #6c757d;
            padding: 0 1rem;
        }
        .dropdown-menu {
            background-color: #343a40;
            border: 1px solid rgba(255, 255, 255, .15);
        }
        .dropdown-item {
            color: #ced4da;
        }
        .dropdown-item:hover,
        .dropdown-item:focus {
            color: #ffffff;
            background-color: #495057;
        }
        .sidebar .dropdown-menu {
            position: static;
            width: 100%;
            margin: 0;
            border-radius: 0;
        }
        .logout-btn {
            background-color: darkgray;
            color: white;
            border: none;
        }
        .logout-btn:hover {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body class="bg-white">
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="index.php">CHAS</a>
        <a href="login_auth.php" class="btn logout-btn">Logout</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </header>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../index.php">
                                <i class="bi bi-house"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-badge"></i>
                                Employee
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                                <li><a class="dropdown-item" href="../employee/view_emp.php">View Employee</a></li>
                                <li><a class="dropdown-item" href="../employee/add_emp.php">Add Employee</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-cart"></i>
                                Product
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                                <li><a class="dropdown-item" href="../product/view_prod.php">Search Product</a></li>
                                <li><a class="dropdown-item" href="../product/add_prod.php">Add Product</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-box-seam"></i>
                                Supplier
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                                <li><a class="dropdown-item" href="../supplier/view_supplier.php">View Supplier</a></li>
                                <li><a class="dropdown-item" href="../supplier/add_supplier.php">Add Supplier</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-receipt"></i>
                                Receipt
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                                <li><a class="dropdown-item" href="view_receipt.php">View Receipt</a></li>
                                <li><a class="dropdown-item" href="add_receipt.php">Add Receipt</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="../purchase.php"><i class="bi bi-basket"></i> Purchase</a></li>
                    </ul>
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center mt-4 mb-1">
                        <span>Saved reports</span>
                        <a class="link-secondary" href="#" aria-label="Add a new report">
                            <i class="bi bi-plus-circle"></i>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-file-earmark-text"></i>
                                Current month
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 text-black">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3" style="border-bottom: 1px solid #000;">
                    <h1 class="h2">Add Receipt</h1>
                </div>
                <div class="container my-5">
                    <header class="d-flex justify-content-between my-4">
                        <h1 class="custom-h1">Receipt Details</h1>
                        <div>
                            <a href="view_receipt.php" class="btn btn-primary text-light">Back to List</a>
                        </div>
                    </header>
                    <form action="process.php" method="post">
                        <div class="form-element my-4">
                            <label for="name" class="form-label text-white">Receipt Date :<span class="asterisk"> *</span></label>
                            <input type="date" class="form-control" name="receiptDate" placeholder="Enter Receipt Date" required>
                        </div>
                        <div class="form-element my-4">
                            <label for="name" class="form-label text-white">Total Amount :<span class="asterisk"> *</span></label>
                            <input type="text" class="form-control" name="totalAmount" placeholder="Total Amount" required>
                        </div>
                        <div class="form-element my-4">
                            <label for="name" class="form-label text-white">Customer Name :<span class="asterisk"> *</span></label>
                            <input type="text" class="form-control" name="custName" placeholder="Customer Name:" required>
                        </div>
                        <div class="form-element my-4">
                            <label for="name" class="form-label text-white">Customer Address :<span class="asterisk"> *</span></label>
                            <input type="text" class="form-control" name="custAddress" placeholder="Address:" required>
                        </div>
                        <div class="form-element my-4">
                            <label for="name" class="form-label text-white">Payment Method :<span class="asterisk"> *</span></label>
                            <input type="text" class="form-control" name="payMethod" placeholder="Payment Method:" required>
                        </div>
                        <div class="form-element my-4">
                            <label for="empID" class="form-label text-white">Person In Charge :<span class="asterisk"> *</span></label>
                            <select name="empID" id="empID" class="form-control" required>
                                <option value="">Select Employee</option>
                                <?php
                                include("../connect.php");
                                $sql = "SELECT empID, empName FROM EMPLOYEE ORDER BY empName";
                                $stid = oci_parse($dbconn, $sql);
                                oci_execute($stid);
                                while ($sup = oci_fetch_array($stid, OCI_ASSOC)) {
                                    echo "<option value=\"" . htmlspecialchars($sup['EMPID']) . "\">" . htmlspecialchars($sup['EMPNAME']) . "</option>";
                                }
                                oci_free_statement($stid);
                                ?>
                            </select>
                        </div>
                        <!-- Added fields for RECEIPT_PRODUCT -->
                        <div class="form-element my-4">
                            <label for="prodID" class="form-label text-white">Product :<span class="asterisk"> *</span></label>
                            <select name="prodID" id="prodID" class="form-control" required>
                                <option value="">Select Product</option>
                                <?php
                                $sql = "SELECT prodID, prodName FROM PRODUCT ORDER BY prodName";
                                $stid = oci_parse($dbconn, $sql);
                                oci_execute($stid);
                                while ($prod = oci_fetch_array($stid, OCI_ASSOC)) {
                                    echo "<option value=\"" . htmlspecialchars($prod['PRODID']) . "\">" . htmlspecialchars($prod['PRODNAME']) . "</option>";
                                }
                                oci_free_statement($stid);
                                ?>
                            </select>
                        </div>
                        <div class="form-element my-4">
                            <label for="qty" class="form-label text-white">Quantity :<span class="asterisk"> *</span></label>
                            <input type="number" class="form-control" name="qty" placeholder="Quantity" min="1" required>
                        </div>
                        <div class="form-element my-4">
                            <label for="price" class="form-label text-white">Price :<span class="asterisk"> *</span></label>
                            <input type="text" class="form-control" name="price" placeholder="Price" required>
                        </div>
                        <div class="form-element my-4">
                            <input type="submit" name="add" value="Add Receipt" class="btn btn-primary text-light">
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>