<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

mysqli_query($conn, "set autocommit =0");
mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
mysqli_query($conn, "start transaction");

$order_ID = $_GET['order_ID'];

$result = mysqli_query($conn, "select order_ID from orders where order_ID = $order_ID");

if(mysqli_fetch_array($result)){
	$ret = mysqli_query($conn, "delete from job where order_ID = $order_ID");
	if(!$ret)
	{
		mysqli_query($conn,"rollback");
	    msg('Query Error : '.mysqli_error($conn));
	    return;
	}
	$ret = mysqli_query($conn, "delete from order_drycleaning where order_ID = $order_ID");
	if(!$ret)
	{
		mysqli_query($conn,"rollback");
	    msg('Query Error : '.mysqli_error($conn));
		return;
	}
	$ret = mysqli_query($conn, "delete from order_alteration where order_ID = $order_ID");
	if(!$ret)
	{
		mysqli_query($conn,"rollback");
	    msg('Query Error : '.mysqli_error($conn));
	    return;
	}
	$ret = mysqli_query($conn, "delete from orders where order_ID = $order_ID");
	if(!$ret)
	{
		mysqli_query($conn,"rollback");
	    msg('Query Error : '.mysqli_error($conn));
	    return;
	}
	else
	{
		mysqli_query($conn,"commit");
	    s_msg ('성공적으로 삭제 되었습니다');
	    echo "<meta http-equiv='refresh' content='0;url=order_list.php'>";
	}	
}

else{
	mysqli_query($conn,"rollback");
	s_msg ('에러 : 삭제할 수 없습니다.');
    echo "<meta http-equiv='refresh' content='0;url=order_list.php'>";
    return;
}

?>

