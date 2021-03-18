<?
/*
        $data = array(
            'gbn' => 'hospital_auth_add'
            , 'clientCode' => $row_user['clientCode']
            , 'phone' => $row_user['phone']
            , 'coCode' => $row_user['coCode']
        );

        $url = "http://admin.intocns.co.kr/admin/app/mongo_interface.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data));
        $response = curl_exec($ch);
        curl_close($ch);
        print_r($response);

$m = new MongoClient('mongodb://localhost');
$db = $m->test;
$collection = $db->gals;
$data = array("gal_url"=>"http://gal.dcinside", "gal_barogagy"=>"스타 갤러리", "gal_name"=>"스타");
$collection->insert($data);
echo $collection->count();

exit;

*/


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
    $query_user = " SELECT  p.idx pet_idx, c.coCode, c.clientCode, c.petCode,
                            p.yyyy, p.mm, p.dd, p.pet_priv_code, p.vet_species, p.vet_breed
                    FROM itapp_pet p, itapp_pet_cert c
                    WHERE p.del_yn = 'N'
                      AND (p.gender IS NULL OR p.gender NOT IN('1','2','3','4','5'))
                      AND p.user_idx = c.user_idx AND p.idx = c.pet_idx AND c.del_yn = 'N'
                    limit 1 ";
    //--  AND (p.pet_cate_1 IS NULL OR p.pet_cate_1 NOT IN('1','2','3','4','5'))  
    //--  AND (p.pet_cate_2 IS NULL OR p.pet_cate_2 NOT IN('1','2','3','4','5'))  
    //--  AND (p.pet_priv_code IS NULL OR p.pet_priv_code = '')  
    //echo $query_user; exit;
    $result_user = mysql_query($query_user);
    $row_user = mysql_fetch_assoc($result_user);
    
    echo $row_user['pet_idx']."[".$row_user['coCode']."][".$row_user['clientCode']."][".$row_user['petCode']."]";
    echo "yyyy:".$row_user['yyyy']." mm:".$row_user['mm']." dd:".$row_user['dd']." rfid:".$row_user['pet_priv_code']." species:".$row_user['vet_species']." breed:".$row_user['vet_breed']."<br>";
    if($row_user['coCode'] != "" && $row_user['petCode'] != ""){
        $data = array(
            'gbn' => 'pet_hospital_data'
            , 'coCode' => $row_user['coCode']
            , 'clientCode' => $row_user['clientCode']
            , 'petCode' => $row_user['petCode']
        );
        //print_r($data);
        $url = "http://admin.intocns.co.kr/admin/app/mongo_interface.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data));
        $response = curl_exec($ch);
        curl_close($ch);
        print_r($response);

        $pet = json_decode($response);//->pets;
        foreach($pet as $key => $val){
            //print_r($key);
            $id = json_decode(json_encode($val), true);
            $_id = $id['_id']['$id'];
            $petCode = $val->code;
            $name = $val->name;
            $rfid = $val->rfid;
            $birthDate = $val->birthDate;
            $yyyy = substr($birthDate, 0, 4);
            $mm = substr($birthDate, 5, 2);
            $dd = substr($birthDate, 8, 2);
            $sexualType = $val->sexualType;
            $gender = "";
            if($sexualType == '수컷'){$gender = '1';}
            else if($sexualType == '수컷(중성화)' || $sexualType == '중성화 수컷'){$gender = '2';}
            else if($sexualType == '암컷'){$gender = '3';}
            else if($sexualType == '암컷(중성화)' || $sexualType == '중성화 암컷'){$gender = '4';}
            else if($sexualType == '모름'){$gender = '5';}
            else{
                echo "gender is not found [".$sexualType."]"; exit;
                $gender = $sexualType;
            }
            $breed = $val->breed;
            $species = $val->species;

            $jong_code = $species;

            $query_insert_pet = " UPDATE itapp_pet
                                    SET   gender = '".$gender."' ";
            if($row_user['yyyy'] == "") $query_insert_pet .= " , yyyy = '".$yyyy."' ";
            if($row_user['mm']   == "") $query_insert_pet .= " , mm   = '".$mm."' ";
            if($row_user['dd']   == "") $query_insert_pet .= " , dd   = '".$dd."' ";
            if($row_user['pet_priv_code']   == "") $query_insert_pet .= " , pet_priv_code   = '".$rfid."' ";
            if($row_user['vet_species']   == "") $query_insert_pet .= " , vet_species   = '".addslashes(iconv("utf-8", "euc-kr", $species))."' ";
            if($row_user['vet_breed']   == "") $query_insert_pet .= " , vet_breed   = '".addslashes(iconv("utf-8", "euc-kr", $breed))."' ";
            $query_insert_pet .= " WHERE idx = '".$row_user['pet_idx']."' ";

            echo $query_insert_pet;
            //exit;
            $result_insert_pet = mysql_query($query_insert_pet);
            if($result_insert_pet){ 
                echo "update ok";
                // echo "<script>location.reload();</script>";
            }
            else{ echo "update fail";exit;}
        }//foreach

    }//if
?><script>setTimeout("location.reload();", 5000);</script><?
echo date("Y-m-d H:i:s");
} catch (Exception $e) {
    echo "CATCH EXCEPTION ---";
      echo $e->getMessage();
}

?>