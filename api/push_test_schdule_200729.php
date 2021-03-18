<?php 
date_default_timezone_set('Asia/Seoul');

$Cfg_DB_Host = "211.239.154.7";
$Cfg_DB_User = "intopet";
$Cfg_DB_Pass = "intopetD5N9T2X6@";
$Cfg_DB_Name = "intopet";

$CONN = mysql_connect($Cfg_DB_Host, $Cfg_DB_User, $Cfg_DB_Pass);
mysql_select_db($Cfg_DB_Name, $CONN);
mysql_query("set session character_set_results=utf8;");

mysql_query("set session character_set_results=utf8;");
mysql_query("set session character_set_client=euckr;");
mysql_query("set session character_set_connection=euckr;");
 
echo date("Y-m-d H:i:s")." start\n";

    echo date("Y-m-d H:i:s")." check\n";
    $query_que = " SELECT * 
                    FROM itapp_app_push push
                    WHERE push.del_yn = 'N'
                    AND push_date = '".date("Y-m-d")."' AND push_hour = '".date("H")."' AND push_min = '".date("i")."' ";
    $result_que = mysql_query($query_que);
    $que = "N";
    if($query_que && $row_que = mysql_fetch_assoc($result_que)){
        $que = "Y";
    }
    if($que=="N"){
        echo "nodata\n";
        exit;
    }


    $query_app_push = "SELECT user.idx
                        , user.push_token
                        , user.device_gbn
                        , user.push_time_start
                        , user.push_time_end
                        , user.new_push_setting_11
                    FROM itapp_user user
                    WHERE user.del_yn = 'N'
                    AND user.device_gbn IS NOT NULL
                    AND user.push_token IS not NULL  ";
    if($row_que['device_gbn']=='1'){ //all
    }else if($row_que['device_gbn']=='2'){ //android
        $query_app_push .= " AND user.device_gbn = 'android' ";
    }else if($row_que['device_gbn']=='3'){ //ios
        $query_app_push .= " AND user.device_gbn = 'ios' ";
    }

    $result_app_push = mysql_query($query_app_push);
    while($query_app_push && $row_app_push = mysql_fetch_assoc($result_app_push)){

        // 사용자 알림OFF 설정이 되어있는 경우
        if($row_app_push[new_push_setting_11] == "1"){
            $push_time_start = $row_app_push[push_time_start];
            $push_time_end = $row_app_push[push_time_end];

            $now = date("H:i:s");

            if($now >= $push_time_start && $now <= $push_time_end){
                continue;
            }
        }

        $start_datetime = date("Y-m-d H:i:s");

        if(!$row_app_push['push_token']){
            //mysql_query("UPDATE itapp_app_push_user SET push_result = '0', push_result_txt = 'user-token-null' WHERE idx = '".$row_app_push['idx']."'");
        } else {

            $title      = $row_que['push_title'];
            $message    = $row_que['push_desc'];
            $land_url   = $row_que['push_link'] ? $row_que['push_link'] : "http://app2.intopet.co.kr/app";
            $token      = $row_app_push['push_token'];
            $fcmtoken   = $token;
            $push_img   = $row_que['file_path_1'];

            if($row_app_push['device_gbn']=='android'){
                //android
                $data = array(
                    'title'=>$title
                    ,'msg'=>$message
                    ,'url'=>'http://app2.intopet.co.kr/app/php/lounge/today/today.php?land='.$land_url
                    ,'img_noti'=>'http://app2.intopet.co.kr/app/files/'.$push_img
                );
                //android
                $apiKey = "AAAAbzl4TmI:APA91bH53Bc8zzFWwTPHHd6avNp48wi_t9Bqsbc8V2kFOzRDDw_v0efmI7crqwh0W2p4y1qpYnhwP6DYAJeTBSh3thjFJw3GdmMnO_ojFdtxzF_Wm3Hb1nmQMpk3tFmMAlE7ccqR6-aw";

                $fields = array(
                    'to'=>$token
                    ,'priority'=>'high'
                    ,'content_available'=>true
                    ,'mutable_content'=>false
                    ,'data'=>$data
                );

            }else if($row_app_push['device_gbn']=='ios'){
                //ios
                $notification = array(
                    'title'=>$title
                    ,'body'=>$message
                    ,'url'=>'http://app2.intopet.co.kr/app/php/lounge/today/today.php?land='.$land_url
                    ,'img_noti'=>'http://app2.intopet.co.kr/app/files/'.$push_img
                );
                //ios
                $apiKey = "AAAAA2uL1kc:APA91bEtjHiu1Owb4qKoARQt9b7v7Fn_noLfSOUBWZ0LblUntMXD5H5Z_2LXCqZMeIBLljPiNgl-ho6nWoP-Ot0erGQ1sMDWCh5qpTZk06Sj9Y5lBGuh_beSyWkW0YIV3ZmXqCNVnjeS";

                $fields = array(
                    'to'=>$token
                    ,'priority'=>'high'
                    ,'content_available'=>true
                    ,'mutable_content'=>true
                    ,'notification'=>$notification
                );

            }

            $url = "https://fcm.googleapis.com/fcm/send";
            $headers = array(
                'Authorization:key='.$apiKey
                ,'Content-Type: application/json'
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

            echo date("Y-m-d H:i:s")." sended push_idx[".$row_que['idx']."] user_idx[".$row_app_push['idx']."]\n";

            $query_log = "INSERT INTO itapp_push_log SET
                            fcmtoken        = '".addslashes(iconv("utf-8", "euc-kr", $fcmtoken))."',
                            returnstr       = '".addslashes(iconv("utf-8", "euc-kr", json_encode($result)))."',
                            title           = '".addslashes(iconv("utf-8", "euc-kr", $title))."',
                            msg             = '".addslashes(iconv("utf-8", "euc-kr", $message))."',
                            reg_ip          = 'localhost',
                            reg_datetime    = NOW(),
                            user_agent      = 'system',
                            page_gbn        = '".$row_app_push['device_gbn']."' ";
            $result = mysql_query($query_log);
        }
    }

echo date("Y-m-d H:i:s")." end\n";
?>
