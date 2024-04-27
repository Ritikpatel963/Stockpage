<?php include_once('../connect.php');


if (isset($_POST['del_record'])) {

    $delete_array = $_POST['delete'];

    foreach ($delete_array as $x) {
        $sql = mysqli_query($db, "DELETE FROM sales WHERE id = '$x'");
    }
    header('location:payment_details.php');
}
?>

<link rel="stylesheet" href="css/app.css" />
<link rel="stylesheet" href="bootstrap-5/css/bootstrap.min.css">

<!-- <hr style="border: 2px solid red"> -->
<div style="width: 100%;text-align: center;margin-top: 15px;">
    <div style="position: relative;top: 12px;">
        <sup style="font-size: 10px;right: 55px">निधि</sup>
        <img src="../logo.png" alt="" style="width: 200px;position: relative;left: 40%;">
    </div>
    <br>
    <strong>शॉप नं. 2. शंभूश्री अपार्टमेंट, एम. आर. 4 रोड विजय नगर, जबलपुर (म.प्र.) 482002</strong>
    <br>
    <br>
    <strong>Balance Slip</strong>
</div>
<?php
if (isset($_POST['delete']) || isset($_GET['bill_no'])) {
    if (isset($_POST['delete'])) {
        $delete_array = $_POST['delete'];
    }
    if (isset($_GET['bill_no'])) {
        $bill_no = $_GET['bill_no'];
        $delete_array = array($bill_no);
        // echo $delete_array;
    }
    foreach ($delete_array as $x) {
        // echo $x;
        $sql = mysqli_query($db, "SELECT * FROM sales where id='$x' ");
        $row = mysqli_fetch_array($sql);
        $customer = $row['name'];
        $remark = $row['remark'];
        $id = $row['pid'];
        $bill_no = $row['bill_no'];
        $payment_date = $row['payment_date'];
        $date = date('d-m-Y', strtotime($payment_date));
        $bp_remark = $row['bp_remark'];
        $paid = $row['paid'];
        $sql_prev = mysqli_query($db, "SELECT sum(paid) as prev_paid FROM sales where id<$x and bill_no='$bill_no' and description='Payment' group by name");
        $prev_row = mysqli_fetch_array($sql_prev);
        $prev_paid = $prev_row['prev_paid'] ?? 0;

        $sql2 = mysqli_query($db, "SELECT mobile,balance_amount,address,paid FROM sales WHERE bill_no='$bill_no' group by bill_no order by id limit 1");
        $sql2_row = mysqli_fetch_array($sql2);
        $address = $sql2_row['address'];
        $mobile = $sql2_row['mobile'];
        $credit = $sql2_row['balance_amount'];
        $paidp = $sql2_row['paid'];
        $prev_paid = $prev_paid + $paidp;

        $sql3 = mysqli_query($db, "SELECT balance_amount, paid,sum(CASE WHEN bp_remark = bill_no THEN paid ELSE 0 END ) as paid2 FROM sales WHERE bill_no='$bill_no' AND date<='$payment_date' group by bill_no");
        $sql3_row = mysqli_fetch_array($sql3);
        $balance_amt = $sql3_row['paid'] + $sql3_row['paid2'];
        $balance_amt = $credit - $balance_amt;



        echo "
		<br>
<div class='col-md-12'>
	<table width='100%;' style='text-align: center;font-weight: bold' class='table table-condensed table-bordered table-striped' >
	<tr>
		<td align='center'>Invoice No. : </td>
		<td align='center'>  $id </td>
		<td align='center'>Date : </td>
		<td align='center'>  $date </td>
	</tr>	
	<tr>
			<td>
				Customer Name
			</td>
			<td align=''>
				 $customer 
			</td>
			<td>
			Mobile No.
			</td>
			<td align=''>
				 $mobile 
			</td>
		</tr>
	<tr>
			<td>
	Address
			</td>
			<td align='' colspan='3'>
				 $address
			</td>
	</tr>
			<td>
				For Bill
			</td>
			<td>
				$bp_remark
			</td>
			<td>
				REMARK
			</td>
			<td >
				 $remark 
			</td>
			</tr>
			<tr>
			<td>
			Bill Amount
			</td>
			<td>
			$credit
			</td>
			</tr>
			<tr>
			<td>
				Previous Paid
			</td>
			<td >
				 $prev_paid 
			</td>
		</tr>
			<tr>
			<td>
				Payment
			</td>
			<td >
				 $paid 
			</td>
		</tr>
		<tr>

			<td>
				Balance Amount
			</td>
			<td >
				 $balance_amt
			</td>
			
		</tr>
		<tr>
	";
    }
}
?>

<?php
$s = $customer;
$t_another_balance = $another_balance = 0;
$sql = "SELECT mobile, name, date, bill_no,mobile,type,firmname, description,total_amt,discount_final, balance_amount,paid,sum(CASE WHEN bp_remark = bill_no THEN paid ELSE 0 END ) as paid2,sum(CASE WHEN bp_remark = bill_no THEN 1 ELSE 0 END ) as paidcount,remark FROM sales WHERE name = '$s' and bill_no!='$bp_remark' GROUP BY bill_no ORDER BY bill_no";


$sql_run = mysqli_query($db, $sql);
$count = mysqli_num_rows($sql_run);

$total_amt = $discount_tt = $super_advance = $super_balance = 0;


for ($i = 1; $i <= $count; $i++) {
    $row = mysqli_fetch_array($sql_run);
    $bill = $row['bill_no'];


    $type = $row['type'];
    $amount = $row['total_amt'];

    $billamt = $row['balance_amount'];
    $discount_final = $row['discount_final'];

    $total_amt = $billamt;
    $another_balance += $total_amt;


    $paid = round($row['paid']) + $row['paid2'];

    $remark = $row['remark'];

    $super_advance += $paid;
    //FIND PARTICULARS SEPARATELY

    $super_balance = $total_amt - $paid;
    $t_another_balance = $another_balance - $super_advance;

    echo
    "<tr align='center'>
			<td>$i</td>
			<td>$super_balance</td>
			</tr>
			";
}


$balance_amt = $balance_amt + $t_another_balance;
?>

</table>
</div>

</div>
</div>