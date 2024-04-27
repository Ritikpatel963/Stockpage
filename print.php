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

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        position: relative;
    }

    .header h1 {
        margin: 0;
        color: #333;
    }

    /* CSS to adjust image overlay */

    /* .header img {
			position: absolute;
			top: 80%;
			transform: translateY(-50%);
			z-index: 1;
		}

		.header img.left {
			left: 10px;
			z-index: 1;
		}

		.header img.right {
			right: 10px;
			z-index: 1;
		} */


    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    .total {
        text-align: right;
        margin-top: 20px;
    }

    .total span {
        font-weight: bold;
    }

    .invoice-details {
        display: flex;
        flex-direction: row;
        margin: 0 0 10px 0;
        justify-content: space-between;
        align-items: center;
        padding: 2px;
        border-bottom: 1px solid black;
    }

    .heading-after-bill-info {
        border: 1px solid black;
        margin: 10px 0;
    }

    .heading-after-bill-info h2 {
        font-size: 24px;
    }

    .product_total {
        display: flex;
        align-items: center;
        gap: 281px;
        border-bottom: 1px solid black;
    }

    ol li {
        font-size: 10px;
        font-weight: 500;
    }

    /* Media query for print */
    @media print {
        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-sm-6,
        .col-md-6,
        .col-lg-6 {
            width: 50%;
        }

        .col-sm-12,
        .col-md-12 {
            width: 100%;
        }

        .col-sm-8 {
            width: 60%;
        }

        .col-sm-4 {
            width: 40%;
        }

        .col-sm-7 {
            width: 55%;
        }

        .col-sm-5 {
            width: 45%;
        }

        .col-md-4 {
            width: 33.33333%;
        }

        ol li,
        p,
        th,
        td {
            font-size: 8px;
            font-weight: 500;
        }

        .heading-after-bill-info h2 {
            font-size: 15px;
        }

        .bill-info h2 {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
        }

        .bill-info div {
            font-size: 10px;
        }

        .invoice-details h2 {
            font-size: 16px;
            margin: 0;
        }

        .invoice-details {
            font-size: 10px;
        }

        #footer_table th {
            background-color: transparent;
            padding: 10px;
            border: 1px solid #000;
            font-size: 12px;
        }

        th {
            font-weight: bold;
        }
    }

    .bank-address p {
        padding: 5px;
        margin: 0 !important;
    }
</style>

