<?php
/**
 * @desc 个案记录相关模型
 * @author luobin
 *
 */
class CasesProcessModel extends CI_Model {
	public $__casesProcessTable = "le_cases_process";
	public $__wordbook = array(
			'serialNumber', 
			'socialWorker', 
			'date', 
			'thosePresent', 
			'address', 
			'target', 
			'languageLog', 
			'nonLanguageExpression', 
			'workersFeel', 
			'overallFeeling', 
			'plan', 
			'opinionSupervision', 
		);
	public function __construct() {
		parent::__construct ();
	}
}