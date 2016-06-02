
document.observe("dom:loaded", function(){
	$("check").observe("click", function(){
		console.log($('newPW').value);
		new Ajax.Request("changepw/", {
			method: "post",
			//수정(안윤근): id는 checkotp에서 세션으로 받을게요.
			parameters: {/*id: getURLParameter('id'), */newPW: $('newPW').value},
			on200: return_success,
			// onFailure: return_success,
			// on200:return_success,
			on400: exceptionError,
			on404: matchOTPFailed,
			on500: exceptionError
		});
	});
});



function return_success(ajax){
	// json_decode(ajax);
	console.log("matched");
	location.replace("login.html");

	
}

/* 수정자: 안윤근
 * Comment: 코드 복붙하지 맙시다...
 * 그리고 JSON 파싱할 필요 없이 이미 바로 JSON 으로 주고있어요.
 */
function matchOTPFailed(ajax){
	var obj = ajax.responseJSON;//JSON.parse(ajax.responseText);
	console.log(obj.reason);
	$('error').innerHTML = obj.reason;
}

function exceptionError(ajax){
	$('error').innerHTML = "error!! ";

}

function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
}

