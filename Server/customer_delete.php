<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

mysqli_query($conn, "set autocommit =0");
mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
mysqli_query($conn, "start transaction");

$PID = $_GET['PID'];

$result = mysqli_query($conn, "select PID from customer where PID = $PID");
$order_ID = mysqli_query($conn, "select order_ID from customer natural join orders where PID = $PID");

if(mysqli_fetch_array($result)){
	while($row = mysqli_fetch_array($order_ID)){
		$test = mysqli_query($conn, "delete from order_drycleaning where order_ID = $row[0]");
		if(!$test)
		{
			mysqli_query($conn,"rollback");
	    	msg('Query Error : '.mysqli_error($conn));
	    	return;
		}
		$test = mysqli_query($conn, "delete from order_alteration where order_ID = $row[0]");
		if(!$test)
		{
			mysqli_query($conn,"rollback");
	    	msg('Query Error : '.mysqli_error($conn));
	    	return;
		}
		$test = mysqli_query($conn, "delete from job where order_ID = $row[0]");
		if(!$test)
		{
			mysqli_query($conn,"rollback");
	    	msg('Query Error : '.mysqli_error($conn));
	    	return;
		}
		$test = mysqli_query($conn, "delete from orders where order_ID = $row[0]");
		if(!$test)
		{
			mysqli_query($conn,"rollback");
	    	msg('Query Error : '.mysqli_error($conn));
	    	return;
		}
	}
	
	$ret = mysqli_query($conn, "delete from customer where PID = $PID");
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
	    echo "<meta http-equiv='refresh' content='0;url=customer_list.php'>";
	}	
}
?>

