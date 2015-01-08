<?php
/**
 * @desc 用户登录退出相关模型
 * @author luobin
 *
 */
class CasesModel extends CI_Model {

	public $__casesTable = "le_cases";
	public $__wordbook = array(
			'serialNumber'=>'',
			'name'=>'',
			'alias'=>'',
			'sex'=>'',
			'birthday'=>'',
			'address'=>'',
			'phone'=>'',
			'marriage'=>'',
			'occupationStatus'=>'',
			'economics'=>'',
			'department'=>'',
			'socialServiceRecords'=>'',
			'patientSummary'=>'',
			'causesOfDisability'=>'',
			'serviceNeeds'=>'',
			'familyBackground'=>'',
			'readme'=>''
	);
    function __construct(){
        parent::__construct();
    }
	    
}