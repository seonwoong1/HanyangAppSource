/*
 * @Author
 * 우승연, 안윤근
 *
 * @Description
 * 첫 로그인인지 체크하고 OTP를 보냅니다. 
 *
 */
document.observe("dom:loaded", function(){
	$("send").observe("click", function(){
				
		function sendOTPFailed(ajax){
			$('error').innerHTML = ajax.responseJSON.reason;
		}
		function exceptionError(ajax){
			$('error').innerHTML = ajax.responseJSON.reason;
		}

		new Ajax.Request("checkfirst/",{
			method: "get",
			parameters: {id: $("id").value},
			on200: function(ajax){

				function return_success(ajax){
					location.replace("inputotp.html?id="+$("id").value);
				}

				if(ajax.responseJSON.resultCode==1){
					alert("첫 로그인 시 비밀번호는 학번과 같습니다.");
					location.replace("login.html");
				}else{
					new Ajax.Request("sendotp/", {
						method: "post",
						parameters: {id: $("id").value},
						on200: return_success,
						on400: exceptionError,
						on500: exceptionError,
						on404: sendOTPFailed
					});	
				}
			},
			on400: exceptionError,
			on500: exceptionError,
			on404: sendOTPFailed
		});		
		
	});
});