window.onload=function(){
	var all=document.getElementById("all");
	var head=document.getElementsByTagName("header")[0];
	all.style.height=(window.innerHeight-head.offsetHeight)+"px";
}
//显示朋友圈时根据发表图像的多少来设置li的宽度，对图像进行pailie
function tuXiangSuanFa(){
	var tuXiang=document.getElementsByClassName("tuxiang");
	for (var i=0;i<tuXiang.length;i++) {
		var imgCount=tuXiang[i].getElementsByTagName("img").length;
		if(imgCount===4){
			tuXiang[i].style.width=3.4+"rem";
		}
	}
}

//朋友圈加载视频时，图片上定位播放按钮
var  video=document.getElementsByTagName("video");
for (var i=0;i<video.length;i++) {
	$(video[i]).next().css({
        right:($(video[i]).width()+$(video[i]).next().width())/2,
        bottom:($(video[i]).height()-$(video[i]).next().height())/2
	});
}
//定位图片后点击播放视频]
var oBox=document.getElementById('box');
var vd=oBox.getElementsByTagName("video")[0];
function boVideo(videoPath){
    oBox.style.display='flex';
	$(vd).attr("src",$(videoPath).prev().children().attr("src"));
	vd.play();
}
oBox.onclick=function(){
	oBox.style.display="none";
	vd.load();
	vd.pause();
}
