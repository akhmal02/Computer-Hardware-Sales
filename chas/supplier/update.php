<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Supplier - CHAS</title>
    <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #dbe9f4, #b0cde7);
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .navbar {
            background-color: #1565c0;
            color: white;
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
                        <a class="nav-link" href="../product/view_prod.php"><i class="bi bi-box"></i> Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="view_supplier.php"><i class="bi bi-truck"></i> Suppliers</a>
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
                        <h2 class="section-title"><i class="bi bi-pencil-square"></i> Update Supplier</h2>
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
                        <a href="view_supplier.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back to List</a>
                    </div>
                    <?php
                    if (isset($_GET["id"])) {
                        $id = $_GET["id"];
                        include("../connect.php");
                        $sql = "SELECT * FROM SUPPLIER WHERE SUPID = :supID";
                        $stid = oci_parse($dbconn, $sql);
                        oci_bind_by_name($stid, ':supID', $id);
                        oci_execute($stid);
                        $row = oci_fetch_array($stid, OCI_ASSOC);

                        if ($row) {
                            if ($row['SUPTYPE'] === 'L') {
                                $sqlLocal = "SELECT * FROM LOCAL WHERE SUPID = :supID";
                                $stidLocal = oci_parse($dbconn, $sqlLocal);
                                oci_bind_by_name($stidLocal, ':supID', $id);
                                oci_execute($stidLocal);
                                $localData = oci_fetch_array($stidLocal, OCI_ASSOC);
                                $row['TAXTIN'] = $localData['TAXTIN'] ?? '';
                                oci_free_statement($stidLocal);
                            } elseif ($row['SUPTYPE'] === 'I') {
                                $sqlIntl = "SELECT * FROM INTERNATIONAL WHERE SUPID = :supID";
                                $stidIntl = oci_parse($dbconn, $sqlIntl);
                                oci_bind_by_name($stidIntl, ':supID', $id);
                                oci_execute($stidIntl);
                                $intlData = oci_fetch_array($stidIntl, OCI_ASSOC);
                                $row['IMPORTLICENSENUM'] = $intlData['IMPORTLICENSENUM'] ?? '';
                                $row['COUNTRY'] = $intlData['COUNTRY'] ?? '';
                                oci_free_statement($stidIntl);
                            }
                    ?>
                            <form action="process.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-element mb-3">
                                            <label>Supplier ID *</label>
                                            <input type="text" class="form-control" name="id" value="<?= htmlspecialchars($row['SUPID']); ?>" readonly onclick="alert('Supplier ID is a primary key and cannot be changed.')">
                                        </div>
                                        <div class="form-element mb-3">
                                            <label>Supplier Name *</label>
                                            <input type="text" class="form-control" name="supName" value="<?= htmlspecialchars($row['SUPNAME']); ?>">
                                        </div>
                                        <div class="form-element mb-3">
                                            <label>Phone Number *</label>
                                            <input type="text" class="form-control" name="supPhone" value="<?= htmlspecialchars($row['SUPPHONE']); ?>">
                                        </div>
                                        <div class="form-element mb-3">
                                            <label>Company Email *</label>
                                            <input type="email" class="form-control" name="supEmail" value="<?= htmlspecialchars($row['SUPEMAIL']); ?>">
                                        </div>
                                        <div class="form-element mb-3">
                                            <label>Company Address</label>
                                            <input type="text" class="form-control" name="supAddress" value="<?= htmlspecialchars($row['SUPADDRESS']); ?>">
                                        </div>
                                        <div class="form-element mb-3">
                                            <label>Supplier Type *</label>
                                            <select class="form-control" name="supType" onchange="toggleSupplierFields()" readonly onclick=" alert('Supplier Type cannot be changed.')">
                                                <option value="" disabled <?= empty($row['SUPTYPE']) ? 'selected' : '' ?>>Select Supplier Type</option>
                                                <option value="L" <?= ($row['SUPTYPE'] === 'L') ? 'selected' : '' ?>>Local</option>
                                                <option value="I" <?= ($row['SUPTYPE'] === 'I') ? 'selected' : '' ?>>International</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="localFields" style="display:none;">
                                            <div class="form-element mb-3">
                                                <label>Tax TIN *</label>
                                                <input type="text" class="form-control" name="taxTIN" value="<?= htmlspecialchars($row['TAXTIN'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div id="internationalFields" style="display:none;">
                                            <div class="form-element mb-3">
                                                <label>Import License *</label>
                                                <input type="text" class="form-control" name="importLicenseNum" value="<?= htmlspecialchars($row['IMPORTLICENSENUM'] ?? ''); ?>">
                                            </div>
                                            <div class="form-element mb-3">
                                                <label>Country *</label>
                                                <input type="text" class="form-control" name="country" value="<?= htmlspecialchars($row['COUNTRY'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" name="edit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Changes</button>
                                </div>
                            </form>
                    <?php
                        } else {
                            echo "<p>No record found for Supplier ID: " . htmlspecialchars($id) . "</p>";
                        }
                        oci_free_statement($stid);
                        oci_close($dbconn);
                    } else {
                        echo "<p>Invalid request: No Supplier ID provided.</p>";
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSupplierFields() {
            const type = document.querySelector('select[name="supType"]').value;
            document.getElementById('localFields').style.display = type === 'L' ? 'block' : 'none';
            document.getElementById('internationalFields').style.display = type === 'I' ? 'block' : 'none';
        }
        window.addEventListener('DOMContentLoaded', toggleSupplierFields);
    </script>
</body>

</html>