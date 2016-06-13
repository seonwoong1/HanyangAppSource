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

/*
 * @Author
 * 우승연, 안윤근(리팩토링)
 * @Description
 * 링크 수정하는 김에 리팩토링까지 했습니다.
 */
function return_success(ajax){
	var obj = ajax.responseJSON;
	switch(obj.resultCode*1){
		case 1:
		case 3:
			location.replace("../main/main.html");
			break;
		case 2:
		case 4:
			location.replace("firstlogin.html?SID="+$("id").value);
			break;
		default:
			console.log("err!");
	}
}

function informationNonExist(ajax){
	$('error').innerHTML = "information not exist";

}

function enteredValueError(ajax){
	$('error').innerHTML = "enteredValueError ";

}


