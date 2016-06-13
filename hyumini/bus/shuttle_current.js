/* 
 * @Author
 * Author: 안윤근
 * 
 * @Description
 * shuttle.php로 ajax를 보내 현재 운행중인 제일 가까운 셔틀 시간을 가져옵니다
 * 가져온 정보는 테이블로 바꿔서 출력합니다.
 * ( <div id="shuttleNow"></div> 안에 출력합니다. )
 * 
 */

$(document).ready(function(){

		function remainMinutes(time){
			time = time.split(":");
			var now = new Date();
			var nMin = now.getHours() * 60 + now.getMinutes();
			var tMin = time[0] * 60 + time[1]*1;
			return tMin-nMin;
		}

		function on200(json){
//			console.log(json);
			if(json.resultCode==1){
				var table = json.resultTable;
				var courses = Object.keys(table);
//				console.log(table);
				var nowTable = $(document.createElement("table"));
				nowTable.attr("id", "shuttleNowTable");
				$.map(table, function(stations, course){				
					var tr_head = $(document.createElement("tr"));
					var td_head = $(document.createElement("td"));

					td_head.attr("colspan", stations.length);
					td_head.text(course.replace("_","에서 "));
					tr_head.append(td_head);
					nowTable.append(tr_head);

					var tr = $(document.createElement("tr"));
					$.map(stations, function(time, station){
						var td = $(document.createElement("td"));
						td.text(station+": "+remainMinutes(time)+"분");
						tr.append(td);
					});
					
					nowTable.append(tr);
				
				});

				$("#shuttleNow").html(nowTable);
			}
		}
		function on400(json){

		}
		function on404(json){
		}
		function on500(json){
		}

		function shuttleNow(){
			$.ajax({
				url:"./shuttle.php",
				data:{context:"now"},
				dataType:"JSON",
				statusCode:{
					200: on200,
					400: on400,
					404: on404,
					500: on500
				}
			});
		}
		shuttleNow();
		setInterval(shuttleNow, 30000);
	}
);