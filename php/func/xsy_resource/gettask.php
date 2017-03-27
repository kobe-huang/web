<?php
/**
 * @Author: 特筹网
 * @Date:   2017-01-12 14:41:04
 * @Last Modified by:   gangareset_task_listn
 * @Last Modified time: 2017-03-09 15:05:35
 */
header('Content-Type:text/html;charset=utf-8'); 
//require_once '../../framework/bootstrap.inc.php';  //系统初始化--这个地方需要优化，只初始化ql和cache就好了，其他的不用
require_once 'device_inc/device.inc.php';
require_once 'device_inc/misc.inc.php';

/**
 *  $arr->数组   $sort->排序顺序标志  
 *     排序顺序标志 SORT_DESC 降序；SORT_ASC 升序 ,
 */

/*
   task_list:
 { "id":"36",
   "task_id":"34",
   "task_d_id":"35",
   "task_time":"10",
   "task_num":"80",
   "task_pri":"2",
   "strategy_id":"17",
   "time":1489456698},*/



class GETTASK {
    public $time = 1489218514; //2017-03-11 03:48:34pm
    public $account ="";    //账户
    public $user_uid = ""; // user's id
    public $device_id = ""; //设备ID

    function task($get){
        log_info("----enter task-----");
        $this->check_web_status(); //检查系统是否在维护状态

        $alive_data = $this->check_user($get);
        $this->user_id = $alive['user_id'];
        $this->account = $alive_data['account'];

        log_info("check user okay");
        $this->time = time();     //当前系统时间

        $data = $this->allot($alive_data['account'], $alive_data['ms_id']);
        log_info("after allot");
        $this->update_alive($data, $alive_data);

        $code=101;
        $message='成功';
        return $this->output($code, $message, $data);
    }

    function output($code, $message, $data){//返回给设备
        $output = array(
            'Code'=>$code ,
            'Message'=>$message
        );
        if($data){
            $output['data']=$data;
        }
        exit(json_encode($output));
    }

    function send_error_info($code, $message){ //发送给设备错误信息
         exit(json_encode($this->output($code,$message) ) );
    }

    function check_web_status(){ //检查web的状态
        $_setting = pdo_fetch('SELECT * FROM '.tablename("ms_setting")." where 1");
        if($_setting['close']==1){//站点维护开启
            $code=105;
            $message='系统维护中';
            exit(json_encode($this->output($code,$message))); //kobe 这个json_encode是什么用？？
        }
    }

    function check_user($get){ //检查用户的合理性
        global $_W;
        if (!empty($get['task'])) {
            $json = str_replace("\\", "", $get['task']);
            $data = json_decode($json,true);
        
            if(!empty($data['ms_act']) && !empty($data['ms_pwd'])){
                $user = pdo_fetch('SELECT * FROM ' . tablename('mc_members') . " where realname='".$data['ms_act']."'");
                if(!empty($user)){
                    // 有此用户名
                    $salt=$user['salt'];
                    $password=$user['password'];
                    $ms_pwd=md5(trim($data['ms_pwd']) . $salt . $_W['config']['setting']['authkey']);
                    if($ms_pwd==$password){
                        // 密码正确  
                        if (!empty($data['ms_id']) && !empty($data['ms_type']) && !empty($data['ms_act']) ) {
                                $alive_data=array(
                                    'ms_id'=>$data['ms_id'],                  //序号
                                    'account'=>$data['ms_act'],               //账号
                                    'ms_type'=>$data['ms_type'],              //型号
                                    'user_id'=>$user['uid'],                  //user_id
                                    'time'=>time(),
                                );
                                return $alive_data;
                        }
                    }else{
                        $code=106;
                        $message='密码错误';            
                    }
                                              
                }else{
                    $code=107;
                    $message='用户名不存在';            
                }

            }
            else{
                $code=102;
                $message='失败11';            
            }
        }
        else{
            $code=102;
            $message='未知失败22';            
        }

        $this->send_error_info($code, $message);  //发出失败的信息
    }


