<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

mysqli_query($conn, "set autocommit =0");
mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
mysqli_query($conn, "start transaction");
	

if (array_key_exists("PID", $_GET)) {
    $PID = $_GET["PID"];
    $query = "select * from customer where PID = $PID";
    $result = mysqli_query($conn, $query);
    $customer = mysqli_fetch_assoc($result);
    if (!$customer) {
    	mysqli_query($conn,"rollback");
        msg("고객 정보가 존재하지 않습니다.");
        return;
    }
}
mysqli_query($conn,"commit");
?>
    <div class="container fullwidth">

        <h3>고객 정보 상세 보기</h3>

        <p>
            <label for="PID">고객 ID</label></label>
            <input readonly type="text" id="PID" name="PID" value="<?= $customer['PID'] ?>"/>
        </p>

        <p>
            <label for="name">고객 이름</label>
            <input readonly type="text" id="name" name="name" value="<?= $customer['name'] ?>"/>
        </p>

        <p>
            <label for="phone_number">핸드폰 번호</label>
            <input readonly type="text" id="phone_number" name="phone_number" value="<?= $customer['phone_number'] ?>"/>
        </p>
    </div>
<? include "footer.php" ?>