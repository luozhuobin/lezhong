<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 小组报告书相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-14
 */
class Groupreport extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'GroupReportModel', "groupReport" );
		$this->load->model ( 'GroupModel', "group" );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$offset = $this->input->get ( 'per_page', TRUE );
		$join = array ($this->group->__groupTable => $this->group->__groupTable . '.groupId = ' . $this->groupReport->__groupReportTable . '.groupId' );
		$list = $this->groupReport->getData ( $this->groupReport->__groupReportTable, array (), $offset ,$join);
		$data ['groupreport'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->groupReport->getPrimaryName ( $this->groupReport->__groupReportTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'groupreport-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'groupreport-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->groupReport->getPrimaryName ( $this->groupReport->__groupReportTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->groupReport->getDataByPrimaryKey ( $this->groupReport->__groupReportTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑小组报告书";
		} else {
			$data ['title'] = "新增小组报告书";
		}
			##小组列表
		$this->load->model ( 'GroupModel', "group" );
		$list = $this->group->getData ( $this->group->__groupTable, array () );
		$group = $list ['total'] > 0 ? $list ['data'] : array ();
		$data ['groupList'] = $group;
		$this->load->view ( 'groupreport-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->groupReport->__groupReportTable, $this->groupReport->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'groupreport-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			$isSuccess = $this->groupReport->save ( $this->groupReport->__groupReportTable, $post );
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
			$isDel = $this->groupReport->del ( $this->groupReport->__groupReportTable, $value );
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