<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @desc 小组相关类
 * @author zhuobin.luo
 * @link 498512133@qq.com
 * @since 2014-05-14
 */
class Group extends CI_Controller {
	private static $__type = array ("成长", "教育", "互助", "治疗" );
	private static $__xmlCoontent = '';
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'GroupModel', "group" );
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
		$list = $this->group->getData ( $this->group->__groupTable,$where, $offset );
		$data ['group'] = $list ['data'];
		$links = $this->getPageList ( $list ['total'], $offset );
		$data ['links'] = $links;
		$c = $this->input->get ( 'c', TRUE );
		$m = $this->input->get ( 'm', TRUE );
		$key = $this->group->getPrimaryName ( $this->group->__groupTable );
		$data ['c'] = $c;
		$data ['m'] = $m;
		$data ['primaryName'] = $key;
		$this->load->view ( 'group-show', $data );
	}
	/**
	 * @desc 详情
	 */
	public function detail() {
		$this->load->view ( 'group-detail' );
	}
	/**
	 * @desc 新增或者修改内容
	 */
	public function edit() {
		$data = array ();
		$key = $this->group->getPrimaryName ( $this->group->__groupTable );
		$value = intval ( $this->input->get ( $key ) );
		if (! empty ( $value )) {
			$data = $this->group->getDataByPrimaryKey ( $this->group->__groupTable, $value );
			$data ["primaryValue"] = $value;
			$data ['primaryName'] = $key;
			$data ['title'] = "编辑小组计划书";
		} else {
			$data ['title'] = "新增小组计划书";
		}
		$data ['__type'] = self::$__type;
		$this->load->view ( 'group-edit', $data );
	}
	/**
	 * @desc 模板导入
	 */
	public function import() {
		$data = $this->xlsImport ( $this->group->__groupTable, $this->group->__wordbook );
		$data ['c'] = $this->input->get ( "c", TRUE );
		$this->load->view ( 'import', $data );
	}
	/**
	 * @desc 模板导出
	 * @id 数据Id
	 */
	public function export() {
		$this->groupId = $this->input->get ( "groupId", TRUE );
		if (! empty ( $this->groupId )) {
			$this->__group = $this->group->getDataByPrimaryKey ( $this->group->__groupTable, $this->groupId );
			if (! empty ( $this->__group )) {
				$fileName = $this->__group ['name'] . ".doc";
				$this->__xmlContent = file_get_contents("group.xml");
				##封面
				$this->cover ();
				##小组计划书
				$this->prospectus();	
				##小组报告书
				$this->report();
				##小组报名表
				$this->register();	
				##小组签到表
				$this->signin();			
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
			$isSuccess = $this->group->save ( $this->group->__groupTable, $post );
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
			$isDel = $this->group->del ( $this->group->__groupTable, $value );
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
	
	/**
	 * @desc 封面
	 */
	public function cover() {
		##起止日期
		$openDate = empty ( $this->__group ['openDate'] ) ? '___________________' : $this->__group ['openDate'];
		##个案编号
		$serialNumber = empty ( $this->__group ['serialNumber'] ) ? '___________________' : $this->__group ['serialNumber'];
		##跟进社工
		$socialWorker = empty ( $this->__group ['socialWorker'] ) ? '___________________' : $this->__group ['socialWorker'];
		##督导
		$supervisor = empty ( $this->__group ['supervisor'] ) ? '___________________' : $this->__group ['supervisor'];
		$search = array ('{$openDate}', '{$serialNumber}', '{$socialWorker}', '{$supervisor}' );
		$replace = array ($openDate,  $serialNumber, $socialWorker, $supervisor );
		$this->__xmlContent = str_ireplace ( $search, $replace, $this->__xmlContent );
	}
	/**
	 * @desc 计划书
	 */
	public function prospectus(){
		foreach ( $this->__group as $key => $value ) {
			if ($key == "type") {
				$type = '';
				foreach(self::$__type as $k => $val){
					if($val == $value){
						$type .= '<w:r w:rsidR="003234CB">
								<w:rPr>
								<w:rFonts w:hint="eastAsia"/>
								</w:rPr>
								<w:sym w:font="Wingdings 2" w:char="F052"/>
								</w:r>';
					}else{
						$type .= '<w:r>
									<w:rPr>
									<w:rFonts w:ascii="宋体" w:hAnsi="宋体" w:hint="eastAsia"/>
									<w:sz w:val="24"/>
									<w:lang w:val="en-GB"/>
									</w:rPr>
									<w:t>□</w:t>
									</w:r>';
					}
					$type .= '<w:r w:rsidR="003234CB">
								<w:rPr>
								<w:rFonts w:hint="eastAsia"/>
								</w:rPr>
								<w:t>'.$val.'</w:t>
								</w:r>';
				}
				$value = $type;
			}
			$this->__xmlContent = str_ireplace ( '{$' . $key . '}', $value, $this->__xmlContent );
		}
	}
	/**
	 * @desc 小组报告书
	 */
	public function report(){
		$this->load->model ( 'GroupReportModel', "groupReport" );
		$groupReport = $this->groupReport->getData ( $this->groupReport->__groupReportTable, array ("groupId" => $this->__group ['groupId'] ) );
		$report = $groupReport ['total'] > 0 ? $groupReport ['data'] : array();
		foreach($this->groupReport->__wordbook as $key=>$value){
			$this->__xmlContent = str_ireplace ( '{$'.$value.'}', $report[$value], $this->__xmlContent );
		}
	}
	/**
	 * @desc 小组报名表
	 */
	public function register(){
		$this->load->model ( 'GroupRegisterModel', "groupRegister" );
		$groupRegister = $this->groupRegister->getData ( $this->groupRegister->__groupRegisterTable, array ("groupId" => $this->__group ['groupId'] ) );
		$register = $groupRegister ['total'] > 0 ? $groupRegister ['data'] : array();;
		$total = $groupRegister['total'] > 15 ? $groupRegister['total'] : 15;
		$registerForm = '';
		$str = '<w:tr w:rsidR="0084205F" w:rsidRPr="0084205F" w:rsidTr="001E6939">
				<w:trPr>
				<w:trHeight w:hRule="exact" w:val="454"/>
				</w:trPr>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="181" w:type="pct"/>
						<w:tcBorders>
							<w:right w:val="single" w:sz="2" w:space="0" w:color="auto"/>
						</w:tcBorders>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="0084205F" w:rsidRPr="0084205F" w:rsidRDefault="0084205F" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r w:rsidRPr="0084205F">
							<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>{$id}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="708" w:type="pct"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="0084205F" w:rsidRPr="0084205F" w:rsidRDefault="001504CC" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
							<w:rPr>
							<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
							<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
							<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
							<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>{$applican}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="1122" w:type="pct"/>
						<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="0084205F" w:rsidRPr="0084205F" w:rsidRDefault="001504CC" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
							<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
							<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>{$phone}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="523" w:type="pct"/>
						<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="0084205F" w:rsidRPr="0084205F" w:rsidRDefault="006B215A" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
								<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>{$sex}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="1345" w:type="pct"/>
						<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="0084205F" w:rsidRPr="0084205F" w:rsidRDefault="006B215A" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
								<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>{$conditions}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="1121" w:type="pct"/>
						<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="0084205F" w:rsidRPr="0084205F" w:rsidRDefault="00896694" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
							<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia"/>
								<w:szCs w:val="21"/>
							</w:rPr>
						</w:pPr>
						<w:r>
							<w:rPr>
								<w:rFonts w:asciiTheme="minorEastAsia" w:hAnsiTheme="minorEastAsia" w:hint="eastAsia"/>
								<w:szCs w:val="21"/>
							</w:rPr>
							<w:t>{$followUp}</w:t>
						</w:r>
					</w:p>
				</w:tc>
			</w:tr>';
		$search = array('{$id}','{$applican}','{$phone}','{$sex}','{$conditions}','{$followUp}');
		for($i = 0; $i < $total ;$i++){
			$id = $i+1;
			$replace = array(
				$id,
				$register[$i]['applican'],
				$register[$i]['phone'],
				$register[$i]['sex'],
				$register[$i]['conditions'],
				$register[$i]['followUp'],
			);
			$registerForm .= str_ireplace ( $search, $replace, $str );
		}
		$this->__xmlContent = str_ireplace ( '{$registerForm}', $registerForm, $this->__xmlContent );
		$this->__xmlContent = str_ireplace ( '{$socialWorker}', $this->__group['socialWorker'], $this->__xmlContent );
		$this->__xmlContent = str_ireplace ( '{$serialNumber}', $this->__group['serialNumber'], $this->__xmlContent );
	}
	
	/**
	 * @desc 签到表
	 */
	public function signin(){
		$this->load->model ( 'GroupSigninModel', "groupSignin" );
		$groupSignin = $this->groupSignin->getData ( $this->groupSignin->__groupSigninTable, array ("groupId" => $this->__group ['groupId'] ) );
		$signin = $groupSignin ['total'] > 0 ? $groupSignin ['data'] : array();;
		$total = $groupSignin['total'] > 15 ? $groupSignin['total'] : 15;
		$signinForm = '';
		$str = '<w:tr w:rsidR="001E6939" w:rsidRPr="00C11BD8" w:rsidTr="001E6939">
				<w:trPr>
					<w:trHeight w:hRule="exact" w:val="454"/>
				</w:trPr>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="0" w:type="auto"/>
						<w:tcBorders>
							<w:right w:val="single" w:sz="2" w:space="0" w:color="auto"/>
						</w:tcBorders>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="001E6939" w:rsidRPr="00853C54" w:rsidRDefault="001E6939" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
						</w:pPr>
						<w:r w:rsidRPr="00853C54">
							<w:t>{$id}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="0" w:type="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="001E6939" w:rsidRPr="00C11BD8" w:rsidRDefault="003D22D7" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
						</w:pPr>
						<w:r>
							<w:rPr>
							<w:rFonts w:hint="eastAsia"/>
							</w:rPr>
							<w:t>{$participantName}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="0" w:type="auto"/>
						<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="001E6939" w:rsidRPr="00C11BD8" w:rsidRDefault="001A6F03" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
						</w:pPr>
						<w:r>
							<w:rPr>
								<w:rFonts w:hint="eastAsia"/>
							</w:rPr>
							<w:t>{$section_1}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="0" w:type="auto"/>
						<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="001E6939" w:rsidRPr="00C11BD8" w:rsidRDefault="007B3FAA" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
						</w:pPr>
						<w:r>
							<w:rPr>
								<w:rFonts w:hint="eastAsia"/>
							</w:rPr>
							<w:t>{$section_2}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="0" w:type="auto"/>
						<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="001E6939" w:rsidRPr="00C11BD8" w:rsidRDefault="003169A2" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
						</w:pPr>
						<w:r>
							<w:rPr>
								<w:rFonts w:hint="eastAsia"/>
							</w:rPr>
							<w:t>{$section_3}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="0" w:type="auto"/>
						<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="001E6939" w:rsidRPr="00C11BD8" w:rsidRDefault="006333DE" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
						</w:pPr>
						<w:r>
							<w:rPr>
								<w:rFonts w:hint="eastAsia"/>
							</w:rPr>
							<w:t>{$section_4}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="0" w:type="auto"/>
						<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="001E6939" w:rsidRPr="00C11BD8" w:rsidRDefault="00FD34A4" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
						</w:pPr>
						<w:r>
							<w:rPr>
							<w:rFonts w:hint="eastAsia"/>
							</w:rPr>
							<w:t>{$section_5}</w:t>
						</w:r>
					</w:p>
				</w:tc>
				<w:tc>
					<w:tcPr>
						<w:tcW w:w="0" w:type="auto"/>
						<w:shd w:val="clear" w:color="auto" w:fill="auto"/>
						<w:vAlign w:val="center"/>
					</w:tcPr>
					<w:p w:rsidR="001E6939" w:rsidRPr="00C11BD8" w:rsidRDefault="00EF4C2F" w:rsidP="00B15C6B">
						<w:pPr>
							<w:jc w:val="center"/>
						</w:pPr>
						<w:r>
							<w:rPr>
								<w:rFonts w:hint="eastAsia"/>
							</w:rPr>
							<w:t>{$section_6}</w:t>
						</w:r>
					</w:p>
				</w:tc>
			</w:tr>';
		$search = array('{$id}','{$participantName}','{$section_1}','{$section_2}','{$section_3}','{$section_4}','{$section_5}','{$section_6}');
		for($i = 0; $i < $total ;$i++){
			$id = $i+1;
			$replace = array(
				$id,
				$signin[$i]['participantName'],
				$signin[$i]['section_1'],
				$signin[$i]['section_2'],
				$signin[$i]['section_3'],
				$signin[$i]['section_4'],
				$signin[$i]['section_5'],
				$signin[$i]['section_6'],
			);
			$signinForm .= str_ireplace ( $search, $replace, $str );
		}
		$this->__xmlContent = str_ireplace ( '{$signinForm}', $signinForm, $this->__xmlContent );
		$this->__xmlContent = str_ireplace ( '{$socialWorker}', $this->__group['socialWorker'], $this->__xmlContent );
		$this->__xmlContent = str_ireplace ( '{$serialNumber}', $this->__group['serialNumber'], $this->__xmlContent );
		
		$this->load->model ( 'GroupSectionModel', "groupSection" );
		$groupSection = $this->groupSection->getData ( $this->groupSection->__groupSectionTable, array ("groupId" => $this->__group ['groupId'] ) );
		$section = $groupSection ['total'] > 0 ? $groupSection ['data'][0] : array();
		if(!empty($section)){
			foreach($section as $key=>$value){
				$value = empty($value)?'      ':$value;
				$this->__xmlContent = str_ireplace ( '{$'.$key.'}', $value, $this->__xmlContent );
			}
		}
	}
}