$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        title: "卖家资格认证", //标题
        url: 'http://www.jeasyui.com/demo/main/datagrid2_getdata.php', //请求路径
        queryParams: {//传输参数
            type: '-1',
            keyword: ''
        },
        columns: [
            [
                { field: 'name', title: '公司名称', align: 'center', width: '22%' },
                { field: 'status', title: '认证状态', align: 'center', width: '15%' },
                { field: 'whp', title: '是否涉及危化品', align: 'center', width: '10%' },
                { field: 'dp', title: '是否涉及易制毒品', align: 'center', width: '15%' },
                { field: 'shg', title: '销售化工品', align: 'center', width: '15%' }, {
                    field: 'operate',
                    title: '操作',
                    width: '20%',
                    align: 'center',
                    formatter: function(value, row, index) {
                        return renderOperateBtns(value, row, index);
                    }
                }
            ]
        ]
    }
    //渲染操作按钮
    function renderOperateBtns(value, row, index) {
        switch (row.operate) {
            case '1':
                return '<div class="operate-wrap"><a href="#"  data-id="' + row.id + '" class="operate-btn js_revoke">撤销通过</a><a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.link + '" data-id="' + row.id + '" class="operate-btn js_iframeLink">详情</a></div>';
                break;
            case '-1':
                return '<div class="operate-wrap"><a href="#"  data-id="' + row.id + '" class="operate-btn js_agree">通过</a><a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.link + '" data-id="' + row.id + '" class="operate-btn js_iframeLink">详情</a></div>';
                break;
            case '0':
                return '<div class="operate-wrap"><a href="#"  data-id="' + row.id + '" class="operate-btn js_disagree">不通过</a><a href="#"  data-id="' + row.id + '" class="operate-btn js_agree">通过</a><a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.link + '" data-id="' + row.id + '" class="operate-btn js_iframeLink">详情</a></div>';
                break;
        }
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);
    //********使用本地数据测试,上线时删除********//
    $('#dataGrid').datagrid('loadData', sellerData);
    //********使用本地数据测试,上线时删除********//

    //初始化分页控件  
    $('#dataGrid').datagrid('getPager').pagination({
        pageSize: 10, //每页显示的记录条数，默认为10 
        pageList: [5, 10, 15], //可以设置每页记录条数的列表 
        layout: ['list', 'sep', 'first', 'prev', 'links', 'next', 'last', 'sep', 'refresh']
    });

    //监听条件选择变化，更新表格数据
    $('#js_statusSelect').combobox({
        onChange: function(newValue, oldValue) {
            changeStatus(newValue)
        }
    });

    //搜索功能
    $('#js_searchCompany').searchbox({
        searcher: function(value, name) {
            changeStatus(null, value);
        },
        prompt: '公司名称'
    });
    //更新状态参数或者搜索关键字 reload表格
    function changeStatus(type, search) {
        var dg = $('#dataGrid');
        var queryParams = {
            type: '-1',
            keyword: ''
        }
        if (type) {
            queryParams.type = type;
        }
        if (search) {
            queryParams.keyword = search;
        }
        dg.datagrid('reload', queryParams);
    }

    
    //初始化审核不通过的input验证信息
    $('#js_revokeReason').validatebox({
        required: true,
        missingMessage: '必须填写'
    });

    //通过
    $(document).on('click', '.js_agree', function() {
        
        var self = $(this),
            id = self.attr('data-id')
        
        $.ajax({
            url: '',
            type: '',
            dataType: 'json',
            data: {}
        })
        .done(function(res) {
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        });
    });
    //撤销通过
    $(document).on('click', '.js_revoke', function() {
        var self = $(this),
            id = self.attr('data-id')
            //先充值表单的值
        $('#js_revokeForm').form('clear');
        $('#dlg').dialog({
            title: '不通过原因',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons: '#dlg-buttons'
        });
    });
    //撤销通过--提交  
    $(document).on('click', '.js_revokeCommit', function() {
        $('#js_revokeForm').form('submit', {
            //设置表单提交地址
            url: 'www.baidu.com',
            onSubmit: function() {
                var isValid = $(this).form('validate');

                $.messager.progress();
                if (!isValid) {
                    // 如果表单是无效的则隐藏进度条
                    $.messager.progress('close'); 
                }
                // 返回false终止表单提交
                return isValid; 
            },
            success: function(data) {
                // 如果提交成功则隐藏进度条
                $.messager.progress('close');
                $('#js_revokeForm').form('clear');
                $('#dlg').dialog('close');
            }
        });
    });
    //撤销通过--取消
    $(document).on('click', '.js_revokeCancel', function() {
        $('#dlg').dialog('close');
    });

    //详情--打开新窗口查看详情
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
            url = $(this).attr("data-href");
            //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
            window.parent.addTab(tabTitle,url);
    });

});
