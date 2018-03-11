<?php
class gjPhone
{
 
  protected $imgPath; // 图片路径
  protected $imgSize; // 图片大小
  protected $hecData; // 分离后数组
  protected $horData; // 横向整理的数据
  protected $verData; // 纵向整理的数据
  protected $xWord; // 切割过后的 字符组
  function __construct ($path)
  {
    $this->imgPath = $path;
  }
 
  /**
   * 颜色分离转换...
   *
   * @param unknown_type $path      
   * @return j => width i => height
   */
  public function getHec ()
  {
    $size = getimagesize($this->imgPath);
    $res = imagecreatefromgif($this->imgPath);
    for ($i = 0; $i < $size[1]; ++ $i) {
      for ($j = 0; $j < $size[0]; ++ $j) {
        $rgb = imagecolorat($res, $j, $i);
        $rgbarray = imagecolorsforindex($res, $rgb);
        if (($rgbarray['red'] < 125 || $rgbarray['green'] < 125 || $rgbarray['blue'] < 125) 
             && 
            ($rgbarray['red'] >30 || $rgbarray['green'] >30 || $rgbarray['blue'] >30)
             && 
            ($rgbarray['green'] + $rgbarray['red'] + $rgbarray['blue'] != 0)) {
          $data[$i][$j] = 1;
          //echo($data[$i][$j]);
        } else {
          $data[$i][$j] = 0; 
          //echo($data[$i][$j]);
        }
      }
      //echo("\n");
    }
    $this->imgSize = $size;
    $this->hecData = $data;
  }
 
  /**
   * 颜色分离后的数据横向整理...
   * 去除噪点
   * @return unknown
   */
  public function magHorData ()
  {
    $data = $this->hecData;
    $size = $this->imgSize;
    for ($i = 0; $i < $size[1]; $i++) {
      
        for ($j = 0; $j < $size[0];$j++) {
          $z = 0;
          if ($data[$i][$j] == '1') 
          {
            if(isset($data[$i-1][$j]) && $data[$i-1][$j] == 0) $z += 1;
            if(isset($data[$i+1][$j]) && $data[$i+1][$j] == 0) $z += 1;
            if(isset($data[$i][$j+1]) && $data[$i][$j-1] == 0) $z += 1;
            if(isset($data[$i][$j-1]) && $data[$i][$j+1] == 0) $z += 1;
            if($z>=3){ $newdata[$i][$j] = '0';}else{$newdata[$i][$j] = '1';}
            
          }
          else
          {
            $newdata[$i][$j] = $data[$i][$j];
          }
        }
      
    }
    for ($i = 0; $i < $size[1]; $i++) {

      for ($j = 0; $j < $size[0];$j++) {
        echo($newdata[$i][$j]);
    }
    echo("\n");
  }
    return $this->horData = $newdata;
}
 
  /*
  * 切割无用的宽边
  * 同时掉转 遍历方向
  *
  */
public function fifder()
{
  $data = $this->horData;
  $size = $this->imgSize;
  $xfifder = array();

  $newdata = array_slice($data,5,13,false);
  
  for ($i = 0; $i < $size[0]; $i++) {
    $z = 0;
    for ($j = 0; $j < 13;$j++) {
      $data[$i][$j] = $newdata[$j][$i] ;
      if($newdata[$j][$i] == '1')$z+=1;
    }
      if($z>=2)$xfifder[] = $i;
  }
  //print_r($xfifder);



  $xWord = array();
  $xPre = $xfifder[0];
  $xWordIndex = 0;

  foreach($xfifder as $x)
  {
    if($x == $xPre){
      $xWord[$xWordIndex][] = $data[$x];
      $xPre ++ ;
    }
    else
    {
      $xPre = $x;
      $xPre ++;
      $xWordIndex ++ ;
      $xWord[$xWordIndex][] = $data[$x];
    }
  }

  //print_r($xWord);
foreach($xWord as $w){
  $str = "";
  for ($i = 0; $i < 12; $i++) {
    for ($j = 0; $j < count($w);$j++) {
      echo($w[$j][$i]);
      $str .= $w[$j][$i];
    
    }
    echo("\n");
  }
  $trueWord[] = $str;
  echo("\n");
 }
   return $this->xWord = $trueWord;
}
  /**
   * 整理纵向数据...
   *
   * @return unknown
   */
  public function magVerData ($newdata)
  {
    $size = $this->imgSize;
    for ($i = 0; $i < $size[0]; ++ $i) {
      for ($j = 1; $j < $size[1]; ++ $j) {
        $ndata[$i][$j] = $newdata[$j][$i];
      }
    }
     
    $sum = count($ndata);
    $c = 0;
    for ($a = 0; $a < $sum; $a ++) {
      $value = $ndata[$a];
      if (in_array(1, $value)) {
        $ndatas[$c] = $value;
        $c ++;
      } elseif (is_array($ndatas)) {
        $b = $c - 1;
        if (in_array(1, $ndatas[$b])) {
          $ndatas[$c] = $value;
          $c ++;
        }
      }
    }
     
    return $this->verData = $ndatas;
  }
 