<!-- Main Content Wrapper -->
<main class="main-content w-full px-5 pb-8 pt-6">
    <?php
    if (isset($_GET['bill'])) {
        $bill = $_GET['bill'];
        $billx = floor($bill);
        $sql = "SELECT * FROM sales WHERE bill_no = $bill and description!='Payment'  LIMIT 1";
        $sql_run = mysqli_query($db, $sql);

        if ($sql_run->num_rows > 0) {
            $row = mysqli_fetch_array($sql_run);

            $name = $row['name'];
            $address = $row['address'];
            $contact = $row['mobile'];
            $pan = $row['pan'];
            $remark = $row['remark'];
            $gst = $row['gst'];
            $date = $row['date'];
            $firmname = $row['firmname'];
        }

        if (isset($firmname)) {
            ///FIRM DETAILS
            $sql4 = "SELECT * FROM firm WHERE name = '$firmname'";
            $sql_run4 = mysqli_query($db, $sql4);
            $row4 = mysqli_fetch_array($sql_run4);
            $address4 = $row4['address'] ?? '';
            $phone4 = $row4['phone'] ?? '';
            $email4 = $row4['email'] ?? '';
            $gst = $row4['gst'] ?? '';
            ///FIRM DETAILS
        }
    }
    ?>


    <div class='container-fluid'>
        <div class="row">
            <div class='col-md-12'>
                <div class="header" style="display: flex;justify-content: space-between;align-items: center;">
                    <img src="../NARBARIY.png" alt="Left Image" class="left" width="70px">
                    <div style="position: relative;top: 12px;">
                        <sup style="font-size: 10px;right: -55px">निधि</sup>
                        <sup style="font-size: 10px;left: 180px">जबलपुर न्यायालय के अंतर्गत</sup>
                        <!-- <h1>नरवरिया आभूषण</h1> -->
                        <img src="../logo.png" alt="" style="width: 30%;position: relative;left: 35%;">
                        <!-- <small>शॉप नं. 2. शंभूश्री अपार्टमेंट, एम. आर. 4 रोड विजय नगर, जबलपुर (म.प्र.) 482002</small> -->
                    </div>
                    <img src="../hallmark.png" alt="Right Image" class="right" width="80px">
                </div>
                <div class="text-end">
                    <small style="text-transform: uppercase;">Customer Copy</small>
                </div>
            </div>
            <div class="row" style="border: 1px solid;padding-top: 5px">
                <div class='col-md-12'>
                    <div class="invoice-details">
                        <div>
                            <h2 class="m-0">Tax Invoice</h2>
                        </div>
                        <div class="d-flex" style="gap: 75px">
                            <div><strong>Bill Number:</strong> NNA <?= date("y") . "-" . date("y", strtotime("+1 Year")) . '/00' . $billx ?></div>
                            <div><strong>Date:</strong> <?php echo  date("M d,Y", strtotime($date)) ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-7">
                        <div class="bill-info">
                            <div>NIDHI NARVARIYA ABHUSHAN</div>
                            <div>SHOP NO. 2, SHAMBHU SHREE APT. MR-4 ROAD,</div>
                            <div>VIJAY NAGAR, JABALPUR (M.P) 482002</div>
                            <div><strong>Phone:</strong> +91-9685242115, 0761-4700474 </div>
                            <div><strong>GSTIN:</strong> 23CGSPN6374G1ZX</div>
                            <div><strong>REG. NO.:</strong> JABA23102SE016034</div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="bill-info">
                            <h2>Customer Details:</h2>
                            <!-- <div><strong>Name:</strong> AKSHAT NARVARIYA</div>
                            <div>523, NARVARIYA BUILDING, CHERITAL</div>
                            <div>MAIN RD. INFRONT OF GIRLS SCHOOL</div>
                            <div>JABALPUR</div>
                            <div><strong>Phone:</strong> +91-9685242115</div> -->
                            <div><strong>Name:</strong> <?php echo $name ?></strong></div>
                            <div><strong>Address:</strong> <?php echo $address ?></div>
                            <div><strong>Phone:</strong> <?php echo $contact ?></div>
                        </div>
                    </div>
                </div>
                <div class="heading-after-bill-info" style="text-align: center;padding: 10px;">
                    <h2 class="m-0">सोना चांदी के विश्वसनीय एवं नवीनतम आभूषणों का एकमात्र प्रतिष्ठान</h2>
                </div>
                <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Product Description</th>
                            <th>HSN Code</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Gross Weight</th>
                            <th>Stone Weight</th>
                            <th>Net Weight</th>
                            <th>Price</th>
                            <th>CGST (1.50%)</th>
                            <th>SGST (1.50%)</th>
                            <th>Product Value (Rs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM sales WHERE bill_no = '$bill' and description!='Payment' ";
                        $sql_run = mysqli_query($db, $sql);
                        $count = mysqli_num_rows($sql_run);

                        $margin = 6 - $count;

                        $i = 1;
                        $m_total = 0;
                        $m_amount = 0;
                        $s_amount = 0;
                        $tdix = 0;
                        //tax variables
                        $sg_total = 0;
                        $cg_total = 0;
                        $hide = "";

                        if ($i > 0) {
                            $totalRate = 0;
                            $totalQty = 0;
                            $totalProductPrice = 0;
                            $totalStonePrice = 0;
                            $totalCGstAmt = 0;
                            $totalSGstAmt = 0;

                            while ($i <= $count) {
                                $row = mysqli_fetch_assoc($sql_run);
                                $id = $row['id'];
                                $code = $row['code'];
                                $particulars = $row['description'];
                                $hsn_code = (float) $row['hsn'];
                                $orn = $row['orn'];
                                $discount = $row['discount'];
                                $balance_amount = $row['balance_amount'];
                                $type = $row['type'];
                                $amt = $row['dis_amt'];
                                $gweight = (float)$row['gweight'];
                                $weight = (float)$row['weight'];
                                $rate = (float)$row['rate'];
                                $lbr = $row['lbr'];
                                $amt1 = (float)$row['amount'];
                                $amt2 = $row['amt'];
                                $paid = $row['paid'];
                                $qty = $row['qty'];
                                $balance = $row['balance'];
                                $purity = $row['purity'];
                                $rate = (float)$row['rate'];

                                $final = $row['final'];
                                $dweight = $row['dweight'];
                                $drate = $row['drate'];
                                $sweight = $row['sweight'];
                                if ($sweight == 0.00)
                                    $sweight = "";
                                $srate = $row['srate'];
                                if ($srate == 0.00)
                                    $srate = 0;

                                $totalStonePrice += $srate;
                                $gwt = $row['gwt'];
                                $orn_description = $row['orn_description'];
                                if ($orn_description == "")
                                    $hide = "display:none";
                                $disx = $amt2 - $amt;
                                $tdix += $disx;
                                //tax rate %
                                $tax = $row['gst'];
                                $s_tax = $tax / 2;
                                $c_tax = $tax / 2;

                                $remark = $row['remark'];
                                $cash = $row['cash'];
                                $online = $row['online'];

                                $amt = $balance;
                                //TAXABLE AMNT				

                                $amt_r = number_format((float)$amt);
                                $tax_amt = ($amt / 100) * $tax;
                                $tax_amt = round($tax_amt);

                                //SGST TAX AMNT
                                $sgst_amt = (float) (($amt2 - $srate) * ($s_tax * 1 / 100));

                                //CGST TAX AMNT
                                $cgst_amt = (float)(($amt2 - $srate) * ($c_tax * 1 / 100));

                                //FIND TOTAL STATE TAX
                                $sg_total = round($sg_total + $sgst_amt);

                                //FIND TOTAL CENTRAL TAX
                                $cg_total = round($cg_total + $cgst_amt);

                                // FIND TOTAL TAX
                                $total_tax = round($sg_total + $cg_total);

                                //SUPER TOTAL
                                $s_amount = round($s_amount + $amt);
                                $s_total = round($row['total_amt']);

                                $discount_final = $row['discount_final'];

                                $totalCGstAmt += $cgst_amt;
                                $totalSGstAmt += $sgst_amt;
                        ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $particulars ?></td>
                                    <td><?= $hsn_code ?></td>
                                    <td><?= ($type == "gold_22k" || $type == "gold_20k") ? "Gold" : (($type == "silver_925") ? "Silver 925" : ucfirst($type)) ?></td>
                                    <td><?php $totalQty += $qty;
                                        echo $qty; ?></td>
                                    <td><?= $gweight ?></td>
                                    <td><?= $sweight ?></td>
                                    <td><?= $weight ?></td>
                                    <td>₹<?php $totalRate += ($amt2 - $srate);
                                            echo number_format(($amt2 - $srate), 2); ?></td>
                                    <td>₹<?= number_format($cgst_amt, 2) ?></td>
                                    <td>₹<?= number_format($sgst_amt, 2) ?></td>
                                    <td class="text-end">₹<?php $totalProductPrice += (($weight * $rate) + $cgst_amt + $sgst_amt);
                                                            echo number_format((($weight * $rate) + $cgst_amt + $sgst_amt), 2); ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?= $totalQty ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>₹<?= number_format($totalRate, 2) ?></td>
                            <td>₹<?= number_format($totalCGstAmt, 2) ?></td>
                            <td>₹<?= number_format($totalSGstAmt, 2) ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="row bank-address" style="
    margin: 0;
    padding: 0;
    --bs-gutter-x: 20px;
">
                    <div class="col-md-6">
                        <div style="margin: 10px; border: 1px solid black;padding: 5px;">
                            <p class="m-0">Bank Details : HDFC Bank</p>
                            <p class="m-0">Branch : Vijay Nagar, Jabalpur</p>
                            <p class="m-0">Name: NIDHI NARVARIA ABHUSHAN</p>
                            <p class="m-0">A/C: 50200088307803 | IFSC Code : HDFC0001282 </p>
                            <!-- Add more bank addresses as needed -->
                        </div>
                        <div style="margin: 10px;padding: 5px; border: 1px solid black; border-left: 2px dotted black; border-bottom: 1px dotted black;">
                            <p style="margin: 0;font-size: 9px">Total Amount include Hallmark Charge @ Rs 50 Per Peice</p>
                        </div>

                        <div style="margin-top: 10px;">
                            <ol style="gap: 7px; display: grid;list-style: auto;">
                                <li>Hallmark Purity Will Be Considered Void If Repaired and Polished Any Where Else</li>
                                <li>Weight Verified And Received Goods In Good Condition.</li>
                                <li>फैंसी जेवर की टूट-फूट की गारंटी नहीं है। बिका हुआ माल वापिस नहीं होगा।</li>
                                <li>जेवर से संबंधित लेन-देन करते समय पर्ची की मूलप्रति लाना आवश्यक है।</li>
                                <li>आभूषण का लेन-देन हमारे प्रतिष्ठान के नियमानुसार किया जावेगा।</li>
                                <li>मैंने ऊपर लिखी आभूषणों की मात्रा, कीमत, डिजाइन, विनिर्देश से स्वयं को संतुष्ट कर लिया है। जेवर एकदम सही और स्वीकार्य स्थिति में प्राप्त कर लिये हैं।</li>
                            </ol>
                        </div>

                        <div class="col-md-12">
                            <p style="font-size: 8px;margin: 5px 0">Net Invoice Value Includes Gold/Silver Value, Product Making Charges</p>
                            <p style="font-size: 8px;margin: 5px 0">GST, And Stone Cost (As Applicable)</p>
                            <p style="font-size: 8px;margin: 5px 0">Note: Read/Understood And Agreed To The Above Mentioned Terms & Conditions</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row" style="border: 1px solid;border-right:0px;border-bottom: none">
                            <div class="col-md-6">
                                <p>Product Total value</p>
                            </div>
                            <div class="col-md-6">
                                <p style="text-align: end;">₹<?= number_format($totalProductPrice, 2) ?></p>
                            </div>
                        </div>
                        <!-- Content for Section 2 (if any) -->
                        <div class="row" style="border: 1px solid black;border-right:0px;border-bottom: none;">
                            <div class="col-md-12">
                                <p>Additional:Other Charge</p>
                            </div>
                            <div class="col-md-4">
                                <p>1.</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <p>Stone Price</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <p>₹<?= number_format($totalStonePrice, 2) ?></p>
                            </div>
                        </div>
                        <!-- Content for Section 3 (if any) -->
                        <div class="row" style="border: 1px solid black;border-right:0px;">
                            <div class="col-md-6">
                                <p>Total value</p>
                            </div>
                            <div class="col-md-6">
                                <p style="text-align: end;">₹<?= number_format(($totalProductPrice + $totalStonePrice), 2) ?></p>
                            </div>
                        </div>
                        <!-- Content for Section 4 (if any) -->
                        <div class="row" style="  border: none; border-left: 1px solid black; border-right: 0px;">
                            <div class="col-md-6">
                                <p>Less: Other Discount</p>
                            </div>
                            <div class="col-md-6">
                                <p style="text-align: end;">₹<?= number_format($discount, 2) ?></p>
                            </div>
                        </div>
                        <!-- Content for Section 4 (if any) -->
                        <div class="row" style="  border: none; border-left: 1px solid black; border-right: 0px;">
                            <div class="col-md-6">
                                <p>Less: Ornaments Return</p>
                                <p><?= $orn_description ?></p>
                            </div>
                            <div class="col-md-6">
                                <p style="text-align: end;">₹<?= number_format($orn, 2) ?></p>
                            </div>
                        </div>
                        <!-- Content for Section 6 (if any) -->
                        <div class="row" style="border: 1px solid black;border-right:0px;">
                            <div class="col-md-6">
                                <p>Net invoice Value</p>
                            </div>
                            <div class="col-md-6">
                                <p style="text-align: end;">₹<?= number_format(($totalProductPrice + $totalStonePrice - $discount), 2) ?></p>
                            </div>
                        </div>
                        <div class="row" style="  border: none; border-left: 1px solid black; border-right: 0px solid black;">
                            <div class="col-md-12" style="display: flex; gap: 10px;">
                                <p>Value in word:</p>
                                <p><?php
                                    $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                    $totalAmtInWords = $formatter->format(($totalProductPrice + $totalStonePrice - $discount));

                                    echo $capitalizedWords = ucwords($totalAmtInWords);
                                    ?>
                                </p>
                            </div>
                        </div>
                        <!-- Content for Section 8 (if any) -->
                        <div class="row" style="border: 1px solid black;border-right:0px;">
                            <div class="col-md-12">
                                <p>Amount Paid:</p>
                            </div>
                            <div class="col-md-6">
                                <p>Cash : </p>
                            </div>
                            <div class="col-md-6">
                                <p style="text-align: end;">₹<?= number_format($cash, 2) ?></p>
                            </div>
                            <?php if ($remark != "") : ?>
                                <div class="col-md-6">
                                    <p>Card : </p>
                                </div>
                                <div class="col-md-6">
                                    <p style="text-align: end;">₹<?= number_format($remark, 2) ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-6">
                                <p>Online : </p>
                            </div>
                            <div class="col-md-6">
                                <p style="text-align: end;">₹<?= number_format($online, 2) ?></p>
                            </div>
                        </div>
                        <!-- Content for Section 8 (if any) -->
                        <div class="row" style="border: 1px solid black;border-right:0px;border-top: 0px">
                            <div class="col-md-6">
                                <p>Balance Amount:</p>
                            </div>
                            <div class="col-md-6">
                                <p style="text-align: end;">₹<?= number_format((($totalProductPrice + $totalStonePrice - $discount) - $paid), 2) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 p-2" style="margin: 0;">
                    <style>
                        #footer_table th {
                            background-color: transparent;
                            padding: 10px;
                            border: 1px solid #000;
                            font-size: 12px;
                        }
                    </style>
                    <table id="footer_table" class="table">
                        <colgroup>
                            <col width="50%">
                            <col width="50%">
                        </colgroup>
                        <tr>
                            <th style="border-bottom: 0px;">Customer Name: <?= $name ?></th>
                            <th style="border-bottom: 0px;">For Nidhi Narvaria Abhushan</th>
                        </tr>
                        <tr>
                            <th style="border-top: 0px;">Customer Signature:</th>
                            <th style="border-top: 0px;">Authorised Signatory:</th>
                        </tr>
                    </table>

                    <div class="d-flex justify-content-center">
                        <img src="../footer_logos.jpeg" style="width: 65%;" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Right Sidebar -->
<script>
    window.addEventListener("DOMContentLoaded", () => Alpine.start());
</script>
<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>

</body>

</html>