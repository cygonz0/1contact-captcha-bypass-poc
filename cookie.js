function setCookies(cookieName,cookieValue,cookieName2,cookieValue2)
	{
		var today = new Date();
		var expire = new Date();
		expire.setTime(today.getTime() + 3600000 * 356 * 24);
		document.cookie = cookieName+'='+cookieValue+ ';expires='+expire.toGMTString()+ ' ' +cookieName2+'='+cookieValue2+ ';expires='+expire.toGMTString();
		alert(document.cookie);
	}
	function getCookies(key){
		var arr = document.cookie.match(new RegExp("(^| )"+key+"=([^;]*)(;|$)")); 
		if(arr != null) {return(arr[2]); }else{return ""}	
	}