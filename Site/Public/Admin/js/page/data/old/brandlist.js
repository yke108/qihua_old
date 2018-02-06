$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        title: "品牌管理", //标题
        url: 'http://www.jeasyui.com/demo/main/datagrid2_getdata.php444', //请求路径
        queryParams: {//传输参数
            type: '-1',
            keyword: ''
        }, 
        columns: [
            [
                { field: '_', checkbox: true },
                { field: 'name', title: '名称', align: 'center', width: '6%' },
                { field: 'url', title: '网址', align: 'center', width: '15%' },
                { field: 'letter', title: '首字母', align: 'center', width: '15%' },
                { field: 'sort', title: '排序', align: 'center', width: '25%' , formatter: function(value, row, index){
                    return '<input name="sort" class="js_sortInput" >'
                }},
                {
                    field: 'operate',
                    title: '操作',
                    width: '30%',
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
                +'<a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.editLink + '" data-id="' + row.id + '" class="operate-btn ">编辑</a>'
                +'<a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.editLink + '" data-id="' + row.id + '" class="operate-btn ">删除</a>'
                +'</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);
    //********使用本地数据测试,上线时删除********//
    $('#dataGrid').datagrid('loadData', brandListData);
    //********使用本地数据测试,上线时删除********//

    $('#js_searchArea').searchbox({
        searcher: function(value, name) {
            changeStatus(null, value);
        },
        prompt: '公司名称'
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
