<?php

function lottery()
{
  //echo func_num_args();//放回参数个数
  if(func_num_args() == 0)echo("传入参数不得为空\n");
  $args = func_num_args();
  $args_arr = func_get_args();
  global $total;
  //$total = array_sum($args_arr);//放回参数总数
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
}

lottery(1,2,3,4,5);
