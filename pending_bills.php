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
?>

<tr style="background-color:#F7FEDE; font-weight:bold">
	<td width="20%">Date</td>
	<td width="20%">Bill No</td>
	<td width="20%">Party Name </td>
	<td align='right'>Pending </td>
</tr>

<?php
if (isset($_POST['party'])) {
	$party = $_POST['party'];
	$bill_total = 0;
	$total_paid = $tt_paid = $advance = 0;



	//FIND TOTAL PAID //
	// $sql3 = mysqli_query($db,"SELECT paid from sales where name = '$party' and balance_amount<=0 and bp_remark = 'normal' group by bill_no");
	// 	$sql3count = mysqli_num_rows($sql3);
	// 	for($x=1;$x<=$sql3count;$x++)
	// 		{
	// 		$rw3 = mysqli_fetch_array($sql3);
	// 		$total_paid += $rw3['paid'];
	// 		}
	// 		$tt_paid = $total_paid;

	//

	///select each bill
	$sql4 = mysqli_query($db, "SELECT bill_no,balance_amount,date,paid from sales where name = '$party' and amount!=0 group by bill_no");
	$sql4count = mysqli_num_rows($sql4);
	$o = 1;
	for ($h = 1; $h <= $sql4count; $h++) {
		$rw4 = mysqli_fetch_array($sql4);
		$bill = $rw4['balance_amount'];
		$bill = $bill - $rw4['paid'];
		$bill_no = $rw4['bill_no'];
		$sql5 = mysqli_query($db, "SELECT sum(paid) as paid from sales where name = '$party' and description='Payment' and bill_no='$bill_no' group by bill_no");
		$rw5 = mysqli_fetch_array($sql5);
		$p = $rw5['paid'] ?? 0;
		$bill = $bill - $p;
		$date = date('d-m-y', strtotime($rw4['date']));
		$bill = $bill - $tt_paid;

		if ($bill <= 0) {
			echo '';
			$tt_paid = abs($bill);
		} else {
			echo
			"
				<tr> 
					<td>$date</td>
					<td class='mybills'>$bill_no</td>
					<td width='30%'>$party</td>
					<td align='right'>$bill</td>
				</tr>
				";
			$tt_paid = 0;
			$bill_total += $bill;
		}
	}
}

?>
<tr style="font-weight:bold">
	<td colspan='3' align="right">TOTAL</td>
	<td align="right"><?php echo $bill_total ?></td>
</tr>