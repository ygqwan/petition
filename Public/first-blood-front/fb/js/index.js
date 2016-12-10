//接口基础路径
//basePath = "http://domob-206.domob-inc.cn:12710/first-blood/index.php?s=home";
//线上接口地址    http://petition.moxz.cn/first-blood/index.php?s=home
basePath = 'http://10.0.0.206:12710/first-blood/index.php?s=home';
showinfo();
var info_next_offset,
	history_next_offset,
	mine_next_offset,
	saveName,
	responseTitle,
	adminCont;

//登录成功获取心愿信息
function showinfo(){
	$.getJSON(basePath+"/petition/running/0/?", function(data){
      console.log(data);
      var runningVoteLength = data.response.petition_info_list.length;
      if(data.response.is_exists == true){
      	$(".loadMore-info").removeClass("hide");
      } else{
      	$(".loadMore-info").addClass("hide");
      }
      for(var i=0;i<runningVoteLength;i++){
      	var voteprecent = parseInt(data.response.petition_info_list[i].petition_info.voted_number)/30*100;
      	var html = '<div class="show-info" d-class="'+data.response.petition_info_list[i].petition_info.petition.id+'">'+
			'<h3 class="info-title">'+data.response.petition_info_list[i].petition_info.petition.title+'</h3>'+
			'<div class="progress">'+
              '<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '+voteprecent+'%;">'+
                '<span class="sr-only">60% Complete</span>'+
              '</div>'+
            '</div>'+
            '<p class="info-num info-distence">'+
            	'<span>已有</span>'+
            	'<span>'+data.response.petition_info_list[i].petition_info.voted_number+'</span>'+
            	'<span>人签字</span>'+
            '</p>'+
            '<p class="info-support">'+
            	'<a href="javascript:void(0);">签字支持</a>'+
            '</p>'+
            '<p class="hide" d-class="'+data.response.petition_info_list[i].petition_info.is_follower+'"><span class="savecurrentTime">'+data.response.petition_info_list[i].petition_info.petition.create_time+'</span><span class="savecurrentName">'+data.response.petition_info_list[i].petition_info.petition.owner.user_name+'</span></p>'+
		'</div>';
		console.log(data.response.petition_info_list[i].petition_info.voted_number)
		$(html).insertBefore($(".loadMore-info"));
      } 
    });
}

//查看历史心声
function showinfoHistory(lengthNum){
	$.getJSON(basePath+"/petition/history/"+lengthNum+"/?", function(data){
		console.log(data)
	  if(data.response.is_exists == true){
      	$(".loadMore-history").removeClass("hide");
      } else{
      	$(".loadMore-history").addClass("hide");
      }
      var runningVoteLength = data.response.petition_info_list.length;
      for(var i=0;i<runningVoteLength;i++){
      	var voteprecent = parseInt(data.response.petition_info_list[i].petition_info.voted_number)/30*100;
      	var html = '<div class="show-info" d-class="'+data.response.petition_info_list[i].petition_info.petition.id+'">'+
			'<h3 class="info-title">'+data.response.petition_info_list[i].petition_info.petition.title+'</h3>'+
			'<div class="progress">'+
              '<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '+voteprecent+'%;">'+
                '<span class="sr-only">60% Complete</span>'+
              '</div>'+
            '</div>'+
            '<p class="info-num info-distence">'+
            	'<span>已有</span>'+
            	'<span>'+data.response.petition_info_list[i].petition_info.voted_number+'</span>'+
            	'<span>人签字</span>'+
            '</p>'+
            '<p class="info-support-result">'+
            	'<a href="javascript:void(0);">查看结果</a>'+
            '</p>'+
		'</div>';
		$(html).insertBefore($(".loadMore-history"));
      } 
    });
}

