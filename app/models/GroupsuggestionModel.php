<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class GroupsuggestionModel extends CI_Model {

	public $__groupsuggestionTable = "le_group_suggestion";
	public $__wordbook =  array(
			'groupId'=>'',
			'targetsAchievement'=>'',
			'timeArrangement'=>'',
			'form'=>'',
			'site'=>'',
			'content'=>'',
			'jobPerformance'=>'',
			'workAttitude'=>'',
			'involvement'=>'',
			'packageProgramme'=>'',
			'advices'=>''
	);
    function __construct(){
        parent::__construct();
    }
	
}