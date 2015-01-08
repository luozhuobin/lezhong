<?php
/**
 * @desc 设置相关模型
 * @author luobin
 *
 */
class SettingsModel extends CI_Model {

	private static $__GroupTable = "le_member_group";
	private static $__MemberTable = "le_member_list";
	##帐号使用 状态
	static $__Status = array(
						1=>"<font color='green'>正常</font>",
						2=>"<font color='red'>注销</font>")
						;
    function __construct(){
        parent::__construct();
    }
    
    /**
     * @desc 获取成员分组列表
     */
    public function memberGroup(){
    	$list = array();
    	$query = $this->db->get(self::$__GroupTable);
    	foreach($query->result_array() as $row){
    		$list[] = $row;
    	}
    	return $list;
    }
    /**
     * @desc 获取成员列表 可通过分组id或者用户名进行搜索
     * @param groupId 分组id
     * @param username 用户名
     */
    public function memberList($groupId = 0, $username = '',$offset = 0){
    	$list = array();
    	if($groupId >0 ){
    		$this->db->where("groupId",$groupId);
    	}
    	if(!empty($username)){
    		$this->db->where("username",$username);
    	}
    	$this->db->select("count(*) as total ");
    	$query = $this->db->get(self::$__MemberTable);
    	$result = $query->result_array();
    	$total = !empty($result)?intval($result[0]['total']):0;
    	$list['total'] = $total;
    	$per_page = $this->config->item('per_page');
    	if($total>$per_page){
    		$offset = ($total-$per_page)>$offset?$offset:$total-$per_page;
    	}else{
    		$offset = 0;
    	}
    	$this->db->select("*");
    	if($groupId >0 ){
    		$this->db->where("groupId",$groupId);
    	}
    	if(!empty($username)){
    		$this->db->where("username",$username);
    	}
    	$this->db->join(self::$__GroupTable,self::$__GroupTable.'.groupId = '.self::$__MemberTable.'.groupId');
    	$query = $this->db->get(self::$__MemberTable,$per_page,$offset);
    	foreach($query->result_array() as $val){
    		$val['status'] = self::$__Status[$val['status']];
    		$val['createtime'] = date("Y-m-d H:i:s",$val['createtime']);
    		$list['data'][] = $val;
    	}
    	return $list;
    }
    /**
     * @desc 获取某一成员的详细信息
     */
    public function getMemberDetail($memberId){
    	if(empty($memberId)){
    		return false;
    	}
    	$this->db->select("*");
    	$this->db->join(self::$__GroupTable,self::$__GroupTable.'.groupId = '.self::$__MemberTable.'.groupId');
    	$this->db->where("memberId",$memberId);
    	$query = $this->db->get(self::$__MemberTable);
    	$detail = $query->result_array();
    	return $detail;
    }
    /**
     * @desc 新增或者修改成员资料
     */
    public function saveMemberInfo($data,$memberId = 0){
    	if($memberId > 0){
    		$this->db->where("memberId",$memberId);
    		$this->db->update(self::$__MemberTable,$data);
    	}else{
    		$this->db->insert(self::$__MemberTable,$data);
    	}
    	$rows = $this->db->affected_rows();
    	return $rows;
    }
    /**
     * @desc 删除用户
     */
    public function delMember($memberId){
    	if(empty($memberId)){
    		return false;
    	}
    	$detail = $this->getMemberDetail($memberId);
		if(!empty($detail)){
			$this->db->delete(self::$__MemberTable, array('memberId' => $memberId)); 
			$affected = $this->db->affected_rows();
			$isDel = $affected>0?true:false;
		}else{
			$isDel = false;
		}
    	return $isDel;
    }
}