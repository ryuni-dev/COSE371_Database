<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

mysqli_query($conn, "set autocommit =0");
mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
mysqli_query($conn, "start transaction");


$PID = $_POST['PID'];
$name = $_POST['name'];
$phone_number = $_POST['phone_number'];

$result = mysqli_query($conn, "insert into customer (PID, name, phone_number) values('$PID', '$name', '$phone_number')");
if(!$result)
{
	mysqli_query($conn,"rollback");
    msg('Query Error : '.mysqli_error($conn));
    return;
}
else
{
	mysqli_query($conn,"commit");
    s_msg ('성공적으로 입력 되었습니다');
    echo "<script>location.replace('customer_list.php');</script>";
}

?>