  /**
   * 显示电话号码...
   *
   * @return unknown
   */
  public function showPhone ($ndatas)
  {
    $phone = null;
    $d = 0;
    foreach ($ndatas as $key => $val) {
      if (in_array(1, $val)) {
        foreach ($val as $k => $v) {
          $ndArr[$d] .= $v;
        }
      }
      if (! in_array(1, $val)) {
        $d ++;
      }
    }
    foreach ($ndArr as $key01 => $val01) {
      $phone .= $this->initData($val01);
    }
    return $phone;
  }
  
  //比较
  function compare()
  {
    $str = "";
    $data = $this->xWord;
    foreach($data as $wordnum => $w)
    {
      $result = $this->initData($w);
      if(isset($result))
      {
        $str .= $result;
      }
      else
      { 
        $str = "第".($wordnum+1)."个字符无法识别";
        break;
      }
    }
    //print_r($data);
    echo("*************************************\n".$str."\n****************************");

  }
  /**
   * 分离显示...
   *
   * @param unknown_type $dataArr      
   */
  function drawWH ($dataArr)
  {
    if (is_array($dataArr)) {
      foreach ($dataArr as $key => $val) {
        foreach ($val as $k => $v) {
          if ($v == 0) {
            $c .= "<font color='#FFFFFF'>" . $v . "</font>";
          } else {
            $c .= $v;
          }
        }
        $c .= "<br/>";
      }
    }
    echo $c;
  }
 
  /**
   * 初始数据...
   *
   * @param unknown_type $numStr      
   * @return unknown
   */
  public function initData ($numStr)
  {
    $result = null;
    $data = array(
        '0' => '001111000111111011100111110000111100001111100011110000111100001111000111111001111111111000111100',
        '1' => '011000000000011000000000111111111111111111111111',
        '2' => '001111001111111011100011110000110000001100001110000011100001110000111000011000001111111111111111',
        '3' => '001000000010011000000011110000000001110000000001110000110001110000110001011001110011011111011111000110001100',
        '4' => '000001100000111000001110000111100011011000110110011101101100011011111111111111110000011000000110',
        '5' => '111111000001111111000001110001000001110001000001110001100001110001100001110000110011110000111111000000001100',
        '6' => '001111100111111101100111110000001101111011111110111011111100001111000011011000110111111000111100',
        '7' => '110000000000110000000111110000111111110001110000110111000000111100000000111000000000111000000000',
        '8' => '011111000111111011000011110000010100001101111110011111101111001111000011110000110111111000111100',
        '9' => '001111000000011111100001110000110001110000110001110000110001110000110001011000100001011111100111000111111110000001110000',
        'A' => '',
        'B' => '111111110011111111101100000110110000011011000001101111111100111111111011000001111100000011110000001111111111101111111100',
        'D' => '011111110000111111111001100000110011000000110110000001101100000011011000000110110000001101100000011011000001100111111111001111111000',
        'F' => '111111111111111111000100110000001100000011111110111111101100000011000000110000001100000011000000',
        'H' => '110000001111000000111100000011110000001111000000111111111111111111111111000000111100000011110000001111000000111100000011',
        'J' => '000000110000001100000011000000110000001100000011000000110000001111000011111001110111111000111100',
        'L' => '110000001100000011000000110000001110000011000000110000001100000011000000110000001111111111111111',
        'N' => '000000001111100000111111000011111100001111011000111100110011110011001111000110111100001111110000111111000001111100000011',
        'P' => '111111100111111110110000111110000011110000111111111110111111100110000000110000000110000000110000000110000000',
        'R' => '111111110011111111101100000111110000001111000001110111111110111111100011000111001100001110110000011011000001111100000001',
        'T' => '111111111111111111110000110000000011000000001100000000110000000011000000001100000000110000000011000000001100000000110000',
        'V' => '110000000111100000001101100000110011000001100011000110000110001100001100011000001101100000011011000000011100000000111000000001110000',
        'X' => '110000011111000111011000110001101100001111100000111000000111000001111100001101100011000110111000111110000011',
        'Z' => '001111111001111111000000110000001100000011100000011000000110010001110000001100000111000000111111111111111111'
    );
    foreach ($data as $key => $val) {
      similar_text($numStr, $val, $pre);
      if ($pre > 90) { // 相似度95%以上
        $result = $key;
        break;
      }
    }
    return $result;
  }
}



for($i = 26; $i < 100; $i++){
$imgPath = "./VerifyCode.aspx.";
$imgPath2 = $imgPath.$i;
$gjPhone = new gjPhone($imgPath2);
// 进行颜色分离
$gjPhone->getHec();
echo("\n");



// 画出横向数据
$horData = $gjPhone->magHorData();

//切割并返回字符组
$gjPhone->fifder();

//对字符进行比对

$gjPhone->compare();

}
/*echo "===============横向数据==============<br/><br/><br/>\n";
$gjPhone->drawWH($horData);
// 画出纵向数据
$verData = $gjPhone->magVerData($horData);
echo "<br/><br/><br/>===============纵向数据==============< br/><br/><br/>\n";
$gjPhone->drawWH($verData);
 
// 输出电话
$phone = $gjPhone->showPhone($verData);
echo "<br/><br/><br/>===============电话==============<br /><br/><br/>\n" . $phone;


