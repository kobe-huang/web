<?php
$t=time();
echo($t . "<br>");
date_default_timezone_set("Asia/Shanghai");
echo(date("Y-m-d \nh:i:sa",$t));

class WeEngine {
	
	private $account = null;
	
	private $modules = array();
	
	public $keyword = array();
	
	public $message = array();



class task_list {
	public $task_stratage_info = array()
	public $strategy_info      = array();

	public $task_list = array();

	public function __construct($task_list, $strategy) {
		$this->task_stratage_info = $task_list;
		$this->strategy_info = $strategy;
	}

	public function create_task_list(){
		//step1  排出任务序列,可以加上随机操作
		$task_stratage_num = count($this->task_stratage_info);
		for ($i=0; $i < $task_stratage_num ; $i++) { 

			# code...
		}
		//step2  添加上时间
		//step3  生成任务列表

	}



}