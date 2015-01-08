<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 巡房记录相关类
 * @author zhuobin.luo
 * @since 2014-05-01
 */
class Rounds extends CI_Controller {
	##性别
	private static $__sex = array ("男", "女", "未知" );
	##科室
	private static $__section = array ();
	##过敏史
	private static $__allergicHistory = array ();
	##禁忌
	private static $__inhibition = array ();
	##住院照顾
	private static $__hospitalCare = array ("独自住院", "家属陪同", "聘用陪人（有医务知识）", "聘用陪人（无医务知识）" );
	##进食
	private static $__eat = array ();
	##沐浴
	private static $__bath = array ();
	##穿衣
	private static $__wash = array ();
	##服药
	private static $__dose = array ();
	##住院费用支付方式
	private static $__payment = array ();
	##危机因素
	private static $__riskFactors = array ("无", "低", "中", "高" );
	##当事人是否愿意接受所建议的服务
	private static $__isAccept = array ("愿意", "不愿意或不适用" );
	##
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'RoundsModel', "Rounds" );
		self::$__section = array ("儿科", "手外科", "妇产科", "托养中心", "其他" . self::$__parting );
		self::$__allergicHistory = array ("无", "有" . self::$__parting );
		self::$__inhibition = array ("无", "有" . self::$__parting );
		self::$__eat = array ("自理", "需协助", "其他" . self::$__parting );
		self::$__bath = array ("自理", "需协助", "其他" . self::$__parting );
		self::$__wash = array ("自理", "需协助", "其他" . self::$__parting );
		self::$__dose = array ("自理", "需协助", "其他" . self::$__parting );
		self::$__payment = array ("自费", "企业报销", "医保报销", "工伤报销", "其他" . self::$__parting );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$offset = $this->input->get ( 'per_page', TRUE );
		$list = $this->Rounds->getData ( $this->Rounds->__RoundsTable, array (), $offset );
		$data ['rounds'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->Rounds->getPrimaryName ( $this->Rounds->__RoundsTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'Rounds-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'Rounds-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->Rounds->getPrimaryName ( $this->Rounds->__RoundsTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->Rounds->getDataByPrimaryKey ( $this->Rounds->__RoundsTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑巡房记录";
		} else {
			$data ['title'] = "新增巡房记录";
		}
		$data ['__sex'] = self::$__sex;
		$data ['__section'] = self::$__section;
		$data ['__allergicHistory'] = self::$__allergicHistory;
		$data ['__inhibition'] = self::$__inhibition;
		$data ['__hospitalCare'] = self::$__hospitalCare;
		$data ['__eat'] = self::$__eat;
		$data ['__bath'] = self::$__bath;
		$data ['__wash'] = self::$__wash;
		$data ['__dose'] = self::$__dose;
		$data ['__payment'] = self::$__payment;
		$data ['__riskFactors'] = self::$__riskFactors;
		$data ['__isAccept'] = self::$__isAccept;
		$data ['parting'] = self::$__parting;
		$this->load->view ( 'Rounds-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->Rounds->__RoundsTable, $this->Rounds->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'Rounds-export' );
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
			$isSuccess = $this->Rounds->save ( $this->Rounds->__RoundsTable, $post );
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
			$isDel = $this->Rounds->del ( $this->Rounds->__RoundsTable, $value );
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