<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Receipt - CHAS</title>
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
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .section-title {
            color: #0d47a1;
            margin-bottom: 1.5rem;
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

        .btn-add {
            background-color: #1e88e5;
            color: white;
        }

        .btn-add:hover {
            background-color: #1565c0;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Header -->
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
                    <li class="nav-item"><a class="nav-link" href="../dashboard.php"><i class="bi bi-house"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../employee/view_emp.php"><i class="bi bi-people"></i> Employees</a></li>
                    <li class="nav-item"><a class="nav-link" href="../product/view_prod.php"><i class="bi bi-box"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="../supplier/view_supplier.php"><i class="bi bi-truck"></i> Suppliers</a></li>
                    <li class="nav-item"><a class="nav-link active" href="view_receipt.php"><i class="bi bi-receipt"></i> Receipts</a></li>
                    <li class="nav-item"><a class="nav-link" href="purchase.php"><i class="bi bi-basket"></i> Purchase</a></li>
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
                    <h2 class="section-title">Receipt Records</h2>
                    <a href="purchase.php" class="btn btn-add"><i class="bi bi-person-plus"></i> Add New Receipt</a>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="datatableid">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Customer</th>
                                    <th>Address</th>
                                    <!-- <th>Payment Method</th> -->
                                    <th>Employee In Charge</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include("../connect.php");

                                $sql = "SELECT r.RECEIPTID, r.RECEIPTDATE, r.TOTALAMOUNT, r.CUSTNAME, r.PAYMETHOD, e.EMPNAME
                                        FROM RECEIPT r
                                        LEFT JOIN EMPLOYEE e ON r.EMPID = e.EMPID
                                        ORDER BY r.RECEIPTDATE DESC";

                                $stid = oci_parse($dbconn, $sql);
                                oci_execute($stid);

                                while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                                ?>
                                    <tr>
                                        <td><?= $row["RECEIPTID"] ?></td>
                                        <td><?= $row["RECEIPTDATE"] ?></td>
                                        <td><?= $row["TOTALAMOUNT"] ?></td>
                                        <td><?= $row["CUSTNAME"] ?></td>
                                        <!-- <td><?= $row["CUSTADDRESS"] ?></td> -->
                                        <td><?= $row["PAYMETHOD"] ?></td>
                                        <td><?= $row["EMPNAME"] ?? 'N/A' ?></td>
                                        <td>
                                            <a href="view_detail.php?id=<?= $row['RECEIPTID'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i> View</a>
                                            <!--<a href="process.php?id=<?= $row['RECEIPTID'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>-->
                                            <!--<a href="process.php?id=<?= $row['RECEIPTID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this receipt?');"><i class="bi bi-trash"></i> Delete</a>-->

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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatableid').DataTable({
                paging: true,
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: false,
                info: true,
                autoWidth: false
            });
        });


    </script>
</body>

</html>