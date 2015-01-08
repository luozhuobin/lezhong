<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------


/**
 * CodeIgniter Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Model {
	
	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct() {
		log_message ( 'debug', "Model Class Initialized" );
	}
	
	/**
	 * __get
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string
	 * @access private
	 */
	function __get($key) {
		$CI = & get_instance ();
		return $CI->$key;
	}
	
	/**
	 * @desc 新增或者修改数据
	 * 当自增 主键不为空时，作为where条件进行修改
	 */
	public function save($table, $data) {
		$primary = $this->getPrimaryName ( $table );
		if (! empty ( $data [$primary] )) {
			$where = array ($primary => $data [$primary] );
			unset ( $data [$primary] );
			$this->db->where ( $where );
			$this->db->update ( $table, $data );
		} else {
			$data ['createtime'] = time ();
			$this->db->insert ( $table, $data );
		}
		$rows = $this->db->affected_rows ();
		return intval ( $rows );
	}
	/**
	 * @desc 获取表主键字段名称
	 */
	public function getPrimaryName($table) {
		$this->db->select ( "COLUMN_NAME" );
		$this->db->where ( "TABLE_NAME", $table );
		$this->db->where ( "CONSTRAINT_NAME", 'PRIMARY' );
		$query = $this->db->get ( "information_schema.KEY_COLUMN_USAGE" );
		$primary = $query->result_array ();
		if (! empty ( $primary [0] ) && ! empty ( $primary [0] ['COLUMN_NAME'] )) {
			$key = $primary [0] ['COLUMN_NAME'];
		} else {
			$key = false;
		}
		return $key;
	}
	/**
	 * @desc 根据主键获取数据
	 */
	public function getDataByPrimaryKey($table, $value) {
		$primaryKey = $this->getPrimaryName ( $table );
		if (! $primaryKey) {
			return false;
		}
		$this->db->where ( $primaryKey, $value );
		$query = $this->db->get ( $table );
		$data = $query->result_array ();
		$data = ! empty ( $data ) ? $data [0] : array ();
		return $data;
	}
	/**
	 * @desc 列表形式显示数据
	 */
	public function getData($table, $where, $offset = 0 , $join = array(),$select = '*',$type = array()) {
		$list = array ();
		$this->db->where ( $where );
		$this->db->select ( "count(*) as total " );
		if (! empty ( $join )) {
			foreach ( $join as $key => $value ) {
				$this->db->join ( $key, $value ,$type[$key]);
			}
		}
		$query = $this->db->get ( $table );
		$result = $query->result_array ();
		$total = ! empty ( $result ) ? intval ( $result [0] ['total'] ) : 0;
		$list ['total'] = $total;
		$per_page = $this->config->item ( 'per_page' );
		if ($total > $per_page) {
			$offset = ($total - $per_page) > $offset ? $offset : $total - $per_page;
		} else {
			$offset = 0;
		}
		$this->db->select ( $select );
		$this->db->where ( $where );
		if (! empty ( $join )) {
			foreach ( $join as $key => $value ) {
				$this->db->join ( $key, $value ,$type[$key]);
			}
		}
		$query = $this->db->get ( $table, $per_page, $offset );
		foreach ( $query->result_array () as $val ) {
			$list ['data'] [] = $val;
		}
		return $list;
	}
	/**
	 * @desc 返回对应条件的数据总条数
	 * 
	 */
	public function getDataCount($table, $where) {
		$this->db->where ( $where );
		$this->db->select ( "count(*) as total " );
		$query = $this->db->get ( $table );
		$result = $query->result_array ();
		$total = ! empty ( $result ) ? intval ( $result [0] ['total'] ) : 0;
		return $total;
	}
	/**
	 * @desc 删除某一条数据
	 */
	public function del($table, $value) {
		if (empty ( $value )) {
			return false;
		}
		$detail = $this->getDataByPrimaryKey ( $table, $value );
		if (! empty ( $detail )) {
			$primaryKey = $this->getPrimaryName ( $table );
			$this->db->delete ( $table, array ($primaryKey => $value ) );
			$affected = $this->db->affected_rows ();
			$isDel = $affected > 0 ? true : false;
		} else {
			$isDel = false;
		}
		return $isDel;
	}
}
// END Model Class

/* End of file Model.php */
/* Location: ./system/core/Model.php */