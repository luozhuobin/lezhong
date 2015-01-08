<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class GroupSigninModel extends CI_Model {

	public $__groupSigninTable = "le_group_signin";
	public $__wordbook =  array(
		'groupId', 
		'participantName', 
		'section_1', 
		'section_2', 
		'section_3', 
		'section_4', 
		'section_5', 
		'section_6'
	);
    function __construct(){
        parent::__construct();
    }
	
}