<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 个案相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-20
 */
class Cases extends CI_Controller {
	##结婚情况
	private static $__marriage = array ("已婚", "未婚", "已育", "未育" );
	##就业情况
	private static $__occupationStatus = array ("就业", "无业", "学龄", "退休" );
	##科室
	private static $__department = array ("儿科", "手外科", "妇产科", "托养中心", "其他" );
	##性别
	private static $__sex = array ("男", "女", "未知" );
	##个案数据
	private $__cases = array ();
	private $__caseId = 0;
	##xml内容
	private $__xmlContent = '';
	
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
	private static $__diet = array ("正常餐", "糖尿餐", "低油", "打碎", "有过敏" );
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
		
	##社工建议及行动
	private static $__followUp = array ('需要', '不需要' );
	private static $__crisis = array ('无', '高', '中', '低' );
	private static $__urgent = array ('不需要', '需要' );
	private static $__isAccept = array ('愿意', '不愿意', '不适用' );
	private static $__isNeed = array ('否', '委派', '转介予' );
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'CasesModel', "cases" );
		$this->load->model ( 'CaseslogModel', "CaseslogModel" );
		$this->load->model ( 'CasesProcessModel', "CasesProcessModel" );
	}
	/**
	 * @desc 列表
	 */
	public function show() {
		$offset = $this->input->get ( 'per_page', TRUE );
		$list = $this->cases->getData ( $this->cases->__casesTable, array (), $offset );
		if (! empty ( $list ['data'] )) {
			
			foreach ( $list ['data'] as $key => &$value ) {
				##社工建议及行动
				
				##个案记录
				$logCount = $this->CaseslogModel->getDataCount($this->CaseslogModel->__caseslogTable,array("casesId"=>$value ['casesId']));
				if($logCount>0){
					$value['casesLog'] = '<a href="?c=caseslog&m=show&casesId='.$value['casesId'].'">查看</a>';
				}else{
					$value['casesLog'] = '<a href="?c=caseslog&m=edit&serialNumber='.$value['casesId'].'">新增</a>';
				}
				##过程记录
				$logCount = $this->CasesProcessModel->getDataCount($this->CasesProcessModel->__casesProcessTable,array("casesId"=>$value ['casesId']));
				if($logCount>0){
					$value['casesProcess'] = '<a href="?c=casesProcess&m=show&casesId='.$value['casesId'].'">查看</a>';
				}else{
					$value['casesProcess'] = '<a href="?c=casesProcess&m=edit&casesId='.$value['casesId'].'">新增</a>';
				}
				##个性化服务列表
				$logCount = $this->CaseslogModel->getDataCount($this->CaseslogModel->__caseslogTable,array("casesId"=>$value ['casesId']));
				if($logCount>0){
					$value['casesLog'] = '<a href="?c=caseslog&m=show&casesId='.$value['casesId'].'">查看</a>';
				}else{
					$value['casesLog'] = '<a href="?c=caseslog&m=edit&casesId='.$value['casesId'].'">新增</a>';
				}
			}
		}
		$data ['cases'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->cases->getPrimaryName ( $this->cases->__casesTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'cases-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'cases-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->cases->getPrimaryName ( $this->cases->__casesTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->cases->getDataByPrimaryKey ( $this->cases->__casesTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑个案";
			
			##个性化服务计划表
			$this->load->model ( 'AloneModel', "alone" );
			$recordAlone = $this->alone->getData ( $this->alone->__aloneTable, array ("casesId" => $data ['casesId'] ) );
			if ($recordAlone ['total'] > 0) {
				$aloneKey = $this->alone->getPrimaryName ( $this->alone->__aloneTable );
				$aloneValue = $recordAlone ['data'] [0] [$aloneKey];
				$data ['aloneKey'] = $aloneKey;
				$data ['aloneValue'] = $aloneValue;
				$data = array_merge ( $data, $recordAlone ['data'] [0] );
			}
			##社工建议及行动
			$this->load->model ( 'CasesSuggestModel', "casesSuggest" );
			$casesSuggest = $this->casesSuggest->getData ( $this->casesSuggest->__casesSuggestTable, array ("casesId" => $data ['casesId'] ) );
			if ($casesSuggest ['total'] > 0) {
				$casesSuggestKey = $this->casesSuggest->getPrimaryName ( $this->casesSuggest->__casesSuggestTable );
				$casesSuggestValue = $casesSuggest ['data'] [0] [$casesSuggestKey];
				$data ['casesSuggestKey'] = $casesSuggestKey;
				$data ['casesSuggestValue'] = $casesSuggestValue;
				$data = array_merge ( $data, $casesSuggest ['data'] [0] );
			}
		} else {
			$data ['title'] = "新增个案";
		}
		$data ['__marriage'] = self::$__marriage;
			$data ['__occupationStatus'] = self::$__occupationStatus;
			$data ['__department'] = self::$__department;
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
			$data ['__followUp'] = self::$__followUp;
			$data ['__crisis'] = self::$__crisis;
			$data ['__urgent'] = self::$__urgent;
			$data ['__isAccept'] = self::$__isAccept;
			$data ['__isNeed'] = self::$__isNeed;
		$this->load->view ( 'cases-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->cases->__casesTable, $this->cases->__wordbook );
		$data ['c'] = "cases";
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * 手册：http://wenku.baidu.com/link?url=yKLV9Z1UyA3SCZqcZkDM0miWl5LWLgEJvOh_cY-iPQRIOP23sWg2sNgP_2-is2h_K22lLeDXPW_dtcH1A6pqqd6wmA30DqipuczndeBYL0O
	 * @id 数据Id
	 * 
	 */
	public function export() {
		$this->caseId = $this->input->get ( "casesId", TRUE );
		$this->assess = $this->input->get ( "assess", TRUE );
		if (! empty ( $this->caseId )) {
			$this->__cases = $this->cases->getDataByPrimaryKey ( $this->cases->__casesTable, $this->caseId );
			if (! empty ( $this->__cases )) {
				$fileName = $this->__cases ['alias'] . "_个案.doc";
				$this->__xmlContent = file_get_contents("header.xml");
					##封面
				$this->cover ();
				##服务对象资料将记录
				$this->mainData ();
				##社工的建议及行动
				$this->suggest ();
				##接受个案服务同意书
				$this->consent ();
				##接收转介回复
				$this->referralReply();
				##个案记录表
				$this->casesLog();
				##过程记录
				$this->processRecording();
				##个案阶段检视 / 结案表
				$this->conclusionForm();
				##转介/结案同意书
				$this->finalAgreement();
				##个案转介表
				$this->referralForm();
				##结案评估表
				$this->finalAssessment();
				##个别化服务计划表
				$this->individualSchedule();
				$this->__xmlContent .= file_get_contents("footer.xml");
				
				if (file_exists ( $fileName )) {
					unlink ( $fileName );
				}
				file_put_contents($fileName,$this->__xmlContent);
				$this->wordExport ( $fileName );
			}
		
		}
	
	}
	
	/**
	 * @desc 保存记录
	 */
	public function saveInfo() {
		$post = $this->input->post ();
		if (! empty ( $post )) {
			$isSuccess = $this->cases->save ( $this->cases->__casesTable, $post );
			if ($isSuccess >= 0) {
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
			$isDel = $this->cases->del ( $this->cases->__casesTable, $value );
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
		##==============word文件导出=======================
	/**
	 * @desc 封面
	 */
	public function cover() {
		$cover = file_get_contents ( "cases_cover.xml" );
		##开案日期
		$openDate = empty ( $this->__cases ['openDate'] ) ? '______________________' : $this->__cases ['openDate'];
		##结案日期
		$closeDate = empty ( $this->__cases ['closeDate'] ) ? '______________________' : $this->__cases ['closeDate'];
		##个案编号
		$serialNumber = empty ( $this->__cases ['serialNumber'] ) ? '______________________' : $this->__cases ['serialNumber'];
		##跟进社工
		$socialWorker = empty ( $this->__cases ['socialWorker'] ) ? '______________________' : $this->__cases ['socialWorker'];
		##督导
		$supervisor = empty ( $this->__cases ['supervisor'] ) ? '______________________' : $this->__cases ['supervisor'];
		$search = array ('{$openDate}', '{$closeDate}', '{$serialNumber}', '{$socialWorker}', '{$supervisor}' );
		$replace = array ($openDate, $closeDate, $serialNumber, $socialWorker, $supervisor );
		$this->__xmlContent .= str_ireplace ( $search, $replace, $cover );
	}
	/**
	 * @desc 服务对象资料将记录
	 */
	public function mainData() {
		$cover = file_get_contents( "mainData.xml" );
		$field = array(
			'serialNumber',
			'name',
			'sex',
			'birthday',
			'address',
			'phone',
			'marriage',
			'occupationStatus',
			'economics',
			'socialServiceRecords',
			'department',
			'patientSummary',
			'serviceNeeds',
			'familyBackground',
			'readme'
		);
		foreach ( $field as $key => $value ) {
			$search = '{$' . $value . '}';
			$replace = $this->__cases [$value];
			switch ($value) {
				##科室
				case 'department':
					$departArray = array(
						'儿科'=>'{$pediatrics}',
						'手外科'=>'{$handSurgery}',
						'妇产科'=>'{$gynaecology}',
						'托养中心'=>'{$goster}',
						'departmentOtherHook'=>'{$departmentOtherHook}',
						'departmentOther'=>'{$departmentOther}',
					);
					$departValue = $departArray [$this->__cases [$value]];
					if (! empty ( $departValue )) {
						$search = $departValue;
						$replace = '√';
					} else {
						$cover = str_ireplace ( '{$departmentOtherHook}', '√', $cover );
						$search = '{$departmentOther}';
					}
					$cover = str_ireplace ( $search, $replace, $cover );
					##其余的需要删除替换标致
					foreach ( $departArray as $k => $v ) {
						$cover = str_ireplace ( $v, '', $cover );
					}
					break;
				case 'socialServiceRecords' :
					##使用社会服务记录
					$socialArray = array (
						'没有' => '{$none}', 
						'曾经有' => '{$once}', 
						'现仍有接受服务' => '{$now}',
						'noceRemark' => '{$noceRemark}',
						'nowRemark' => '{$nowRemark}'  
					);
					$socialValue = $socialArray [$this->__cases [$value]];
					if (! empty ( $socialValue )) {
						$cover = str_ireplace ( $socialValue, '√', $cover );
						if ($socialValue == '{$once}') {
							$cover = str_ireplace ( '{$noceRemark}', $this->__cases ['socialServiceRemark'], $cover );
						} else if ($socialValue == '{$now}') {
							$cover = str_ireplace ( '{$nowRemark}', $this->__cases ['socialServiceRemark'], $cover );
						}
					}
					##其余的需要删除替换标致
					foreach ( $socialArray as $k => $v ) {
						$cover = str_ireplace ( $v, '', $cover );
					}
					break;
				default :
					$cover = str_ireplace ( $search, $replace, $cover );
					break;
			}
		}
		$this->__xmlContent .= $cover;
	}
	/**
	 * @desc 社工的建议及行动
	 */
	public function suggest() {
		$cover = file_get_contents ( "suggest.xml" );
		$this->load->model ( 'CasesSuggestModel', "casesSuggest" );
		$casesSuggest = $this->casesSuggest->getData ( $this->casesSuggest->__casesSuggestTable, array ("casesId" => $this->__cases ['casesId'] ) );
		$casesSuggest = $casesSuggest ['total'] > 0 ? $casesSuggest ['data'] [0] : array();;
		##是否需要跟进
		$notFollowUp = '';
		$followUpRemark = '';
		$followUp = '';
		if(!empty($casesSuggest['followUp'])){
			if($casesSuggest['followUp'] == "不需要"){
				$notFollowUp = '√';
				$followUpRemark = $casesSuggest['followUpRemark'];
			}else{
				$followUp = '√';
			}
		}
		$cover = str_ireplace ( '{$notFollowUp}', $notFollowUp, $cover );
		$cover = str_ireplace ( '{$followUpRemark}', $followUpRemark, $cover );
		$cover = str_ireplace ( '{$followUp}', $followUp, $cover );
		##危机因素
		$crisis = '';
		$crisisRemark = '';
		if (! empty ( $casesSuggest ['crisis'] )) {
			if ($casesSuggest ['crisis'] == "无") {
				$crisis = '（√ ）无    （ ）有【（ ）高／（ ）中／（ ）低】';
			} else {
				if ($casesSuggest ['crisis'] == "高") {
					$crisis = '（ ）无    （√ ）有【（√ ）高／（ ）中／（ ）低】';
				} elseif ($casesSuggest ['crisis'] == "中") {
					$crisis = '（ ）无    （√ ）有【（ ）高／（ √）中／（ ）低】';
				} elseif ($casesSuggest ['crisis'] == "低") {
					$crisis = '（ ）无    （ √）有【（ ）高／（ ）中／（√ ）低】';
				}
				$crisisRemark = $casesSuggest ['crisisRemark'];
			}
		}else{
			$crisis = '（ ）无    （ ）有【（ ）高／（ ）中／（ ）低】';
		}
		$cover = str_ireplace ( '{$crisisRemark}', $crisisRemark, $cover );
		$cover = str_ireplace ( '{$crisis}', $crisis, $cover );
		
		##紧急服务
		$urgent = '';
		$urgentRemark = '';
		if (! empty ( $casesSuggest ['urgent'] )) {
			if ($casesSuggest ['urgent'] == "不需要") {
				$urgent = '（√ ）不需要  （ ）需要';
			} else {
				$urgent = '（ ）不需要  （ √ ）需要';
				$urgentRemark = $casesSuggest ['urgentRemark'];
			}
		}else{
			$urgent = '（ ）不需要  （  ）需要';	
		}
		$cover = str_ireplace ( '{$urgentRemark}', $urgentRemark, $cover );
		$cover = str_ireplace ( '{$urgent}', $urgent, $cover );
		$cover = str_ireplace ( '{$otherReasons}', $casesSuggest ['otherReasons'], $cover );
		if(!empty($casesSuggest ['isAccept'])){
			if($casesSuggest ['isAccept'] == '愿意'){
				$willing = '√';
			}else if($casesSuggest ['isAccept'] == '不愿意'){
				$unwilling = '√';
			}else{
				$notApplicable = '√';
				$isAcceptRemark = $casesSuggest ['isAcceptRemark'];
			}
		}
		$cover = str_ireplace ( '{$willing}', $willing, $cover );
		$cover = str_ireplace ( '{$unwilling}', $unwilling, $cover );
		$cover = str_ireplace ( '{$notApplicable}', $notApplicable, $cover );
		$cover = str_ireplace ( '{$isAcceptRemark}', $isAcceptRemark, $cover );
		$cover = str_ireplace ( '{$serialNumber}', $this->__cases['serialNumber'], $cover );
		$cover = str_ireplace ( '{$overallAim}', $casesSuggest ['overallAim'], $cover );
		$cover = str_ireplace ( '{$goals}', $casesSuggest ['goals'], $cover );
		$cover = str_ireplace ( '{$servicePlan}', $casesSuggest ['servicePlan'], $cover );
		$cover = str_ireplace ( '{$difficulty}', $casesSuggest ['difficulty'], $cover );
		$cover = str_ireplace ( '{$socialWorker}', $casesSuggest ['socialWorker'], $cover );
		$cover = str_ireplace ( '{$date}', $casesSuggest ['date'], $cover );
		
		if (! empty ( $casesSuggest ['isNeed'] )) {
			if ($casesSuggest ['isNeed'] == "否") {
				$isNeed = '（ √ ）否；（ ）是【（ ）委派 ／ （ ）转介予';
			} else if ($casesSuggest ['isNeed'] == "委派") {
				$isNeed = '（  ）否；（√ ）是【（ √）委派 ／ （ ）转介予';
			} else {
				$isNeed = '（  ）否；（√ ）是【（ ）委派 ／ （√ ）转介予';
			}
		} else {
			$isNeed = '（ ）否；（ ）是【（ ）委派 ／ （ ）转介予';
		}
		$cover = str_ireplace ( '{$isNeed}', $isNeed, $cover );
		$cover = str_ireplace ( '{$isNeedRemark}', $casesSuggest ['isNeedRemark'], $cover );
		$cover = str_ireplace ( '{$other}', $casesSuggest ['other'], $cover );
		$cover = str_ireplace ( '{$opinionSupervision}', $casesSuggest ['opinionSupervision'], $cover );
		$cover = str_ireplace ( '{$inspectionBodies}', $casesSuggest ['inspectionBodies'], $cover );
		$this->__xmlContent .= $cover;
	}
	/**
	 * @desc 接受个案服务同意书
	 */
	public function consent() {
		$cover = file_get_contents ( "consent.xml" );
		$this->__xmlContent .= $cover;
	}

	/**
	 * @desc 接收转介回复
	 */
	public function referralReply() {
		$cover = file_get_contents ( "referralReply.xml" );
		$this->__xmlContent .= $cover;
	}
	/**
	 * @desc 个案记录表
	 */
	public function casesLog() {
		$cover = file_get_contents ( "casesLog.xml" );
		$where = array ("casesId" => $this->__cases ['casesId'] );
		$log = $this->CaseslogModel->getData ( $this->CaseslogModel->__caseslogTable, $where );
		$count = $log ['total'] > 20 ? $log ['total'] : 20;
		for($i = 0; $i < $count; $i ++) {
			$replace .= '
			<w:tr w:rsidR="006F4649" w:rsidRPr="0005356A" w:rsidTr="00672B84">
				<w:trPr>
					<w:trHeight w:hRule="exact" w:val="489"/>
					<w:jc w:val="center"/>
				</w:trPr>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="1152" w:type="dxa"/>
						<w:tcBorders>
						<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
						</w:tcBorders>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="006F4649" w:rsidRPr="0005356A" w:rsidRDefault="006F4649" w:rsidP="00672B84">
						<w:pPr>
							<w:tabs>
								<w:tab w:val="left" w:pos="6720"/>
							</w:tabs>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
							<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma" w:hint="eastAsia"/>
							<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>'.$log['data'][$i]['date'].'</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="982" w:type="dxa"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="006F4649" w:rsidRPr="0005356A" w:rsidRDefault="006F4649" w:rsidP="00672B84">
						<w:pPr>
							<w:tabs>
								<w:tab w:val="left" w:pos="6720"/>
							</w:tabs>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
							<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma" w:hint="eastAsia"/>
							<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>'.$log['data'][$i]['contactWay'].'</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="1138" w:type="dxa"/>
						<w:tcBorders>
						<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
						</w:tcBorders>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="006F4649" w:rsidRPr="0005356A" w:rsidRDefault="006F4649" w:rsidP="00672B84">
						<w:pPr>
							<w:tabs>
								<w:tab w:val="left" w:pos="6720"/>
							</w:tabs>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
							<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma" w:hint="eastAsia"/>
							<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>'.$log['data'][$i]['relationship'].'</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="3960" w:type="dxa"/>
						<w:tcBorders>
							<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
							<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/>
						</w:tcBorders>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="006F4649" w:rsidRPr="0005356A" w:rsidRDefault="006F4649" w:rsidP="00672B84">
						<w:pPr>
							<w:tabs>
								<w:tab w:val="left" w:pos="6720"/>
							</w:tabs>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
							<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma" w:hint="eastAsia"/>
							<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>'.$log['data'][$i]['content'].'</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="540" w:type="dxa"/>
						<w:tcBorders>
							<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
						</w:tcBorders>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="006F4649" w:rsidRPr="0005356A" w:rsidRDefault="006F4649" w:rsidP="00672B84">
						<w:pPr>
							<w:tabs>
								<w:tab w:val="left" w:pos="6720"/>
							</w:tabs>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
							<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma" w:hint="eastAsia"/>
							<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>'.$log['data'][$i]['logId'].'</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="2315" w:type="dxa"/>
						<w:tcBorders>
							<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>
						</w:tcBorders>
					</w:tcPr>
					<w:p w:rsidR="006F4649" w:rsidRPr="0005356A" w:rsidRDefault="006F4649" w:rsidP="00672B84">
						<w:pPr>
							<w:tabs>
								<w:tab w:val="left" w:pos="6720"/>
							</w:tabs>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
							<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:cs="Tahoma" w:hint="eastAsia"/>
							<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>'.$log['data'][$i]['opinionSupervision'].'</w:t>
						</w:r>
					</w:p>
				</w:tc>
			</w:tr>';
		}
		$cover = str_ireplace ( '{$trContent}', $replace, $cover );
		$cover = str_ireplace ( '{$serialNumber}', $this->__cases ['serialNumber'], $cover );
		$this->__xmlContent .= $cover;
	}
	/**
	 * desc 过程记录
	 */
	public function processRecording() {
		$cover = file_get_contents ( "processRecording.xml" );
		$where = array ($this->CasesProcessModel->__casesProcessTable.".casesId" => $this->__cases ['casesId'] );
		$join = array ($this->cases->__casesTable => $this->cases->__casesTable . ".casesId=" . $this->CasesProcessModel->__casesProcessTable . ".casesId" );
		$select = $this->CasesProcessModel->__casesProcessTable . '.*,' . $this->cases->__casesTable . '.serialNumber';
		$process = $this->CasesProcessModel->getData ( $this->CasesProcessModel->__casesProcessTable, $where, '',$join, $select );
		if(!empty($process['data'])){
			foreach($process['data'] as $key=>$value){
				foreach($value as $k=>$v){
					$cover = str_ireplace ( '{$'.$k.'}', $v, $cover );
				}
			}
		}
		$this->__xmlContent .= $cover;
	}
	/**
	 * @desc 个案阶段检视 / 结案表
	 */
	public function conclusionForm() {
		$cover = file_get_contents ( "conclusionForm.xml" );
		$this->__xmlContent .= $cover;
	}
	
	/**
	 * @desc 结案同意书
	 */
	public function finalAgreement() {
		$cover = file_get_contents ( "finalAgreement.xml" );
		$this->__xmlContent .= $cover;
	}
	
	/**
	 * @desc 个案转介表
	 */
	public function referralForm() {
		$cover = file_get_contents ( "referralForm.xml" );
		$this->__xmlContent .= $cover;
	}
	
	/**
	 * @desc 结案评估表
	 */
	public function finalAssessment() {
		$cover = file_get_contents ( "finalAssessment.xml" );
		$this->__xmlContent .= $cover;
	}
	
	/**
	 * @desc 个别化计划表
	 */
	public function individualSchedule() {
		$cover = file_get_contents ( "individualSchedule.xml" );
		##个性化服务计划表
		$this->load->model ( 'AloneModel', "alone" );
		$recordAlone = $this->alone->getData ( $this->alone->__aloneTable, array ("casesId" => $this->__cases ['casesId'] ) );
		$alone = $recordAlone ['total'] > 0 ? $recordAlone ['data'] [0] : array();;
		##收入来源
		$incomeSource = '';
		foreach ( self::$__incomeSource as $key => $value ) {
			if ($value == $alone ['incomeSource']) {
				$incomeSource .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$incomeSource .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$incomeSource .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$incomeSource}', $incomeSource, $cover );
		##财政状况 
		$financialSituation = '';
		foreach ( self::$__financialSituation as $key => $value ) {
			if ($value == $alone ['financialSituation']) {
				$financialSituation .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$financialSituation .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$financialSituation .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$financialSituation}', $financialSituation, $cover );
		
		##需资助亲友
		$subsidize = '';
		foreach ( self::$__subsidize as $key => $value ) {
			if ($value == $alone ['subsidize']) {
				$subsidize .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$subsidize .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$subsidize .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$subsidize}', $subsidize, $cover );
		
		##活动能力
		$activity = '';
		foreach ( self::$__activity as $key => $value ) {
			if ($value == $alone ['activity']) {
				$activity .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$activity .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$activity .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$activity}', $activity, $cover );
		
		##记忆
		$momory = '';
		foreach ( self::$__momory as $key => $value ) {
			if ($value == $alone ['momory']) {
				$momory .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$momory .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$momory .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$momory}', $momory, $cover );
		
		##环境辨认
		$identifyEnvironmental = '';
		foreach ( self::$__identifyEnvironmental as $key => $value ) {
			if ($value == $alone ['identifyEnvironmental']) {
				$identifyEnvironmental .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$identifyEnvironmental .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$identifyEnvironmental .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$identifyEnvironmental}', $identifyEnvironmental, $cover );
		
		##如厕
		$toilet = '';
		foreach ( self::$__toilet as $key => $value ) {
			if ($value == $alone ['toilet']) {
				$toilet .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$toilet .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$toilet .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$toilet}', $toilet, $cover );
		
		##合作
		$cooperation = '';
		foreach ( self::$__cooperation as $key => $value ) {
			if ($value == $alone ['cooperation']) {
				$cooperation .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$cooperation .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$cooperation .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$cooperation}', $cooperation, $cover );
		
		##穿衣
		$dressing = '';
		foreach ( self::$__dressing as $key => $value ) {
			if ($value == $alone ['dressing']) {
				$dressing .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$dressing .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$dressing .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$dressing}', $dressing, $cover );
		
		##进食
		$eat = '';
		foreach ( self::$__eat as $key => $value ) {
			if ($value == $alone ['eat']) {
				$eat .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$eat .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$eat .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$eat}', $eat, $cover );
		
		##个人清洁
		$personalCleanliness = '';
		foreach ( self::$__personalCleanliness as $key => $value ) {
			if ($value == $alone ['personalCleanliness']) {
				$personalCleanliness .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$personalCleanliness .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$personalCleanliness .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$personalCleanliness}', $personalCleanliness, $cover );
		
		##饮食
		$diet= '';
		foreach ( self::$__diet as $key => $value ) {
			if ($value == $alone ['diet']) {
				$diet .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$diet .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$diet .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$diet}', $diet, $cover );
		
		##亲戚到访
		$visit= '';
		foreach ( self::$__visit as $key => $value ) {
			if ($value == $alone ['visit']) {
				$visit .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$visit .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$visit .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$visit}', $visit, $cover );
		
		##探望亲戚
		$visitRelatives= '';
		foreach ( self::$__visitRelatives as $key => $value ) {
			if ($value == $alone ['visitRelatives']) {
				$visitRelatives .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$visitRelatives .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$visitRelatives .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$visitRelatives}', $visitRelatives, $cover );
		
		##电话联络
		$phoneContact= '';
		foreach ( self::$__phoneContact as $key => $value ) {
			if ($value == $alone ['phoneContact']) {
				$phoneContact .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$phoneContact .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$phoneContact .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$phoneContact}', $phoneContact, $cover );
		
		##书信来往
		$lettersVisit= '';
		foreach ( self::$__lettersVisit as $key => $value ) {
			if ($value == $alone ['lettersVisit']) {
				$lettersVisit .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$lettersVisit .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$lettersVisit .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$lettersVisit}', $lettersVisit, $cover );
		
		##邻里倾谈
		$neighborhoodTalk= '';
		foreach ( self::$__neighborhoodTalk as $key => $value ) {
			if ($value == $alone ['neighborhoodTalk']) {
				$neighborhoodTalk .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$neighborhoodTalk .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$neighborhoodTalk .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$neighborhoodTalk}', $neighborhoodTalk, $cover );
		
		##玩乐
		$neighborhoodFun= '';
		foreach ( self::$__neighborhoodFun as $key => $value ) {
			if ($value == $alone ['neighborhoodFun']) {
				$neighborhoodFun .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$neighborhoodFun .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$neighborhoodFun .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$neighborhoodFun}', $neighborhoodFun, $cover );
		
		##互不理睬
		$noTalk= '';
		foreach ( self::$__noTalk as $key => $value ) {
			if ($value == $alone ['noTalk']) {
				$noTalk .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$noTalk .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$noTalk .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$noTalk}', $noTalk, $cover );
		
		##交恶
		$becomeEnemies= '';
		foreach ( self::$__becomeEnemies as $key => $value ) {
			if ($value == $alone ['becomeEnemies']) {
				$becomeEnemies .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$becomeEnemies .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$becomeEnemies .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$becomeEnemies}', $becomeEnemies, $cover );
		
		##倾诉心情
		$talkToMind= '';
		foreach ( self::$__talkToMind as $key => $value ) {
			if ($value == $alone ['talkToMind']) {
				$talkToMind .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$talkToMind .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$talkToMind .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$talkToMind}', $talkToMind, $cover );
		
		##寻求协助
		$neetTohelp= '';
		foreach ( self::$__neetTohelp as $key => $value ) {
			if ($value == $alone ['neetTohelp']) {
				$neetTohelp .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$neetTohelp .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$neetTohelp .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$neetTohelp}', $neetTohelp, $cover );
		
		##提供意见
		$advising= '';
		foreach ( self::$__advising as $key => $value ) {
			if ($value == $alone ['advising']) {
				$advising .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$advising .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$advising .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$advising}', $advising, $cover );
		
		##投诉批评
		$critique= '';
		foreach ( self::$__critique as $key => $value ) {
			if ($value == $alone ['critique']) {
				$critique .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$critique .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$critique .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$critique}', $critique, $cover );
		
		##手机
		$phone= '';
		foreach ( self::$__phone as $key => $value ) {
			if ($value == $alone ['phone']) {
				$phone .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$phone .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$phone .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$phone}', $phone, $cover );
		
		##电视
		$tv= '';
		foreach ( self::$__tv as $key => $value ) {
			if ($value == $alone ['tv']) {
				$tv .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$tv .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$tv .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$tv}', $tv, $cover );
		
		##书报
		$newspaper= '';
		foreach ( self::$__newspaper as $key => $value ) {
			if ($value == $alone ['newspaper']) {
				$newspaper .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$newspaper .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$newspaper .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$newspaper}', $newspaper, $cover );
		
		##户外
		$outdoor= '';
		foreach ( self::$__outdoor as $key => $value ) {
			if ($value == $alone ['outdoor']) {
				$outdoor .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$outdoor .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$outdoor .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$outdoor}', $outdoor, $cover );
		
		##聚餐
		$dinnerParty= '';
		foreach ( self::$__dinnerParty as $key => $value ) {
			if ($value == $alone ['dinnerParty']) {
				$dinnerParty .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$dinnerParty .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$dinnerParty .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$dinnerParty}', $dinnerParty, $cover );
		
		##特别活动
		$specialEvents= '';
		foreach ( self::$__specialEvents as $key => $value ) {
			if ($value == $alone ['specialEvents']) {
				$specialEvents .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$specialEvents .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$specialEvents .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$specialEvents}', $specialEvents, $cover );
		
		##吸烟
		$smoking= '';
		foreach ( self::$__smoking as $key => $value ) {
			if ($value == $alone ['smoking']) {
				$smoking .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$smoking .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$smoking .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$smoking}', $smoking, $cover );
		
		##喝酒
		$drinking= '';
		foreach ( self::$__drinking as $key => $value ) {
			if ($value == $alone ['drinking']) {
				$drinking .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$drinking .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$drinking .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$drinking}', $drinking, $cover );
		
		##运动
		$sports= '';
		foreach ( self::$__sports as $key => $value ) {
			if ($value == $alone ['sports']) {
				$sports .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$sports .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$sports .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$sports}', $sports, $cover );
		
		##阅读
		$reading= '';
		foreach ( self::$__reading as $key => $value ) {
			if ($value == $alone ['reading']) {
				$reading .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$reading .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$reading .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$reading}', $reading, $cover );
		
		##生活有规律
		$law= '';
		foreach ( self::$__law as $key => $value ) {
			if ($value == $alone ['law']) {
				$law .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$law .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$law .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$law}', $law, $cover );
		
		##情绪控制
		$emotionalControl= '';
		foreach ( self::$__emotionalControl as $key => $value ) {
			if ($value == $alone ['emotionalControl']) {
				$emotionalControl .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$emotionalControl .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$emotionalControl .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$emotionalControl}', $emotionalControl, $cover );
		
		##性格乐观
		$hopeful= '';
		foreach ( self::$__hopeful as $key => $value ) {
			if ($value == $alone ['hopeful']) {
				$hopeful .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$hopeful .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$hopeful .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$hopeful}', $hopeful, $cover );
		
		##信任他人
		$trustOthers= '';
		foreach ( self::$__trustOthers as $key => $value ) {
			if ($value == $alone ['trustOthers']) {
				$trustOthers .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$trustOthers .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$trustOthers .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$trustOthers}', $trustOthers, $cover );
		
		##合群
		$gregarious= '';
		foreach ( self::$__gregarious as $key => $value ) {
			if ($value == $alone ['gregarious']) {
				$gregarious .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$gregarious .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$gregarious .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$gregarious}', $gregarious, $cover );
		
		##对待疾病态度
		$attitude= '';
		foreach ( self::$__attitude as $key => $value ) {
			if ($value == $alone ['attitude']) {
				$attitude .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$attitude .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$attitude .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$attitude}', $attitude, $cover );
		
		##生活积极性
		$active= '';
		foreach ( self::$__active as $key => $value ) {
			if ($value == $alone ['active']) {
				$active .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$active .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$active .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$active}', $active, $cover );
		
		##处理压力
		$stressManagement= '';
		foreach ( self::$__stressManagement as $key => $value ) {
			if ($value == $alone ['stressManagement']) {
				$stressManagement .= '<w:r w:rsidR="00C647E6">
									<w:rPr>
									<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:sym w:font="Wingdings 2" w:char="F052"/>
									</w:r>';
			} else {
				$stressManagement .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
			}
			$stressManagement .= '<w:r w:rsidRPr="000F6B3B">
								<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:eastAsiaTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:sz w:val="24"/>
								<w:lang w:val="en-GB"/>
								</w:rPr>
								<w:t>'.$value.'</w:t>
								</w:r>';
		}
		$cover = str_ireplace ( '{$stressManagement}', $stressManagement, $cover );
		$cover = str_ireplace ( '{$name}', $this->__cases ['name'], $cover );
		if (! empty ( $this->__cases ['birthday'] )) {
			$age = getAge ( $this->__cases ['birthday'] );
		}
		$cover = str_ireplace ( '{$age}', $age, $cover );
		$cover = str_ireplace ( '{$sex}', $this->__cases ['sex'], $cover );
		$cover = str_ireplace ( '{$serialNumber}', $this->__cases ['serialNumber'], $cover );
		$cover = str_ireplace ( '{$firstTalkDate}', $alone ['firstTalkDate'], $cover );
		$cover = str_ireplace ( '{$question}', $alone ['question'], $cover );
		$cover = str_ireplace ( '{$targets}', $alone ['targets'], $cover );
		$cover = str_ireplace ( '{$servicePlan}', $alone ['servicePlan'], $cover );
		$cover = str_ireplace ( '{$steeringViews}', $alone ['steeringViews'], $cover );
		
		##社工建议及行动
		$this->load->model ( 'CasesSuggestModel', "casesSuggest" );
		$casesSuggest = $this->casesSuggest->getData ( $this->casesSuggest->__casesSuggestTable, array ("casesId" => $this->__cases ['casesId'] ) );
		$casesSuggest = $casesSuggest ['total'] > 0 ? $casesSuggest ['data'] [0] : array();;
		if (! empty ( $casesSuggest ['isNeed'] )) {
			if ($casesSuggest ['isNeed'] == "否") {
				$isNeed = '（ √ ）否；（ ）是【（ ）委派 ／ （ ）转介予';
			} else if ($casesSuggest ['isNeed'] == "委派") {
				$isNeed = '（  ）否；（√ ）是【（ √）委派 ／ （ ）转介予';
			} else {
				$isNeed = '（  ）否；（√ ）是【（ ）委派 ／ （√ ）转介予';
			}
		} else {
			$isNeed = '（ ）否；（ ）是【（ ）委派 ／ （ ）转介予';
		}
		$cover = str_ireplace ( '{$isNeed}', $isNeed, $cover );
		$cover = str_ireplace ( '{$isNeedRemark}', $casesSuggest ['isNeedRemark'], $cover );
		$cover = str_ireplace ( '{$other}', $casesSuggest ['other'], $cover );
		$cover = str_ireplace ( '{$opinionSupervision}', $casesSuggest ['opinionSupervision'], $cover );
		$cover = str_ireplace ( '{$inspectionBodies}', $casesSuggest ['inspectionBodies'], $cover );
		$this->__xmlContent .= $cover;
	}
	
	/**
	 * @desc 
	 */
	public function test(){
		include 'system/libraries/Smarty-3.1.19/libs/Smarty.class.php';
		$smarty = new Smarty;
		$smarty->template_dir="app/views";
//		$smarty->debugging = true;
		$smarty->force_compile = false;
		$smarty->caching = true;
		$smarty->cache_lifetime = 1800;
		if(!$smarty->isCached('test.html')){
	    	echo "no cache";
			$smarty->assign("Name", "Fred Irving Johnathan Bradley Peppergill", true);
			$smarty->assign("FirstName", array("John", "Mary", "James", "Henry", "luobin","Tom"),true);
			$smarty->assign("LastName", array("Doe", "Smith", "Johnson", "Case"));
			$smarty->assign("Class", array(array("A", "B", "C", "D"), array("E", "F", "G", "H"),
			                               array("I", "J", "K", "L"), array("M", "N", "O", "P")));
			
			$smarty->assign("contacts", array(array("phone" => "1", "fax" => "2", "cell" => "3"),
			                                  array("phone" => "555-4444", "fax" => "555-3333", "cell" => "760-1234")));
			
			$smarty->assign("option_values", array("NY", "NE", "KS", "IA", "OK", "TX"));
			$smarty->assign("option_output", array("New York", "Nebraska", "Kansas", "Iowa", "Oklahoma", "Texas"));
			$smarty->assign("option_selected", "sadfasdfasdfadfasdfads",true);
			$smarty->assign("hello", "NE");
		}
		$smarty->display('test.html');
	}
}