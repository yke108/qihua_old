$(function(){
    var pageGridConfig = {
        footer: '#footerBar',
        url: '/Admin/Data/indicatorList', //请求路径
        columns: [
            [
                { field: 'name', title: '关键指标名称', align: 'center', width: '20%' },
                { field: 'addTime', title: '添加时间', align: 'center', width: '20%' },
                { field: 'editTime', title: '修改时间', align: 'center', width: '20%' },
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
                +'<a href="javascript:void(0);"  data-id="' + row.id + '" data-name="'+row.name+'" class="operate-btn js_modify">编辑</a>'
                +'</div>';
    }

    //渲染表格
    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)

    $('#dataGrid').datagrid(config);

    $('.js_search').on('click',function(){
        keyword = $('#keyword').textbox('getValue');
        var param = {
            keyword:keyword
        }
        $('#dataGrid').datagrid('load',param);
    });
    $('#footerBar').css('visibility', 'visible');

    //删除按钮
    // $(document).on('click', '.js_del', function() {
    //     var self = $(this),
    //         id = self.attr('data-id')
    //         //先重置表单的值
    //     $.messager.confirm('确认提示', '您确认要删除该数据吗？', function(r) {
    //         if (r) {
    //             var ajaxData = {
    //                 url: '/Admin/Data/removeAppSecret',
    //                 data: {
    //                     id: id
    //                 }
    //             }
    //             ajax(ajaxData).then(function(data) {
    //                 $('#dataGrid').datagrid('reload');
    //             })
    //         }
    //     });
    // });
    //新增
    $('.js_add').on('click', function() {

        var names = $('#addName');

        $('#dlg').dialog({
            title: '新增',
            width: 400,
            height: 300,
            closed: false,
            cache: false,
            modal: true,
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function() {
                    if (!names.val()) {
                        $.messager.alert('提示','关键指标不能为空！');
                        return;
                    }

                    var ajaxData = {
                        url: '/Admin/Data/indicatorAdd',
                        data: {
                            names: names.val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        $('#dataGrid').datagrid('reload');
                        $('#dlg').dialog('close');
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

        $('#js_revokeForm').form('clear');
        names.val('');
    });

    //编辑
    $(document).on('click', '.js_modify', function() {
        var self = $(this),
            id = self.attr('data-id');

        $('#js_revokeForm').form('clear');

        var editName = $('#editName');
        editName.val(self.attr('data-name'));
        $('#editDlg').dialog({
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
                    // var isValid = $('#js_revokeForm').form('validate');
                    if (!editName.val()) {
                        return;
                    }

                    var ajaxData = {
                        url: '/Admin/Data/indicatorEdit',
                        data: {
                            id:id,
                            name: editName.val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        $('#dataGrid').datagrid('reload');
                        $('#editDlg').dialog('close');
                    })
                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function() {
                    $('#editDlg').dialog('close');
                }
            }]

        });
    });

})
