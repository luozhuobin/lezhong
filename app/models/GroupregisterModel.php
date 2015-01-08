<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class GroupRegisterModel extends CI_Model {

	public $__groupRegisterTable = "le_group_register";
	public $__wordbook =  array(
		'groupId'=>'',
		'applican'=>'',
		'phone'=>'',
		'sex'=>'',
		'conditions'=>'',
		'followUp'=>'',
	);
    function __construct(){
        parent::__construct();
    }
	
}