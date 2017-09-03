<?php


//奖品项
class Awards
{
  public $name;//奖项名
  public $num;//奖项数量
  
  function __construct($a, $b)
  {
    $this->name = $a;
    $this->num = $b;
  }

  function attr()
  {
    echo("name->".$this->name."\n");
    echo("number->".$this->num."\n");
  }
}


//$a1 = new Awards("一等奖", 10);
//$a1->attr();
//echo $a1->num;

//function lottery()
class Lottery
{
  public $args;
  public $args_arr;
  //echo func_num_args();//放回参数个数
  protected $total;
  //if(func_num_args() == 0)echo("传入参数不得为空\n");
  function __construct()
  {
    $this->args = func_num_args();
    $this->args_arr = func_get_args();
  }
  //$total = array_sum($args_arr);//放回参数总数
  public function start()
  {
    $this->total = array_sum($this->args_arr);
    if($this->total > 0)
    {
      $ran = mt_rand(0, $this->total);
      $tmp_total = 0;
      for($i = 0; $i < $this->args; $i++)
      {
        $tmp_total += $this->args_arr[$i];
        if($ran < $tmp_total)
        {
          $this->args_arr[$i]--;
          echo("第".($i+1)."等奖，剩余".$this->args_arr[$i]."\n");
          break;
        }
      }
      return true;
    }
    else
    {
      echo("抽奖结束\n");
      return false;
    }
  }
  /*
  do
  {
    
    $total = array_sum($args_arr);
    $ran = mt_rand(0, $total);
    $tmp_total = 0;
    for($i = 0; $i < $args; $i++)
    {
      $tmp_total += $args_arr[$i];
      if($ran < $tmp_total)
      {
        $args_arr[$i]--;
        echo("第".($i+1)."等奖，剩余".$args_arr[$i]."\n");
        break;
      }
    }
  }while($total > 0);
  echo("抽奖结束\n");
  */
}

$lo = new Lottery(1, 2, 3, 4);

while(true)
{
  if($lo->start());
  else{break;}
}
