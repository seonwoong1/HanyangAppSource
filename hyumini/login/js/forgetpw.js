document.observe("dom:loaded", function(){
	$("send").observe("click", function(){
		new Ajax.Request("sendotp/", {
			method: "post",
			parameters: {id: $("id").value},
			on200: return_success,
			// onFailure: return_success,
			// on200:return_success,
			on400: exceptionError,
			on404: sendOTPFailed
		});
	});
});



function return_success(ajax){
	// json_decode(ajax);
	console.log("inside success");
	// var obj = JSON.parse(ajax.responseText);
	location.replace("inputotp.html?id="+$("id").value);

	
}

function sendOTPFailed(ajax){
	$('error').innerHTML = "send OTP failed";

}

function exceptionError(ajax){
	$('error').innerHTML = "error!! ";

}


