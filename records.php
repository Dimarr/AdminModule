<?php
$ini_array = parse_ini_file("options.ini");
$link = mysqli_connect($ini_array["url"], $ini_array["user"], $ini_array["password"], $ini_array["database"]);

if (!$link) {
 echo "Error: Impossible connect to MySQL." . PHP_EOL;
 echo "Error Code: " . mysqli_connect_errno() . PHP_EOL;
 echo "Details of error: " . mysqli_connect_error() . PHP_EOL;
 exit;
}
//echo "eeeee";

if(isset($_POST['edit_row_sp']))
{
 $row=$_POST['row_id'];
 $name=$_POST['name_val'];
 $email=$_POST['email_val'];
 //$detail=$_POST['detail_val'];
 $phone=$_POST['phone_val'];
 $car=$_POST['car_val'];

 $sql="update sproviders set name='$name',email='$email',phone='$phone',carid='$car' where id='$row'";
 mysqli_query($link,$sql);
 echo "success";
 exit;
}

if(isset($_POST['edit_row']))
{
 $row=$_POST['row_id'];
 $fname=$_POST['fname_val'];
 $lname=$_POST['lname_val'];
 $email=$_POST['email_val'];
 //$detail=$_POST['detail_val'];
 $phone=$_POST['phone_val'];
 $car=$_POST['car_val'];

 $sql="update users set firstname='$fname',lastname='$lname',email='$email',phone='$phone',carid='$car' where userid='$row'";
 mysqli_query($link,$sql);
 echo "success";
 exit();
}

if(isset($_POST['changestatuscall']))
{
 $row=$_POST['row_id'];
 $status=$_POST['status'];
 if ($status>0) mysqli_query($link,"update calls set status='$status' where callid='$row'");
 exit;
}

if(isset($_POST['changestatus']))
{
 $row=$_POST['row_id'];
 $status=$_POST['status'];
 if ($status>0) mysqli_query($link,"update users set logined='$status' where userid='$row'");
 exit();
}

if(isset($_POST['changestatus_sp']))
{
 $row=$_POST['row_id'];
 $status=$_POST['status'];
 if ($status>0) mysqli_query($link,"update sproviders set logined='$status' where id='$row'");
 exit;
}

if(isset($_POST['showcalls']))
{
 $row=$_POST['row_id'];
 $fname=$_POST['fname_val'];
 $lname=$_POST['lname_val'];
 $email=$_POST['email_val'];
 $detail=$_POST['detail_val'];
 $phone=$_POST['phone_val'];
 $car=$_POST['car_val'];

 mysqli_query($link,"update users set firstname='$fname',lastname='$lname',email='$email',phone='$phone',carid='$car' where userid='$row'");
 //echo "success";
 exit;
}
if(isset($_POST['map']))
{
 $rowid=$_POST['row_id'];
 $select = mysqli_query($link, 'CALL showmap('.$rowid.', @x1, @y1, @x2, @y2)' );
 $select = mysqli_query($link, 'SELECT @x1, @y1, @x2, @y2');
 //echo $rowid;

 $row=mysqli_fetch_array($select);

 $arHash = array( // массив к возврату в виде объекта json
     'X1'    => $row[0],
     'Y1' => $row[1],
     'X2'  => $row[2],
     'Y2'    => $row[3]
 );
  echo json_encode($arHash);
  exit;
}
?>