<?

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

header('Content-Type: text/html; charset=UTF-8');

$query = "select * from itapp_cont where del_yn = 'N' AND idx >= '143891'";


$result = mysql_query($query,$CONN);
while($result && $row = mysql_fetch_array($result)){
    

    if(urlencode(urldecode($row['title'])) === $row['title']){
        $title = (addslashes(iconv("utf-8", "cp949", urldecode($row['title']))));
    } else {
        $title = addslashes(iconv("utf-8", "cp949", $row['title']));
    }
    if(urlencode(urldecode($row['cont'])) === $row['cont']){
        $cont = (addslashes(iconv("utf-8", "cp949", urldecode($row['cont']))));
    } else{
        $cont = addslashes(iconv("utf-8", "cp949", $row['cont']));
    }
    if(urlencode(urldecode($row['keyword'])) === $row['keyword']){
        $keyword = (addslashes(iconv("utf-8", "cp949", urldecode($row['keyword']))));
    } else {
        $keyword = addslashes(iconv("utf-8", "cp949", $row['keyword']));
    }
    
    $query2 = "UPDATE itapp_cont SET
                                title_org   = '".$title."',
                                cont_org    = '".$cont."',
                                keyword_org = '".$keyword."'
                    WHERE  idx          = '".$row['idx']."' ";
    $result2 = mysql_query($query2);

   echo "----------------------------<br/>";
   echo $row['title']."<br/>";
//    echo urlencode($row[title])."<br/>";
   echo urldecode($row[title])."<br/>";
   echo urldecode(addslashes(iconv("cp949", "utf-8", iconv("utf-8", "cp949", $row['title']))))."<br/>";
   echo "----------------------------<br/>";
//    echo $query2;
//    echo $row['idx'].$result2."<br>";
}


?>
