$(function() {

    //初始化表格配置参数
    var pageGridConfig = {
        url: '/Admin/member/companyAuthList',
        footer: '#footerBar',
        columns: [
            [
                {field: '_', checkbox: true },
                { field: 'companyName', title: '公司名称', align: 'center', width: '14%',
                    formatter:function(v, r, i) {
                        return '<a href="javascript:void(0);" data-title="企业认证-' + r.companyName + '" data-href="/Admin/member/memberDetail?id=' + r.id + '&tab=1&type=2" data-id="' + r.id + '" class="js_iframeLink">' + r.companyName + '</a>';
                    }
                },
                { field: 'type', title: '证件类型', align: 'center', width: '10%',
                    formatter:function(v, r, i) {
                        var type = '普通营业执照';
                        if(r.type == 2) {
                            type = '企业三证合一'
                        }
                        return '<span>'+type+'</span>'
                    }
                },
                { field: 'businessCert', title: '营业执照', align: 'center', width: '10%',
                    formatter:function(v, r, i) {
                        var type = '×';
                        if(r.businessCert != null) {
                            type = '√'
                        }
                        return '<span>'+type+'</span>'
                    }
                 },
                { field: 'codeCert', title: '组织机构代码证', align: 'center', width: '10%',
                    formatter:function(v, r, i) {
                        var type = '×';
                        if(r.codeCert != null) {
                            type = '√'
                        }
                        return '<span>'+type+'</span>'
                    }
                 },
                { field: 'taxCert', title: '税务登记证', align: 'center', width: '10%',
                    formatter:function(v, r, i) {
                        var type = '×';
                        if(r.taxCert != null) {
                            type = '√'
                        }
                        return '<span>'+type+'</span>'
                    }
                 },
                { field: 'accountCert', title: '开户许可证', align: 'center', width: '10%',
                    formatter:function(v, r, i) {
                        var type = '×';
                        if(r.accountCert != null) {
                            type = '√'
                        }
                        return '<span>'+type+'</span>'
                    }
                },
                { field: 'state', title: '认证状态', align: 'center', width: '14%',
                    formatter:function(v, r, i) {
                        var type = '';
                        if(r.state == 1) {
                            type = '有效'
                        }else if(r.state == 2) {
                            type = '待审核'
                        } else if(r.state == 0) {
                            type = '审核不通过'+'[ <span style="color:#f00;">'+r.reason+'</span> ]'
                        }else if(r.state == 3) {
                            type = '已撤销'+'[ <span style="color:#f00;">'+r.reason+'</span> ]'
                        }else if(r.state == 4) {
                            type = '未认证'
                        }
                        return '<span>'+type+'</span>'
                    }
                 },
                { field: 'addTime', title: '创建/更新时间', align: 'center', width: '10%',
                    formatter: function(v, r, i) {
                        return formatDate(r.addTime);
                    }
                 },
                {
                    field: 'operate',
                    title: '操作',
                    width: '10%',
                    align: 'center',
                    formatter: function(v, r, i) {
                        var str = '';
                        var className = 'js_enableOne';
                        var tostate = 0;
                        if(r.state == 1) {
                            str = '审核不通过';
                            className = 'js_disableOne';
                            tostate = 3;
                        } else if(r.state == 2) {
                            str = '审核通过';
                            className = 'js_enableOne';
                            tostate = 1;
                        } else if(r.state == 0) {
                            str = '审核通过';
                            className = 'js_enableOne';
                            tostate = 1;
                        } else if(r.state == 3) {
                            str = '恢复通过';
                            className = 'js_enableOne';
                            tostate = 1;
                        } else if(r.state == 4) {
                            str = '审核通过';
                            className = 'js_enableOne';
                            tostate = 1;
                        }

                        if(r.state == 2) {
                            return '<a href="javascript:void(0)" data-state="'+r.state+'"  data-toState="'+tostate+'" data-id="' + r.id + '" class="operate-btn  '+className+'">'+str+'</a><a href="javascript:void(0)" data-state="2"  data-toState="0" data-id="' + r.id + '" class="operate-btn  js_disableOne">审核不通过</a>'
                        } else {
                            return '<a href="javascript:void(0)" data-state="'+r.state+'"  data-toState="'+tostate+'" data-id="' + r.id + '" class="operate-btn  '+className+'">'+str+'</a>'
                        }

                    }
                }
            ]
        ]
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格
    $('#dataGrid').datagrid(config);


    //打开新窗口查看详情
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
            url = $(this).attr("data-href");
            //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
            window.parent.addTab(tabTitle,url);
    });

    //批量导出
    // $(document).on('click', '#js_express', function(){
    //     $.messager.confirm('确认提示', '您确认要批量导出吗', function(r){
    //         if (r){
    //             window.location.href = '/Admin/Member/expAuth';
    //         }
    //     });
    // });

    //批量导出
    $(document).on('click', '#js_express', function(){
        var selectedArray = $('#dataGrid').datagrid('getChecked');
        var idArray = [];

        if(selectedArray.length) {
            for(var i = 0, len = selectedArray.length; i<len; i++) {
                idArray.push(selectedArray[i].id)
            }
        }

        $.messager.confirm('确认提示', '您确认要批量导出吗', function(r){
            if (r){
                var companyName = $('#js_company').textbox('getValue'),
                    certType = $('#js_statusSelect').combobox('getValue'),
                    state = $('#js_stateSelect').combobox('getValue');
                window.location.href = '/Admin/Member/expAuth?id='+idArray.join(',')+'&companyName='+companyName+'&certType='+certType+'&state='+state;
            }
        });
    });
    //审核不通过
    $(document).on('click', '.js_disableOne', function(){
        var self = $(this),
            id = self.attr('data-id'),
            state = self.attr('data-state'),
            toState = self.attr('data-toState');

        operateOne(id, state, toState);
    });

    //审核通过
    $(document).on('click', '.js_enableOne', function(){
        var self = $(this),
            id = self.attr('data-id'),
            state = self.attr('data-state'),
            toState = self.attr('data-toState');

        var postData = {
            url: '/Admin/member/companyVerify',
            data: {
                id: id,
                prevState: state,
                state: toState,
                reason: ''
            }
        }
        ajax(postData).then(function(data) {
            $('#dataGrid').datagrid('uncheckAll');
            $('#dataGrid').datagrid('reload');
        }, function(rs){
            //$.messager.alert('提示',rs.msg);
        })
    });


    function operateOne(id, state, toState) {
        var title = '审核';
        $('#js_revokeForm').form('clear');

        $('#dlg').dialog({
            title: title,
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons: [{
                text: title,
                iconCls: 'icon-ok',
                handler: function() {
                    var isValid = $('#js_revokeForm').form('validate');
                    if (!isValid) {
                        return;
                    }

                    var postData = {
                        url: '/Admin/member/companyVerify',
                        data: {
                            id: id,
                            prevState: state,
                            state: toState,
                            reason: $('#js_revokeReason').val()
                        }
                    }
                    ajax(postData).then(function(data) {
                         $('#dlg').dialog('close');
                        $('#dataGrid').datagrid('uncheckAll');
                        $('#dataGrid').datagrid('reload');
                    })

                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function() {
                    $('#dlg').dialog('close');
                }
            }]

        });
    }

    //审核搜索
    $('#js_search').on('click', function(){
        var companyName = $('#js_company').textbox('getValue'),
            certType = $('#js_statusSelect').combobox('getValue'),
            state = $('#js_stateSelect').combobox('getValue')

        var queryParams = {
            companyName: companyName,
            certType: certType,
            state: state
        }
        $('#dataGrid').datagrid('load', queryParams);
    });

});
