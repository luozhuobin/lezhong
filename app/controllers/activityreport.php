<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 活动报告书相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-14
 */
class Activityreport extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'ActivityReportModel', "activityReport" );
		$this->load->model ( 'ActivityModel', "activity" );
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
		$join = array ($this->activity->__activityTable => $this->activity->__activityTable . '.activityId = ' . $this->activityReport->__activityReportTable . '.activityId' );
		$list = $this->activityReport->getData ( $this->activityReport->__activityReportTable, $where, $offset ,$join);
		$data ['activityreport'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->activityReport->getPrimaryName ( $this->activityReport->__activityReportTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'activityreport-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'activityreport-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->activityReport->getPrimaryName ( $this->activityReport->__activityReportTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->activityReport->getDataByPrimaryKey ( $this->activityReport->__activityReportTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑活动报告书";
		} else {
			$data ['title'] = "新增活动报告书";
		}
		##活动列表
		$this->load->model ( 'ActivityModel', "activity" );
		$list = $this->activity->getData ( $this->activity->__activityTable, array () );
		$activity = $list ['total'] > 0 ? $list ['data'] : array ();
		$data ['activityList'] = $activity;
		$this->load->view ( 'activityreport-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->activityReport->__activityReportTable, $this->activityReport->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'activityreport-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			$isSuccess = $this->activityReport->save ( $this->activityReport->__activityReportTable, $post );
			if ($isSuccess > 0) {
				$this->jsonCallback ( "1", "保存成功" ,array("opt"=>$isSuccess));
			} else {
				$this->jsonCallback ( "1", "操作成功" );
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
			$isDel = $this->activityReport->del ( $this->activityReport->__activityReportTable, $value );
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