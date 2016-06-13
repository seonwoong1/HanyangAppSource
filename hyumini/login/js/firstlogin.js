/*
 * @Author
 * Author: 우승연
 * Refactoring: 안윤근
 * 
 */
document.observe("dom:loaded", function(){
	$("finish").observe("click", function(){
		var sid = getURLParameter("SID");
		new Ajax.Request("setidpw/", {
			method: "post",
			parameters: {SID:sid, newID: $("id").value, newPW: $('pw').value},
			on200: return_success,
			on400: exceptionError,
			on404: settingFailed
		});
	});
});



function return_success(ajax){
	// json_decode(ajax);
	console.log("inside success");
	var obj = ajax.responseJSON;
	location.replace("login.html");
}

function settingFailed(ajax){
	$('error').innerHTML = ajax.responseJSON.reason;

}

function exceptionError(ajax){
	$('error').innerHTML = ajax.responseJSON.reason;

}

function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
}
