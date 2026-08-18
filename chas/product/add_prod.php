<?php session_start()?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - CHAS</title>
    <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(to right, #dbe9f4, #b0cde7);
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .navbar {
            background-color: #1565c0;
        }

        .navbar-brand,
        .logout-btn {
            color: #fff !important;
        }

        .logout-btn:hover {
            background-color: #f44336;
            color: white;
        }

        .sidebar {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            position: sticky;
            top: 0;
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: #0d47a1;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin: 0.2rem 0.5rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(13, 71, 161, 0.1);
            border-left: 4px solid #1e88e5;
        }

        .sidebar .bi {
            margin-right: 8px;
        }

        .main-content {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .section-title {
            color: #0d47a1;
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: 600;
            color: #0d47a1;
        }

        .form-element input,
        .form-element select {
            border-radius: 12px;
            padding: 10px;
        }

        .btn-primary {
            background-color: #1e88e5;
            border: none;
        }

        .btn-primary:hover {
            background-color: #1565c0;
        }
    </style>
</head>

<body>
    <header class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h4 text-white">CHAS - Computer Hardware Application System</span>
            <a href="../logout.php" class="btn btn-light text-dark fw-semibold">Logout</a>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar py-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard.php"><i class="bi bi-house"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../employee/view_emp.php"><i class="bi bi-people"></i> Employees</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="view_prod.php"><i class="bi bi-box"></i> Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../supplier/view_supplier.php"><i class="bi bi-truck"></i> Suppliers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../receipt/view_receipt.php"><i class="bi bi-receipt"></i> Receipts</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="../receipt/purchase.php"><i class="bi bi-basket"></i> Purchase</a></li>
                </ul>
                <hr class="mx-3 my-2 text-primary">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link " href="../performance.php"><i class="bi bi-bar-chart-line"></i> Performances</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="../report.php"><i class="bi bi-clipboard-data"></i> Reports</a>
                    </li>
                </ul>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="main-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title"><i class="bi bi-plus-square"></i> Add New Product</h2>
                        <?php if (isset($_SESSION['status'])): ?>
                            <?php
                            $type = 'info';
                            if (stripos($_SESSION['status'], 'success') !== false) {
                                $type = 'success';
                            } elseif (stripos($_SESSION['status'], 'error') !== false) {
                                $type = 'danger';
                            }
                            ?>
                            <div class="alert alert-<?= $type ?> alert-dismissible fade show mt-3 mx-4" role="alert">
                                <?= $_SESSION['status'];
                                unset($_SESSION['status']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <a href="view_prod.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back to List</a>
                    </div>
                    <form action="process.php" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-element mb-3">
                                    <label>Product Name *</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Product Name">
                                </div>
                                <div class="form-element mb-3">
                                    <label>Brand *</label>
                                    <input type="text" class="form-control" name="brand" placeholder="Enter Product Brand">
                                </div>
                                <div class="form-element mb-3">
                                    <label>Description</label>
                                    <input type="text" class="form-control" name="desc" placeholder="Enter Product Description">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-element mb-3">
                                    <label>Price *</label>
                                    <input type="text" class="form-control" name="price" placeholder="Enter Product Price">
                                </div>
                                <div class="form-element mb-3">
                                    <label>Category *</label>
                                    <input type="text" class="form-control" name="category" placeholder="Enter Product Category">
                                </div>
                                <div class="form-element mb-3">
                                    <label>Supplier</label>
                                    <select name="supplier" class="form-control">
                                        <option value="">Select Supplier</option>
                                        <?php
                                        include("../connect.php");
                                        $sql = "SELECT SUPID, SUPNAME FROM SUPPLIER";
                                        $stid = oci_parse($dbconn, $sql);
                                        oci_execute($stid);
                                        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                                            echo "<option value=\"{$row['SUPID']}\">{$row['SUPNAME']}</option>";
                                        }
                                        oci_free_statement($stid);
                                        oci_close($dbconn);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" name="add" class="btn btn-primary"><i class="bi bi-check-circle"></i> Add Product</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>