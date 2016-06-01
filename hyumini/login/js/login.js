// window.onload = function() {
// 	$('login').onclick = function() {
// 	$('error').innerHTML = "error!!!";
// 	};
// };
document.observe("dom:loaded", function(){
	$("login").observe("click", function(){
		new Ajax.Request("checklogin/", {
			method: "get",
			parameters: {id: $("id").value, pw: $('pw').value},
			onSuccess: return_success,
			// onFailure: return_success,
			// on200:return_success,
			on400: enteredValueError,
			on404: informationNonExist
		});
	});
});

function for500(ajax){
	console.log("what");
}

function return_success(ajax){
	// json_decode(ajax);
	var obj = JSON.parse(ajax.responseText);

	if ("1" == obj.resultCode){
		console.log("success");
		location.replace("main.html");

	}
	else if (2 == obj.resultCode){
		console.log("first login");
		location.replace("firstlogin.html");
	}
	else if (3 == obj.resultCode){
		console.log("admin");
		location.replace("managermain.html");

	}
	else if (4 == obj.resultCode){
		console.log("admin and first");
		location.replace("firstlogin.html");

	}
	else{
		console.log("errrrrrrrrrr!!!!!!!!!");
	}
}

function informationNonExist(ajax){
	$('error').innerHTML = "information not exist";

}

function enteredValueError(ajax){
	$('error').innerHTML = "enteredValueError ";

}


