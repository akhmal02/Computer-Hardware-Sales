<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #dbe9f4, #b0cde7);
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .navbar {
            background-color: #1565c0;
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
            transition: background-color 0.3s;
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

        .summary-box h6 {
            font-size: 0.9rem;
        }

        .summary-box {
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .summary-box:hover {
            transform: translateY(-5px);
        }

        .summary-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .text-shadow {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="navbar-brand mb-0 h4 text-white">CHAS - Computer Hardware Application System</span>
            </div>
            <a href="logout.php" class="btn btn-light text-dark fw-semibold">Logout</a>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar py-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php"><i class="bi bi-house"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="employee/view_emp.php"><i class="bi bi-people"></i> Employees</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="product/view_prod.php"><i class="bi bi-box"></i> Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="supplier/view_supplier.php"><i class="bi bi-truck"></i> Suppliers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="receipt/view_receipt.php"><i class="bi bi-receipt"></i> Receipts</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="receipt/purchase.php"><i class="bi bi-basket"></i> Purchase</a></li>
                </ul>

                <!-- Line strip & Report Section -->
                <hr class="mx-3 my-2 text-primary">

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="performance.php"><i class="bi bi-bar-chart-line"></i> Performances</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="report.php"><i class="bi bi-clipboard-data"></i> Reports</a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="main-content">
                    <h2 class="section-title">Dashboard Overview</h2>
                    <div class="row g-4" id="summary-boxes">
                        <!-- Dynamic summary boxes -->
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Dynamic Script -->
    <script>
        fetch('php/summary_data.php')
            .then(res => res.json())
            .then(data => {
                const summaryBoxes = [{
                        label: "Total Products",
                        value: data.total_products,
                        color: "info",
                        icon: "inventory_2",
                        link: "product/view_prod.php"
                    },
                    {
                        label: "Total Suppliers",
                        value: data.total_suppliers,
                        color: "dark",
                        icon: "local_shipping",
                        link: "supplier/view_supplier.php"
                    },
                    {
                        label: "Total Staff",
                        value: data.total_staff,
                        color: "success",
                        icon: "groups",
                        link: "employee/view_emp.php"
                    },
                    {
                        label: "Today's Receipts",
                        value: data.today_receipts,
                        color: "primary",
                        icon: "receipt_long",
                        link: "receipt/view_receipt.php"
                    },

                ];

                const container = document.getElementById('summary-boxes');
                summaryBoxes.forEach(box => {
                    container.innerHTML += `
                    <div class="col-md-3">
                        <div class="summary-box">
                            <span class="material-icons-outlined text-${box.color} summary-icon">${box.icon}</span>
                            <h6 class="text-secondary">${box.label}</h6>
                            <p class="display-6 fw-semibold text-${box.color} text-shadow">${box.value}</p>
                            <a href="${box.link}" class="text-decoration-none text-${box.color}">View →</a>
                        </div>
                    </div>
                `;
                });
            });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>