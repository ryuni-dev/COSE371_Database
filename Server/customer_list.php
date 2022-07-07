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

    $query = "select * from customer";
    
    if (array_key_exists("search_keyword", $_POST)) {  // array_key_exists() : Checks if the specified key exists in the array
        $search_keyword = $_POST["search_keyword"];
        $query .= " where name like '%$search_keyword%' or PID like '%$search_keyword%'";
    }
    $result = mysqli_query($conn, $query);
    if (!$result) {
    	mysqli_query($conn,"rollback");
        die('Query Error : ' . mysqli_error());
        return;
    }
    mysqli_query($conn,"commit");
    ?>
    
    <form action="customer_list.php" method="post">
		<input type="text" name="search_keyword" placeholder="검색">
		<br></br>
	</form>

    <table class="table table-striped table-bordered">
        <tr>
            <th>No.</th>
            <th>고객 ID</th>
            <th>이름</th>
            <th>핸드폰 번호</th>
            <th>수정 or 삭제</th>
        </tr>
        <?
        $row_index = 1;
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>{$row_index}</td>";
            echo "<td><a href='customer_view.php?PID={$row['PID']}'>{$row['PID']}</a></td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['phone_number']}</td>";
            
            echo "<td width='17%'>
                <a href='customer_form.php?PID={$row['PID']}'><button class='button primary small'>수정</button></a>
                 <button onclick='javascript:deleteConfirm({$row['PID']})' class='button danger small'>삭제</button>
                </td>";
            echo "</tr>";
            $row_index++;
        }
        ?>
    </table>
    <script>
        function deleteConfirm(PID) {
            if (confirm("정말 삭제하시겠습니까?") == true){    //확인
                window.location = "customer_delete.php?PID=" + PID;
            }else{   //취소
                return;
            }
        }
    </script>
</div>
<? include("footer.php") ?>
