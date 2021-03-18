<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<?
//echo strtotime("2020-07-30 12:00:00");
exit; 

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

  try {
      
      $mongo = new MongoClient("mongodb://app.intopet.co.kr:27017" );
      $testDB = $mongo->intopet1;

      $i = 0;

      $startnum = $_GET['startnum'];
      echo "[".$startnum."]";
      
      //$query = array("section.data.type"=> "stream"
      //,                       "publish_datetime"=>array('$gt'=>new MongoDate(strtotime("2020-01-01T00:00:00Z")))
      //);
      //$query = array("section.data.type"=> "video",
      //               "create_id"=>new MongoId("577e29814e8df67c22a5d0ea"));
      $query = array("section.data.type"=> "video"
      ,                       "publish_datetime"=>array('$gt'=>new MongoDate(strtotime("2017-01-01T00:00:00Z")), '$lt'=>new MongoDate(strtotime("2018-01-01T00:00:00Z")))
      );
      //"publish_datetime"=>array('$gt'=>new MongoDate(strtotime("2020-01-01T00:00:00Z")))
      $snss = $testDB->sns->find($query)->skip(($startnum -1))->limit(500);

      $last_cont_datetime = "";
      $arr_insert_cont = array();
      $arr_insert_reply1 = array();
      $arr_insert_reply2 = array();

      foreach( $snss as $sns ){
            $i++;
//            if($sns["_id"]=="57b6cb054546820a5e50c754" ||
//               $sns["_id"]=="57bbbcbc19b99f4e5a430d2e" ||
//               $sns["_id"]=="57c02a9b6545fc675f48d55c") continue;
            echo $i." ".$sns["_id"]." ";
            //exit;//test

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

            $query_update_cont = " UPDATE itapp_cont 
                                    SET cont = '".strFilter($section_str)."',
                                        cont_org = '".addslashes(iconv("utf-8", "cp949", urldecode(strFilter($section_str))))."' ";
            if($list_thumb != "") $query_update_cont .= " , list_thumb = '".$list_thumb."'   ";
            $query_update_cont .= " WHERE old_sns_id = '".$sns["_id"]."' ";
            //echo $query_update_cont; exit;
            $result_update_cont = mysql_query($query_update_cont);
            echo "cont update : ".mysql_affected_rows()."<br>";
            //exit;
      }

	exit;

  } catch (Exception $e) {
  	echo "eee";
        echo $e->getMessage();
  }
  
function strFilter($str){
	return str_replace("'", "`", iconv("utf-8","euc-kr",$str));
}
?>