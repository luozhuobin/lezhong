<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 用户登录
 * @author zhuobin.luo
 * @since 2014-05-02
 */
class Admin extends CI_Controller {
	
	/**
	 * @desc  登录界面
	 */
	public function index() {
		##已登录跳转到首页
		$isLogin = $this->doCheck ();
		if ($isLogin) {
			header ( "Location:/?c=cases&m=show" );
			exit ();
		}
		$this->load->view ( 'login_new' );
	}
	/**
	 * @desc  实现登录
	 */
	public function doLogin() {
		$username = $this->input->post ( 'username', TRUE );
		$password = $this->input->post ( "password", TRUE );
		if (empty ( $username ) || ! filter_var ( $username, FILTER_VALIDATE_EMAIL )) {
			$this->jsonCallback ( "username", "不存在该用户" );
		}
		$password = md5 ( $password );
		##载入数据模型
		$this->load->model ( "AdminModel", "Admin" );
		$this->load->model ( "LogModel", "LoginLog" );
		$member = $this->Admin->getMemberByPassword ( $username, $password );
		if (! empty ( $member )) {
			$this->session->set_userdata ( 'memberId', $member ['memberId'] );
			$code = $this->getCheckCode ( $member ['memberId'], $member ['password'] );
			$this->session->set_userdata ( 'checkCode', $code );
			$this->session->set_userdata ( 'truename', $member ['truename'] );
			$this->session->set_userdata ( 'groupId', $member ['groupId'] );
			##登录日志记录
			$status = 1;
			$code = "1";
			$msg = "登录成功，正在跳转，请稍候。。。";
		} else {
			##错误登录日志记录
			$status = 2;
			$code = "password";
			$msg = "登录失败，请重新登录。。。";
		}
		$this->LoginLog->addLoginLog ( $username, $password, $status );
		$this->jsonCallback ( $code, $msg );
	}
	
	/**
	 * @desc 用户退出
	 */
	public function logout() {
		$this->session->unset_userdata ( 'userId' );
		$this->session->unset_userdata ( 'code' );
		$this->session->sess_destroy ();
		header ( "Location:/" );
		exit ();
	}
}