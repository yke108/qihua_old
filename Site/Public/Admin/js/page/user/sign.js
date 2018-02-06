$(function() {
    //录入签约
    var signGridConfig = {
        toolbar: '#signToolbar',
        url: '/Admin/member/memberSignList', //请求路径
        columns: [
            [
                {field: '_', checkbox: true },
                { field: 'code', title: '合同编号', align: 'center', width: '10%' },
                { field: 'companyName', title: '公司名称', align: 'center', width: '10%' },
                { field: 'area', title: '所在地区', align: 'center', width: '10%' },
                { field: 'cooperation', title: '合作年度', align: 'center', width: '6%' }, {
                    field: 'contractTime',
                    title: '合同签约时间',
                    align: 'center',
                    width: '10%',
                    formatter: function(v, r, i) {
                        return formatDate2(r.contractTime);
                    }
                }, {
                    field: 'expireTime',
                    title: '合同到期时间',
                    align: 'center',
                    width: '10%',
                    formatter: function(v, r, i) {
                        return formatDate2(r.expireTime);
                    }
                },
                { field: 'signatory', title: '签约人', align: 'center', width: '6%' }, {
                    field: 'state',
                    title: '状态',
                    align: 'center',
                    width: '14%',
                    formatter: function(v, r, i) {
                        var type = '';
                        if (r.state == 1) {
                            type = '有效'
                        } else if (r.state == 2) {
                            type = '待审核'
                        } else if (r.state == 0) {
                            type = '审核不通过' + '[ <span style="color:#f00;">' + r.reason + '</span> ]'
                        } else if (r.state == 3) {
                            type = '已撤销' + '[ <span style="color:#f00;">' + r.reason + '</span> ]'
                        } else if (r.state == 5) {
                            type = '已过期'
                        }
                        return '<span>' + type + '</span>'
                    }
                },
                { field: 'addTime', title: '录入/修改时间', align: 'center', width: '8%' }, {
                    field: 'operate',
                    title: '操作',
                    align: 'center',
                    width: '14%',
                    formatter: function(v, r, i) {
                        var str = '';
                        var className = '';

                        if (r.state == 1) { //有效
                            str = '修改签约';
                            className = 'js_signModify';
                        } else if (r.state == 2) { //待审核
                            str = '修改签约';
                            className = 'js_signModify';
                        } else if (r.state == 0) { //审核不通过
                            str = '修改签约';
                            className = 'js_signModify';
                        } else if (r.state == 5) { //已过期
                            str = '录入续约';
                            className = 'js_renew';
                        } else if (r.state == 3) { //已撤销
                            str = '修改签约';
                            className = 'js_signModify';
                        }

                        return '<a href="javascript:void(0);" data-title="录入签约详情-' + r.companyName + '" data-href="/Admin/member/memberDetail?id=' + r.id + '&tab=2&type=3" data-id="' + r.id + '" class="operate-btn js_iframeLink">查看详情</a><a href="javascript:void(0)"  data-id="' + r.id + '" data-index="' + i + '" class="operate-btn  ' + className + '">' + str + '</a>'
                    }
                }
            ]
        ],
        footer: '#signFooterBar',
        singleSelect: false
    }
    var signConfig = $.extend(true, {}, dataGridConfig, signGridConfig)

    //录入审核
    var approveGridConfig = {
        toolbar: '#approveToolbar',
        url: '/Admin/member/memberSignList', //请求路径
        columns: [
            [
                { field: 'code', title: '合同编号', align: 'center', width: '8%' },
                { field: 'companyName', title: '公司名称', align: 'center', width: '8%' },
                { field: 'area', title: '所在地区', align: 'center', width: '10%' },
                { field: 'cooperation', title: '合作年度', align: 'center', width: '4%' },
                { field: 'contractTime', title: '合同签约时间', align: 'center', width: '10%',
                    formatter: function(v, r, i) {
                        return formatDate2(r.contractTime);
                    }
                },
                { field: 'expireTime', title: '合同到期时间', align: 'center', width: '10%',
                    formatter: function(v, r, i) {
                        return formatDate2(r.expireTime);
                    }
                },
                { field: 'signatory', title: '签约人', align: 'center', width: '6%' }, {
                    field: 'state',
                    title: '状态',
                    align: 'center',
                    width: '12%',
                    formatter: function(v, r, i) {
                        var type = '';
                        if (r.state == 1) {
                            type = '有效'
                        } else if (r.state == 2) {
                            type = '待审核'
                        } else if (r.state == 3) {
                            type = '已撤销' + '[ <span style="color:#f00;">' + r.reason + '</span> ]'
                        } else if (r.state == 0) {
                            type = '审核不通过' + '[ <span style="color:#f00;">' + r.reason + '</span> ]'
                        }
                        return '<span>' + type + '</span>'
                    }
                },
                { field: 'addTime', title: '录入/修改时间', align: 'center', width: '8%', },
                {
                    field: 'operate',
                    title: '操作',
                    align: 'left',
                    width: '16%',
                    formatter: function(v, r, i) {
                        var str = '';
                        var className = '';
                        var btn = '';
                        if (r.state == 1) { //有效
                            btn = '<a href="javascript:void(0)"  data-state="'+r.state+'" data-toState="3" data-opTitle="撤销通过" data-id="' + r.id + '" data-index="' + i + '" class="operate-btn  js_operate">撤销通过</a>'
                        } else if (r.state == 2) { //待审核
                            btn = '<a href="javascript:void(0)"  data-state="'+r.state+'" data-toState="1" data-opTitle="审核通过" data-id="' + r.id + '" data-index="' + i + '" class="operate-btn  js_operate">审核通过</a><a href="javascript:void(0)" data-state="'+r.state+'" data-toState="0" data-opTitle="审核不通过" data-id="' + r.id + '" data-index="' + i + '" class="operate-btn  js_operate">审核不通过</a>'
                        } else if (r.state == 3) { //已撤销
                            btn = '<a href="javascript:void(0)"  data-state="'+r.state+'" data-toState="1" data-opTitle="恢复通过" data-id="' + r.id + '" data-index="' + i + '" class="operate-btn  js_operate">恢复通过</a>';
                        } else if (r.state == 0) { //审核不通过
                            btn = '<a href="javascript:void(0)"  data-state="'+r.state+'" data-toState="1" data-opTitle="审核通过" data-id="' + r.id + '" data-index="' + i + '" class="operate-btn  js_operate">审核通过</a>';
                        }

                        return '<a href="javascript:void(0);" data-title="录入签约详情-' + r.companyName + '" data-href="/Admin/member/memberDetail?id=' + r.id + '&tab=2&type=3" data-id="' + r.id + '" class="operate-btn js_iframeLink">查看详情</a>' + btn
                    }
                }
            ]
        ],
        footer: '#approveFooterBar',
        singleSelect: false
    }
    var approveConfig = $.extend(true, {}, dataGridConfig, approveGridConfig)
        //渲染表格
    $('#signDataGrid').datagrid(signConfig);

    $('#approveDataGrid').datagrid(approveConfig);


    var contractDetails = null;
    var defVal = $('input[name="attachment"]').val(),
        devArr = [],
        uploaded = [];
    //录入签约批量导出
    // $(document).on('click', '#js_signExpress', function(){
    //     $.messager.confirm('确认提示', '您确认要批量导出吗', function(r){
    //         if (r){
    //             window.location.href = '/Admin/Member/expSign';
    //         }
    //     });
    // });

    //批量导出
    $(document).on('click', '#js_signExpress', function(){
        var selectedArray = $('#signDataGrid').datagrid('getChecked');
        var idArray = [];

        if(selectedArray.length) {
            for(var i = 0, len = selectedArray.length; i<len; i++) {
                idArray.push(selectedArray[i].id)   
            }
        }

        $.messager.confirm('确认提示', '您确认要批量导出吗', function(r){
            if (r){
                window.location.href = '/Admin/Member/expSign?id='+idArray.join(',');
            }
        });
    });
    
    //录入审核批量导出
    $(document).on('click', '#js_approveExpress', function(){
        $.messager.confirm('确认提示', '您确认要批量导出吗', function(r){
            if (r){
                window.location.href = '/Admin/Member/expSignAuth';
            }
        });
    });
    //新增
    $(document).on('click', '#js_newContract', function() {
        var self = $(this),
            id = self.attr('data-id')

        $('#fileList .del').trigger('click');
        $('.defList').html('');

        //enable
        $('.contract-info .js_company').textbox('enable');
        $('.contract-info .js_company').textbox('setValue', '');
        $('.contract-info .js_contractNo').textbox('setValue', '');
        $('.contract-info .js_signer').textbox('setValue', '');
        $('.contract-info .js_contractBgYear').combobox('enable');
        $('.contract-info .js_contractBgYear').combobox('setValue', '');
        $('.contract-info .js_contractSignBgTime').datebox('setValue', '');
        $('.contract-info .js_contractSignEdTime').datebox('setValue', '');


        $('#dlg').dialog({
            title: '录入新签约商家',
            width: 900,
            height: 630,
            cache: false,
            modal: true,
            buttons: [{
                text: '录入新签约商家',
                iconCls: 'icon-ok',
                handler: function() {
                    var isValid = $('.contract-info').form('validate');
                    if (!isValid) {
                        return;
                    }
                    save('/Admin/member/signAdd');
                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function() {
                    $('#dlg').dialog('close');
                }
            }]
        });


        if (!contractDetails) {
            contractDetails = kindEditor('contractDetails', 400, 300);
        } else {
            contractDetails.html('')
        }
    });
    //修改签约 js_signModify
    $(document).on('click', '.js_signModify', function() {
        var self = $(this),
            index = self.attr('data-index'),
            id = self.attr('data-id'),
            rowData

        rowData = $('#signDataGrid').datagrid('getData').rows[index];

        $('#fileList .del').trigger('click');

        //enable
        $('.contract-info .js_company').textbox('enable');
        $('.contract-info .js_contractBgYear').combobox('enable');
        $('.contract-info .js_contractBgYear').combobox('select', rowData.cooperation);

        $('.contract-info').form('load', rowData);

        $('.contract-info .js_contractSignBgTime').datebox('setValue', formatDate2(rowData.contractTime));
        $('.contract-info .js_contractSignEdTime').datebox('setValue', formatDate2(rowData.expireTime));

        defVal = $('input[name="attachment"]').val();
        devArr = defVal.split(',');
        setImg(rowData.attachment);
        $('#dlg').dialog({
            title: '录入续约',
            width: 900,
            height: 630,
            cache: false,
            modal: true,
            buttons: [{
                text: '录入续约',
                iconCls: 'icon-ok',
                handler: function() {
                    var isValid = $('.contract-info').form('validate');
                    if (!isValid) {
                        return;
                    }
                    save('/Admin/member/signSave');
                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function() {
                    $('#dlg').dialog('close');

                }
            }]
        });

        if (!contractDetails) {
            contractDetails = kindEditor('contractDetails', 400, 300);
        }

        contractDetails.html(rowData.content);
    });
    //录入续约
    $(document).on('click', '.js_renew', function() {
        var self = $(this),
            index = self.attr('data-index'),
            id = self.attr('data-id'),
            rowData

        rowData = $('#signDataGrid').datagrid('getData').rows[index];

        $('#fileList .del').trigger('click');

        //disable
        $('.contract-info .js_company').textbox('disable');
        $('.contract-info .js_contractBgYear').combobox('disable');
        $('.contract-info .js_contractBgYear').combobox('select', rowData.cooperation);

        $('.contract-info').form('load', rowData);

        $('.contract-info .js_contractSignBgTime').datebox('setValue', formatDate2(rowData.contractTime));
        $('.contract-info .js_contractSignEdTime').datebox('setValue', formatDate2(rowData.expireTime));

        defVal = $('input[name="attachment"]').val();
        devArr = defVal.split(',');
        setImg(rowData.attachment);
        $('#dlg').dialog({
            title: '录入续约',
            width: 900,
            height: 630,
            cache: false,
            modal: true,
            buttons: [{
                text: '录入续约',
                iconCls: 'icon-ok',
                handler: function() {
                    var isValid = $('.contract-info').form('validate');
                    if (!isValid) {
                        return;
                    }
                    save('/Admin/member/signSave');
                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function() {
                    $('#dlg').dialog('close');
                    $('#fileList .del').trigger('click');
                }
            }]
        });

        if (!contractDetails) {
            contractDetails = kindEditor('contractDetails', 400, 300);
        }

        contractDetails.html(rowData.content + '');
    });


    function setImg(urls) {

        if (urls.length == 0) return;
        var arr = urls.split(','),
            str = ''

        for (var i = 0, url; url = arr[i++];) {
            str += '<div class="def-item">\
                    <img src="' + url + '" alt="">\
                    <span class="del">删除</span>\
                </div>'
        }

        $('.defList').html(str).show();
    }

    //保存
    function save(url) {
        var companyName = $('.contract-info .js_company').textbox('getValue');
        var code = $('.contract-info .js_contractNo').textbox('getValue');
        var contractTime = $('.contract-info .js_contractSignBgTime').datebox('getValue');
        var expireTime = $('.contract-info .js_contractSignEdTime').datebox('getValue');
        var signatory = $('.contract-info .js_signer').textbox('getValue');
        var cooperation = $('.contract-info .js_contractBgYear').combobox('getValue');
        var content = contractDetails.html();
        var attachment = $('input[name="attachment"]').val();
        var id = $('input[name="id"]').val();


        if ($.trim(content) == '') {
            alert('请输入合同内容');
            return;
        }
        if (attachment == '') {
            alert('请上传合同文件');
            return;
        }
        var ajaxData = {
            url: url,
            data: {
                uid: id,
                companyName: companyName,
                code: code,
                contractTime: contractTime,
                expireTime: expireTime,
                signatory: signatory,
                cooperation: cooperation,
                content: content,
                attachment: attachment
            }
        }

        ajax(ajaxData).then(function(rs) {
            $('#dlg').dialog('close');

            $('#signDataGrid').datagrid('reload');
            $('#approveDataGrid').datagrid('reload');
        }, function(rs) {
           // $.messager.alert('提示', rs.msg, 'info');
        });
    }



    ~(function() {
        if (defVal != '') {
            devArr = defVal.split(',');
        }
        //初始化Web Uploader
        var uploader = WebUploader.create({

            // 选完文件后，是否自动上传。
            auto: true,

            // swf文件路径
            //swf: BASE_URL + '/js/Uploader.swf',

            // 文件接收服务端。
            server: '/Home/Image/webUploader',

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#picker',

            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'jpg,jpeg,png',
                mimeTypes: 'image/*'
            },
            duplicate :true
        });

        uploader.on('fileQueued', function(file) {
            var $li = $(
                    '<div id="' + file.id + '" class="file-item thumbnail">' +
                    '<img>' +
                    '</div>'
                ),
                $img = $li.find('img'),
                $del = $('<span class="del">删除</span>');

            $li.append($del);

            $del.on('click', function() {
                var parent = $(this).parent('.thumbnail');
                var index = parent.index();

                uploaded.splice(index, 1);
                parent.remove();
                fillImagesData()
            });

            // $list为容器jQuery实例
            $('.uploader-list').append($li);

            // 创建缩略图
            // 如果为非图片文件，可以不用调用此方法。
            // thumbnailWidth x thumbnailHeight 为 100 x 100
            uploader.makeThumb(file, function(error, src) {
                if (error) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }

                $img.attr('src', src);
            }, 100, 100);
        });

        uploader.on('uploadSuccess', function(file, rs) {
            if (rs.code == 200) {
                uploaded.push(rs.data[0].url);
                fillImagesData();
            }
        });

        function fillImagesData() {
            var rsArr = uploaded.concat(devArr);
            $('input[name="attachment"]').val(rsArr.join(','));
        }

        $(document).on('click', '.del', function() {
            var parent = $(this).parent('.def-item');
            var index = parent.index();

            devArr.splice(index, 1);
            fillImagesData()
            parent.remove();
        });
    })();


    //打开新窗口查看编辑或者预览
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
        url = $(this).attr("data-href");
        //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
        window.parent.addTab(tabTitle, url);
    });


    //录入搜索
    $('#js_searchSign').on('click', function(){
        var code = $('#signToolbar .code').textbox('getValue'),
            companyName = $('#signToolbar .companyName').textbox('getValue'),
            state = $('#signToolbar .state').combobox('getValue')

        var queryParams = {
            code: code,
            companyName: companyName,
            state: state
        }
        $('#signDataGrid').datagrid('load', queryParams);
    });

    //审核搜索
    $('#js_searchOperate').on('click', function(){
        var code = $('#approveToolbar .code').textbox('getValue'),
            companyName = $('#approveToolbar .companyName').textbox('getValue'),
            state = $('#approveToolbar .state').combobox('getValue')

        var queryParams = {
            code: code,
            companyName: companyName,
            state: state
        }
        $('#approveDataGrid').datagrid('load', queryParams);
    });



    //取消禁用
    $(document).on('click', '.js_operate', function(){
        var self = $(this),
            id = self.attr('data-id'),
            prevState = self.attr('data-state'),
            state = self.attr('data-toState'),
            title = self.attr('data-opTitle'),
            ajaxData = {
                id: id,
                prevState: prevState,
                state: state
            }

        operateFn(title, ajaxData);
    });
    function operateFn(title, ajaxData) {

        //审核通过不需要理由
        if(ajaxData.state == 1) {
            var postData = {
                url: '/Admin/member/signVerify',
                data: ajaxData
            }
            ajax(postData).then(function(data) {
                $('#approveDataGrid').datagrid('uncheckAll');
                $('#approveDataGrid').datagrid('reload');
            })
            return;
        }
        $('#js_revokeForm').form('clear');

        $('#dlg2').dialog({
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
                    var reason = $('#js_revokeReason').val();
                    if (!isValid) {
                        return;
                    }
                    ajaxData.reason = reason;
                    var postData = {
                        url: '/Admin/member/signVerify',
                        data: ajaxData
                    }
                    ajax(postData).then(function(data) {
                         $('#dlg2').dialog('close');
                        $('#approveDataGrid').datagrid('uncheckAll');
                        $('#approveDataGrid').datagrid('reload');
                    })

                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function() {
                    $('#dlg2').dialog('close');
                }
            }]

        });
    }

});
