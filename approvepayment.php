<?php
/**
 * Created by IntelliJ IDEA.
 * User: РумянцевДмитрий
 * Date: 17.07.2018
 * Time: 13:16
 */
//sale_price * (sale_installments - 1) * sale_discount_fee * (1 + sale_vat)
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

$jsonrequest = "{\r\n \"payme_sale_id\":".$saleid."\"}";

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
//echo $alert;
?>