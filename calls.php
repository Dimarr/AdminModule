<html xmlns:display="http://www.w3.org/1999/xhtml">
<!--<Title>Calls</Title><button onclick="window.close();">Close</button> -->
<head>
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script type="text/javascript" src="./Admin.js"></script>
</head>
<body>
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
{
$sql="SELECT statusname, statusid FROM callstatus";
$select= mysqli_query($link,$sql);
?>
<link rel="stylesheet" href="./style.css" type="text/css"/>
<input type="text" id="theinput" name="theinput" style="display: none;"/>
<input type="search" id="number" name="Number" placeholder="Request Number">
<select name="thelist" onChange="combo(this, 'theinput')" onMouseOut="comboInit(this, 'theinput')" >
<option id=0 value="All">All</option>
<?php
 while ($row=mysqli_fetch_array($select))
 {
 ?>
 <option id=<?php echo $row['statusid']?>><?php echo $row['statusname'];?></option>
<?php
 }
 //mysqli_close($link);
?>
</select>
<button type="submit" class="show_button" onclick="filter()">Search</button>
<button type="submit" class="show_button" align="right" onclick="logout()">Logout</button>

<?php
$rowid=$_GET['row_id'];
$status=@$_GET['status'];
$callid=@$_GET['callid'];

$sql="SELECT firstname,lastname FROM users WHERE userid=".$rowid;
$select= mysqli_query($link,$sql);
$row=mysqli_fetch_array($select);
$uname=$row['firstname']." ".$row['lastname'];
$title="Requests for ".$uname;

/*$sql="SELECT calls.callid,date_format(cdate,'%Y-%m-%d %H:%i') as cdate,details,callstatus.statusname, calls.status as statusid, sproviders.name as spname,sproviders.phone as spphone, servicetype.name as service
FROM mobi1.calls, sproviders, servicetype, callstatus
WHERE callstatus.statusid= calls.status AND calls.spid= sproviders.id AND servicetype.id=calls.serviceid AND calls.userid='$rowid'"; */
$sql="SELECT * from requestssp WHERE userid=".$rowid;
$where="";
$orderby = " ORDER BY cdate DESC;";
if ($status!="") {
    $where=" AND status=".$status;
}
if ($callid!="") {
 $where=" AND callid=".$callid;
}
//echo $sql.$where;
$select= mysqli_query($link,$sql.$where.$orderby);
?>
<button type="submit" class="excel_button" onclick="toexcel('<?php echo $sql.$where.$orderby;?>','calls_users')">Export to Excel</button>
<title><?php echo $title ?></title>
<table align="center" cellpadding="10" border="1" id="user_table">
 <tr>
  <th>Request number</th>
  <th>Date of request</th>
  <th>Rating</th>
  <th>Request details</th>
  <th>Status request</th>
  <th>Service</th>
  <th>Service provider</th>
  <th>Service provider's phone</th>
  <th></th>
 </tr>
 <?php
 while ($row=mysqli_fetch_array($select))
 {
 ?>
 <tr id="row<?php echo $row['callid'];?>">
  <td id="request_id<?php echo $row['callid'];?>"><?php echo $row['callid'];?></td>
  <td id="datereq_val<?php echo $row['callid'];?>"><?php echo $row['cdate'];?></td>
  <td id="rating_val<?php echo $row['callid'];?>"><?php echo $row['rating'];?></td>
  <td id="detail_val<?php echo $row['callid'];?>"><?php echo $row['details'];?></td>
  <td id="status_val<?php echo $row['callid'];?>"><?php echo $row['statusname'];?></td>
  <td id="service_val<?php echo $row['callid'];?>"><?php echo $row['service'];?></td>
  <td id="spname_val<?php echo $row['callid'];?>"><?php echo $row['spname'];?></td>
  <td id="spphone_val<?php echo $row['callid'];?>"><?php echo $row['spphone'];?></td>
  <td id="statusid_val<?php echo $row['callid'];?>" style="display: none;"><?php echo $row['status'];?></td>
  <td><input type='button' class="show_button" id="approve_button<?php echo $row['callid'];?>" value="Approve"
         onclick="appr_rej('<?php echo $row['callid'];?>','approve','<?php echo $row['status'];?>','<?php echo $row['spid'];?>','<?php echo $row['amount'];?>','<?php echo $row['installments'];?>');">
  <input type='button' class="show_button" id="reject_button<?php echo $row['callid'];?>" value="Reject" onclick="appr_rej('<?php echo $row['callid'];?>','reject','<?php echo $row['status'];?>','<?php echo $row['spid'];?>');">
  <input type='button' class="show_button" id="map_button<?php echo $row['callid'];?>" value="Show map" onclick="map('<?php echo $row['callid'];?>','<?php echo $uname;?>','<?php echo $row['spname'];?>');">
  </td>
 </tr>
  <?php
 }
 mysqli_close($link);
 //echo "success";
 exit();
}
?>
</table>
</body>
</html>