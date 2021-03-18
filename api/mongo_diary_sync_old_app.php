<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<?
echo date("Y-m-d H:i:s")."start sns<br>";

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

if($_GET['user_id']){ 
      echo "1 ";
      $user_id = $_GET[user_id];
}else{
      echo "2 ";
      //exit; 
      $query_user0 = "SELECT user_id, last_login_datetime FROM itapp_user u
      WHERE u.del_yn = 'N'
        AND user_id NOT IN(
            SELECT user_id FROM itapp_diary_sync WHERE diary_sync_datetime IS NOT NULL)
      ORDER BY last_login_datetime DESC, reg_datetime DESC
      LIMIT 1 ";
      $result_user0 = mysql_query($query_user0);
      $row_user0 = mysql_fetch_assoc($result_user0);
      $user_id = $row_user0[user_id];
      echo $row_user0[last_login_datetime]." ";
}
echo $user_id;
//exit;      


$query_sync = "SELECT count(*) cnt FROM itapp_diary_sync WHERE user_id = '".$user_id."' AND diary_sync_datetime is not null ";
$result_sync = mysql_query($query_sync);
$row_sync = mysql_fetch_assoc($result_sync);
if($row_sync[cnt] > 0){
    echo "already sync";
    exit;
}

$user_idx = 0;
$query_user = "SELECT * FROM itapp_user WHERE user_id = '".$user_id."' AND del_yn = 'N' ";
$result_user = mysql_query($query_user);
$row_user = mysql_fetch_assoc($result_user);

$user_idx = $row_user[idx];
$user_id  = $row_user[user_id];
$old_user_id  = $row_user[old_id];
//echo "user_idx:".$user_idx."<br>";

if($user_idx == 0) {
    echo "user not exist owner_userid: ".$user_id."<br>";
    exit;
}
if($old_user_id == "") {
      echo " old id not exist owner_userid: ".$user_id."<br>";

      $query_end_update = " insert into itapp_diary_sync SET user_id = '".$user_id."', diary_sync_datetime = now() ";
      $result_end_update = mysql_query($query_end_update);
      if($result_end_update){}else{ echo $query_end_update; exit;}

      if($result_end_update){ 
            echo "";
            echo "<script>location.reload();</script>";
      }else{ echo "update fail";exit;}

      exit;
}
  
