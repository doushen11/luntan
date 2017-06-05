//JS控制，默认发送朋友圈全部可见
var all=document.getElementById("all");
var allImg=all.getElementsByTagName("img");
allImg[0].style.display="inline-block";

var tongXunLuYes=document.getElementsByClassName("tongxunlu_yes")[0];
tongXunLuYes.onclick=function(){
	window.location.href="p_tongxunlu.html";
}
var tongXunLuNo=document.getElementsByClassName("tongxunlu_no")[0];
tongXunLuNo.onclick=function(){
	window.location.href="p_tongxunlu.html";
}