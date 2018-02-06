$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        url: '/Admin/BuyOffer/BuyOfferHistory', //请求路径
        queryParams: { //传输参数
            id: $('#pageId').val()
        },
        columns: [
            [{
                    field: 'addTime',
                    title: '时间',
                    align: 'center',
                    width: '33%'
                }, {
                    field: 'operation',
                    title: '操作事项',
                    align: 'center',
                    width: '33%',
                    formatter: function(v, r, i) {
                        if (r.reason) {
                            return r.state + '<span style="color:red">[' + r.reason + ']</span>'
                        }
                        return r.state;
                    }
                },
                { field: 'otype', title: '操作者', align: 'center', width: '33%' }
            ]
        ],
        singleSelect: false,
        pagination: false,
        rownumbers: false
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
        //渲染表格
    $('#dataGrid').datagrid(config);


    //打开新窗口查看编辑或者预览
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
        url = $(this).attr("data-href");
        //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
        window.parent.addTab(tabTitle, url);
    });


    //审核通过
    $(document).on('click', '.js_agree', function() {
        var self = $(this),
            id = self.attr('data-id'),
            state = self.attr('data-review')
            //先重置表单的值
        $.messager.confirm('确认提示', '您确认要通过吗？', function(r) {
            if (r) {
                var ajaxData = {
                    url: '/Admin/BuyOffer/review',
                    data: {
                        id: id,
                        state: state
                    }
                }
                ajax(ajaxData).then(function(data) {
                    reloadIframe();
                })
            }
        });
    });


    //审核不通过
    $(document).on('click', '.js_disagree', function() {
        var self = $(this),
            id = self.attr('data-id'),
            state = self.attr('data-review')

        $('#js_revokeForm').form('clear');

        var reason = $('#js_revokeReason');


        $('#dlg').dialog({
            title: '审核',
            width: 400,
            height: 200,
            closed: false,
            cache: false,
            modal: true,
            buttons: [{
                text: '审核',
                iconCls: 'icon-ok',
                handler: function() {
                    var isValid = $('#js_revokeForm').form('validate');
                    if (!isValid) {
                        return;
                    }

                    var ajaxData = {
                        url: '/Admin/BuyOffer/review',
                        data: {
                            id: id,
                            state: state,
                            reason: reason.val()
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        reloadIframe();
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
    });



});


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
