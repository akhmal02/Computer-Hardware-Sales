<?php
include("../connect.php");

if (isset($_GET['id'])) {
    $receiptID = $_GET['id'];

    $sql = "SELECT rp.RECPRODID, rp.QTY, rp.PRICE, p.PRODNAME
            FROM RECEIPT_PRODUCT rp
            JOIN PRODUCT p ON rp.PRODID = p.PRODID
            WHERE rp.RECEIPTID = :receiptID";

    $stid = oci_parse($dbconn, $sql);
    oci_bind_by_name($stid, ":receiptID", $receiptID);
    oci_execute($stid);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt Product Details - CHAS</title>
    <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(to right, #dbe9f4, #b0cde7);
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .main-content {
            background: rgba(255, 255, 255, 0.75);
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

        .table thead {
            background-color: #1565c0;
            color: white;
        }

        .btn-back {
            background-color: #1e88e5;
            color: white;
        }

        .btn-back:hover {
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
    <div class="container mt-5">
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title"><i class="bi bi-eye"></i> Product Details for Receipt ID: <?= htmlspecialchars($receiptID) ?></h2>
                <a href="view_receipt.php" class="btn btn-back"><i class="bi bi-arrow-left-circle"></i> Back</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price per Unit (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . $count++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['PRODNAME']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['QTY']) . "</td>";
                            echo "<td>" . number_format($row['PRICE'], 2) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    oci_free_statement($stid);
    oci_close($dbconn);
} else {
    echo "<p>Invalid receipt ID.</p>";
}
?>
