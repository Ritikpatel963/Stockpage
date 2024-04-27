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

<?php
$id = $types = $product = $purity = $hsn = $purchase_date = '';

ob_start();
include_once('../connect.php');

$focus1 = 'autofocus';
$focus2 = '';
$advance = '';

$products = $db->query("SELECT * FROM `stock` ORDER BY id DESC");
?>

<?php
if (isset($_GET['cc'])) {
    $cc = $_GET['cc'];
}

?>

<?php
//FOR NEW sales BILL
if (!isset($_POST['bill']) && !isset($_GET['bill'])) {
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

    // INITIATE VARIABLES
    $remark = '';
    $name = '';
    $address = '';
    $mobile = '';
    $truck_no = '';
    $gstper = 3;
    $m_total = '';
    $m_balance = '';
    $total_amt = '';
    $pan = '';
    $amt = '';
    $date = date('Y-m-d');
    $focus1 = 'autofocus';
    $focus2 = '';
    $discount = '';
    $paid = '';
    $balance = '';
    $final = '';
    $cash = $credit_card = $chq_amount = 0;
    $online = 0;
    $cheque_no = '';
    $orn = '';
    $balance_amount = '';
    $orn_description = $area = "";
    $g_total = 0;
}

if (isset($_POST['bill'])) {
    $bill = $_POST['bill'];
}

if (isset($_GET['bill'])) {
    $bill = $_GET['bill'];
}


if (isset($_POST['bill']) || isset($_GET['bill'])) {
    $sql = "SELECT * FROM sales WHERE bill_no = '$bill' and description!='Payment' LIMIT 1";
    $sql_run = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($sql_run);

    // Set default values based on data types
    $m_total = isset($row['total_amt']) ? $row['total_amt'] : 0; // Assuming total_amt is numeric
    $date = isset($row['date']) ? $row['date'] : ''; // Assuming date is a string
    $name = isset($row['name']) ? $row['name'] : ''; // Assuming name is a string
    $party = $name;
    $address = isset($row['address']) ? $row['address'] : ''; // Assuming address is a string
    $area = isset($row['area']) ? $row['area'] : ''; // Assuming area is a string
    $mobile = isset($row['mobile']) ? $row['mobile'] : ''; // Assuming mobile is a string
    $final = isset($row['final']) ? $row['final'] : 0; // Assuming final is numeric
    $paid = isset($row['paid']) ? $row['paid'] : 0; // Assuming paid is numeric
    $balance = isset($row['balance']) ? $row['balance'] : 0; // Assuming balance is numeric
    $discount = isset($row['discount']) ? $row['discount'] : 0; // Assuming discount is numeric
    $pan = isset($row['pan']) ? $row['pan'] : ''; // Assuming pan is a string
    $remark = isset($row['remark']) ? $row['remark'] : 0; // Assuming remark is a string
    $focus2 = 'autofocus';
    $focus1 = '';
    $orn = isset($row['orn']) ? $row['orn'] : ''; // Assuming orn is a string
    $balance_amount = isset($row['balance_amount']) ? $row['balance_amount'] : 0; // Assuming balance_amount is numeric
    $orn_description = isset($row['orn_description']) ? $row['orn_description'] : ''; // Assuming orn_description is a string
}

if (isset($_POST['check'])) {
    $name = $_POST['name'];
    $sql = mysqli_query($db, "SELECT * FROM sales where name = '$name' and description!='Payment' limit 1");
    $sqlrow = mysqli_fetch_array($sql);
    $address = $sqlrow['address'];
    $area = $sqlrow['area'];
    $pan = $sqlrow['pan'];
    $mobile = $sqlrow['mobile'];
    $date = date('Y-m-d');
}
?>