    /**
     * 根据用户序号找出对应策略，根据策略分配任务
     * $ms_id   终端序号
     */ 
    function allot($account,$ms_id){
        $strategy = $this->get_strategy($account);//得到策略
        if (empty($strategy)){
            $this->send_error_info(102, "该用户未设置策略");
        }

        $allot = pdo_fetch('SELECT * FROM ' . tablename('ms_allot_table') . " where ms_id='".$ms_id."'");//任务列表
        $run_task_index = 0;            //要运行的任务index，初始化为0
        if ( empty($allot) ) {          //未分配过任务
            $task_list = $this->get_strategy_task_list($strategy['id']);
            log_info("strategy list:");
            print_2_array($task_list);
            $task_list = $this->reset_task_list($task_list,$strategy); 
            log_info("task list:");
            print_2_array($task_list);   
            $task_list = json_encode($task_list);
           
            $task_data=array(
                'ms_id' => $ms_id,//序列号
                'task_list'=> $task_list,//执行的任务列表
                'ms_task_index'=> 0,
            );
            pdo_insert ( "ms_allot_table", $task_data );        
        }
        else{  //已分配过任务，存在任务列表            
            
            $task_list=json_decode($allot['task_list'],true);      //已分配任务列表,从json数据中读出
            //now_timen = 现在的服务器时间
            //检查时间，查task_list, 找出要执行的“任务index”。          
            $tmp_task_count = count($task_list);        
            if( $allot['ms_task_index'] + 1 >= $tmp_task_count ){   //如果是运行到最后一个任务
                pdo_delete("ms_allot_table", array("ms_id" => $ms_id));
                return $this->allot($this->account, $ms_id);
            }
            
            $current_time = time(); //可以换成 $this->time
            for ($tmp_task_index = 0; $tmp_task_index < $tmp_task_count; $tmp_task_index++) {
                if ($current_time > $task_list[$tmp_task_index]['time']) {}
                else
                {
                    break;
                }
            }

            $tmp_task_index  = $tmp_task_index - 1; 
            //任务已经执行过了
            //if  current_task_index  ==  "任务index"  then  返回   idle任务给终端   end
            if( $tmp_task_index == $allot['ms_task_index']){
               $idle_path= pdo_fetch("SELECT id,path FROM " .tablename('xsy_resource_file') . " WHERE id=".$strategy['idle']);
               $data=array(
                            'TaskId'=>intval($idle_path['id']),
                            'TaskPath'=>$idle_path['path'],
                            'TaskDataID'=>'',
                            'TaskDataPath'=>'',
                            'strategy_id'=>'',
                            );
                return $data;
            }
            //if  current_task_index + 1 ==  "任务index"  then    //这种是正常状态
            else{
                $run_task_index = $tmp_task_index;//下一个执行的任务
                //更新正在执行的任务               
                pdo_update("ms_allot_table", array('ms_task_index'=>$run_task_index), array("ms_id"=>$ms_id) );
                //if  current_task_index + 1 ==  "任务index"  then    //这种是正常状态
                if( ($allot['ms_task_index']+1) != $run_task_index){
                    //错误处理
                }              
            }
        }

        $TaskId      = $task_list[$run_task_index ]['task_id'];
        $TaskPath    = $this->get_task_path($TaskId);
        $TaskDataID  = $task_list[$run_task_index]['task_d_id'];
        $TaskDataPath= $this->get_task_path($TaskDataID);
        $strategy_id = $strategy['id'];

        $user_config_path = pdo_fetch("SELECT user_config_path FROM " .tablename('xxx_user_strategy_table') ." WHERE user_id='".$this->user_id."'");

        $data=array(
        'TaskId'=>intval($TaskId),
        'TaskPath'=>$TaskPath,
        'TaskDataID'=>intval($TaskDataID),
        'TaskDataPath'=>$TaskDataPath,
        'strategy_id'=>intval($strategy_id),
        'user_config_path'=>$user_config_path,  //kobe新加一条，用户的配置数据地址
        );
        return $data;
    }

    //刷新alive设备表
    function update_alive($data, $alive_data){
        if(!empty($data['strategy_id']) && !empty($data['TaskId'])){
            $ms_id=pdo_fetchcolumn ("SELECT ms_id FROM " .tablename('ms_alive_table') . " WHERE ms_id='".$alive_data['ms_id']."'");
            if(empty($ms_id)){//新增
                //alive终端表 ms_alive_table
                $alive_data['strategy_id']=$data['strategy_id'];        //策略
                $alive_data['running_script_id']=$data['TaskId']; //执行的任务id
                pdo_insert ( "ms_alive_table", $alive_data );
            }else{//更新
                pdo_update("ms_alive_table",array('strategy_id'=>$data['strategy_id'],'running_script_id'=>$data['TaskId'],'time'=>$alive_data['time']),array("ms_id"=>$alive_data['ms_id']));
            }
        }
    }


