<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";  

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

mysqli_query($conn, "set autocommit =0");
mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
mysqli_query($conn, "start transaction");

$mode = "등록";
$action = "order.php";

if (array_key_exists("order_ID", $_GET)) {
    $order_ID = $_GET["order_ID"];
    $query =  "select * from orders where order_ID = $order_ID";
    $result = mysqli_query($conn, $query);
    $orders = mysqli_fetch_array($result);
    if(!$orders) {
    	mysqli_query($conn,"rollback");
        msg("해당 주문이 존재하지 않습니다.");
        return;
    }
    mysqli_query($conn,"commit");
    $mode = "수정";
    $action = "order_modify.php?order_ID=$order_ID";
}
?>
<div class="container">
    <?
    $conn = dbconnect($host, $dbid, $dbpass, $dbname);
    
    mysqli_query($conn, "set autocommit =0");
	mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
	mysqli_query($conn, "start transaction");

    $query = "select * from drycleaning";

    $drycleaning = mysqli_query($conn, $query);
    if (!$drycleaning) {
    	mysqli_query($conn,"rollback");
        die('Query Error : ' . mysqli_error());
        return;
    }
    
    $query = "select * from clothes_alteration";

    $alteration = mysqli_query($conn, $query);
    if (!$alteration) {
    	mysqli_query($conn,"rollback");
        die('Query Error : ' . mysqli_error());
        return;
    }
    
    mysqli_query($conn,"commit");
    ?>
  
    <form name='orders' action="<?=$action?>" method='POST'>
        <p align='right'> 사용자 ID 입력: <input type='text' name='PID' value="<?=$orders['PID']?>"></p>
        <table class="table table-striped table-bordered">
            <tr>
                <th>No.</th> 
				<th>종류</th>
                <th>개당 가격</th>
                <th>수량</th>
                <th>선택</th>
            </tr>
            <?
            $row_index = 1;
            while ($row = mysqli_fetch_array($drycleaning)) {
                echo "<tr>";
                echo "<td>{$row_index}</td>";
                echo "<td>{$row['clothes_type']}</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td width='7%'>
					<input type='number' id='quantity' name='quantity[]'>
					</td>";
                echo "<td width='17%'>
                    <input type='checkbox' name=type_code[] value='{$row['clothes_type']}'>
                    </td>";
                echo "</tr>";
                $row_index++;
            }
            while ($row = mysqli_fetch_array($alteration)) {
                echo "<tr>";
                echo "<td>{$row_index}</td>";
                echo "<td>{$row['alteration_type']}</td>";
                echo "<td>{$row['price']}</td>";
				echo "<td width=7%>
					<input type='number' id='quantity' name='quantity[]'>
					</td>";

                echo "<td width='17%'>
                    <input type='checkbox' name=type_code[] value='{$row['alteration_type']}'>
                    </td>";
                echo "</tr>";
                $row_index++;
            }
            ?>
            
        </table>
        <div align='center'>
            <input type='submit' class='button primary small' value='전송'>
        </div>
    </form>
</div>
<? include("footer.php") ?>