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
	<table width='100%'>
		<tr align='center' style='font-weight:bold'>
			<td>
				<span style='font-size:29px; color:#990000 !important'> PAYMENT DETAILS </span> <br />
			</td>
		</tr>

	</table>

	<hr style="border: 2px solid red">


	<?php
	$payment = '';
	if (isset($_GET['s'])) {
		$s = $_GET['s'];
		$bill = $_GET['bill'];
	}

	if (isset($_POST['s'])) {
		$s = $_POST['s'];
		$bill = $_POST['bill'];
	}

	if (isset($_GET['date_1'])) {
		$date_1 = $_GET['date_1'];
		$date_2 = $_GET['date_2'];
	}

	?>
	<table class='no_print'>
		<tr style='font-weight:bold'>
			<td>&nbsp;</td>
			<td style='background-color:#ccc'>
				<form action='' method='post' />
				Start Date <input type='date' name='date_1' value='<?php echo $old_date ?>' />
				End Date <input type='date' name='date_2' value='<?php echo  $date ?>' />
				<input type='text' name='customer' value='' list="name_list" />
			</td>

			<datalist id='name_list'>
				<?php
				$sql = "SELECT distinct name FROM sales";
				$sql_run = mysqli_query($db, $sql);
				$count = mysqli_num_rows($sql_run);
				for ($i = 1; $i <= $count; $i++) {
					$row = mysqli_fetch_array($sql_run);
					$name = $row['name'];
					echo "<option>$name</option>";
				}
				?>
			</datalist>
			<td><input type='submit' name='submit' style='display:block;background-color: 211360;color: white;padding: 10px' /></td>
		</tr>
		</form>
	</table>

	<table class="table-bordered table-striped" width="100%">
		<tr class='no_print'>
			<td colspan='3' align='center'> <button onclick="window.print()" style='display:block;background-color: 211360;color: white;padding: 10px'>Print</button> </td>
			<td colspan='5' align='right'>
				<strong> SELECT / DESELECT ALL </strong>
				&nbsp; <input type='checkbox' id='checker' onclick='select_all()' />

			</td>
		</tr>

		<form action='payment_print.php' method='post' />
		<tr>
			<td align='right' colspan='8'>
				<input type='submit' name='printer' value='Print-Select' style='display:block;background-color: 211360;color: white;padding: 10px' />
			</td>
		</tr>
		<tr style="background-color:#F7FEDE; font-weight:bold" align='center'>
			<td width="3%">Id </td>
			<td width="3%">Bill No. </td>
			<td width="15%">Date</td>
			<td width="15%">Customer</td>
			<td align=''>Payment</td>
			<td width="5%">For Bill </td>
			<td align="">Remark</td>
			<td align="">Action</td>
		</tr>

		<?php

		$balance = $t_credit = 0;
		$sql = "SELECT  * from sales WHERE description='Payment'  ORDER BY id desc,date";

		if (isset($_POST['submit'])) {
			$date_1 = $_POST['date_1'];
			$date_2 = $_POST['date_2'];
			$customer = $_POST['customer'];
			if ($customer == "")
				$sql = "SELECT  * from sale WHERE description='Payment' and date between '$date_1' and '$date_2' ORDER BY id desc,date";
			else
				$sql = "SELECT  * from sales WHERE description='Payment' and name='$customer' and date between '$date_1' and '$date_2' ORDER BY id desc,date";
		}

		//FIND OPENING BALANCE


		$sql_run = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count = mysqli_num_rows($sql_run);

		$debit_total = $credit_total = 0;


		for ($i = 1; $i <= $count; $i++) {
			$row = mysqli_fetch_array($sql_run);
			$date = $row['date'];
			$date = date('d-m-Y', strtotime($date));
			$customer = $row['name'];
			$credit = $row['paid'];
			$remark = $row['remark'];
			$bill_no = $row['bill_no'];
			$bp_remark = $row['bp_remark'];
			$id = $row['id'];
			$t_credit += $credit;

			echo
			"<tr align='center'>
			<td>$i</td>
			<td>$bill_no</td>
			<td>$date</td>
			<td>$customer</td>
			<td>$credit</td>
			<td>$bp_remark</td>
			<td>$remark</td>
			<td align='center' class='no_print'><input style='width:20px; height:20px' class='del' type='checkbox' name='delete[$i]' value='$id'/></td>
		";
		}

		echo "<tr style='font-weight:bold'>
					  <td> </td>
   					  <td> </td>
   				  	 <td colspan='' align='center'><strong>TOTAL</strong></td>
					  <td align='center'> </td>
					  <td align='center'>$t_credit </td> 	
					  <td align='center'> </td> 	
					  <td align='center'> </td> 	
					  <td class='no_print' align='right'><input type='submit' name='printer' value='Print-Select' style='display:block;background-color: 211360;color: white;padding: 10px'/></td>
		</tr>
					<tr>
 					<td class='no_print' align='right' colspan='10' ><input type='hidden' value='search' name='page'><input type='password' id='pass' name='pass' placeholder='ENTER PASSWORD'><input type='submit' class='btn btn-danger' id='del_record' name='del_record' value='DELETE SELECT BILL' disabled/></td>
		</tr>";

		?>
	</table>
	</form>

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
		} else {
			$sql = "SELECT mobile, name, date, bill_no,mobile,type,firmname, description,total_amt,discount_final, balance_amount,paid,remark FROM sales GROUP BY bill_no ORDER BY bill_no DESC";
		}

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