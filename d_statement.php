<style>
  .no_print > tbody:nth-child(1) > tr:nth-child(1) > td:nth-child(2) > input:nth-child(1){
    padding: 10px;
  border-radius: 6%;
  border: 1px solid darkblue;
  }
</style>
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
include 'navbar.php'; ?>

<!-- Main Content Wrapper -->
<main class="main-content w-full px-[var(--margin-x)] pb-8">
  <form method='post' class="mt-3">
    <table width="30%" class='no_print'>
      <tr style='font-weight:bold; display:flex; gap:20px'>
        <td><input type='checkbox' onclick='selectall()' id='checker'>Select All </td>
        <td>
          <!-- 	<div class="multiselect">
    <div class="selectBox" onclick="showCheckboxes()">
      <select name="addresslist">
        <option>Address</option>
      </select>
      <div class="overSelect"></div>
    </div>
    <div id="checkboxes">
    	<?php
      $sql = mysqli_query($db, "SELECT distinct area from sales");
      $count = mysqli_num_rows($sql);
      for ($x = 1; $x <= $count; $x++) {
        $rw = mysqli_fetch_array($sql);
        $name = $rw['area'];
        echo "<input style='width:15px;height:15px ' name='address[]' class='chk' type='checkbox' value='$name'/> $name <br>";
      }
      ?>

    </div>
  </div> -->
          <input type="text" name="address[]" list='addlist'>
          <datalist id="addlist">
            <?php
            $sql = mysqli_query($db, "SELECT distinct area from sales");
            $count = mysqli_num_rows($sql);
            for ($x = 1; $x <= $count; $x++) {
              $rw = mysqli_fetch_array($sql);
              $name = $rw['area'];
              echo "<option>$name</option><br>";
            }
            ?>
          </datalist>
        </td>
        <td><input type='submit' style="padding: 10px;background-color: darkblue;color: white;border-radius: 10%;" name="submit" value="SEARCH"></td>
      </tr>
  </form>
  </table>

  <table class="table-striped table-bordered table-condensed" style="width:100%">
    <tr style="background-color:#F7FEDE; font-weight:bold" align=''>
      <td width="12%">Date</td>
      <td width="15%">Party Name </td>
      <td width="15%">Area</td>
      <td width="15%">Address</td>
      <td width="15%">Mobile</td>
      <td width="15%">Particulars</td>
      <td align='right'>Pending Bills</td>
      <td align="right">Bill Total</td>
    </tr>

    <?php
    $bill_total = 0;
    if (isset($_POST['address'])) {
      $address = $_POST['address'];
      foreach ($address as $add) {
        $sql1 = mysqli_query($db, "SELECT name,address,area,mobile from sales where area = '$add'  group by name");
        $sql1count = mysqli_num_rows($sql1);


        //FIND TOTAL PAID AND TOTAL UNPAID for customer wise
        for ($i = 1; $i <= $sql1count; $i++) {
          $billtt = 0;
          $total_bill = $total_paid = $tt_paid = 0;
          $sql1rw = mysqli_fetch_array($sql1);
          $party = $sql1rw['name'];
          $address = $sql1rw['address'];
          $area = $sql1rw['area'];
          $mobile = $sql1rw['mobile'];

          //FIND TOTAL BILL and TOTAL PAID
          $sql2 = mysqli_query($db, "SELECT balance_amount from sales where name = '$party' group by bill_no");
          $sql2count = mysqli_num_rows($sql2);
          for ($z = 1; $z <= $sql2count; $z++) {
            $rw2 = mysqli_fetch_array($sql2);
            $total_bill += $rw2['balance_amount'];
          }
          //

          //FIND TOTAL PAID //
          $sql3 = mysqli_query($db, "SELECT paid from sales where name = '$party' and description!='Payment' and balance_amount<=0 group by bill_no");
          $sql3count = mysqli_num_rows($sql3);
          for ($x = 1; $x <= $sql3count; $x++) {
            $rw3 = mysqli_fetch_array($sql3);
            $total_paid += $rw3['paid'];
            $tt_paid = $total_paid;
          }
          //

          ///select each party
          $sql4 = mysqli_query($db, "SELECT balance_amount,date,paid,bill_no from sales where name = '$party' and amount!=0 group by bill_no");
          $sql4count = mysqli_num_rows($sql4);
          $o = 1;
          for ($h = 1; $h <= $sql4count; $h++) {
            $o++;

            $rw4 = mysqli_fetch_array($sql4);
            $bill = $rw4['balance_amount'];
            $balance_amount = $bill;
            $p = $rw4['paid'];
            $bill_no = $rw4['bill_no'];
            $bill = $bill - $p;
            $date = date('d-m-y', strtotime($rw4['date']));
            $sql_by_bill = mysqli_query($db, "SELECT sum(paid) FROM sales where description='Payment' and bill_no='$bill_no' GROUP BY bill_no");
            $paid_row = mysqli_fetch_array($sql_by_bill);
            $paid_by_bill = $paid_row['sum(paid)'];
            if (($bill - $paid_by_bill <= 0)) {
              $o++;
            }
            if (($bill - $paid_by_bill > 0)) {
              $bill = $bill - $tt_paid;
              $bill = $bill - $paid_by_bill;

              if ($bill <= 0) {
                $tt_paid = abs($bill);
              } else {
                $sqld = mysqli_query($db, "SELECT description,type,amount from sales WHERE bill_no='$bill_no'");
                $sqld_count = mysqli_num_rows($sqld);

                echo
                "
				<tr> 
				<td width='20%'>$date</td>
					<td width='15%' style='height:100px;'>$party</td>
					<td width='15%'>$area</td>
					<td width='15%'>$address</td>
					<td width='2%'>$mobile</td>

						<td><table class='table-bordered' width='100%;text-align:center;'> ";

                for ($ds = 0; $ds < $sqld_count; $ds++) {
                  $sqld_row = mysqli_fetch_array($sqld);
                  $description = $sqld_row['description'];
                  $type = $sqld_row['type'];
                  $amount = $sqld_row['amount'];


                  if ($description != 'Payment') {
                    echo "
					<tr style='text-align:center;'>
					<td>$description $type</td>
					</tr>
					";
                  }
                  // else
                  // {
                  // 	echo "	<tr style='text-align:center;'>
                  // 	<td colspan='3'>PAYMENT</td></tr>";
                  // }

                }
                echo "	<tr style='text-align:center;'>";
                echo "</table></td>
					<td align='right'>$bill</td>
				";
                $billtt += $bill;
                // if($o == $sql4count)
                // {
                // echo "<td align='right'><b>$billtt</b></td>";
                // }


                $tt_paid = 0;
                $bill_total += $bill;
              }
            }
            if ($h == $sql4count) {
              if ($billtt > 0) {
                echo "<td align='right'><b>$billtt</b></td>";
                echo "</tr>";
              }
            }
          }
          //for this customer total bill
        }
      }
    }

    ?>
    <tr style="font-weight:bold">
      <td colspan='7' align="right">TOTAL</td>
      <td align="right"><?php echo $bill_total ?></td>
    </tr>
  </table>

  <script>
    var expanded = false;

    function showCheckboxes() {
      var checkboxes = document.getElementById("checkboxes");
      if (!expanded) {
        checkboxes.style.display = "block";
        expanded = true;
      } else {
        checkboxes.style.display = "none";
        expanded = false;
      }
    }
  </script>


  <script>
    function selectall() {
      var chk = document.getElementsByClassName("chk");

      var checker = document.getElementById("checker");

      if (checker.checked == true) {
        for (var i = 0; i < chk.length; i++) {
          chk[i].checked = true;
        }
      } else {
        for (var i = 0; i < chk.length; i++) {
          chk[i].checked = false;
        }
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
</body>

</html>