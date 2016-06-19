
$(document).ready(function(){
	$("#한대앞역").hide();
	$("#예술인A").hide();
	$("#순환").hide();
	$("input:radio[value='휴일']").hide();
	$('label[for="휴일"]').hide();

	$("select[name=selectSeme]").change(function(){
		$("input:radio[value='평일']").click();
		if($(this).val() == '학기중'){
			$("input:radio[value='토요일공휴일']").show();
			$("input:radio[value='일요일']").show();
			$("input:radio[value='휴일']").hide();

			$('label[for="토요일공휴일"]').show();
			$('label[for="일요일"]').show();
			$('label[for="휴일"]').hide();

		}else{
			$("input:radio[value='토요일공휴일']").hide();
			$("input:radio[value='일요일']").hide();
			$("input:radio[value='휴일']").show();

			$('label[for="토요일공휴일"]').hide();
			$('label[for="일요일"]').hide();
			$('label[for="휴일"]').show();
		}
	});

	$("input[name=selDay]").click(function(){
		$('#shuttleTableTheadTd').empty();
       	$('#shuttleTableTbody').empty();
		switch($(this).val()){
			case '평일':
				$("#한대앞역").show();
				$("#순환").css("width","");
				$("#예술인A").show();
				$("#순환").show();
				break;
			case '토요일공휴일':
				$("#순환").show();
				$("#순환").css("width","100%");
				$("#한대앞역").hide();
				$("#예술인A").hide();
				break;
			case '일요일':
				$("#순환").show();
				$("#순환").css("width","100%");
				$("#한대앞역").hide();
				$("#예술인A").hide();
				break;
			case '휴일':
				$("#순환").show();
				$("#순환").css("width","100%");
				$("#한대앞역").hide();
				$("#예술인A").hide();
			break;
		}
	});

	$("#한대앞역").click(function(){
		$.ajax({
            url:'./shuttle/',
            type: "get",
            dataType: "json",
            data:{
              context : "choice",
              choice : $("select[name=selectSeme]").val()+"_한대앞역"
            },
            success:function(data){
	           	$('#shuttleTableTheadTd').empty();
	           	$('#shuttleTableTbody').empty();
	           	var univSplit;
	           	var artistSplit;
	           	$.each(data["resultTable"], function(key, value) {
	          		if(key=="셔틀콕"){
	            		univSplit = value.toString().split(",");
	        		}else
	    				artistSplit = value.toString().split(",");
	            	$('#shuttleTableTheadTd').append("<th>"+key+"</th>");
	          		//console.log("key:"+key);
	          		//console.log("val:"+value);
	            });

	            for(var i=0;i<univSplit.length;i++){
	            	var content = "";
	            	content +="<tr>";
	            	content +="<td>"+univSplit[i]+"</td>";
	            	content +="<td>"+artistSplit[i]+"</td>";
	            	content +="</tr>";

	            	$('#shuttleTableTbody').append(content);
	            }
            },
            error:function(){
            	$('#shuttleTableTheadTd').empty();
	           	$('#shuttleTableTbody').empty();
            	$('#shuttleTableTheadTd').append("<th>error</th>");
            }
        });
	});

	$("#예술인A").click(function(){
		$.ajax({
            url:'./shuttle/',
            type: "get",
            dataType: "json",
            data:{
              context : "choice",
              choice : $("select[name=selectSeme]").val()+"_예술인A"
            },
            success:function(data){
	           	$('#shuttleTableTheadTd').empty();
	           	$('#shuttleTableTbody').empty();
	           	var univSplit;
	           	var artistSplit;
	           	$.each(data["resultTable"], function(key, value) {
	          		if(key=="셔틀콕"){
	            		univSplit = value.toString().split(",");
	        		}else
	    				artistSplit = value.toString().split(",");
	            	$('#shuttleTableTheadTd').append("<th>"+key+"</th>");
	          		//console.log("key:"+key);
	          		//console.log("val:"+value);
	            });

	            for(var i=0;i<univSplit.length;i++){
	            	var content = "";
	            	content +="<tr>";
	            	content +="<td>"+univSplit[i]+"</td>";
	            	content +="<td>"+artistSplit[i]+"</td>";
	            	content +="</tr>";

	            	$('#shuttleTableTbody').append(content);
	            }
            },
            error:function(){
            	$('#shuttleTableTheadTd').empty();
	           	$('#shuttleTableTbody').empty();
            	$('#shuttleTableTheadTd').append("<th>error</th>");
            }
        });
	});

	$("#순환").click(function(){
		//console.log("HELLO");
		var selDayVar = $("input[name=selDay]:checked").val();
		var selDayName = "";

		//console.log(selDayVar);

		switch(selDayVar){
			case "평일":
				selDayName = "평일순환";
				break;
			case "토요일공휴일":
				selDayName = "토요일공휴일";
				break;
			case "일요일":
				selDayName = "일요일";
				break;
			case "휴일":
				selDayName = "휴일순환";
		}

		//console.log($("select[name=selectSeme]").val()+selDayName);

		$.ajax({
            url:'./shuttle/',
            type: "get",
            dataType: "json",
            data:{
              context : "choice",
              choice : $("select[name=selectSeme]").val()+"_"+selDayName
            },
            success:function(data){
	           	$('#shuttleTableTheadTd').empty();
	           	$('#shuttleTableTbody').empty();
	           	var dormSplit;
	           	var univSplit;
	           	var stationSplit;
	           	var artistSplit;
	           	$.each(data["resultTable"], function(key, value) {
	          		if(key == "창의원"){
          				dormSplit = value.toString().split(",");
	          		}else if(key=="셔틀콕"){
	            		univSplit = value.toString().split(",");
	        		}else if(key == "한대앞역"){
	        			stationSplit = value.toString().split(",");
	        		}else
	    				artistSplit = value.toString().split(",");
	            	$('#shuttleTableTheadTd').append("<th>"+key+"</th>");
	          		//console.log("key:"+key);
	          		//console.log("val:"+value);
	            });

	            for(var i=0;i<univSplit.length;i++){
	            	var content = "";
	            	content +="<tr>";
	            	content +="<td>"+dormSplit[i]+"</td>";
	            	content +="<td>"+univSplit[i]+"</td>";
	            	content +="<td>"+stationSplit[i]+"</td>";
	            	content +="<td>"+artistSplit[i]+"</td>";
	            	content +="</tr>";

	            	$('#shuttleTableTbody').append(content);
	            }
            },
            error:function(){
            	$('#shuttleTableTheadTd').empty();
	           	$('#shuttleTableTbody').empty();
            	$('#shuttleTableTheadTd').append("<th>error</th>");
            }
        });
	});
	$("input:radio[value='평일']").click();
});