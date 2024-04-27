<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['getDetails'])) {
    $my_host = 'localhost';
    $my_username = 'root';
    $my_pass = '';
    $my_db_name = 'narvariya';

    $db = mysqli_connect($my_host, $my_username, $my_pass, $my_db_name) or die("cannot connect to server");

    $id = $_POST['type'];

    $productResult = $db->query("SELECT * FROM stock WHERE type='$id'");
    if ($productResult->num_rows > 0) {
        echo '<option value="" disabled selected>-- SELECT --</option>';
        foreach ($productResult as $product) {
            echo "<option value='{$product['name']}'>{$product['name']}</option>";
        }
    }
    exit();
}
?>

<?php include_once('../connect.php'); ?>
<?php include 'navbar.php'; ?>

<style>
    .slider-container {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .slider {
        display: flex;
        transition: transform 0.5s ease;
    }

    .card-container {
        display: flex;
        flex-wrap: nowrap;
        /* Prevent cards from wrapping */
    }

    .card {
        flex: 0 0 calc(25% - 20px);
        /* Set width for 4 cards with margin */
        margin-right: 20px;
        box-sizing: border-box;
        /* Include padding and border in the width */
    }

    .prev,
    .next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgb(21 13 101 / 66%);
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px;
        z-index: 1;
    }

    .prev {
        left: 0;
    }

    .next {
        right: 0;
    }
</style>

