var all=document.getElementById("all");
window.onload=function(){	
	var head=document.getElementsByTagName("header")[0];
	all.style.height=(window.innerHeight-head.offsetHeight)+"px";
	
	//手动设置默认不显示地理位置
	var firstP=all.getElementsByTagName("p")[0];
	firstP.style.color="#3090E2";
	var firstImg=all.getElementsByTagName("img")[0];
	firstImg.style.display="inline-block";
}
//点击li，切换定位
function chooseAddress(address){
	var allImg=all.getElementsByTagName("img");
	var allP=all.getElementsByTagName("p");
	for (var i=0;i<allImg.length;i++) {
		allImg[i].style.display="none";
		allP[i].style.color="#222222"
	}
	$(address).children().css("color","#3090E2");
	$(address).children().css("display","inline-block");
}
