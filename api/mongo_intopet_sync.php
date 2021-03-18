<?
//mongo db I/F
if($_GET['gbn'] != "") $gbn = $_GET['gbn']; else if($_POST['gbn'] != "") $gbn = $_POST['gbn']; else $gbn = "";
if($_GET['old_id'] != "") $old_id = $_GET['old_id']; else if($_POST['old_id'] != "") $old_id = $_POST['old_id']; else $old_id = "";
if($_GET['reg_id'] != "") $reg_id = $_GET['reg_id']; else if($_POST['reg_id'] != "") $reg_id = $_POST['reg_id']; else $reg_id = "";
//$gbn = "users";//test
//$old_id = "56c33e2e13c29a691eb27387";//test
//echo "111";

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

try {
    
    if($gbn=="users"){
        $mongo = new MongoClient("mongodb://intopet:dlsxn**00@app.intopet.co.kr:27017/intopet1" );
        $db = $mongo->intopet1; //db select
        $collection = $db->users;

        $query = array("_id"=>new MongoId($old_id));
        $cursor = $collection->find($query);

        //$json_arr = array();
        foreach ($cursor as $document) {
            //var_dump($document);
            //echo $document["email"]."<br>";
            //echo $document["clipsns_id"]."<br />";

            $query_clip_ids = "";
            $clipsns_ids = $document["clipsns_id"];
            for($m1 = 0; $m1 < count($clipsns_ids); $m1++){
                if($m1 > 0) $query_clip_ids .= ",";
                $query_clip_ids .= "'".$clipsns_ids[$m1]."'";
                //echo $clipsns_ids[$m1]."<br>";
            }//for 
            //echo $query_clip_ids;

            $query_clip_m1 = " INSERT INTO `intopet`.`itapp_cont_save` (idx, cont_idx, del_yn, reg_id, reg_ip, reg_datetime)
                                SELECT '', idx cont_idx, 'N', '".$reg_id."', '', NOW() FROM itapp_cont
                                WHERE old_sns_id IN(".$query_clip_ids.") AND del_yn = 'N'
                                  AND idx NOT IN( SELECT cont_idx FROM itapp_cont_save
                                                  WHERE reg_id = '".$reg_id."' ) ";
            //echo $query_clip_m1;exit;
            $result_clip_m1 = mysql_query($query_clip_m1);
            //if($result_clip_m1){}else{ echo $query_clip_m1; exit;}
            //array_push($json_arr, $document);
        }
        //print_r(json_encode($json_arr));
        echo "[OK]";
                  
    }

} catch (Exception $e) {
  	echo "eee";
        echo $e->getMessage();
}
?>