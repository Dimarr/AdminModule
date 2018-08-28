<html xmlns:display="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<link rel="stylesheet" href="./style.css" type="text/css"/>
<?php
//echo "eeee";
$errcode     = @$_GET['errorcode'];
$salepaymecode = @$_GET['salepaymecode'];
$status      = @$_GET['salestatus'];
$errmsg      = @$_GET['errormsg'];
$mfee        = @$_GET['marketfee'];
$instl       = @$_GET['inst'];
$discfee_    = @$_GET['discfee'];
$procfee_    = @$_GET['procfee'];
$proccharge_ = @$_GET['proccharge'];
$sprice      = @$_GET['saleprice'];
$vat_        = @$_GET['vat'];
$salecur     = @$_GET['salecurrency'];
$errtext     = @$_GET['errtext'];
$slavecode   = @$_GET['slavecode'];

$finalprice = 0;
//echo $errcode;
if ($errcode=="20000") {
?>
<table align="left" cellpadding="10" border="1" id="user_table">
    <tr>
    <td>Sale status</td>
    <td><?php echo $status;?></td>
    </tr><tr>
    <td>Sale PayMe ID</td>
    <td><?php
        echo $salepaymecode;
        if (!is_null($slavecode))
            if (!empty($slavecode)) {
                echo ", ".$slavecode;
            }
        ?>
    </td>
    </tr><tr>
    <td>Market Fee</td>
    <td><?php echo number_format($mfee/10000*$sprice, 2, '.', '')." ".$salecur;?></td>
    </tr><tr>
    <td>Number of installments</td>
    <td><?php echo $instl+1;?></td>
    </tr><tr>
    <td>Discount Fee</td>
    <td><?php echo number_format($instl * $discfee_/100 * (1 + $vat_) * $sprice/100, 2, '.', '')." ".$salecur;?></td>
    </tr><tr>
    <td>Processing Fee</td>
    <td><?php echo number_format($procfee_/100 * (1 + $vat_) * $sprice/100, 2, '.', '')." ".$salecur;?></td>
    </tr><tr>
    <td>Processing Chgarge</td>
    <td><?php echo number_format($proccharge_, 2, '.', '')." ".$salecur;?></td>
    </tr><tr>
    <td>VAT</td>
    <td><?php echo number_format($vat_*100, 2, '.', '')."%";?></td>
    </tr><tr>
    <td>Price before fees</td>
    <td><?php echo number_format($sprice/100, 2, '.', '')." ".$salecur;?></td>
    </tr><tr>
    <td>Final price</td>
    <td><?php
        //$final_format=number_format($finalprice, 2, '.', '');
        if ($status=="completed") {
            $finalprice = $sprice/100  - $sprice/100 * ( $instl * $discfee_/100 * (1 + $vat_) + $procfee_/100 * (1 + $vat_)) - $proccharge_ - $mfee/10000 * $sprice;
            echo number_format($finalprice, 2, '.', '');
        } else echo "No relevant";
        ?>
    </td>
    </tr>
<?php } else {?>
<table align="left" cellpadding="10" border="1" id="user_table">
    <tr><td>Error status</td>
    <td><?php echo $errcode?></td></tr>
    <tr><td>Error message</td>
    <td><?php echo $errmsg?></td></tr>
<?php }
    //echo $errtext;
    if (!is_null($errtext)) {
        if (!empty($errtext)) { ?>
            <tr><td>Error of additional payment</td>
            <td><?php echo $errtext ?></td></tr>
        <?php } } ?>
</table>
</body>
</html>