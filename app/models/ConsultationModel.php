<?php
/**
 * @desc 日常咨询相关模型
 * @author luobin
 *
 */
class ConsultationModel extends CI_Model {

	public $__consultationTable = "le_daily_consultation";
	public $__wordbook =  array(
		'date'=>'',
		'contact'=>"",
		'content'=>'',
		'follow'=>'',
		'opinionSupervision'=>'');
    function __construct(){
        parent::__construct();
    }
	
}