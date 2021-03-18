<?php 
include_once($_SERVER['DOCUMENT_ROOT']."/app/php/common/include/session.php"   );

echo "start[".date("Y-m-d H:i:s", time())."]<br>\n";
echo "push_test<br>\n";

//$token = "d6BfvPg1S9-PzDGiZ_Mfan:APA91bHN9kDT48z3UkKWKmTrpoHEYUDtrLuFQs4vdVs6NO6N_ZDr6jneljg4VFdgAt5G6a20A9TqXhvQUf4OPrCI2vhEWPEe_5BJRNHFypBD9Lfcda5QjuNSvFA5tsFahbPWlh3GG9Ah";
if($_POST[fcmtoken]) $fcmtoken = $_POST[fcmtoken];
if($_GET[fcmtoken])  $fcmtoken = $_GET[fcmtoken];

if($fcmtoken==""){
    echo "토큰을 입력하세요";
    exit;
}
$title = "타이틀부분입니다.";
$message = "메세지를 넣어서 보낼수 있습니다.";

$token = $fcmtoken;

/*
{
   "to" : "dU47bZBGRiiEJbmv8BzUYy:APA91bEX0TNl7lVcBi1G8czKpsZo_goCVHDce7dN6mzYpraIava4FT_zakliv4sPMxpwj0q9D4tWwBF8BH_ZlgpfY2eFFBCi35j7Io3JNq9ibkpkHOn_LdJ1AGf0R6etAbe2WXF10zCG",
   "data": {
      "title":"타이틀",
      "msg":"메시지",
       "url": "http://www.naver.com",
       "img_noti": "http://img.danawa.com/images/news/images/000585/20200623104532850_70DDHGH5.jpg"
   },
   "priority":"high",
   "content-available":"true",
   "mutable-content":"false"
}

{
    "to":"d6BfvPg1S9-PzDGiZ_Mfan:APA91bHN9kDT48z3UkKWKmTrpoHEYUDtrLuFQs4vdVs6NO6N_ZDr6jneljg4VFdgAt5G6a20A9TqXhvQUf4OPrCI2vhEWPEe_5BJRNHFypBD9Lfcda5QjuNSvFA5tsFahbPWlh3GG9Ah",
    "data":{
        "title":"\ud0c0\uc774\ud2c0",
        "msg":"\uba54\uc138\uc9c0",
        "url":"http:\/\/app2.intopet.co.kr\/app\/php\/lounge\/story\/story_cont_view.php?idx=1604",
        "img_noti":"http:\/\/app.intopet.co.kr\/ID5948eacc00ee755a3f791d73\/20200227055452415_tmp_1320617560.png"
    },
    "priority":"high",
    "content-available":"true",
    "mutable-content":"false"
}

*/
/*
$notification = array();
$notification['title'] = $title;
$notification['msg'] = $message;
$notification['url'] = "http://app2.intopet.co.kr/app/php/lounge/story/story_cont_view.php?idx=1604"; 
$notification['img_dialog'] = "http://app.intopet.co.kr/ID5948eacc00ee755a3f791d73/20200227055452415_tmp_1320617560.png";
$notification['img_noti'] = "http://app2.intopet.co.kr/app/php/common/images/logo.svg";
$notification['p_idx'] = "2";
*/
$data = array('title'=>$title,'msg'=>$message,'url'=>'http://app2.intopet.co.kr/app/php/lounge/story/story_cont_view.php?idx=107763','img_noti'=>'http://app.intopet.co.kr/ID5948eacc00ee755a3f791d73/20200227055452415_tmp_1320617560.png');
$tokens = array();
$tokens[0] = $toden;

$url = "https://fcm.googleapis.com/fcm/send";
//$apiKey = "AAAAlX9Ehk0:APA91bGSZP8MdgGAH4-PQ1MAuiCBwfAgDURuRFT8ew35qmhxMhqtd-WWS5pziDDv5ZDepPkSxLJDYnPqT6EkY4FkmTOmbsXI3-HZFQZIjsq-BmID2r-lQGeJmwu-PVJMZj9ky0oBwgqi";
//$apiKey = "AAAAA2uL1kc:APA91bEtjHiu1Owb4qKoARQt9b7v7Fn_noLfSOUBWZ0LblUntMXD5H5Z_2LXCqZMeIBLljPiNgl-ho6nWoP-Ot0erGQ1sMDWCh5qpTZk06Sj9Y5lBGuh_beSyWkW0YIV3ZmXqCNVnjeS";
$apiKey = "AAAAbzl4TmI:APA91bH53Bc8zzFWwTPHHd6avNp48wi_t9Bqsbc8V2kFOzRDDw_v0efmI7crqwh0W2p4y1qpYnhwP6DYAJeTBSh3thjFJw3GdmMnO_ojFdtxzF_Wm3Hb1nmQMpk3tFmMAlE7ccqR6-aw";
//$fields = array('registration_ids'=>$tokens,'notification'=>$notification);
$fields = array('to'=>$token,'data'=>$data,'priority'=>'high','content-available'=>'true','mutable-content'=>'false');
$headers = array('Authorization:key='.$apiKey,'Content-Type: application/json');

