<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<?
echo date("Y-m-d H:i:s")."start<br>";

// last run 2020.08.05 11:10
//exit;

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
      //    $mongo = new Mongo("mongodb://localhost:27017");
      //$mongo = new Mongo("mongodb://intopet:dlsxn**00@app.intopet.co.kr:27017/intopet1" );
      //$mongo = new Mongo("mongodb://intopet:dlsxn**00@app.intopet.co.kr:27017/?authSource=intopet1");
    //$mongo = new MongoClient("mongodb://intopet:dlsxn**00@app.intopet.co.kr:27017/intopet1?authSource=intopet1" );
    $mongo = new MongoClient("mongodb://app.intopet.co.kr:27017" );
    //$mongo = new Mongo("mongodb://intopet:dlsxn**00@app.intopet.co.kr:27017/intopet1" );
    //$testDB = $mongo->intopet1;
    $testDB = $mongo->intopet1;
//$user1 = array( "id" => "lqez", "class" => "surplus" );
//$testDB->user->insert( $user1 );

//$user2 = array( "id" => "trustin", "class" => "superb" );
//$testDB->user->insert( $user2 );

//$user3 = array( "id" => "lono", "class" => "superb" );
//$testDB->user->insert( $user3 );
/*
	$faqs = $testDB->faq->find();
	foreach( $faqs as $faq ){
      print_r( $faq );
    }
    
    exit;
*/
      $i = 0;
      $startnum = $_GET['startnum'];
      //db.getCollection('users').find({"_id":{"$gt":ObjectId("5f06808a0038960348b4092c")}}).count()
//      $users = $testDB->users->find()->skip(($startnum -1))->limit(1000);
      //$query = array("_id"=>array('$gt'=>new MongoId('5f0d3a795aeb2a3c61a43c11'))); //5f06808a0038960348b4092c
      //$users = $testDB->users->find($query)->skip(($startnum -1))->limit(1000);
      //$users = $testDB->users->find()->skip(($startnum -1))->limit(1000);
      $query = array("_id"=>new MongoId('5e193886e3dd195c30a82b32'));//59c626c1f7dcfebc534abc8d  5f06808a0038960348b4092c
      $users = $testDB->users->find($query)->skip(($startnum -1))->limit(1000);
      //$users = $testDB->users->find();

      $pushs = null;
/*      db.users.find({
            "_id": {
                "$gt": ObjectId("5e611f32230f043d4b12eaea")
            }
        }); */
      //$startnum = 301001;  //1000씩
      
?>      <a href="./mongo_user.php?startnum=<?=($startnum + 1000)?>">go_<?=($startnum + 1000)?> 뒤로  </a><br>
        <!--<a href="./mongo_intopet_asis_to_new.php?startnum=<?=($startnum - 1000)?>">go_<?=($startnum - 1000)?> 밑으로</a><br>-->
