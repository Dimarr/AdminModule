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

$sql="SELECT name FROM sproviders WHERE id=".$rowid;
$select= mysqli_query($link,$sql);
$row=mysqli_fetch_array($select);
$title="Requests for ".$row['name'];
?>
<title><?php echo $title ?></title>
<?php
/*$sql="SELECT calls.callid,date_format(cdate,'%Y-%m-%d %H:%i') as cdate,details,callstatus.statusname,  calls.status as statusid, users.firstname, users.lastname ,users.phone , servicetype.name as service
FROM mobi1.calls, users, servicetype, callstatus
WHERE callstatus.statusid= calls.status AND calls.userid= users.userid AND servicetype.id=calls.serviceid AND calls.spid='$rowid'"; */
$sql="SELECT * from requestsuser WHERE spid=".$rowid;
$select= mysqli_query($link,$sql);
$where="";
$orderby = " ORDER BY cdate DESC;";
if ($status!="") {
 $where=" AND status=".$status;
}
if ($callid!="") {
 $where=" AND callid=".$callid;
}

//echo $sql.$where.$orderby;
$select= mysqli_query($link,$sql.$where.$orderby);
?>
<table align="center" cellpadding="10" border="1" id="user_table">
 <tr>
  <th>Request number</th>
  <th>Date of request</th>
  <th>Rating</th>
  <th>Request details</th>
  <th>Status request</th>
  <th>Service</th>
  <th>User Firstname</th>
  <th>User Lastname</th>
  <th>User's phone</th>
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
  <td id="fname_val<?php echo $row['callid'];?>"><?php echo $row['firstname'];?></td>
  <td id="lname_val<?php echo $row['callid'];?>"><?php echo $row['lastname'];?></td>
  <td id="phone_val<?php echo $row['callid'];?>"><?php echo $row['phone'];?></td>
  <td id="statusid_val<?php echo $row['callid'];?>" style="display: none;"><?php echo $row['status'];?></td>
  <td>
   <input type='button' class="show_button" id="approve_button<?php echo $row['callid'];?>" value="Approve" onclick="appr_rej('<?php echo $row['callid'];?>','approve','<?php echo $row['status'];?>');">
   <input type='button' class="show_button" id="reject_button<?php echo $row['callid'];?>" value="Reject" onclick="appr_rej('<?php echo $row['callid'];?>','reject','<?php echo $row['status'];?>');">
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