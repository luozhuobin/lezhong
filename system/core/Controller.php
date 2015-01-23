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
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {
	
	private static $instance;
	public static $__parting = '{lezhong}';
	/**
	 * Constructor
	 */
	public function __construct() {
		self::$instance = & $this;
		
		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach ( is_loaded () as $var => $class ) {
			$this->$var = & load_class ( $class );
		}
		
		$this->load = & load_class ( 'Loader', 'core' );
		
		$this->load->initialize ();
		log_message ( 'debug', "Controller Class Initialized" );
		##登录状态判断
		$c = $this->input->get ( "c", true );
		$m = $this->input->get ( "m", true );
		if (! empty ( $c ) && $c != 'admin' && ! empty ( $m ) && ! in_array ( $m, array ("index", "doLogin" ) )) {
			$isLogin = $this->doCheck ();
			if (! $isLogin) {
				$this->session->sess_destroy ();
				header ( "Location:/" );
				exit ();
			}
		}
		
	}
	
	public static function &get_instance() {
		return self::$instance;
	}
	
	public function jsonCallback($code, $msg, $data = array()) {
		echo json_encode ( array ("code" => $code, "msg" => $msg, "data" => $data ) );
		exit ();
	}
	/**
	 * @desc 获取登录校验码
	 */
	public function getCheckCode($memberId, $password) {
		$encryptionKey = $this->config->item ( 'encryption_key' );
		$code = md5 ( $encryptionKey . $memberId . $password . $encryptionKey );
		return $code;
	}
	/**
	 * @desc 校验cookie
	 */
	public function doCheck() {
		$memberId = intval ( $this->session->userdata ( 'memberId' ) );
		$code = $this->session->userdata ( 'checkCode' );
		if ($memberId != 0 && ! empty ( $code )) {
			$this->load->model ( "AdminModel", "Admin" );
			$member = $this->Admin->getMemberById ( $memberId );
			if (! empty ( $member )) {
				$checkCode = $this->getCheckCode ( $memberId, $member ['password'] );
				if ($checkCode === $code) {
					$isLogin = true;
				} else {
					$isLogin = false;
				}
			} else {
				$isLogin = false;
			}
		} else {
			$isLogin = false;
		}
		return $isLogin;
	}
	/**
	 * @desc 返回分页
	 */
	public function getPageList($total, $perpage) {
		$this->load->library ( 'pagination' );
		$config ['base_url'] = "/?c=" . $this->input->get ( "c", TRUE ) . "&m=" . $this->input->get ( "m", TRUE );
		$config ['total_rows'] = $total;
		$config ['per_page'] = $perpage;
		$config ['anchor_class'] = 'class="number"';
		$config ['cur_tag_open'] = '<a href="javascript:;" class="number current">';
		$config ['cur_tag_close'] = '</a>';
		$config ['first_link'] = '首页';
		$config ['prev_link'] = '上一页';
		$config ['next_link'] = '下一页';
		$config ['last_link'] = '尾页';
		$config ['uri_segment'] = '4'; //设为页面的参数，如果不添加这个参数分页用不了 
		$this->pagination->initialize ( $config );
		$links = $this->pagination->create_links ();
		return $links;
	}
	
	/**
	 * @desc 模板导入
	 * @desc table 数据表名称
	 * @desc wordbook 数据字典
	 */
	public function xlsImport($table, $wordbook) {
		$data = array ();
		if (! empty ( $_FILES )) {
			$config ['upload_path'] = $this->config->item ( 'upload_path' );
			$config ['allowed_types'] = $this->config->item ( 'allowed_types' );
			$config ['max_size'] = $this->config->item ( 'max_size' );
			$config ['max_width'] = $this->config->item ( 'max_width' );
			$config ['max_height'] = $this->config->item ( 'max_height' );
			$config ['file_name'] = $this->config->item ( 'file_name' );
			$this->load->library ( 'upload', $config );
			$filrUrl = $this->upload->do_upload ( 'stencil' );
			if (! $filrUrl) {
				$data = array ('msg' => "文件上传失败，请重试。。。", "code" => "error" );
			} else {
				include 'system/libraries/Spreadsheet_Excel_Reader.php';
				$data = new Spreadsheet_Excel_Reader ();
				$data->setOutputEncoding ( 'UTF-8' );
				$data->read ( $filrUrl );
				##去掉头部 中文字
				array_shift ( $data->sheets [0] ["cells"] );
				$tmp = array ();
				foreach ( $data->sheets [0] ['cells'] as $key => $value ) {
					if ($key % 2 != 0) {
						foreach ( $value as $k => $v ) {
							$tmp [] = "'{$v}'";
						}
					}
				}
				$field = implode ( ",", array_keys ( $wordbook ) );
				$tmp2 = implode ( ",", $tmp );
				$field .= ",createtime";
				$tmp2 .= "," . time ();
				$sql = "insert into " . $table . " ({$field}) values({$tmp2})";
				$this->db->query ( $sql );
				$row = $this->db->affected_rows ();
				$msg = intval ( $row ) > 0 ? "导入成功。。" : "导入模板失败，请 重试。。。";
				$data = array ('msg' => $msg, "code" => "success" );
			}
		}
		return $data;
	}
	
	/**
	 * @desc 返回当前用户权重
	 */
	public function getUserGroup() {
		$memberId = intval ( $this->session->userdata ( 'memberId' ) );
		$this->load->model ( "AdminModel", "Admin" );
		$member = $this->Admin->getMemberWeights ( $memberId );
		return $member;
	}
	
	/**
	 * @desc 导出word文件
	 */
	public function wordExport($fileName) {
		header ( "Content-Type: text/doc; charset=utf-8" );
		header ( "Expires:Mon,26 Jul 1997 05:00:00 GMT" ); // some day in the past
		header ( "Last-Modified:" . gmdate ( "D, d M Y H:i:s" ) . "GMT" );
		header ( "Content-type: application/x-download" );
		header ( "Content-Disposition: attachment; filename=" . iconv ( "UTF-8", "gb2312", $fileName ) . "" );
		header ( "Content-Transfer-Encoding: binary" );
		readfile ( $fileName );
		unlink ( $fileName );
		exit ();
	}
}