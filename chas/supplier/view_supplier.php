<?php
include("../connect.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Supplier - CHAS</title>
    <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.min.css">
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
            margin-top: 2rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #0d47a1;
            margin-bottom: 1.5rem;
        }

        .btn-add {
            margin-bottom: 1rem;
            background-color: #1e88e5;
            color: white;
        }

        .btn-add:hover {
            background-color: #1565c0;
        }

        .btn-add2 {
            margin-bottom: 1rem;
            background-color: rgb(34, 160, 99);
            color: white;
        }

        .btn-add2:hover {
            background-color: rgb(13, 151, 86);
        }

        .table thead {
            background-color: #1565c0;
            color: white;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f1f5fb;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h4 text-white">CHAS - Computer Hardware Application System</span>
            <a href="../logout.php" class="btn btn-light text-dark fw-semibold">Logout</a>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
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

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="main-content">
                    <h2 class="section-title">Supplier Records</h2>
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
                    <a href="add_supplier.php" class="btn btn-add"><i class="bi bi-person-plus"></i> Add New Supplier</a>
                    <!--<a href="view_local.php" class="btn btn-add2"><i class="bi bi-eye"></i> Local Supplier </a>-->
                    <!--<a href="view_international.php" class="btn btn-add2"><i class="bi bi-eye"></i> International Supplier </a>-->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="datatableid">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>E-mail</th>
                                    <!--<th>Address</th>-->
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM SUPPLIER";
                                $stid = oci_parse($dbconn, $sql);
                                oci_execute($stid);
                                while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                                ?>
                                    <tr>
                                        <td><?= $row["SUPID"] ?></td>
                                        <td><?= $row["SUPNAME"] ?></td>
                                        <td><?= $row["SUPPHONE"] ?></td>
                                        <td><?= $row["SUPEMAIL"] ?></td>
                                        <!--<td><?= $row["SUPADDRESS"] ?></td>-->
                                        <td>
                                            <a href="update.php?id=<?= $row['SUPID']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                            <a href="delete.php?id=<?= $row['SUPID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this supplier?');"><i class="bi bi-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                oci_free_statement($stid);
                                oci_close($dbconn);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatableid').DataTable();
        });
    </script>
</body>

</html>