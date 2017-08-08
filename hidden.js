document.getElementById('btnContactModal').addEventListener('click',function(){
	document.getElementById('modalForm').style.display = 'block';
});
function CountChars(el){

	var spanRem = document.getElementById('characters_remaining');
	var len = el.value.length;
	var rem = 1000 - len;
	//var rem = el.value.substring(0,1000)
	console.log('Len: ' + len);
	console.log('Rem: ' + rem);
	spanRem.innerHTML = rem + " characters remaining";
}
function HideModal(){
	var m = document.getElementById('modalForm');
	m.style.display = 'none';
}