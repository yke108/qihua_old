$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
            footer: '#footerBar',
            url: '/Admin/Sell/productList', //请求路径
            queryParams: {
                status: 0
            },
            columns: [
                [
                    { field: '_', checkbox: true },
                    { field: 'productCode', title: '信息编号', align: 'center', width: '8%',
                    formatter: function(v, r, i) {
                        return '<a href="javascript:void(0);" data-title="商品详情-' + r.cnName + '-'+r.id+'" data-href="/Admin/Sell/details?id=' + r.id + '" data-id="' + r.id + '" class="js_iframeLink">' + r.productCode + '</a>';
                    }
                },
                { field: 'title', title: '商城标题', align: 'center', width: '12%' },
                { field: 'categoryList', title: '商品分类', align: 'center', width: '8%' },
                { field: 'enName', title: '商品名称', align: 'center', width: '10%' },
                    { field: 'price', title: '参考价格', align: 'center', width: '6%' },
                    { field: 'moq', title: '最低起订量', align: 'center', width: '6%' },
                    { field: 'inventory', title: '库存数量', align: 'center', width: '6%' },
                    { field: 'Uid', title: '公司名称', align: 'center', width: '11%' },
                    { field: 'addTime', title: '创建时间', align: 'center', width: '10%' }, {
                        field: 'state',
                        title: '状态',
                        align: 'center',
                        width: '12%',
                        formatter: function(value, row, index) {
                            if (row.state == 0) {
                                return '<p>审核不通过</p><p>' + row.reason.addTime + '</p><p>【<span style="color:#f00;">' + row.reason.reason + '</span>】</p>';
                            }
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
        return '<div class="operate-wrap">' + '<a href="javascript:void(0);"  data-id="' + row.id + '" class="operate-btn js_agree">重审通过</a>' + '</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
        //渲染表格
    $('#dataGrid').datagrid(config);

    $('#footerBar').css('visibility', 'visible')

    //搜索功能 提交表格
    $('#js_search').on('click', function() {
        var categoryFirst = $('#js_getKind_LV1').combobox('getValue'),
            categorySecond = $('#js_getKind_LV2').combobox('getValue'),
            categoryThird = $('#js_getKind_LV3').combobox('getValue'),
            companyName = $('#js_company').textbox('getValue'),
            keyword = $('#js_goodName').textbox('getValue');
        var queryParams = {
            status: 0,
            categoryFirst: categoryFirst,
            categorySecond: categorySecond,
            categoryThird: categoryThird,
            companyName: companyName,
            keyword: keyword
        }
        $('#dataGrid').datagrid('load', queryParams);
    })

    //审核通过
    $(document).on('click', '.js_agree', function() {
            var self = $(this),
                id = self.attr('data-id')
            var ajaxData = {
                url: '/Admin/Sell/rStatus',
                data: {
                    id: id
                }
            }
            $.messager.confirm('确认提示', '您确认要重申通过吗？', function(r){
                if (r){
                    ajax(ajaxData).then(function(data) {
                        $('#dataGrid').datagrid('reload');
                    })
                }
            });
        })
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
            keyword = $('#js_goodName').textbox('getValue');
        var queryParams = {
            status: 0,
            categoryFirst: categoryFirst,
            categorySecond: categorySecond,
            categoryThird: categoryThird,
            companyName: companyName,
            keyword: keyword
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
