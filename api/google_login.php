<?php 
    include_once($_SERVER['DOCUMENT_ROOT']."/app/php/common/include/session.php"   );
    //include $_SERVER["DOCUMENT_ROOT"].'/app/php/common/include/db.php';

//echo "[".$profile."]";
//$profile_array = json_decode($profile,true);
//$userid = $profile_array["zah"];//email    $profile_array["mId"];
//$nick_name = $profile_array["zai"];
//print_r($profile_array);
//echo "<br><br>";
//print_r($profile_array["profile"]["nickname"]);
//echo "<br><br>----[".$profile_array["profile_needs_agreement"]."]";
//echo "<br>----[".print_r($profile_array[0])."]";
//echo "[".$profile->.profile[0]->nickname."]";
//exit;

//adroid
//{"mId":"102622452978531598120","versionCode":3,"zah":"tudeilee@gmail.com","zai":"이승규","zal":1594609094,"zam":"328F615B6D1DBF59535EC30FA3DDD6EC","zan":[{"zzbc":"https://www.googleapis.com/auth/userinfo.profile","zzq":1},{"zzbc":"https://www.googleapis.com/auth/userinfo.email","zzq":1},{"zzbc":"openid","zzq":1},{"zzbc":"email","zzq":1},{"zzbc":"profile","zzq":1}],"zao":"승규","zap":"이","zaq":[]}

//ios
//{    "name" : "이승규",    "familyName" : "이",    "imageURL" : "https:\/\/lh5.googleusercontent.com\/-Qolf-gwzQQY\/AAAAAAAAAAI\/AAAAAAAAAAA\/AMZuucnwK2ECHpkEVaXuQaqL49wDBGEJGA\/s175\/photo.jpg",    "givenName" : "승규",    "email" : "tudeilee@gmail.com"  }

$query = "INSERT INTO itapp_api_log SET
userid           = '".addslashes(iconv("utf-8", "euc-kr", $userid))."',
profile          = '".addslashes(iconv("utf-8", "euc-kr", $profile))."',
fcmtoken        = '".addslashes(iconv("utf-8", "euc-kr", $fcmtoken))."',
reg_ip          = '".$_SERVER['REMOTE_ADDR']."',
reg_datetime    = NOW(),
user_agent      = '".$_SERVER['HTTP_USER_AGENT']."',
page_gbn        = 'google',
device_gbn      = '".$devicegbn."' ";
$result = mysql_query($query);

//echo "google_login..<br>\n";
//echo "domain:".$_SERVER["HTTP_HOST"]."<br>\n";
//echo "uri:".$_SERVER['REQUEST_URI']."<br>\n";
//echo "userid:".$userid."<br>\n";
//echo "profile:".$profile."<br>\n";
//echo "fcmtoken:".$fcmtoken."<br>\n<br><br><br><br><br>";
//exit;

if($userid=="" || $profile==""){
    echo "profile no recv";
    echo "<br><br>연동 정보를 수신하지 못했습니다.<br>다른 로그인 방법을 이용해주세요";
    exit;
}
//echo "11<br>";
//echo $profile."<br><br>";
$profile_arr = json_decode($profile,true);
//echo "22<br>";
$email = $profile_arr["zah"];
//echo "33<br>";
if($email==""){ $email = $profile_arr["email"]; }
//echo "44<br>";
//echo "email:".$email;exit;
//echo "55<br>";

//$email = "shkimdev@gmail.com";//test

//$nick_name = $profile_arr["zai"];
$user_name = $profile_arr["zai"];
if($user_name==""){ $user_name = $profile_arr["name"]; }

