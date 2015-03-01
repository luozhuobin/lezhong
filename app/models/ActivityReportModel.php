<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class ActivityReportModel extends CI_Model {

	public $__activityReportTable = "le_activity_report";
	public $__wordbook =  array(
		'groupName'=>'',
		'content'=>'');
    function __construct(){
        parent::__construct();
    }
	
}