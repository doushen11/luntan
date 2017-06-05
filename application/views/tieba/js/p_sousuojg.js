window.onload=function(){
	var all=document.getElementById("all");
	var head=document.getElementsByTagName("header")[0];
	all.style.height=(window.innerHeight-head.offsetHeight)+"px";
}
//选择好地理位置返回到发送朋友圈页面
function fanJg(address){
	$(address).css("background-color","#999999");
	window.location.href="p_fapengyouquan.html";
}

//接收搜索传过来的值
var input_sousuojg=document.getElementsByTagName("input")[0];
input_sousuojg.value=sessionStorage.sousuozhi;

//点击搜索按钮，跳转页面并且存值
var souZhi=document.getElementsByClassName("sou_zhi")[0];
var inputSelect=document.getElementsByTagName("input")[0];
inputSelect.onsearch=souZhi.onclick=function(){
	//将搜索之临时存储在本地里，每次退出应用之后就会消失
	sessionStorage.sousuozhi=input_sousuojg.value;
	window.location.href="p_sousuojg.html";
}