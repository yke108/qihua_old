$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
            footer: '#footerBar',
            sortName: "createTime",
            checkOnSelect: "false",
            sortOrder: "asc",
            url: userListUrl, //请求路径
            queryParams: { //传输参数
                type: '-1',
                keyword: ''
            },
            columns: [
                [
                    { field: '_', checkbox: true },
                    { field: 'realname', title: '姓名', align: 'center', width: '10%' },
                    { field: 'username', title: '用户名', align: 'center', width: '10%' },
                    { field: 'department', title: '所在部门', align: 'center', width: '14%' },
                    { field: 'group', title: '角色', align: 'center', width: '10%' },
                    { field: 'addtime', title: '创建时间', align: 'center', width: '16%',
                        formatter: function(v, r, i) {
                            return formatDate(r.addtime);
                        }
                     },
                    { field: 'creater', title: '创建人', align: 'center', width: '10%' },
                    { field: 'state', title: '状态', align: 'center', width: '5%',
                        formatter: function(v, r, i) {
                            return r.state == 1 ? '启用' : '停用';
                        }
                     }, {
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
        return '<div class="operate-wrap">' + '<a href="javascript:void(0);" data-index="'+index+'" data-id="' + row.id + '" class="operate-btn js_editUser">编辑</a>' + '<a href="#" data-index="' + index + '" data-id="' + row.id + '" class="operate-btn js_delUser">删除</a>' + '</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);

    var resetForm = function() {
        $('#js_userForm')[0].reset();
        $('#js_userForm .easyui-textbox').each(function(index, item){
            $(item).textbox('setValue', '');
        })

        $('#js_userForm .easyui-combotree').each(function(index, item){
            $(item).combotree('setValue', '');
        })

        $('#js_userForm .easyui-combobox').each(function(index, item){
            $(item).combobox('setValue', '');
        })
    }


    //搜索功能,得到结果后刷新表单
    $('#js_userListSearch').on('click', function(){
        var username = $('#js_userName').val();
        var realname = $('#js_name').val();
        var department = $('#js_department').combotree('getValue');
        var state = $('#js_statusSelect').combobox('getValue');

        var dg = $('#dataGrid');
        var queryParams = {
            username: username,
            realname: realname,
            department: department,
            state: state
        }

        dg.datagrid('load', queryParams);

        console.log(username, realname, department, state);
    });

    //选择部门
    $('#department').combotree({
        url:"/Admin/Auth/departmentList.html",
        lines: true,
        onClick: function(node) {
            var ajaxData = {
                url: idGetGroupList,
                data: {
                    id: node.id
                }
            }
            ajax(ajaxData).then(function(data){
                $('#role').combobox('loadData', data.data);
                $('.role-wrap').css('visibility', 'visible');
            })

            $('.did').val(node.id);
        }
    })
    //选择角色
    $('#role').combobox({
        onSelect: function(node) {
            $('.gid').val(node.id);
        }
    })

    $('#js_department').combotree({
        url:"/Admin/Auth/departmentList.html",
        lines: true,
        onClick: function(node) {
            $('#js_department').val(node.id);
        }
    })

    var editId = null;
    //编辑
    $(document).on('click', '.js_editUser', function() {
        $('.js_submit').attr('data-url', userSaveUrl);
        var self = $(this),
            index = self.attr('data-index'),
            id = self.attr('data-id'),
            rowData = $('#dataGrid').datagrid('getData').rows[index];

        editId = id;

        $('#js_userForm').form('load', rowData);

        $('#dlg').dialog({
            title: '编辑用户',
            width: 520,
            height: 580,
            closed: false,
            cache: false,
            modal: true,
            buttons: 'btns',
            onOpen:function(){
                $('.role-wrap').css('visibility', 'visible');
                $('.userState').show();
            }
        });
    })

    //新增
    $('.js_addUser').on('click', function() {
        //设置提交的url
        $('.js_submit').attr('data-url', userAddUrl);

        resetForm();

        $('#dlg').dialog({
            title: '新增用户',
            width: 520,
            height: 580,
            closed: false,
            cache: false,
            modal: true,
            buttons: 'btns',
            onOpen:function(){
                $('.role-wrap').css('visibility', 'hidden');
                $('.userState').hide();
            }
        });
    })

    //删除
    $(document).on('click', '.js_delUser', function() {
        var self = $(this),
            id = self.attr('data-id')

        $.messager.confirm('确认', '您确认想要删除记录吗？', function(r) {
            if (r) {
                var ajaxData = {
                    url: userDelUrl,
                    data: {
                        id: id
                    }
                }
                ajax(ajaxData).then(function(data){
                    $('#dataGrid').datagrid('reload');
                })

            }
        });
    })

    //提交
    $('.js_submit').on('click', function() {
        var url = $(this).attr('data-url');

        if(!$('#js_userForm').form('validate')) return;

        var parmsArray = $('#js_userForm').serializeArray();

        var ajaxData = {
            url: url,
            data: {}
        }

        for(var i = 0, parm; parm = parmsArray[i++]; ) {
            ajaxData.data[parm.name] = parm.value;
        }

        if(editId) {
             ajaxData.data.id = editId;
             editId = null;
        }

        ajax(ajaxData).then(function(data) {
            $('#dataGrid').datagrid('reload');
            $('#dlg').dialog('close');
            resetForm();
        },function(data) {
            //$.messager.alert('提示',data.msg);
        })
    })

    //批量删除
    $('.js_multiRemove').linkbutton({
        onClick: function() {
            postDataGridMulti(userDelUrl, $(this), '#dataGrid');
        }
    })
    //批量启用
    $('.js_multiEnable').linkbutton({
        onClick: function() {
            postDataGridMulti(userBatchActiveUrl, $(this), '#dataGrid');
        }
    })
    //批量禁用
    $('.js_multiDisable').linkbutton({
        onClick: function() {
            postDataGridMulti(userBatchInactiveUrl, $(this), '#dataGrid');
        }
    })
    //批量导出
    $(document).on('click', '#js_express', function(){
        $.messager.confirm('确认提示', '您确认要批量导出吗', function(r){
            if (r) {
                var username = $('#js_userName').val();
                var realname = $('#js_name').val();
                var department = $('#js_department').combotree('getValue');
                var state = $('#js_statusSelect').combobox('getValue');
                window.location.href = '/Admin/Auth/expUser?username=' + username + '&realname=' + realname + '&department=' + department + '&state=' + state;
            }
        });
    });
});