    function reset_task_list($stratefy_task_list, $strategy){ //重新设置任务列表
        
        $stratefy_task_list = my_sort($stratefy_task_list, 'task_pri' );//按优先级排序；
        log_info("reset_task_list");
        print_2_array($stratefy_task_list);

        $task_list = array();
        foreach ($stratefy_task_list as $key => $value) {//根据任务列表次数重新组合数组
            if ($value['task_num'] >=1 ){
                for ($i=1; $i <= $value['task_num']; $i++) {
                    $task_list[]=$value;
                }
            }
        }

        //log_info("reset_task_list2222:");
        //print_2_array($task_list);

        if (1 == $strategy['is_ramd']){      
           log_info("++++do ramd_task+++++");
            $task_list = $this->ramd_task($task_list); //做随机化 
        }

        log_info("reset_task_list3333:");
        print_2_array($task_list);

        $task_list = $this->settime($task_list, $strategy['full_time']); //设置运行时间  
        log_info("++++do settime++++");
        log_info($strategy['full_time']);
        log_info("reset_task_list444:");
        print_2_array($task_list);   
        return $task_list;
    }

    //通过帐号查找对应的策略
    function get_strategy($account){  
        $strategy = pdo_fetch('SELECT b.* FROM ' . tablename('mc_members') . " a
                LEFT JOIN " . tablename('ms_strategy') . " b on a.strategy_id=b.id 
                where a.realname='".$account."'");//根据用户账号找出对应策略
        return $strategy;
    }


    function get_strategy_task_list($strategy_id){ //得到策略对应的任务表
        $task_list = pdo_fetchall('SELECT * FROM ' . tablename('ms_strategy_task_list') . " 
                where strategy_id='".$strategy_id."'");//策略id找出对应任务列表
        return $task_list;
    }


    function get_task_path($id){
        if(!empty($id)){
            $file_path = pdo_fetchcolumn ("SELECT path FROM " .tablename('xsy_resource_file') . " WHERE id=".$id);
        }
        return $file_path;
    }

    //随机同一个优先级的任务
    function ramd_task($task_list){
        $task_num = count($task_list);
        $task_pri = 121212;
        $task_pri_num_array = array();
        $task_out_array = array();
        for ($i=0; $i < $task_num; $i++) {
            if($task_pri != $task_list[$i]['task_pri']){
                $task_pri = $task_list[$i]['task_pri'];
                $task_pri_num_array[] = $i;
            };            
        }
        $task_pri_num_array[] = $task_num;   //最后的一个

        for($i = 0; $i < (count($task_pri_num_array) - 1); $i++){
            $tmp_num = $task_pri_num_array[$i+1] -  $task_pri_num_array[$i];
           
            $tmp_wash_card_array = array();
            log_info("wash_card:  $tmp_num");
            $tmp_wash_card_array = my_wash_card($tmp_num);

            for ($m=0; $m < $tmp_num ; $m++) { 
                $task_out_array[] = $task_list[ $task_pri_num_array[$i] + $tmp_wash_card_array[$m] ];
            }
        }
         return $task_out_array;
    }
 
    //给每一个任务设置运行的起始时间
    function settime($list,$full_time){ 
        $num = 0;
        foreach($list as $k => $v) {
            $num += $v['task_time']; //task_time 为每个任务运行的时间
        }

        $full_time = $full_time - $num;
        $count=count($list);

        $rand_time = my_randnum($full_time, $count);
        $rand_time[0] = 0; //设置第一个的间隔为0
        $tmp_time = $this->time;
        for ($i=0; $i < $count ; $i++) { 
            $list[$i]['time'] =  $tmp_time;
            if( $rand_time[$i] >=0 ){ //要做个判断，可能会小于0
                $tmp_time = $tmp_time +  $list[$i]['task_time'] +  $rand_time[$i];
            }
            else{
                $tmp_time = $tmp_time +  $list[$i]['task_time'];
            }
        }
        return $list;
    }
}


//------------------------------------------------------------------------------------
$get=$_GET;
$GETTASK = new GETTASK();
$GETTASK->task($get);