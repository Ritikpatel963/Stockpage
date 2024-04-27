<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['getDetails'])) {
    $id = $_POST['id'];

    $productResult = $db->query("SELECT * FROM stock WHERE name='$id'");
    if ($productResult->num_rows > 0) {
        $productData = $productResult->fetch_assoc();

        echo json_encode($productData);
    }
}

include 'navbar.php';
?>

<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<style>
    .no_print>tbody:nth-child(1)>tr:nth-child(1)>td:nth-child(2)>input:nth-child(1) {
        padding: 10px;
        border-radius: 6%;
        border: 1px solid darkblue;
    }
</style>

<!-- Main Content Wrapper -->
<main class="main-content w-full px-5 pb-8">
    <div class="container py-3">
        <h2>Purchased History</h2>
        <div class="card shadow p-3">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Product Type</th>
                        <th>Carat</th>
                        <th>Purchased Weight</th>
                        <th>Purchased Cost</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch products from the database
                    $sql = "SELECT ph.*, s.name, s.type, s.carat FROM purchase_history ph INNER JOIN stock s ON s.id = ph.product_id";
                    $result = $db->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . ucfirst($row["type"]) . "</td>";
                            echo "<td>" . ($row["carat"] != 0 ? $row["carat"] . "K" : "N/A") . "</td>";
                            echo "<td>" . $row["weight"] . "gm</td>";
                            echo "<td>Rs. " . (($row["cost"] != "" || $row["cost"] != 0) ? number_format($row["cost"], 2) : "N/A") . "</td>";
                            echo "<td>" . date("d-m-Y H:i", strtotime($row["created_at"])) . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</div>

<script>
    window.addEventListener("DOMContentLoaded", () => Alpine.start());
</script>
<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('table').DataTable();
    });
</script>

</body>

</html>