$(function() {

    //关于我们
    ~(function(){
        //创建编辑器
        var aboutEditor = kindEditor('editProtol', 400, 300);

        $('.js_saveEditProtol').on('click', function(){
        	var title = $('.js_newsDialogTitle').val(),
                content = aboutEditor.html();

            $.post('protocol',{title:title,content:content}, function(data){

                $.messager.alert('提示', data.msg,'info');
    	    })
        })
    })();

});

