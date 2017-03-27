<?php
//kobe for 调试，debug输出信息
 function print_2_array($contact1)
 {
    return;
    //for($row=0; $row<count($contact1); $row++)
    foreach ($contact1 as $key1 => $value1) {   
        //log_info($key1);
        //使用内层循环遍历数组$contact1 中 子数组的每个元素,使用count()函数控制循环次数
        $out_string = "";
        //for($col=0; $col<count($contact1[$row]); $col++)
        foreach ( $contact1[$key1] as $key => $value ) {
             $out_string = $out_string . "  " . $contact1[$key1][$key];
            # code...
        }
        error_log($out_string);   
    }
}

function print_n_array($contact1){
    foreach ($contact1 as $key1 => $value1) {   
        if ( true == is_array($contact1[$key1]) )
        {
            $this->print_n_array($contact1[$key1]);
        } else{
            error_log( $key1 . " => " . $contact1[$key1]);
        }
        
    }
}

function log_info($info)
{
    return;
    error_log($info);
}

/*function tablename($table) {
    if(empty($GLOBALS['_W']['config']['db']['master'])) {
        return "`{$GLOBALS['_W']['config']['db']['tablepre']}{$table}`";
    }
    return "`{$GLOBALS['_W']['config']['db']['master']['tablepre']}{$table}`";
}*/


//------------------------
//数组按照关键字排序
function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){   
    if(is_array($arrays)){   
        foreach ($arrays as $array){   
            if(is_array($array)){   
                $key_arrays[] = $array[$sort_key];   
            }else{   
                return false;   
            }   
        }   
    }else{   
        return false;   
    }  
    array_multisort($key_arrays,$sort_order,$sort_type,$arrays);   
    return $arrays;   
}  
  
//获取一定范围内的多个随机数字
//http://www.12345t.com/code/php/20150330/406.html
function my_randnum($total, $div, $area = 30){ //$total 总数, $div 份数 //randnumber

    //$area = 15; //各份数间允许的最大差值
    $average = round($total / $div);
    $sum = 0;
    $result = array_fill( 1, $div, 0 );
     
    for( $i = 1; $i < $div; $i++ ){
     //根据已产生的随机数情况，调整新随机数范围，以保证各份间差值在指定范围内
        if( $sum > 0 ){
        $max = 0;
        $min = 0 - round( $area / 2 );
        }elseif( $sum < 0 ){
        $min = 0;
        $max = round( $area / 2 );
        }else{
        $max = round( $area / 2 );
        $min = 0 - round( $area / 2 );
        }
     
        //产生各份的份额
        $random = rand( $min, $max );
        $sum += $random;
        $result[$i] = $average + $random;
    }
     
    //最后一份的份额由前面的结果决定，以保证各份的总和为指定值
    $result[$div] = $average - $sum;

    foreach( $result as $temp ){
        $data[]=$temp;
    }
   return $data;
   
}

//kobe找的网上的洗牌算法
function my_wash_card($card_num)  
{ 
    $cards=$tmp=array(); 
    for($i=0;$i<$card_num;$i++){ 
        $tmp[$i]=$i; 
    } 

    for($i=0;$i<$card_num;$i++){ 
        $index=rand(0,$card_num-$i-1); 
        $cards[$i]=$tmp[$index]; 
        unset($tmp[$index]); 
        $tmp=array_values($tmp); 
        //log_info("my_wash_card: $cards[$i]");
    } 
    return $cards; 
} 
