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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $type = $_POST['type'];
    $price = $_POST['price'] ?? null;
    $weight = $_POST['weight'];
    $stn_weight = $_POST['stone_weight'] ?? null;
    $stn_rate = $_POST['stone_rate'] ?? null;
    $qty = $_POST['qty'] ?? null;
    $carat = $_POST['carat'];

    // Prepare insert statement
    $sql = "INSERT INTO stock (name, type, price, weight, qty, carat, stn_weight, stn_rate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and execute the statement
    if ($stmt = $db->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssdddidd", $name, $type, $price, $weight, $qty, $carat, $stn_weight, $stn_rate);

        // Execute the statement
        if ($stmt->execute()) {
            // Get form data
            $productId = $db->insert_id;

            // Prepare and execute the SQL query
            $stmt = $db->prepare("INSERT INTO purchase_history (product_id, weight) VALUES (?, ?)");
            $stmt->bind_param("id", $productId, $weight);
            $stmt->execute();

            // Close statement and database connection
            $stmt->close();

            echo "<script>alert('Product Added Successfully!');location.href='./stock.php';</script>";
        } else {
            echo "<script>alert('Server Error!');location.href='./stock.php';</script>";
        }

        // Close statement
        $stmt->close();
    }
}

include 'navbar.php';
?>

<style>
    .no_print>tbody:nth-child(1)>tr:nth-child(1)>td:nth-child(2)>input:nth-child(1) {
        padding: 10px;
        border-radius: 6%;
        border: 1px solid darkblue;
    }
</style>

<!-- Main Content Wrapper -->
<main class="main-content w-full px-5 pb-8">
    <div class="container">
        <h1 class="well">Add Product</h1>
        <div class="col-lg-12 well">
            <div class="row">
                <form action="" method="POST">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Product Name</label>
                                <input type="text" name="name" placeholder="Enter Product Name Here.." class="form-control">
                            </div>
                            <div class="col-sm-12 form-group">
                                <label>Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="gold">Gold</option>
                                    <option value="gold_20k">Gold 20K</option>
                                    <option value="gold_22k">Gold 22K</option>
                                    <option value="silver">Silver</option>
                                    <option value="silver_925">Silver 925</option>
                                    <option value="diamond">Diamond</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Carat</label>
                            <input type="number" name="carat" placeholder="Enter Product Carat Here.." class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Net Weight</label>
                            <input type="number" name="weight" placeholder="Enter Product Weight Here.." class="form-control">
                        </div>

                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-success my-2">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div </main>
    </div>
    <!-- 
        This is a place for Alpine.js Teleport feature 
        @see https://alpinejs.dev/directives/teleport
      -->
    <div id="x-teleport-target"></div>

    <!-- Right Sidebar -->
    <script>
        window.addEventListener("DOMContentLoaded", () => Alpine.start());
    </script>
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>