<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 活动报名表相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-14
 */
class Activityregister extends CI_Controller {
	private static $__sex = array ("男", "女", "未知" );
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'ActivityRegisterModel', "activityregister" );
		$this->load->model ( 'ActivityModel', "activity" );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$offset = $this->input->get ( 'per_page', TRUE );
		$serialNumber = urldecode($this->input->get('serialNumber',TRUE));
		$name = urldecode($this->input->get('name',TRUE));
		if(!empty($serialNumber)){
			$where['serialNumber'] = $serialNumber;
		}
		if(!empty($name)){
			$where['name'] = $name;
		}
		$join = array ($this->activity->__activityTable => $this->activity->__activityTable . '.activityId = ' . $this->activityregister->__activityRegisterTable . '.activityId' );
		$list = $this->activityregister->getData ( $this->activityregister->__activityRegisterTable, $where, $offset ,$join);
		$data ['activityregister'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->activityregister->getPrimaryName ( $this->activityregister->__activityRegisterTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'activityregister-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'activityregister-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->activityregister->getPrimaryName ( $this->activityregister->__activityRegisterTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->activityregister->getDataByPrimaryKey ( $this->activityregister->__activityRegisterTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑报名者资料";
		} else {
			$data ['title'] = "新增报名";
		}
		$data ['__sex'] = self::$__sex;
		$list = $this->activity->getData ( $this->activity->__activityTable );
		$data['activityList'] = $list['data'];
		$this->load->view ( 'activityregister-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->activityregister->__activityRegisterTable, $this->activityregister->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'activityregister-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			$isSuccess = $this->activityregister->save ( $this->activityregister->__activityRegisterTable, $post );
			if ($isSuccess > 0) {
				$this->jsonCallback ( "1", "保存成功" , array('opt'=>$isSuccess));
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
			$isDel = $this->activityregister->del ( $this->activityregister->__activityRegisterTable, $value );
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