1. 서버에서 클라이언트로 로그인 요청
http://app2.intopet.co.kr/app/api/kakao_login_request.php?

=> 해당 페이지로 들어오면 path가 "kakao_login_request.php" 인경우 다음작업

2. 클라이언트에서 서버로 로그인 요청
String url="http://app2.intopet.co.kr/app/api/kakao_login.php?"; //카카오 로그인
//String url="http://app2.intopet.co.kr/app/api/facebook_login.php?"; //페이스북 로그인
//String url="http://app2.intopet.co.kr/app/api/login.php?"; //로그인

String parameter="userid=사용자고유키값&profile=json형식의값&fcmtoken=푸시토큰;

wb.postUrl(url, EncodingUtils.getBytes(postData, "BASE64")); //post 방식
wb.loadUrl(url + parameter); //get 방식
