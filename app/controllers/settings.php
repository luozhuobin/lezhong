<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 后台管理相关类
 * @author zhuobin.luo
 * @since 2014-05-01
 */
class Settings extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		##载入数据模型
		$this->load->model ( "SettingsModel", "Settings" );
	}
	/**
	 * @desc 成员列表（包括管理员）
	 */
	public function show() {
		$group = $this->Settings->memberGroup ();
		$data = array ();
		$data ['memberGroup'] = $group;
		$offset = $this->input->get ( 'per_page', TRUE );
		$memberList = $this->Settings->memberList ( 0, '', $offset );
		$data ['memberList'] = $memberList ['data'];
		$links = $this->getPageList ( $memberList ['total'], $offset );
		$data ['links'] = $links;
		$this->load->view ( 'settings-show', $data );
	}
	/**
	 * @desc 成员资料详情
	 */
	public function detail() {
		$this->load->view ( 'rounds-detail' );
	}
	/**
	 * @desc 新用户注册
	 */
	public function edit() {
		$memberId = $this->input->get ( "id", TRUE );
		$data = array ();
		if (! empty ( $memberId )) {
			$detail = $this->Settings->getMemberDetail ( $memberId );
			if (! empty ( $detail )) {
				$data = $detail [0];
				$data ['title'] = "修改资料";
				$data['isUpdate'] = 1;
			}
		} else {
			$data['isUpdate'] = 2;
			$data ['title'] = "新增用户";
		}
		$group = $this->Settings->memberGroup ();
		$data ['memberGroup'] = $group;
		$this->load->view ( 'settings-edit', $data );
	}
	/**
	 * @desc 保存用户数据
	 */
	public function saveInfo() {
		if (! empty ( $_POST )) {
			$groupId = intval ( $this->session->userdata ( 'groupId' ) );
			if($groupId > 3){
				$this->jsonCallback ( "2", "权限不足" );
			}
			$groupId = intval ( $this->input->post ( "groupId", TRUE ) );
			$memberId = intval ( $this->input->post ( "memberId", TRUE ) );
			$username = $this->input->post ( 'username', TRUE );
			$password = $this->input->post ( 'password', TRUE );
			$defaultPassword = $this->input->post ( "defaultPassowrd", TRUE );
			$confirm_password = $this->input->post ( 'confirm_password', TRUE );
			$truename = $this->input->post ( 'truename', TRUE );
			$telephone = $this->input->post ( 'telephone', TRUE );
			$address = $this->input->post ( 'address', TRUE );
			$status = $this->input->post ( "status", TRUE );
			##用户名验证
			if (! filter_var ( $username, FILTER_VALIDATE_EMAIL )) {
				$this->jsonCallback ( "username", "无效的邮箱帐号" );
			}
			##密码至少为6位
			if (mb_strlen ( $password ) < 6) {
				$this->jsonCallback ( "password", "请输入6位以上的密码" );
			}
			if ($password != $confirm_password) {
				$this->jsonCallback ( "confirm_password", "两次输入的密码不一致" );
			}
			if (empty ( $memberId )) {
				##是否已被注册
				$isExist = $this->Settings->memberList ( 0, $username );
				if (! empty ( $isExist ) && $isExist ['total'] > 0) {
					$this->jsonCallback ( "username", "该帐号已被注册" );
				}
				
			} 
			
			##分组
			if (intval ( $groupId ) == 0) {
				$this->jsonCallback ( "groupId", "请选择分组" );
			}
			##真实姓名
			if (empty ( $truename )) {
				$this->jsonCallback ( "truename", "请输入真实姓名" );
			}
			##手机号码
			if (! preg_match ( "/^(13[0-9]|147|15[0-9]|18[0|1|2|5|6|7|8|9])\d{8}$/", $telephone )) {
				$this->jsonCallback ( "telephone", "无效的手机号码" );
			}
			##联系地址
			if (empty ( $address )) {
				$this->jsonCallback ( "address", "请输入地址" );
			}
			$data = array ("groupId" => $groupId, "truename" => $truename, "telephone" => $telephone, "address" => $address, "status" => $status );
			if (empty ( $memberId )) {
				$data ['username'] = $username;
				$data ['password'] = md5 ( $password );
				$data ['createtime'] = time ();
			} else {
				##判断用户是否有修改密码
				$data ['password'] = $password;
			}
			$isSuccess = $this->Settings->saveMemberInfo ( $data, $memberId );
			if ($isSuccess) {
				$this->jsonCallback ( "1", "保存成功" );
			} else {
				$this->jsonCallback ( "1", "操作成功" );
			}
		}
	}
	/**
	 * @desc 删除用户
	 */
	public function delMember() {
		$memberId = $this->input->post ( "memberId", TRUE );
		if (! empty ( $memberId )) {
			
			$isDel = $this->Settings->delMember ( $memberId );
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