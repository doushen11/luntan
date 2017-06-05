window.onload=function(){
	var all=document.getElementById("all");
	var navTitle=document.getElementsByClassName("nav_title")[0];
	var foot=document.getElementsByClassName("foot")[0];
	all.style.height=(window.innerHeight-navTitle.offsetHeight-foot.offsetHeight)+"px";
}