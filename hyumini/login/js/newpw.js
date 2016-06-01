
document.observe("dom:loaded", function(){
	$("check").observe("click", function(){
		console.log($('newPW').value);
		new Ajax.Request("changepw/", {
			method: "post",
			parameters: {id: getURLParameter('id'), newPW: $('newPW').value},
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

function matchOTPFailed(ajax){
	var obj = JSON.parse(ajax.responseText);
	console.log(obj.reason);
	$('error').innerHTML = "OTP Expired/Not Match";

}

function exceptionError(ajax){
	$('error').innerHTML = "error!! ";

}

function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
}

