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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

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

        .chart-container {
            width: 100%;
            height: 400px;
            margin-bottom: 2rem;
        }

        canvas {
            background: #fff;
            border-radius: 8px;
            padding: 10px;
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header class="navbar navbar-expand-lg" style="background-color: #1565c0; color: white;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <!--<img src="assets/logo.png" alt="CHAS Logo" style="height: 40px; margin-right: 10px;">-->
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
                        <a class="nav-link" href="dashboard.php"><i class="bi bi-house"></i> Dashboard</a>
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
                        <a class="nav-link active" href="performance.php"><i class="bi bi-bar-chart-line"></i> Performances</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="report.php"><i class="bi bi-clipboard-data"></i> Reports</a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="main-content">
                    <h2 class="section-title">Business Overview</h2>
                    <form id="filterForm" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="monthFilter" class="form-label text-primary">Month</label>
                            <select id="monthFilter" class="form-select" name="month">
                                <option value="">All Months</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="yearFilter" class="form-label text-primary">Year</label>
                            <select id="yearFilter" class="form-select" name="year">
                                <!-- Dynamically populated by JavaScript -->
                            </select>
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                        </div>
                    </form>
                    <div class="chart-container">
                        <h5 class="text-primary">Daily Sales Report</h5>
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                    <br>
                    <div class="chart-container">
                        <h5 class="text-primary">Top Selling Products</h5>
                        <canvas id="topProductChart"></canvas>
                    </div>
                    <br>
                    <div class="chart-container">
                        <h5 class="text-primary">Total Items Purchased by Category</h5>
                        <canvas id="categoryPurchaseChart"></canvas>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Populate year dropdown dynamically
        const yearSelect = document.getElementById('yearFilter');
        const currentYear = new Date().getFullYear();
        for (let year = currentYear; year >= 2022; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        }

        // Define chart references globally to allow destroy
        let dailySalesChart, topProductChart, categoryPurchaseChart;

        function loadCharts(month = '', year = '') {
            // Fetch Daily Sales
            fetch(`php/daily_sales_data.php?month=${month}&year=${year}`)
                .then(res => res.json())
                .then(data => {
                    if (dailySalesChart) dailySalesChart.destroy();
                    dailySalesChart = new Chart(document.getElementById('dailySalesChart'), {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Total Sales (RM)',
                                data: data.sales,
                                borderColor: '#FF7043',
                                backgroundColor: 'rgba(255,112,67,0.2)',
                                fill: true,
                                tension: 0.3,
                                pointBackgroundColor: '#FF7043'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#333'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#333'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#0d47a1'
                                    }
                                }
                            }
                        }
                    });
                });

            fetch(`php/top_product_data.php?month=${month}&year=${year}`)
                .then(res => res.json())
                .then(data => {
                    if (topProductChart) topProductChart.destroy();

                    topProductChart = new Chart(document.getElementById('topProductChart'), {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Total Sales (RM)',
                                data: data.totalRevenue,
                                backgroundColor: '#1E88E5',
                                borderRadius: 5,
                                yAxisID: 'yRevenue'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                yRevenue: {
                                    type: 'linear',
                                    position: 'left',
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Revenue (RM)',
                                        color: '#1E88E5'
                                    },
                                    ticks: {
                                        color: '#1E88E5',
                                        callback: value => 'RM ' + value.toFixed(2)
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#333'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#0d47a1'
                                    }
                                }
                            }
                        }
                    });
                });



            // Fetch Category Purchase
            fetch(`php/category_purchase_data.php?month=${month}&year=${year}`)
                .then(res => res.json())
                .then(data => {
                    if (categoryPurchaseChart) categoryPurchaseChart.destroy(); // Destroy existing chart
                    categoryPurchaseChart = new Chart(document.getElementById('categoryPurchaseChart'), {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Items Purchased',
                                data: data.values,
                                backgroundColor: [
                                    '#1E88E5', '#43A047', '#FB8C00', '#8E24AA',
                                    '#F4511E', '#00ACC1', '#5E35B1', '#FDD835'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        color: '#0d47a1'
                                    }
                                },
                                datalabels: {
                                    color: '#fff',
                                    font: {
                                        weight: 'bold'
                                    },
                                    formatter: (value, context) => {
                                        const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                        const percentage = (value / total * 100).toFixed(1);
                                        return `${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                });
        }

        // On Filter Form Submit
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const month = document.getElementById('monthFilter').value;
            const year = document.getElementById('yearFilter').value;
            loadCharts(month, year);
        });

        // Initial load with no filter
        loadCharts();
    </script>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>