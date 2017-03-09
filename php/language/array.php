<?php
$name = array(  
        "self" => "wangzhengyi",  
        "student" => array(  
                "chenshan",  
                "xiaolingang"  
        ),  
        "unkmow" => "chaikun",  
        "teacher" => array(  
                "huangwei",  
                "fanwenqing"  
        ),
        "huangyinke"  
);  
$name[1][2] = "huangyinke++++";
$name[1]['mytitt'] = "huangyinke------";

function arrToStr ($array)  
{  
    // 定义存储所有字符串的数组  
    static $r_arr = array();  
      
    if (is_array($array)) {  
        foreach ($array as $key => $value) {  
            if (is_array($value)) {  
                // 递归遍历  
                arrToStr($value);  
            } else {  
                $r_arr[] = $value;  
            }  
        }  
    } else if (is_string($array)) {  
            $r_arr[] = $array;  
    }  
          
    //数组去重  
    $r_arr = array_unique($r_arr);  
    $string = implode(",", $r_arr);  
      
    return $string;  
}  

print( arrToStr($name) );
print("\r\n");

print( $name[0] );
print( $name[1][2]);
print( $name[1]['mytitt']);
?>
