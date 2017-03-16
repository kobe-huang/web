<?php

 $card_num=54; //牌数 
 print_r(wash_card($card_num)); 

function RandomizeArray($array){
  // error check:
  $array = (!is_array($array)) ? array($array) : $array;
  $a = array();
  $max = count($array) + 10;
  while(count($array) > 0){    
    $e = array_shift($array);
    $r = rand(0, $max);
    // find a empty key:
    while (isset($a[$r])){
      $r = rand(0, $max);
    }    
    $a[$r] = $e;
  }
  ksort($a);
  $a = array_values($a);
  return $a;
}

 function wash_card($card_num) 
 { 
     $cards=$tmp=array(); 
     for($i=0;$i<$card_num;$i++){ 
         $tmp[$i]=$i; 
     } 

     for($i=0;$i<$card_num;$i++){ 
         $index=rand(0, $card_num-$i-1); 
         $cards[$i]=$tmp[$index]; 
         unset($tmp[$index]); 
         $tmp=array_values($tmp); 
     } 

     for($i=0; $i<($card_num/2); $i++){
        
     }

     return ($cards);
    // RandomizeArray($cards); 
    // RandomizeArray($cards); 
    // RandomizeArray($cards); 
    // RandomizeArray($cards); 
    // return RandomizeArray($cards); 
 } 