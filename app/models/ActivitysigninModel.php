<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class ActivitySigninModel extends CI_Model {

	public $__activitySigninTable = "le_activity_signin";
	public $__wordbook =  array(
		'groupName'=>'',
		'section'=>'',
		'participantName'=>'',
		'attendance'=>''
	);
    function __construct(){
        parent::__construct();
    }
	
}