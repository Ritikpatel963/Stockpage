<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['getDetails'])) {
	$id = $_POST['id'];

	$productResult = $db->query("SELECT * FROM stock WHERE name='$id'");
	if ($productResult->num_rows > 0) {
		$productData = $productResult->fetch_assoc();

		echo json_encode($productData);
		exit();
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
	$payment = '';
	if (isset($_GET['s'])) {
		$s = $_GET['s'];
	}

	if (isset($_POST['s'])) {
		$s = $_POST['s'];
	}

	if (isset($_GET['s']) || isset($_POST['s'])) {
		echo "
	<table class='no_print'>
	<tr style='font-weight:bold'>
	<td> &nbsp; BILL DETAILS : $s |</td>
	<td>&nbsp;</td>
	<td style='background-color:#ccc'>
	<form action='details.php' method='post'/>
	Start Date <input type='date' name='date_1' value='$date'/>
	End Date <input type='date' name='date_2' value='$date'/>
	</td>
	<input type='hidden' name='s' value='$s'/>
	<td><input type='submit' name='submit' style='background:darkblue;padding:10px;color:white'/></td>
	</tr>
	</form>
	</table>";
	}
	?>

	<table class="table-bordered table-striped" width="100%">
		<tr style="background-color:#F7FEDE; font-weight:bold" align='center'>
			<td width="3%">Id </td>
			<td width="15%">Date</td>
			<td width="10%">Bill No.</td>
			<td width="15%">Particulars </td>
			<td align=''>Debit </td>
			<td align=''>Credit </td>
			<td align=''>Total Balance </td>
			<td align="">Remark</td>
			<td align='' class="no_print">Modify / Pay </td>

		</tr>

		<?php
		$sql = "SELECT mobile, name, date, bill_no,mobile,type,firmname, description,total_amt,discount_final, balance_amount,paid,sum(CASE WHEN bp_remark = bill_no THEN paid ELSE 0 END ) as paid2,sum(CASE WHEN bp_remark = bill_no THEN 1 ELSE 0 END ) as paidcount,remark FROM sales WHERE name = '$s' GROUP BY bill_no ORDER BY bill_no";


		if (isset($_POST['submit'])) {
			$date_1 = $_POST['date_1'];
			$date_2 = $_POST['date_2'];
			$s = $_POST['s'];

			$sql = "SELECT mobile, name, date, bill_no,mobile,type,firmname, description,total_amt,discount_final, balance_amount,paid,sum(CASE WHEN bp_remark = bill_no THEN paid ELSE 0 END ) as paid2,sum(CASE WHEN bp_remark = bill_no THEN 1 ELSE 0 END ) as paidcount,remark FROM sales WHERE date between '$date_1' and '$date_2' and name = '$s' GROUP BY bill_no ORDER BY bill_no";
		}

		//FIND OPENING BALANCE


		$sql_run = mysqli_query($db, $sql);
		$count = mysqli_num_rows($sql_run);

		$total_amt = $discount_tt = $super_advance = $super_balance = 0;


		for ($i = 1; $i <= $count; $i++) {
			$row = mysqli_fetch_array($sql_run);
			$date = $row['date'];
			$date = date('d-m-y', strtotime($date));
			$bill = $row['bill_no'];
			$mobile = $row['mobile'];
			$partyname = $row['name'];
			$firmname = $row['firmname'];
			$paidcount = $row['paidcount'];
			if ($paidcount > 0)
				$paidcount = "PAYMENT($paidcount)";
			else
				$paidcount = "";


			$type = $row['type'];
			$amount = $row['total_amt'];
			$description = $row['description'];

			$billamt = $row['balance_amount'];
			$discount_final = $row['discount_final'];

			$total_amt += $billamt;


			$paid = round($row['paid']) + $row['paid2'];

			$remark = $row['remark'];

			$super_advance += $paid;
			//FIND PARTICULARS SEPARATELY

			$super_balance = $total_amt - $super_advance;

			$modify = 'Modify';
			if ($description == 'Payment') {
				$modify = '';
			}
			$sqld = mysqli_query($db, "SELECT description,type,amount from sales WHERE bill_no='$bill'");
			$sqld_count = mysqli_num_rows($sqld);

			echo
			"<tr align='center'>
			<td>$i</td>
			<td>$date</td>
			<td> <a href='details2.php?s=$s&bill=$bill' style='display:block;' >$bill</a></td>
			<td><table class='table-bordered' width='100%;text-align:center;'> ";

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

			echo "</table></td><td>$billamt</td>
			<td>$paid</td>
			<td>$super_balance</td>
			<td>$remark</td>
	 <td class='no_print'><a onclick='pass($bill);' style='background:red;cursor: pointer;padding:5px;color:white'>Modify</a></td>
			
			";
		}


		echo "<tr style='font-weight:bold'>
					 	<td> </td>
					 	<td> </td>
					  	<td colspan='' align='center'><strong>TOTAL</strong></td>
					  <td> </td>
					  <td align='center'>$total_amt </td>
					  <td align='center'>$super_advance </td> 	
					  <td align='center' style='color:red'>$super_balance </td> 
				</tr>";
		?>
	</table>
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

<!-- Right Sidebar -->
<script>
	window.addEventListener("DOMContentLoaded", () => Alpine.start());
</script>
<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>

</body>

</html>