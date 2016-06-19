/* 
   * @Authorship
   * Author: 성다혜
   * 
*/
$(document).ready(function(){
  $(".toUnivBuInfo").hide();
  $(".toUnivHwaInfo").hide();
  $(".toUnivGongInfo").hide();
  $(".toUnivKangInfo").hide();
  $(".toUnivMyungInfo").hide();
  $(".toUnivBoonInfo").hide();
  $(".toUnivSinInfo").hide();
  $(".toUnivDangInfo").hide();
  $(".toUnivBongInfo").hide();

  $(".toHomeBuInfo").hide();
  $(".toHomeHwaInfo").hide();
  $(".toHomeGwangInfo").hide();
  $(".toHomeGangSaInfo").hide();
  $(".toHomeMyungInfo").hide();
  $(".toHomeBoonInfo").hide();

  $(".toHomeSinInfo").hide();
  $(".toHomeYoungInfo").hide();
  $(".toHomeZamInfo").hide();

  $(".toUnivBu").click(function(){
        $(".toUnivBuInfo").toggle();
        $(".toUnivBuSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "attend",
              course : "부평, 부천"
            },
            success:function(data){
              $('.toUnivBuInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toUnivBuInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toUnivBuInfo').empty();
              $('.toUnivBuInfo').append("error");
            }
        });
  });
  $(".toUnivHwa").click(function(){
        $(".toUnivHwaInfo").toggle();
        $(".toUnivHwaSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "attend",
              course : "화정, 일산"
            },
            success:function(data){
              $('.toUnivHwaInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toUnivHwaInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toUnivHwaInfo').empty();
              $('.toUnivHwaInfo').append("error");
            }
        });
  });
  $(".toUnivGong").click(function(){
        $(".toUnivGongInfo").toggle();
        $(".toUnivGongSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "attend",
              course : "공항, 화곡"
            },
            success:function(data){
              $('.toUnivGongInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toUnivGongInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toUnivGongInfo').empty();
              $('.toUnivGongInfo').append("error");
            }
        });
  });
  $(".toUnivKang").click(function(){
        $(".toUnivKangInfo").toggle();
        $(".toUnivKangSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "attend",
              course : "강남, 사당"
            },
            success:function(data){
              $('.toUnivKangInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toUnivKangInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toUnivKangInfo').empty();
              $('.toUnivKangInfo').append("error");
            }
        });
  });
  $(".toUnivMyung").click(function(){
        $(".toUnivMyungInfo").toggle();
        $(".toUnivMyungSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "attend",
              course : "명일, 잠실"
            },
            success:function(data){
              $('.toUnivMyungInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toUnivMyungInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toUnivMyungInfo').empty();
              $('.toUnivMyungInfo').append("error");
            }
        });
  });
  $(".toUnivBoon").click(function(){
        $(".toUnivBoonInfo").toggle();
        $(".toUnivBoonSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "attend",
              course : "분당, 수지"
            },
            success:function(data){
              $('.toUnivBoonInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toUnivBoonInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toUnivBoonInfo').empty();
              $('.toUnivBoonInfo').append("error");
            }
        });
  });
  $(".toUnivSin").click(function(){
        $(".toUnivSinInfo").toggle();
        $(".toUnivSinSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "attend",
              course : "신갈, 죽전"
            },
            success:function(data){
              $('.toUnivSingInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toUnivSinInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toUnivSinInfo').empty();
              $('.toUnivSinInfo').append("error");
            }
        });
  });
  $(".toUnivDang").click(function(){
        $(".toUnivDangInfo").toggle();
        $(".toUnivDangSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "attend",
              course : "당산, 목동"
            },
            success:function(data){
              $('.toUnivDangInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toUnivDangInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toUnivDangInfo').empty();
              $('.toUnivDangInfo').append("error");
            }
        });
  });
  $(".toUnivBong").click(function(){
        $(".toUnivBongInfo").toggle();
        $(".toUnivBongSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "attend",
              course : "봉천, 신림"
            },
            success:function(data){
              $('.toUnivBongInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toUnivBongInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toUnivBongInfo').empty();
              $('.toUnivBongInfo').append("error");
            }
        });
  });



  $(".toHomeBu").click(function(){
        $(".toHomeBuInfo").toggle();
        $(".toHomeBuSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "leave",
              course : "부평, 부천"
            },
            success:function(data){
              $('.toHomeBuInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toHomeBuInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toHomeBuInfo').empty();
              $('.toHomeBuInfo').append("error");
            }
        });
  });
  $(".toHomeHwa").click(function(){
        $(".toHomeHwaInfo").toggle();
        $(".toHomeHwaSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "leave",
              course : "화정, 일산"
            },
            success:function(data){
              $('.toHomeHwaInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toHomeHwaInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toHomeHwaInfo').empty();
              $('.toHomeHwaInfo').append("error");
            }
        });
  });
  $(".toHomeGwang").click(function(){
        $(".toHomeGwangInfo").toggle();
        $(".toHomeGwangSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "leave",
              course : "화곡, 광명"
            },
            success:function(data){
              $('.toHomeGwangInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toHomeGwangInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toHomeGwangInfo').empty();
              $('.toHomeGwangInfo').append("error");
            }
        });
  });
  $(".toHomeGangSa").click(function(){
        $(".toHomeGangSaInfo").toggle();
        $(".toHomeGangSaSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "leave",
              course : "강남, 사당"
            },
            success:function(data){
              //console.log(data);
              $('.toHomeGangSaInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toHomeGangSaInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toHomeGangSaInfo').empty();
              $('.toHomeGangSaInfo').append("error");
            }
        });
  });
  $(".toHomeMyung").click(function(){
        $(".toHomeMyungInfo").toggle();
        $(".toHomeMyungSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "leave",
              course : "명일, 잠실, 성남"
            },
            success:function(data){
              $('.toHomeMyungInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toHomeMyungInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toHomeMyungInfo').empty();
              $('.toHomeMyungInfo').append("error");
            }
        });
  });
  $(".toHomeBoon").click(function(){
        $(".toHomeBoonInfo").toggle();
        $(".toHomeBoonSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "leave",
              course : "분당, 수지"
            },
            success:function(data){
              $('.toHomeBoonInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toHomeBoonInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toHomeBoonInfo').empty();
              $('.toHomeBoonInfo').append("error");
            }
        });
  });
  $(".toHomeSin").click(function(){
        $(".toHomeSinInfo").toggle();
        $(".toHomeSinSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "leave",
              course : "신촌, 시흥"
            },
            success:function(data){
              $('.toHomeSinInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toHomeSinInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toHomeSinInfo').empty();
              $('.toHomeSinInfo').append("error");
            }
        });
  });

  $(".toHomeYoung").click(function(){
        $(".toHomeYoungInfo").toggle();
        $(".toHomeYoungSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "leave",
              course : "영등포, 광명"
            },
            success:function(data){
              $('.toHomeYoungInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toHomeYoungInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toHomeYoungInfo').empty();
              $('.toHomeYoungInfo').append("error");
            }
        });
  });
  $(".toHomeZam").click(function(){
        $(".toHomeZamInfo").toggle();
        $(".toHomeZamSp").toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
        $.ajax({
            url:'./commute/',
            type: "get",
            dataType: "json",
            data:{
              commuteType : "leave",
              course : "잠실, 사당"
            },
            success:function(data){
              $('.toHomeZamInfo').empty();
              $.each(data["resultTable"], function(key, value) {
                //console.log(key+ ':' + value);
                $('.toHomeZamInfo').append("<p>"+key+"&nbsp;&nbsp;&nbsp;"+value+"</p>");
              });
            },
            error:function(){
              $('.toHomeZamInfo').empty();
              $('.toHomeZamInfo').append("error");
            }
        });
  });
});
