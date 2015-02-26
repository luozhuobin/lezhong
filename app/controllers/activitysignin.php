<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 活动报名表相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-14
 */
class Activitysignin extends CI_Controller {
	private static $__attendance = array ("出席", "缺席", "迟到" );
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'ActivitySigninModel', "activitySignin" );
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
		$this->load->model ( 'ActivityModel', "activity" );
		$join = array($this->activity->__activityTable=>$this->activitySignin->__activitySigninTable.".activityId = ".$this->activity->__activityTable.".activityId");
		$type = array($this->activity->__activityTable=>"LEFT");
		$list = $this->activitySignin->getData ( $this->activitySignin->__activitySigninTable, $where, $offset ,$join,'*',$type);
		$data ['activitySignin'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->activitySignin->getPrimaryName ( $this->activitySignin->__activitySigninTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'activitySignin-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'activitySignin-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->activitySignin->getPrimaryName ( $this->activitySignin->__activitySigninTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->activitySignin->getDataByPrimaryKey ( $this->activitySignin->__activitySigninTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑报名者资料";
		} else {
			$data ['title'] = "新增报名";
		}
		$data ['__attendance'] = self::$__attendance;
		##活动列表
		$this->load->model ( 'activityModel', "activity" );
		$list = $this->activity->getData ( $this->activity->__activityTable );
		$data['activityList'] = $list['data'];
		$this->load->view ( 'activitySignin-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->activitySignin->__activitySigninTable, $this->activitySignin->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'activitySignin-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			if (empty ( $post ['activityId'] )) {
				$this->jsonCallback ( "activityName", "请选择活动" );
			}
			if (empty ( $post ['participantName'] )) {
				$this->jsonCallback ( "participantName", "请输入参加者姓名" );
			}
			$where = array ("activityId" => $post ['activityId'], "participantName" => $post ['participantName'] );
			$this->load->model ( 'activitySigninModel', "activitySignin" );
			$signin = $this->activitySignin->getData ( $this->activitySignin->__activitySigninTable, $where );
			if ($signin ['total'] > 0) {
				$post ['signinId'] = $signin ['data'] [0] ['signinId'];
			}
			$isSuccess = $this->activitySignin->save ( $this->activitySignin->__activitySigninTable, $post );
			if ($isSuccess > 0) {
				$this->jsonCallback ( "1", "保存成功" ,array("opt"=>$isSuccess));
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
			$isDel = $this->activitySignin->del ( $this->activitySignin->__activitySigninTable, $value );
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