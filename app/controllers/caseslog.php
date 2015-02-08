<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 小组记录相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-21
 */
class Caseslog extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'CaseslogModel', "caseslog" );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$this->load->model ( 'CasesModel', "cases" );
		$casesId = intval ( $this->input->get ( "casesId" ) );
		$offset = $this->input->get ( 'per_page', TRUE );
		$join = array ($this->cases->__casesTable => $this->cases->__casesTable . '.casesId = ' . $this->caseslog->__caseslogTable . '.casesId' );
		$where = array();
		if(!empty($casesId)){
			$where = array ($this->cases->__casesTable . ".casesId" => $casesId );
		}
		$list = $this->caseslog->getData ( $this->caseslog->__caseslogTable, $where, $offset, $join );
		$data ['caseslog'] = $list ['data'];
		$links = $this->getPageList ( $list ['total']);
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->caseslog->getPrimaryName ( $this->caseslog->__caseslogTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'caseslog-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'caseslog-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$caseslog = array ();
		$casesId = intval ( $this->input->get ( "casesId" ) );
		$logId = intval ( $this->input->get ( "logId" ) );
		$data['casesId'] = $casesId;
		if (! empty ( $logId )) {
			$caseslog = $this->caseslog->getData ( $this->caseslog->__caseslogTable, array ("logId" => $logId ) );
			if ($caseslog ['total'] > 0) {
				$data = $caseslog ['data'][0];
				$data ['title'] = "编辑个案记录";
			} else {
				$data ['title'] = "新增个案记录";
			}
		}else{
			$data ['title'] = "新增个案记录";
		}
		$this->load->view ( 'caseslog-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->caseslog->__caseslogTable, $this->caseslog->__wordbook );
		$data ['c'] = "caseslog";
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'caseslog-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if(!empty($post['opinionSupervision'])){
			$post['supervisionTime'] = time();
		}
		if (! empty ( $post )) {
			$isSuccess = $this->caseslog->save ( $this->caseslog->__caseslogTable, $post );
			$this->jsonCallback ( "1", "保存成功" ,array("opt"=>$isSuccess));
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
			$isDel = $this->caseslog->del ( $this->caseslog->__caseslogTable, $value );
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