//判断用户是否登录
$.getJSON(basePath+"/user/info/?", function(data){
    console.log(data);
    if (data.status == 1) {
    	// $(".bg").addClass("hide");
    	// $(".cus-login").addClass("hide");
    	$(".header-login").addClass("hide");
		$(".login-success").removeClass("hide");
		$(".myName").text(data.response.user_info.user_name);
		$(".myEmail").text("("+data.response.user_info.user_email+")");
    	if (data.response.user_info.is_admin == false) {
    		saveName = data.response.user_info.user_name;
			showinfo();
    	} else {
    		$.getJSON(basePath+"/petition/running/0/?", function(data){
		      console.log(data);
		      var runningVoteLength = data.response.petition_info_list.length;
		      if(data.response.is_exists == true){
		      	$(".loadMore-info").removeClass("hide");
		      } else{
		      	$(".loadMore-info").addClass("hide");
		      }
		      for(var i=0;i<runningVoteLength;i++){
		      	var voteprecent = parseInt(data.response.petition_info_list[i].petition_info.voted_number)/30*100;
		      	var html = '<div class="show-info" d-class="'+data.response.petition_info_list[i].petition_info.petition.id+'">'+
					'<h3 class="info-title">'+data.response.petition_info_list[i].petition_info.petition.title+'</h3>'+
					'<div class="progress">'+
		              '<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '+voteprecent+'%;">'+
		                '<span class="sr-only">60% Complete</span>'+
		              '</div>'+
		            '</div>'+
		            '<p class="info-num info-distence">'+
		            	'<span>已有</span>'+
		            	'<span>'+data.response.petition_info_list[i].petition_info.voted_number+'</span>'+
		            	'<span>人签字</span>'+
		            '</p>'+
		            '<p class="info-support">'+
		            	'<a href="javascript:void(0);">签字支持</a>'+
		            '</p>'+
		            '<p class="response-enter">'+
	                    '<a href="javascript:void(0);">回应</a>'+
	                '</p>'+
	                '<p class="hide" d-class="'+data.response.petition_info_list[i].petition_info.is_follower+'"><span class="savecurrentTime">'+data.response.petition_info_list[i].petition_info.petition.create_time+'</span><span class="savecurrentName">'+data.response.petition_info_list[i].petition_info.petition.owner.user_name+'</span></p>'+
				'</div>';
				console.log(data.response.petition_info_list[i].petition_info.voted_number)
				$(html).insertBefore($(".loadMore-info"));
		      } 
		    });
    	}
    	
    } else{
    	//document.documentElement.style.overflow='hidden';
    }
})

//登录
$(".header-login").click(function(){
	var curreentUrl = encodeURIComponent(window.location.href);
	location.href=basePath+'/user/login/sso/1&u='+curreentUrl;
});
//进行中的心声加载更多
$(".loadMore-info").click(function(){
	info_next_offset = $(".show-info").length+1;
	$.getJSON(basePath+"/petition/running/offset/" + info_next_offset + "/?", function(data){
      console.log(data);
      var runningVoteLength = data.response.petition_info_list.length;
      if(data.response.is_exists == true){
      	  //$(".loadMore-info").removeClass("hide");
	      for(var i=0;i<runningVoteLength;i++){
	      	var voteprecent = parseInt(data.response.petition_info_list[i].petition_info.voted_number)/30*100;
	      	var html = '<div class="show-info" d-class="'+data.response.petition_info_list[i].petition_info.petition.id+'">'+
				'<h3 class="info-title">'+data.response.petition_info_list[i].petition_info.petition.title+'</h3>'+
				'<div class="progress">'+
	              '<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '+voteprecent+'%;">'+
	                '<span class="sr-only">60% Complete</span>'+
	              '</div>'+
	            '</div>'+
	            '<p class="info-num info-distence">'+
	            	'<span>已有</span>'+
	            	'<span>'+data.response.petition_info_list[i].petition_info.voted_number+'</span>'+
	            	'<span>人签字</span>'+
	            '</p>'+
	            '<p class="info-support">'+
	            	'<a href="javascript:void(0);">签字支持</a>'+
	            '</p>'+
	            '<p class="hide" d-class="'+data.response.petition_info_list[i].petition_info.is_follower+'"><span class="savecurrentTime">'+data.response.petition_info_list[i].petition_info.petition.create_time+'</span><span class="savecurrentName">'+data.response.petition_info_list[i].petition_info.petition.owner.user_name+'</span></p>'+
			'</div>';
			$(html).insertBefore($(".loadMore-info"));
	      } 
	  	} else {
	  		//$(".loadMore-info").addClass("hide");
	  		alert("没有更多信息了哦！");
	  	}
    });
});

