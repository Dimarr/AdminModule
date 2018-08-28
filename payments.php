<html xmlns:display="http://www.w3.org/1999/xhtml">
<!--<Title>Calls</Title><button onclick="window.close();">Close</button> -->
<head>
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script type="text/javascript" src="./Admin.js"></script>
</head>
<body>
<link rel="stylesheet" href="./style.css" type="text/css"/>
<?php
//echo "eeee";
session_start();
if (!isset($_SESSION["is_auth"])){
 echo "<h2 style=\"color:red;\">User is not authorized</h2>";
 session_destroy();
 header("Location: ./index.php");
 exit;
}

$ini_array = parse_ini_file("options.ini");
$link = mysqli_connect($ini_array["url"], $ini_array["user"], $ini_array["password"], $ini_array["database"]);

if (!$link) {
 echo "Error: Impossible connect to MySQL." . PHP_EOL;
 echo "Error Code: " . mysqli_connect_errno() . PHP_EOL;
 echo "Details of error: " . mysqli_connect_error() . PHP_EOL;
 exit;
}
//echo  $_POST['showcalls'];
//if(isset($_POST['showcalls']))
 //mysqli_close($link);
$rowid=$_GET['row_id'];
$sp=@$_GET['sp'];

if ($sp==1) {
    $sql = "SELECT * FROM listpaymentsadmin WHERE spid=" .$rowid." ORDER BY pdate";
}
else {
    $sql = "SELECT * FROM listpaymentsadmin WHERE userid=" .$rowid." ORDER BY pdate";
}
//echo $sql;
$select= mysqli_query($link,$sql);
?>
<table align="left" cellpadding="10" border="1" id="user_table">
 <tr>
<?php
if ($sp==1) {
    echo "<th>User Name</th>";
} else {
    echo "<th>SP Name</th>";
}
?>

  <th>Date of payment</th>
  <th>Payment Amount</th>
  <th>Status payment</th>
  <th>Fee's details</th>
  </tr>

 <?php
 $i=1;
 //include "./paymegetdata.php";
 //$r=feedetail($row['saleid']);

 while ($row=mysqli_fetch_array($select))
 {
 //echo $r;
 ?>
  <td id="spusername_id<?php echo $i;?>"><?php echo ($sp==1) ? $row['username'] : $row['spname'];?></td>
  <td id="pdate_id<?php echo $i;?>"><?php echo $row['pdate'];?></td>
  <td id="amount_id<?php echo $i;?>"><?php echo $row['amount'];?></td>
  <td id="status_id<?php echo $i;?>"><?php echo $row['pstatus'];?></td>
  <td>
  <input type='button' class="show_button" id="fee_button<?php echo $row['saleid'];?>" value="<?php echo ($row['pstatusid']<3) ? 'Fee\'s details' : 'Error detail' ?>" onclick="show_fees('<?php echo $row['saleid'];?>','<?php echo $row['amount'];?>','<?php echo $row['errortext'];?>','<?php echo $row['details']?>');">
  </td>
  </tr>
  <?php
  $i++;
 }
 mysqli_close($link);
 //echo "success";
 exit();
?>
</table>
</body>
</html>