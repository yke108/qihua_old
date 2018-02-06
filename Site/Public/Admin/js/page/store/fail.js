$(function() {
    //初始化表格配置参数

    var pageGridConfig = {
        footer: '#footerBar',
        url: '/Admin/Store/productdepotList', //请求路径
        queryParams: {
            status: 2
        },
        columns: [
            [   
                {field: '_', checkbox: true },
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
                { field: 'state', title: '状态', align: 'center', width: '12%',
                    formatter: function(value, row, index) {
                        if(row.state == 2) {
                            return '<p>审核不通过</p><p>【<span style="color:#f00;">'+row.reason.reason+'</span>】<br/>'+row.reason.addTime+'</p>';
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
                +'<a href="javascript:void(0);"  data-id="' + row.id + '" class="operate-btn js_agree">重审通过</a>'
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
    //             window.location.href = '/Admin/Store/expStore?id=2';
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
                window.location.href = '/Admin/Store/expStore?id=2&filter='+idArray.join(',');
            }
        });
    });
    //审核通过
     $(document).on('click', '.js_agree', function() {
        var self = $(this),
            id = self.attr('data-id');
            //先重置表单的值
        $.messager.confirm('确认提示', '您确认要通过此商品吗？', function(r){
            if (r){
                var ajaxData = {
                    url: '/Admin/Store/rStatus',
                    data: {
                        id: id
                    }
                }
                ajax(ajaxData).then(function(data) {
                    $('#dataGrid').datagrid('reload');
                }, function(rs){
                    //$.messager.alert('提示',rs.msg);
                })
            }
        });

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
            status: 2,
            categoryFirst: categoryFirst,
            categorySecond:categorySecond,
            categoryThird:categoryThird,
            companyName:companyName,
            keyword: keyword
        }
        $('#dataGrid').datagrid('load', queryParams);
    })
});
