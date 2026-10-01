var objekt2 = document;
var objekt3 = document;
var objekt4 = document;
var over='no';
var underover='no';
var divswitch = ' ';
var underdivswitch =' ';
var bName=navigator.appName;
var bVer=parseInt(navigator.appVersion);
var NS4=(bName=="Netscape" && bVer>=4);
var IE4=(bName=="Microsoft Internet Explorer" && bVer>=4);
var NS6=(bName=="Netscape" && !document.layers);

function div_start(div_name1,div_name2,div_name3,div_name4){
if (NS4) {
objekt2=eval("document."+div_name2);
objekt3=eval("document."+div_name3);
objekt4=eval("document."+div_name4);

}
if (IE4) {
objekt2=eval("document.all."+div_name2+".style");
objekt3=eval("document.all."+div_name3+".style");
objekt4=eval("document.all."+div_name4+".style");

}
if (NS6) {
objekt2=document.getElementById(div_name2).style;
objekt3=document.getElementById(div_name3).style;
objekt4=document.getElementById(div_name4).style;

}
}


function div_show(ebene){
over='yes';
if (divswitch != ' '){
	if (NS4) divswitch.visibility="hide";
	if (IE4 || NS6) divswitch.visibility="hidden";
}
if (underdivswitch != ' '){
	if (NS4) underdivswitch.visibility="hide";
	if (IE4 || NS6) underdivswitch.visibility="hidden";
}
if (NS4) ebene.visibility="show";
if (IE4 || NS6) ebene.visibility="visible";
}

function overChecker(ebene) {
	divswitch = ebene;
	over='no';
	setTimeout("div_hide()", 800);
}

function underoverChecker(underebene) {
	underdivswitch = underebene;
	underover='no';
	setTimeout("div_hide()", 2000);
}

function div_hide(){
	if (over=='no' && underover=='no'){
	if (NS4) divswitch.visibility="hide";
	if (IE4 || NS6) divswitch.visibility="hidden";
	}
	if (underover=='no'){
	if (NS4) underdivswitch.visibility="hide";
	if (IE4 || NS6) underdivswitch.visibility="hidden";

}
}

function underdiv_show(underebene){
over='yes';
underover='yes';
if (underdivswitch != ' '){
	if (NS4) underdivswitch.visibility="hide";
	if (IE4 || NS6) underdivswitch.visibility="hidden";
}

if (NS4) underebene.visibility="show";
		if (IE4 || NS6) underebene.visibility="visible";
}
if (underdivswitch != ' '){
	if (NS4) underdivswitch.visibility="hide";
	if (IE4 || NS6) underdivswitch.visibility="hidden";

}
