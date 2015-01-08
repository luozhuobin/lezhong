<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class ActivityModel extends CI_Model {

	public $__activityTable = "le_activity";
	public $__wordbook =  array(
		'name'=>'',
		'target'=>'',
		'quota'=>"",
		'co_organizer'=>'',
		'venue'=>'',
		'type'=>'',
		'section'=>"",
		'date'=>'',
		'time'=>'',
		'content'=>'',
		'material'=>'',
		'servicesSuppliesExpenses'=>'',
		'transportationExpenses'=>'',
		'promotionExpenses'=>'',
		'volunteerSubsidies'=>'',
		'generalBudget'=>'',
		'support'=>'',
		'purpose'=>'',
		'subjectRecruitment'=>'',
		'volunteersRecruitment'=>'',
		'manning'=>'',
		'divisionOfWork'=>'',
		'riskAssessment'=>'',
		'evaluationMethod'=>'',
		'opinionSupervision'=>'');
    function __construct(){
        parent::__construct();
    }
	
}