<?
//������ ������;
session_start();
//���� ������������ �� �����������

if (!(isset($_SESSION['Name'])))
{
//���� �� �������� �����������
header("Location: ./index.php");
exit;
};
//������� ���� �������� ��� �������������� �������������
$nm =$_SESSION['Name'] ;
echo ("<div style=\"text-align: center; margin-top: 10px;\">");
print "������������ ������� $nm <br> ";
print "�� �� ��������� �������� $nm <br> ";
?>
