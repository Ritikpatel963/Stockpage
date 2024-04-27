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

            <form method="post" class="mt-3">
                <table width="100%" class="table-condensed table-striped table-bordered">
                    <tr style='font-size:13px' class="no_print">
                        <td>
                            Start Date <input type='date' name='date1' value="<?php echo $date ?>" />
                        </td>

                        <td>
                            End Date <input type='date' name='date2' value="<?php echo $date ?>" />
                        </td>

                        <td>
                            <input type='submit' style="background-color: 211360;color: white;border-radius: 10%;padding: 10px;" name='submit' value="Search" />
                        </td>

                        <td align='right'>
                            <strong> SELECT / DESELECT ALL </strong>
                            &nbsp; <input type='checkbox' id='checker' onclick='select_all()' style='width:15px;height:15px' />
                        </td>
                    </tr>
                </table>
            </form>

            <table class='table-striped table-bordered' width="100%">
                <tr style="background-color:#F7FEDE; font-weight:bold" align='center'>
                    <td width="5%">S.No</td>
                    <td>Date</td>
                    <td>Bill</td>
                    <td>Name</td>
                    <td align='right'>Amount</td>
                    <td align='right'>Payment</td>
                    <td class='no_print' align="right">Delete </td>
                </tr>

                <form action='delete.php' method='post'>

                    <?php
                    $sql = "SELECT mobile, name, date, bill_no,paid, balance_amount, type 
			FROM sales GROUP BY bill_no ORDER BY id DESC LIMIT 1000";

                    if (isset($_POST['date1'])) {
                        $date1 = $_POST['date1'];
                        $date2 = $_POST['date2'];
                        $sql = "SELECT mobile, name, date, bill_no, paid, balance_amount, type FROM sales where date between '$date1' and '$date2' GROUP BY bill_no ORDER BY id";
                    }

                    $sql_run = mysqli_query($db, $sql) or die(mysqli_error($db));
                    $count = mysqli_num_rows($sql_run);
                    $total_amt = 0;

                    if ($count > 0) {
                        for ($i = 1; $i <= $count; $i++) {
                            $row = mysqli_fetch_array($sql_run);
                            $date = $row['date'];

                            $dt = date('d-m-y', strtotime($date));
                            $bill = $row['bill_no'];
                            $name = $row['name'];
                            $type = $row['type'];
                            $amount = $row['balance_amount'];
                            $payment = $row['paid'];
                            $total_amt += $amount;

                            echo
                            "<tr align='center'>
			<td>$i</td>
			<td>$dt</td>
			<td>$bill</td>
			<td>$name</td>
			<td width='20%' align='right'>$amount</td>
			<td width='20%' align='right'>$payment</td>
			<td class='no_print' align='right'><input style='width:15px;height:15px' class='del' type='checkbox' name='delete[$i]' value='$bill'/></td>
			</tr>
			";
                        }
                    }

                    ?>
                    <tr>
                        <td colspan="5" align="right"></td>
                        <td align="right"><input type='password' name='password' placeholder="Delete Password" /></td>
                        <td align='right'>
                            <input type="submit" style="background-color: red;color: white;border-radius: 10%;padding: 10px;" name="submit" value='Delete' />
                </form>
                </td>
                </tr>
            </table>
            <?php
            if (isset($_POST['delete'])) {
                $del_array = $_POST['delete'];
                $password = $_POST['password'];
                if ($password == '999') {
                    //first select the bills and if it is normal
                    foreach ($del_array as $xx) {
                        $sql = mysqli_query($db, "SELECT bp_remark, paid from sales where bill_no = '$xx' limit 1");
                        $row = mysqli_fetch_array($sql);
                        $bp_remark = $row['bp_remark'];
                        $paid = $row['paid'];

                        if ($bp_remark == 'normal') {
                            $sql = mysqli_query($db, "delete from sales where bill_no = '$xx'");
                        } else {
                            $sql = mysqli_query($db, "UPDATE sales set advance = advance - $paid where bill_no = '$bp_remark'");
                            $sql = mysqli_query($db, "delete from sales where bill_no = '$xx'");
                        }
                    }
                    header("location:delete.php");
                } else {
                    echo "Wrong Password";
                }
            }

            ?>

        </main>
    </div>
    <!-- 
        This is a place for Alpine.js Teleport feature 
        @see https://alpinejs.dev/directives/teleport
      -->
    <div id="x-teleport-target"></div>
    
<script type='text/javascript'>
	function select_all() {
		var checker = document.getElementById('checker');
		if (checker.checked == true) {
			var inputs = document.getElementsByClassName('del');
			for (var i = 0; i < inputs.length; i++) {
				inputs[i].checked = true;
			}
		} else {
			var inputs = document.getElementsByClassName('del');
			for (var i = 0; i < inputs.length; i++) {
				inputs[i].checked = false;
			}
		}

	}
</script>
</body>

</html>