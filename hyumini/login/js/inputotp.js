document.observe("dom:loaded", function(){
	$("check").observe("click", function(){
		new Ajax.Request("checkotp/", {
			method: "get",
			parameters: {id: getURLParameter('id'), otp: $("otp").value},
			on200: return_success,
			on400: exceptionError,
			on404: matchOTPFailed
		});
	});
});


/* 수정자: 안윤근
 * Comment: 세션으로 SID받아서 이렇게 위험하게 할 필요 없어요.
 */
function return_success(ajax){
	// json_decode(ajax);
	console.log("matched");
	var obj = ajax.responseJSON;//JSON.parse(ajax.responseText);
	//location.replace("newpw.html?id="+getURLParameter('id'));	
	location.replace("newpw.html");
}

//수정: 안윤근
function matchOTPFailed(ajax){
	$('error').innerHTML = ajax.responseJSON.reason;
}

//수정: 안윤근
function exceptionError(ajax){
	$('error').innerHTML = ajax.responseJSON.reason;
}

function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
}
