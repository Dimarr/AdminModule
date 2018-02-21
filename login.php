<?

session_start();
echo "1";
if (isset($_POST['login']) && isset($_POST['password']))
{
echo "1";
$login = $_POST['login'];
    //mysqli_real_escape_string($_POST['login']);
$password = $_POST['password'];
    echo "2";

if (($login=='a123')&& ($password=='123')){
echo ("����� ��������� � ������ �����");
$_SESSION['Name']=$login;

header("Location: ./secret.php");
}
else
{die('Not correct.');
}
}
?>
