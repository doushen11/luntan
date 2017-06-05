//点击切换当前地理位置
var oBox = document.getElementById('box');
var bottomFenLei = document.getElementsByClassName('bottom_fenlei')[0];
var bottomFenLeiTwo = document.getElementsByClassName('bottom_fenlei_two')[0];
var bottomFenLeiThree = document.getElementsByClassName('bottom_fenlei_three')[0];
function chooseAddress(){
	oBox.style.display = 'block';
	oBox.style.height = $(document).height() + "px";
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