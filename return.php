<html xmlns:display="http://www.w3.org/1999/xhtml">
<!--<Title>Calls</Title><button onclick="window.close();">Close</button> -->
<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<link rel="stylesheet" href="./style.css" type="text/css"/>
<body>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: РумянцевДмитрий
 * Date: 07.08.2018
 * Time: 11:19
 */
//http://34.199.251.216:8000/?payme_status=success&payme_signature=2917a2de6a0eb4209d70514aca47a792&payme_sale_id=SALE1533-297866TQ-8OM4VCRJ-3FWPB3NL&payme_transaction_id=TRAN1533-297911HI-KJ7MNWL4-Z2MYNBFL&price=100&currency=ILS&transaction_id=9875&is_token_sale=1&is_foreign_card=1

$paymestatus= @$_GET['payme_status'];
$paymesign=   @$_GET['payme_signature'];
$amount=      @$_GET['price'];
$amount = substr($amount,0,strlen($amount)-2).'.'.substr($amount,-2);
$currency=    @$_GET['currency'];
$trid=        @$_GET['transaction_id'];
?>
<table align="left" cellpadding="10" border="1" id="user_table">
    <th colspan="2" align="center">Payment details</th>
    <tr><td width="200"><p><label>Payment status: </td><td width="200"><b><?php echo $paymestatus ?></b></label></td></tr>
    <br>
    <tr><td width="200"><p><label>Payment signature:</td><td width="200"><b><?php echo $paymesign ?></b></label></td></tr>
    <br>
    <tr><td width="200"><p><label>Payment amount:</td><td width="200"><b><?php echo $amount ?></b></label></td></tr>
    <br>
    <tr><td width="200"><p><label>Payment currency:</td><td width="200"><b><?php echo $currency ?></b></label></td></tr>
    <br>
    <tr><td width="200"><p><label>ID of transaction:</td><td width="200"><b><?php echo $trid ?></b></label></td></tr>
</table>
</body>
</html>
