<?php
include_once('../connect.php');
?>

<br/>

<?php
if(isset($_POST['code']))
	{
	$code = $_POST['code'];
	$sql = mysqli_query($db,"SELECT * FROM purchase where id = '$code'");
	$sqlrow = mysqli_fetch_array($sql);	
    $product = $sqlrow['product'];
	$types = $sqlrow['types'];
	$weight = $sqlrow['weight'];
	$id = $sqlrow['id'];
	$purity = $sqlrow['purity'];
	$hsn = $sqlrow['hsn'];
	$date = $sqlrow['date'];


     echo"<input type='hidden' value='$product' id='pp'>
     <input type='hidden' value='$types'  id='pp2'>
     <input type='hidden' value='$weight' id='pp3'>
     <input type='hidden' value='$purity' id='pp4'>
     <input type='hidden' value='$hsn' id='pp5'>
     <input type='hidden' value='$date' id='pp6'>

     ";
    
	}

 
?>

