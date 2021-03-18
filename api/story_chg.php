<?php
$data = shell_exec("dir");
// euc-kr의 인코딩을 utf-8로 변환한다.
//$data = mb_convert_encoding($data, "UTF-8", "euc-kr");
// htmlspecialchars는 html에서 읽혀지는 <, >, &, ", '의 특수 기호를 html코드로 변환한다.
echo "<pre>".htmlspecialchars($data)."</pre>";

//$data = shell_exec("ffmpeg -i /home/intopet/app/files/2020/08/20200826065429.mp4 -vf scale=320:-1 -r 10 -f image2pipe -vcodec ppm - | convert -delay 10 -loop 0 - /home/intopet/app/files/2020/08/20200826065429.gif");

?>