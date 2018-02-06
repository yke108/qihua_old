$(function() {

    //初始化表格配置参数
    var historyGridConfig = {
        url: '/Admin/Member/MemberCompanyDetail', //请求路径
        queryParams: {//传输参数
            id: $('#memberId').val()
        },
        method: 'get',
        columns: [
            [
                { field: 'addTime', title: '时间', align: 'center', width: '32%'},
                { field: 'opera', title: '操作事项', align: 'center', width: '32%',
                    formatter: function(v, r, i) {
                        if(r.reason) {
                            return r.opera + '[<span style="color:red">'+r.reason+'</span>]'
                        }
                        return r.opera;
                    }
                 },
                { field: 'oid', title: '操作者', align: 'center', width: '33%' }
            ]
        ],
        singleSelect: false,
        pagination: false,
        rownumbers: false
    }

    var signGridConfig = {
        url: '/Admin/Member/MemberSignDetail', //请求路径
        queryParams: {//传输参数
            id: $('#memberId').val()
        },
        method: 'get',
        columns: [
            [
                { field: 'addTime', title: '时间', align: 'center', width: '32%'},
                { field: 'opera', title: '操作事项', align: 'center', width: '32%',
                    formatter: function(v, r, i) {
                        if(r.reason) {
                            return r.opera + '[<span style="color:red">'+r.reason+'</span>]'
                        }
                        return r.opera;
                    }
                 },
                { field: 'oid', title: '操作者', align: 'center', width: '33%' }
            ]
        ],
        singleSelect: false,
        pagination: false,
        rownumbers: false
    }

    var config = $.extend(true, {}, dataGridConfig, historyGridConfig)
    //渲染表格
    $('#historyDataGrid').datagrid(config);

    var signConfig = $.extend(true, {}, dataGridConfig, signGridConfig)
    //渲染表格
    $('#historyDataGrid').datagrid(signConfig);


    //审核不通过
    $(document).on('click', '.js_disableOne', function() {
        var self = $(this),
            id = self.attr('data-id'),
            state = self.attr('data-state'),
            toState = self.attr('data-toState');

        operateOne(id, state, toState);
    });

    // $(window.parent.document).find('iframe').each(function(index, item){
    //     console.log($(item).attr('src'))
    // });

    //设置详情页的tab
    var urlParam = getUrlParam();
    if (urlParam.tab) {
        $('#js_detailsTab').tabs('select', urlParam.tab * 1);
    }

    //刷选当前页面
    function reloadIframe() {
        var currTab = self.parent.$('#tabs').tabs('getSelected'),
            url = $(currTab.panel('options').content).attr('src');

        self.parent.$('#tabs').tabs('update', {
            tab: currTab,
            options: {
                href: url
            }
        });
    }

    //审核通过
    $(document).on('click', '.js_enableOne', function() {
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
            reloadIframe();
        }, function(rs) {
            //$.messager.alert('提示', rs.msg);
        })
    });

    //重置密码
    $(document).on('click', '.js_resetPassword', function() {
        var self = $(this),
            id = self.attr('data-id');

        var postData = {
            url: '/Admin/Member/restPass',
            data: {
                id: id
            }
        }
        ajax(postData).then(function(data) {
            $.messager.alert('提示', data.msg);
            $('.code-txt').html(data.data.password);
        }, function(rs) {
            //$.messager.alert('提示', rs.msg);
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
                        reloadIframe();
                    }, function(rs) {
                       // $.messager.alert('提示', rs.msg);
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
})
