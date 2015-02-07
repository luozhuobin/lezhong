<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 公用函数处理类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-20
 */
class Utils extends CI_Controller {
	public function __construct() {
		parent::__construct ();
	}
	public static function getMenu(){
		$this->load->view ( 'menu-show', $data );
	}
}