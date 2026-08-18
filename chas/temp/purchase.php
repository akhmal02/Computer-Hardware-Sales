<?php
include("connect.php");
session_start();
$empID = $_SESSION['empID'] ?? 1;

// Fetch employee name
$empQuery = oci_parse($dbconn, "SELECT EMPNAME FROM EMPLOYEE WHERE EMPID = :id");
oci_bind_by_name($empQuery, ":id", $empID);
oci_execute($empQuery);
$empRow = oci_fetch_assoc($empQuery);
$empName = $empRow['EMPNAME'] ?? 'Unknown';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Receipt - CHAS</title>
    <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(to right, #dbe9f4, #b0cde7);
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background-color: #1565c0;
            color: white;
        }

        .navbar-brand,
        .logout-btn {
            color: white !important;
        }

        .sidebar {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            position: sticky;
            top: 0;
            min-height: 100vh;
        }

        .sidebar .bi {
            margin-right: 8px;
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

        .cart-panel {
            position: fixed;
            top: 0;
            right: -400px;
            width: 350px;
            height: 100%;
            background: white;
            z-index: 9999;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.2);
            padding: 1rem;
            overflow-y: auto;
            transition: right 0.3s ease;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h4 text-white">CHAS - Computer Hardware Application System</span>
            <a href="login_auth.php" class="btn btn-light text-dark fw-semibold">Logout</a>
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
                    <li class="nav-item"><a class="nav-link active" href="purchase.php"><i class="bi bi-money"></i> Purchase</a></li>
                </ul>
                <hr class="mx-3 my-2 text-primary">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="report.php"><i class="bi bi-clipboard-data"></i> Reports</a></li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                    <h3 class="text-primary">Product List</h3>
                    <button class="btn btn-primary position-relative" onclick="toggleCart()">
                        <i class="bi bi-cart4"></i> Cart
                        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger" id="cartCount">0</span>
                    </button>
                </div>

                <!-- Category Filter -->
                <div class="mb-4">
                    <label for="categoryFilter" class="form-label fw-semibold">Filter by Category:</label>
                    <select id="categoryFilter" class="form-select w-auto d-inline-block" onchange="filterByCategory()">
                        <option value="All">All</option>
                        <?php
                        $catQuery = "SELECT DISTINCT PRODCATEGORY FROM PRODUCT";
                        $catStmt = oci_parse($dbconn, $catQuery);
                        oci_execute($catStmt);
                        while ($catRow = oci_fetch_array($catStmt, OCI_ASSOC)) {
                            $cat = htmlspecialchars($catRow['PRODCATEGORY']);
                            echo "<option value=\"$cat\">$cat</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Product Grid -->
                <div class="row">
                    <?php
                    $query = "SELECT * FROM PRODUCT";
                    $stid = oci_parse($dbconn, $query);
                    oci_execute($stid);
                    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                    ?>
                        <div class="col-md-4 mb-4 product-card" data-category="<?= htmlspecialchars($row['PRODCATEGORY']) ?>">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $row['PRODNAME'] ?></h5>
                                    <h6 class="card-subtitle mb-2 text-success">RM <?= number_format($row['PRODPRICE'], 2) ?></h6>
                                    <p class="card-text"><?= $row['PRODDESC'] ?></p>
                                    <input type="number" class="form-control mb-2" min="1" value="1" id="qty_<?= $row['PRODID'] ?>">
                                    <button class="btn btn-primary w-100" onclick="addToCart('<?= $row['PRODID'] ?>', '<?= addslashes($row['PRODNAME']) ?>', <?= $row['PRODPRICE'] ?>)">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Cart Panel -->
    <div id="cartPanel" class="cart-panel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Shopping Cart</h5>
            <button class="btn btn-sm btn-outline-secondary" id="closeCartBtn" onclick="toggleCart()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div id="cartItems"></div>
        <hr>
        <div class="fw-bold">Total: RM <span id="cartTotal">0.00</span></div>
        <button class="btn btn-primary w-100 mt-3" onclick="if (prepareCheckoutData()) new bootstrap.Modal(document.getElementById('checkoutModal')).show();">
            Checkout
        </button>

    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-4">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="checkoutModalLabel">Confirm Purchase</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="submit_receipt.php" method="POST" onsubmit="return prepareCheckoutData()">
                    <div class="modal-body row">
                        <div class="col-md-6 mb-3">
                            <label for="receiptDate" class="form-label fw-semibold">Receipt Date</label>
                            <input type="text" class="form-control bg-light" id="receiptDate" name="receiptDate" value="<?= date('Y-m-d') ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="totalAmountField" class="form-label fw-semibold">Total Amount (RM)</label>
                            <input type="text" class="form-control bg-light" id="totalAmountField" name="totalAmount" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payMethod" class="form-label fw-semibold">Payment Method</label>
                            <select name="payMethod" id="payMethod" class="form-select" required>
                                <option value="">Select Payment Method</option>
                                <option value="QR Code">QR Code</option>
                                <option value="Debit">Debit Card</option>
                                <option value="Credit">Credit Credit</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="custName" class="form-label fw-semibold">Customer Name</label>
                            <input type="text" name="custName" id="custName" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="custAddress" class="form-label fw-semibold">Customer Address</label>
                            <textarea name="custAddress" id="custAddress" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="empID" class="form-label fw-semibold">Employee In Charge</label>
                            <select name="empID" id="empID" class="form-select" required>
                                <option value="">Select Employee</option>
                                <?php
                                $empSelectQuery = oci_parse($dbconn, "SELECT EMPID, EMPNAME FROM EMPLOYEE ORDER BY EMPNAME");
                                oci_execute($empSelectQuery);
                                while ($empRow = oci_fetch_assoc($empSelectQuery)) {
                                    $selected = ($empRow['EMPID'] == $empID) ? 'selected' : '';
                                    echo "<option value=\"{$empRow['EMPID']}\" $selected>{$empRow['EMPNAME']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Checkout Summary</label>
                            <div class="border rounded p-3 bg-light" id="checkoutSummary" style="min-height: 100px;"></div>
                        </div>
                    </div>
                    <input type="hidden" name="cartData" id="cartData">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirm Purchase</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = {};

        function toggleCart() {
            const panel = document.getElementById("cartPanel");
            panel.style.right = panel.style.right === "0px" ? "-400px" : "0px";
        }

        function filterByCategory() {
            const selected = document.getElementById("categoryFilter").value;
            const cards = document.querySelectorAll(".product-card");
            cards.forEach(card => {
                const category = card.getAttribute("data-category");
                card.style.display = (selected === "All" || category === selected) ? "block" : "none";
            });
        }

        function addToCart(id, name, price) {
            const qty = parseInt(document.getElementById("qty_" + id).value);
            if (!qty || qty <= 0) return alert("Invalid quantity");
            if (cart[id]) cart[id].qty += qty;
            else cart[id] = {
                name,
                price,
                qty
            };
            updateCartUI();
            alert(`${name} added (${qty})`);
        }

        function removeFromCart(id) {
            delete cart[id];
            updateCartUI();
        }

        function updateCartUI() {
            const cartItems = document.getElementById("cartItems");
            const cartTotal = document.getElementById("cartTotal");
            const cartCount = document.getElementById("cartCount");
            let totalQty = 0,
                total = 0;
            cartItems.innerHTML = "";
            for (let id in cart) {
                const item = cart[id];
                const subtotal = item.qty * item.price;
                total += subtotal;
                totalQty += item.qty;
                cartItems.innerHTML += `
                    <div class="border-bottom mb-2 pb-2">
                        <strong>${item.name}</strong>
                        <div>Qty: ${item.qty} | RM ${item.price.toFixed(2)}</div>
                        <div class="text-muted">Subtotal: RM ${subtotal.toFixed(2)}</div>
                        <button class="btn btn-sm btn-outline-danger mt-1" onclick="removeFromCart('${id}')">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>`;
            }
            cartTotal.textContent = total.toFixed(2);
            cartCount.textContent = totalQty;
        }

        function prepareCheckoutData() {
            const cartDataField = document.getElementById("cartData");
            const totalAmountField = document.getElementById("totalAmountField");
            const summaryDiv = document.getElementById("checkoutSummary");

            let total = 0;
            let html = "";
            const cartArray = [];

            for (let id in cart) {
                const item = cart[id];
                let subtotal = item.qty * item.price;
                total += subtotal;
                html += `<div>${item.name} - ${item.qty} x RM${item.price.toFixed(2)} = RM${subtotal.toFixed(2)}</div>`;
                cartArray.push({
                    prodID: id,
                    qty: item.qty,
                    price: item.price
                });
            }

            if (cartArray.length === 0) {
                alert("Cart is empty!");
                return false;
            }

            summaryDiv.innerHTML = html + `<hr><strong>Total: RM ${total.toFixed(2)}</strong>`;
            totalAmountField.value = total.toFixed(2);
            cartDataField.value = JSON.stringify(cartArray);
            return true;
        }
    </script>
</body>

</html>