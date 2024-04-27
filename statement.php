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
    <div class="mt-5">
        <table class="table table-striped table-hover">
            <tr style="background-color:#F7FEDE; font-weight:bold" align='center'>
                <td>S.No.</td>
                <td>Customer Name</td>
                <td align='right'>Debit </td>
                <td align='right'>Credit </td>
                <td align='right'>Balance </td>
                <td>Bill Details</td>
            </tr>
            <?php
            $sql = "SELECT name,mobile FROM sales  GROUP BY name ORDER BY name";
            $sql_run = mysqli_query($db, $sql);
            $count = mysqli_num_rows($sql_run);
            //
            $super_total = 0;
            $super_paid = 0;
            $super_balance = 0;

            for ($i = 1; $i <= $count; $i++) {
                $row = mysqli_fetch_array($sql_run);
                $name = $row['name'];
                $mobile = $row['mobile'];

                $sql1 = "SELECT mobile, name, date, bill_no,mobile,type,firmname, description,total_amt,discount_final, balance_amount, paid,remark FROM sales WHERE name = '$name' and description!='Payment' GROUP BY bill_no ORDER BY bill_no DESC ";

                $sql1_run = mysqli_query($db, $sql1);
                $count1 = mysqli_num_rows($sql1_run);

                $x_total = $x_paid = $x_balance = 0;

                for ($n = 1; $n <= $count1; $n++) {
                    $row1 = mysqli_fetch_array($sql1_run);
                    $m_total = $row1['balance_amount'];
                    $paid = $row1['paid'];

                    $x_total += $m_total;
                    $x_paid += $paid;
                    $x_balance = $x_total - $x_paid;
                }

                $sql1 = mysqli_query($db, "SELECT sum(paid) as paids FROM sales WHERE name = '$name' and description='Payment'GROUP BY name");
                if ($sql1->num_rows > 0) {
                    $row = mysqli_fetch_array($sql1);
                    $x_paid += $row['paids'];
                } else {
                    $x_paid += 0;
                }
                $x_balance = $x_total - $x_paid;




                echo
                "<tr align='center'>
                        <td>$i</td>
                        <td>$name</td>
                        <td align='right'>$x_total</td>
                        <td align='right'>$x_paid</td>
                        <td align='right'>$x_balance</td>
                        <td><a href='details.php?s=$name' style='display:block;background-color: 211360;color: white;'>Bills</a></td>
                        ";

                $super_total += $x_total;
                $super_paid += $x_paid;
                $super_balance += $x_balance;
            }

            echo
            "<tr style='font-weight:bold'>
                            <td colspan='2' align='right'><strong>TOTAL</strong></td>
                            <td align='right'>$super_total </td>
                            <td align='right'>$super_paid </td> 
                            <td align='right' style='color:red'>$super_balance </td> 
                        </tr>";
            ?>
        </table>
    </div>
</main>
</div>
<!-- 
        This is a place for Alpine.js Teleport feature 
        @see https://alpinejs.dev/directives/teleport
      -->
<div id="x-teleport-target"></div>
<script>
    window.addEventListener("DOMContentLoaded", () => Alpine.start());
</script>
<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>

</html>