//退出登录
$(".exit").click(function(){
 	var curreentUrl = encodeURIComponent(window.location.href);
 	location.href = basePath + "/user/logout&u="+curreentUrl;;
});

//历史心声
$(".tit-history").click(function(){
	var btnText = $(this).text()
	if (btnText == "查看历史心声") {
		$(this).text("返回可签字心声");
		$(".info").addClass("hide");
		$(".info-history").removeClass("hide");
		//获取头部高度
		var headerHeight = $(".header").height();
		$(window).scrollTop(headerHeight);
	} else{
		$(this).text("查看历史心声");
		$(".info").removeClass("hide");
		$(".info-history").addClass("hide");
	}
	var lengthNum = $(".info-history .show-info").length;
	showinfoHistory(lengthNum);
});

//历史心声加载更多
$(".loadMore-history").click(function(){
	var lengthNum = $(".info-history .show-info").length+1;
	$.getJSON(basePath+"/petition/history/offset/" + info_next_offset + "/?", function(data){
      console.log(data);
      var runningVoteLength = data.response.petition_info_list.length;
      if(data.response.is_exists == true){
	      for(var i=0;i<runningVoteLength;i++){
	      	var voteprecent = parseInt(data.response.petition_info_list[i].petition_info.voted_number)/30*100;
	      	var html = '<div class="show-info" d-class="'+data.response.petition_info_list[i].petition_info.petition.id+'">'+
				'<h3 class="info-title">'+data.response.petition_info_list[i].petition_info.petition.title+'</h3>'+
				'<div class="progress">'+
	              '<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '+voteprecent+'%;">'+
	                '<span class="sr-only">60% Complete</span>'+
	              '</div>'+
	            '</div>'+
	            '<p class="info-num info-distence">'+
	            	'<span>已有</span>'+
	            	'<span>'+data.response.petition_info_list[i].petition_info.voted_number+'</span>'+
	            	'<span>人签字</span>'+
	            '</p>'+
	            '<p class="info-support">'+
	            	'<a href="javascript:void(0);">签字支持</a>'+
	            '</p>'+
			'</div>';
			$(html).insertBefore($(".loadMore-history"));
	      } 
	  	} else {

	  		alert("没有更多信息了哦！");
	  	}
    });
});

//签字支持
var sure_title;
$(".info-support,.info-title").live('click',function(){
	var _this = $(".info-support,.info-title");
	$.getJSON(basePath+"/user/info/?", function(data){
	    console.log(data);
	    if (data.status == 1) {
	    	var is_follower = _this.parent().attr("d-class");
			if (is_follower == "true") {
				$(".sure-name").text("已签字");
				$(".sure-name").removeAttr('onclick');
			};
			var currentId = _this.parent().attr("d-class");
			console.log($(this))
			$.getJSON(basePath+"/petition/detail/id/" + currentId + "/?", function(data){
			    //console.log(data);
			    if (data.status == 1) {
			    	var obj = data.response.petition_info.petition;
			    	var voteprecent = parseInt(data.response.petition_info.voted_number)/30*100;
				    var clientHeight = $(document).height();
					var scrollTop = $(document).scrollTop();
					var top = scrollTop +parseInt($(".sure-sign").css("marginTop"));
					document.documentElement.style.overflow='hidden';
					$(".bg").css("height",clientHeight+'px').removeClass("hide");
					$(".sure-sign").css("marginTop",top+"px").removeClass("hide");
					var startTime = format(parseInt(obj.create_time)*1000);
					var endTime = format(parseInt(obj.end_time)*1000);
					var endPeopleNum = 30-parseInt(data.response.petition_info.voted_number);
					$(".sure-title").text(obj.title);
					sure_title = obj.title;
					$(".sure-sign .sure-sponsor span").eq(0).text(obj.owner.user_name);
					console.log(obj.owner.user_name);
					$(".sure-sign .sure-sponsor span").eq(2).text(startTime);
					$(".sure-num span").eq(0).text(endPeopleNum);
					$(".sure-sign .sure-people span").eq(0).text(data.response.petition_info.voted_number);
					$(".question p").text(obj.desc);
					$(".progress-bar").css("width",voteprecent);
				} else{
					alert(data.message);
				}
		  	});

			//确认签字
			$(".sure-name").unbind("click").click(function(){
				var currentIdNew = parseInt(currentId);
				if($(".nameInput").val() == ""){
					alert("请输入签名！");
					return;
				} else{
					$.post(basePath+"/petition/vote",{id:currentIdNew,user_name:$(".nameInput").val()},
					  function(data){
					    console.log(data);
					    if(data.message ==1){
					    	var clientHeight = $(document).height();
							var scrollTop = $(document).scrollTop();
							var top = scrollTop +parseInt($(".fin-sign").css("marginTop"));
							document.documentElement.style.overflow='hidden';
							$(".sure-sign").addClass("hide");
							$(".bg").css("height",clientHeight+'px').removeClass("hide");
							$(".fin-sign").css("marginTop",top+"px").removeClass("hide");
							$(".fin-sign .parth2").text(sure_title);
					    } else {
					    	alert(data.message);
					    }
					},"json");//这里返回的类型有：json,html,xml,text
					
				}
			});
	    }else{
	    	var curreentUrl = encodeURIComponent(window.location.href);
			location.href=basePath+'/user/login/sso/1&u='+curreentUrl;
	    }
	});
});

