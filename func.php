<?php
//使用给定的URL下载图片并保存为特定文件
function get_image($url,$filename,$timeout=5)
{
  $file = fopen($filename, 'w+');
  $ch=curl_init();
  curl_setopt($ch,CURLOPT_URL,$url);//设定需要回去的URL
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//设置获取的信息以字符串返回
  curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);//设置超时时间30秒
  curl_setopt($ch,CURLOPT_FILE,$file);//设置保存的文件
  curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36SE 2.X MetaSr 1.0');
  curl_setopt($ch, CURLOPT_REFERER, 'http://www.baidu.com'); 
  curl_exec($ch);
  curl_close($ch);
  fclose($file);
  echo " $filename 下载完成";
}
//获取html源码
function get_html($url)
{
  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_TIMEOUT,30);
  curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36SE 2.X MetaSr 1.0');
  curl_setopt($ch, CURLOPT_REFERER, 'http://www.baidu.com');
  //curl_setopt($ch, CURLOPT_HEADER, 1);
  $htdata = curl_exec($ch);
  curl_close($ch);
  return $htdata;
}

//对源码进行xpath匹配
function xpath_preg($html,$xprule)
{
  $dom = new DOMDocument();
  @$dom->loadHTML($html);
  if(!isset($dom)){echo "DOMDocument不存在";}
  $xpath = new DOMXpath($dom);
  $elements = $xpath->query($xprule);
  return $elements;
}
//对html源码进行正则匹配
//function grep_html(){};

//对数据库进行连接
function connect_db($server, $user, $passwd, $db)
{
  $conn=mysql_connect($server,$user,$passwd) or die("连接失败") ; //连接数据库
 
  mysql_query("set names 'utf8'"); //数据库输出编码 应该与你的数据库编码保持一致.南昌网站建设公司百恒网络PHP工程师建议用UTF-8 国际标准编码.
 
  mysql_select_db($db); //打开数据库
  return $conn;
}

//对数据库进行查询
function comp($res = array(),$str)
{
    foreach($res as $r)
    {
      if($r['href'] == $str)
      {
        return false;
      }
      else
      {
        return true;
      }
    }

}
?>
