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
			 var classname = "error";
			 if(callback.code == "1"){
				 classname = "success";
			 }
			 $("#login-notice").attr("class","notification "+classname+" png_bg");
			 $("#login-notice").children("div").html(callback.msg);
			 if(callback.code == "1"){
				 window.location.href="?c=cases&m=show";
			 }
		   }
		});
}