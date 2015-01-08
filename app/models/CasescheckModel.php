<?php
/**
 * @desc 用户登录退出相关模型
 * @author luobin
 *
 */
class CasescheckModel extends CI_Model {

	public $__casescheckTable = "le_cases_check";
	public $__wordbook = array(
			'serialNumber'=>'',
			'name'=>'',
			'time'=>'',
			'serviceNeeds'=>'',
			'goals'=>'',
			'abstract'=>'',
			'presentSituation'=>''
		);
    function __construct(){
        parent::__construct();
    }
}