<? /* if($startnum < 20000){ ?>
      <script>setTimeout("location.href='./mongo_intopet_asis_to_new.php?startnum=<?=($startnum + 1000)?>';", 1000);</script>
<? }  */ ?>
<? if($startnum > 10000){ ?>
      <script>//setTimeout("location.href='./mongo_intopet_asis_to_new.php?startnum=<?=($startnum - 1000)?>';", 1000);</script>
<? }   ?>
<?
      // 0~800 완료 / 300001 ~ 309991
      echo "[".$startnum."]";
	foreach( $users as $user ){
            $i++;
            echo "[[".$user["_id"]." ".$user["nickname"]."]]";//exit;

            //if($i < $startnum) continue;
            //print_r( $user );
            //echo $i." ".$user["nickname"]."";exit;
            //echo var_dump($user["push_notification"])."<br>";
/*
            $pushs = $user["push_notification"];
            for($p = 0; $p < count($pushs); $p++){
                  //echo $pushs[$p]['content']."<br>";
  	          	$query_user_push = " 
				INSERT INTO `intopet`.`asis_users_push` 
				VALUES	('', 	'".$user["_id"]."', 	'".$pushs[$p]['_id']."', 
				      	'".$pushs[$p]['push_datetime']."', 
						'".strFilter($pushs[$p]['content'])."', 
						'".$pushs[$p]['sns_id']."', 
						'".$pushs[$p]['push_create_userid']."', 
						'".strFilter($pushs[$p]['push_title'])."', 
						'".$pushs[$p]['push_type']."'	)";
  		      $result_user_push = mysql_query($query_user_push);
	            if($result_user_push){}else{ echo $query_user_push; exit;}
	            	
	            //if($p > 3) exit;
            }//for pushs
*/

/*          $mate_requested_ids = $user["mate_requested_id"];
            for($m1 = 0; $m1 < count($mate_requested_ids); $m1++){
                  //echo $mate_requested_ids[$m1]['content']."<br>";
  	          	$query_user_m1 = " 
				INSERT INTO `intopet`.`asis_users_mate_requested_id_200805` 
				VALUES	('', 	'".$user["_id"]."', 	'".$mate_requested_ids[$m1]['_id']."', 
						'".strFilter($mate_requested_ids[$m1]['message'])."', 
						'".$mate_requested_ids[$m1]['user_id']."'	)";
  		      $result_user_m1 = mysql_query($query_user_m1);
	            if($result_user_m1){}else{ echo $query_user_m1; exit;}
	            	
                 	//if($m1 > 3) exit;
            }//for requested_ids

            $mate_ids = $user["mate_id"];
            for($m2 = 0; $m2 < count($mate_ids); $m2++){
                  //echo $mate_requested_ids[$m1]['content']."<br>";
                  $query_user_m2 = " 
                        INSERT INTO `intopet`.`asis_users_mate_id_200805` 
                        VALUES	('', 	'".$user["_id"]."', 	'".$mate_ids[$m2]['_id']."', 
                                    '".$mate_ids[$m2]['user_id']."'	)";
                    $result_user_m2 = mysql_query($query_user_m2);
                  if($result_user_m2){}else{ echo $query_user_m2; exit;}
                        
                  //if($m2 > 3) exit;
            }//for mate

            $mate_request_ids = $user["mate_request_id"];
            for($m3 = 0; $m3 < count($mate_request_ids); $m3++){
                  $query_user_m3 = " 
                        INSERT INTO `intopet`.`asis_users_mate_request_id_200805` 
                        VALUES	('', 	'".$user["_id"]."', 	'".$mate_request_ids[$m3]['_id']."', 
                                    '".$mate_request_ids[$m3]['user_id']."',
                                    '".strFilter($mate_request_ids[$m3]['message'])."' )";
                  $result_user_m3 = mysql_query($query_user_m3);
                  if($result_user_m3){}else{ echo $query_user_m3; exit;}
                        
                  //if($m3 > 3) exit;
            }//for requested_ids

            $followed_ids = $user["followed_id"];
            for($m4 = 0; $m4 < count($followed_ids); $m4++){
                  $query_user_m4 = " 
                        INSERT INTO `intopet`.`asis_users_followed_id_200805` 
                        VALUES	('', 	'".$user["_id"]."', 	'".$followed_ids[$m4]."')";
                  $result_user_m4 = mysql_query($query_user_m4);
                  if($result_user_m4){}else{ echo $query_user_m4; exit;}
                        
                  //if($m4 > 3) exit;
            }//for mate

            $following_ids = $user["following_id"];
            for($m5 = 0; $m5 < count($following_ids); $m5++){
                  $query_user_m5 = " 
                        INSERT INTO `intopet`.`asis_users_following_id_200805` 
                        VALUES	('', 	'".$user["_id"]."', 	'".$following_ids[$m5]."')";
                  $result_user_m5 = mysql_query($query_user_m5);
                  if($result_user_m5){}else{ echo $query_user_m5; exit;}
                        
                  //if($m5 > 3) exit;
            }//for mate

            $clipsns_ids = $user["clipsns_id"];
            for($m6 = 0; $m6 < count($clipsns_ids); $m6++){
                  $query_user_m6 = " 
                        INSERT INTO `intopet`.`asis_users_clipsns_id_200805` 
                        VALUES	('', 	'".$user["_id"]."', 	'".$clipsns_ids[$m6]."')";
                  $result_user_m6 = mysql_query($query_user_m6);
                  if($result_user_m6){}else{ echo $query_user_m6; exit;}
                        
                  //if($m6 > 3) exit;
            }//for mate
*/
/*            $query_user = " 
            INSERT INTO `intopet`.`asis_users_200805` 
                  VALUES
                  ('', 
                  '".$user["_id"]."', 
                  '".strFilter($user["nickname"])."', 
                  '".$user["email"]."', 
                  '".$user["password"]."', 
                  '".$user["sign_type"]."', 
                  '".$user["phonenumber"]."', 
                  '".$user["kakaoid"]."', 
                  '".$user["create_datetime"]."', 
                  '".$user["active_checkvalue"]."', 
                  '".$user["setting_push04"]."', 
                  '".$user["setting_push03"]."', 
                  '".$user["setting_push02"]."', 
                  '".$user["setting_push01"]."', 
                  '".$user["soundfilename"]."', 
                  '".$user["push_number"]."', 
                  '".$user["active"]."', 
                  '".$user["update_datetime"]."', 
                  '".$user["nickname_search_allow"]."', 
                  '".$user["auth_type"]."', 
                  '".$user["_v"]."', 
                  '".$user["push_token"]."', 
                  '".$user["small_image_url"]."', 
                  '".$user["big_image_url"]."', 
                  '".strFilter($user["address"])."', 
                  '".$user["sex"]."', 
                  '".$user["birthday"]."', 
                  '".strFilter($user["introduce"])."', 
                  '".$user["sns_read_total_startdatetime"]."', 
                  '".$user["sns_read_event_startdatetime"]."', 
                  '".$user["sns_read_mate_follow_startdatetime"]."', 
                  '".$user["setting_push05"]."', 
                  '".$user["setting_push06"]."', 
                  '".$user["setting_push07"]."', 
                  '".$user["setting_push08"]."', 
                  '".$user["setting_push09"]."', 
                  '".$user["setting_push10"]."', 
                  '".$user["sns_read_petcast_startdatetime"]."', 
                  '".$user["sns_read_notification_startdatetime"]."', 
                  '".$user["setting_push11"]."', 
                  '".$user["sns_read_category_startdatetime"]."', 
                  '".$user["sns_read_search_startdatetime"]."', 
                  '".$user["user_type"]."', 
                  '".$user["webCookie"]."', 
                  '".strFilter($user["username"])."',
                  '".$user["hospital_auth_phonenumber"]."', 
                  '".$user["hospital_coCode"]."', 
                  '".$user["hospital_clientCode"]."', 
                  '".$user["push_night_silent_owl_mode"]."', 
                  '".$user["push_night_silent_end"]."', 
                  '".$user["push_nignt_silent_start"]."', 
                  '".$user["push_night_silent"]."', 
                  '".$user["is_init_phonenumber"]."', 
                  '".$user["sns_read_thema_startdatetime"]."', 
                  '".$user["active_validity_period"]."', 
                  '".$user["update_nickname_number"]."'
                              )";
*/
            if(strlen($user["birthday"]) > 7) $birthDay  = substr($user["birthday"],6,4);
            if(strlen($user["birthday"]) > 5) $birthMon  = substr($user["birthday"],4,2);
            if(strlen($user["birthday"]) > 3) $birthYear = substr($user["birthday"],0,4);
            if($user["sex"]=="f") $gender = "W"; else $gender = "M";
            $query_user = " 
                INSERT INTO `intopet`.`itapp_user` 
      VALUES
      ('', 
      '".$user["email"]."', 
      '".strFilter($user["username"])."',
      null,
      '".$user["phonenumber"]."', 
      FROM_UNIXTIME(SUBSTRING('".$user["create_datetime"]."',12),'%Y-%m-%d %H:%i:%s'),
      'system_".date("Y-m-d H:i:s")."','','N',null,
      null, null, null, 
      '".strFilter($user["nickname"])."', 
      '".strFilter($user["introduce"])."', 
      '".$gender."', 
      '".$birthYear."', '".$birthMon."', '".$birthDay."', 
      'N',null,null, null,
      '".$user["big_image_url"]."', 
      0, null, null, 
      'n','n','n','n','n','n','n',
      null,null,null,null,null,null,null,null,null,null,
      '".$user["_id"]."', 
      '".$user["password"]."', 
      '".$user["sign_type"]."', 
      '".$user["kakaoid"]."', 
      '".$user["setting_push01"]."', 
      '".$user["setting_push02"]."', 
      '".$user["setting_push03"]."', 
      '".$user["setting_push04"]."', 
      '".$user["setting_push05"]."', 
      '".$user["setting_push06"]."', 
      '".$user["setting_push07"]."', 
      '".$user["setting_push08"]."', 
      '".$user["setting_push09"]."', 
      '".$user["setting_push10"]."', 
      '".$user["setting_push11"]."', 
      '".$user["phonenumber"]."', 
      '','',
      '".$user["push_night_silent_owl_mode"]."', 
      '".$user["push_night_silent_end"]."', 
      '".$user["push_nignt_silent_start"]."', 
      '".$user["push_night_silent"]."', 
      '".$user["push_token"]."', 
      null, null, null, null,
      null, null, null, null, null, null, null, null, null, null, 
      '',null, null, null, null, null, null, null,
      '".$user["hospital_auth_phonenumber"]."'
                  )";

            $result_user = mysql_query($query_user);
            if($result_user){}else{ echo $query_user; exit;}

            echo "user_idx:".mysql_insert_id()."<br>";
            //if($i > ($startnum+499)){
            //      echo date("Y-m-d H:i:s")."end<br>";
            //      exit;
            //}
	}
	
      echo date("Y-m-d H:i:s")."end<br>";
	exit;

      //$mongo = new MongoClient("mongodb://intovet_operator:into90*(@211.253.25.44:27017/intovet" );
      //$mongo = new MongoClient(  "mongodb://localuser:localuser@localhost/localmongo" );
      $mongo = new Mongo("mongodb://localhost:27017" );
      //print_r($mongo->listDatabases());
      $db = $mongo->localmongo; //db select
      //$collections = $db->users;


      
      $collections = $db->listCollections();
      foreach ($collections as $collection) {
          echo "amount of documents in $collection: ";
          echo $collection->count(), "<br>";
      }
      exit;
      //echo "<br><br>";
      //echo $_GET[gbn];exit;
      
      if($_GET['gbn']=="hospital"){
          $collection = $mongo->intovet->hospitals;
          $query = array( 'coCode' => 123);
          $cursor = $collection->find($query);
          //echo var_dump($cursor)."<br />";
         foreach ($cursor as $document) {
               var_dump($document);
             //echo $document["name"]." ".$document["address1"]."<br />";
         }
                  
      }
      $collection = $mongo->admin->users;
      $cursor = $collection->find();

      foreach ($cursor as $document) {
          echo $document["nickname"] . "<br />";
      }
      
  } catch (Exception $e) {
  	echo "Exception:<br>";
        echo $e->getMessage();
  }
  
function strFilter($str){
	return str_replace("'", "`", iconv("utf-8","euc-kr",$str));
}
?>