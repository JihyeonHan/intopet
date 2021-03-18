<?
 /*http://app2.intopet.co.kr/app/api/init.php?token=&uuid= 
 */

if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH)
    echo "CRYPT_BLOWFISH is enabled!";
else
    echo "CRYPT_BLOWFISH is not available";

    //기존 암호
    //"password" : "$2a$10$3e0j8pojl4hi4zUCCXLO2e1LEvHm1hiI8u61Mye9Y8GiO2m8ThuFO",

    if(verify("w8cpuxrg" , "$2a$10$3e0j8pojl4hi4zUCCXLO2e1LEvHm1hiI8u61Mye9Y8GiO2m8ThuFO")){
        echo "true";
    }else{
        echo "false";
    }
    echo "verify=[".verify("w8cpuxrg" , "$2a$10$3e0j8pojl4hi4zUCCXLO2e1LEvHm1hiI8u61Mye9Y8GiO2m8ThuFO")."]<br><br>";
    // exit;

    //$salt = '$2a$07$R.gJb2U2N.FmZ4hPp1y2CN$';
    $salt = '$2a$10$R.gJb2U2N.FmZ4hPp1y2CN$';
    $passwd = "qazqazqaz";
    $passwd_hashed = crypt($passwd, $salt);
    //$passwd = crypt("qazqazqaz", 10);
    echo "passwd=[".$passwd_hashed."]<br><br>";
    echo "verify=[".verify($passwd , "$2a$10$R.gJb2U2N.FmZ4hPp1y2C.QZLoV4VRqSo7Q4jYrt27Ul87TWxhSYG")."]<br><br>";

    function verify($password , $hashedPassword ) {
        #로그인시 입력한 비밀번호의 해시값과 저장된 해시값 비교 
        return crypt($password, $hashedPassword) == $hashedPassword; 
    }
   
/*
    $passwd = "qazqazqaz";
    $hashedPassword = "$2a$10$CvnRSOpOgzSoIAPrB4wIpeMMyCpO7JeAOqBOz5NsAqHbf7F/5.Fl6";
    echo crypt($passwd, $hashedPassword);
    echo "--";
    //echo password_get_info();
*/

/*    
    echo "<br><br><br>------start-------<br><br>";
    //echo password_hash($passwd, PASSWORD_BCRYPT, array('cost' => 10));
    echo crypt($passwd, PASSWORD_BCRYPT, array('cost' => 10));
    echo "<br><br><br>------11-------<br><br>";
    $db_hashed_passwd = "$2a$10$CvnRSOpOgzSoIAPrB4wIpeMMyCpO7JeAOqBOz5NsAqHbf7F/5.Fl6";
    echo "<br><br><br>------22-------<br><br>";

    if ( ! password_verify($passwd, $db_hashed_passwd)) {
        //die('비밀번호가 일치하지 않습니다.');
        echo "nono";
    }else{
        echo "same";
    }
    echo "<br><br><br>-------end------<br><br>";
*/
?>