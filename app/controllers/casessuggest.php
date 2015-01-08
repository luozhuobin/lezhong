<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 社工建议及行动相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-21
 */
class Casessuggest extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'CasesSuggestModel', "casesSuggest" );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			$isSuccess = $this->casesSuggest->save ( $this->casesSuggest->__casesSuggestTable, $post );
			if ($isSuccess > 0) {
				$this->jsonCallback ( "1", "保存成功" );
			} else {
				$this->jsonCallback ( "2", "保存失败" );
			}
		} else {
			$this->jsonCallback ( "3", "表单数据为空" );
		}
	}
	
}