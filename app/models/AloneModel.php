<?php
/**
 * @desc 用户登录退出相关模型
 * @author luobin
 *
 */
class AloneModel extends CI_Model {

	public $__aloneTable = "le_record_alone";
	public $__wordbook =   array(
		'casesId'=>'',
		'name'=>'',
		'aliases'=>'',
		'age'=>"",
		'sex'=>'',
		'firstTalkDate'=>'',
		'question'=>'',
		'targets'=>'',
		'servicePlan'=>'',
		'incomeSource'=>'',
		'financialSituation'=>'',
		'subsidize'=>'',
		'activity'=>'',
		'momory'=>'',
		'identifyEnvironmental'=>'',
		'toilet'=>'',
		'cooperation'=>'',
		'dressing'=>'',
		'eat'=>'',
		'personalCleanliness'=>'',
		'diet'=>'',
		'visit'=>'',
		'phoneContact'=>'',
		'visitRelatives'=>'',
		'lettersVisit'=>'',
		'neighborhoodTalk'=>'',
		'neighborhoodFun'=>'',
		'noTalk'=>'',
		'becomeEnemies'=>'',
		'talkToMind'=>'',
		'neetTohelp'=>'',
		'advising'=>'',
		'critique'=>'',
		'phone'=>'',
		'tv'=>'',
		'newspaper'=>'',
		'outdoor'=>'',
		'dinnerParty'=>'',
		'specialEvents'=>'',
		'smoking'=>'',
		'drinking'=>'',
		'sports'=>'',
		'reading'=>'',
		'law'=>'',
		'emotionalControl'=>'',
		'hopeful'=>'',
		'trustOthers'=>'',
		'gregarious'=>'',
		'attitude'=>'',
		'active'=>'',
		'stressManagement'=>'',
		'steeringViews'=>'');
    function __construct(){
        parent::__construct();
    }
	    
}