document.observe("dom:loaded", function(){
	$("finish").observe("click", function(){
		new Ajax.Request("setidpw/", {
			method: "post",
			parameters: {id: $("id").value, pw: $('pw').value},
			on200: return_success,
			on400: exceptionError,
			on404: settingFailed
		});
	});
});



function return_success(ajax){
	// json_decode(ajax);
	console.log("inside success");
	var obj = JSON.parse(ajax.responseText);
	location.replace("login.html");
}

function settingFailed(ajax){
	$('error').innerHTML = "setting failed";

}

function exceptionError(ajax){
	$('error').innerHTML = "error!! ";

}


