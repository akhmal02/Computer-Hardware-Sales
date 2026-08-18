<?php
session_start();
include("connect.php"); // Oracle connection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: linear-gradient(to right, #dbe9f4, #b0cde7);
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .navbar {
            background-color: rgba(33, 150, 243, 0.8);
            backdrop-filter: blur(10px);
        }

        .navbar-brand,
        .logout-btn {
            color: #fff;
        }

        .logout-btn:hover {
            background-color: #f44336;
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

        .main-content {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .chart-container {
            width: 100%;
            margin-bottom: 2rem;
        }

        .section-title {
            color: #0d47a1;
            margin-bottom: 1rem;
        }

        .sidebar .bi {
            margin-right: 8px;
        }

        table {
            background: #fff;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <header class="navbar navbar-expand-lg" style="background-color: #1565c0;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h4 text-white">CHAS - Computer Hardware Application System</span>
            <a href="logout.php" class="btn btn-light text-dark fw-semibold">Logout</a>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar py-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-house"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="employee/view_emp.php"><i class="bi bi-people"></i> Employees</a></li>
                    <li class="nav-item"><a class="nav-link" href="product/view_prod.php"><i class="bi bi-box"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="supplier/view_supplier.php"><i class="bi bi-truck"></i> Suppliers</a></li>
                    <li class="nav-item"><a class="nav-link" href="receipt/view_receipt.php"><i class="bi bi-receipt"></i> Receipts</a></li>
                    <li class="nav-item"><a class="nav-link" href="receipt/purchase.php"><i class="bi bi-basket"></i> Purchase</a></li>
                </ul>
                <hr class="mx-3 my-2 text-primary">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="performance.php"><i class="bi bi-bar-chart-line"></i> Performances</a></li>
                    <li class="nav-item"><a class="nav-link active" href="report.php"><i class="bi bi-clipboard-data"></i> Reports</a></li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="main-content">
                    <h2 class="section-title">Business Report Overview</h2>

                    <?php
                    $queries = [
                        "Track Employee Performance in Issuing Receipts" =>
                        "SELECT e.empName, COUNT(r.receiptID) AS \"Total Receipts\", SUM(r.totalAmount) AS \"Total Sales\" 
                         FROM EMPLOYEE e 
                         JOIN RECEIPT r ON e.empID = r.empID 
                         GROUP BY e.empName 
                         ORDER BY \"Total Receipts\" DESC",

                        "How Long Each Employee Has Been Working" =>
                        "SELECT empID, empName, empHireDate, TRUNC(MONTHS_BETWEEN(SYSDATE, empHireDate) / 12, 0) AS \"Years Worked\" 
                         FROM EMPLOYEE 
                         ORDER BY empHireDate",

                        "Products Never Sold" =>
                        "SELECT prodID, prodName 
                         FROM PRODUCT 
                         MINUS 
                         SELECT p.prodID, p.prodName 
                         FROM PRODUCT p 
                         JOIN RECEIPT_PRODUCT rp ON p.prodID = rp.prodID",

                        "Average Spending per Receipt by Payment Method" =>
                        "SELECT payMethod, COUNT(receiptID) AS total_receipts, 
                                SUM(totalAmount) AS total_revenue, 
                                ROUND(AVG(totalAmount),2) AS avg_receipt_value 
                         FROM RECEIPT 
                         GROUP BY payMethod 
                         ORDER BY avg_receipt_value DESC",

                        "List of International Suppliers with Country" =>
                        "SELECT s.supName, i.country 
                         FROM SUPPLIER s 
                         LEFT OUTER JOIN INTERNATIONAL i ON s.supID = i.supID 
                         WHERE s.supType = 'International' 
                         ORDER BY i.country, s.supName",

                        "Count of Products Supplied by Each Supplier" =>
                        "SELECT s.supName, COUNT(ps.prodID) AS product_count 
                         FROM SUPPLIER s 
                         JOIN PRODUCT_SUPPLIER ps USING (supID) 
                         GROUP BY s.supName 
                         ORDER BY product_count DESC",

                        "Supervisor Team Total Sales" =>
                        "SELECT s.empName AS supervisor_name, SUM(r.totalAmount) AS total_team_sales 
                         FROM EMPLOYEE e 
                         JOIN RECEIPT r ON e.empID = r.empID 
                         JOIN EMPLOYEE s ON e.supervisorID = s.empID 
                         GROUP BY s.empName 
                         ORDER BY total_team_sales DESC",

                        "Top Spending Customers" =>
                        "SELECT custName, SUM(totalAmount) AS total_spent 
                         FROM RECEIPT 
                         GROUP BY custName 
                         ORDER BY total_spent DESC"
                    ];

                    foreach ($queries as $title => $sql) {
                        echo "<div class='chart-container'>";
                        echo "<h5 class='text-primary'>SQL: $title</h5>";
                        echo "<table class='table table-bordered table-striped'><thead><tr>";

                        // Parse and execute query
                        $stid = oci_parse($dbconn, $sql);
                        oci_execute($stid);

                        // Get column names
                        $ncols = oci_num_fields($stid);
                        for ($i = 1; $i <= $ncols; $i++) {
                            $colname = oci_field_name($stid, $i);
                            echo "<th>$colname</th>";
                        }
                        echo "</tr></thead><tbody>";

                        // Display data
                        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                            echo "<tr>";
                            foreach ($row as $val) {
                                echo "<td>" . htmlspecialchars($val !== null ? $val : '-') . "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</tbody></table></div>";
                        oci_free_statement($stid);
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>