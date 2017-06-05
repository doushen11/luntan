//点击一级城市切换地点
var oBox = document.getElementById('box');
function diquOne(){
	oBox.style.display = 'block';
	oBox.style.height = $(document).height() + "px";
}
//点击二级城市切换地点
var bottomFenLei = document.getElementsByClassName('bottom_fenlei')[0];
var bottomFenLeiTwo = document.getElementsByClassName('bottom_fenlei_two')[0];
var bottomFenLeiThree = document.getElementsByClassName('bottom_fenlei_three')[0];
function diquTwo(){
	oBox.style.display = 'block';
	oBox.style.height = $(document).height() + "px";
	bottomFenLeiTwo.style.display = 'block';
	bottomFenLei.style.display = 'none';
}

//点击三级城市切换地点
function diquThree(){
	oBox.style.display = 'block';
	oBox.style.height = $(document).height() + "px";
	bottomFenLeiThree.style.display = 'block';
	bottomFenLei.style.display = 'none';
}

//点击取消退出弹窗
var btnFalse = document.getElementsByClassName('btn_false')[0];
btnFalse.onclick = function() {
	oBox.style.display = 'none';
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
	$(document.body).css({
		"overflow": "visible"
	});
}

//选择一级模块弹出一级模块弹窗
var oBoxMoKuai = document.getElementById('box_mokuai');
var bottomMoKuaiFenLei=document.getElementsByClassName("bottom_mokuai_fenlei")[0];
var bottomMoKuaiFenLeiTwo=document.getElementsByClassName("bottom_mokuai_fenlei_two")[0];
function mokuaiOne(){
	oBoxMoKuai.style.display = 'block';
	oBoxMoKuai.style.height = $(document).height() + "px";
	bottomMoKuaiFenLei.style.display='block';
	bottomMoKuaiFenLeiTwo.style.display='none';
}

//选择二级模块弹出二级模块弹窗
function mokuaiTwo(){
	oBoxMoKuai.style.display = 'block';
	oBoxMoKuai.style.height = $(document).height() + "px";
	bottomMoKuaiFenLei.style.display='none';
	bottomMoKuaiFenLeiTwo.style.display='block';
}

//点击模块取消退出页面
var btnFalseMoKuai=document.getElementsByClassName('btn_false_mokuai')[0];
btnFalseMoKuai.onclick=function(){
	oBoxMoKuai.style.display = 'none';
}

//点击一级模块确定按钮跳到二级模块
var btnTrueMoKuai=document.getElementsByClassName('btn_true_mokuai')[0];
btnTrueMoKuai.onclick=function(){
	bottomMoKuaiFenLei.style.display='none';
	bottomMoKuaiFenLeiTwo.style.display='block';
}
//点击二级模块确定按钮更新数据到页面
var btnTrueMoKuaiTwo=document.getElementsByClassName('btn_true_mokuai_two')[0];
btnTrueMoKuaiTwo.onclick=function(){
	oBoxMoKuai.style.display = 'none';
}
//点击二级模块返回按钮返回到一级模块页面
var btnFanHuiMoKuai=document.getElementsByClassName('btn_fanhui_mokuai')[0];
btnFanHuiMoKuai.onclick=function(){
	bottomMoKuaiFenLei.style.display='block';
	bottomMoKuaiFenLeiTwo.style.display='none';
}