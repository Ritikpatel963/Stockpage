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
    <form action='todays.php' method='post'>
					<table width="100%" border="1" align="center">
						<tr style="font-weight:bold; background-color:#dcdcdc">
							<td>Begin Date <input type='date' name='date1' value="<?php echo $old_date ?>" /></td>
							<td>End Date <input type='date' name='date2' value="<?php echo $date ?>" /></td>
							<td align="right">
								<strong>FILTER BY FIRM :</strong>
							</td>

							<td>
								<select name='all_records'>
									<option value="all">All</option>
									<option value="bills">Bills Only</option>
									<option value="payment">Payments Only</option>
								</select>
							</td>


							<td>
								<input type='submit' name='submit' value="SEARCH" style="background-color: 211360; color: white;text-align: center;padding: 5;border-radius: 10%;">
				</form>
				</td>
				</tr>
				</table>

				<table align="right">
					<tr style='font-weight:bold'>
						<td>SELECT ALL <input type='checkbox' id='checker' onclick="select_all()"></td>
					</tr>
				</table>

				<form action='todays.php' method='post'>
					<table class="table-bordered" width="100%">
						<tr style="background-color:#F7FEDE; font-weight:bold" align='center'>
							<td>Id</td>
							<td width="8%">Date</td>
							<td width="5%">Bill</td>
							<td width="15%" style="display:none">Firm</td>
							<td width="10%">Customer</td>
							<td width="15%">Description</td>
							<td align='center'>Amount</td>
							<td align='center'>Paid</td>
							<td align='center'>Balance</td>
							<td align='center'>Remark</td>
							<td class='no_print' width="4%">Print</td>
							<td class='no_print' width="4%">Modify</td>
							<td align="right" class='no_print' width="4%">Delete</td>
						</tr>

						<?php
						//FIND TODAY Records
						$today = date('Y-m-d');
						$paid_tt = 0;

						$sql = "SELECT mobile, name, date, bill_no,total_amt,mobile, type,description,firmname, discount_final, balance_amount,paid,sum(CASE WHEN bp_remark = bill_no THEN paid ELSE 0 END ) as paid2,sum(CASE WHEN bp_remark = bill_no THEN 1 ELSE 0 END ) as paidcount, remark FROM sales WHERE date = '$today' GROUP BY bill_no ORDER BY bill_no desc";

						if (isset($_POST['submit'])) {
							$date1 = $_POST['date1'];
							$date2 = $_POST['date2'];
							$all_records = $_POST['all_records'];

							if ($all_records == 'bills') {
								$sql = "SELECT mobile, name, date, bill_no,total_amt,mobile, type,description,firmname, discount_final, balance_amount,paid,sum(CASE WHEN bp_remark = bill_no THEN paid ELSE 0 END ) as paid2,sum(CASE WHEN bp_remark = bill_no THEN 1 ELSE 0 END ) as paidcount, remark FROM sales WHERE date between '$date1' and '$date2' and description != 'Payment' GROUP BY bill_no ORDER BY bill_no desc";
							} else if ($all_records == 'payment') {
								$sql = "SELECT mobile, name, date, bill_no,total_amt,mobile, type,description,firmname, discount_final, balance_amount,paid,sum(CASE WHEN bp_remark = bill_no THEN paid ELSE 0 END ) as paid2,sum(CASE WHEN bp_remark = bill_no THEN 1 ELSE 0 END ) as paidcount, remark FROM sales WHERE date between '$date1' and '$date2' and description = 'Payment' GROUP BY bill_no ORDER BY bill_no desc";
							} else if ($all_records == 'all') {
								$sql = "SELECT mobile, name, date, bill_no,total_amt,mobile, type,description,firmname, discount_final, balance_amount,paid,sum(CASE WHEN bp_remark = bill_no THEN paid ELSE 0 END ) as paid2,sum(CASE WHEN bp_remark = bill_no THEN 1 ELSE 0 END ) as paidcount, remark FROM sales WHERE date between '$date1' and '$date2' GROUP BY bill_no ORDER BY bill_no";
							}
						}
						$sql_run = mysqli_query($db, $sql);
						$count = mysqli_num_rows($sql_run);
						$total_amt = 0;

						if ($count > 0) {

							$discount_tt = $total_tt = 0;
							for ($i = 1; $i <= $count; $i++) {
								$row = mysqli_fetch_array($sql_run);
								$date = $row['date'];
								$dt = date('d-m-y', strtotime($date));
								$bill = $row['bill_no'];
								$partyname = $row['name'];
								$firmname = $row['firmname'];


								$type = $row['type'];
								$amount = $row['total_amt'];
								$description = $row['description'];

								$balance_amount = $row['balance_amount'];
								$discount_final = $row['discount_final'];
								$remark = $row['remark'];

								$total_amt += $balance_amount;

								$discount_tt += $discount_final;

								$paid = $row['paid2'] + $row['paid'];
								$paidcount = $row['paidcount'];
								if ($paidcount > 0)
									$paidcount = "PAYMENT($paidcount)";
								else
									$paidcount = "";

								$type = $row['remark'];
								$sqld = mysqli_query($db, "SELECT description,type,amount from sales WHERE bill_no='$bill'");
								$sqld_count = mysqli_num_rows($sqld);

								echo
								"<tr align='center'>
			<td>$i</td>
			<td>$dt</td>
			<td>$bill</td>
			<td>$partyname</td>
			<td><table class='table-bordered'  width='100%;text-align:center;'> ";

								for ($ds = 0; $ds < $sqld_count; $ds++) {
									$sqld_row = mysqli_fetch_array($sqld);
									$description = $sqld_row['description'];
									$type = $sqld_row['type'];
									$amount = $sqld_row['amount'];


									if ($description != 'Payment') {
										echo "
					<tr style='text-align:center;'>
					<td>$description</td>
					<td>$type</td>
					<td>$amount</td>
					</tr>
					";
									}
									// else
									// {
									// 	echo "	<tr style='text-align:center;'>
									// 	<td colspan='3'>PAYMENT</td></tr>";
									// }

								}
								echo "	<tr style='text-align:center;'>
				 	<td colspan='3'>$paidcount</td></tr>";
								echo "</table></td>	
			<td>$balance_amount</td>
			<td>$paid</td>
			<td>$discount_final</td>
			<td>$remark</td>
			";

								//FIND PARTICULARS SEPARATELY
								$paid_tt += $paid;

								echo
								"<td class='no_print'><a href='print.php?bill=$bill'>Print</a></td>
	 <td class='no_print'><a onclick='pass($bill);'>Modify</a></td>
	 <td align='right'>
	 <input style='height:15px;width:15px' type='checkbox' 
	 class='del' id='delete' name='delete[$i]' value='$bill'/></td>
	</tr>
	";
							}

							echo "<tr style='font-weight:bold'>
		  <td></td>
		  <td colspan='4' align='right'><strong>TOTAL</strong></td>					  
		  <td align='center' style='color:red'>$total_amt</td> 
 		  <td align='center' style='color:red'>$paid_tt</td> 
		  <td align='center' style='color:red'>$discount_tt</td> 
 		  <td> </td>
		  <td align='right' colspan='2'><input type='password' name='password' placeholder='password'/></td>
		  <td align='right'><input type='submit' name='deleter' value='DEL'/></td>
	   	 </tr>";
						}
						?>
				</form>
				</table>

			</div>
		</div>
	</div>
