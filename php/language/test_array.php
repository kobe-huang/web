<?php

 function randnum($total,$div,  $area = 50){
   //$area = 50; //各份数间允许的最大差值
   print($area);
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

   foreach( $result as $key =>$temp ){
   $data[]=$temp;
   }
   return $data;
   }
 
   /*调用方法*/
 
   $x=randnum(1000,5);
   print_R($x);
exit;

function my_randnum($total, $div){ //$total 总数, $div 份数 //randnumber

    $area = 14; //各份数间允许的最大差值
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
    return $result;
}
$tttt = my_randnum(100,5);
$tttt[0] = 0;
print_r($tttt);
if(0 == $tttt[0]){
print($tttt[1]);
}

exit;




$huangyinke = 1000;
error_log("fdfdf $huangyinke");
exit;


 $contact1 = array(                                             //定义外层数组
    array(1,'huang' => '高某','A公司','北京市','(010)987654321','gm@Linux.com'),//子数组1
    array(2,'洛某','B公司','上海市','(021)123456789','lm@apache.com'),//子数组2
    array(3,'峰某','C公司','天津市','num' => '(022)24680246','fm@mysql.com'),  //子数组3
    array(4,'书某','D公司','重庆市','(023)13579135','sm@php.com')     //子数组4
    );

 function print_2_array($contact1)
 {
    //for($row=0; $row<count($contact1); $row++)
    foreach ($contact1 as $key1 => $value1) {   
        //error_log($key1);
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
print_2_array($contact1);
exit;

function my_wash_card($card_num)  //kobe的洗牌算法
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
    } 
    return $cards; 
} 
$my_array = my_wash_card(10);
$wewew = print_r($my_array);
error_log($wewew);
exit;

$my_array = my_wash_card(1);
print_r($my_array);

exit;

$my_array = array(1,2,3,4,5,6);
print_r($my_array);
foreach ($my_array as $key => $value) {
    print($key . "\n");
    print($value);
}
exit;


//数组下标也是按照，字符串来处理的。这个跟lua还不一样。 '0' 和 0是一样的。
//数组中的数据类型，不能是function 函数
$array1[] = array('id'=>1,'price'=>50);
$array1[] = array('id'=>2,'price'=>70);
$array1[] = array('id'=>3,'price'=>30);
$array1[] = array('id'=>4,'price'=>20);
$array1[3] = array('id'=>5,'price'=>20);
$array1[] = array('id'=>6,'price'=>20);
print_r($array1);

$arr = array(   
    12314,
    '4' => 987,
    '0' => array(
        'num1' => 3,
        'num2' => 27 
    ),

    '1' => array(
        'num1' => 5,
        'num2' => 50
    ),
    
    '2' => array(
        'num1' => 4,
        'num2' => 44
    ),    
    '3' => array(
        'num1' => 322,
        'num2' => 78
    ) 
);


//$arr[0] = 2232323;
//$arr[0] = array();
function writeMsg() {
  echo "Hello world!";
}
//$arr[6] = writeMsg;

$arr[0][0] = 12121212;
print_r($arr);
print($arr[0]);
print($arr[0]['num2']);
print($arr[4]);


function FetchRepeatMemberInArray($array) { 
    // 获取去掉重复数据的数组 
    $unique_arr = array_unique ( $array ); 
    print_r($unique_arr);
    // 获取重复数据的数组 
    $repeat_arr = array_diff_assoc ( $array, $unique_arr ); 
    return $repeat_arr; 
} 

// 测试用例 
$array = array ( 
        2, 
        2, 
        3, 
        1, 
        2, 
        3,
        2,
        1,
        3  
); 
$repeat_arr = FetchRepeatMemberInArray ( $array ); 
print_r ( $repeat_arr ); 




/*
foreach ( $arr as $key => $row ){
    $num1[$key] = $row ['num1'];
    $num2[$key] = $row ['num2'];
}

array_multisort($num1, SORT_ASC, $num2, SORT_DESC, $arr);

print_r($arr);*/
//result:Array([0]=>Array([num1]=>3 [num2]=>78) [1]=>Array([num1]=>3 [num2]=>27) [2]=>Array([num1]=>4 [num2]=>44) [3]=>Array([num1]=>5 [num2]=>50))