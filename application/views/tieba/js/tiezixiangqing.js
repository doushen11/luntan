window.onload=function(){
	var all=document.getElementById("all");
	var navTitle=document.getElementsByClassName("nav_title")[0];
	var foot=document.getElementsByClassName("foot")[0];
	all.style.height=(window.innerHeight-navTitle.offsetHeight-foot.offsetHeight)+"px";
}
//点击回复或者评论，可以进行发送消息
var foot=document.getElementsByClassName("foot")[0];
var huiFu=foot.getElementsByClassName("hui_fu")[0];
var huiFuNeiRong=foot.getElementsByClassName("huifu_neirong")[0];
var louPing=document.getElementsByClassName("lou_ping")[0];
var tuXiangJie=document.getElementsByClassName("tu_xiangjie")[0];
var xuangJieSpan=tuXiangJie.getElementsByTagName("span")[0];
louPing.onclick=xuangJieSpan.onclick=foot.onclick=function(){
	huiFu.style.display="none";
	huiFuNeiRong.style.display="flex";
}
