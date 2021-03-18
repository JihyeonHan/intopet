<?

exit;
include_once($_SERVER['DOCUMENT_ROOT']."/app/php/common/include/session.php"   );

$query = "SELECT * FROM itapp_cont WHERE del_yn = 'N' AND old_create_id IS NULL ORDER BY reg_datetime DESC LIMIT 0, 300";
$result = mysql_query($query);
while($result && $row = mysql_fetch_assoc($result)){
    if( urldecode($row['title']) == $row['title_org'] ){
        echo "same:".urldecode($row['title'])."<br>";
    }else{
        echo "not ------------:".urldecode($row['title'])."<br>";
        $update_query = "UPDATE itapp_cont SET 
                            title_org =  '".iconv("utf-8","cp949",fFilter(urldecode($row['title'])))."',
                            cont_org  =  '".iconv("utf-8","cp949",fFilter(urldecode($row['cont'])))."' 
                        where idx = '".$row['idx']."'";
        mysql_query($update_query);
        echo $update_query;
        //exit;
    }
}

/*
$query = "SELECT * FROM itapp_cont WHERE menu_gbn = 'story' AND del_yn = 'N' ORDER BY reg_datetime DESC LIMIT 0, 30";
$result = mysql_query($query);
while($result && $row = mysql_fetch_assoc($result)){
    $p1 = strpos($row[cont],"<img src=");
    if($p1 !== false && $p1 >= 0) $p2 = strpos($row[cont],"\"", $p1+10);
    if($p1 !== false && $p1 >= 0 && $p2 >= 0) $prod_url = substr($row[cont], $p1 + 10, $p2-$p1-10);

    echo $prod_url;
    echo "<br/>";
    echo basename($prod_url);
    echo "<br/>";

    $update_query = "UPDATE itapp_cont SET list_thumb =  '2020/08/".basename($prod_url)."' where idx = '".$row[idx]."'";
    mysql_query($update_query);
}
*/
function fFilter($str){
    //한글,알파벳,숫자만 유지
    //preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}0-9a-z]/i", "", $str);
    //return preg_replace("/[#\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $str);
    $str = removeEmoji($str);
    return preg_replace("/\xF0[\x90-\xBF][\x80-\xBF]{2} | [\xF1-\xF3][\x80-\xBF]{3} | \xF4[\x80-\x8F][\x80-\xBF]{2}/", "", $str);

}
function removeEmoji($text) {
    $clean_text = "";
    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);
    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);
    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);
    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);
    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    // Match Flags
    $regexDingbats = '/[\x{1F1E6}-\x{1F1FF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    // Others
    $regexDingbats = '/[\x{1F910}-\x{1F95E}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    $regexDingbats = '/[\x{1F980}-\x{1F991}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    $regexDingbats = '/[\x{1F9C0}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    $regexDingbats = '/[\x{1F9F9}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);
    return $clean_text;
}
?>