
function charTip(sID){ // SELECTED ID i.e. '#msct'

	arr = document.querySelectorAll('.modalPop'); // arr = IMAGES array
	objD = window.getComputedStyle(document.querySelector(sID)).display; // sID's display PROP; i.e. 'none'
	for (i = 0; i < arr.length; i++){
		a = '#' + arr[i].id; // ARRAY CURRENT ID; i.e '#msnt'
		aD = window.getComputedStyle(arr[i]).display; // ARRAY CURRENT's display PROP; i.e. 'none'
		// OPEN THE REQUESTED IMAGE
		if ((sID == a) && (aD == 'none')){
			oObj = document.querySelector(sID); // OPEN OBJECT
			oObj.style.display = 'block';
			openAnim(oObj);
			continue;
		}
		if ((sID == a) && (aD == 'block')){
			oObj = document.querySelector(sID); // CLOSE OPEN OBJECT
			closeAnim(oObj);
			continue;
		}
		// CLOSE ANY OTHER OPEN IMGs
		if ((sID != a) && (aD == 'block')){
			oObj = document.querySelector(a);
			closeAnim(oObj);
		}
	}
	function openAnim(oObj){

		oObj.style.width = '0';
		oObj.style.overflow = 'hidden';
		var w = 0;
		var h = 0;
		var anim = setInterval(function toNormal(){
			w += 15;
			h += 7;
			oObj.style.width = (w + 'px');
			if (w >= 200){
				oObj.style.height = ('100px');
				clearInterval(anim);
			}
		},1);
	}
	function closeAnim(oObj){

		var w = 200;
		var h = 100;
		oObj.style.width = w + 'px';
		oObj.style.height = h + 'px';
		var anim = setInterval(function toNormal(){
			w -= 15;
			h -= 7;
			oObj.style.width = (w + 'px');
			oObj.style.height = (h + 'px');
			if (w <= 0){
				clearInterval(anim);
				oObj.style.display = 'none';
			}
		},1);
	}
}
var txtCName = document.getElementById('txtCName');
if (txtCName){
	txtCName.addEventListener('keyup', function(e){
		var spanCName = document.querySelector('#spanCName');
		spanCName.innerHTML = e.target.value;
	});
	txtCName.addEventListener('focus', function(e){
		var spanCName = document.querySelector('#spanCName');
		spanCName.innerHTML = e.target.value;
		v = this.value;
		this.select(v);
	});
	txtCName.addEventListener('blur', function(e){
		var spanCName = document.querySelector('#spanCName');
		spanCName.innerHTML = e.target.value;
	});
}
function trCheck(tr,check){

	check.checked === true ? tr.style = 'border: 1px solid #0000AD' : tr.style = 'border: 1px solid #FFF';
}