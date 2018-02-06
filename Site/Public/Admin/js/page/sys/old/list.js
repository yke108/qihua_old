$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        
            footer:'#ft',
            sortName: "createTime",
            sortOrder: "asc",
            url: 'http://www.jeasyui.com/demo/main/datagrid2_getdata.php444', //请求路径
            queryParams: { //传输参数
                type: '-1',
                keyword: ''
            },
            columns: [
                [
                    { field: '_', checkbox: true },
                    { field: 'adminId', title: 'ID', align: 'center', width: '15%' },
                    { field: 'name', title: '用户名', align: 'center', width: '20%' },
                    { field: 'group', title: '用户组', align: 'center', width: '20%' },
                    { field: 'createTime', title: '创建时间', align: 'center', width: '20%' }, {
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
        return '<div class="operate-wrap">' + '<a href="javascript:void(0);" data-title="' + row.title + '" data-href="' + row.editLink + '" data-id="' + row.id + '" class="operate-btn js_iframeLink">编辑</a>' + '<a href="#" data-index="' + index + '" data-id="' + row.id + '" class="operate-btn js_revoke">删除</a>' + '</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
        //渲染表格
    $('#dataGrid').datagrid(config);
    //********使用本地数据测试,上线时删除********//
    $('#dataGrid').datagrid('loadData', sysListData);
    //********使用本地数据测试,上线时删除********//
    //
    //
    //
    var treeData = [{
        "id": 1,
        "text": "Folder1",
        "iconCls": "icon-save",
        "children": [{
            "id": 109,
            "text": "File1",
            "checked": true
        }, {
            "id": 3,
            "text": "Books",
            "state": "open",
            "attributes": {
                "url": "/demo/book/abc",
                "price": 100
            },
            "children": [{
                "id":9,
                "text": "PhotoShop",
                "checked": true
            }, {
                "id": 11,
                "text": "Sub Bookds"
            }]
        }]
    }, {
        "id": 18,
        "text": "Languages",
        "state": "closed",
        "children": [{
            "id": 102,
            "text": "Java"
        }, {
            "id": 103,
            "text": "C#"
        }]
    }]


    $('#tree').tree({
        data: treeData,
        lines: true,
        onClick: function(node){

            console.log(node) 
            if(node) {
                $('#js_editTreeBtn').linkbutton('enable');
                $('#js_removeTreeBtn').linkbutton('enable');
            }
        }

    })

    //初始化操作按钮
    //新增
    $('#js_createTreeBtn').linkbutton({    
        iconCls: 'icon-add',
        onClick: function() {
            var node = $('#tree').tree('getSelected');
            $('#tree').tree('append', {
                                parent:node.target,
                                data: {
                                    "id": "909",
                                    "text": "测试"
                                }
                          });
        }
    });
    //删除
    $('#js_removeTreeBtn').linkbutton({    
        iconCls: 'icon-add',
        disabled: true,
        onClick: function() {
            var node = $('#tree').tree('getSelected');
             $('#tree').tree('remove', node.target);
        }
    });  
    //修改
    $('#js_editTreeBtn').linkbutton({    
        iconCls: 'icon-add',
        disabled: true,
        onClick: function() {
            alert(1)
        }
    }); 


});
