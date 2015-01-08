<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class GroupReportModel extends CI_Model {

	public $__groupReportTable = "le_group_report";
	public $__wordbook =  array(
		'groupId', 
		'preparatoryWork', 
		'preparatoryWorkOpinion', 
		'targetCompletion', 
		'targetCompletionOption', 
		'reportContent', 
		'reportContentOption', 
		'personnelArrangement', 
		'personnelArrangementOption', 
		'staffExpression', 
		'staffExpressionOption', 
		'targetExpression', 
		'targetExpressionOption', 
		'funds', 
		'fundsOption', 
		'other', 
		'otherOption', 
		'followUp', 
		'followUpOption'
	);
    function __construct(){
        parent::__construct();
    }
	
}