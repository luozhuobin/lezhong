<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class ServiceModel extends CI_Model {

	public $__serviceTable = "le_service_log";
	public $__wordbook =  array(
		'name'=>'',
		'number'=>'',
		'topic'=>'',
		'duration'=>"",
		'date'=>'');
    function __construct(){
        parent::__construct();
    }
	
}