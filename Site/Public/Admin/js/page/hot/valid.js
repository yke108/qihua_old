$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        footer: '#footerBar',
        url: '/Admin/Hot/productList', //请求路径
        queryParams: {
            status: 1
        },
        columns: [
            [
                {field: '_', checkbox: true },
                { field: 'purchaseCode', title: '抢购编号', align: 'center', width: '8%',  
                    formatter: function(v, r, i) {
                        return '<a href="javascript:void(0);" data-title="商品详情-' + r.cnName + '-'+r.id+'" data-href="/Admin/Hot/details?id=' + r.id + '" data-id="' + r.id + '" class="js_iframeLink">' + r.purchaseCode + '</a>';
                    } 
                },
                { field: 'title', title: '商城标题', align: 'center', width: '12%' },
                { field: 'categoryList', title: '商品分类', align: 'center', width: '8%' },
                { field: 'cnName', title: '商品中文名', align: 'center', width: '6%' },
                { field: 'price', title: '参考价格', align: 'center', width: '6%' },
                { field: 'moq', title: '最低起订量', align: 'center', width: '6%' },
                { field: 'inventory', title: '库存数量', align: 'center', width: '6%' },
                { field: 'Uid', title: '公司名称', align: 'center', width: '8%' },
                { field: 'addTime', title: '创建时间', align: 'center', width: '12%' },
                { field: 'verifyTime', title: '有效期', align: 'center', width: '6%',
                    formatter:function(value,row,index){
                        return row.verifyTime;
                    }
                },
                { field: 'state', title: '状态', align: 'center', width: '6%',
                    formatter: function(value, row, index) {
                        if(row.state == 1) {
                            return '<p>在售</p>';
                        }
                    }
                },
                { field: 'operate', title: '操作', align: 'center', width: '8%',
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
                +'<a href="javascript:void(0);" data-code="'+row.productDepotCode+'"  data-id="' + row.id + '" class="operate-btn js_revoke">下架</a>'
                +'</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);
    $('#footerBar').css('visibility', 'visible')
  //下架
    var id = '';
    $(document).on('click', '.js_revoke', function(){
        var self = $(this);
        id = self.attr('data-id');
            //先重置表单的值
        $('#js_revokeForm').form('clear');
        $('#dlg').dialog({
            title: '撤销原因',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons: '#dlg-buttons'
        });
    });
        //下架--提交按钮
        $(document).on('click', '.js_revokeCommit', function() {
            var isValid = $('#js_revokeForm').form('validate');
            var reason = $('#js_revokeReason').val();
            var code = $(this).attr('data-code');
            if (!isValid) {
                return;
            }
            var ajaxData = {
                url: '/Admin/Hot/changeOff',
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
        //下架--取消按钮
        $(document).on('click', '.js_revokeCancel', function() {
            $('#dlg').dialog('close');
        });

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
            status: 1,
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
