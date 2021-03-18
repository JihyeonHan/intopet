<?php
include_once($_SERVER['DOCUMENT_ROOT']."/app/php/common/include/session.php");
include_once($_SERVER['DOCUMENT_ROOT']."/app/php/mypage/hospital/vaccine_list.php");

$query_user = "SELECT u.idx AS user_idx
                    , u.user_id
                    , c.petCode
                    , c.coCode
                FROM itapp_pet_cert c
                LEFT JOIN itapp_user u
                ON u.idx = c.user_idx
                WHERE u.del_yn = 'N'
                AND c.del_yn = 'N'
                AND IFNULL(u.last_login_datetime, '') != ''
                -- AND u.idx = '334728'";
$result_user = mysql_query($query_user);
$i = 0;
while($result_user && $row_user = mysql_fetch_assoc($result_user)){
    $i++;
    // echo "[".$i."] ";
    // echo $row_user[petCode]." / ";
    // echo $row_user[coCode];
    // echo "<br/>";


    // 백신내역
    $data = array(
        'gbn' => 'charts'
        , 'chartType'=> 'vaccine'
        , 'coCode' => $row_user['coCode']
        , 'petCode' => $row_user['petCode']
    );
    $url = "http://admin.intocns.co.kr/admin/app/mongo_interface.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($data));
    $response = curl_exec($ch);
    curl_close($ch);

    // print_r(json_decode($response));
    $vaccine_list = json_decode($response);

    foreach($vaccine_list as $key => $val){
        $id = json_decode(json_encode($val), true);
        $_id = $id['_id']['$id'];
        $chartType = $val->chartType;
        $coCode = $val->coCode;
        $petCode = $val->petCode;
        $name = $val->name;
        $count = $val->count;
        $date = $val->date->sec;
        $nextDate = $val->nextDate->sec;
        $deleted = $val->deleted;

        $query_exists = "SELECT * FROM itapp_schedule WHERE mongo_old_id = '".$_id."' AND del_yn = 'N' AND user_idx = '".$row_user['user_idx']."'";
        $result_exists = mysql_query($query_exists);
        $rows_exists = mysql_num_rows($result_exists);
        
        if(!$rows_exists && !$deleted){
            $query_pet_cert = "SELECT * FROM itapp_pet_cert WHERE coCode = '".$coCode."' AND petCode = '".$petCode."' AND user_idx = '".$row_user['user_idx']."'";
            $result_pet_cert = mysql_query($query_pet_cert);
            $row_pet_cert = mysql_fetch_assoc($result_pet_cert);

            $query_schedule = "INSERT INTO itapp_schedule 
                                SET user_idx = '".$row_user['user_idx']."'
                                    , pet_idx = '".$row_pet_cert['pet_idx']."'
                                    , cg = null
                                    , title = '".urlencode("[".$name."]"." ".$name)."'
                                    , start_date = '".date("Y-m-d", $date)."'
                                    , end_date = '".date("Y-m-d", $date)."'
                                    , reg_datetime = '".date("Y-m-d h:i:s", $date)."'
                                    , reg_id = '".$row_user['user_id']."'
                                    , reg_ip = '".$_SERVER['REMOTE_ADDR']."'
                                    , del_yn = 'N'
                                    , mongo_old_id = '".$_id."'
                                    , mongo_yn = 'Y'
                                    , chartType = '".$chartType."'
                                    , coCode = '".$coCode."'
                                    , petCode = '".$petCode."'
                                    , count = '".$count."'
                                    , date = '".date("Y-m-d h:i:s", $date)."'
                                    , nextDate = '".date("Y-m-d h:i:s", $nextDate)."'";
            $result_schedule = mysql_query($query_schedule);
        }
    }

    // 내원내역
    $data = array(
        'gbn' => 'charts'
        , 'chartType'=> 'subjective'
        , 'coCode' => $row_user['coCode']
        , 'petCode' => $row_user['petCode']
    );
    $url = "http://admin.intocns.co.kr/admin/app/mongo_interface.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($data));
    $response = curl_exec($ch);
    curl_close($ch);

    // print_r(json_decode($response));
    $vaccine_list = json_decode($response);

    foreach($vaccine_list as $key => $val){
        // print_r($val);
        $id = json_decode(json_encode($val), true);
        $_id = $id['_id']['$id'];
        $chartType = $val->chartType;
        $coCode = $val->coCode;
        $petCode = $val->petCode;
        $type = $val->type;
        $comment = $val->comment;
        $count = $val->count;
        $date = $val->date->sec;
        $nextDate = $val->nextDate->sec;
        $deleted = $val->deleted;

        $query_exists = "SELECT * FROM itapp_schedule WHERE mongo_old_id = '".$_id."' AND del_yn = 'N' AND user_idx = '".$row_user['user_idx']."'";
        $result_exists = mysql_query($query_exists);
        $rows_exists = mysql_num_rows($result_exists);

        if(!$rows_exists && !$deleted){
            $query_pet_cert = "SELECT * FROM itapp_pet_cert WHERE coCode = '".$coCode."' AND petCode = '".$petCode."' AND user_idx = '".$row_user['user_idx']."'";
            $result_pet_cert = mysql_query($query_pet_cert);
            $row_pet_cert = mysql_fetch_assoc($result_pet_cert);

            $query_schedule = "INSERT INTO itapp_schedule 
                                SET user_idx = '".$row_user['user_idx']."'
                                    , pet_idx = '".$row_pet_cert['pet_idx']."'
                                    , cg = null
                                    , title = '".urlencode("[".$type."]"." ".$comment)."'
                                    , start_date = '".date("Y-m-d", $date)."'
                                    , end_date = '".date("Y-m-d", $date)."'
                                    , reg_datetime = '".date("Y-m-d h:i:s", $date)."'
                                    , reg_id = '".$row_user['user_id']."'
                                    , reg_ip = '".$_SERVER['REMOTE_ADDR']."'
                                    , del_yn = 'N'
                                    , mongo_old_id = '".$_id."'
                                    , mongo_yn = 'Y'
                                    , chartType = '".$chartType."'
                                    , coCode = '".$coCode."'
                                    , petCode = '".$petCode."'
                                    , date = '".date("Y-m-d h:i:s", $date)."'
                                    , nextDate = '".date("Y-m-d h:i:s", $nextDate)."'";
            $result_schedule = mysql_query($query_schedule);
        }
    }
}
?>