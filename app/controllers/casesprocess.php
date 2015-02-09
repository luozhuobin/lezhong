<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 小组记录相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-21
 */
class CasesProcess extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'CasesProcessModel', "casesProcess" );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$this->load->model ( 'CasesModel', "cases" );
		$casesId = intval ( $this->input->get ( "casesId" ) );
		$offset = $this->input->get ( 'per_page', TRUE );
		$serialNumber = urldecode($this->input->get('serialNumber',TRUE));
		$name = urldecode($this->input->get('name',TRUE));
		$where = array();
		if(!empty($casesId)){
			$where[$this->cases->__casesTable . ".casesId"] = $casesId;
		}
		if(!empty($serialNumber)){
			$where['serialNumber'] = $serialNumber;
		}
		if(!empty($name)){
			$where['name'] = $name;
		}
		$join = array ($this->cases->__casesTable => $this->cases->__casesTable . '.casesId = ' . $this->casesProcess->__casesProcessTable . '.casesId' );
		$select = $this->casesProcess->__casesProcessTable.".*,".$this->cases->__casesTable.".serialNumber,".$this->cases->__casesTable.".name";
		$list = $this->casesProcess->getData ( $this->casesProcess->__casesProcessTable, $where, $offset, $join ,$select);
		$data ['casesProcess'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->casesProcess->getPrimaryName ( $this->casesProcess->__casesProcessTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$data['casesId'] = $casesId;
		$this->load->view ( 'casesProcess-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'casesProcess-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$casesProcess = array ();
		$processId = intval ( $this->input->get ( "processId" ) );
		if (! empty ( $processId )) {
			$this->load->model ( 'CasesModel', "cases" );
			$select = $this->casesProcess->__casesProcessTable . ".*," . $this->cases->__casesTable . ".serialNumber,". $this->cases->__casesTable . ".name";
			$join = array ($this->cases->__casesTable => $this->cases->__casesTable . ".casesId=" . $this->casesProcess->__casesProcessTable . ".casesId" );
			$casesProcess = $this->casesProcess->getData ( $this->casesProcess->__casesProcessTable, array ("processId" => $processId ), '', $join, $select );
			if ($casesProcess ['total'] > 0) {
				$data = $casesProcess ['data'][0];
				$data ['title'] = "编辑个案过程记录";
				$data['casesId'] = $casesProcess['data'][0]['casesId'];
				$data['processId'] = $processId;
			} else {
				$data ['title'] = "新增个案过程记录";
			}
		}else{
			$data ['title'] = "新增个案过程记录";
		}
		$this->load->model ( 'CasesModel', "cases" );
		$list = $this->cases->getData ( $this->cases->__casesTable);
		$data['logList'] = $list['data'];
		$this->load->view ( 'casesProcess-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->casesProcess->__casesProcessTable, $this->casesProcess->__wordbook );
		$data ['c'] = "casesProcess";
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'casesProcess-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			$isSuccess = $this->casesProcess->save ( $this->casesProcess->__casesProcessTable, $post );
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
			$isDel = $this->casesProcess->del ( $this->casesProcess->__casesProcessTable, $value );
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