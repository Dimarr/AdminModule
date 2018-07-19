<html xmlns:display="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Seller's Data</title>
    <link rel="stylesheet" href="./st.css" type="text/css"/>
</head>
<body>
<div class="form">
    <div>
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

$spid= $_GET["spid"];

if($spid > 0)
{
    $spbystype = @$_REQUEST["bystype"];
    $birthdate = @$_REQUEST["bdate"];
    $socialissuedate = @$_REQUEST["sidate"];
    $street = @$_REQUEST["street"];
    $streetnmb = @$_REQUEST["streetnmb"];
    $country = @$_REQUEST["country"];
    $lastname = @$_REQUEST["lname"];
    $url = @$_REQUEST["url"];
    $gender = @$_REQUEST["gender"];
    $sellerinc = @$_REQUEST["sellerinc"];
    $mrchntname = @$_REQUEST["mrchntname"];
    $picsocialid = @$_REQUEST["filesocialid"];
    $piccheque = @$_REQUEST["filecheque"];
    $filecorporate = @$_REQUEST["filecorporate"];
    $sellercity = @$_REQUEST["sellercity"];
    $bankid= @$_REQUEST["banks"];
    $bbranch= @$_REQUEST["bbranch"];
    $baccount= @$_REQUEST["baccount"];
    $bn= @$_REQUEST["bn"];
    $bnid= @$_REQUEST["bnid"];
    $desc= @$_REQUEST["desc"];

    $sql="SELECT name, bankid, bankbranch, bankaccount, description, BN, BNID FROM sproviders WHERE id=".$spid;
    $select=mysqli_query($link,$sql);
    $row=mysqli_fetch_row($select);
    $name = $row[0];
    $curbank=$row[1];
    $bankbranch = $row[2];
    $bankaccount = $row[3];
    $description = $row[4];
    $bbn = $row[5];
    $bbnid = $row[6];

    $sql = "SELECT * FROM sellers WHERE spid=".$spid;
    $select=mysqli_query($link,$sql);
    $row=mysqli_fetch_row($select);
    $status = mysqli_affected_rows($link);
    //echo "**".$status."**";

    $sqlbanks="SELECT ID, Name FROM banks ORDER BY ID";
    $selectbanks=mysqli_query($link,$sqlbanks);
    //$rowbanks=mysqli_fetch_array($selectbanks);
    //echo $rowbanks[0];
    //echo $rowbanks[1];

    if ($status == 0) {
        $sql = "INSERT INTO sellers (spid,seller_person_business_type,seller_birthdate," .
            "seller_social_id_issued,seller_address_street,seller_address_street_number," .
            "seller_address_country,seller_site_url,seller_last_name,seller_gender,seller_inc,seller_merchant_name," .
            "file_social_id,file_cheque,file_corporate,seller_address_city) VALUES(" .
            $spid . "," . $spbystype . ",'" . $birthdate . "','" . $socialissuedate . "','" . $street . "'," . $streetnmb . ",'" . $country . "','" .
            $url . "','" . $lastname . "'," . $gender . "," . $sellerinc . ",'" . $mrchntname . "','" . $picsocialid . "','" . $piccheque . "','" . $filecorporate . "','".$sellercity."')";
    } else {
        $sql = "UPDATE sellers".
         " SET seller_address_city='".$sellercity."',seller_person_business_type =".$spbystype.",seller_birthdate='".$birthdate."',seller_social_id_issued='".$socialissuedate."',seller_address_street='".
            $street."',seller_address_street_number=".$streetnmb.",seller_address_country='".$country."',seller_site_url='".
            $url."',seller_last_name='".$lastname."',seller_gender=".$gender.",seller_inc=".$sellerinc.",seller_merchant_name='".$mrchntname.
            "',file_social_id='".$picsocialid."',file_cheque='".$piccheque."',file_corporate='".$filecorporate."' WHERE spid=".$spid.";";
    }
    mysqli_query($link,$sql);
    $sql ="UPDATE sproviders SET bankid=".$bankid.",bankbranch='".$bbranch."',bankaccount='".
        $baccount."',description='".$desc."',BN='".$bn."',BNID='".$bnid."' WHERE id=".$spid;
    //echo $sql;
    mysqli_query($link,$sql);
}
?>
    <h1>Insert Seller's Data for Service Provider <?php echo $name ?></h1>
    <form name="form" method="post" action="">
        <table>
            <tr><td width="200"><p><label for="fname"><b>First Name</b></label></td>
            <td width="500"><input type="text" name="fname" placeholder="First Name" value="<?php echo $name ?>" readonly /></p></td>
            <td width="200"><p><label for="lname"><b>Last Name</b></label></td>
            <td width="500"><input type="text" name="lname" placeholder="Last Name" value="<?php echo $row[10] ?>" required /></p></td></tr>
            <tr><td width="200"><p><label for="bystype"><b>Bussiness type</b></label></td>
            <td width="500"><input type="text" name="bystype" placeholder="Bussiness type" value="<?php echo $row[2] ?>" required /></p></td>
            <td width="200"><p><label for="desc"><b>Description</b></label></td>
            <td width="500"><input type="text" name="desc" placeholder="Description" value="<?php echo $description ?>" /></p></td></tr>

            <tr><td width="200"><p><label for="banks"><b>Banks</b></label></td>
            <td width="500"><select name="banks"></p>
            <?php
            while($rowbank = mysqli_fetch_array($selectbanks, MYSQLI_ASSOC)) {
                $option = "<option ";
                if ($rowbank['ID'] == $curbank) $option.= "selected ";
                echo $option."value='".$rowbank['ID']."'>".$rowbank['Name']."</option>";
            }
            ?>
            </select></td>
            <td width="200"><p><label for="bbranch"><b>Bank's Branch</b></label></td>
            <td width="500"><input type="text" name="bbranch" placeholder="Bank Branch" value="<?php echo $bankbranch ?>" /></p></td></tr>
            <tr><td width="200"><p><label for="baccount"><b>Bank Account</b></label></td>
            <td width="500"><input type="text" name="baccount" placeholder="Bank Account" value="<?php echo $bankaccount ?>" /></p></td>
            <td width="200"><p><label for="bn"><b>Seller inc. code</b></label></td>
            <td width="500"><input type="text" name="bn" placeholder="Seller inc. code" value="<?php echo $bbn ?>" /></p></td></tr>
            <tr><td width="200"><p><label for="bnid"><b>Seller social ID</b></label></td>
            <td width="500"><input type="text" name="bnid" placeholder="Social ID" value="<?php echo $bbnid ?>" /></p></td>
            <td width="200"><p><label for="bystype"><b>Birthdate</b></label></td>
            <td width="500"><input type="date" name="bdate" placeholder="Birthdate" value="<?php echo $row[3] ?>" required /></p></td></tr>
            <tr><td width="200"><p><label for="sidate"><b>Social ID Issue Date</b></label></td>
            <td width="500"><input type="date" name="sidate" placeholder="Social ID Issue Date" value="<?php echo $row[4] ?>" required /></p></td>
            <td width="200"><p><label for="country"><b>Country</b></label></td>
            <td width="500"><input type="text" name="country" placeholder="Your Country" value="<?php echo $row[7] ?>" required /></p></td></tr>
            <tr><td width="200"><p><label for="sellercity"><b>City</b></label></td>
            <td width="500"><input type="text" name="sellercity" placeholder="City" value="<?php echo $row[17] ?>" required /></p></td>
            <td width="200"><p><label for="street"><b>Street</b></label></td>
            <td width="500"><input type="text" name="street" placeholder="Street" value="<?php echo $row[5] ?>" required /></p></td></tr>
            <tr><td width="200"><p><label for="streetnmb"><b>Building Number</b></label></td>
            <td width="500"><input type="number" name="streetnmb" placeholder="Building number" value="<?php echo $row[6] ?>" required /></p></td>
            <td width="200"><p><label for="url"><b>Your site</b></label></td>
            <td width="500"><input type="text" name="url" placeholder="Your URL" value="<?php echo $row[8] ?>" required /></p></td></tr>
            <tr><td width="200"><p><label for="gender"><b>Gender</b></label></td>
            <td width="500"><input type="number" name="gender" placeholder="0 - male, 1- female" value="<?php echo $row[12] ?>" required /></p></td>
            <td width="200"><p><label for="sellerinc"><b>Seller's incorporation type</b></label></td>
            <td width="500"><input type="number" name="sellerinc" placeholder="0 - individual, 2 - business" value="<?php echo $row[13] ?>" required /></p></td></tr>
            <tr><td width="200"><p><label for="mrchntname"><b>Name of Merchant</b></label></td>
            <td width="500"><input type="text" name="mrchntname" placeholder="Name of Merchant" value="<?php echo $row[11] ?>" /></p></td>
            <td width="200"><p><label for="filesocialid"><b>URL to Social ID photo</b></label></td>
            <td width="500"><input type="text" name="filesocialid" placeholder="URL to Social ID photo" value="<?php echo $row[14] ?>" required /></p></td></tr>
            <tr><td width="200"><p><label for="filecheque"><b>URL to proof of bank account ownership</b></label></td>
            <td width="500"><input type="text" name="filecheque" placeholder="URL to proof of bank account ownership" value="<?php echo $row[15] ?>" required /></p></td>
            <td width="200"><p><label for="filecorporate"><b>URL to incorporation document photop</b></label></td>
            <td width="500"><input type="text" name="filecorporate" placeholder="URL to incorporation document photo" value="<?php echo $row[16] ?>" required /></p></td></tr>
            <tr><td><p><input name="submit" type="submit" value="Submit" /></p></td></tr>
        </table>
    </form>
    </div>
</div>
</body>
<?php mysqli_close($link);
?>
</html>