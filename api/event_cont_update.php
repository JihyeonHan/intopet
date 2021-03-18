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

$query = "select * from itapp_event where start_date is null and del_yn = 'N' ";


$result = mysql_query($query,$CONN);
while($result && $row = mysql_fetch_array($result)){
    
    $query2 = "UPDATE itapp_event SET
                                event_desc = '".iconv("utf-8", "cp949", urldecode($row['event_desc']))."'
                    WHERE  idx          = '".$row['idx']."' ";
    $result2 = mysql_query($query2);

   echo $row['idx']."----------------------------<br/>";
   echo $row['event_title']."<br/>";
   echo "----------------------------<br/>";
//    echo $query2;
//    echo $row['idx'].$result2."<br>";
}


?>
