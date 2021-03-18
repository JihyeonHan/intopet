<?php 
include_once($_SERVER['DOCUMENT_ROOT']."/app/php/common/include/session.php");

$query_app_push = "SELECT user.push_token
                        , user.device_gbn
                        , push.push_title
                        , push.push_desc
                        , push.push_link
                        , push.idx
                        , user.push_time_start
                        , user.push_time_end
                        , user.new_push_setting_11
                    FROM itapp_app_push_user push
                    LEFT JOIN itapp_user user
                    ON user.idx = push.user_idx
                    WHERE push.del_yn = 'N'
                    AND push.push_result IS NULL
                    AND user.device_gbn = 'android'";
$result_app_push = mysql_query($query_app_push);
while($query_app_push && $row_app_push = mysql_fetch_assoc($result_app_push)){

    // 사용자 알림OFF 설정이 되어있는 경우
    if($row_app_push[new_push_setting_11] == "1"){
        $push_time_start = $row_app_push[push_time_start];
        $push_time_end = $row_app_push[push_time_end];

        $now = date("H:i:s");

        if($now >= $push_time_start && $now <= $push_time_end){
            mysql_query("UPDATE itapp_app_push_user SET push_result = '0', push_result_txt = 'user-push-time-off' WHERE idx = '".$row_app_push[idx]."'");
            continue;
        }
    }

    $start_datetime = date("Y-m-d H:i:s");

    if(!$row_app_push[push_token]){
        mysql_query("UPDATE itapp_app_push_user SET push_result = '0', push_result_txt = 'user-token-null' WHERE idx = '".$row_app_push[idx]."'");
    } else {

        $title      = $row_app_push[push_title];
        $message    = $row_app_push[push_desc];
        $land_url   = $row_app_push[push_link] ? $row_app_push[push_link] : "http://app2.intopet.co.kr/app";
        $token      = $row_app_push[push_token];
        $fcmtoken   = $token;

        $data = array(
            'title'=>$title
            ,'msg'=>$message
            ,'url'=>$land_url
            ,'img_noti'=>'http://app.intopet.co.kr/ID5948eacc00ee755a3f791d73/20200227055452415_tmp_1320617560.png'
        );

        $url = "https://fcm.googleapis.com/fcm/send";
        $apiKey = "AAAAbzl4TmI:APA91bH53Bc8zzFWwTPHHd6avNp48wi_t9Bqsbc8V2kFOzRDDw_v0efmI7crqwh0W2p4y1qpYnhwP6DYAJeTBSh3thjFJw3GdmMnO_ojFdtxzF_Wm3Hb1nmQMpk3tFmMAlE7ccqR6-aw";
        $headers = array(
            'Authorization:key='.$apiKey
            ,'Content-Type: application/json'
        );
        
        $fields = array(
            'to'=>$token
            ,'priority'=>'high'
            ,'content_available'=>true
            ,'mutable_content'=>false
            ,'data'=>$data
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);
        
        $end_datetime = date("Y-m-d H:i:s");

        $query_push_result = "UPDATE itapp_app_push_user
                                SET push_result = '".($result->success)."'
                                    , push_result_txt = '".($result->results[0]->error)."'
                                    , start_datetime = '".$start_datetime."'
                                    , end_datetime = '".$end_datetime."'
                                WHERE idx = '".$row_app_push[idx]."'";
        $result_push_result = mysql_query($query_push_result);

        $query_log = "INSERT INTO itapp_push_log SET
                        fcmtoken        = '".addslashes(iconv("utf-8", "euc-kr", $fcmtoken))."',
                        returnstr       = '".addslashes(iconv("utf-8", "euc-kr", json_encode($result)))."',
                        title           = '".addslashes(iconv("utf-8", "euc-kr", $title))."',
                        msg             = '".addslashes(iconv("utf-8", "euc-kr", $message))."',
                        reg_ip          = '".$_SERVER[REMOTE_ADDR]."',
                        reg_datetime    = NOW(),
                        user_agent      = '".$_SERVER['HTTP_USER_AGENT']."',
                        page_gbn        = 'android' ";
        $result = mysql_query($query);
    }
}
?>