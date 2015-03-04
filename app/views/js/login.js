/**
 * 用户登录
 * @return
 */
function login(){
	var form = $("#login-form").serialize();
	$(".login-notice").attr("class","input-notification png_bg none");
	$.ajax({
		   type: "POST",
		   url: "?c=admin&m=doLogin",
		   data: form,
		   dataType:"json",
		   success: function(callback){
			 var classname = "red";
			 if(callback.code == "1"){
				 classname = "green";
			 }
			 $("#login-notice").attr("class","notification "+classname+" png_bg");
			 $("#login-notice").html('<span style="color:'+classname+';text-align:center;">'+callback.msg+'</span>');
			 if(callback.code == "1"){
				 window.location.href="?c=cases&m=show";
			 }
		   }
		});
}