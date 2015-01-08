<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 小组报名表相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-14
 */
class Groupregister extends CI_Controller {
	private static $__sex = array ("男", "女", "未知" );
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'GroupregisterModel', "groupregister" );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$offset = $this->input->get ( 'per_page', TRUE );
		$list = $this->groupregister->getData ( $this->groupregister->__groupRegisterTable, array (), $offset );
		$data ['groupregister'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->groupregister->getPrimaryName ( $this->groupregister->__groupRegisterTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'groupregister-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'groupregister-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->groupregister->getPrimaryName ( $this->groupregister->__groupRegisterTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->groupregister->getDataByPrimaryKey ( $this->groupregister->__groupRegisterTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑报名者资料";
		} else {
			$data ['title'] = "新增报名";
		}
		$data ['__sex'] = self::$__sex;
		$this->load->view ( 'groupregister-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->groupregister->__groupRegisterTable, $this->groupregister->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'groupregister-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			$isSuccess = $this->groupregister->save ( $this->groupregister->__groupRegisterTable, $post );
			if ($isSuccess > 0) {
				$this->jsonCallback ( "1", "保存成功" );
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
			$isDel = $this->groupregister->del ( $this->groupregister->__groupRegisterTable, $value );
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