window.onload=function(){
	var all=document.getElementById("all");
	var head=document.getElementsByTagName("header")[0];
	all.style.height=(window.innerHeight-head.offsetHeight)+"px";
}