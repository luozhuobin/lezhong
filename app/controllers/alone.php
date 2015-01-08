<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 个别化服务计划表相关类
 * @author zhuobin.luo
 * @since 2014-05-01
 */
class Alone extends CI_Controller {
	##性别
	private static $__sex = array ("男", "女", "未知" );
	private static $__incomeSource = array ("工作", "家人支持", "储蓄", "退休金", "低保", "高龄津贴", "残疾津贴", "其他" );
	private static $__financialSituation = array ("富余", "足够", "不够" );
	private static $__subsidize = array ("经常", "很少", "没有" );
	private static $__activity = array ("行动自如", "可自行活动但建议有人陪伴", "需用辅具", "轮椅", "卧床", "其他" );
	private static $__momory = array ("完整", "偶尔健忘", "丧失短暂记忆", "丧失短暂记忆及长期记忆" );
	private static $__identifyEnvironmental = array ("完全能辨认人物及场所", "能辨认熟悉之人物及场所", "需协助才能辨认方向", "完全丧失辨认能力" );
	private static $__toilet = array ("厕所", "便椅", "尿壶", "尿片", "其他" );
	private static $__cooperation = array ("合作", "偶尔抗拒", "需鼓励和劝说", "抗拒合作", "不能有效沟通" );
	private static $__dressing = array ("自如", "需协助", "不能自助" );
	private static $__eat = array ("自如", "需协助", "不能自助" );
	private static $__personalCleanliness = array ("自如", "需协助", "不能自助" );
	private static $__diet = array ("经常", "偶然", "没有" );
	private static $__visit = array ("经常", "偶然", "没有" );
	private static $__phoneContact = array ("经常", "偶然", "没有" );
	private static $__visitRelatives = array ("经常", "偶然", "没有" );
	private static $__lettersVisit = array ("经常", "偶然", "没有" );
	private static $__neighborhoodTalk = array ("经常", "偶然", "没有" );
	private static $__neighborhoodFun = array ("经常", "偶然", "没有" );
	private static $__noTalk = array ("经常", "偶然", "没有" );
	private static $__becomeEnemies = array ("经常", "偶然", "没有" );
	private static $__talkToMind = array ("经常", "偶然", "没有" );
	private static $__neetTohelp = array ("经常", "偶然", "没有" );
	private static $__advising = array ("经常", "偶然", "没有" );
	private static $__critique = array ("经常", "偶然", "没有" );
	private static $__phone = array ("经常", "偶然", "没有" );
	private static $__tv = array ("经常", "偶然", "没有" );
	private static $__newspaper = array ("经常", "偶然", "没有" );
	private static $__outdoor = array ("经常", "偶然", "没有" );
	private static $__dinnerParty = array ("经常", "偶然", "没有" );
	private static $__specialEvents = array ("经常", "偶然", "没有" );
	private static $__smoking = array ("经常", "偶然", "没有" );
	private static $__drinking = array ("经常", "偶然", "没有" );
	private static $__sports = array ("经常", "偶然", "没有" );
	private static $__reading = array ("经常", "偶然", "没有" );
	private static $__law = array ("经常", "偶然", "没有" );
	private static $__emotionalControl = array ("经常", "偶然", "没有" );
	private static $__hopeful = array ("经常", "偶然", "没有" );
	private static $__trustOthers = array ("经常", "偶然", "没有" );
	private static $__gregarious = array ("经常", "偶然", "没有" );
	private static $__attitude = array ("经常", "偶然", "没有" );
	private static $__active = array ("经常", "偶然", "没有" );
	private static $__stressManagement = array ("经常", "偶然", "没有" );
	
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'AloneModel', "alone" );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$offset = $this->input->get ( 'per_page', TRUE );
		$list = $this->alone->getData ( $this->alone->__aloneTable, array (), $offset );
		$data ['alone'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->alone->getPrimaryName ( $this->alone->__aloneTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'alone-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'alone-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->alone->getPrimaryName ( $this->alone->__aloneTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->alone->getDataByPrimaryKey ( $this->alone->__aloneTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑巡房记录";
		} else {
			$data ['title'] = "新增巡房记录";
		}
		$data ['__sex'] = self::$__sex;
		$data ['__incomeSource'] = self::$__incomeSource;
		$data ['__financialSituation'] = self::$__financialSituation;
		$data ['__subsidize'] = self::$__subsidize;
		$data ['__activity'] = self::$__activity;
		$data ['__momory'] = self::$__momory;
		$data ['__identifyEnvironmental'] = self::$__identifyEnvironmental;
		$data ['__toilet'] = self::$__toilet;
		$data ['__cooperation'] = self::$__cooperation;
		$data ['__dressing'] = self::$__dressing;
		$data ['__eat'] = self::$__eat;
		$data ['__personalCleanliness'] = self::$__personalCleanliness;
		$data ['__diet'] = self::$__diet;
		$data ['__visit'] = self::$__visit;
		$data ['__phoneContact'] = self::$__phoneContact;
		$data ['__visitRelatives'] = self::$__visitRelatives;
		$data ['__lettersVisit'] = self::$__lettersVisit;
		$data ['__neighborhoodTalk'] = self::$__neighborhoodTalk;
		$data ['__neighborhoodFun'] = self::$__neighborhoodFun;
		$data ['__noTalk'] = self::$__noTalk;
		$data ['__becomeEnemies'] = self::$__becomeEnemies;
		$data ['__talkToMind'] = self::$__talkToMind;
		$data ['__neetTohelp'] = self::$__neetTohelp;
		$data ['__advising'] = self::$__advising;
		$data ['__critique'] = self::$__critique;
		$data ['__phone'] = self::$__phone;
		$data ['__tv'] = self::$__tv;
		$data ['__newspaper'] = self::$__newspaper;
		$data ['__outdoor'] = self::$__outdoor;
		$data ['__dinnerParty'] = self::$__dinnerParty;
		$data ['__specialEvents'] = self::$__specialEvents;
		$data ['__smoking'] = self::$__smoking;
		$data ['__drinking'] = self::$__drinking;
		$data ['__sports'] = self::$__sports;
		$data ['__reading'] = self::$__reading;
		$data ['__law'] = self::$__law;
		$data ['__emotionalControl'] = self::$__emotionalControl;
		$data ['__hopeful'] = self::$__hopeful;
		$data ['__trustOthers'] = self::$__trustOthers;
		$data ['__gregarious'] = self::$__gregarious;
		$data ['__attitude'] = self::$__attitude;
		$data ['__active'] = self::$__active;
		$data ['__stressManagement'] = self::$__stressManagement;
		$this->load->view ( 'alone-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->alone->__aloneTable, $this->alone->__wordbook );
		$data ['c'] = "alone";
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->load->view ( 'alone-export' );
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			$isSuccess = $this->alone->save ( $this->alone->__aloneTable, $post );
			if ($isSuccess > 0) {
				$this->jsonCallback ( "1", "保存成功" );
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
			$isDel = $this->alone->del ( $this->alone->__aloneTable, $value );
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