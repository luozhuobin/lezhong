<?php
/**
 * @desc 义工登记相关模型
 * @author luobin
 *
 */
class VolunteerModel extends CI_Model {

	public $__volunteerTable = "le_volunteer";
	public $__wordbook =  array(
			'name'=>'',
			'sex'=>'',
			'birthday'=>'',
			'address'=>'',
			'phone'=>"",
			'marriage'=>'',
			'workStatus'=>'',
			'familyEconomic'=>'',
			'source'=>'');
    function __construct(){
        parent::__construct();
    }
}