<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class ActivitySuggestionModel extends CI_Model {

	public $__activitySuggestionTable = "le_activity_suggestion";
	public $__wordbook =  array(
			'groupName'=>'',
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