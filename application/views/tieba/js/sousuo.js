$(".select_neirong").on("search",function(){
	$(".delete").css("display","none");
	$(".sousuo_history").css("display","none");
	$(".sousuo_jg").css("display","block");
});

window.onload=function(){
	var all=document.getElementById("all");
	var navTitle=document.getElementsByClassName("nav_title")[0];
	all.style.height=(window.innerHeight-navTitle.offsetHeight)+"px";
}