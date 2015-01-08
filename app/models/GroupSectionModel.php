<?php
/**
 * @desc 义工培训相关模型
 * @author luobin
 *
 */
class GroupSectionModel extends CI_Model {

	public $__groupSectionTable = "le_group_Section";
	public $__wordbook =  array(
		'groupId', 'sectionName', 'date'
	);
    function __construct(){
        parent::__construct();
    }
	
}