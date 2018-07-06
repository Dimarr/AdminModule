<html xmlns:display="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="./Admin.js"></script>
</head>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: РумянцевДмитрий
 * Date: 25.05.2018
 * Time: 15:35
 */
$ini_array = parse_ini_file("options.ini");
$link = mysqli_connect($ini_array["url"], $ini_array["user"], $ini_array["password"], $ini_array["database"]);
//echo $_SERVER['DOCUMENT_ROOT'];

if (!$link) {
    echo "Error: Impossible connect to MySQL." . PHP_EOL;
    echo "Error Code: " . mysqli_connect_errno() . PHP_EOL;
    echo "Details of error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$status=@$_GET['payme_status'];
$paymesgn=@$_GET['payme_signature'];
$paymetransactionid=@$_GET['payme_transaction_id'];
$transactionid=@$_GET['transaction_id'];

$sql="SELECT paymetrid FROM payments WHERE payid=".$transactionid;
$select= mysqli_query($link,$sql);
$row=mysqli_fetch_array($select);
$payme_sale_id=$row['paymetrid'];
echo "*".$payme_sale_id."*";
echo $paymesgn . " is gotten";
$signature = md5($ini_array["paymeclient"] . $ini_array["paymeskey"] . $paymetransactionid . $payme_sale_id);
echo $signature. " calc";
echo $status;
echo $paymetransactionid;
echo "<h2 style=\"color:darkblue;\">Payment was proceeded successfully</h2>";
mysqli_close($link);
?>