print("<XMP>");
print(json_encode($fields));
print("\n");

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
$result = curl_exec($ch);
if($result === FALSE){
    echo "error_fail";
}
curl_close($ch);
echo "[".$result."]";

$query = "INSERT INTO itapp_push_log SET
fcmtoken        = '".addslashes(iconv("utf-8", "euc-kr", $fcmtoken))."',
returnstr       = '".addslashes(iconv("utf-8", "euc-kr", $result))."',
title           = '".addslashes(iconv("utf-8", "euc-kr", $title))."',
msg             = '".addslashes(iconv("utf-8", "euc-kr", $message))."',
reg_ip          = '".$_SERVER[REMOTE_ADDR]."',
reg_datetime    = NOW(),
user_agent      = '".$_SERVER['HTTP_USER_AGENT']."',
page_gbn        = 'android' ";
//echo $query;
$result = mysql_query($query);

/*
//$auth = "AIzaSyBSahDQm35SsGNSyiy_qrUBhP0XIr8w4cw";
$auth = "AAAAlX9Ehk0:APA91bGSZP8MdgGAH4-PQ1MAuiCBwfAgDURuRFT8ew35qmhxMhqtd-WWS5pziDDv5ZDepPkSxLJDYnPqT6EkY4FkmTOmbsXI3-HZFQZIjsq-BmID2r-lQGeJmwu-PVJMZj9ky0oBwgqi";

if($_POST[fcmtoken]) $fcmtoken = $_POST[fcmtoken];
if($_GET[fcmtoken])  $fcmtoken = $_GET[fcmtoken];
if($fcmtoken != "f0U5Eo3BT3upsOSwFtkKHL:APA91bGZbek2k6BtRsOP6pSKyLJoEeQz-0oR6t1psqvpdXpNoyykLqlAQWzX-OFlyCzX5MBSmQWRmj33GgOsc8zuqOdod_mn1Egb61sAgQJnT6ZcsmkpTFT1SxJtbSZeBxjtJ7PThsio" &&
   $fcmtoken != "d6BfvPg1S9-PzDGiZ_Mfan:APA91bHN9kDT48z3UkKWKmTrpoHEYUDtrLuFQs4vdVs6NO6N_ZDr6jneljg4VFdgAt5G6a20A9TqXhvQUf4OPrCI2vhEWPEe_5BJRNHFypBD9Lfcda5QjuNSvFA5tsFahbPWlh3GG9Ah" &&
   $fcmtoken != "e9dkv4LDTXyM4BAmO_zmhg:APA91bGOk6AUdLila3cDW2gPLH1mDv_6V9aMJVXsiJNi0-kH1mNoIKOpe3ovP3UozpRM0uCVeERaSKfYebODDzSIIE3sfmiSybDFWBpuzOJutF-oUMLw1NIeNUeTfkNOnX6S4DR6KKRa"){
       echo "token is not valid";
       exit;
}

$google_reg_id = $fcmtoken;

//exit;
echo $result = sendMessage($google_reg_id, "타이틀 타이틀", "내용부분입니다. 감사합니다.", "http://app2.intopet.co.kr/app/php/lounge/story/story_cont_view.php?idx=1604", "http://app.intopet.co.kr/ID5948eacc00ee755a3f791d73/20200227055452415_tmp_1320617560.png", "http://app2.intopet.co.kr/app/php/common/images/logo.svg", "2", $auth);

function sendMessage($registration_id, $title, $msg, $url, $img_dialog, $img_noti, $p_idx, $auth) {
	$data = array(
		'registration_ids' => array($registration_id),
		'data' => array('title' => $title, 'msg' => $msg, 'url' => $url, 'img_dialog' => $img_dialog, 'img_noti' => $img_noti, 'p_idx' => $p_idx)
	);
    
    $headers = array(
        "Content-Type:application/json", 
        "charset=UTF-8",
        "Authorization:key=".$auth 
    );
    
    print("<XMP>");
    print(json_encode($data));
    print("\n");
    
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
    curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $result = curl_exec($ch);
    echo "[".$result."]";

    curl_close($ch);

    $result_obj = json_decode($result);
    print_r($result_obj);
	$success = $result_obj->{'success'};

	return $success;
}
mysql_close($CONN); 
*/
echo "\n<br>end..[".date("Y-m-d H:i:s", time())."]\n";
?>