<?php
include 'navbar.php';
?>
<!-- Main Content Wrapper -->
<main class="main-content w-full px-[var(--margin-x)] pb-8">

    <div class='container-fluid'>
        <div class='row'>
            <div class='col-md-12'><br><br></div>
            <div class='col-md-12'>
                <form action="index.php" method="post">
                    <table id="t_style" class="table-striped table-bordered" width="100%">
                        <tr style="font-weight:bold">
                            <td colspan='8'>
                                Customer Name
                                <input style='width:80%' type="text" name="name" list='name_list' value="<?php echo $name ?>" <?php echo $focus1 ?> />
                                <input type='submit' name='check' value="Check" style="padding: 10px;background: darkblue;color: white" />
                            </td>

                            <datalist id='name_list'>
                                <?php
                                $sql = "SELECT distinct name FROM sales GROUP BY name";
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

                        <tr style="font-weight:bold">
                            <td>Area: </td>
                            <td><input style='width:85%' type="text" name="area" value="<?php echo $area ?>" list='area_list' />
                                <datalist id='area_list'>
                                    <?php
                                    $sql = mysqli_query($db, "SELECT distinct area FROM sales");
                                    $sql_count = mysqli_num_rows($sql);
                                    for ($v = 1; $v <= $sql_count; $v++) {
                                        $row = mysqli_fetch_array($sql);
                                        $area = $row['area'];
                                        echo "<option>$area</option>";
                                    }
                                    ?>
                                </datalist>


                            </td>
                            <td>Address: </td>
                            <td><input style='width:85%' type="text" name="address" value="<?php echo $address ?>" list='address_list' />
                                <datalist id='address_list'>
                                    <?php
                                    $sql = mysqli_query($db, "SELECT distinct address FROM sales");
                                    $sql_count = mysqli_num_rows($sql);
                                    for ($v = 1; $v <= $sql_count; $v++) {
                                        $row = mysqli_fetch_array($sql);
                                        $address = $row['address'];
                                        echo "<option>$address</option>";
                                    }
                                    ?>
                                </datalist>

                            </td>
                            <td>Pan no. / Adhar no.: </td>
                            <td> <input type="text" name="pan" id='pan' style="width: 72.8%" value="<?php echo $pan ?>" </td>
                            <td>Mobile No: </td>
                            <td> <input style='width:100%' type="text" name="mobile1" value="<?php echo $mobile ?>" />
                        </tr>
                    </table>

                    <br />

                    <table id="t_style" class="table-striped table-bordered" width="100%">
                        <tr>
                            <td colspan='3' align="left" style="font-weight:bold">
                                Date <input type='date' name='date' value='<?php echo $date ?>' />
                            </td>
                            <td colspan='10' align="right" style="font-weight:bold">
                                B.No <?php echo $bill ?></td>
                        </tr>
                        <tr style="background-color:#F7FEDE; font-weight:bold">
                            <td>#</td>
                            <td width="7%">Type</td>
                            <td width='11%'>Product Name </td>
                            <td width="7%">Hsn Code</td>
                            <td>Qty</td>
                            <td>Gross Weight<br>(Grams)</td>
                            <td>Stone Weight</td>
                            <td>Net Weight<br>(Grams)</td>
                            <td>Stone Rate</td>
                            <td align='center' width='5%'>Gram Rate</td>
                            <td align='center' width='1%'>Labour/Gm</td>
                            <td align='center' width='1%'>Amount<br>(Rs.)</td>
                            <td align='center'>+Tax%</td>
                            <td align='right' width="4%">After Tax </td>
                            <td align='right'>Add / Del</td>
                        </tr>

                        <?php
                        $tweight = 0;
                        $total_amt = 0;
                        $gstper = '';
                        $discount = 0;
                        $dis_amt = 0;
                        $discount_final =  0;
                        $previous_amt = 0;

                        $sql = "SELECT * FROM sales WHERE bill_no = '$bill' and description!='Payment'";
                        $sql_run = mysqli_query($db, $sql);
                        $count = mysqli_num_rows($sql_run);
                        $i = 0;
                        $main_total = 0;
                        if ($count > 0) {
                            while ($i < $count) {
                                $i++;
                                $row = mysqli_fetch_array($sql_run);
                                $id = $row['id'];
                                $description = $row['description'];
                                $amount = $row['amount'];
                                $total_amt = $total_amt + $row['amount'];
                                $weight = $row['weight'];
                                $gweight = $row['gweight'];
                                $tweight = $tweight + $weight;
                                $qty = $row['qty'];
                                $rate = $row['rate'];
                                $hsn = $row['hsn'];
                                $purity1 = $row['purity'];
                                $lbr1 = $row['lbr'];
                                $type = $row['type'];
                                $gstper = $row['gst'];
                                $online = $row['online'] ?? 0;
                                $cash = $row['cash'] ?? 0;
                                $amt = $row['amt'];
                                $discount = $row['discount'];
                                $dis_amt = $row['dis_amt'];
                                $dweight = $row['dweight'];
                                $drate = $row['drate'];
                                $sweight = $row['sweight'];
                                $srate = $row['srate'];
                                $gwt = $row['gwt'];
                                $discount_final = $row['discount_final'];
                                $balance_amount = $total_amt - $orn;
                                if ($discount_final == 0) {
                                    $discount_final = $balance_amount - $paid;
                                }
                                $advance = $row['advance'];
                                $g_total = $discount_final;

                                echo
                                "<tr>
			<td>$i</td>
			<td>$type</td>
			<td>$description</td>
			<td>$hsn</td>
			<td>$qty</td>
			<td align='center'>$gweight</td>
			<td align='center'>$weight</td>
			<td align='center'>$sweight</td>
			<td align='center'>$srate</td>
			<td align='center'>$rate</td>
			<td align='center'>$lbr1</td>
			<td align='center'>$amt</td>
			<td align='center'>$gstper</td>
			<td align='right'>$amount</td>
		
			<td align='right'>
			<form action='index.php?bill=$bill' method='post'>
				<input type='hidden' name='del' value='$id' />
				<input type='hidden' name='mobile' value='$mobile'/>
				<input type='submit' name='delete' value='del' style='padding: 10px;background: red;color: white'/>
			</form>
			</td>
			</tr>
			";
                            }
                            // CALCULATION PREVIOUS AMOUNT -----------------------------------

                            $sql_pr = mysqli_query($db, "SELECT  balance_amount ,paid FROM sales where name ='$party' and bill_no<'$bill' and description!='Payment' GROUP BY bill_no");

                            $sqlrow_pr_count = mysqli_num_rows($sql_pr);
                            while ($sqlrow_pr_count != 0) {

                                $sqlrow_pr = mysqli_fetch_array($sql_pr);
                                $previous_amt += $sqlrow_pr['balance_amount'] - $sqlrow_pr['paid'];
                                $sqlrow_pr_count--;
                            }
                            $sql_pr2 = mysqli_query($db, "SELECT  sum(paid) as paids  FROM sales where name ='$party' and bill_no<'$bill' and description='Payment' GROUP BY name");
                            if ($sql_pr2->num_rows > 0) {
                                $sqlrow_pr2 = mysqli_fetch_array($sql_pr2);
                                $previous_amt -= $sqlrow_pr2['paids'];
                            }

                            $g_total += $previous_amt;

                            // END ------------------------------------------------------------
                        }

                        ?>

                        <tr>
                            <td> </td>

                            <td>
                                <input type='text' id='type' name='type' style="width:100%;" list='type_list' onkeypress="return noenter()">
                                <datalist id='type_list'>
                                    <option>gold</option>
                                    <option>gold_20k</option>
                                    <option>gold_22k</option>
                                    <option>silver</option>
                                    <option>silver_925</option>
                                    <option>diamond</option>
                                </datalist>
                            </td>

                            <td>
                                <select name="description" id="description" style="width: 100%;padding: 3px;">
                                    <option value="" disabled selected>-- SELECT --</option>
                                </select>
                            </td>


                            <td><input type="text" name="hsn" style='width:100%;text-align:center' id="hsn" onkeypress="return noenter()"></td>

                            <td><input type="text" name="qty" style='width:100%;text-align:center' id="qty" onkeypress="return noenter()"></td>

                            <td><input type="text" name="gweight" style='width:100%;text-align:center' id="gweight" onkeypress="return noenter()"></td>

                            <td><input type="text" name="sweight" value="0" style='width:100%;text-align:center' id="sweight" onkeypress="return noenter()"></td>

                            <td><input type="text" name="weight" readonly style='width:100%;text-align:center' id="weight" onkeypress="return noenter()"></td>

                            <td><input type="text" name="srate" value="0" style='width:100%;text-align:center' id="srate" onkeypress="return noenter()"></td>


                            <td><input type="text" name="rate" style='width:100%;text-align:center' id="rate" onkeypress="return noenter()"></td>

                            <td><input type="text" name="lbr" style='width:100%;text-align:center' id="lbr" onkeypress="return noenter()" onblur="amount_sum()"></td>


                            <td><input type="text" name="amt" style='width:100%;text-align:center' id="amt" onfocus="amount_sum()" onkeypress="return noenter()"></td>


                            <td><input type="text" value="3" readonly name="gstper" style='width:100%;text-align:center' id="gstper" onkeypress="return noenter()" onblur="amount_sum()"></td>


                            <td><input type="text" name="amount" style='width:50px;text-align:center' id="amount" onkeypress="return noenter()"></td>

                            <input type='hidden' name='purchase_date' id='purchase_date' value='<?php echo $purchase_date ?>' onkeypress="return noenter()" />
                            <td align="right"> <input type="submit" name="add" value="add" style="padding: 10px;background: darkblue;color: white" /></td>
                        </tr>
                        <tr>
                            <td rowspan="9"></td>
                            <td colspan="5">&nbsp;</td>
                        </tr>


                        <tr>
                            <td colspan='9'> </td>
                            <td colspan="4"><strong>TOTAL AMOUNT WITH GST</strong></td>
                            <td align='right'><input type='text' name='total' style='width:100%;text-align:center' value='<?php echo $total_amt ?>' id='total' />
                            </td>
                        </tr>

                        <tr>
                            <td colspan='9'> </td>
                            <td colspan="4"><strong>ORNAMENTS RETURN</strong></td>
                            <td align='right'><input type='text' name='orn' style='width:100%;text-align:center' value='<?php echo $orn ?>' id='orn' onkeyup='orn_balance()' />
                            </td>
                        </tr>
                        <tr style="display: none;" class="return_description">
                            <td colspan='9'> </td>
                            <td colspan="3"><strong>RETURN DESCRIPTION</strong></td>
                            <td align='right' colspan="2"><input type='text' name='orn_description' style='width:100%;text-align:center' value='<?php echo $orn_description ?>' id='orn' />
                            </td>
                        </tr>


                        <tr>
                            <td colspan='9'> </td>
                            <td colspan="4"><strong>BILL AMOUNT</strong></td>
                            <td align='right'><input type='text' name='balance_amount' style='width:100%;text-align:center' value='<?php echo $balance_amount ?>' id='balance_amount' onfocus='orn_balance()' />
                            </td>
                        </tr>

                        <tr style="">
                            <td colspan='9'></td>
                            <td colspan="4"><strong>AMOUNT PAID BY CUSTOMER</strong></td>
                            <td><input type='text' name='paid' id="paid" style="width: 100%; text-align: center;" value='<?php echo $paid ?>' onkeyup="dis1()" /></td>
                        </tr>

                        <tr>
                            <td colspan='9'> </td>
                            <td colspan="4"><strong>DISCOUNT</strong></td>
                            <td align='right'><input type='text' name='discount' style='width:100%;text-align:center' value='<?php echo $discount ?>' id='discount_ro' onkeyup="dis1()" />
                            </td>
                        </tr>

                        <tr>
                            <td colspan='9'> </td>
                            <td colspan="4"><strong>BALANCE AMOUNT</strong></td>
                            <td align='right'><input type='text' name='discount_final' style='width:100%;text-align:center' value='<?php echo $discount_final ?>' id='discount_final' />
                            </td>
                        </tr>

                        <tr style="display:none">
                            <td colspan="4"><strong>FINAL AMOUNT : </strong></td>
                            <td width="5%"><input type='text' name='balance' id="balance" style="width: 100%; text-align: center;" value='<?php echo $balance ?>' /></td>
                        </tr>
                        <tr>
                            <td colspan='9'> </td>
                            <td colspan="4"><strong>PREVIOUS AMOUNT</strong></td>
                            <td align='right'><input type='text' name='previous_amt' style='width:100%;text-align:center' value='<?php echo $previous_amt ?>' id='previous_amt' />
                            </td>
                        </tr>

                        <tr>
                            <td colspan="9"></td>
                            <td colspan="4"><strong>GRAND TOTAL : </strong></td>
                            <td> <input type='text' name='g_total' style='width:100%;text-align:center' value='<?php echo $g_total ?? 0 ?>' id='g_total' />
                            </td>
                        </tr>

                        <tr style="font-weight:bold">
                            <td align="center" colspan="2"><strong>Card:</strong></td>
                            <td>
                                <input type="number" name="remark" id="remark" style="width: 100%" value="<?php echo $remark ?>" class="form-control">
                            </td>
                            <td align="center">Cash :</td>
                            <td>
                                <input type="number" name="cash" value="<?= $cash ?>" class="form-control">
                            </td>
                            <td align="center">Online :</td>
                            <td>
                                <input type="number" name="online" value="<?= $online ?>" class="form-control">
                            </td>
                            <td style="display: none;background-color:lightblue" align="center">For</td>
                            <td style="display: none;" colspan='4'>
                                <select name='firmname' style="background-color:#cfecec" class='form-control'>
                                    <?php
                                    $sql = mysqli_query($db, "SELECT * FROM firm");
                                    $sql_count = mysqli_num_rows($sql);
                                    for ($t = 1; $t <= $sql_count; $t++) {
                                        $rw = mysqli_fetch_array($sql);
                                        $name = $rw['name'];
                                        echo "<option>$name</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td align="right" colspan="2">
                                <input type="hidden" name="bill" value="<?php echo $bill ?>" />
                                <div class="d-flex" style="gap: 10px;">
                                    <input type="submit" name="finish" value="Final Invoice" id='finish' style="padding: 10px;background: darkblue;color: white" />

                                    <?php if (isset($_GET['bill'])) { ?>
                                        <input type="submit" name="finish2" value="Invoice Copy" style="padding: 10px;background: darkblue;color: white" />
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['add'])) {
        $bill = $_POST['bill'];
        $date = $_POST['date'];
        $type = $_POST['type'];
        $qty = $_POST['qty'];
        //description  details//
        $description = $_POST['description'];
        $discount = $_POST['discount'];
        $dis_amt = $_POST['dis_amt'];

        //customer details //
        $name = trim($_POST['name']);
        $address = $_POST['address'];
        $area = $_POST['area'];
        $mobile = $_POST['mobile1'];
        $pan = $_POST['pan'];
        //size
        $weight = $_POST['weight'];
        $gweight = $_POST['gweight'];
        $rate = $_POST['rate'];
        $purity = $_POST['purity'];
        $hsn = $_POST['hsn'];
        $lbr = $_POST['lbr'];
        $amount = $_POST['amount'];
        $gstper = $_POST['gstper'];
        $amt = $_POST['amt'];
        $remark = $_POST['remark'];
        $dweight = $_POST['dweight'];
        $drate = $_POST['drate'];
        $sweight = $_POST['sweight'];
        $srate = $_POST['srate'];
        $gwt = $_POST['gwt'];

        $purchase_date = $_POST['purchase_date'];
        $orn_description = $_POST['orn_description'];

        $firmname = $_POST['firmname'];

        $prodctDetails = $db->query("SELECT * FROM stock WHERE `name` LIKE '$description'")->fetch_assoc();
        if ($prodctDetails) {
            if ($prodctDetails['weight'] > $gweight) {
                $db->query("UPDATE stock SET weight = (weight - '$gweight') WHERE `name` LIKE '$description'");
                $sql = mysqli_query($db, "INSERT INTO sales (qty, bill_no, name, address, date, description, mobile, gweight, weight,rate,purity,hsn,lbr,amount,type,gst,amt,discount,dis_amt,pan,remark,purchase_date,dweight,drate,sweight,srate,gwt,orn_description,area) 
            VALUES 
            ('$qty', '$bill', '$name', '$address', '$date', '$description',  '$mobile', '$gweight', '$weight','$rate','$purity','$hsn','$lbr','$amount','$type','$gstper','$amt','$discount','$dis_amt','$pan','$remark','$purchase_date','$dweight','$drate','$sweight','$srate','$gwt','$orn_description','$area')") or
                    die(mysqli_error($db));;

                $sql = mysqli_query($db, "INSERT IGNORE INTO product SET name = '$description'");
                header("location:index.php?bill=$bill");
            } else {
                echo '<script>alert("Product weight is insufficient!");location.href = document.referrer</script>';
            }
        }
    }
    ?>

    <?php
    if (isset($_POST['finish'])) {
        $bill = $_POST['bill'];
        $mobile = $_POST['mobile1'];
        $name = trim($_POST['name']);
        $address = $_POST['address'];
        $area = $_POST['area'];
        $pan = $_POST['pan'];
        $paid = $_POST['paid'];
        $final = $_POST['final'];
        $balance = $_POST['balance'];
        $remark = $_POST['remark'];
        $date = $_POST['date'];
        $orn = $_POST['orn'];
        $balance_amount = $_POST['balance_amount'];
        $discount_final = $_POST['discount_final'];
        $tt = $_POST['total'];
        $firmname = $_POST['firmname'];
        $discount = $_POST['discount'];
        $orn_description = $_POST['orn_description'];

        $cash = $_POST['cash'];
        $online = $_POST['online'];

        $sql = "UPDATE sales SET 
		mobile = '$mobile', 
		date = '$date',
		name = '$name', 
		address = '$address',
		area='$area',
		cash='$cash',
		online='$online',
		total_amt='$tt',
		final='$final',
		balance='$balance',
		pan='$pan',
		remark='$remark',
		orn = '$orn',
		discount='$discount',
		balance_amount = '$balance_amount',
		discount_final = '$discount_final',
		firmname = '$firmname',
		paid = '$paid',
		bp_remark = 'normal',
		orn_description='$orn_description'
		WHERE bill_no = '$bill' and description!='Payment'";

        if ($sql_run = mysqli_query($db, $sql)) {
            $sql2 = mysqli_query($db, "INSERT ignore INTO address set name = '$address' ");
            header("location:print.php?bill=$bill&mobile=$mobile");
        } else {
            echo mysqli_error($db);
        }
    }

    if (isset($_POST['finish2'])) {
        $bill = $_POST['bill'];
        $mobile = $_POST['mobile1'];

        header("location:print2.php?bill=$bill&mobile=$mobile");
    }
    ?>

    <?php
    if (isset($_POST['delete'])) {
        $mobile = $_POST['mobile'];
        $del = $_POST['del'];

        //THEN DELETE FROM sales
        $sql = "DELETE FROM sales WHERE id = '$del'";
        $sql_run = mysqli_query($db, $sql);
        header("location:index.php?bill=$bill");
    }
    ?>

    <script type="text/javascript">
        var total = document.getElementById('total').value;
        var paid = document.getElementById('paid').value;
        var balance = document.getElementById('balance');
        if (paid == "") {
            var total = document.getElementById('total').value;
            balance.value = total
        }

        function dis() {

            var total = document.getElementById('total').value;
            var discount = document.getElementById("discount").value;
            var final = document.getElementById('final');
            var balance = document.getElementById('balance');
            final.value = (total - discount);
            balance.value = (total - discount);
        }

        function diss() {
            var tweight = document.getElementById("tweight").value;
            var disper = document.getElementById("disper").value;
            var discount = document.getElementById("discount");
            discount.value = tweight * disper;
        }

        function amount_sum() {
            var stwt, strate, lbr, amount, gstper, diawt, diarate, gwt, grate, nwt, dis_amt, discount;
            amount = document.getElementById("amount");
            weight = document.getElementById("gweight").value;
            rate = document.getElementById("rate").value;
            lbr = document.getElementById("lbr").value;
            gstper = document.getElementById("gstper").value;
            dis_amt = document.getElementById("dis_amt");
            var amt1 = document.getElementById("amt");
            var type = document.getElementById("type").value;
            var sweight = document.getElementById('sweight').value;
            var srate = document.getElementById('srate').value;

            if (type == 'gold_22k' || type == 'gold_20k') {
                var mix = +rate + +(rate * (lbr / 100));
            } else {
                var mix = +rate + +lbr;
            }

            if (type != 'diamond') {
                var amt = mix * (weight - sweight) + parseFloat(srate);

                if (type == 'gold' || type == 'silver') {
                    var diss = discount * weight;
                } else {
                    var diss = discount;
                }

                var amt_dis = amt;
            } else {
                var amt_dis = Number(amt1.value);
            }

            nweight = document.getElementById("weight")
            nweight.value = (weight - sweight);

            if (type != 'diamond') {
                amt1.value = amt;
            }

            var gstamount = (amt_dis / 100) * gstper;
            amount.value = Math.round(amt_dis + gstamount);
        }

        $("#gweight").keyup(function() {
            var sweight = document.getElementById('sweight').value;
            weight = document.getElementById("gweight").value;
            nweight = document.getElementById("weight")
            nweight.value = (weight - sweight);
        });

        $("#sweight").keyup(function() {
            var sweight = document.getElementById('sweight').value;
            weight = document.getElementById("gweight").value;
            nweight = document.getElementById("weight")
            nweight.value = (weight - sweight);
        });
    </script>
    <script type="text/javascript">
        function dis1() {

            var balance_amount = document.getElementById('balance_amount').value;
            var paid = document.getElementById('paid').value;
            var balance = document.getElementById('balance');
            var discount = document.getElementById('discount_ro').value;
            var discount_final = document.getElementById('discount_final');
            discount_final.value = Math.round((balance_amount - paid) - discount);
            balance.value = paid;

            var previous_amt = document.getElementById('previous_amt').value;

            $('#g_total').val(Math.round((balance_amount - paid) + +previous_amt) - discount);
        }

        function orn_balance() {

            var orn = document.getElementById('orn').value;
            // var discount = document.getElementById('discount').value;
            var total = document.getElementById('total').value;
            var balance_amount = document.getElementById('balance_amount');
            balance_amount.value = Math.round(total - orn);
            var paid = document.getElementById('paid').value;
            var balance_amount2 = document.getElementById('balance_amount').value;

            var previous_amt = document.getElementById('previous_amt').value;
            $('#g_total').val(balance_amount2 - paid + +previous_amt);

            dis1();
            //	paid.value = balance_amount.value;
        }
    </script>
    <script type='text/javascript'>
        //AJAX REQUEST WITHOUT PAGE RELOAD // REQUEST FROM DOM2.PHP
        function zap() {
            var code = document.getElementById("code").value;
            const xhr = new XMLHttpRequest();
            xhr.onload = function() {
                const serverResponse = document.getElementById("variables");
                serverResponse.innerHTML = this.responseText;

                //DESCRIPTION
                var pp = document.getElementById('pp').value;
                var dd = document.getElementById('description');
                dd.value = pp;

                //TYPE
                var pp2 = document.getElementById('pp2').value;
                var type = document.getElementById('type');
                type.value = pp2;

                //WEIGHT
                var pp3 = document.getElementById('pp3').value;
                var weight = document.getElementById('weight');
                weight.value = pp3;

                //PURITY
                var pp4 = document.getElementById('pp4').value;
                var purity = document.getElementById('purity');
                purity.value = pp4;

                //HSN
                var pp5 = document.getElementById('pp5').value;
                var hsn = document.getElementById('hsn');
                hsn.value = pp5;

                //PURCHASE DATE
                var pp6 = document.getElementById('pp6').value;
                var purchase_date = document.getElementById('purchase_date');
                purchase_date.value = pp6;
                var rate = document.getElementById('rate').focus();

                typer();


            }
            xhr.open("POST", "dom2.php");
            xhr.setRequestHeader("Content-Type", "application/x-www-Form-urlencoded");
            xhr.send("code=" + code);
        }

        chkorn();
        $('#orn').keyup(function() {
            chkorn();
        });

        function chkorn() {
            ($('#orn').val() > 0) ? $('.return_description').css('display', ""): $('.return_description').css('display', "none");
        }

        $('#type').keyup(function() {
            var id = $(this).val();

            if (id == "diamond") {
                $('#rate').attr("readonly", "true");
                $('#lbr').attr("readonly", "true");
            } else {
                $('#rate').removeAttr("readonly")
                $('#lbr').removeAttr("readonly")
            }

            $.ajax({
                url: "stock.php",
                type: "post",
                data: {
                    type: id,
                    getDetails: 1
                },
                success: function(resp) {
                    $('#description').html(resp);
                }
            });
        });

        $('#description').change(function() {
            $('#hsn').val("7113");
        });
    </script>
</main>
</div>

<script type="text/javascript">
    function noenter() {
        return !(window.event && window.event.keyCode == 13);
    }
</script>
</body>

</html>