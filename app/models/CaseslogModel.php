<?php
/**
 * @desc 个案记录相关模型
 * @author luobin
 *
 */
class CaseslogModel extends CI_Model {
	public $__caseslogTable = "le_cases_log";
	public $__wordbook = array(
			'serialNumber'=>'',
			'name'=>'',
			'date'=>'',
			'contactWay'=>'',
			'relationship'=>'',
			'objective'=>'',
			'content'=>'',
			'nextTalk'=>'',
			'nextMeetingArrangements'=>''
		);
	public function __construct() {
		parent::__construct ();
	}
}