document.onkeydown=function(){
	if(event.keyCode==13)
	{
		$('#loginLink').click();
		return false;
	}
}
$('#loginLink').click(function() {
	var username, pwd, code, pass;
	username = $('#username').val();
	pwd = $('#password').val();
	code = $('#verify').val();
	if (username == '') {
		$('#item-error').html('<span>用户名不能为空</span>').slideDown();
		setTimeout(function() {
			$('#item-error').slideUp();
		}, 3000);
		 $('#username').focus();
		return false;
	}
	if (pwd == '') {
		$('#item-error').html('<span>密码不能为空</span>').slideDown();
		setTimeout(function() {
			$('#item-error').slideUp();
		}, 3000);
		$('#password').focus();
		return false;
	}
	$.post( WEB_URL+'/login', $('#login-form').serialize(), function(res) {
		if (res.code == 'ajaxerror') {
			$('#item-error').html('<span>'+res.msg+'</span>').slideDown();
			setTimeout(function() {
				$('#item-error').slideUp();
			}, 3000);
			return false;
		}
		$('#item-error').html('<span>登录成功，正在跳转...</span>').slideDown();
		if (res.code == 'ajaxok') {
			setTimeout(function() {
				window.location.href = WEB_URL;
			}, 1000);
		}

	}, 'json');
});