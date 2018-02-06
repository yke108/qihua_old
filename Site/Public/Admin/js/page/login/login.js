$(function(){
    $('.js_loginSubmit').on('click', function(){
        var username = $('#username').val(),
            password = $('#password').val(),
            captcha = $('#captcha').val(),
            url = $('#url').val()

        if(username == '') {
            alert('请输入用户名')
            return;
        }
        if(password == '') {
            alert('请输入密码')
            return;
        }
        if(captcha == '') {
            alert('请输入验证码')
            return;
        }

        $.post("/Admin/Public/checkLogin",{ url:url,username:username,password:password,captcha:captcha},function(result){
            if(result.error ==0){
                window.location.href = result.url;
            }
            else{
                alert(result.content);
                fleshVerify();
            }
        },'json')
    })
})