//历史心声 查看结果
$(".info-support-result").live("click",function(){
	var currentId = $(this).parent().attr("d-class");
	location.href="result.html?id="+currentId;      //查看哪条结果
});

//发布心声下一步
$(".send-next").click(function(){
	if($(".send-edit-title").val() == ""){
		alert("请输入您的心声标题！");
		return;
	} else if($(".send-edit-dec").val() == ""){
		alert("请输入您心声的描述！");
		return;
	} else{
		var timestamp=new Date().getTime();
		var date = format(timestamp);
		$(".sure-sponsor span").eq(0).text(saveName);
		$(".sure-sponsor span").eq(2).text(date);
		$(".cont-status").attr("src","images/status3.png");
		$(".preview-status").attr("src","images/status1.png");
		$("#show-first").addClass("hide");
		$("#show-second").removeClass("hide");
		$(".send-sec-title").text($(".send-edit-title").val());
		$(".send-sec-dec").text($(".send-edit-dec").val());
	}
});
//点击返回编辑
$(".send-back").click(function(){
	// $(".send-second").children().children(".show-border-bottom").removeClass("border-bottom");
	// $(".send-first").children().children(".show-border-bottom").addClass("border-bottom");
	$(".cont-status").attr("src","images/status1.png");
	$(".preview-status").attr("src","images/status2.png");
	$("#show-first").removeClass("hide");
	$("#show-second").addClass("hide");
});


//发布心声
$(".header-btn").click(function(){
	var clientHeight = $(document).height();
	var scrollTop = $(document).scrollTop();
	var top = scrollTop +parseInt($(".send-imassge").css("marginTop"));
	document.documentElement.style.overflow='hidden';
	$(".bg").css("height",clientHeight+'px').removeClass("hide");
	$(".send-imassge").css("marginTop",top+"px").removeClass("hide");
});

//点击发布
$(".send-print").click(function(){
	var sendTitle = $(".send-edit-title").val();
	var sendDec = $(".send-edit-dec").val();
	$.post(basePath+"/petition/launch",{title:sendTitle,desc:sendDec},
	  function(data){
	    console.log(data);
	    if(data.status ==1){
	    	$("#myTab").addClass("hide");
			$(".tab-content").addClass("hide");
	    	$(".send-fin").removeClass("hide");
			$(".parth2").text($(".send-edit-title").val());
	    } else {
	    	alert(data.message);
	    }
	},"json");//这里返回的类型有：json,html,xml,text
});

//管理员回应入口
var savecurrentName,
	savecurrentTime;
