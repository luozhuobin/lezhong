<?php
/**
 * @desc 用户登录退出相关模型
 * @author luobin
 *
 */
class RoundsModel extends CI_Model {

	public $__RoundsTable = "le_record_rounds";
	public $__wordbook = array(
			"姓名"=>"name",
			"性别"=>"sex",
			"出生日期"=>"birthday",
			"联系方式"=>"contact",
			"科室"=>"section",
			"日期"=>"date",
			"入院时间"=>"admissionTime",
			"手术时间"=>"operationTime",
			"出院时间"=>"dischargeTime",
			"诊断"=>"diagnose",
			"过敏史"=>"allergicHistory",
			"禁忌"=>"inhibition",
			"住院照顾"=>"hospitalCare",
			"进食"=>"eat",
			"沐浴"=>"bath",
			"洗衣"=>"wash",
			"服药"=>"dose",
			"支付方式"=>"payment",
			"危机因素"=>"riskFactors",
			"是否接受建议服务"=>"isAccept",
			"患者对病情了解程度"=>"patientsUnderstand",
			"家属对病情了解程度"=>"familyUnderstand",
			"对治疗方案了解程度"=>"treatmentPlanUnderstand",
			"对检查项目了解程度"=>"inspectionItemsUnderstand",
			"对疾病接受程度"=>"acceptanceOfDisease",
			"对治疗接受程度"=>"acceptanceOfTreatment",
			"对手术接受程度"=>"acceptanceOfOperation",
			"对检查接受程度"=>"acceptanceOfCheck",
			"对住院环境评价"=>"evaluationOfHospital",
			"对伙食评价"=>"evaluationOfFood",
			"对医务人员评价"=>"evaluationOfMedical",
			"医患互动"=>"DoctorPatientInteraction",
			"病友互动"=>"patientInteraction",
			"社工建议及行动"=>"RecommendationsAndAction",
			"巡房内容"=>"content",
			"督导意见"=>"suggestion"
		);
    function __construct(){
        parent::__construct();
    }
	    
}