</body>

</html>

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

<?php
//delete bill

if (isset($_POST['deleter'])) {

	$delete_array = $_POST['delete'];
	$password = $_POST['password'];
	if ($password == 'Markiv') {

		foreach ($delete_array as $x) {
			$sql = mysqli_query($db, "DELETE FROM sales WHERE bill_no = '$x'");
		}
		header('location:todays.php');
	} else {
		echo "<script>alert('Incorrect Password')</script>";
	}
}


?>

<script type="text/javascript">
	function pass(bill) {
		var pass = prompt("Please enter your Password:");
		if (pass == 'Markiv') {
			window.location.href = 'index.php?bill=' + bill;
		} else {
			alert('INCORRECT PASSWORD');
		}
	}
</script>

  
    </main>
  </div>
  <!-- 
        This is a place for Alpine.js Teleport feature 
        @see https://alpinejs.dev/directives/teleport
      -->
  <div id="x-teleport-target"></div>
  <script>
        $(document).ready(function() {
          $('#productTable').DataTable();

          $('.add-purchase-btn').click(function() {
            var productId = $(this).data('product-id');
            $('#prod_id').val(productId);
            $('#addPurchaseModal').modal('show')
          });
        });
      </script>
  <script>
    window.addEventListener("DOMContentLoaded", () => Alpine.start());
  </script>
  <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>

</html>