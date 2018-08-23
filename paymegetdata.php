<?php
/**
 * Created by IntelliJ IDEA.
 * User: РумянцевДмитрий
 * Date: 17.07.2018
 * Time: 13:16
 */
//sale_price * (sale_installments - 1) * sale_discount_fee * (1 + sale_vat)
$ini_array = parse_ini_file("options.ini");
$paymeclient = $ini_array["paymeclient"];
$marketfee= $ini_array["marketfee"];
$getsales = $ini_array["getsales"];

if(isset($_POST['sale_id'])) {
    $saleid=$_POST['sale_id'];
    $ouramount=$_POST['our_amount'];
    $errtext = $_POST['err_text'];
    //echo $errtext;
    feedetail($saleid, $ouramount, $errtext);
}

function feedetail ($paymesaleid, $amount, $errtext) {
    global $paymeclient;
    $jsonrequest = "{\r\n  \"payme_client_key\": \"".$paymeclient."\","
        ."\"sale_payme_id\": \"".$paymesaleid."\"}";
    global $ch1;
    global $getsales;
    $ch1 = curl_init();

    curl_setopt($ch1, CURLOPT_URL, $getsales);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch1, CURLOPT_HEADER, FALSE);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json"
    ));
    $saleerrorcode = "20000";
    $errormsg   = "";
    $salestatus = "Non exist";
    $cur        = "";
    $marketfee  = 0;
    $inst       = 0;
    $discfee    = 0;
    $procfee    = 0;
    $proccharge = 0;
    $saleprice  = $amount*100;
    $vat        = 0;

    curl_setopt($ch1, CURLOPT_POST, TRUE);
    //echo $jsonrequest;
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonrequest);

    $response = curl_exec($ch1);
    curl_close($ch1);

    if (!$response) {
        return "An error occurred while getting data:".curl_error($ch1);
    } else {
        $res="";
        $obj=json_decode($response);
        //var_dump($obj->{'items'});
        foreach ($obj as $field => $value) {
            //если скалярное значение
            /*if (is_scalar($value) or is_null($value)){
                echo $field . ' - ' . $value . '</br>';
            }*/
            //если массив
            if (is_array($value)){
                for ($i = 0; $i < count($value); $i++) {
                    //а в нем у нас объекты
                    foreach ($value[$i] as $field_ob => $value_ob) {
                        if (is_object($value_ob)) {
                            foreach ($value_ob as $field_ob_1 => $value_ob_1) {
                                switch ($field_ob_1) {
                                    case "sale_processing_fee" : $procfee = $value_ob_1;
                                        break;
                                    case "sale_processing_charge" : $proccharge = $value_ob_1;
                                        break;
                                    case "sale_discount_fee" : $discfee = $value_ob_1;
                                        break;
                                    case "sale_market_fee" : $marketfee = $value_ob_1;
                                        break;
                                }
                                //echo $field . ' -> ' . $field_ob . ' -> ' . $field_ob_1 . ' -> ' . $value_ob_1 . '</br>';
                            }
                            } else {
                            switch ($field_ob) {
                                case "sale_status" : $salestatus = $value_ob;
                                    break;
                                //case "sale_price" :  $saleprice=$value_ob;
                                //    break;
                                case "sale_error_code" : $saleerrorcode = $value_ob;
                                    break;
                                case "sale_error_text" : $errormsg = $value_ob;
                                    break;
                                case "sale_price_after_fees" : $sale_price_after_fees=$value_ob;
                                    break;
                                case "sale_vat" : $vat = $value_ob;
                                    break;
                                case "sale_installments" : $inst = $value_ob-1;
                                    break;
                                case "sale_currency" : $cur = $value_ob;
                                    break;
                            }
                            //echo $field . ' -> ' . $field_ob . ' -> ' . $value_ob . '</br>';
                        }
                    }
                }
            }
            //если другой объект
            /*if (is_object($value)){
                foreach ($value as $field_ob => $value_ob) {
                    echo $field . ' -*> ' . $field_ob . ' -*> ' . $value_ob . '</br>';
                }
            }*/
        }        //echo $obj->{'items'}["sale_status"];
        //$res.='sale_status=>\"'.$obj->{"sale_status"}.'\",';
        //$res.='sale_price=>'.$obj->{"sale_price"}.',';
        //$res.='sale_price_after_fees=>'.$obj->{"sale_price_after_fees"}.',';
        //echo
        //$saleprice=@$obj->{"sale_price"};
        //$sale_price_after_fees=$obj->{'items'}['sale_price_after_fees'];
        //$inst=@$obj->{"sale_installments"}-1;
        //$procfee= @$obj->{"sale_fees"}["sale_processing_fee"];
        //$proccharge= @$obj->{"sale_fees"}["sale_processing_charge"];
        //$discfee= @$obj->{"sale_fees"}["sale_discount_fee"];
        //$marketfee= @$obj->{"sale_fees"}["sale_market_fee"];
        //$vat = @$obj->{"sale_vat"};

        //echo "procfee ".$procfee;
        /*echo $salestatus.'<br>';
        echo $marketfee.'<br>';
        echo $inst.'<br>';
        echo $discfee.'<br>';
        echo $procfee.'<br>';
        echo $proccharge.'<br>';
        echo $saleprice.'<br>';
        $finalprice = $saleprice/100  - $saleprice/100 * ( $inst * $discfee/100 * (1 + $vat) + $procfee/100 * (1 + $vat)) - $proccharge - $marketfee/10000 * $saleprice;
        $final_format=number_format($finalprice, 2, '.', '');
        echo " From JSON ".$sale_price_after_fees.'<br>';
        echo "Calculated ".$final_format;*/
        $res="errorcode=".$saleerrorcode."&salestatus=".$salestatus."&vat=".$vat."&salecurrency=".$cur.
            "&marketfee=".$marketfee."&inst=".$inst."&discfee=".$discfee."&procfee=".$procfee.
            "&proccharge=".$proccharge."&saleprice=".$saleprice."&errormsg=".$errormsg;
        if (!is_null($errtext)) $res.="&errtext=".$errtext;
        echo $res;
    }
}

//echo $jsonrequest;

/*
$response = curl_exec($ch);
if (!$response) {
    $alert = "An error occurred while storing data:".curl_error($ch);
} else {
    //var_dump($response);
    $res= json_decode($response);
    $status_code = $res->{'status_code'};
    if ($res->{'status_code'}===0){
        $paymesellerid = $res->{'seller_payme_id'};
        $paymesellersecret = $res->{'seller_payme_secret'};
        $alert = "Data was stored successfully. Created Seller identificator: ".$res->{'seller_payme_id'};
    } else {
        $alert= "An error occurred while storing data: ".$res->{'status_error_details'}." Please, correct data";
    }
};
curl_close($ch);*/
?>

