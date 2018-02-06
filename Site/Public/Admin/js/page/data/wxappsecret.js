$(function(){
    var pageGridConfig = {
        footer: '#footerBar',
        url: '/Admin/Data/getAppSecretLists', //请求路径
        columns: [
            [
                {field: '_', checkbox: true },
                { field: 'appId', title: 'appId', align: 'center', width: '16%' },
                { field: 'appSecret', title: 'Secret', align: 'center', width: '16%' },
                // { field: 'status', title: '状态', align: 'center', width: '8%' },
                { field: 'addTimeTip', title: '添加时间', align: 'center', width: '20%' },
                { field: 'operate', title: '操作', align: 'center', width: '16%',
                    formatter: function(value, row, index) {
                         return renderOperateBtns(value, row, index);
                    }
                }
            ]
        ],
        singleSelect: false
    }

    //渲染操作按钮
    function renderOperateBtns(value, row, index) {
        return '<div class="operate-wrap">'
                +'<a href="javascript:void(0);"  data-id="' + row.id + '" class="operate-btn js_modify">编辑</a>'
                +'<a href="javascript:void(0);"  data-id="' + row.id + '" class="operate-btn js_del">删除</a>'
                +'</div>';
    }

    //渲染表格
    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)

    $('#dataGrid').datagrid(config);

    $('#footerBar').css('visibility', 'visible');

    //删除按钮
    $(document).on('click', '.js_del', function() {
        var self = $(this),
            id = self.attr('data-id')
            //先重置表单的值
        $.messager.confirm('确认提示', '您确认要删除该数据吗？', function(r) {
            if (r) {
                var ajaxData = {
                    url: '/Admin/Data/removeAppSecret',
                    data: {
                        id: id
                    }
                }
                ajax(ajaxData).then(function(data) {
                    $('#dataGrid').datagrid('reload');
                })
            }
        });
    });
    //新增
    $('.js_add').on('click', function() {
        $('#js_revokeForm').form('clear');

        var appId = $('#js_appId'),
            appSecret = $('#js_appSecret')

        $('#dlg').dialog({
            title: '新增',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function() {
                    var isValid = $('#js_revokeForm').form('validate');
                    if (!isValid) {
                        return;
                    }

                    var ajaxData = {
                        url: '/Admin/Data/addAppSecret',
                        data: {
                            appId: appId.val(),
                            appSecret:appSecret.val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        $('#dataGrid').datagrid('reload');
                        $('#dlg').dialog('close');
                    },function(data){
                        $.messager.confirm(data.msg);
                    })
                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function() {
                    $('#dlg').dialog('close');
                }
            }]

        });
    });

    //编辑
    $(document).on('click', '.js_modify', function() {
        var self = $(this),
            id = self.attr('data-id');

        $('#js_revokeForm').form('clear');

        var appId = $('#js_appId'),
            appSecret = $('#js_appSecret');

        $('#dlg').dialog({
            title: '编辑',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function() {
                    var isValid = $('#js_revokeForm').form('validate');
                    if (!isValid) {
                        return;
                    }

                    var ajaxData = {
                        url: '/Admin/Data/editAppSecret',
                        data: {
                            id:id,
                            appId: appId.val(),
                            appSecret:appSecret.val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        $('#dataGrid').datagrid('reload');
                        $('#dlg').dialog('close');
                    },function(data){
                        $.messager.confirm(data.msg);
                    })
                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function() {
                    $('#dlg').dialog('close');
                }
            }]

        });
    });

})
