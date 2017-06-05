var all=document.getElementById("all");
var allUl=all.getElementsByTagName("ul")[0];
var allUlLi=allUl.getElementsByTagName("li");
for (var i=0;i<allUlLi.length;i++) {
	allUlLi[i].onclick=function(){
		$(this).css("border-bottom","0.04rem solid #308EE3");
		$(this).siblings().css("border-bottom","0.04rem solid #E5E5E5");
	}
}