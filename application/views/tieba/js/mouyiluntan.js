//点击模块弹出模块区域
var oBox = document.getElementById('box');
var foot=document.getElementsByClassName("foot")[0];
function quYu() {
	oBox.style.display = 'block';
	foot.style.display='none';
	oBox.style.height = $(document).height() + "px";

}
var bottomFenLei = document.getElementsByClassName('bottom_fenlei')[0];
var bottomFenLeiTwo = document.getElementsByClassName('bottom_fenlei_two')[0];
var bottomFenLeiThree = document.getElementsByClassName('bottom_fenlei_three')[0];

//点击取消退出弹窗
var btnFalse = document.getElementsByClassName('btn_false')[0];
btnFalse.onclick = function() {
	oBox.style.display = 'none';
	foot.style.display='block';

}

//确定一级城市跳到二级城市弹窗部分
var btnTrue = document.getElementsByClassName('btn_true')[0];
btnTrue.onclick = function() {

	bottomFenLei.style.display = 'none';
	bottomFenLeiTwo.style.display = 'block';
}
//二级城市重置按钮
var btnReset= document.getElementsByClassName('btn_reset')[0];
btnReset.onclick = function() {

	bottomFenLei.style.display = 'block';
	bottomFenLeiTwo.style.display = 'none';
}
//二级城市确定按钮，跳转到三级城市
var btnTrueTwo = document.getElementsByClassName('btn_true_two')[0];
btnTrueTwo.onclick = function() {
	bottomFenLeiTwo.style.display = 'none';
	bottomFenLeiThree.style.display = 'block';
}
//三级城市重置按钮
var btnResetThree= document.getElementsByClassName('btn_reset_three')[0];
btnResetThree.onclick = function() {
	bottomFenLei.style.display = 'block';
	bottomFenLeiThree.style.display = 'none';
}

//三级城市确定按钮，更新页面信息
var btnTrueThree = document.getElementsByClassName('btn_true_three')[0];
btnTrueThree.onclick=function(){
	oBox.style.display = 'none';
	bottomFenLei.style.display = 'block';
	bottomFenLeiThree.style.display = 'none';
	foot.style.display='block';
}

//点击模块弹出模块内容
var oBoxMoKuai = document.getElementById('box_mokuai');
var bottomMoKuaiFenLei=document.getElementsByClassName("bottom_mokuai_fenlei")[0];
function moKuai(){
	oBoxMoKuai.style.display = 'block';
	foot.style.display='none';
	oBoxMoKuai.style.height = $(document).height() + "px";
}
//点击模块取消退出页面
var btnFalseMoKuai=document.getElementsByClassName('btn_false_mokuai')[0];
btnFalseMoKuai.onclick=function(){
	oBoxMoKuai.style.display = 'none';
	foot.style.display='block';
}
//点击一级模块确定按钮返回到页面更新内容
var btnTrueMoKuai=document.getElementsByClassName('btn_true_mokuai')[0];
btnTrueMoKuai.onclick=function(){
	oBoxMoKuai.style.display = 'none';
	foot.style.display='block';
}

window.onload=function(){
	var all=document.getElementById("all");
	var navTitle=document.getElementsByClassName("nav_title")[0];
	var person=document.getElementsByClassName("person")[0];
	var daohang=document.getElementsByClassName("daohang")[0];
	var foot=document.getElementsByClassName("foot")[0];
	all.style.height=(window.innerHeight-navTitle.offsetHeight-person.offsetHeight-daohang.offsetHeight-foot.offsetHeight)+"px";
}

//点击切换导航栏
var daoHang=document.getElementsByClassName("daohang")[0];
var daoLi=daoHang.getElementsByTagName("li");
var xunHuan=document.getElementsByClassName("xunhuan")[0];
var jingImg=xunHuan.getElementsByClassName("jing_img");
//默认不是精品贴，让精品图隐藏
for(var k=0;k<jingImg.length;k++) {
	jingImg[k].style.display="none";
}
for (var i=0;i<daoLi.length;i++) {
	daoLi[i].onclick=function(){
		if($(this).index()==2){
			for(var k=0;k<jingImg.length;k++) {
				jingImg[k].style.display="inline-block";
			}
		}
		else{
			for(var k=0;k<jingImg.length;k++) {
				jingImg[k].style.display="none";
			}
		}
		$(this).css("background-color","#308EE3");
		$(this).siblings().css("background-color","#D8D8D8");
	}
}

//取消关注，关注按钮切换
var person=document.getElementsByClassName("person")[0];
var guanZhu=person.getElementsByClassName("guan")[0];
guanZhu.onclick=function(){
	if($(this).attr("isguan")==0){
		$(this).attr("src","img/icon_guanzhu@2x.png");
		$(this).attr("isguan","1");
	}else{
		$(this).attr("src","img/icon_quguan@2x.png");
		$(this).attr("isguan","0");
	}
}
