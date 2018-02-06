$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        title: "已删除商家列表", //标题
        url: 'http://www.jeasyui.com/demo/main/datagrid2_getdata.php444', //请求路径
        queryParams: {//传输参数
            type: '-1',
            keyword: ''
        }, 
        columns: [
            [
                {field: '_', checkbox: true },
                { field: 'name', title: '账号名', align: 'center', width: '15%' },
                { field: 'company', title: '公司名', align: 'center', width: '15%' },
                { field: 'area', title: '所在地区', align: 'center', width: '10%' },
                { field: 'email', title: '绑定邮箱', align: 'center', width: '10%' },
                { field: 'phone', title: '绑定手机', align: 'center', width: '10%' }, 
                { field: 'status', title: '状态', align: 'center', width: '5%' }, 
                { field: 'createTime', title: '创建时间', align: 'center', width: '10%' },{
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
        switch (row.operate) {
            case '1':
                return '<div class="operate-wrap">'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_edit">编辑</a>'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_frozen">冻结</a>'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_disable">禁用</a>'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_revoke">删除</a>'
                    +'</div>';
                break;
            case '2':
                return '<div class="operate-wrap">'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_edit">编辑</a>'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_frozen">取消冻结</a>'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_disable">禁用</a>'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_revoke">删除</a>'
                    +'</div>';
                break;
            case '3':
                return '<div class="operate-wrap">'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_edit">编辑</a>'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_frozen">冻结</a>'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_disable">取消禁用</a>'
                    +'<a href="#" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_revoke">删除</a>'
                    +'</div>';
                break;
        }
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);
    //********使用本地数据测试,上线时删除********//
    $('#dataGrid').datagrid('loadData', userListData);
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
    //编辑
    $(document).on('click', '.js_edit', function() {
        
        var self = $(this),
            index = self.attr('data-index'),
            id = self.attr('data-id'),
            rowData
        rowData = $('#dataGrid').datagrid('getData').rows[index];
        //清空弹窗
        $('#js_editUserForm').form('clear');
        $('#js_editUserForm').form('load', rowData);

        $('#editUser').dialog({
            title: '编辑用户',
            width: 600,
            height: 300,
            closed: false,
            cache: false,
            modal: true,
            buttons: '#editUser-buttons'
        });
                  
    });


    //恢复选中的用户
    $('.js_recovery').on('click', function(){
        var selectedArray = $('#dataGrid').datagrid('getChecked'),
            i = 0,
            length = selectedArray.length,
            idArray = []

        if(length === 0) {
            $.messager.alert('提示', '请选择要恢复的数据项', 'warning');
            return;
        }

        for(; i<length; i++) {
            idArray.push(selectedArray[i].id);
        }

        //ajax提交
        console.log(idArray.join('-'))
    })
});
