<?php
/**
 * @desc 用户登录退出相关模型
 * @author luobin
 *
 */
class AdminModel extends CI_Model {
	
	private static $__GroupTable = "le_member_group";
	private static $__MemberTable = "le_member_list";
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * @desc 查找用户
	 * @param username 用户名
	 * @param password 密码
	 */
	public function getMemberByPassword($username, $password) {
		if (empty ( $username ) || empty ( $password )) {
			return array ();
		}
		$this->db->where ( "username", $username );
		$this->db->where ( "password", $password );
		$this->db->where ( "status", 1 );
		$query = $this->db->get ( self::$__MemberTable );
		$member = array ();
		$member = $query->result_array ();
		return $member [0];
	}
	/**
	 * @desc 查找用户
	 * @param memberId 用户id
	 */
	public function getMemberById($memberId) {
		if (empty ( $memberId )) {
			return array ();
		}
		$this->db->where ( "memberId", $memberId );
		$query = $this->db->get ( self::$__MemberTable );
		$member = array ();
		$member = $query->result_array ();
		return $member [0];
	}
	
	/**
	 * @desc 获取用户权重
	 */
	public function getMemberWeights($memberId) {
		$this->db->select ( '*' );
		$this->db->from ( 'le_member_list' );
		$this->db->join ( 'le_member_group', 'le_member_list.groupId = le_member_group.groupId' );
		$this->db->where('memberId', $memberId);
		$query = $this->db->get ();
		$row = $query->result_array();
		$row = !empty($row)?$row[0]:array();
		return $row;
	}
}