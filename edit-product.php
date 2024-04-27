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

<style>
    .no_print>tbody:nth-child(1)>tr:nth-child(1)>td:nth-child(2)>input:nth-child(1) {
        padding: 10px;
        border-radius: 6%;
        border: 1px solid darkblue;
    }
</style>

<!-- Main Content Wrapper -->
<main class="main-content w-full px-5 pb-8">
    <?php
    // Check if product ID is provided in the URL
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];

        // Fetch product details from the database
        $sql = "SELECT * FROM stock WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['name'];
            $type = $row['type'];
            $price = $row['price'];
            $weight = $row['weight'];
            $stn_weight = $row['stn_weight'];
            $stn_rate = $row['stn_rate'];
            $qty = $row['qty'];
            $carat = $row['carat'];
        } else {
            echo "<script>alert('Product not found!');window.location.href='./stock.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Invalid request!');window.location.href='./stock.php';</script>";
        exit;
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

        // Update product details in the database
        $sql = "UPDATE stock SET name=?, type=?, price=?, weight=?, qty=?, carat=?, stn_rate=?, stn_weight=? WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssdddddii", $name, $type, $price, $weight, $qty, $carat, $stn_rate, $stn_weight, $product_id);

        if ($stmt->execute()) {
            echo "<script>alert('Product updated successfully!');window.location.href='./stock.php';</script>";
        } else {
            echo "<script>alert('Failed to update product!');</script>";
        }
    }
    ?>

    <div class="container">
        <h1 class="well">Edit Product</h1>
        <div class="col-lg-12 well">
            <div class="row">
                <form action="" method="POST">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Product Name</label>
                                <input type="text" name="name" value="<?php echo $name; ?>" class="form-control">
                            </div>
                            <div class="col-sm-12 form-group">
                                <label>Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option <?= $type == "gold" ? "selected" : "" ?> value="gold">Gold</option>
                                    <option <?= $type == "gold_20k" ? "selected" : "" ?> value="gold_20k">Gold 20K</option>
                                    <option <?= $type == "gold_22k" ? "selected" : "" ?> value="gold_22k">Gold 22K</option>
                                    <option <?= $type == "silver" ? "selected" : "" ?> value="silver">Silver</option>
                                    <option <?= $type == "silver_925" ? "selected" : "" ?> value="silver_925">Silver 925</option>
                                    <option <?= $type == "diamond" ? "selected" : "" ?> value="diamond">Diamond</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Carat</label>
                            <input type="number" name="carat" value="<?php echo $carat; ?>" class="form-control">
                        </div>
                        <!-- <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" value="<?php echo $price; ?>" class="form-control">
                        </div> -->
                        <div class="form-group">
                            <label>Net Weight</label>
                            <input type="text" name="weight" value="<?php echo $weight; ?>" class="form-control">
                        </div>
                        <!-- <div class="form-group">
                            <label>Stone Weight</label>
                            <input type="text" name="stone_weight" value="<?php echo $stn_weight; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Stone Rate</label>
                            <input type="text" name="stone_rate" value="<?php echo $stn_rate; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="qty" value="<?php echo $qty; ?>" class="form-control">
                        </div> -->

                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-success my-2">Update</button>
                            <a href="./stock.php" class="btn btn-lg btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>
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