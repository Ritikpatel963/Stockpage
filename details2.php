<?php
include '../../connect.php';

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
  <!-- FILTER -->
<table width='100%'>
	<tr></tr>
	<tr align='center' style='font-weight:bold'>
		<td>|| श्री गणेशाय नमः    ||<br/>
	GSTIN: 23ABTPC789B1ZV<br><br><br></td>
		<td>|| श्री || <br/>
	
		 ESTIMATE ORDER  <br/>
	
		<span style='font-size:29px; color:#990000 !important'> नरवरिया आभूषण </span> <br/>
		</td>
		<td align="right">जबलपुर न्यायलय के अंतर्गत <br> ॐ श्री लक्ष्मी जी  <br>Mob: 9229887581 <br/> (S):0761-4010342 , 0761-4010329</td>
	</tr>
	
	<tr align='center' style='font-weight:bold'>
		<td colspan="3">
		<span style="background-color: #0c0c4abd !important;color:white !important;display: block;border-radius: 15px;">हीरे , सोने एवं चांदी के विश्वसनीय एवं नवीनतम आभूषणो का एक मात्र प्रतिष्ठान </span>   
	</td></tr>
	<tr>
		<td colspan="3" align="center">
			<strong>
				
			456  , हितकारिणी स्कूल के पास नुनहाई , सराफा जबलपुर - 482002  
		</strong>
		</td>
	</tr>
</table>

<hr style="border: 2px solid red">


<?php
$payment='';
if (isset($_GET['s']))
	{
	$s = $_GET['s'];
	$bill=$_GET['bill'];
	}

if (isset($_POST['s']))
	{
	$s = $_POST['s'];
	$bill=$_POST['bill'];
	}

if(isset($_GET['date_1']))
	{
	$date_1 = $_GET['date_1'];
	$date_2 = $_GET['date_2'];	
	}

// if(isset($_GET['s']) || isset($_POST['s']))
// {
// 	echo "
// 	<table class='no_print'>
// 	<tr style='font-weight:bold'>
// 	<td> &nbsp; BILL DETAILS : $s | $bill</td>
// 	<td>&nbsp;</td>
// 	<td style='background-color:#ccc'>
// 	<form action='details.php' method='post'/>
// 	Start Date <input type='date' name='date_1' value='$date'/>
// 	End Date <input type='date' name='date_2' value='$date'/>
// 	</td>
// 	<input type='hidden' name='s' value='$s'/>
// 	<input type='hidden' name='bill_no' value='$bill'/>
// 	<td><input type='submit' name='submit'/></td>
// 	</tr>
// 	</form>
// 	</table>";
// 	}
?>

	<table class="table-bordered table-striped" width="100%">
		<tr style="background-color:#F7FEDE; font-weight:bold" align='center'>
			<td width="3%">Id </td>
			<td width="3%">Bill No. </td>
			<td width="15%">Date</td>
			<td align=''>Debit  </td>
			<td align=''>Credit </td>
			<td align=''>Balance </td>
			<td align="">Remark</td>
	</tr>
		
		<?php
			$debit_total = $credit_total = 0;
		$balance=0;
		//$sql = "SELECT mobile, name, date, bill_no,mobile,type,firmname, description,total_amt,discount_final, balance_amount,paid,sum(CASE WHEN bp_remark = bill_no THEN paid ELSE 0 END ) as paid2,sum(CASE WHEN bp_remark = bill_no THEN 1 ELSE 0 END ) as paidcount,remark FROM sales WHERE name = '$s' GROUP BY bill_no ORDER BY bill_no";
		$sql = "SELECT  paid as credit,balance_amount as debit,date,remark,id,bill_no from sales WHERE name = '$s' and bill_no='$bill' and description!='Payment' GROUP by bill_no  ORDER BY date";
		
		// if(isset($_POST['submit']))
		// 	{
		// 	$date_1 = $_POST['date_1'];	
		// 	$date_2 = $_POST['date_2'];	
		// 	$s = $_POST['s'];
		// 	$bill_no=$_POST['bill_no']
		// 	$sql = "SELECT  debit,credit,date,remark,id,bill_no from finance WHERE customer = '$s' and bill_no='$bill' and date between '$date_1' and '$date_2'";
		// 	}
		
		//FIND OPENING BALANCE
			

		$sql_run = mysqli_query($db,$sql) or die(mysqli_error($db));
		$count = mysqli_num_rows($sql_run);

		$debit_total = $credit_total = 0;
		
		
		for($i=1; $i<=$count; $i++)
			{
			$row = mysqli_fetch_array($sql_run);
			$date = $row['date'];
			$date = date('d-m-Y',strtotime($date));
			$debit = $row['debit'];
			$credit = $row['credit'];
			$remark = $row['remark'];
			$bill_no=$row['bill_no'];
			

			$balance+=$debit-$credit;
			echo 
			"<tr align='center'>
			<td>$i</td>
			<td>$bill_no</td>
			<td>$date</td>
			<td>$debit</td>
			<td>$credit</td>
			<td>$balance</td>
			<td>$remark</td>
			";

			$debit_total+= $debit;
			$credit_total+= $credit;
			}

		$sql = "SELECT  paid as credit,balance_amount as debit,date,remark,id,bill_no from sales WHERE name = '$s' and bill_no='$bill' and description='Payment'  ORDER BY date";
	
	$sql_run = mysqli_query($db,$sql) or die(mysqli_error($db));
		$count = mysqli_num_rows($sql_run);

	
		
		
		for($i=1; $i<=$count; $i++)
			{
			$row = mysqli_fetch_array($sql_run);
			$date = $row['date'];
			$date = date('d-m-Y',strtotime($date));
			$debit = 0;
			$credit = $row['credit'];
			$remark = $row['remark'];
			$bill_no=$row['bill_no'];
			

			$balance+=$debit-$credit;
			echo 
			"<tr align='center'>
			<td>$i</td>
			<td>$bill_no</td>
			<td>$date</td>
			<td>0</td>
			<td>$credit</td>
			<td>$balance</td>
			<td>$remark</td>
			";

			$debit_total+= $debit;
			$credit_total+= $credit;
			}
			echo "<tr style='font-weight:bold'>
					  <td> </td>
   					  <td> </td>
   				  	 <td colspan='' align='center'><strong>TOTAL</strong></td>
					  <td align='center'>$debit_total </td>
					  <td align='center'>$credit_total </td> 	
					  <td align='center'>$balance </td> 	
					  <td align='center' style='color:red'> </td> 
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