$(".response-enter").live('click',function(){
	var clientHeight = $(document).height();
	var scrollTop = $(document).scrollTop();
	responseTitle = $(this).parent().children(".info-title").text();
	var top = scrollTop +parseInt($(".admin-send-imassge").css("marginTop"));
	document.documentElement.style.overflow='hidden';
	$(".bg").css("height",clientHeight+'px').removeClass("hide");
	$(".admin-send-imassge").css("marginTop",top+"px").removeClass("hide");
	var currentId = $(this).parent().attr("d-class");
	$(".admin-send-imassge").attr("d-class",currentId);
	savecurrentName = $(this).parent().children().children(".savecurrentName").text();
	savecurrentTime = format(parseInt($(this).parent().children().children(".savecurrentTime").text())*1000);
});

//回应心声下一步
$(".admin-next").click(function(){
	
	if($(".admin-edit-dec").val() == ""){
		alert("请输入您心声的描述！");
		return;
	} else{
		adminCont = $(".admin-edit-dec").val().replace(/\n/g, '_@').replace(/\r/g, '_#');;
		var reg=new RegExp("\r\n","g");
		adminCont= adminCont.replace(/_#_@/g, '<br/>');//IE7-8
		adminCont = adminCont.replace(/_@/g, '<br/>');//IE9、FF、chrome
		adminCont = adminCont.replace(/\s/g, '&nbsp;');
		$(".cont-status").attr("src","images/status3.png");
		$(".preview-status").attr("src","images/status1.png");
		// $(".send-second").children().children(".show-border-bottom").addClass("border-bottom");
		// $(".send-first").children().children(".show-border-bottom").removeClass("border-bottom");
		$(".send-sec-title span").eq(1).text(responseTitle);
		$("#admin-show-first").addClass("hide");
		$("#admin-show-second").removeClass("hide");
		$(".send-sec-dec").html(adminCont);
		$(".admin-send-imassge .sure-sponsor span").eq(0).text(savecurrentName);
		$(".admin-send-imassge .sure-sponsor span").eq(2).text(savecurrentTime);
	}
});
//点击返回编辑
$(".admin-back").click(function(){
	$(".cont-status").attr("src","images/status1.png");
	$(".preview-status").attr("src","images/status2.png");
	// $(".send-second").children().children(".show-border-bottom").removeClass("border-bottom");
	// $(".send-first").children().children(".show-border-bottom").addClass("border-bottom");
	$("#admin-show-first").removeClass("hide");
	$("#admin-show-second").addClass("hide");
});

//点击发布
$(".admin-print").click(function(){
	var currentId = parseInt($(".admin-send-imassge").attr("d-class"));
	$.post(basePath+"/petition/reply",{id:currentId,desc:adminCont},function(data){
		console.log(data);
		if(data.status == 1){
			$(".admin-send-fin .parth2").text()
			$(".admin-tab").addClass("hide");
			$(".admin-send-imassge .tab-content").addClass("hide");
			$(".admin-send-fin").removeClass("hide");
			$(".admin-send-fin .parth2").text(responseTitle);
		} else {
			alert(data.message);
		}
		
	},"json");//这里返回的类型有：json,html,xml,text
});

//判断是不是mine跳转过来
var params = location.search.substring(1).split('&');
var things = [];
for (var i in params) {
    _sp = params[i].split('=');
    things[_sp[0]] = _sp[1];
};                                  //表示    things.str;
if(things.is_follower){
	var is_follower = things.is_follower;
	var currentId = things.currentId;
	$(".bg").removeClass("hide");
	$(".sure-sign").removeClass("hide");
	if(is_follower == "true"){
		$(".sure-name").text("已签字");
		$(".sure-name").addClass("disableStatus");
		$(".sure-name").removeClass("sure-name");
	}
	$(".disableStatus").click(function(){
		alert("您已签过字！");
	});
	$.getJSON(basePath+"/petition/detail/id/" + currentId + "/?", function(data){
	    console.log(data);
	    if (data.status == 1) {
	    	var obj = data.response.petition_info.petition;
	    	var voteprecent = parseInt(data.response.petition_info.voted_number)/30*100;
		    var clientHeight = $(document).height();
			var scrollTop = $(document).scrollTop();
			var top = scrollTop +parseInt($(".sure-sign").css("marginTop"));
			document.documentElement.style.overflow='hidden';
			$(".bg").css("height",clientHeight+'px').removeClass("hide");
			$(".sure-sign").css("marginTop",top+"px").removeClass("hide");
			var startTime = format(parseInt(obj.create_time)*1000);
			var endTime = format(parseInt(obj.end_time)*1000);
			var endPeopleNum = 30-parseInt(data.response.petition_info.voted_number);
			$(".sure-title").text(obj.title);
			sure_title = obj.title;
			$(".sure-sign .sure-sponsor span").eq(0).text(obj.owner.user_name);
			console.log(obj.owner.user_name);
			$(".sure-sign .sure-sponsor span").eq(2).text(startTime);
			$(".sure-num span").eq(0).text(endPeopleNum);
			$(".sure-sign .sure-people span").eq(0).text(data.response.petition_info.voted_number);
			$(".question p").text(obj.desc);
			$(".progress-bar").css("width",voteprecent);
		} else{
			alert(data.message);
		}
	});
	//确认签字
	$(".sure-name").unbind("click").click(function(){
		var currentIdNew = parseInt(currentId);
		if($(".nameInput").val() == ""){
			alert("请输入签名！");
			return;
		} else{
			$.post(basePath+"/petition/vote",{id:currentIdNew,user_name:$(".nameInput").val()},
			  function(data){
			    console.log(data);
			    if(data.message ==1){
			    	var clientHeight = $(document).height();
					var scrollTop = $(document).scrollTop();
					var top = scrollTop +parseInt($(".fin-sign").css("marginTop"));
					document.documentElement.style.overflow='hidden';
					$(".sure-sign").addClass("hide");
					$(".bg").css("height",clientHeight+'px').removeClass("hide");
					$(".fin-sign").css("marginTop",top+"px").removeClass("hide");
					$(".fin-sign .parth2").text(sure_title);
			    } else {
			    	alert(data.message);
			    }
			},"json");//这里返回的类型有：json,html,xml,text
			
		}
	});
}

//返回首页
// $(".go-index").click(function(){
// 	window.location.reload(true);
// });

//复制到剪贴板
$('.fin-copy').click(function(){
  var Url2=document.getElementById("copyCont");
  Url2.select(); // 选择对象
  document.execCommand("Copy"); // 执行浏览器复制命令
  //alert("已复制好，可贴粘。");
  // $(this).css("backgroundColor","#84c424");
  $(this).html("已复制");
});	

//关闭
$(".close").click(function(){
	document.documentElement.style.overflow='auto';
	$(this).parent().css("marginTop","-220px");
	$(this).parent().addClass("hide");
	$(".bg").addClass("hide");
	if ($(this).parent().attr("class") == "send-imassge hide") {
		$("#myTab").removeClass("hide");
		$(".send-imassge .tab-content").removeClass("hide");
		$(".send-imassge .tab-content #show-first").removeClass("hide");
		$(".send-imassge .tab-content #show-second").addClass("hide");
		$(".send-imassge").addClass("hide");
		$(".send-imassge .tab-content .send-fin").addClass("hide");
	};
	if ($(this).parent().attr("class") == "admin-send-imassge hide") {
		$(".admin-tab").removeClass("hide");
		$(".admin-send-imassge .tab-content").removeClass("hide");
		$("#admin-show-first").removeClass("hide");
		$("#admin-show-second").addClass("hide");
		$(".admin-send-imassge").addClass("hide");
		$(".admin-send-fin").addClass("hide");
	};
});

//时间戳转换为日期
function add0(m){return m<10?'0'+m:m }
function format(shijianchuo)
{
	//shijianchuo是整数，否则要parseInt转换
	var time = new Date(shijianchuo);
	var y = time.getFullYear();
	var m = time.getMonth()+1;
	var d = time.getDate();
	var h = time.getHours();
	var mm = time.getMinutes();
	return y+'-'+add0(m)+'-'+add0(d);
	//return add0(m)+'月'+add0(d)+'日';
}