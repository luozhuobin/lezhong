/**
 * @desc 新增或者修改表单
 */
function saveInfo(formId,c,m){
	var form = $("#"+formId).serialize();
	$(".error").hide();
	$(".success").hide();
	$.ajax({
	   type: "POST",
	   url: "?c="+c+"&m="+m,
	   data: form,
	   dataType:"json",
	   success: function(callback){
		 $(".input-notification").attr("class","input-notification png_bg none");
	     switch(callback.code){
		     case '1':
		    	 $("#"+formId+"SaveResult").attr("class","notification success png_bg");
		    	 $("#"+formId+"SaveResult").children("div").html(callback.msg);
		    	 $("#"+formId+"SaveResult").show("slow");
		    	 break;
		     case '2':
		    	 $("#"+formId+"SaveResult").attr("class","notification error png_bg");
		    	 $("#"+formId+"SaveResult").children("div").html(callback.msg);
		    	 $("#"+formId+"SaveResult").show("slow");
		    	 break;
		     default:
		     	 $("#"+callback.code+"Info").html(callback.msg);
		     	 $("#"+callback.code+"Info").attr("class","input-notification png_bg error");
		     	 $("#"+callback.code+"Info").show('slow');
		    	 break;
	     }
	     if(callback.code ==1 || callback.code == 2){
		     setTimeout(function(){
		    	 $("#"+formId+"SaveResult").hide("slow");
		    	 $("#"+callback.code+"Info").hide("slow");
		     },2000);
	     }
	     switch(c){
	     	case 'cases':
	     		if($("#tab1_casesId").val() == ''){
	     			$("#tab1_casesId").val(callback.data.opt);
	     		}
	     		if($("#tab2_casesId").val() == ''){
	     			$("#tab2_casesId").val(callback.data.opt);
	     		}
	     		if($("#tab3_casesId").val() == ''){
	     			$("#tab3_casesId").val(callback.data.opt);
	     		}
	    	 break;
	     	case 'caseslog':
	     		if($("#logId").val() == ''){
	     			$("#logId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'casesprocess':
	     		if($("#processId").val() == ''){
	     			$("#processId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'groupregister':
	     		if($("#regId").val() == ''){
	     			$("#regId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'groupsignin':
	     		if($("#signinId").val() == ''){
	     			$("#signinId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'groupreport':
	     		if($("#reportId").val() == ''){
	     			$("#reportId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'groupsuggestion':
	     		if($("#suggestionId").val() == ''){
	     			$("#suggestionId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'activity':
	     		if($("#activityId").val() == ''){
	     			$("#activityId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'activityreport':
	     		if($("#reportId").val() == ''){
	     			$("#reportId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'activitysignin':
	     		if($("#signinId").val() == ''){
	     			$("#signinId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'activityregister':
	     		if($("#regId").val() == ''){
	     			$("#regId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'activitysuggestion':
	     		if($("#suggestionId").val() == ''){
	     			$("#suggestionId").val(callback.data.opt);
	     		}
	     		break;
	     	case 'consultation':
	     		if($("#con_id").val() == ''){
	     			$("#con_id").val(callback.data.opt);
	     		}
	     		break;
	     	case 'volunteer':
	     		if($("#volunteerId").val() == ''){
	     			$("#volunteerId").val(callback.data.opt);
	     		}
	     		break;
	     }
	   }
	});
}
/**
 * @desc 删除列表某一行
 * @return
 */
function delData(c,m,value,obj){
	if(window.confirm('你确定要删除该记录吗？')){
		$.ajax({
			   type: "POST",
			   url: "?c="+c+"&m="+m,
			   data: "value="+value,
			   dataType:"json",
			   success: function(callback){
					if(callback.code == "1"){
						$(obj).parents("tr").hide();
					}else{
						$("#notice").children("div").html(callback.msg);
						$("#notice").show("slow");
						setTimeout(function(){
					    	 $("#notice").hide("slow");
					     },2000);
					}
			   }
			});
	}else{
		return false;
	}
}

function getDetail(c,key,value,obj){
	$.ajax({
		   type: "POST",
		   url: "?c="+c+"&m=getDetail",
		   data: key+"="+value,
		   dataType:"json",
		   success: function(callback){
				if(callback.code == "1"){
					
				}else{
					
				}
		   }
		});
}