$(function () {
    //初始化表格配置参数
    var pageGridConfig = {
        url: '/Admin/BuyOffer/findGoods', //请求路径
        columns: [
            [
                {field: 'number', title: '信息编号', align: 'center', width: '15%'}, {
                field: 'title',
                title: '标题',
                align: 'center',
                width: '15%',
                formatter: function (value, row, index) {
                    return '<a href="javascript:void(0);" data-title="求购详情-' + row.title + '" data-href="/Admin/BuyOffer/details?id=' + row.id + '" data-id="' + row.id + '" class=" js_iframeLink">' + row.title + '</a>'
                }
            },
                {field: 'type', title: '信息类型', align: 'center', width: '15%'},
                {field: 'companyName', title: '公司名称', align: 'center', width: '15%'}, {
                field: 'createTime',
                title: '创建时间',
                align: 'center',
                width: '15%'
            }, {
                field: 'state',
                title: '状态',
                align: 'center',
                width: '12%',
                formatter: function (value, row, index) {
                    var state = row.state;
                    var reason = row.reason;
                    var str = '';
                    switch (state) {
                        //正常
                        case '1':
                            str = '正常';
                            break;
                        //待审核
                        case '2':
                            str = '待审核';
                            break;
                        //已下架
                        case '3':
                            str = '已过期';
                            break;
                        case '4':
                            str = '撤销通过';
                            break;
                        case '0':
                            str = '审核不通过';
                            break;
                    }

                    if(reason){
                        str+='(原因：'+reason+')'
                    }

                    return str;
                }
            }, {
                field: 'operate',
                title: '操作',
                width: '12%',
                align: 'center',
                formatter: function (value, row, index) {
                    return renderOperateBtns(value, row, index);
                }
            }
            ]
        ],
        singleSelect: false,
        footer: '#footerBar'
    }
    //渲染操作按钮
    function renderOperateBtns(value, row, index) {
        var review = row.state;
        var str = '';
        switch (review) {
            //待审核
            case '2':
                str = '<a href="javascript:void(0);"  data-review="1"  data-id="' + row.id + '" class="operate-btn js_agree">审核通过</a>\
                        <a href="javascript:void(0);" data-review="0" data-id="' + row.id + '" class="operate-btn js_disagree">审核不通过</a>'
                break;
            //有效
            case '1':
                str = '<a href="javascript:void(0);" data-review="4"  data-id="' + row.id + '" class="operate-btn js_disagree">撤销通过</a>';
                break;
            //已过期
            case '3':
                //str = '<a href="javascript:void(0);" data-review="2"  data-id="' + row.id + '" class="operate-btn js_agree">审核通过</a>';
                break;
            //审核不通过
            case '4':
                str = '<a href="javascript:void(0);" data-review="1"  data-id="' + row.id + '" class="operate-btn js_agree">恢复通过</a>';
                break;
            //审核不通过
            case '0':
                str = '<a href="javascript:void(0);" data-review="1"  data-id="' + row.id + '" class="operate-btn js_agree">恢复通过</a>';
                break;

        }
        return '<div class="operate-wrap">' + str + '</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);


    //打开新窗口查看编辑或者预览
    $(document).on('click', '.js_iframeLink', function () {
        var tabTitle = $(this).attr('data-title')
        url = $(this).attr("data-href");
        //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
        window.parent.addTab(tabTitle, url);
    });


    //审核通过
    $(document).on('click', '.js_agree', function () {
        var self = $(this),
            id = self.attr('data-id'),
            state = self.attr('data-review')
        //先重置表单的值
        $.messager.confirm('确认提示', '您确认要通过吗？', function (r) {
            if (r) {
                var ajaxData = {
                    url: '/Admin/BuyOffer/review',
                    data: {
                        id: id,
                        state: state
                    }
                }
                ajax(ajaxData).then(function (data) {
                    $('#dataGrid').datagrid('reload');
                })
            }
        });
    });


    //审核不通过
    $(document).on('click', '.js_disagree', function () {
        var self = $(this),
            id = self.attr('data-id'),
            state = self.attr('data-review')

        $('#js_revokeForm').form('clear');

        var reason = $('#js_revokeReason');


        $('#dlg').dialog({
            title: '审核',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons: [{
                text: '审核',
                iconCls: 'icon-ok',
                handler: function () {
                    var isValid = $('#js_revokeForm').form('validate');
                    if (! isValid) {
                        return;
                    }

                    var ajaxData = {
                        url: '/Admin/BuyOffer/review',
                        data: {
                            id: id,
                            state: state,
                            reason: reason.val()
                        }
                    }
                    ajax(ajaxData).then(function (data) {
                        $('#dataGrid').datagrid('reload');
                        $('#dlg').dialog('close');
                    })
                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function () {
                    $('#dlg').dialog('close');
                }
            }]

        });
    });

    //搜索功能 提交表格
    $('#js_userListSearch').on('click', function () {
        var no = $('#js_no').textbox('getValue'),
            title = $('#js_title').textbox('getValue'),
            type = $('#js_typeSelect').combobox('getValue'),
            username = $('#js_userName').textbox('getValue'),
            state = $('#js_statusSelect').combobox('getValue')

        var queryParams = {
            number: no,
            title: title,
            type: type,
            username: username,
            state: state
        }

        $('#dataGrid').datagrid('load', queryParams);
    })

    //批量导出
    $(document).on('click', '#js_express', function () {
        $.messager.confirm('确认提示', '您确认要批量导出吗', function (r) {
            if (r){
                var no = $('#js_no').textbox('getValue'),
                title = $('#js_title').textbox('getValue'),
                type = $('#js_typeSelect').combobox('getValue'),
                username = $('#js_userName').textbox('getValue'),
                status  =$('#js_statusSelect').combobox('getValue');
            window.location.href = '/Admin/BuyOffer/expFind?no='+no+'&title='+title+'&type='+type+'&username='+username+'&state='+status;
            }
        });
    });
});
