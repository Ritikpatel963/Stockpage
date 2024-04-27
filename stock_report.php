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

<link href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>

<link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css" rel="stylesheet">
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

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
        <h2>Stock Report</h2>
        <div class="card shadow p-3">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Product Name</th>
                        <th>Carat</th>
                        <th>Net Weight</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch products from the database
                    $sql = "SELECT * FROM stock ORDER BY type DESC";
                    $result = $db->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . ucfirst($row["type"]) . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["carat"] . "</td>";
                            echo "<td>" . $row["weight"] . "gm</td>";
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
        new DataTable('table', {
            layout: {
                topStart: {
                    buttons: ['csv', 'excel', 'print']
                }
            }
        });
    });
</script>

</body>

</html>