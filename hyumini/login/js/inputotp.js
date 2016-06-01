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



function return_success(ajax){
	// json_decode(ajax);
	console.log("matched");
	var obj = JSON.parse(ajax.responseText);
	location.replace("newpw.html?id="+getURLParameter('id'));	
}

function matchOTPFailed(ajax){
	$('error').innerHTML = "OTP Expired/Not Match";

}

function exceptionError(ajax){
	$('error').innerHTML = "error!! ";

}

function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
}
