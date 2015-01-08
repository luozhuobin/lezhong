<?php
/**
 * @desc 用户登录退出相关模型
 * @author luobin
 *
 */
class CasecheckModel extends CI_Model {

	public $__casecheckTable = "le_case_check";
	public $__wordbook = array(
		'time'=>'',
		'serviceNeeds'=>'',
		'goals'=>'',
		'abstract'=>'',
		'presentSituation'=>'',
		'isClose'=>'',
		'opinionSupervision'=>''
	);
    function __construct(){
        parent::__construct();
    }
	    
}