//$query_user_cnt = " SELECT count(*) cnt FROM itapp_user WHERE del_yn   = 'N' and user_id = 'google_".$userid."' ";
$query_user_cnt = " SELECT count(*) cnt FROM itapp_user WHERE del_yn   = 'N' and user_id = '".$email."'  ";//remark 20.08.07 11:30 and sign_type='google'
$result_user_cnt = mysql_query($query_user_cnt,$CONN);
//$user_cnt = mysql_num_rows($result_user); 
$row_user_cnt = mysql_fetch_array($result_user_cnt);
if($row_user_cnt['cnt'] > 0){
    $query_upd = " UPDATE `intopet`.`itapp_user` set `push_token` = '".$fcmtoken."', device_gbn = '".$devicegbn."', login_status = 'Y'
                    WHERE `user_id` = '".$email."'  ";//remark 20.08.07 11:30 and sign_type='google'
    $result = mysql_query($query_upd);
}else{
    $query_ins = " INSERT INTO `intopet`.`itapp_user` 
                	(`idx`, `user_id`, 	`user_name`, 	                                 `reg_datetime`, `reg_id`, 	`reg_ip`, 	`del_yn`, 	`last_login_datetime`,`device_gbn`,`push_token`,`sign_type`	, new_push_setting_11, new_push_setting_01, new_push_setting_02, new_push_setting_03, new_push_setting_04, new_push_setting_05, new_push_setting_06, new_push_setting_07, new_push_setting_08, new_push_setting_09, new_push_setting_10)
            	VALUES	('', '".$email."', '".iconv("utf-8","euc-kr",$user_name)."', now(),          'system',  '".$_SERVER['REMOTE_ADDR']."','N',now(),       '".$devicegbn."',   '".$fcmtoken."','google','0',                 '1',                 '1',                 '1',                 '1',                 '1',                 '1',                 '1',                 '1',                 '1',                 '1') ";
    $result = mysql_query($query_ins);
//".$profile->.profile[0]->nickname."
}
$query_user = " SELECT * FROM itapp_user WHERE del_yn   = 'N' and user_id = '".$email."' ";
$result_user = mysql_query($query_user,$CONN);
$row_user = mysql_fetch_array($result_user);

$qry = mysql_query("DELETE FROM itapp_session WHERE session_key  ='$PHPSESSID'", $CONN);
$qid = mysql_query("DELETE FROM itapp_session WHERE session_expiry < " . time(), $CONN);

$user_idx		  = $row_user['idx'];
$user_id		  = $row_user['user_id'];
$user_name	      = $row_user['user_name'];

$_SESSION["user_idx"]       = $user_idx;
$_SESSION["user_id"]        = $user_id;
$_SESSION["user_name"]      = $user_name;
$_SESSION["intopet_login"]  = $user_idx;


SetCookie("user_idx",			$user_idx,		time()+(60*60*24*365),"/",$_SERVER['SERVER_NAME']);
SetCookie("user_id",			$user_id,		time()+(60*60*24*365),"/",$_SERVER['SERVER_NAME']);
SetCookie("user_name",		    $user_name,		time()+(60*60*24*365),"/",$_SERVER['SERVER_NAME']);
SetCookie("intopet_login",	    $user_idx,		time()+(60*60*24*365),"/",$_SERVER['SERVER_NAME']);

$connect_date = date('YmdHis');
$upd_data = mysql_query("UPDATE itapp_user SET last_login_datetime = '".$connect_date."' WHERE user_id = '".$_SESSION['user_id']."' ", $CONN);

$add_data = mysql_query("INSERT into itapp_connect_log (user_id, ip, log_datetime, gbn, user_agent) 
   values ('".$_SESSION['user_id']."','".$_SERVER["REMOTE_ADDR"]."',now(),'login','".$_SERVER['HTTP_USER_AGENT']."')", $CONN);

mysql_close($CONN);

//echo $_SESSION["user_id"];
?>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<div style="width:100%;height:100%;"><img style="margin:20% auto;width:100%;" src="/app/img/loading.gif"></div>
<iframe src="intopetapp://login_result?mid=<?=$_SESSION["user_id"]?>" style="display:none;"></iframe>
<script>setTimeout("document.location.replace('/app/php/lounge/today/today.php');", 1000);</script>
<?
/*
1. 서버에서 클라이언트로 로그인 요청

http://app2.intopet.co.kr/app/api/google_login_request.php?

=> 해당 페이지로 들어오면 path가 "google_login_request.php" 인경우 다음작업

2. 클라이언트에서 서버로 로그인 요청
String url="http://app2.intopet.co.kr/app/api/google_login.php?"; //카카오 로그인
//String url="http://app2.intopet.co.kr/app/api/facebook_login.php?"; //페이스북 로그인
//String url="http://app2.intopet.co.kr/app/api/login.php?"; //로그인

String parameter="userid=사용자고유키값&profile=json형식의값&fcmtoken=푸시토큰;

wb.postUrl(url, EncodingUtils.getBytes(postData, "BASE64")); //post 방식
wb.loadUrl(url + parameter); //get 방식
*/
?>
