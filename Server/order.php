<?
include "config.php";
include "util.php";
?>

<div class="container">

    <?
    $conn = dbconnect($host, $dbid, $dbpass, $dbname);
    
    mysqli_query($conn, "set autocommit =0");
	mysqli_query($conn, "set sesstion transaction isolation level serializable"); 
	mysqli_query($conn, "start transaction");
    
    $PID = $_POST['PID'];
    $quantity = $_POST['quantity'];
    
    $codes = array('상의'=>0, '외투'=>1, '하의'=>2, '기장수선'=>3, '박음질'=>4, '부자재'=>5);

    $available_insert = check_id($conn, $PID);
    
    if ($available_insert){
        $total_amount = 0;
        foreach($_POST['type_code'] as $type){
            $query = "select price from clothes_alteration where alteration_type = $type";
            $result = mysqli_query($conn, $query);
            $total_amount += mysqli_fetch_array($result)[0];
            
            $query = "select price from drycleaning where clothes_type = $type";
            $result = mysqli_query($conn, $query);
            $total_amount += mysqli_fetch_array($result)[0];
        }
        
        
        $query = "insert into orders (PID, date_time) values ('$PID', NOW())";
        $result = mysqli_query($conn, $query);
        
        if (!$result){
			mysqli_query($conn,"rollback");
			s_msg('주문하기가 실패하였습니다. 다시 시도하여 주십시오.'); 
			return; 
        }
        
        $order_ID = mysqli_insert_id($conn);
        
        if (!$order_ID){
			mysqli_query($conn,"rollback");
			s_msg('주문하기가 실패하였습니다. 다시 시도하여 주십시오.'); 
			return; 
        }
        
        $query = "insert into job (order_ID, completion) values ('$order_ID', 0)";
        $result = mysqli_query($conn, $query);
        
        if (!$result){
			mysqli_query($conn,"rollback");
			s_msg('주문하기가 실패하였습니다. 다시 시도하여 주십시오.'); 
			return; 
        }
        
        foreach($_POST['type_code'] as $type){

			$query = "select * from clothes_alteration where alteration_type = $type";
			$result = mysqli_query($conn, $query);
			
			$qnt = $quantity[$codes[$type]];
			if ($type == "상의" or $type == "하의" or $type == "외투"){
				$query = "insert into order_drycleaning (order_ID, clothes_type, quantity) values ('$order_ID', '$type', '$qnt')";
            	$test = mysqli_query($conn, $query);
            	
            	if (!$test){
					mysqli_query($conn,"rollback");
					s_msg('주문하기가 실패하였습니다. 다시 시도하여 주십시오.'); 
					return; 
        		}
			}
			else{
            	$query = "insert into order_alteration (order_ID, alteration_type, quantity) values ('$order_ID', '$type', '$qnt')";
            	$test = mysqli_query($conn, $query);
            	
            	if (!$test){
					mysqli_query($conn,"rollback");
					s_msg('주문하기가 실패하였습니다. 다시 시도하여 주십시오.'); 
					return; 
        		}
			}
			
        }
        mysqli_query($conn,"commit");
        s_msg('주문이 완료되었습니다');
        echo "<script>location.replace('order_list.php');</script>";
    }
    else{
		mysqli_query($conn,"rollback");
        msg('등록되지 않은 아이디 입니다.');
		return; 
    }
    ?>

</div>

