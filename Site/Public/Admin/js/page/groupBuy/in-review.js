$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        title: "审核中商品", //标题
        url: 'http://www.jeasyui.com/demo/main/datagrid2_getdata.php444', //请求路径
        queryParams: {//传输参数
            type: '-1',
            keyword: ''
        }, 
        columns: [
            [
                {field: '_', checkbox: true },
                { field: 'goodName', title: '商品名', align: 'center', width: '20%' },
                { field: 'img', title: '主图', align: 'center', width: '15%', formatter: function(value, row, index){
                    return '<img src="'+row.img+'" style="max-height: 30px;">'
                } },
                { field: 'storeName', title: '店铺名', align: 'center', width: '15%' },
                { field: 'type', title: '分类', align: 'center', width: '15%' }, 
                { field: 'createTime', title: '创建时间', align: 'center', width: '10%' },
                {
                    field: 'operate',
                    title: '操作',
                    width: '20%',
                    align: 'center',
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
                +'<a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.editLink + '" data-id="' + row.id + '" class="operate-btn js_iframeLink">编辑</a>'
                +'<a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.previewLink + '" data-id="' + row.id + '" class="operate-btn js_iframeLink">预览</a>'
                +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_argee">审核通过</a>'
                +'</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);
    //********使用本地数据测试,上线时删除********//
    $('#dataGrid').datagrid('loadData', inReviewData);
    //********使用本地数据测试,上线时删除********//
    //

    //监听条件选择变化，更新表格数据
    $('#js_typeSelect').combobox({
        onChange: function(newValue, oldValue) {
            changeStatus(newValue)
        }
    });
    //搜索功能
    $('#js_inReviewSearch').searchbox({
        searcher: function(value, name) {
            console.log(value, name)
            changeStatus(null, value);
        },
        prompt: '搜索',
        menu:'#searchMenu'
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
        dg.datagrid('load', queryParams);
    }


    //获取地区数据
    $('#js_getAreaData').on('click', function(){
        //先获取省数据
        $('.js_select_province').show();
    })

    //初始化审核不通过的input验证信息
    $('#js_revokeReason').validatebox({
        required: true,
        missingMessage: '必须填写'
    });
 
    //批量通过选中的用户
    $('.js_agreeSome').on('click', function(){
        var selectedArray = $('#dataGrid').datagrid('getChecked'),
            i = 0,
            length = selectedArray.length,
            idArray = []

        if(length === 0) {
            $.messager.alert('提示', '请选择要通过的数据项', 'warning');
            return;
        }

        for(; i<length; i++) {
            idArray.push(selectedArray[i].id);
        }

        //ajax提交
        console.log(idArray.join('-'))
    })


    //打开新窗口查看编辑或者预览
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
            url = $(this).attr("data-href");
            //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
            window.parent.addTab(tabTitle,url);
    });
});
