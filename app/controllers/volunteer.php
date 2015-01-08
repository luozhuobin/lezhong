<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 个别化服务计划表相关类
 * @author zhuobin.luo
 * @since 2014-05-01
 */
class Volunteer extends CI_Controller {
	private static $__marriage = array ("已婚", "未婚", "已育", "未育" );
	private static $__workStatus = array ("就业", "无业", "学龄", "退休" );
	private static $__sex = array ("男", "女", "未知" );
	private static $__source = array ();
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'VolunteerModel', "volunteer" );
		self::$__source = array ("企业", "社区", "自主", "其他" . self::$__parting );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$offset = $this->input->get ( 'per_page', TRUE );
		$list = $this->volunteer->getData ( $this->volunteer->__volunteerTable, array (), $offset );
		$data ['volunteer'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->volunteer->getPrimaryName ( $this->volunteer->__volunteerTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'volunteer-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'volunteer-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->volunteer->getPrimaryName ( $this->volunteer->__volunteerTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->volunteer->getDataByPrimaryKey ( $this->volunteer->__volunteerTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑义工资料";
		} else {
			$data ['title'] = "新增义工资料";
		}
		$data ['__marriage'] = self::$__marriage;
		$data ['__sex'] = self::$__sex;
		$data ['__workStatus'] = self::$__workStatus;
		$data ['__source'] = self::$__source;
		$data ['parting'] = self::$__parting;
		$this->load->view ( 'volunteer-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->volunteer->__volunteerTable, $this->volunteer->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'volunteer-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			foreach ( $post as $key => $value ) {
				if (strstr ( $key, "Remark" ) !== false) {
					if (! empty ( $post [$key] )) {
						$k = str_ireplace ( "Remark", "", $key );
						$post [$k] = $post [$key];
					}
					unset ( $post [$key] );
				}
			}
			$isSuccess = $this->volunteer->save ( $this->volunteer->__volunteerTable, $post );
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
			$isDel = $this->volunteer->del ( $this->volunteer->__volunteerTable, $value );
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