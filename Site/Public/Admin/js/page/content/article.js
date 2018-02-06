$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        title: "商家文章", //标题
        url: 'http://www.jeasyui.com/demo/main/datagrid2_getdata.php444', //请求路径
        queryParams: {//传输参数
            type: '-1',
            keyword: ''
        }, 
        columns: [
            [
                { field: '_', checkbox: true},
                { field: 'userId', title: "ID",align: 'center', width: '14%'  },
                { field: 'title', title: '标题', align: 'center', width: '14%' },
                { field: 'type', title: '分类', align: 'center', width: '14%' },
                { field: 'status', title: '审核状态', align: 'center', width: '14%' },
                { field: 'createTime', title: '创建时间', align: 'center', width: '14%' },
                {
                    field: 'operate',
                    title: '操作',
                    width: '25%',
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
                +'<a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.editLink + '" data-id="' + row.id + '" class="operate-btn js_iframeLink">审核</a>'
                +'<a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.previewLink + '" data-id="' + row.id + '" class="operate-btn js_iframeLink">预览</a>'
                +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_revoke">删除</a>'
                +'</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);
    //********使用本地数据测试,上线时删除********//
    //$('#dataGrid').datagrid('loadData', contentArticleData);
    //********使用本地数据测试,上线时删除********//

    //监听条件选择变化，更新表格数据
    $('#js_askStatusSelect').combobox({
        onChange: function(newValue, oldValue) {
            changeStatus(newValue)
        }
    });
    $('#js_typeSelect').combobox({
        onChange: function(newValue, oldValue) {
            changeStatus(newValue)
        }
    });
   

    //更新状态参数或者搜索关键字 reload表格
    function changeStatus(type, search) {
        var dg = $('#dataGrid');
        var queryParams = {
            type: '-1',
            keyword: '',
            page: 1
        }
        if (type) {
            queryParams.type = type;
        }
        if (search) {
            queryParams.keyword = search;
        }
        dg.datagrid('load', queryParams);
    }


    //打开新窗口查看编辑或者预览
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
            url = $(this).attr("data-href");
            //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
            window.parent.addTab(tabTitle,url);
    });


    //回复用户留言 
    $(document).on('click', '.js_reply', function() {
        
    });
});
