<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

?>
<div class="container">
	<?
	$conn = dbconnect($host, $dbid, $dbpass, $dbname);
	
	mysqli_query($conn, "set autocommit =0");
	mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
	mysqli_query($conn, "start transaction");
	
    $query = "select order_ID, name, date_time, completion from orders natural join customer natural join job";

    if (array_key_exists("search_keyword", $_POST)) {  // array_key_exists() : Checks if the specified key exists in the array
        $search_keyword = $_POST["search_keyword"];
        $query .= " where name like '%$search_keyword%' or order_ID like '%$search_keyword%'";
    }
    $result = mysqli_query($conn, $query);
    
    mysqli_query($conn,"commit");
    ?>
    
    <form action="order_list.php" method="post">
		<input type="text" name="search_keyword" placeholder="검색">
		<br></br>
	</form>
	
    <table class="table table-striped table-bordered">
        <tr>
            <th>주문 번호</th>
            <th>주문자 이름</th>
            <th>주문 일자</th>
            <th>작업 여부</th>
            <th>작업 토글</th>
            <th>수정 or 삭제</th>

        </tr>
    <?
    //$conn = dbconnect($host, $dbid, $dbpass, $dbname);
 
    while($row=mysqli_fetch_array($result)){
        echo "<tr><td><a href='order_detail.php?order_ID={$row['order_ID']}'>$row[0]</td>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[2]</td>";
        if($row[3] == 0){
        	echo "<td>미완료</td>";
        }
        else{
        	echo "<td>완료</td>";
        }
        echo "<td width='17%'>
                <a href='job.php?order_ID={$row[0]}'><button class='button primary small'>toggle</button></a>
                </td>";
                
        echo "<td width='17%'>
                <a href='order_form.php?order_ID={$row[0]}'><button class='button primary small'>수정</button></a>
                <button onclick='javascript:deleteConfirm({$row[0]})' class='button danger small'>삭제</button>
                </td>";
        echo "</tr>";
    }
    ?>
    </table>
    <script>
        function deleteConfirm(order_ID) {
            if (confirm("정말 삭제하시겠습니까?") == true){    //확인
                window.location = "order_delete.php?order_ID=" + order_ID;
            }else{   //취소
                return;
            }
        }
    </script>
</div>
    
<?
include "footer.php"
?>
