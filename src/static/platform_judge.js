 // (function(){
	    var type = 1;
	    var param = "010102000102000420721173";
	    var sid = 2;
	    var rawuin = 544346483;
	    var qsig = "tencent://groupwpa/?subcmd=all&param=7B2267726F757055696E223A3534343334363438332C2274696D655374616D70223A313436313331373532357D0A";
	    var qun_type = "undefined";

	    

	    // if(sid == 5){
	    // 	document.title = "温馨提示";
	    // 	window.open("http://admin.qun.qq.com/man/jump.html?gc="+rawuin,"_self");
	    // }
		var getUrlParam = function(name, href, noDecode) {
		    var re = new RegExp("(?:\\?|#|&)" + name + "=([^&]*)(?:$|&|#)", "i"),
		    m = re.exec(href);
		    var ret = m ? m[1] : "";
		    return ! noDecode ? decodeURIComponent(ret) : ret
		};

		var jump_from = getUrlParam("jump_from", location.href);
		var auth_key = getUrlParam("auth", location.href);


		var reportData = function(nvalue, error) {
		    var img = new Image();
		    var str = "http://cgi.pub.qq.com/report/bnl?data=" + 0 + "," + nvalue + "," + (error || 0) + "," + p;
		    img.src = str
		};
		var speed = {
		    report: function(f1, f2, f3, f4, time) {
		        var ISD_REPORT_URL = "http://isdspeed.qq.com/cgi-bin/r.cgi?";
		        var isdTransport = new Image();
		        var reportData = f4 + "=" + time;
		        var url = ISD_REPORT_URL + "flag1=" + f1 + "&flag2=" + f2 + "&flag3=" + f3 + "&" + reportData;
		        isdTransport.src = url
		    }
		};



		var ua = navigator.userAgent;
		var p;
		var REGEXP_IOS_QQ = /(iPad|iPhone|iPod).*? QQ\/([\d\.]+)/;
		var isiOSMQ = REGEXP_IOS_QQ.test(ua);
		var isSafari = ua.indexOf("Safari") > -1;
		
		var mobile_q_jump = {
			android:"http://mobile.qq.com/3g",
			ios:"itms-apps://itunes.apple.com/cn/app/qq-2011/id444934666?mt=8",
			winphone:"http://www.windowsphone.com/zh-cn/store/app/qq/b45f0a5f-13d8-422b-9be5-c750af531762",
			pc:"http://mobile.qq.com/index.html"
		};

		if(typeof type == "undefined") type = 1;

		if(ua.indexOf("Android")>-1){
			p = "android";
		}
		else if(ua.indexOf("iPhone")>-1 || ua.indexOf("iPad")>-1 || ua.indexOf("iPod")>-1){
			p = "ios";
		}
		else if(ua.indexOf("Windows Phone") > -1 || ua.indexOf("WPDesktop") > -1){
			p = "winphone";
		}
		else {
			p = "pc";
		}
		
		if(p == "ios"){
			//防止循环
			if(history.pushState && !isiOSMQ)
				history.pushState({},"t","#");
		}
		else if(p == "pc" && qsig != "undefined"){
			window.open(qsig,"_self");
		}

		
		if(type == 1){//手Q
			var isSuccess = true;

			var f = document.createElement("iframe");
			f.style.display = "none";
			document.body.appendChild(f);

			reportData(11780);

			f.onload = function(){
				isSuccess = false;
			};
			setTimeout(function(){
				if(p == "ios" && sid == 2){//ios并且为群名片

					var iosUrl = "mqqapi://card/show_pslcard?src_type=internal&version=1&uin="+ rawuin +"&card_type=group&source=qrcode&jump_from=" + jump_from + "&auth=" + auth_key;

					// ios9以上safari不能通过iframe唤起
					if(isSafari){
						location.href = iosUrl;
					}
					else{
						f.src = iosUrl;				
					}

				}
				else if(p != "pc"){
					var url = window.location.href.split("&");
					var kParam = getUrlParam("k", location.href);
					f.src = "mqqopensdkapi://bizAgent/qm/qr?url=" + encodeURIComponent("http://qm.qq.com/cgi-bin/qm/qr?k=" + kParam+"&jump_from=" + jump_from + "&auth=" + auth_key) ;
				}
				//群
				if(sid == 2 && p != "pc"){
					document.title = "申请加入QQ群";
					document.getElementById("m_container").style.display = "block";
					document.getElementById("update_link").onclick = function(){
						var jumpUrl = mobile_q_jump[p]; 
						if(jumpUrl) window.open(jumpUrl,"_self");
					}
				}

				var now = Date.now();
				setTimeout( function(){
					if((p == "ios" && !isiOSMQ && Date.now() - now < 2000) || (p == "android" && !isSuccess) || ((p == "winphone" && Date.now() - now < 2000))){
						var jumpUrl = mobile_q_jump[p]; 

						reportData(11780, 1);

						if(jumpUrl) {
							setTimeout(function(){
								window.open(jumpUrl,"_self");
							},800);
						}
					}
				} , 1500);
			},1500);
			
		}
		speed.report("7832", "19", "2", "1", Date.now() - window.startTime);
	//})();