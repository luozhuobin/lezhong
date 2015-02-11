<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 小组报名表相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-14
 */
class Groupsuggestion extends CI_Controller {
	private static $__attendance = array ("出席", "缺席", "迟到" );
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'GroupsuggestionModel', "groupsuggestion" );
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
		$this->load->model ( 'GroupModel', "group" );
		$join = array($this->group->__groupTable=>$this->groupsuggestion->__groupsuggestionTable.".groupId = ".$this->group->__groupTable.".groupId");
		$type = array($this->group->__groupTable=>"LEFT");
		$select = $this->groupsuggestion->__groupsuggestionTable.'.*,'.$this->group->__groupTable.'.name,'.$this->group->__groupTable.'.serialNumber';
		$list = $this->groupsuggestion->getData ( $this->groupsuggestion->__groupsuggestionTable, $where, $offset ,$join,$select,$type);
		$data ['groupsuggestion'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->groupsuggestion->getPrimaryName ( $this->groupsuggestion->__groupsuggestionTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'groupsuggestion-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'groupsuggestion-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->groupsuggestion->getPrimaryName ( $this->groupsuggestion->__groupsuggestionTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->groupsuggestion->getDataByPrimaryKey ( $this->groupsuggestion->__groupsuggestionTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑报名者资料";
		} else {
			$data ['title'] = "新增报名";
		}
		$data ['__attendance'] = self::$__attendance;
		##小组列表
		$this->load->model ( 'GroupModel', "group" );
		$list = $this->group->getData ( $this->group->__groupTable, array () );
		$group = $list ['total'] > 0 ? $list ['data'] : array ();
		$data ['groupList'] = $group;
		$this->load->view ( 'groupsuggestion-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->groupsuggestion->__groupsuggestionTable, $this->groupsuggestion->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'groupsuggestion-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			$isSuccess = $this->groupsuggestion->save ( $this->groupsuggestion->__groupsuggestionTable, $post );
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
			$isDel = $this->groupsuggestion->del ( $this->groupsuggestion->__groupsuggestionTable, $value );
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