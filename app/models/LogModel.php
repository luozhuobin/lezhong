<?php
/**
 * @desc 用户登录退出相关模型
 * @author luobin
 *
 */
class LogModel extends CI_Model {

	private static $__LoginLogTable = "le_member_login_log";
    function __construct(){
        parent::__construct();
    }
    
	/**
	 * @desc 登录日志
	 */
    public function addLoginLog($username,$password,$status = 1){
    	$data = array(
    		"username"=>$username,
    		"password"=>$password,
    		"ip"=>$this->input->ip_address(),
    		"status"=>$status,
    		"date"=>date("Y-m-d"),
    		"createtime"=>time()
    	);
    	$this->db->insert(self::$__LoginLogTable,$data);
    }
}