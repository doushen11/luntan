var all=document.getElementById("all");
var head=document.getElementsByTagName("header")[0];
window.onload=function(){
	all.style.height=(window.innerHeight-head.offsetHeight)+"px";
}
//循环输出26个英文字母
for(var i=65;i<91;i++){
	var A_Z="";
	A_Z=String.fromCharCode(i)+" ";
	var twentySix=document.getElementsByClassName("twenty_six")[0];
	var liZiMu='<li name='+A_Z+'>'+A_Z+'</li>';
	$(".twenty_six").append(liZiMu);
}

//点击有排英文字母，切换到相对应的位置
var twentySixQie=twentySix.getElementsByTagName("li");
var scrollTop = $(all).scrollTop();//滚动区域滚动范围大小
var scrollHeight = $(all).height();//可见区域的高度　
var windowHeight = all.scrollHeight;//滚动区域总高度
var personFenLei=document.getElementsByClassName("person_fenlei")[0];
for (var i=0;i<twentySixQie.length;i++) {
	twentySixQie[i].onclick=function(){
		var className=$(this).attr("name");
		var gunDongName=personFenLei.getElementsByClassName(className)[0];
		//如果点击
		if(gunDongName){
			if($(gunDongName).offset().top-head.offsetHeight>all.scrollHeight-$(all).height()){
			$(all).scrollTop(all.scrollHeight-$(all).height())
			}else if($(gunDongName).offset().top-head.offsetHeight==0){
				return;
			}
			else{
				$(all).scrollTop($(gunDongName).offset().top-head.offsetHeight+$(all).scrollTop())
			}
		}
		else if(gunDongName==null){
			console.log("找不到该位置");
			return;
		}
	}
}