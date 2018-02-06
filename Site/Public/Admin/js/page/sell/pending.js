$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        footer: '#footerBar',
        url: '/Admin/Sell/productList', //请求路径
        queryParams: {
            status: 2
        },
        columns: [
            [
                {field: '_', checkbox: true },
                { field: 'productCode', title: '信息编号', align: 'center', width: '8%',
                    formatter: function(v, r, i) {
                        return '<a href="javascript:void(0);" data-title="商品详情-' + r.cnName + '-'+r.id+'" data-href="/Admin/Sell/details?id=' + r.id + '" data-id="' + r.id + '" class="js_iframeLink">' + r.productCode + '</a>';
                    }
                },
                { field: 'title', title: '商城标题', align: 'center', width: '9%' },
                { field: 'categoryList', title: '商品分类', align: 'center', width: '8%' },
                { field: 'enName', title: '商品名称', align: 'center', width: '12%' },
                { field: 'price', title: '参考价格', align: 'center', width: '6%' },
                { field: 'moq', title: '最低起订量', align: 'center', width: '6%' },
                { field: 'inventory', title: '库存数量', align: 'center', width: '6%' },
                // { field: 'validDate', title: '有效期', align: 'center', width: '6%',
                //     formatter:function(value,row,index){
                //         return row.validDate+'天';
                //     }
                // },
                { field: 'Uid', title: '公司名称', align: 'center', width: '12%' },
                { field: 'addTime', title: '创建时间', align: 'center', width: '12%' },
                { field: 'state', title: '状态', align: 'center', width: '6%',
                    formatter: function(value, row, index) {
                        if(row.state == 2) {
                            return '<p>待审核</p>';
                        }
                    }
                },
                { field: 'operate', title: '操作', align: 'center', width: '12%',
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
                +'<a href="javascript:void(0);"  data-id="' + row.id + '" class="operate-btn js_agree">审核通过</a>'
                +'<a href="javascript:void(0);"  data-id="' + row.id + '" class="operate-btn js_disagree">审核不通过</a>'
                +'</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);

    $('#footerBar').css('visibility', 'visible')

    //获取地区数据
    $('#js_getAreaData').on('click', function(){
        //先获取省数据
        $('.js_select_province').show();
    })


    //审核通过
     $(document).on('click', '.js_agree', function() {
        var self = $(this),
            id = self.attr('data-id')
        var ajaxData = {
            url: '/Admin/Sell/examStatus',
            data: {
                id: id
            }
        }
        $.messager.confirm('确认提示', '您确认要通过审核吗？', function(r){
            if (r){
                ajax(ajaxData).then(function(data) {
                    $('#dataGrid').datagrid('reload');
                })
            }
        });

    });
     var id = '';
    //审核不通过
    $(document).on('click', '.js_disagree', function() {
        var self = $(this);
            id = self.attr('data-id')

            //先重置表单的值
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
    //审核不通过--提交按钮
    $(document).on('click', '.js_revokeCommit', function() {
        var isValid = $('#js_revokeForm').form('validate');
        var reason = $('#js_revokeReason').val();
        if (!isValid) {
            return;
        }
         var ajaxData = {
            url: '/Admin/Sell/failStatus',
            data: {
                id: id,
                reason:reason
            }
        }
        ajax(ajaxData).then(function(data) {
            $('#dataGrid').datagrid('reload');
        })
        $('#dlg').dialog('close');
    });
    //撤销通过--取消按钮
    $(document).on('click', '.js_revokeCancel', function() {
        $('#dlg').dialog('close');
    });
    //批量通过
    $('.js_multiEnable').linkbutton({
        onClick: function() {
            postDataGridMulti('/Admin/Sell/examStatus', $(this), '#dataGrid', '您确定批量通过吗？');
        }
    })
    //分类
    $('#js_getKind_LV1').combobox({
        valueField: 'id',
        textField: 'text',
        onSelect: function(param, b) {
            ajax({ url: '/Admin/Store/getCategory', data: { id: param.id },type:"get"}).then(function(rs) {
                $('#js_getKind_LV2').combobox('loadData',rs.data);
                $('#js_getKind_LV2').combobox({
                    valueField: 'id',
                    textField: 'text',
                    onSelect: function(res, b){
                        ajax({url:'/Admin/Store/getCategory',data:{id:param.id,id:res.id},type:"get"}).then(function(rs){
                            $('#js_getKind_LV3').combobox('loadData',rs.data);
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
        $('#js_getKind_LV1').combobox('loadData',rs.data);
    });
    //搜索框
    $('#js_search').on('click', function(){
        var categoryFirst = $('#js_getKind_LV1').combobox('getValue'),
            categorySecond = $('#js_getKind_LV2').combobox('getValue'),
            categoryThird = $('#js_getKind_LV3').combobox('getValue'),
            companyName = $('#js_company').textbox('getValue'),
            keyword = $('#js_goodName').textbox('getValue');
        var queryParams = {
            status: 2,
            categoryFirst: categoryFirst,
            categorySecond:categorySecond,
            categoryThird:categoryThird,
            companyName:companyName,
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
