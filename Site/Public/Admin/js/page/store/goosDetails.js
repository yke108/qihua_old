$(function(){
	//根据内容自适应高度和行高
	$('.description dl').each(function(index,item){
		$(item).find('dd').css({
			height:$(item).find('dt').height(),
			"line-height":$(item).find('dt').height()+'px'
		});
	});
	$('.images-btn 	span:eq(0)').addClass('btn-choose');
	//点击图片按钮切换图片
	$('.images-btn').on('click','span',function(){
		var index = $(this).index();
		$(this).addClass('btn-choose').siblings().removeClass('btn-choose');
		$('.images img').eq(index).show().siblings().hide();
	});
	//给按钮添加class名
	console.log($('.check-botton a'))
	$.each($('.check-botton a'),function(){
		var html = this.innerHTML;
		switch(html){
			case '撤销通过': $(this).addClass('js_disagree');break;
			case '重审通过': $(this).addClass('js_agree');break;
			case '恢复通过': $(this).addClass('js_agree');break;
			case '审核通过': $(this).addClass('js_agree');break;
			case '审核不通过': $(this).addClass('js_disagree');break;
		}
	})
	  //审核通过
     $(document).on('click', '.js_agree', function() {
        var self = $(this),
            id = self.attr('data-id'),
            status = self.attr('data-status'),
            url = '';
            //根据状态判断url
            switch(status){
            	case '0':url='/Admin/Store/examStatus';
            	break;
            	case '2':url='/Admin/Store/rStatus';
            	break;
            	case '3':url='/Admin/Store/renewStatus';
            	break;
            }
        $.messager.confirm('确认提示', '您确认要通过此商品吗？', function(r){
            if (r){
                var ajaxData = {
                    url: url,
                    data: {
                        id: id
                    }
                }
                ajax(ajaxData).then(function(data) {
                    window.location.reload();
                })
            }
        });


    });
    //审核不通过
    $(document).on('click', '.js_disagree', function() {
        var self = $(this),
            id = self.attr('data-id'),
            reason = $('#js_revokeReason'),
            status = self.attr('data-status');

        $('#js_revokeForm').form('clear');
        var url = '';
        switch(status){
            	case '0':url='/Admin/Store/failStatus';
            	break;
            	case '1':url='/Admin/Store/changeStatus';
            	break;
            }
        console.log(status,url);
        $('#dlg').dialog({
            title: '审核不通过',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons:[{
                text:'审核不通过',
                iconCls:'icon-ok',
                handler:function(){
                    var isValid = $('#js_revokeForm').form('validate');
                    if (!isValid) {
                        return;
                    }
                    var ajaxData = {
                        url: url,
                        data: {
                            id: id,
                            reason: reason.val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        $('#dataGrid').datagrid('reload');
                        $('#dlg').dialog('close');
                    })
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#dlg').dialog('close');
                }
            }]

        });
    });
});