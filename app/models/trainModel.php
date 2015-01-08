<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class TrainModel extends CI_Model {

	public $__trainTable = "le_train_log";
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