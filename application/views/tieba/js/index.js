window.onload=function(){
	var all=document.getElementById("all");
	var select=document.getElementsByClassName("select")[0];
	all.style.height=(window.innerHeight-select.offsetHeight)+"px";
}

//该方法在游客进入首页点击是触发，判断是否处于登录状态
function isLogin(){
	if(localStorage.userId){	
			console.log(localStorage.userId="1");
	}else{
		//如果没有登录，跳转到登录页面登录
	}
}

//用来接收登录页面传来的用户ID,存储在本地
function userMessage(userId){
	localStorage.userId=userId;
}


//取消关注或者是重新关注
var fenLei=document.getElementsByClassName("fenlei")[0];
var guanZhu=document.getElementsByClassName("guan");
console.log(guanZhu.length);
for (var i=0;i<guanZhu.length;i++) {
	guanZhu[i].onclick=function(){
		if($(this).attr("isguan")==0){
			$(this).attr("src","img/icon_guanzhu@2x.png");
			$(this).attr("isguan","1");
		}else{
			$(this).attr("src","img/icon_quguan@2x.png");
			$(this).attr("isguan","0");
		}
	}
}

//点击内容，切换了论坛内容页面
var neiRong=fenLei.getElementsByClassName("neirong");
var touXiang=fenLei.getElementsByClassName("touxiang");
var neriRongUl=fenLei.getElementsByTagName("ul");
for (var i=0;i<neriRongUl.length;i++) {
	touXiang[i].onclick=neriRongUl[i].onclick=function(){
		window.location.href="mouyiluntan.html";
	}
}