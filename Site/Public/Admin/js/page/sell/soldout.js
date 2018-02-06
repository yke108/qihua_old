$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
            footer: '#footerBar',
            url: '/Admin/Sell/productList', //请求路径
            queryParams: {
                status: 3
            },
            columns: [
                [
                    { field: '_', checkbox: true },
                    { field: 'productCode', title: '信息编号', align: 'center', width: '8%',
                    formatter: function(v, r, i) {
                        return '<a href="javascript:void(0);" data-title="商品详情-' + r.cnName + '-'+r.id+'" data-href="/Admin/Sell/details?id=' + r.id + '" data-id="' + r.id + '" class="js_iframeLink">' + r.productCode + '</a>';
                    }
                },
                { field: 'title', title: '商城标题', align: 'center', width: '6%' },
                { field: 'categoryList', title: '商品分类', align: 'center', width: '8%' },
                { field: 'enName', title: '商品名称', align: 'center', width: '10%' },
                    { field: 'price', title: '参考价格', align: 'center', width: '6%' },
                    { field: 'moq', title: '最低起订量', align: 'center', width: '6%' },
                    { field: 'inventory', title: '库存数量', align: 'center', width: '6%' },
                    { field: 'Uid', title: '公司名称', align: 'center', width: '12%' },
                    { field: 'addTime', title: '创建时间', align: 'center', width: '12%' }, {
                        field: 'state',
                        title: '状态',
                        align: 'center',
                        width: '15%',
                        formatter: function(value, row, index) {

                            var reasonH='【<span style="color:#f00;">' + row.reason.reason + '</span>】';
                            if(row.reason.reason==undefined||$.trim(row.reason.reason).length<=0){
                                reasonH='';
                            }

                            return '<p style="color:#999">'+row.reason.state+'</p><p>' + row.reason.addTime + '</p>'+reasonH+'</p>';
                        }
                    }, {
                        field: 'operate',
                        title: '操作',
                        align: 'center',
                        width: '8%',
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
        return '<div class="operate-wrap">' + '<a href="javascript:void(0);" data-code="' + row.productDepotCode + '"  data-id="' + row.id + '" class="operate-btn js_agree">恢复上架</a>' + '</div>';
    }
    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
        //渲染表格
    $('#dataGrid').datagrid(config);

    $('#footerBar').css('visibility', 'visible')

    //恢复上架
    $(document).on('click', '.js_agree', function() {
        var self = $(this),
            id = self.attr('data-id'),
            productDepotCode = self.attr('data-code');

        $('#js_revokeForm').form('clear');

        $('#dlg').dialog({
            title: '恢复上架',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons: [{
                text: '恢复上架',
                iconCls: 'icon-ok',
                handler: function() {
                    var isValid = $('#js_revokeForm').form('validate');
                    if (!isValid) {
                        return;
                    }
                    var ajaxData = {
                        url: '/Admin/Sell/renewStatus',
                        data: {
                            id: id,
                            productDepotCode: productDepotCode,
                            reason: $('#js_revokeReason').val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        $('#dlg').dialog('close');
                        $('#dataGrid').datagrid('uncheckAll');
                        $('#dataGrid').datagrid('reload');
                    }, function(rs){
                        $('#dlg').dialog('close');
                        //$.messager.alert('提示',rs.msg);
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
    //批量删除
    $('.js_batchDel').linkbutton({
        onClick: function() {
            postDataGridMulti('/Admin/Sell/del', $(this), '#dataGrid');
        }
    });
    //下架状态
    ~(function() {
        ajax({ url: '/Admin/Sell/getRevoke' }).then(function(rs) {
            $('#js_status').combobox('loadData', rs.data);
        });

        $('#js_status').combobox({
            valueField: 'id',
            textField: 'text',
            onSelect: function(param, b) {

            }
        });
    })();
    //分类
    $('#js_getKind_LV1').combobox({
        valueField: 'id',
        textField: 'text',
        onSelect: function(param, b) {
            ajax({ url: '/Admin/Store/getCategory', data: { id: param.id }, type: "get" }).then(function(rs) {
                $('#js_getKind_LV2').combobox('loadData', rs.data);
                $('#js_getKind_LV2').combobox({
                    valueField: 'id',
                    textField: 'text',
                    onSelect: function(res, b) {
                        ajax({ url: '/Admin/Store/getCategory', data: { id: param.id, id: res.id }, type: "get" }).then(function(rs) {
                            $('#js_getKind_LV3').combobox('loadData', rs.data);
                            $('#js_getKind_LV3').combobox({
                                valueField: 'id',
                                textField: 'text'
                            });
                        })
                    }
                });
            });
        }
    });
    ajax({ url: '/Admin/Store/getCategory' }).then(function(rs) {
        $('#js_getKind_LV1').combobox('loadData', rs.data);
    });
    //搜索框
    $('#js_search').on('click', function() {
        var categoryFirst = $('#js_getKind_LV1').combobox('getValue'),
            categorySecond = $('#js_getKind_LV2').combobox('getValue'),
            categoryThird = $('#js_getKind_LV3').combobox('getValue'),
            companyName = $('#js_company').textbox('getValue'),
            keyword = $('#js_goodName').textbox('getValue'),
            operateStatus =  $('#js_status').combobox('getValue')
        var queryParams = {
            status: 3,
            categoryFirst: categoryFirst,
            categorySecond: categorySecond,
            categoryThird: categoryThird,
            companyName: companyName,
            keyword: keyword,
            operateStatus: operateStatus
        }
        $('#dataGrid').datagrid('load', queryParams);
    });
    //打开新窗口查看编辑或者预览
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
        url = $(this).attr("data-href");
        //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
        window.parent.addTab(tabTitle, url);
    });
});
