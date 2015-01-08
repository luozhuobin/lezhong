<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class ActivityRegisterModel extends CI_Model {

	public $__activityRegisterTable = "le_activity_register";
	public $__wordbook =  array(
		'groupName'=>'',
		'applican'=>'',
		'phone'=>'',
		'sex'=>'',
		'conditions'=>'',
	);
    function __construct(){
        parent::__construct();
    }
	
}