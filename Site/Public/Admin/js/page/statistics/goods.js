$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        title: "团购商品列表", //标题
        url: 'http://www.jeasyui.com/demo/main/datagrid2_getdata.php444', //请求路径
        queryParams: {//传输参数
            type: '-1',
            keyword: ''
        }, 
        columns: [
            [
                {field: '_', checkbox: true },
                { field: 'name', title: '名称', align: 'center', width: '12%' },
                { field: 'area', title: '所在地', align: 'center', width: '12%' },
                { field: 'company', title: '供应商', align: 'center', width: '12%' },
                { field: 'status', title: '状态', align: 'center', width: '12%' }, 
                { field: 'num', title: '成交笔数', align: 'center', width: '12%' }, 
                { field: 'total', title: '累计销售量', align: 'center', width: '12%' }, 
                { field: 'totalPrice', title: '累计销售额', align: 'center', width: '12%' }, 
                { field: 'createTime', title: '创建时间', align: 'center', width: '12%' }
            ]
        ]
    }
    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);
    //********使用本地数据测试,上线时删除********//
    //$('#dataGrid').datagrid('loadData', statisticsGoodsData);
    //********使用本地数据测试,上线时删除********//

 
    //获取地区数据
    $('#js_getAreaData').on('click', function(){
        //先获取省数据
        $('.js_select_province').show();
    })
    //搜索功能 提交表格
    $('#js_userListSearch').on('click', function(){
        alert(0)
    })
    //初始化审核不通过的input验证信息
    $('#js_revokeReason').validatebox({
        required: true,
        missingMessage: '必须填写'
    });
 
    //删除选中的用户
    $('.js_remove').on('click', function(){
        var selectedArray = $('#dataGrid').datagrid('getChecked'),
            i = 0,
            length = selectedArray.length,
            idArray = []

        if(length === 0) {
            $.messager.alert('提示', '请选择要删除的数据项', 'warning');
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