try {
      
      $mongo = new MongoClient("mongodb://app.intopet.co.kr:27017" );
      $testDB = $mongo->intopet1;

      $i = 0;
      $startnum = $_GET[startnum];
      //echo "[".$startnum."]";
      //$snss = $testDB->sns->find();
      //$query = array("_id"=>array('$gt'=>new MongoId('5f091b716a3759fd5603d161'))); //200806 실행 //5f091b716a3759fd5603d161
      //$query = array("_id"=>array('$lt'=>new MongoId('5e255e45fee977cb084d0a0b')));  //작업중이던 부분
      $query = array("owner_userid"=>new MongoId($old_user_id));
      //$query = array("publish_datetime"=>array('$gt'=>new MongoDate(strtotime("2020-01-01T00:00:00.000Z"))), "publish_datetime"=>array('$lt'=>new MongoDate(strtotime("2020-08-01T00:00:00.000Z"))));
      //$query = array("create_datetime"=>array('$gt'=>new MongoDate(strtotime("2020-01-01T00:00:00.000Z"))), "create_datetime"=>array('$lt'=>new MongoDate(strtotime("2020-08-01T00:00:00.000Z"))));
//      $query = array("update_datetime"=>array('$gt'=>new MongoDate(strtotime("2020-07-08T00:00:00.000Z"))) );
      //$snss = $testDB->diaries->find($query)->skip(($startnum -1))->limit(1000);
      $diarys = $testDB->diaries->find($query);//->skip(($startnum -1))->limit(1000);
?>        <!--<a href="./mongo_diary.php?startnum=<?=($startnum + 1000)?>">go_<?=($startnum + 1000)?> 뒤로  </a> <br>
      2018년  건<br>
      2019년  건<br>
      2020년 51,828 건
      전체 224,014 건
-->
      <!--<a href="./mongo_sns.php?startnum=<?=($startnum - 1000)?>">go_<?=($startnum - 1000)?> 밑으로</a><br>-->
<?    /* if($startnum < 20000){ ?>
            <script>setTimeout("location.href='./mongo_intopet_asis_to_new.php?startnum=<?=($startnum + 1000)?>';", 1000);</script>
<?    }  */ ?>
<?    if($startnum < 224000){ ?>
            <script>//setTimeout("location.href='./mongo_diary.php?startnum=<?=($startnum + 1000)?>';", 1000);</script>
<?    } 
      
      $last_cont_datetime = "";
      $inserted_cnt = 0;
      foreach( $diarys as $diary ){
            $i++;
            //print_r($diary);
            //exit;
            
            //if($i < $startnum) continue;
            //echo "ok";
            //if($i > 2) exit;
            $img1 = '';
            $img2 = '';
            $img3 = '';
            $img4 = '';
            $img5 = '';


            $query_exists_cont = "SELECT * FROM itapp_schedule WHERE user_idx = '".$user_idx."' AND old_id = '".$diary["_id"]."' ";
            $result_exists_cont = mysql_query($query_exists_cont);
            $row_exists_cont = mysql_fetch_assoc($result_exists_cont);
            $rows_exists_cont = mysql_num_rows($result_exists_cont);
            if($rows_exists_cont) {
                echo "diasy exist ".$diary["_id"]." ";

                $imgs = $diary["image_urls"];
                if(count($imgs) > 0){
                    //echo "<br>img founded:".count($imgs);
                    //print_r($imgs);//exit;
                    for($r = 0; $r < count($imgs); $r++){
                        if($r==0) $img1 = $imgs[$r];
                        else if($r==1) $img2 = $imgs[$r];
                        else if($r==2) $img3 = $imgs[$r];
                        else if($r==3) $img4 = $imgs[$r];
                        else if($r==4) $img5 = $imgs[$r];
                        else {
                            echo "image count over";
                            exit;
                        }
                        //echo "<br>".$r.":".$imgs[$r];
                    }
//                    $query_img_update = " update itapp_schedule SET cont = '".strFilter(urlencode($diary["memo"]))."', img1 = '".$img1."',img2 = '".$img2."',img3 = '".$img3."',img4= '".$img4."',img5 = '".$img5."' where user_idx = '".$user_idx."' AND old_id = '".$diary["_id"]."' ";
                    //echo $query_img_update ;
//                    $result_img_update = mysql_query($query_img_update);
                    //exit;
                }

                $pets = $diary["pets"];
                $petCode = "";
                $pet_idx = "";
                //print_r($pets);
                if(count($pets) == 1){
                    //echo "<br>pet founded:".count($pets);
                    //print_r($imgs);//exit;
                    for($p = 0; $p < count($pets); $p++){
                        if($p==0) $petCode = $pets[$p]['petCode'];
                        //echo "<br>".$r.":".$imgs[$r];
                    }
                    $query_pet = "SELECT idx FROM itapp_pet WHERE user_idx = '".$user_idx."' AND old_id = '".$petCode."' ";
                    $result_pet = mysql_query($query_pet);
                    $row_pet = mysql_fetch_assoc($result_pet);
                    $pet_idx = $row_pet[idx];
                    
                    //echo " 1_petCode:".$petCode." petIdx:".$pet_idx;
                    //exit;
                }
                //echo " 2_petCode:".$petCode." petIdx:".$pet_idx;
                //exit;

                $cg = 0;
                if($diary["type_name"]=="식사") $cg = 2;
                if($diary["type_name"]=="간식") $cg = 3;
                if($diary["type_name"]=="접종") $cg = 7;
                if($diary["type_name"]=="목욕") $cg = 4;
                if($diary["type_name"]=="미용") $cg = 5;
                if($diary["type_name"]=="기념") $cg = 11;
                if($diary["type_name"]=="메모") $cg = 1;
                if($diary["type_name"]=="출산") $cg = 9;
                if($diary["type_name"]=="진료") $cg = 6;
                if($diary["type_name"]=="생일") $cg = 10;
                if($diary["type_name"]=="배변") $cg = 8;
    
                if($diary["type_name"]=="투약") $cg = 7;
    
                if($cg == 0) {
                      echo "category not found ".$diary["type_name"]." owner_userid: ".$diary["owner_userid"]."<br>";
                      exit;
                }
                //if($cg != 7) {
                //    echo "not 7... pass";
                //    continue;
                //}

                $query_img_update = " update itapp_schedule SET cg = '".$cg."', pet_idx = '".$pet_idx."', title = '".strFilter(urlencode($diary["info1"]))."', cont = '".strFilter(urlencode($diary["memo"]))."', img1 = '".$img1."',img2 = '".$img2."',img3 = '".$img3."',img4= '".$img4."',img5 = '".$img5."' where user_idx = '".$user_idx."' AND old_id = '".$diary["_id"]."' ";
                //echo $query_img_update ;//exit;
                $result_img_update = mysql_query($query_img_update);
                echo "updated:".$diary["_id"]."<br>";
                //exit;
                continue;
            }
            //echo "11-----------------------<br><br><br><br><br><br>";
            //exit;

            /* //매칭할 수 없음
            $pets = $diary["pets"];
            for($r = 0; $r < count($pets); $r++){
                  echo $pets[$r]['petCode']."<br>";
                  echo $pets[$r]['_id']."<br>";
            }//for pushs
            */

            $cg = 0;
            if($diary["type_name"]=="식사") $cg = 2;
            if($diary["type_name"]=="간식") $cg = 3;
            if($diary["type_name"]=="접종") $cg = 7;
            if($diary["type_name"]=="목욕") $cg = 4;
            if($diary["type_name"]=="미용") $cg = 5;
            if($diary["type_name"]=="기념") $cg = 11;
            if($diary["type_name"]=="메모") $cg = 1;
            if($diary["type_name"]=="출산") $cg = 9;
            if($diary["type_name"]=="진료") $cg = 6;
            if($diary["type_name"]=="생일") $cg = 10;
            if($diary["type_name"]=="배변") $cg = 8;

            if($diary["type_name"]=="투약") $cg = 7;

            if($cg == 0) {
                  echo "category not found ".$diary["type_name"]." owner_userid: ".$diary["owner_userid"]."<br>";
                  exit;
            }
            //echo "cg:".$cg;
            //echo "okok";
            //exit;

            if(substr($diary["start_datetime"],0,8) != ""){
                $start_date = substr($diary["start_datetime"],0,4)."-".substr($diary["start_datetime"],4,2)."-".substr($diary["start_datetime"],6,2);
            }
            if(substr($diary["end_datetime"],0,8) != ""){
                $end_date = substr($diary["end_datetime"],0,4)."-".substr($diary["end_datetime"],4,2)."-".substr($diary["end_datetime"],6,2);
            }
  
            $query_diary = " 
            INSERT INTO `intopet`.`itapp_schedule` 
                  VALUES
                  ('', '".$user_idx."', null, '".$cg."', '".strFilter(urlencode($diary["info1"]))."',
                  '".strFilter(urlencode($diary["memo"]))."', 
                  null, null, null, 
                  '".$start_date."','".$end_date."', 'pink',
                  FROM_UNIXTIME(SUBSTRING('".$diary["create_datetime"]."',12),'%Y-%m-%d %H:%i:%s'),
                  '".$user_id."', '', 'N', 
                  '".strFilter(urlencode($diary["type_name"]))."',
                  '".strFilter(urlencode($diary["position_name"]))."',
                  '".strFilter(urlencode($diary["position_address"]))."',
                  '".$diary["position_latitude"]."',
                  '".$diary["position_longitude"]."',
                  '".$diary["_id"]."', 
                  null,null,null,null,null,null,null,null,
                  null,null,null,null,null,
                  null,null,null,null )";
            //echo $query_diary;
            echo "inserted ";
            //exit;
            $result_diary = mysql_query($query_diary);
            if($result_diary){}else{ echo $query_diary; exit;}
            $inserted_cnt++;
            
            //exit;

            //$last_cont_datetime = $sns["create_datetime"];
      } //foreach

      $query_end_update = " insert into itapp_diary_sync SET user_id = '".$user_id."', diary_sync_datetime = now() ";
    //echo $query_end_update;exit;
      $result_end_update = mysql_query($query_end_update);
      if($result_end_update){}else{ echo $query_end_update; exit;}

      $query_exists_cont = "SELECT count(*) cnt FROM itapp_schedule WHERE user_idx = '".$user_idx."' AND old_id is not null ";
      $result_exists_cont = mysql_query($query_exists_cont);
      $row_exists_cont = mysql_fetch_assoc($result_exists_cont);
      echo "<br><br>read:".$i." db cnt:".$row_exists_cont[cnt];
      

      //echo "[".$last_cont_datetime."]<br>";
	//echo "[".substr($last_cont_datetime,11)."]<br>";
	//echo "last_cont_datetime:".gmdate("Y-m-d\TH:i:s\Z", substr($last_cont_datetime,11))."<br>";
      //echo date("Y-m-d H:i:s")."end<br>";
      echo "<br><br>selected[".$i."] inserted[".$inserted_cnt."]";

      if($result_end_update){ 
            echo "update ok";
            echo "<script>location.reload();</script>";
      }else{ echo "update fail";exit;}

	//exit;

} catch (Exception $e) {
      echo "eee";
      echo $e->getMessage();
}
  
function strFilter($str){
	return str_replace("'", "`", iconv("utf-8","euc-kr",$str));
}
echo "<br>".date("Y-m-d H:i:s")."end <br>";

?>