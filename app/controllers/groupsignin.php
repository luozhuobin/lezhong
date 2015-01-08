<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 小组报名表相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-14
 */
class Groupsignin extends CI_Controller {
	private static $__attendance = array ("出席", "缺席", "迟到" );
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'GroupsigninModel', "groupSignin" );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$offset = $this->input->get ( 'per_page', TRUE );
		$this->load->model ( 'GroupModel', "group" );
		$join = array($this->group->__groupTable=>$this->groupSignin->__groupSigninTable.".groupId = ".$this->group->__groupTable.".groupId");
		$type = array($this->group->__groupTable=>"LEFT");
		$list = $this->groupSignin->getData ( $this->groupSignin->__groupSigninTable, array (), $offset ,$join,'*',$type);
		$data ['groupSignin'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->groupSignin->getPrimaryName ( $this->groupSignin->__groupSigninTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'groupSignin-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'groupSignin-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->groupSignin->getPrimaryName ( $this->groupSignin->__groupSigninTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->groupSignin->getDataByPrimaryKey ( $this->groupSignin->__groupSigninTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑报名者资料";
		} else {
			$data ['title'] = "新增报名";
		}
		$data ['__attendance'] = self::$__attendance;
		##小组列表
		$this->load->model ( 'GroupModel', "group" );
		$list = $this->group->getData ( $this->group->__groupTable, array () ,0,array(),'groupId,name');
		$group = $list ['total'] > 0 ? $list ['data'] : array ();
		$data ['groupList'] = $group;
		$this->load->view ( 'groupSignin-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->groupSignin->__groupSigninTable, $this->groupSignin->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'groupSignin-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			if (empty ( $post ['groupId'] )) {
				$this->jsonCallback ( "groupName", "请选择小组" );
			}
			if (empty ( $post ['participantName'] )) {
				$this->jsonCallback ( "participantName", "请输入参加者姓名" );
			}
			$where = array ("groupId" => $post ['groupId'], "participantName" => $post ['participantName'] );
			$this->load->model ( 'GroupSigninModel', "groupSignin" );
			$signin = $this->groupSignin->getData ( $this->groupSignin->__groupSigninTable, $where );
			if ($signin ['total'] > 0) {
				$post ['signinId'] = $signin ['data'] [0] ['signinId'];
			}
			$isSuccess = $this->groupSignin->save ( $this->groupSignin->__groupSigninTable, $post );
			if ($isSuccess > 0) {
				$this->jsonCallback ( "1", "保存成功" );
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
			$isDel = $this->groupSignin->del ( $this->groupSignin->__groupSigninTable, $value );
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