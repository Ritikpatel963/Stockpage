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
	$payment = '';
	if (isset($_GET['s'])) {
		$s = $_GET['s'];

		echo "
	<table>
	<tr style='font-weight:bold'>
	<td> &nbsp; BILL DETAILS : $s |</td>
	<td>&nbsp;</td>
	<td style='background-color:#ccc'>
	<form action='details.php' method='post'/>
	Start Date <input type='date' name='date_1'/>
	End Date <input type='date' name='date_2'/>
	</td>
	<input type='hidden' name='s' value='$s'/>
	<td><input type='submit' name='submit'/></td>
	</tr>
	</form>
	</table>";
	}
	?>

	<table class="table table-condensed table-striped table-bordered">
		<tr style="background-color:#F7FEDE; font-weight:bold" align='center'>
			<td width="3%">Id </td>
			<td width="5%">Date</td>
			<td width="10%">Bill No.</td>
			<td width="15%">Particulars </td>
			<td align=''>Debit </td>
			<td align=''>Credit </td>
			<td align=''>Balance </td>
			<td align="">Remark</td>
		</tr>

		<?php

		if (isset($_POST['submit'])) {
			$date_1 = $_POST['date_1'];
			$date_2 = $_POST['date_2'];
			$s = $_POST['s'];

			$sql = "SELECT mobile, name, date, bill_no,mobile,type,firmname, description,total_amt,discount_final, balance_amount,paid,remark FROM sales WHERE date between '$date1' and '$date2' AND firmname = '$name'
			and name = '$s' GROUP BY bill_no ORDER BY bill_no DESC";
		}

		$sql = "SELECT mobile, name, date, bill_no,mobile,type,firmname, description,total_amt,discount_final, balance_amount,paid,remark FROM sales WHERE name = '$s' GROUP BY bill_no ORDER BY bill_no DESC";

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


			$type = $row['type'];
			$amount = $row['total_amt'];
			$description = $row['description'];

			$balance_amount = $row['balance_amount'];
			$discount_final = $row['discount_final'];

			$total_amt += $balance_amount;

			$discount_tt += $discount_final;

			$paid = round($row['paid']);

			$remark = $row['remark'];

			$super_advance += $paid;
			//FIND PARTICULARS SEPARATELY

			echo
			"<tr align='center'>
			<td>$i</td>
			<td>$date</td>
			<td>$bill</td>
			<td>$type</td>
			<td>$balance_amount</td>
			<td>$paid</td>
			<td>$discount_final</td>
			<td>$remark</td>
			
			";
		}

		$super_balance = $total_amt - $super_advance;

		echo "<tr style='font-weight:bold'>
					  <td colspan='' align='center'><strong>TOTAL</strong></td>
					  <td> </td>
					  <td> </td>
					  <td> </td>
					  <td align='center'>$total_amt </td>
					  <td align='center'>$super_advance </td> 	
					  <td align='center' style='color:red'>$super_balance </td> 
				</tr>";
		?>
	</table>


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