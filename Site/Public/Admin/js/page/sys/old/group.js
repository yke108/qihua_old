$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        title: "用户组", //标题
        url: groupListUrl, //请求路径
        queryParams: {//传输参数
            type: '-1',
            keyword: ''
        }, 
        columns: [
            [
                {field: '_', checkbox: true },
                { field: 'id', title: 'ID', align: 'center', width: '15%' },
                { field: 'title', title: '用户组', align: 'center', width: '20%' },
                { field: 'addtime', title: '创建时间', align: 'center', width: '30%' },{
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
                +'<a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.editLink + '" data-id="' + row.id + '" class="operate-btn js_iframeLink">编辑</a>'
                +'<a href="javascript:void(0);" data-title="'+row.title+'" data-href="' + row.editLink + '" data-id="' + row.id + '" class="operate-btn js_iframeLink">用户职位</a>'
                +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_revoke">删除</a>'
                +'</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);
    //********使用本地数据测试,上线时删除********//
    $('#dataGrid').datagrid('loadData', sysListData);
    //********使用本地数据测试,上线时删除********//

});
