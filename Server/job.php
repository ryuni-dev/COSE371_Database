<?
include "config.php";
include "util.php";

$conn = dbconnect($host,$dbid,$dbpass,$dbname);
$order_ID = $_GET['order_ID'];

mysqli_query($conn, "set autocommit =0");
mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
mysqli_query($conn, "start transaction");

$query = "select completion from job where order_ID = $order_ID";
$result = mysqli_query($conn, $query);
$res = mysqli_fetch_array($result);
if($res['completion'] == 0){
	$query = "update job set completion = 1 where order_ID = $order_ID";
    $result = mysqli_query($conn, $query);
    if (!$result){
		mysqli_query($conn,"rollback");
		s_msg('작업 수정에 실패하였습니다. 다시 시도하여 주십시오.'); 
		return; 
    }
}
else if($res['completion'] == 1){
	$query = "update job set completion = 0 where order_ID = $order_ID";
    $result = mysqli_query($conn, $query);
    if (!$result){
		mysqli_query($conn,"rollback");
		s_msg('작업 수정에 실패하였습니다. 다시 시도하여 주십시오.'); 
		return; 
    }
}
mysqli_query($conn,"commit");
s_msg('작업 여부 수정이 완료되었습니다');
echo "<script>location.replace('order_list.php');</script>";
?>