<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수


$conn = dbconnect($host, $dbid, $dbpass, $dbname);

mysqli_query($conn, "set autocommit =0");
mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
mysqli_query($conn, "start transaction");

if (array_key_exists("order_ID", $_GET)) {
    $order_ID = $_GET["order_ID"];
    $query = "select order_ID, PID, name, phone_number, date_time, completion from customer natural join orders natural join job where order_ID = $order_ID";
    $result = mysqli_query($conn, $query);
    $order = mysqli_fetch_assoc($result);
    
    if (!$order) {
    	mysqli_query($conn,"rollback");
        msg("구매이력이 없습니다.");
        return;
    }
}

?>
    <div class="container fullwidth">

        <h3>주문 정보 상세 보기</h3>

        <p>
            <label for="order_ID">주문번호</label>
            <input readonly type="text" name="order_ID" value="<?= $order['order_ID'] ?>"/>
        </p>

        <p>
            <label for="PID">고객 ID</label>
            <input readonly type="text" name="PID" value="<?= $order['PID'] ?>"/>
        </p>

        <p>
            <label for="name">고객 이름</label>
            <input readonly type="text"  name="name" value="<?= $order['name'] ?>"/>
        </p>

        <p>
            <label for="name">고객 핸드폰 번호</label>
            <input readonly type="text"  name="name" value="<?= $order['phone_number'] ?>"/>
        </p>
        
        <p>
            <label for="date_time">주문일자</label>
            <input readonly type="text" name="date_time" value="<?= $order['date_time'] ?>"/>
        </p>
    </div>
    
    <br>
    
    <div class="container">
    
    <table class="table table-striped table-bordered">
        <tr>
            <th>No.</th>
            <th>종류</th></th>
            <th>개당 가격</th>
            <th>수량</th>
			<th>작업 여부</th>

        </tr>
        
        <?

        $query = "select clothes_type, price, quantity, completion from order_drycleaning natural join job natural join drycleaning where order_ID = $order_ID";
        $result = mysqli_query($conn, $query);
		
        $row_index = 1;
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>{$row_index}</td>";
            echo "<td>{$row['clothes_type']}</td>";
            echo "<td>{$row['price']}</td>";
            echo "<td>{$row['quantity']}</td>";
            if($row['completion'] == 0){
        		echo "<td>미완료</td>";
        	}
        	else{
        		echo "<td>완료</td>";
        	}
            echo "</tr>";
            $row_index++;
        }
        
        $query = "select alteration_type, price, quantity, completion from order_alteration natural join job natural join clothes_alteration where order_ID = $order_ID";
        $result = mysqli_query($conn, $query);
        
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>{$row_index}</td>";
            echo "<td>{$row['alteration_type']}</td>";
            echo "<td>{$row['price']}</td>";
            echo "<td>{$row['quantity']}</td>";
            if($row['completion'] == 0){
        		echo "<td>미완료</td>";
        	}
        	else{
        		echo "<td>완료</td>";
        	}
            echo "</tr>";
            $row_index++;
        }
        
        mysqli_query($conn,"commit");
        ?>
    </table>
</div>
    
<? include("footer.php") ?>