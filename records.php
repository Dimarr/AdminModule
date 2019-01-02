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

 $sql="UPDATE users SET firstname='$fname',lastname='$lname',email='$email',phone='$phone',carid='$car' WHERE userid='$row'";
 mysqli_query($link,$sql);
 echo "success";
 exit();
}

if(isset($_POST['changestatuscall']))
{
 $row=$_POST['row_id'];
 $status=$_POST['status'];
 $spid=@$_POST['spid'];
 $saleid="";
 $alert="";
 $amnt = @$_POST['famount'];
 $instll = @$_POST['inst'];

 if ($status>0) {
   if ($status == 10) {
     /* $sql = "SELECT payments.paymetrid
              from calls, payments
              where calls.status=4 and payments.callid=calls.callid and calls.callid=".$row;  */
      $sql = "SELECT paymetrid FROM payments WHERE callid=".$row;
      $select= mysqli_query($link,$sql);
      if ($res = mysqli_fetch_row($select)) $saleid = $res[0];
      $resapprovement = sendAPItoServer("capturesale,".$saleid.",".$amnt.",".$row.",".$instll);
          //approvepayment($saleid);
      echo $resapprovement;
      /*if ( $resapprovement == "Payment was approved successfully") {
           mysqli_query($link,"UPDATE payments SET pstatus=2 WHERE paymetrid='".$saleid."'"); //Approvement of payment
           mysqli_query($link,"UPDATE calls SET status='$status' WHERE callid='$row'");
      }*/
   }
   if ($status == 4 || $status == 7 || $status == 10) mysqli_query($link,"UPDATE sproviders SET busy=0 WHERE id='$spid'"); //Free SP after decline or approvement by CC
   mysqli_query($link,"UPDATE calls SET status='$status' WHERE callid='$row'");
   if ($status == 7) {
       echo "Payment was rejected";
   }
   if ($status == 4) {
       echo "Asked for Payment";
   }
 }
 exit;
}

if(isset($_POST['changestatus']))
{
 $row=$_POST['row_id'];
 $status=$_POST['status'];
 //if ($status>0)
  mysqli_query($link,"update users set logined='$status' where userid='$row'");
 exit();
}

if(isset($_POST['changestatussp']))
{
 $row=$_POST['row_id'];
 $status=$_POST['status'];
 //if ($status>0)
 //echo "update sproviders set logined=".$status." where id=".$row;
  mysqli_query($link,"update sproviders set logined=".$status." where id=".$row);
 exit;
}

if(isset($_POST['changebusystatussp']))
{
 $row=$_POST['row_id'];
 $status=$_POST['status'];
 //if ($status>0)
 mysqli_query($link,"update sproviders set busy='$status' where id='$row'");
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
function approvepayment($slid) {
    $ini_array = parse_ini_file("options.ini");
//$paymeclient = $ini_array["paymeclient"];
    $capturesale = $ini_array["capturesale"];
//$saleid = @$_GET['saleid'];
//echo "eeeeee".$saleid."fffffffffff";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $capturesale);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_POST, TRUE);

    $jsonrequest = "{\r\n \"payme_sale_id\": \"".$slid."\"}";

//echo $jsonrequest;

    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonrequest);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json"
    ));

    $response = curl_exec($ch);
    if (!$response) {
        $alert = "An error occurred while storing data:".curl_error($ch);
    } else {
        //var_dump($response);
        $res= json_decode($response);
        $status_code = $res->{'status_code'};
        if ($res->{'status_code'}===0){
            //$paymesellerid = $res->{'seller_payme_id'};
            //$paymesellersecret = $res->{'seller_payme_secret'};
            $alert = "Payment was approved successfully";
        } else {
            $alert= "An error occurred while payment proceeding: ".$res->{'status_error_details'};
        }
    };
    curl_close($ch);
    return $alert;
}

function sendAPItoServer($api)
{
    $ini_array = parse_ini_file("options.ini");
    error_reporting(E_ALL);

    //echo "<h2>Соединение TCP/IP</h2>\n";

    /* Получаем порт сервиса WWW. */
    $service_port = $ini_array["port"];//getservbyname('www', 'tcp');

    /* Получаем IP-адрес целевого хоста. */
    $address = $ini_array["address"]; //gethostbyname('www.example.com');

    /* Создаём сокет TCP/IP. */
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

    if ($socket === false) {
        //echo "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "\n";
        return socket_strerror(socket_last_error($socket)) . "\n";
    } else {
        //echo "OK.\n";
    }

    //echo "Пытаемся соединиться с '$address' на порту '$service_port'...";
    $result = socket_connect($socket, $address, $service_port);
    if ($result === false) {
        //echo "Не удалось выполнить socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
        return socket_strerror(socket_last_error($socket)) . "\n";
    } else {
        //  echo "OK.\n";
    }

    $in = $api."\r\n"; //"getspbankdetails,68" . "\r\n";
    $out = '';


//$in .= "Host: www.example.com\r\n";
//$in .= "Connection: Close\r\n\r\n";

    //echo "Отправляем HTTP-запрос HEAD...";
    //echo $in;
    socket_write($socket, $in, strlen($in));
    //echo "OK.\n";

    //echo "Читаем ответ:\n\n";
    $out = socket_read($socket, 8192);
    //echo $out;
    //echo "Закрываем сокет...";
    socket_close($socket);
    //echo "OK.\n\n";
    return $out;
}
?>