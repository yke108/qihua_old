$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
            footer: '#footerBar',
            sortName: "createTime",
            checkOnSelect: "false",
            sortOrder: "asc",
            url: groupListUrl, //请求路径
            queryParams: { //传输参数
                type: '-1',
                keyword: ''
            },
            columns: [
                [
                    { field: '_', checkbox: true },
                    { field: 'title', title: '角色名', align: 'center', width: '10%' },
                    { field: 'name', title: '所属部门', align: 'center', width: '20%' },
                    { field: 'addtime', title: '创建时间', align: 'center', width: '16%',
                        formatter: function(v, r, i) {
                            return formatDate(r.addtime);
                        }
                    },
                    { field: 'realname', title: '创建人', align: 'center', width: '20%' },
                    { field: 'status', title: '状态', align: 'center', width: '10%',
                        formatter: function(v, r, i) {
                            return r.status == 1 ? '启用' : '停用';
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
        return '<div class="operate-wrap">' + '<a href="javascript:void(0);" data-index="' + index + '" data-id="' + row.id + '" class="operate-btn js_editRole">编辑</a>' + '<a href="#" data-index="' + index + '" data-id="' + row.id + '" class="operate-btn js_delRole">删除</a>' + '</div>';
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);
    //********使用本地数据测试,上线时删除********//
    $('#dataGrid').datagrid('loadData', sysRoleList);
    //********使用本地数据测试,上线时删除********//

    //搜索功能
    $('#js_roleList').on('click', function(){
        var userRole = $('#js_userRole').val();
        var department = $('#js_department').combotree('getValue');

        console.log(userRole,  department);
    });

    var resetForm = function() {
        $('#js_addRole .easyui-textbox').each(function(index, item){
            $(item).textbox('setValue', '');
        })

        $('#js_addRole .easyui-combotree').each(function(index, item){
            $(item).combotree('setValue', '');
        })

        $('#js_addRole .easyui-combobox').each(function(index, item){
            $(item).combobox('setValue', '');
        })
    }

    function renderRBAC(data) {
        var html = '';
        for(var i = 0, lv1; lv1 = data[i++]; ) {
            html += '<div class="wrap">'
                +'<div class="left left-auto">'
                    +'<div class="inner">'+lv1.text+'</div>'
                +'</div>'
                +'<div class="right">'
                    for( var j = 0, lv2; lv2 = lv1.children[j++]; ) {
                       html += '<div class="item">'
                            +'<div class="left-auto">'
                                +'<div class="inner">'+lv2.text+'</div>'
                            +'</div>'
                            +'<div class="check-wrap">'
                                for( var k = 0, lv3; lv3 = lv2.children[k++]; ) {
                                    lv3.checked ? lv3.checked = 'checked' : lv3.checked = '';
                                    html += '<label class="check" for="checkbox_'+lv3.id+'"><input data-id="'+lv3.id+'" id="checkbox_'+lv3.id+'" type="checkbox" '+lv3.checked+'> '+lv3.text+'</label>'
                                }
                            html += '</div>'
                        +'</div>'
                    }
                html += '</div>'
            +'</div>';
        }

        return html;
    }

    function getRbacData(id) {

        var ajaxData = {
            url: ruleList,
            data: {}
        }
        if(id) {
            ajaxData.data.id = id;
        }
        ajax(ajaxData).then(function(data){
             $('.rbac').html(renderRBAC(data.data.WEB))
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

    });
    
    //填充部门树
    $('#departmentName').combotree({
        url:"/Admin/Auth/departmentList.html",
        lines: true,
        onClick: function(node) {
            $('.did').val(node.id);
        }
    })

    var editId = null;
    //编辑    
    $(document).on('click', '.js_editRole', function() {
        $('.js_submit').attr('data-url', saveGroupUrl);
        var self = $(this),
            index = self.attr('data-index'),
            id = self.attr('data-id'),
            rowData = $('#dataGrid').datagrid('getData').rows[index];

        editId = id;

        getRbacData(id)

        $('#js_addRole').form('load', rowData);

        $('#dlg').dialog({
            title: '编辑角色',
            width: 900,
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

    //新增
    $('.js_addRole').on('click', function() {
        //设置提交的url
        $('.js_submit').attr('data-url', addGroupUrl);

        resetForm();

        getRbacData();

        $('#dlg').dialog({
            title: '新增角色',
            width: 900,
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
    $(document).on('click', '.js_delRole', function() {
        var self = $(this),
            id = self.attr('data-id')

        $.messager.confirm('确认', '您确认想要删除记录吗？', function(r) {
            if (r) {
                var ajaxData = {
                    url: delGroupUrl,
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

        if(!$('#js_addRole').form('validate')) return;

        var parmsArray = $('#js_addRole').serializeArray();
        
        
        var idArray = [];
        $('.rbac input').each(function(index, item){
            if( $(item).prop('checked') ) {
                idArray.push($(item).attr('data-id'));
            }
        })

        var ajaxData = {
            url: url,
            data: {}
        }

        for(var i = 0, parm; parm = parmsArray[i++]; ) {
            ajaxData.data[parm.name] = parm.value;
        }

        if(idArray.length) {
            ajaxData.data.rules = idArray.join(',');
            idArray = [];
        }

        if(editId) {
             ajaxData.data.id = editId;
             editId = null;
        }

        ajax(ajaxData).then(function(data) {
            $('#dataGrid').datagrid('reload');
            $('#dlg').dialog('close'); 
            $('.rbac').html('');
            resetForm();
        })
    })

    //批量删除
    $('.js_multiRemove').linkbutton({
        onClick: function() {
            postDataGridMulti(delGroupUrl, $(this), '#dataGrid');
        }
    })
    //批量启用
    $('.js_multiEnable').linkbutton({
        onClick: function() {
            postDataGridMulti(groupBatchActiveUrl, $(this), '#dataGrid');
        }
    })
    //批量禁用
    $('.js_multiDisable').linkbutton({
        onClick: function() {
            postDataGridMulti(groupBatchInactiveUrl, $(this), '#dataGrid');
        }
    })



});
