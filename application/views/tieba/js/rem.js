
(function(){
// 获取html标签
var oHtml=document.getElementsByTagName('html')[0];
// 计算html标签的字体大小,注意fz的值 不能小于最小字体；
// window.innerWidth    750
var fz=window.innerWidth/7.5;
// 给html标签的字体设置值
oHtml.style.fontSize=fz+'px';
})();
