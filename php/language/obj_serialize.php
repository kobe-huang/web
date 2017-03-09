<?php
//声明一个类
class dog {

    var $name;
    var $age;
    var $owner;

    function dog($in_name="unnamed",$in_age="0",$in_owner="unknown") {
        $this->name = $in_name;
        $this->age = $in_age;
        $this->owner = $in_owner;
    }

    function getage() {
        return ($this->age * 365);
    }
    
    function getowner() {
        return ($this->owner);
    }
    
    function getname() {
        return ($this->name);
    }
}
//实例化这个类
$ourfirstdog = new dog("Rover",12,"Lisa and Graham");
//用serialize函数将这个实例转化为一个序列化的字符串
$dogdisc = serialize($ourfirstdog);
print $dogdisc; //$ourfirstdog 已经序列化为字符串 O:3:"dog":3:{s:4:"name";s:5:"Rover";s:3:"age";i:12;s:5:"owner";s:15:"Lisa and Graham";}

print '<BR>';

/* 
-----------------------------------------------------------------------------------------
    在这里你可以将字符串 $dogdisc 存储到任何地方如 session,cookie,数据库,php文件 
-----------------------------------------------------------------------------------------
*/

//我们在此注销这个类
unset($ourfirstdog);

/*    还原操作   */

/* 
-----------------------------------------------------------------------------------------
    在这里将字符串 $dogdisc 从你存储的地方读出来如 session,cookie,数据库,php文件 
-----------------------------------------------------------------------------------------
*/


//我们在这里用 unserialize() 还原已经序列化的对象
$pet = unserialize($dogdisc); //此时的 $pet 已经是前面的 $ourfirstdog 对象了
//获得年龄和名字属性
$old = $pet->getage();
$name = $pet->getname();
//这个类此时无需实例化可以继续使用,而且属性和值都是保持在序列化之前的状态
print "Our first dog is called $name and is $old days old<br>";
print '<BR>';
?>