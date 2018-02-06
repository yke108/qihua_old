$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        title: "子订单列表", //标题
        url: '/Admin/order/OrderChildLists', //请求路径 
        columns: [
            [
                { field: 'orderSn', title: '订单号', align: 'center', width: '20%' },
                { field: 'goodsTitle', title: '商品名', align: 'center', width: '20%' },
                { field: 'goodsAmount', title: '总金额', align: 'center', width: '15%' },
                { field: 'addTime', title: '创建时间', align: 'center', width: '15%' },
                { field: 'receiveData', title: '状态', align: 'center', width: '15%' , formatter:function(val ,row,index){
                   return getReceiveData(val ,row,index);
                }},
                {
                    field: 'operate',
                    title: '操作',
                    width: '14%',
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
                +'<a href="javascript:void(0);" data-title="订单-'+row.orderSn+'" data-href="/Admin/Order/orderdetail?id='+row.id+'" data-id="' + row.id + '" class="operate-btn js_iframeLink">查看</a>'
                +'</div>';
    }
    //渲染状态
    function getReceiveData(val ,row,index){
        var receArr = row.state;
        var cont = '';
        switch (receArr[0]){
            case '1': cont = '待协商-';break;
            case '2': cont = '待买方付款-'; break;
            case '3': cont = '申请取消中-'; break;
            case '4': cont = '待卖方收款-'; break;
            case '5': cont = '待卖方发货-'; break;
            case '6': cont = '待买方收货-'; break;
            case '7': cont = '已完成-'; break;
            case '8': cont = '已取消-'; break;
        };
        switch (receArr[1]){
            case '1': cont += '待卖家修改订单'; break;
            case '2': cont += '卖方已修改订单'; break;
            case '3': cont += '买方要求继续协商'; break;
            case '4': cont += '订单已确认生效'; break;
            case '5': cont += '卖方不同意取消订单'; break;
            case '6': cont += '付款信息被退回'; break;
            case '7': cont += '买方不同意取消订单'; break;
            case '8': cont += '待卖方审核申请'; break;
            case '9': cont += '待买方审核申请'; break;
            case '10': cont += '买方已确认付款'; break;
            case '11': cont += '卖方已确认收款'; break;
            case '12': cont += '发货信息被退回'; break;
            case '13': cont += '卖方已确认发货'; break;
            case '14': cont += '交易结束'; break;
            case '15': cont += '买方取消'; break;
            case '16': cont += '卖方取消'; break;
        };
            return cont;

    }
    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);
    //打开新窗口查看编辑或者预览
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
            url = $(this).attr("data-href");
            //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
            window.parent.addTab(tabTitle,url);
    });
 
        //状态
    ~(function() {
        ajax({ url: '/Admin/order/getOrderTypes' }).then(function(rs) {
            $('#js_orderType').combobox('loadData', rs.data);
        });

        $('#js_orderType').combobox({
            valueField: 'value',
            textField: 'name',
            onSelect: function(param, b) {
               
            }
        });
    })();

    //搜索功能 提交表格
    $('#js_userListSearch').on('click', function(){
        var keyword = $('#js_keyword').textbox('getValue'),
            orderType = $('#js_orderType').combobox('getValue'),
            order = $('#js_orderNo').textbox('getValue')

        var queryParams = {
            order: order,
            keyword: keyword,
            orderType: orderType,
            page: 1
        }
        $('#dataGrid').datagrid('load', queryParams);
    })
});
