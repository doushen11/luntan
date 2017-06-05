window.onload=function(){
	var all=document.getElementById("all");
	var head=document.getElementsByTagName("header")[0];
	all.style.height=(window.innerHeight-head.offsetHeight)+"px";
}
//点击帖子更换我的收藏信息
function qieHuanTieZi(tiezi){
	$(tiezi).css("background-color","#FFFFFF");
	$(tiezi).css("color","#3090E2");
	$(tiezi).siblings().css("background-color","#3090E2");
	$(tiezi).siblings().css("color","#FFFFFF");
	$(".xunhuan").css("display","block");
	$(".xunhaun_dongtai").css("display","none");
}
//点击动态更换我的收藏信息
function qieHuanDongTai(dongtai){
	$(dongtai).css("background-color","#FFFFFF");
	$(dongtai).css("color","#3090E2");
	$(dongtai).siblings().css("background-color","#3090E2");
	$(dongtai).siblings().css("color","#FFFFFF");
	$(".xunhuan").css("display","none");
	$(".xunhaun_dongtai").css("display","block");
}