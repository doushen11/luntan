//文本框获取焦点时下划线变色，提示用户已获取焦点
var input_sousuo=document.getElementsByTagName("input")[0];
var sousuo=document.getElementsByClassName("sousuo")[0];
input_sousuo.onfocus=function(){
	sousuo.style.borderBottom="0.02rem solid greenyellow";
}
input_sousuo.onblur=function(){
	sousuo.style.borderBottom="0.02rem solid #999999";
}

//点击搜索按钮，跳转页面并且存值
var souZhi=document.getElementsByClassName("sou_zhi")[0];
var inputSelect=document.getElementsByTagName("input")[0];
inputSelect.onsearch=souZhi.onclick=function(){
	//将搜索之临时存储在本地里，每次退出应用之后就会消失
	sessionStorage.sousuozhi=input_sousuo.value;
	window.location.href="p_sousuojg.html";
}
