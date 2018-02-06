$(function(){
    var pageGridConfig = {
        footer: '#footerBar',
        url: '/Admin/Data/PhoneLists', //请求路径
        columns: [
            [
                { field: 'phone', title: '手机号', align: 'center', width: '50%' },
                { field: 'operate', title: '操作', align: 'center', width: '46%',
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
                +'<a href="javascript:void(0);"  data-phone="' + row.phone + '" class="operate-btn js_del">删除</a>'
                +'</div>';
    }

    //渲染表格
    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)

    $('#dataGrid').datagrid(config);

    $('#footerBar').css('visibility', 'visible');

    //删除按钮
    $(document).on('click', '.js_del', function() {
        var self = $(this),
            phone = self.attr('data-phone')
            //先重置表单的值
        $.messager.confirm('确认提示', '您确认要删除该数据吗？', function(r) {
            if (r) {
                var ajaxData = {
                    url: '/Admin/Data/DelPhone',
                    data: {
                        phone: phone
                    }
                }
                ajax(ajaxData).then(function(data) {
                    $('#dataGrid').datagrid('reload');
                })
            }
        });
    });
    //新增
    //新增
    $('.js_add').on('click', function() {
        $('#js_revokeForm').form('clear');

        var phone = $('#js_phone')

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
                        url: '/Admin/Data/AddPhone',
                        data: {
                            phone: phone.val()
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
