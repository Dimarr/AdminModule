﻿<html xmlns:display="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="./Admin.js"></script>
</head>
<?php
//echo "eeee";
session_start();
if (!isset($_SESSION["is_auth"])){
    echo "<h2 style=\"color:red;\">User is not authorized</h2>";
    session_destroy();
    header("Location: ./index.php");
    exit;
}
?>
<link rel="stylesheet" href="./style.css" type="text/css"/>
<input type="search" id="Phone" name="Phone" placeholder="Phone">
<input type="search" id ="Email" name="Email" placeholder="Email">
<button type="submit" class="show_button" onclick="search('sps')">Search</button>
<button type="submit" class="show_button" onclick="online_sps()">Show online SPs</button>
<button type="submit" class="show_button" align="right" onclick="logout()">Logout</button>
<br>
<body>
<div id="wrapper">

<?php
//echo "eeee";
$ini_array = parse_ini_file("options.ini");
$link = mysqli_connect($ini_array["url"], $ini_array["user"], $ini_array["password"], $ini_array["database"]);
//echo $_SERVER['DOCUMENT_ROOT'];

if (!$link) {
    echo "Error: Impossible connect to MySQL." . PHP_EOL;
    echo "Error Code: " . mysqli_connect_errno() . PHP_EOL;
    echo "Details of error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
    $mail=@$_GET['email'];
    $phn=@$_GET['phone'];
    $where="";
    if ($mail=="") {
        //echo "Empty mail";
        if ($phn!="") {
            $where=" WHERE phone LIKE '%" . $phn . "%'";
        }
    } else {
        if ($phn!="") {
            $where=" WHERE phone LIKE '%" . $phn . "%' AND Email LIKE '%" . $mail . "%'";
        } else {
            $where=" WHERE email LIKE '%" . $mail . "%'";
        }
    }
    //if ($phn=="") echo "Empty phone";
   //echo $phn;
    //echo $mail;
   //echo $where;
    //echo $phn;
//echo "Соединение с MySQL установлено!" . PHP_EOL;
//echo "Информация о сервере: " . mysqli_get_host_info($link) . PHP_EOL;
$sql="SELECT id,name,phone,email,logined,if(logined=0,\"Offline\",\"Online\") as statusonline, logtime,carid,pic FROM sproviders ";
$orderby=" ORDER BY name ";
    //    echo $sql.$where.$orderby;
    $select=mysqli_query($link,$sql.$where.$orderby);
/*$select=mysqli_query($link,"SELECT users.userid,firstname,lastname,phone,email,logined, logtime,carid ,
calls.details, callstatus.statusname as Status
FROM mobi1.users, calls, callstatus WHERE users.userid= calls.userid AND calls.status=callstatus.statusid;");*/
?>
    <table align="center" cellpadding="10" border="1" id="sp_table">
        <tr>
            <th>NAME</th>
            <th>PHONE</th>
            <th>E-mail</th>
            <th>Online status</th>
            <th>Time of Last Login</th>
            <th>Car's Plate</th>
            <!--<th>Details Request</th>
            <th>Status Request</th> -->
            <th></th>
        </tr>
<?php
while ($row=mysqli_fetch_array($select))
{
 ?>
        <tr id="row<?php echo $row['id'];?>">
            <td id="name_val<?php echo $row['id'];?>"><?php echo $row['name'];?></td>
            <td id="phone_val<?php echo $row['id'];?>"><?php echo $row['phone'];?></td>
            <td id="email_val<?php echo $row['id'];?>"><?php echo $row['email'];?></td>
            <td id="log_val<?php echo $row['id'];?>" style="display: none;"><?php echo $row['logined'];?></td>
            <td id="login_val<?php echo $row['id'];?>" onMouseOver="this.style.background='#FFCC33'" onMouseOut="this.style.backgroundColor='#F8E391'" onclick="changestatus_sp('<?php echo $row['id'];?>')"><?php echo $row['statusonline'];?></td>
            <td id="logtime_val<?php echo $row['id'];?>"><?php echo $row['logtime'];?></td>
            <td id="car_val<?php echo $row['id'];?>"><?php echo $row['carid'];?></td>
            <td id="pic_val<?php echo $row['id'];?>" style="display: none;"><?php echo $row['pic'];?></td>
            <td>
                <input type='button' class="edit_button" id="edit_button<?php echo $row['id'];?>" value="edit" onclick="edit_row_sp('<?php echo $row['id'];?>');">
                <input type='button' class="save_button" style="display: none;" id="save_button<?php echo $row['id'];?>" value="save" onclick="save_row_sp('<?php echo $row['id'];?>');">
                <input type='button' class="show_button" id="show_button<?php echo $row['id'];?>" value="calls" onclick="showcalls_sp('<?php echo $row['id'];?>');">
                <input type='button' class="show_button" id="show_pic<?php echo $row['id'];?>" value="show pic" onclick="showpic_sp('<?php echo $row['pic'];?>');">
            </td>
        </tr>
        <?php
}
mysqli_close($link);
?>
</table>
</div>
</body>
</html>