<!-- Main Content Wrapper -->
<main class="main-content w-full px-[var(--margin-x)] pb-8">
    <div class="container mt-3">
        <div class="row mt-5">
            <div class="col-md-8">
                <h3>Manage Stock</h3>
            </div>
            <div class="col-md-4 d-flex" style="gap:5px">
                <a href="add-product.php" class="btn btn-sm btn-danger my-2">Add New Product +</a>
                <a href="purchase_history.php" class="btn btn-sm btn-success my-2">Purchase History</a>
                <a href="stock_report.php" class="btn btn-sm btn-warning my-2">Stock Report</a>
            </div>
        </div>
        <div class="row mt-5" style="gap: 50px;">
            <h2 style="font-size: 40px;font-family: initial;font-weight: 900;color: darkblue;">Gold (<?= $db->query("SELECT SUM(weight) as total_weight FROM stock WHERE type='gold'")->fetch_assoc()['total_weight'] ?? 0 ?>gm.)</h2>
            <?php
            $products = $db->query("SELECT * FROM stock WHERE type='gold'");
            if ($products->num_rows > 0) {
            ?>
                <div class="slider-container">
                    <div class="slider" data-type="gold">
                        <?php foreach ($products as $product) { ?>
                            <div class="card" data-type="gold">
                                <!-- <img src="uploads/<= $product['image'] ?>" class="card-img-top" alt=""> -->
                                <div class="card-body">
                                    <div class="py-4">
                                        <h1 style="font-size: 18px;" class="my-2">Product Name: <?= $product['name'] ?></h1>
                                        <h2 style="font-size: 18px;" class="my-2">Carat: <?= ($product["carat"] != 0 ? $product["carat"] . "K" : "N/A") ?></h2>
                                        <h2 style="font-size: 18px;" class="my-2">Weight: <?= $product['weight'] ?>gm.</h2>

                                    </div>
                                    <a onclick="return pass();" href="edit-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Edit</a>
                                    <a onclick="return pass();" href="delet-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Delete</a>
                                    <button onclick="return pass();" type="button" data-product-id="<?= $product['id'] ?>" class="add-purchase-btn btn btn-primary" style="background-color: 211360;">Purchase</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <button class="prev" onclick="prevSlide('gold')">&#10094;</button>
                    <button class="next" onclick="nextSlide('gold')">&#10095;</button>
                </div>
            <?php
            } else {
            ?>
                <p>No products Found!</p>
            <?php
            }
            ?>
        </div>
        <div class="row mt-5" style="gap: 50px;">
            <h2 style="font-size: 40px;font-family: initial;font-weight: 900;color: darkblue;">Gold 20K (<?= $db->query("SELECT SUM(weight) as total_weight FROM stock WHERE type='gold_20k'")->fetch_assoc()['total_weight'] ?? 0 ?>gm.)</h2>
            <?php
            $products = $db->query("SELECT * FROM stock WHERE type='gold_20k'");
            if ($products->num_rows > 0) {
            ?>
                <div class="slider-container">
                    <div class="slider" data-type="gold_20k">
                        <?php foreach ($products as $product) { ?>
                            <div class="card" data-type="gold_20k">
                                <!-- <img src="uploads/<= $product['image'] ?>" class="card-img-top" alt=""> -->
                                <div class="card-body">
                                    <div class="py-4">
                                        <h1 style="font-size: 18px;" class="my-2">Product Name: <?= $product['name'] ?></h1>
                                        <h2 style="font-size: 18px;" class="my-2">Carat: <?= ($product["carat"] != 0 ? $product["carat"] . "K" : "N/A") ?></h2>
                                        <h2 style="font-size: 18px;" class="my-2">Weight: <?= $product['weight'] ?>gm.</h2>

                                    </div>
                                    <a onclick="return pass();" href="edit-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Edit</a>
                                    <a onclick="return pass();" href="delet-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Delete</a>
                                    <button onclick="return pass();" type="button" data-product-id="<?= $product['id'] ?>" class="add-purchase-btn btn btn-primary" style="background-color: 211360;">Purchase</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <button class="prev" onclick="prevSlide('gold_20k')">&#10094;</button>
                    <button class="next" onclick="nextSlide('gold_20k')">&#10095;</button>
                </div>
            <?php
            } else {
            ?>
                <p>No products Found!</p>
            <?php
            }
            ?>
        </div>
        <div class="row mt-5" style="gap: 50px;">
            <h2 style="font-size: 40px;font-family: initial;font-weight: 900;color: darkblue;">Gold 22K (<?= $db->query("SELECT SUM(weight) as total_weight FROM stock WHERE type='gold_22k'")->fetch_assoc()['total_weight'] ?? 0 ?>gm.)</h2>
            <?php
            $products = $db->query("SELECT * FROM stock WHERE type='gold_22k'");
            if ($products->num_rows > 0) {
            ?>
                <div class="slider-container">
                    <div class="slider" data-type="gold_22k">
                        <?php foreach ($products as $product) { ?>
                            <div class="card" data-type="gold_22k">
                                <!-- <img src="uploads/<= $product['image'] ?>" class="card-img-top" alt=""> -->
                                <div class="card-body">
                                    <div class="py-4">
                                        <h1 style="font-size: 18px;" class="my-2">Product Name: <?= $product['name'] ?></h1>
                                        <h2 style="font-size: 18px;" class="my-2">Carat: <?= ($product["carat"] != 0 ? $product["carat"] . "K" : "N/A") ?></h2>
                                        <h2 style="font-size: 18px;" class="my-2">Weight: <?= $product['weight'] ?>gm.</h2>

                                    </div>
                                    <a onclick="return pass();" href="edit-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Edit</a>
                                    <a onclick="return pass();" href="delet-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Delete</a>
                                    <button onclick="return pass();" type="button" data-product-id="<?= $product['id'] ?>" class="add-purchase-btn btn btn-primary" style="background-color: 211360;">Purchase</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <button class="prev" onclick="prevSlide('gold_22k')">&#10094;</button>
                    <button class="next" onclick="nextSlide('gold_22k')">&#10095;</button>
                </div>
            <?php
            } else {
            ?>
                <p>No products Found!</p>
            <?php
            }
            ?>
        </div>
        <div class="row my-5" style="gap: 50px;">
            <h2 style="font-size: 40px;font-family: initial;font-weight: 900;color: darkblue;">Silver (<?= $db->query("SELECT SUM(weight) as total_weight FROM stock WHERE type='silver'")->fetch_assoc()['total_weight'] ?? 0 ?>gm.)</h2>
            <?php
            $products = $db->query("SELECT * FROM stock WHERE type='silver'");
            if ($products->num_rows > 0) {
            ?>
                <div class="slider-container">
                    <div class="slider" data-type="silver">
                        <?php foreach ($products as $product) { ?>
                            <div class="card" data-type="silver">
                                <img src="uploads/<?= $product['image'] ?>" class="card-img-top" alt="">
                                <div class="card-body">
                                    <div class="py-4">
                                        <h1 style="font-size: 18px;" class="my-2">Product Name: <?= $product['name'] ?></h1>
                                        <h2 style="font-size: 18px;" class="my-2">Carat: <?= ($product["carat"] != 0 ? $product["carat"] . "K" : "N/A") ?></h2>
                                        <h2 style="font-size: 18px;" class="my-2">Weight: <?= $product['weight'] ?>gm.</h2>

                                    </div>
                                    <a onclick="return pass();" href="edit-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Edit</a>
                                    <a onclick="return pass();" href="delet-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Delete</a>
                                    <button onclick="return pass();" type="button" data-product-id="<?= $product['id'] ?>" class="add-purchase-btn btn btn-primary" style="background-color: 211360;">Purchase</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <button class="prev" onclick="prevSlide('silver')">&#10094;</button>
                    <button class="next" onclick="nextSlide('silver')">&#10095;</button>
                </div>
            <?php
            } else {
            ?>
                <p>No products Found!</p>
            <?php
            }
            ?>
        </div>
        <div class="row my-5" style="gap: 50px;">
            <h2 style="font-size: 40px;font-family: initial;font-weight: 900;color: darkblue;">Silver 925 (<?= $db->query("SELECT SUM(weight) as total_weight FROM stock WHERE type='silver_925'")->fetch_assoc()['total_weight'] ?? 0 ?>gm.)</h2>
            <?php
            $products = $db->query("SELECT * FROM stock WHERE type='silver_925'");
            if ($products->num_rows > 0) {
            ?>
                <div class="slider-container">
                    <div class="slider" data-type="silver_925">
                        <?php foreach ($products as $product) { ?>
                            <div class="card" data-type="silver_925">
                                <img src="uploads/<?= $product['image'] ?>" class="card-img-top" alt="">
                                <div class="card-body">
                                    <div class="py-4">
                                        <h1 style="font-size: 18px;" class="my-2">Product Name: <?= $product['name'] ?></h1>
                                        <h2 style="font-size: 18px;" class="my-2">Carat: <?= ($product["carat"] != 0 ? $product["carat"] . "K" : "N/A") ?></h2>
                                        <h2 style="font-size: 18px;" class="my-2">Weight: <?= $product['weight'] ?>gm.</h2>

                                    </div>
                                    <a onclick="return pass();" href="edit-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Edit</a>
                                    <a onclick="return pass();" href="delet-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Delete</a>
                                    <button onclick="return pass();" type="button" data-product-id="<?= $product['id'] ?>" class="add-purchase-btn btn btn-primary" style="background-color: 211360;">Purchase</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <button class="prev" onclick="prevSlide('silver_925')">&#10094;</button>
                    <button class="next" onclick="nextSlide('silver_925')">&#10095;</button>
                </div>
            <?php
            } else {
            ?>
                <p>No products Found!</p>
            <?php
            }
            ?>
        </div>
        <div class="row my-5" style="gap: 50px;">
            <h2 style="font-size: 40px;font-family: initial;font-weight: 900;color: darkblue;">Diamond (<?= $db->query("SELECT SUM(weight) as total_weight FROM stock WHERE type='diamond'")->fetch_assoc()['total_weight'] ?? 0 ?>gm.)</h2>
            <?php
            $products = $db->query("SELECT * FROM stock WHERE type='diamond'");
            if ($products->num_rows > 0) {
            ?>
                <div class="slider-container">
                    <div class="slider" data-type="diamond">
                        <?php foreach ($products as $product) { ?>
                            <div class="card" data-type="diamond">
                                <img src="uploads/<?= $product['image'] ?>" class="card-img-top" alt="">
                                <div class="card-body">
                                    <div class="py-4">
                                        <h1 style="font-size: 18px;" class="my-2">Product Name: <?= $product['name'] ?></h1>
                                        <h2 style="font-size: 18px;" class="my-2">Carat: <?= ($product["carat"] != 0 ? $product["carat"] . "K" : "N/A") ?></h2>
                                        <h2 style="font-size: 18px;" class="my-2">Weight: <?= $product['weight'] ?>gm.</h2>

                                    </div>
                                    <a onclick="return pass();" href="edit-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Edit</a>
                                    <a onclick="return pass();" href="delet-product.php?product_id=<?= $product['id'] ?>" class="btn btn-primary" style="background-color: 211360;">Delete</a>
                                    <button onclick="return pass();" type="button" data-product-id="<?= $product['id'] ?>" class="add-purchase-btn btn btn-primary" style="background-color: 211360;">Purchase</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <button class="prev" onclick="prevSlide('diamond')">&#10094;</button>
                    <button class="next" onclick="nextSlide('diamond')">&#10095;</button>
                </div>
            <?php
            } else {
            ?>
                <p>No products Found!</p>
            <?php
            }
            ?>
        </div>
    </div>

    <div id="addPurchaseModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="submitPurchase.php" method="post">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add Purchase</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Weight (in GM.):</label>
                            <input type="number" name="weight" class="form-control" placeholder="Enter purchased weight in grams.">
                        </div>
                        <div class="form-group">
                            <label>Cost:</label>
                            <input type="text" name="cost" class="form-control" placeholder="Enter purchased cost">
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <input type="hidden" name="product_id" value="" id="prod_id">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.add-purchase-btn').click(function() {
                var productId = $(this).data('product-id');
                $('#prod_id').val(productId);

                $('#addPurchaseModal').modal('show')
            });
        });

        function pass() {
            var pass = prompt("Please enter your Password:");
            if (pass == 'Markiv') {
                return true;
            } else {
                alert('INCORRECT PASSWORD');
                return false;
            }
        }

        let slideIndexGold = 0;
        let slideIndexGold20k = 0;
        let slideIndexGold22k = 0;
        let slideIndexSilver = 0;
        let slideIndexsilver_925 = 0;
        let slideIndexDiamond = 0;

        const cardsGold = document.querySelectorAll('.card[data-type="gold"]');
        const cardsGold20k = document.querySelectorAll('.card[data-type="gold_20k"]');
        const cardsGold22k = document.querySelectorAll('.card[data-type="gold_22k"]');
        const cardsSilver = document.querySelectorAll('.card[data-type="silver"]');
        const cardssilver_925 = document.querySelectorAll('.card[data-type="silver_925"]');
        const cardsDiamond = document.querySelectorAll('.card[data-type="diamond"]');

        const totalSlidesGold = cardsGold.length;
        const totalSlidesGold20k = cardsGold.length;
        const totalSlidesGold22k = cardsGold.length;
        const totalSlidesSilver = cardsSilver.length;
        const totalSlidessilver_925 = cardssilver_925.length;
        const totalSlidesDiamond = cardsDiamond.length;

        function showSlide(index, type) {
            const slider = document.querySelector(`.slider[data-type="${type}"]`);
            const cardWidth = document.querySelector(`.card[data-type="${type}"]`).offsetWidth; // Assuming all cards have the same width
            slider.style.transform = `translateX(-${index * cardWidth}px)`;
        }

        function nextSlide(type) {
            if (type === 'gold') {
                slideIndexGold = Math.min(slideIndexGold + 4, totalSlidesGold - 4);
                showSlide(slideIndexGold, 'gold');
            } else if (type === 'gold_20k') {
                slideIndexGold20k = Math.min(slideIndexGold20k + 4, totalSlidesGold20k - 4);
                showSlide(slideIndexGold20k, 'gold_20k');
            } else if (type === 'gold_22k') {
                slideIndexGold22k = Math.min(slideIndexGold22k + 4, totalSlidesGold22k - 4);
                showSlide(slideIndexGold22k, 'gold_22k');
            } else if (type === 'silver') {
                slideIndexSilver = Math.min(slideIndexSilver + 4, totalSlidesSilver - 4);
                showSlide(slideIndexSilver, 'silver');
            } else if (type === 'silver_925') {
                slideIndexsilver_925 = Math.min(slideIndexsilver_925 + 4, totalSlidessilver_925 - 4);
                showSlide(slideIndexsilver_925, 'silver_925');
            } else if (type === 'diamond') {
                slideIndexDiamond = Math.min(slideIndexDiamond + 4, totalSlidesDiamond - 4);
                showSlide(slideIndexDiamond, 'diamond');
            }
        }

        function prevSlide(type) {
            if (type === 'gold') {
                slideIndexGold = Math.max(slideIndexGold - 4, 0);
                showSlide(slideIndexGold, 'gold');
            } else if (type === 'gold_20k') {
                slideIndexGold20k = Math.min(slideIndexGold - 4, 0);
                showSlide(slideIndexGold20k, 'gold_20k');
            } else if (type === 'gold_22k') {
                slideIndexGold22k = Math.min(slideIndexGold - 4, 0);
                showSlide(slideIndexGold22k, 'gold_22k');
            } else if (type === 'silver') {
                slideIndexSilver = Math.max(slideIndexSilver - 4, 0);
                showSlide(slideIndexSilver, 'silver');
            } else if (type === 'silver_925') {
                slideIndexsilver_925 = Math.max(slideIndexsilver_925 - 4, 0);
                showSlide(slideIndexsilver_925, 'silver_925');
            } else if (type === 'diamond') {
                slideIndexDiamond = Math.max(slideIndexDiamond - 4, 0);
                showSlide(slideIndexDiamond, 'diamond');
            }
        }

        showSlide(slideIndexGold, 'gold');
        showSlide(slideIndexGold20k, 'gold_20k');
        showSlide(slideIndexGold22k, 'gold_22k');
        showSlide(slideIndexSilver, 'silver');
        showSlide(slideIndexsilver_925, 'silver_925');
        showSlide(slideIndexDiamond, 'diamond');
    </script>
</main>

</body>

</html>