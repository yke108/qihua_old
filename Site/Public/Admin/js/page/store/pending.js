$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        footer: '#footerBar',
        url: '/Admin/Store/productdepotList', //请求路径
        queryParams: {
            status: 0
        },
        columns: [
            [
                {field: '_', checkbox: true},
                { field: 'productCode', title: '商品编号', align: 'center', width: '8%',formatter:function(value,row,index){
                    return '<a href="javascript:void(0);" data-title="商品详情" data-href="/Admin/Store/goodsDetails?id='+row.id+'" data-id="'+row.id+'" class="js_iframeLink">'+row.productCode+'</a>'
                }},
                { field: 'categoryList', title: '商品分类', align: 'center', width: '8%' },
                { field: 'cnName', title: '商品中文名', align: 'center', width: '9%' },
                { field: 'cnAlias', title: '中文别名', align: 'center', width: '9%' },
                { field: 'enName', title: '商品英文名', align: 'center', width: '9%' },
                { field: 'enAlias', title: '英文别名', align: 'center', width: '9%' },
                { field: 'cas', title: 'CAS号', align: 'center', width: '8%' },
                { field: 'Uid', title: '公司名称', align: 'center', width: '8%' },
                { field: 'updateTime', title: '创建/修改时间', align: 'center', width: '8%' },
                { field: 'state', title: '状态', align: 'center', width: '6%',
                    formatter: function(value, row, index) {
                        if(row.state == 0) {
                            return "待审核";
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

    $('#footerBar').css('visibility', 'visible');
    //点击跳转商品详情页
    $(document).on('click','.js_iframeLink',function(){
        var tabTitle =  $(this).attr('data-title');
        var url = $(this).attr('data-href');
        window.parent.addTab(tabTitle,url);
    });
    //批量导出
    // $(document).on('click', '#js_express', function(){
    //     $.messager.confirm('确认提示', '您确认要批量导出吗', function(r){
    //         if (r){
    //             window.location.href = '/Admin/Store/expStore?id=0';
    //         }
    //     });
    // });
    //批量导出
    $(document).on('click', '#js_express', function(){
        var selectedArray = $('#dataGrid').datagrid('getChecked');
        var idArray = [];

        if(selectedArray.length) {
            for(var i = 0, len = selectedArray.length; i<len; i++) {
                idArray.push(selectedArray[i].id)   
            }
        }

        $.messager.confirm('确认提示', '您确认要批量导出吗', function(r){
            if (r){
                window.location.href = '/Admin/Store/expStore?id=0&filter='+idArray.join(',');
            }
        });
    });
    //审核通过
     $(document).on('click', '.js_agree', function() {
        var self = $(this),
            id = self.attr('data-id')
            //先重置表单的值
        $.messager.confirm('确认提示', '您确认要通过此商品吗？', function(r){
            if (r){
                var ajaxData = {
                    url: '/Admin/Store/examStatus',
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



    //审核不通过
    $(document).on('click', '.js_disagree', function() {
        var self = $(this),
            id = self.attr('data-id'),
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
                        url: '/Admin/Store/failStatus',
                        data: {
                            id: id,
                            reason: reason.val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        $('#dataGrid').datagrid('reload');
                        $('#dlg').dialog('close');
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

    //批量通过
    $('#js_batchPass').linkbutton({
        onClick: function() {
            postDataGridMulti('/Admin/Store/examStatus', $(this), '#dataGrid', '您确定批量通过吗？');
        }
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
    $('#js_search').on('click', function() {
        var categoryFirst = $('#js_getKind_LV1').combobox('getValue'),
            categorySecond = $('#js_getKind_LV2').combobox('getValue'),
            categoryThird = $('#js_getKind_LV3').combobox('getValue'),
            companyName = $('#js_company').textbox('getValue'),
            keyword = $('#js_goodName').textbox('getValue');
        var queryParams = {
            status: 0,
            categoryFirst: categoryFirst,
            categorySecond:categorySecond,
            categoryThird:categoryThird,
            companyName:companyName,
            keyword: keyword
        }
        $('#dataGrid').datagrid('load', queryParams);
    })
});
