<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<?
//echo strtotime("2020-07-30 12:00:00");
//exit;

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

//mongo db I/F
  try {
      //$mongo = new Mongo("mongodb://localhost:27017");
      //$testDB = $mongo->localmongo;
      
      $mongo = new MongoClient("mongodb://app.intopet.co.kr:27017" );
      $testDB = $mongo->intopet1;

      //db.getCollection('sns').find({"publish_datetime":{"$gt": ISODate("2020-01-01T00:00:00.000Z"), "$lt": ISODate("2020-08-01T00:00:00.000Z")}}).count()
      //9464 건
      $i = 0;
      //$startnum = 131001; //500씩
      // 0~800 X / 120001 ~ 121136
      $startnum = $_GET['startnum'];
      echo "[".$startnum."]";
      //$snss = $testDB->sns->find();
      
      //$query = array("_id"=>array('$gt'=>new MongoId('5f2bcfc2a21841652753519b'))); // 2020-08-07
     
      //$query = array("_id"=>new MongoId('577e2ada4e8df67c22a5d157')); //한건
      //ObjectId("")
      //ObjectId("")
      //ObjectId("")
      //$query = array("_id"=>array('$lt'=>new MongoId('5f2a1767c660eb2613a1f1e8')),"_id"=>array('$gt'=>new MongoId('5e255e45fee977cb084d0a0b')));

      // $query = array("_id"=>array('$gt'=>new MongoId('5f2a1767c660eb2613a1f1e8'))); //5f06752372c29d4d3becbf5c, 5f0d541040002f647e2375e5
      //$query = array("_id"=>array('$lt'=>new MongoId('5e255e45fee977cb084d0a0b')));  //작업중이던 부분
      //$query = array("publish_datetime"=>array('$gt'=>new MongoDate(strtotime("2020-01-01T00:00:00.000Z"))), "publish_datetime"=>array('$lt'=>new MongoDate(strtotime("2020-08-01T00:00:00.000Z"))));


      if($_GET['old_id']==""){
          //echo "no old id";
          //exit;
          if($_GET['gbn']==""){ //last_login_datetime >  '20200808000000' AND
            $query_mig_user = "SELECT * FROM itapp_user WHERE old_id is not null AND del_yn = 'N' AND old_sns_mig_check_datetime is null AND last_login_datetime is not null ORDER BY last_login_datetime desc LIMIT 1 ";
          //}else if($_GET[gbn]=="3"){
          //  $query_mig_user = "SELECT * FROM itapp_user WHERE last_login_datetime >  '20200808000000' AND last_login_datetime <= '20200808170000' AND old_id is not null AND del_yn = 'N' AND old_sns_mig_check_datetime is null order by last_login_datetime desc LIMIT 1 ";
          //}else if($_GET[gbn]=="32"){
          //  $query_mig_user = "SELECT * FROM itapp_user WHERE last_login_datetime >  '20200807120000' AND last_login_datetime <= '20200807180000' AND old_id is not null AND del_yn = 'N' AND old_sns_mig_check_datetime is null order by last_login_datetime desc LIMIT 1 ";
          //}else if($_GET[gbn]=="31"){
          //  $query_mig_user = "SELECT * FROM itapp_user WHERE last_login_datetime >  '20200807000000' AND last_login_datetime <= '20200807120000' AND old_id is not null AND del_yn = 'N' AND old_sns_mig_check_datetime is null order by last_login_datetime desc LIMIT 1 ";
          //}else if($_GET[gbn]=="4"){
          //  $query_mig_user = "SELECT * FROM itapp_user WHERE last_login_datetime >  '20200806000000' AND last_login_datetime <= '20200807000000' AND old_id is not null AND del_yn = 'N' AND old_sns_mig_check_datetime is null order by last_login_datetime desc LIMIT 1 ";
          //}else if($_GET[gbn]=="5"){
          //  $query_mig_user = "SELECT * FROM itapp_user WHERE last_login_datetime >  '20200805000000' AND last_login_datetime <= '20200806000000' AND old_id is not null AND del_yn = 'N' AND old_sns_mig_check_datetime is null order by last_login_datetime desc LIMIT 1 ";
          }else if($_GET['gbn']=="1"){
                exit;//$query_mig_user = "SELECT * FROM itapp_user WHERE idx >  300000 and old_id is not null AND del_yn = 'N' AND old_sns_mig_check_datetime is null AND last_login_datetime IS NULL and user_id <> '' order by idx desc LIMIT 1 ";
          }else if($_GET['gbn']=="2"){
                exit;//$query_mig_user = "SELECT * FROM itapp_user WHERE idx <= 300000 and old_id is not null AND del_yn = 'N' AND old_sns_mig_check_datetime is null AND last_login_datetime IS NULL and user_id <> '' order by idx desc LIMIT 1 ";
          }
          $result_mig_user = mysql_query($query_mig_user);
          $row_mig_user = mysql_fetch_assoc($result_mig_user);

          $cnt_mig_user = mysql_num_rows($result_mig_user);
          if($cnt_mig_user) {
            $go_old_id = $row_mig_user['old_id'];
          }else{
              echo "mig check error".date("Y-m-d H:i:s")."<br>";
              if($_GET['gbn']==""){
              ?><script>setTimeout("location.href='./mongo_sns.php?startnum=1&gbn=<?=$_GET['gbn']?>';", 1000);</script><?
              }
              exit;
          }
          $query = array("create_id"=> new MongoId($go_old_id)
          ,                       "publish_datetime"=>array('$gt'=>new MongoDate(strtotime("2016-01-01T00:00:00Z")),'$lt'=>new MongoDate(strtotime("2020-09-01T00:00:00Z")))
         );

      }else if($_GET['old_id']=="no"){
          //$start_date = "2019-11-21T00:00:00Z";
          //$end_date   = "2019-12-01T00:00:00Z";
          $start_date = $_GET['start_date'];
          $end_date   = $_GET['end_date'];
          if($start_date=="" || $end_date==""){
              echo "start_date or end_date is null";
              exit;
          }
          $start_date = $start_date."T00:00:00Z";
          $end_date   = $end_date."T00:00:00Z";
          $query = array("publish_datetime"=>array('$gt'=>new MongoDate(strtotime($start_date)),'$lt'=>new MongoDate(strtotime($end_date))) );
    }else{
        $go_old_id = $_GET['old_id'];
        $query = array("_id"=>new MongoId($go_old_id)); //한건
      }
      echo "last_login_datetime [".$row_mig_user['last_login_datetime']." ".substr($row_mig_user['last_login_datetime'],0,8)." ".substr($row_mig_user['last_login_datetime'],8)."]--- go_old_id --- ".$go_old_id." ".$row_mig_user['idx']." ".$row_mig_user['user_id']."<br>";
      //$query = array("create_id"=> new MongoId("5b18fbd87fa5285259cf258e")
      //              ,                       "publish_datetime"=>array('$gt'=>new MongoDate(strtotime("2016-01-01T00:00:00Z")),'$lt'=>new MongoDate(strtotime("2020-01-01T00:00:00Z")))
      //              );
     //$query = array("create_id"=> new MongoId("577e29814e8df67c22a5d0ea")
      //               ,                       "publish_datetime"=>array('$gt'=>new MongoDate(strtotime("2016-01-01T00:00:00Z")),'$lt'=>new MongoDate(strtotime("2016-08-09T00:00:00Z")))
      //);

      //$query = array("create_id"=> new MongoId("57578ed3096686f61cd9c0eb"));
        if($_GET['old_id']!="" && $_GET['old_id']!="no"){
            $snss = $testDB->sns->find($query);
        }else{
            //$snss = $testDB->sns->find($query)->skip(($startnum -1))->limit(1000);
            $snss = $testDB->sns->find($query)->skip(($startnum -1));
        }
      //5e255e45fee977cb084d0a0b 이하 118691 건
?>        <a href="./mongo_sns.php?startnum=<?=($startnum + 1000)?>">go_<?=($startnum + 1000)?> 뒤로  </a><br>
<!--      2016년 33,477 건<br>
      2017년 37,665 건<br>
      2018년 26,926 건<br>
      2019년 19,829 건<br>
      2020년 9,464 건
-->
      <!--<a href="./mongo_sns.php?startnum=<?=($startnum - 1000)?>">go_<?=($startnum - 1000)?> 밑으로</a><br>-->
<? /* if($startnum < 20000){ ?>
    <script>setTimeout("location.href='./mongo_intopet_asis_to_new.php?startnum=<?=($startnum + 1000)?>';", 1000);</script>
<? }  */ ?>
<? if($startnum < 33000){ ?>
    <script>//setTimeout("location.href='./mongo_sns.php?startnum=<?=($startnum + 1000)?>';", 1000);</script>
<? } 
      $last_cont_datetime = "";
      $arr_insert_cont = array();
      $arr_insert_reply1 = array();
      $arr_insert_reply2 = array();
      //echo date("Y-m-d H:i:s")." 1<br>";
    foreach( $snss as $sns ){
            $i++;
            //if($i < $startnum) continue;
            echo date("Y-m-d H:i:s")." ".$i." ".$sns["_id"]."<br>";

            $query_exists_cont = "SELECT * FROM itapp_cont WHERE old_sns_id = '".$sns["_id"]."' AND del_yn = 'N'";
            $result_exists_cont = mysql_query($query_exists_cont);
            $row_exists_cont = mysql_fetch_assoc($result_exists_cont);
            $rows_exists_cont = mysql_num_rows($result_exists_cont);
            if($rows_exists_cont) {
                echo "cont exist ".$sns["_id"]."<br>";
                continue;
            }
            //echo date("Y-m-d H:i:s")." 2<br>";
            //exit;
            //echo "gogo";            continue;

            $replys = $sns["reply"];
            for($r = 0; $r < count($replys); $r++){
                  //echo $pushs[$p]['content']."<br>";
  	          	$query_sns_reply = "INSERT INTO `intopet`.`asis_sns_reply_200807` 
                                    VALUES	('', 	'".$sns["_id"]."', 	
                                        '".$replys[$r]['_id']."', 
                                        '".$replys[$r]['good_number']."', 
                                    '".strFilter(urlencode($replys[$r]['message']))."', 
                                    '".$replys[$r]['create_datetime']."', 
                                    '".$replys[$r]['create_id']."', 
                                    '".$replys[$r]['report']."', 
                                    '".$replys[$r]['good_number_ids']."', 
                                    '".$replys[$r]['parent_reply_id']."', 
                                    '".$replys[$r]['parent_good_number']."', 
                                    '".$replys[$r]['active']."'	)";
                $result_sns_reply = mysql_query($query_sns_reply);
                if($result_sns_reply){}else{ echo $query_sns_reply; exit;}
	            	
	            //if($r > 3) exit;
            }//for pushs
            //echo date("Y-m-d H:i:s")." 3<br>";

            $clipnumber_ids = $sns["clip_number_ids"];
            for($m1 = 0; $m1 < count($clipnumber_ids); $m1++){
                  $query_sns_m1 = " 
                        INSERT INTO `intopet`.`asis_sns_clipnumber_id_200807` 
                        VALUES	('', 	'".$sns["_id"]."', 	'".$clipnumber_ids[$m1]."')";
                  $result_sns_m1 = mysql_query($query_sns_m1);
                  if($result_sns_m1){}else{ echo $query_sns_m1; exit;}
                        
                  //if($m1 > 3) exit;
            }//for 
            //echo date("Y-m-d H:i:s")." 4<br>";

            $sns_goods = $sns["good"];
            for($m2 = 0; $m2 < count($sns_goods); $m2++){
  	          	$query_sns_m2 = " 
				INSERT INTO `intopet`.`asis_sns_good_200807` 
				VALUES	('', 	'".$sns["_id"]."', 	'".$sns_goods[$m2]['_id']."', 
						'".$sns_goods[$m2]['good_userid']."', 
						'".$sns_goods[$m2]['good_datetime']."'	)";
  		      $result_sns_m2 = mysql_query($query_sns_m2);
	            if($result_sns_m2){}else{ echo $query_sns_m2; exit;}
	            	
                 	//if($m2 > 3) exit;
            }//for 
            //echo date("Y-m-d H:i:s")." 5<br>";

            $sections = $sns["section"];
            $section_str = "";
            $list_thumb = "";
            for($m3 = 0; $m3 < count($sections); $m3++){

                  $section_datas = $sections[$m3]['data'];
                  for($m4 = 0; $m4 < count($section_datas); $m4++){
                        if($section_datas[$m4]['type']=="image"){
                              $section_str .= "<img src=\"".$section_datas[$m4]['value']."\" style=\"width:100%;\"><br>";
                        }
                        if($section_datas[$m4]['type']=="stream"){
                            $section_str .= "<video autoplay loop muted playsinline controls style=\"width:100%;\">";
                            $section_str .= "<source src=\"".$section_datas[$m4]['value']."\" type=\"video/mp4\"><source/>";
                            $section_str .= "</video><br>";
                        }
                        if($section_datas[$m4]['type']=="video"){
                            $youtubestr = $section_datas[$m4]['value'];
                            //echo $section_datas[$m4]['value']."<br>";
                            $youtubestr = str_replace("http://img.youtube.com/vi/","",$youtubestr);
                            $youtubestr = str_replace("/0.jpg","",$youtubestr);
                            //echo $youtubestr;
                            //exit;//test
                            $section_str .= "<iframe width=\"100%\" height=\"auto\" src=\"https://www.youtube.com/embed/".$youtubestr."\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
                            //echo $section_str;exit;
                            if($m3==0 && $m4==0){
                                  $list_thumb = $section_datas[$m4]['value'];
                            }
                        }
                }
                  $section_str .= "".urlencode($sections[$m3]['content'])."<br><br>";
            }//for 

            $hashtags = $sns["hashtags"];
            $hashtags_str = "";
            for($r = 0; $r < count($hashtags); $r++){
                if($r > 0) $hashtags_str .= " ,";
                $hashtags_str .= "#".$hashtags[$r];
	            //if($r > 3) exit;
            }//for pushs

            $query_user = "  INSERT INTO `intopet`.`asis_sns_200807` 
                                VALUES
                                ('', 
                                '".$sns["_id"]."', 
                                '".$sns["create_id"]."', 
                                '".strFilter(urlencode($sns["title"]))."', 
                                '".$sns["publish_datetime"]."', 
                                '".$sns["active"]."', 
                                '".$sns["event_participation_ids"]."', 
                                '".$sns["event_participation_number"]."', 
                                '".$sns["report"]."', 
                                '".strFilter($section_str)."', 
                                '".$sns["clip_number"]."', 
                                '".$sns["good_number"]."', 
                                '".$sns["views_number"]."', 
                                '".$sns["update_datetime"]."', 
                                '".$sns["__v"]."', 
                                '".$sns["good_week_number"]."', 
                                '".$sns["category1"]."', 
                                '".$sns["category2"]."', 
                                '".iconv("utf-8","cp949",$hashtags_str)."'
                                '' )";
            $result_user = mysql_query($query_user);
            if($result_user){}else{ echo $query_user; exit;}
            //if($i > ($startnum+499)){
            //      echo date("Y-m-d H:i:s")."end<br>";
            //      exit;
            //}
            //echo date("Y-m-d H:i:s")." 6<br>";

            

            // insert cont
            if($result_user){
                $query_reg_id = "SELECT * FROM itapp_user WHERE del_yn = 'N' AND old_id = '".$sns["create_id"]."'";
                $result_reg_id = mysql_query($query_reg_id);
                $row_reg_id = mysql_fetch_assoc($result_reg_id);
                //echo date("Y-m-d H:i:s")." 7<br>";

                $query_exists_cont = "SELECT * FROM itapp_cont WHERE old_sns_id = '".$sns["_id"]."' AND del_yn = 'N'";
                $result_exists_cont = mysql_query($query_exists_cont);
                $row_exists_cont = mysql_fetch_assoc($result_exists_cont);
                $rows_exists_cont = mysql_num_rows($result_exists_cont);
                //echo date("Y-m-d H:i:s")." 8<br>";


                if(!$rows_exists_cont){
                    $query_insert_cont = "INSERT INTO itapp_cont (
                                                menu_gbn
                                                , title
                                                , cont
                                                , view_cnt
                                                , reg_datetime
                                                , reg_id
                                                , reg_ip
                                                , del_yn
                                                , like_cnt
                                                , old_sns_id
                                                , old_create_id
                                                , keyword
                                                , title_org
                                                , cont_org
                                                , keyword_org
                                                , list_thumb
                                            )
                                            SELECT 
                                                DISTINCT
                                                'story'
                                                , s.title
                                                , s.section
                                                , s.views_number
                                                , FROM_UNIXTIME(SUBSTRING(s.update_datetime,12),'%Y-%m-%d %H:%i:%s')
                                                , '".$row_reg_id['user_id']."'
                                                , '127.0.0.1'
                                                , 'N'
                                                , s.good_number
                                                , s.sns_id
                                                , s.create_id 
                                                , hashtags
                                                , '".addslashes(iconv("utf-8", "cp949", urldecode(strFilter(urlencode($sns["title"])))))."'
                                                , '".addslashes(iconv("utf-8", "cp949", urldecode(strFilter($section_str))))."'
                                                , '".iconv("utf-8","cp949",$hashtags_str)."'
                                                , '".$list_thumb."' 
                                            FROM asis_sns_200807 s
                                            WHERE sns_id = '".$sns["_id"]."'
                                            ORDER BY s.views_number DESC
                                            LIMIT 1 ";
                    $result_insert_cont = mysql_query($query_insert_cont);
                    $idx_cont_insert = mysql_insert_id();
                    echo "<br>cont inserted : ".mysql_affected_rows()." ";//<br>
                    array_push($arr_insert_cont, $idx_cont_insert);
                    //echo date("Y-m-d H:i:s")." 9<br>";

                } else {
                    $idx_cont_insert = $row_exists_cont['idx'];
                }

                $replys = $sns["reply"];
                for($r = 0; $r < count($replys); $r++){

                    $query_exists_reply = "SELECT * FROM itapp_reply WHERE old_id = '".$replys[$r]['_id']."' AND del_yn = 'N' and cont_idx is not null ";
                    $result_exists_reply = mysql_query($query_exists_reply);
                    $rows_exists_reply = mysql_num_rows($result_exists_reply);
                    //echo date("Y-m-d H:i:s")." 10<br>";

                    if(!$rows_exists_reply){
                        // 댓글
                        $query_insert_reply1 = "INSERT INTO itapp_reply (
                                                        gbn
                                                        , cont_idx
                                                        , ref_reply_idx
                                                        , title
                                                        , user_id
                                                        , nick
                                                        , reg_datetime
                                                        , reg_id
                                                        , reg_ip
                                                        , del_yn
                                                        , like_cnt
                                                        , old_id
                                                        , url_encode_yn
                                                    )
                                                    SELECT DISTINCT
                                                        NULL
                                                        , c.idx
                                                        , 0
                                                        , r.message
                                                        , u.user_id
                                                        , u.nick
                                                        , FROM_UNIXTIME(SUBSTRING(r.create_datetime,12),'%Y-%m-%d %H:%i:%s')
                                                        , u.user_id 
                                                        , '127.0.0.1'
                                                        , 'N'
                                                        , parent_good_number
                                                        , _id
                                                        , CASE WHEN (r.message LIKE '+%' OR r.message LIKE '\%%') THEN 'Y' ELSE 'N' END 
                                                    FROM asis_sns_reply_200807 r
                                                    LEFT JOIN itapp_user u
                                                    ON u.old_id = r.create_id
                                                    LEFT JOIN itapp_cont c
                                                    ON c.old_sns_id = r.sns_id
                                                    WHERE r._id = r.parent_reply_id
                                                    AND r._id = '".$replys[$r]['_id']."'";
                        $result_insert_reply1 = mysql_query($query_insert_reply1);
                        $idx_reply1_insert = mysql_insert_id();
                        //echo "reply inserted : ".mysql_affected_rows()." ";//<br>
                        array_push($arr_insert_reply1, $idx_reply1_insert);
                        //echo date("Y-m-d H:i:s")." 11<br>";

                        // 대댓글
                        $query_insert_reply2 = "INSERT INTO itapp_reply (
                                                    gbn
                                                    , cont_idx
                                                    , ref_reply_idx
                                                    , title
                                                    , user_id
                                                    , nick
                                                    , reg_datetime
                                                    , reg_id
                                                    , reg_ip
                                                    , del_yn
                                                    , like_cnt
                                                    , old_id
                                                    , url_encode_yn
                                                )
                                                SELECT DISTINCT
                                                    NULL
                                                    , c1.idx
                                                    , ar.idx
                                                    , r.message
                                                    , u.user_id
                                                    , u.nick
                                                    , FROM_UNIXTIME(SUBSTRING(r.create_datetime,12),'%Y-%m-%d %H:%i:%s')
                                                    , u.user_id
                                                    , '127.0.0.1'
                                                    , 'N'
                                                    , parent_good_number
                                                    , _id
                                                    , CASE WHEN (r.message LIKE '+%' OR r.message LIKE '\%%') THEN 'Y' ELSE 'N' END 
                                                FROM asis_sns_reply_200807 r
                                                LEFT JOIN itapp_user u
                                                ON u.old_id = r.create_id
                                                LEFT JOIN itapp_cont c1
                                                ON c1.old_sns_id = r.sns_id
                                                LEFT JOIN itapp_reply ar
                                                ON  ar.old_id = r.parent_reply_id
                                                AND ar.cont_idx IS NOT NULL
                                                WHERE r._id != r.parent_reply_id
                                                AND r._id = '".$replys[$r]['_id']."'";
                        $result_insert_reply2 = mysql_query($query_insert_reply2);
                        $idx_reply2_insert = mysql_insert_id();
                        //echo "rereply inserted : ".mysql_affected_rows()." ";//<br>
                        array_push($arr_insert_reply2, $idx_reply2_insert);
                        //echo date("Y-m-d H:i:s")." 12<br>";

                    }
                }
                
                $sns_goods = $sns["good"];
                for($m2 = 0; $m2 < count($sns_goods); $m2++){

                    $query_exists_cont_good = "SELECT * FROM itapp_cont_like WHERE old_id = '".$sns_goods[$m2]['_id']."' AND del_yn = 'N'";
                    $result_exists_cont_good = mysql_query($query_exists_cont_good);
                    $rows_exists_cont_good = mysql_num_rows($result_exists_cont_good);
                    //echo date("Y-m-d H:i:s")." 13<br>";

                    if(!$rows_exists_cont_good){
                        $query_insert_cont_good = "INSERT INTO itapp_cont_like
                                                    (
                                                        gbn
                                                        , cont_idx
                                                        , user_id
                                                        , reg_datetime
                                                        , reg_id
                                                        , del_yn
                                                        , old_id
                                                    )
                                                    SELECT DISTINCT
                                                        'cont'
                                                        , c.idx
                                                        , u.user_id
                                                        , FROM_UNIXTIME(SUBSTRING(g.good_datetime,12),'%Y-%m-%d %H:%i:%s')
                                                        , u.user_id
                                                        , 'N'
                                                        , g._id
                                                    FROM `asis_sns_good_200807` g
                                                    LEFT JOIN itapp_cont c
                                                    ON c.old_sns_id = g.sns_id
                                                    LEFT JOIN itapp_user u
                                                    ON u.old_id = g.good_userid
                                                    WHERE g._id = '".$sns_goods[$m2]['_id']."'";
                        $result_insert_cont_good = mysql_query($query_insert_cont_good);
                        echo "like inserted : ".mysql_affected_rows()." ";//<br>
                        //echo date("Y-m-d H:i:s")." 14<br>";
                    }
                }

                $clipnumber_ids = $sns["clip_number_ids"];
                for($m1 = 0; $m1 < count($clipnumber_ids); $m1++){
                    $query_exists_cont_save = "SELECT * FROM itapp_cont_save WHERE old_id = '".$clipnumber_ids[$m1]."' AND del_yn = 'N'";
                    $result_exists_cont_save = mysql_query($query_exists_cont_save);
                    $rows_exists_cont_save = mysql_num_rows($result_exists_cont_save);
                    //echo date("Y-m-d H:i:s")." 15<br>";

                    if(!$rows_exists_cont_save){
                        $query_insert_cont_save = "INSERT INTO itapp_cont_save
                                                    (
                                                        cont_idx, del_yn, reg_id, old_id
                                                    )
                                                    SELECT DISTINCT
                                                        '".$idx_cont_insert."'
                                                        , 'N'
                                                        , u.user_id
                                                        , s._id
                                                    FROM asis_sns_clipnumber_id_200807 s
                                                    LEFT JOIN itapp_user u
                                                    ON u.old_id = s._id
                                                    WHERE s._id = '".$clipnumber_ids[$m1]."'";
                        $result_insert_cont_save = mysql_query($query_insert_cont_save);
                        echo "clip inserted : ".mysql_affected_rows()." ";//<br>
                        //echo date("Y-m-d H:i:s")." 16<br>";
                    }
                    
                }
            }

            $last_cont_datetime = $sns["publish_datetime"];

            //echo "foreach";exit;//test
      }

    if($_GET['old_id']=="no"){
        echo "<br><br>".date("Y-m-d H:i:s")." date run :".$start_date." ~ ".$end_date." ".$i;
      exit;
    }

        $query_mig_check = " SELECT COUNT(*) cnt FROM itapp_cont WHERE reg_id = '".$row_mig_user['user_id']."' AND del_yn = 'N' AND old_sns_id is not null ";
        $result_mig_check = mysql_query($query_mig_check);
        $row_mig_check = mysql_fetch_assoc($result_mig_check);
        echo "<br><br>new cnt :".$row_mig_check['cnt']."<br>";

        $query_check_mongo = array("create_id"=> new MongoId($go_old_id));
        $cursor_check_mongo = $testDB->sns->find($query_check_mongo);
        $return_check_mongo = $cursor_check_mongo->count();
        //var_dump($return_check_mongo);
        //print_r($return_check_mongo);
        //echo $return_check_mongo;
        if($return_check_mongo == 0){
            //echo "zero<br>";
        }else{
            //echo "else";
        }
        if($_GET['old_id']!=""){
            exit;
        }

        
        
        if($row_mig_check['cnt'] == $return_check_mongo){
            $query_mig_user_end = " UPDATE itapp_user SET old_sns_mig_check_datetime = now() WHERE user_id = '".$row_mig_user['user_id']."' AND del_yn = 'N' ";
            echo $query_mig_user_end."<br>";
            $result_mig_user_end = mysql_query($query_mig_user_end);
            echo "<br>mig_user_end updated : [[[ ".mysql_affected_rows()." ]]]<br>";
            //if($row_mig_check[cnt]==0){
                ?><script>setTimeout("location.href='./mongo_sns.php?startnum=1&gbn=<?=$_GET['gbn']?>';", 500);</script><?
            //}
            echo "<br><a href='./mongo_sns.php?startnum=1&gbn=".$_GET['gbn']."'>reload()</a><br><br>";
        }else{
            echo "not same new:".$row_mig_check['cnt']." old:".$return_check_mongo." ".$row_mig_user['user_id']." ".$row_mig_user['nick'];
        }

	echo "[".$last_cont_datetime."]<br>";
	echo "[".substr($last_cont_datetime,11)."]<br>";
	echo "last_cont_datetime:".gmdate("Y-m-d\TH:i:s\Z", substr($last_cont_datetime,11))."<br>";
      echo date("Y-m-d H:i:s")."end<br>";
      echo "insert_cont_idx----------------<br/>";
      //print_r($arr_insert_cont);
      //print_r($arr_insert_reply1);
      //print_r($arr_insert_reply2);
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
  	echo "eee";
        echo $e->getMessage();
  }
  
function strFilter($str){
	return str_replace("'", "`", iconv("utf-8","euc-kr",$str));
}
?>