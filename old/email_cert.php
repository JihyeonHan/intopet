<?
include_once($_SERVER['DOCUMENT_ROOT']."/app/php/common/include/db.php");

	$query = " update itapp_user set 
	              user_id = '".$email."'
						where certkey = '".$_GET[code]."'
						  and cert_due_datetime >= '".date("Y-m-d H:i:s")."' ";
	//echo $query;
	//exit;
	$result = mysql_query($query,$CONN);
	if($result){  ?>
	<meta charset="utf-8">
	<script>alert('인증되었습니다');location.href='http://www.intopet.kr/';</script>
<?}else{  ?>
  <meta charset="utf-8">
	<script>alert('오류가 발생하였습니다.');location.href='http://www.intopet.kr/';</script>
<?}
?>
