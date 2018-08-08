<html xmlns:display="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" name="viewport" content="x-www-form-urlencoded">
    <title>Callback</title>
</head>
<body>
<?php
$ini_array = parse_ini_file("options.ini");
$link = mysqli_connect($ini_array["url"], $ini_array["user"], $ini_array["password"], $ini_array["database"]);

//echo $_SERVER['DOCUMENT_ROOT'];

if (!$link) {
    echo "Error: Impossible connect to MySQL." . PHP_EOL;
    echo "Error Code: " . mysqli_connect_errno() . PHP_EOL;
    echo "Details of error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$s="";
$buyerid="";
foreach($_POST as $key => $value) {
    $s.= $key.":".$value."\n";
    if ($key==='status_code') {
        if ($value>0) exit;
    }
    if ($key==='payme_sale_id') $paymecodeid= $value;
    if ($key==='buyer_key') $buyerid= $value;
    if ($key==='buyer_social_id') $fname= $value;
}
$fp = fopen("Report".trim($fname).".txt", "w");
//echo !empty($buyerid);
if (!empty($buyerid)) {
    $sql ="INSERT INTO temppayment (buyerkey,paymesaleid) values ('".trim($buyerid)."','".trim($paymecodeid)."');";
    if (!mysqli_query($link,$sql)) {
        //echo "sql ".$sql;
        echo '<script language="javascript">';
        echo 'alert("An error occurred while running storing data: "'.mysqli_error($link).')';
        echo '</script>';
    }
}
mysqli_close($link);
// записываем в файл текст
fwrite($fp, $s);
// закрываем
fclose($fp);
//echo $_REQUEST;
//echo "ssffsssfff";
?>
</body>
</html>
