<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['getDetails'])) {
    $my_host = 'localhost';
    $my_username = 'root';
    $my_pass = '';
    $my_db_name = 'narvariya';

    $db = mysqli_connect($my_host, $my_username, $my_pass, $my_db_name) or die("cannot connect to server");

    $id = $_POST['id'];

    $productResult = $db->query("SELECT * FROM stock WHERE name='$id'");
    if ($productResult->num_rows > 0) {
        $productData = $productResult->fetch_assoc();

        echo json_encode($productData);
    }
    exit();
}
?>
<?php include_once('../connect.php'); ?>
<?php
include 'navbar.php';
?>

        <!-- Main Content Wrapper -->
        <main class="main-content w-full px-[var(--margin-x)] pb-8">

            <?php
            //find bill for payment bill
            $invoice_sql = "SELECT * FROM sales ORDER BY bill_no DESC LIMIT 1";
            $invoice_run = mysqli_query($db, $invoice_sql);
            $invoice_row = mysqli_num_rows($invoice_run);

            if ($invoice_row == 1) {
                $fetch_row = mysqli_fetch_array($invoice_run);
                $bill = $fetch_row['bill_no'];
                $bill = round($bill + 1);
            } else if ($invoice_row == 0) {
                $bill = 1;
            }
            $party = '';
            if (isset($_GET['party'])) {
                $party = $_GET['party'];
            }
            ?>
            <div class='row mt-5'>
                <div class='col-md-6'>
                    <p class="alert-danger" align="center" style='font-weight:bold; padding:10px;'> Bill Details : </p>
                    <table style="width:100%" class='table-striped table-bordered'>
                        <tbody id='pending'>
                        </tbody>
                    </table>
                </div>

                <div class='col-md-6'>
                    <p class="alert-success" align="center" style='font-weight:bold; padding:10px'> Make Payment : </p>

                    <form action='payment.php' method='post'>
                        <table width="100%" class='table-striped table-bordered no_print'>
                            <tr>
                                <td><strong>Date</strong> </td>
                                <td><input type='date' name='date' style='width:100%' value="<?php echo $date ?>" /></td>
                            </tr>

                            <tr>
                                <td><strong>Party Name</strong> </td>
                                <td><input type='text' id='party' name='party' style='width:100%' list="name_list" onchange="findbill()" onfocus="findbill()" value="<?php echo $party ?>" autofocus />

                                    <datalist id='name_list'>
                                        <?php
                                        $sql = "SELECT name FROM sales GROUP BY name";
                                        $sql_run = mysqli_query($db, $sql);
                                        $count = mysqli_num_rows($sql_run);
                                        for ($i = 1; $i <= $count; $i++) {
                                            $row = mysqli_fetch_array($sql_run);
                                            $name = $row['name'];
                                            echo "<option>$name</option>";
                                        }
                                        ?>
                                    </datalist>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Add Payment </strong> </td>
                                <td><input type='text' name='paid' style='width:100%' /></td>
                            </tr>


                            <tr>
                                <td><strong>For Bill</strong></td>
                                <td>
                                    <select id='for_bill' name='for_bill'>

                                    </select>
                                </td>
                            </tr>


                            <tr>
                                <td><strong>Remark</strong></td>
                                <td><input type='text' name='remark' style='width:100%' /> </td>
                            </tr>


                            <tr>
                                <td colspan='2'>
                                    <input type="hidden" name="bill" value="<?php echo $bill ?>">
                                    <input style='width:100%' type='submit' name='submit' value='Add' />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br>
                                    <br>
                                    <a href="payment_details.php" title="" style="text-align: center;width: 100%; background-color: 211360;" class="btn btn-primary"> SHOW PAYMENT DETAILS</a>
                                </td>
                            </tr>

                    </form>
                </div>
                <?php
                if (isset($_POST['submit'])) {
                    $paid = $_POST['paid'];
                    $remark = $_POST['remark'];
                    $bill = $_POST['bill'];
                    $party = trim($_POST['party']);
                    $date = $_POST['date'];
                    $for_bill = $_POST['for_bill'];
                    $payment_date = $date;
                    if ($for_bill != 'normal') {
                        $bill = $for_bill;
                        $sql = mysqli_query($db, "SELECT date FROM sales where bill_no='$bill' and description!='Payment' GROUP by bill_no");
                        $rs = mysqli_fetch_array($sql);
                        $date = $rs['date'];
                    }

                    $sqlpid = mysqli_query($db, "SELECT pid FROM sales where description='Payment' ORDER BY pid desc");
                    $rspid = mysqli_fetch_array($sqlpid);
                    $pid = $rspid['pid'];
                    $pid = $pid + 1;


                    $sql = mysqli_query($db, "INSERT INTO sales (bill_no, name, date, description,paid,remark,bp_remark,payment_date,pid) 
		values ('$bill', '$party', '$date','Payment','$paid','$remark','$for_bill','$payment_date','$pid')");

                    // if($for_bill != 'xxx')
                    // {
                    // $sql2 = mysqli_query($db,"UPDATE sales set advance = advance + '$paid' where bill_no = '$for_bill'");
                    // }

                    header("location:payment.php?party=$party");
                }


                ?>
        </main>
    </div>
    <!-- 
        This is a place for Alpine.js Teleport feature 
        @see https://alpinejs.dev/directives/teleport
      -->
    <div id="x-teleport-target"></div>
    <script>
        function findbill() {
            var party = document.getElementById('party').value;

            var xmlpg = new XMLHttpRequest();
            xmlpg.onreadystatechange = function() {
                pending.innerHTML = this.responseText;
                mybill();
            }
            xmlpg.open("POST", "pending_bills.php", true);
            xmlpg.setRequestHeader("Content-Type", "application/x-www-Form-urlencoded");
            xmlpg.send('party=' + party);
        }
    </script>

    <script>
        function mybill() {
            var bill = document.getElementsByClassName('mybills');
            var bod = '<option>normal</option>';
            var for_bill = document.getElementById('for_bill');

            for (var i = 0; i < bill.length; i++) {
                bod += '<option>' + bill[i].innerHTML + '</option>';
            }
            for_bill.innerHTML = bod;
        }
    </script>
    <script>
        window.addEventListener("DOMContentLoaded", () => Alpine.start());
    </script>
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>

</html>