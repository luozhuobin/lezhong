<?php
/**
 * @desc 个案记录相关模型
 * @author luobin
 *
 */
class CasesSuggestModel extends CI_Model {
	public $__casesSuggestTable = "le_cases_suggest";
	public $__wordbook = array(
			'casesId', 
			'followUp', 
			'followUpRemark', 
			'crisis', 
			'crisisRemark', 
			'urgent', 
			'urgentRemark', 
			'otherReasons', 
			'isAccept', 
			'isAcceptRemark', 
			'overallAim', 
			'goals', 
			'servicePlan', 
			'difficulty', 
			'socialWorker', 
			'date', 
			'isNeed', 
			'isNeedRemark', 
			'other', 
			'opinionSupervision', 
			'supervisorSign', 
			'signDate', 
			'inspectionBodies'
		);
	public function __construct() {
		parent::__construct ();
	}
}