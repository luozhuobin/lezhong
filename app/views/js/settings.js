 /**
 * @desc 删除用户
 * @return
 */
function delMember(memberId,obj){
	if(window.confirm('你确定要删除该记录吗？')){
		$.ajax({
			   type: "POST",
			   url: "?c=Settings&m=delMember",
			   data: "memberId="+memberId,
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