<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

mysqli_query($conn, "set autocommit =0");
mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
mysqli_query($conn, "start transaction");

$mode = "등록";
$action = "customer_insert.php";

if (array_key_exists("PID", $_GET)) {
    $PID = $_GET["PID"];
    $query =  "select * from customer where PID = $PID";
    $result = mysqli_query($conn, $query);
    $customer = mysqli_fetch_array($result);
    if(!$customer) {
    	mysqli_query($conn,"rollback");
        msg("해당 사용자가 존재하지 않습니다.");
        return;
    }
    $mode = "수정";
    $action = "customer_modify.php";
}

$query = "select * from customer";
$result = mysqli_query($conn, $query);

mysqli_query($conn,"commit");
?>
    <div class="container">
        <form name="customer_form" action="<?=$action?>" method="post" class="fullwidth">
            <input type="hidden" name="PID" value="<?=$customer['PID']?>"/>
            <h3>고객 정보 <?=$mode?></h3>
            <p>
                <label for="PID">고객 ID</label>
                <input type="text" placeholder="고객 ID 입력 (정수로 입력)" id="PID" name="PID" value="<?=$customer['PID']?>"/>
            </p>
            <p>
                <label for="name">고객 이름</label>
                <input type="text" placeholder="고객 이름 입력" id="name" name="name" value="<?=$customer['name']?>"/>
            </p>
            <p>
                <label for="phone_number">고객 휴대폰 번호 입력</label></label>
                <input type="text" placeholder="01012345678" id="phone_number" name="phone_number" value="<?=$customer['phone_number']?>" />
            </p>

            <p align="center"><button class="button primary large" onclick="javascript:return validate();"><?=$mode?></button></p>

            <script>
                function validate() {
                    if(document.getElementById("PID").value == "-1") {
                        alert ("고객 ID를 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("name").value == "") {
                        alert ("고객 이름 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("phone_number").value == "") {
                        alert ("고객의 휴대폰 번호를 입력해 주십시오"); return false;
                    }
                    return true;
                }
            </script>

        </form>
    </div>
<? include("footer.php") ?>