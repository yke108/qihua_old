$(function() {
    //初始化表格配置参数
    var pageId = $('.pageId').val(),
        url = '/Admin/Hot/getGoodsHistories?id='+pageId
    var pageGridConfig = {
        url: url, //请求路径
        method: 'get',
        columns: [
            [
                { field: 'addTimeTip', title: '时间', align: 'center', width: '32%' },
                { field: 'operaTip', title: '操作事项', align: 'center', width: '33%' },
                { field: 'operatorTip', title: '操作者', align: 'center', width: '33%' }
            ]
        ],
        singleSelect: false,
        pagination: false,
        rownumbers: false
    }

    
    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)

    //渲染表格
    $('#historyDataGrid').datagrid(config);

    //审核不通过
    $(document).on('click', '.js_disagress', function() {
        var self = $(this),
            id = $('.pageId').val(),
            reason = $('#js_revokeReason');

        $('#js_revokeForm').form('clear');
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
                        url: '/Admin/Hot/failStatus',
                        data: {
                            id: id,
                            reason:reason.val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        window.location.href = window.location.href;
                        $('#dlg').dialog('close');
                    }, function(rs){
                        $('#dlg').dialog('close');
                       // $.messager.alert('提示',rs.msg); 
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

    //审核通过
    $(document).on('click', '.js_agress', function() {
        var self = $(this),

            id = $('.pageId').val()

            $.messager.confirm('确认提示', '您确认要审核通过此商品吗？', function(r){
            if (r){
                var ajaxData = {
                    url: '/Admin/Hot/examStatus',
                    data: {
                        id: id
                    }
                }
                ajax(ajaxData).then(function(data) {
                    window.location.href = window.location.href;
                }, function(rs){
                        $('#dlg').dialog('close');
                        //$.messager.alert('提示',rs.msg); 
                    })
            }
        });
    });

    //重审通过
    $(document).on('click', '.js_reagress', function() {
        var self = $(this),
            id = $('.pageId').val(),
            reason = $('#js_revokeReason');

        $('#js_revokeForm').form('clear');
        $('#dlg').dialog({
            title: '重审通过',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons:[{
                text:'重审通过',
                iconCls:'icon-ok',
                handler:function(){

                    var isValid = $('#js_revokeForm').form('validate');
                    if (!isValid) {
                        return;
                    }
                    var ajaxData = {
                        url: '/Admin/hot/rStatus',
                        data: {
                            id: id,
                            reason:reason.val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        window.location.href = window.location.href;
                        $('#dlg').dialog('close');
                    }, function(rs){
                        $('#dlg').dialog('close');
                        //$.messager.alert('提示',rs.msg); 
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

    //下架
    $(document).on('click', '.js_remove', function() {
        var self = $(this),
            id = $('.pageId').val(),
            reason = $('#js_revokeReason');

        $('#js_revokeForm').form('clear');
        $('#dlg').dialog({
            title: '下架',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons:[{
                text:'下架',
                iconCls:'icon-ok',
                handler:function(){

                    var isValid = $('#js_revokeForm').form('validate');
                    if (!isValid) {
                        return;
                    }
                    var ajaxData = {
                        url: '/Admin/hot/changeOff',
                        data: {
                            id: id,
                            reason:reason.val(),
                            productDepotCode: $('.productDepotCode').html()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        window.location.href = window.location.href;
                        $('#dlg').dialog('close');
                    }, function(rs){
                        $('#dlg').dialog('close');
                        //$.messager.alert('提示',rs.msg); 
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

    //恢复上架
    $(document).on('click', '.js_reNewaAgress', function() {
        var self = $(this),
            id = $('.pageId').val(),
            reason = $('#js_revokeReason');

        $('#js_revokeForm').form('clear');
        $('#dlg').dialog({
            title: '恢复上架',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons:[{
                text:'恢复上架',
                iconCls:'icon-ok',
                handler:function(){

                    var isValid = $('#js_revokeForm').form('validate');
                    if (!isValid) {
                        return;
                    }
                    var ajaxData = {
                        url: '/Admin/hot/renewStatus',
                        data: {
                            id: id,
                            reason:reason.val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        window.location.href = window.location.href;
                        $('#dlg').dialog('close');
                    }, function(rs){
                        $('#dlg').dialog('close');
                        //$.messager.alert('提示',rs.msg); 
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
