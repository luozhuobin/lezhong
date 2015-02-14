<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 活动报名表相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-14
 */
class Activitysuggestion extends CI_Controller {
	private static $__attendance = array ("出席", "缺席", "迟到" );
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'ActivitySuggestionModel', "activitySuggestion" );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$offset = $this->input->get ( 'per_page', TRUE );
		$serialNumber = urldecode($this->input->get('serialNumber',TRUE));
		$name = urldecode($this->input->get('name',TRUE));
		if(!empty($serialNumber)){
			$where['serialNumber'] = $serialNumber;
		}
		if(!empty($name)){
			$where['name'] = $name;
		}
		$this->load->model ( 'ActivityModel', "activity" );
		
		$join = array($this->activity->__activityTable=>$this->activitySuggestion->__activitySuggestionTable.".activityId = ".$this->activity->__activityTable.".activityId");
		
		$type = array($this->activity->__activityTable=>"LEFT");
		$select = $this->activitySuggestion->__activitySuggestionTable.'.*,'.$this->activity->__activityTable.'.name,'.$this->activity->__activityTable.'.serialNumber';
		$list = $this->activitySuggestion->getData ( $this->activitySuggestion->__activitySuggestionTable, $where, $offset ,$join,$select,$type);
		$data ['activitySuggestion'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->activitySuggestion->getPrimaryName ( $this->activitySuggestion->__activitySuggestionTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'activitySuggestion-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'activitySuggestion-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->activitySuggestion->getPrimaryName ( $this->activitySuggestion->__activitySuggestionTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->activitySuggestion->getDataByPrimaryKey ( $this->activitySuggestion->__activitySuggestionTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑报名者资料";
		} else {
			$data ['title'] = "新增报名";
		}
		$data ['__attendance'] = self::$__attendance;
		##活动列表
		$this->load->model ( 'activityModel', "activity" );
		$list = $this->activity->getData ( $this->activity->__activityTable, array () );
		$activity = $list ['total'] > 0 ? $list ['data'] : array ();
		$data ['activityList'] = $activity;
		$this->load->view ( 'activitySuggestion-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->activitySuggestion->__activitySuggestionTable, $this->activitySuggestion->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'activitySuggestion-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			$isSuccess = $this->activitySuggestion->save ( $this->activitySuggestion->__activitySuggestionTable, $post );
			if ($isSuccess > 0) {
				$this->jsonCallback ( "1", "保存成功",array("opt"=>$isSuccess) );
			} else {
				$this->jsonCallback ( "2", "保存失败" );
			}
		} else {
			$this->jsonCallback ( "3", "表单数据为空" );
		}
	}
	
	/**
	 * @desc 删除某一条记录
	 */
	public function del() {
		$value = $this->input->post ( "value", TRUE );
		if (! empty ( $value )) {
			$isDel = $this->activitySuggestion->del ( $this->activitySuggestion->__activitySuggestionTable, $value );
			if ($isDel) {
				$code = "1";
				$msg = "删除失败，请刷新页面重试。。";
			} else {
				$code = "2";
				$msg = "删除失败，请刷新页面重试。。";
			}
		} else {
			$code = "2";
			$msg = "删除失败，请刷新页面重试。。";
		}
		$this->jsonCallback ( $code, $msg );
	}
}