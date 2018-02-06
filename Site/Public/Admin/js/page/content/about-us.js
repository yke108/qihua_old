$(function() {
    //关于我们
    ~(function() {
        //创建编辑器
        var aboutEditor = kindEditor('editAbout', 400, 300);
        //保存操作
        $('.js_saveAboutEdit').on('click', function() {
            var self = $(this);
            var ajaxData = {
                url: 'about',
                data: {
                    type: '平台简介',
                    title: '平台简介',
                    content: aboutEditor.html()
                }
            }
            ajax(ajaxData, self).then(function(data) {
                $.messager.show({
                    title: '提示',
                    msg: data.msg,
                    showType: 'null',
                    timeout: 600,
                    style: {
                        right: '',
                        top: '30%',
                        bottom: ''
                    }
                });
            }, function(rs){
                $.messager.show({
                    title: '提示',
                    msg: '请先修改内容',
                    showType: 'null',
                    timeout: 800,
                    style: {
                        right: '',
                        top: '30%',
                        bottom: ''
                    }
                });
            })
        })
        $('.easyui-tabs').show();
    })();

    //网站公告
    ~(function() {
        //初始化表格配置参数
        var pageGridConfig = {
            url: './noticeList', //请求路径
            columns: [
                [
                    { field: '_', checkbox: true },
                    { field: 'title', title: '标题', align: 'center', width: '25%' },
                    { field: 'addTime', title: '创建时间', align: 'center', width: '20%', formatter: function(value, row, index) {
                        return formatDate2(row.addTime);
                    } },
                    { field: 'username', title: '创建人', align: 'center', width: '20%' }, {
                        field: 'operate',
                        title: '操作',
                        width: '25%',
                        align: 'center',
                        formatter: function(value, row, index) {
                            return '<div class="operate-wrap">' + '<a href="javascript:void(0);" data-index="' + index + '"  data-id="' + row.id + '" class="operate-btn js_noticeDialogEdit">编辑</a>' + '<a href="javascript:void(0);" data-index="' + index + '"  data-id="' + row.id + '" class="operate-btn js_revokeNotice">删除</a>' + '</div>';
                        }
                    }
                ]
            ],
            singleSelect: false,
            toolbar: '#notice-bar',
            footer: '#notice-footer'
        }

        var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
            //渲染表格
        $('#website-notice').datagrid(config);

        //批量删除
        $('.js_multiRemoveNotices').linkbutton({
            onClick: function() {
                postDataGridMulti('./delnotice', $(this), '#website-notice');
            }
        })

        var operateType = '';
        var editor = null;
        var editId = '';
        //编辑
        $(document).on('click', '.js_noticeDialogEdit', function() {
            var self = $(this),
                index = self.attr('data-index'),
                id = self.attr('data-id'),
                rowData

            operateType = 'edit';
            editId = id

            rowData = $('#website-notice').datagrid('getData').rows[index];

            //设置title
            $('.js_noticeDialogTitle').textbox('setValue', rowData.title);

            $('#noticeDialog').dialog({
                title: '编辑公告',
                width: 700,
                closed: false,
                cache: false,
                modal: true
            });
            //设置内容
            if (!editor) {
                editor = kindEditor('js_noticeDialogEditor', 300, 200);
            }

            editor.html(rowData.content);
        })

        //新增
        $('.js_noticeAdd').on('click', function() {
            operateType = 'add';
            editId = '';
            //重置title
            $('.js_noticeDialogTitle').textbox('setValue', '');

            $('#noticeDialog').dialog({
                title: '新增公告',
                width: 700,
                closed: false,
                cache: false,
                modal: true
            });
            //重置内容
            if (!editor) {
                editor = kindEditor('js_noticeDialogEditor', 300, 200);
            }
            editor.html('');
        })

        //删除
        $(document).on('click', '.js_revokeNotice', function() {
            var self = $(this),
                id = self.attr('data-id')

            $.messager.confirm('确认', '您确认想要删除记录吗？', function(r) {
                if (r) {
                    var ajaxData = {
                        url: './delnotice',
                        data: {
                            id: id
                        }
                    }
                    ajax(ajaxData).then(function(data) {
                        $('#website-notice').datagrid('reload');
                    })

                }
            });
        })

        //提交
        $('.js_noticeDialogSave').on('click', function() {
            var title = $('.js_noticeDialogTitle').textbox('getValue'),
                content = editor.html();
            if (title == '') {

                $.messager.alert('提示', '请设置标题字段', 'warning');
                return;
            }
            if (content == '') {
                $.messager.alert('提示', '请设置内容字段', 'warning');
                return;
            }
            var ajaxOpt = {
                url: 'notice',
                data: {
                    id: editId,
                    title: title,
                    content: content
                }
            }
            ajax(ajaxOpt).then(function(data) {
                $('#website-notice').datagrid('reload');
                $('#noticeDialog').dialog('close');
            })
        })
    })();


    //媒体报道
    ~(function() {
        //初始化表格配置参数
        var pageGridConfig = {
            url: './newsList', //请求路径
            columns: [
                [
                    { field: '_', checkbox: true },
                    { field: 'title', title: '报道标题', align: 'center', width: '20%' },
                    { field: 'referer', title: '来源', align: 'center', width: '20%' },
                    { field: 'reportDate', title: '报道时间', align: 'center', width: '15%' },
                    { field: 'username', title: '创建人', align: 'center', width: '15%' }, {
                        field: 'operate',
                        title: '操作',
                        width: '20%',
                        align: 'center',
                        formatter: function(value, row, index) {
                            return '<div class="operate-wrap">' + '<a href="javascript:void(0);" data-index="' + index + '" data-id="' + row.id + '"  class="operate-btn js_newsDialogEdit">编辑</a>' + '<a href="javascript:void(0);" data-index="' + index + '" data-id="' + row.id + '" class="operate-btn js_removeNews">删除</a>' + '</div>';
                        }
                    }
                ]
            ],
            singleSelect: false,
            toolbar: '#news-bar',
            footer: '#news-footer'
        }

        var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
            //渲染表格
        $('#news-list').datagrid(config);

        var operateType = '';
        var editor = null;
        var editId = null;
        //编辑
        $(document).on('click', '.js_newsDialogEdit', function() {
            var self = $(this),
                index = self.attr('data-index'),
                id = self.attr('data-id'),
                rowData

            operateType = 'edit';
            editId = id;
            $('#newsid').attr('value', editId);

            rowData = $('#news-list').datagrid('getData').rows[index];



            $('#newsDialog').dialog({
                title: '编辑媒体报道',
                width: 700,
                closed: false,
                cache: false,
                modal: true
            });
            //设置title
            //$('#js_newsDialog')[0].reset();
            $('.js_newsDialogTitle').textbox('setValue', rowData.title);
            $('.js_newsDialogFrom').textbox('setValue', rowData.referer);
            var date = rowData.reportDate;
            $('.js_newsDialogDate').datebox('setValue', date);
            //$('.js_newsDialogDate').trigger('click');

            $('#js_newsDialog .thumbnail-wrap img').attr('src', rowData.img);
            $('#js_newsDialog input[name="img"]').val(rowData.img);
            //设置内容
            if (!editor) {
                editor = kindEditor('js_newsDialogEditor', 300, 200);
            }

            editor.html(rowData.content);
        })

        //批量删除
        $('.js_multiRemoveNews').linkbutton({
            onClick: function() {
               postDataGridMulti('./delNews', $(this), '#news-list');
            }
        })


        //新增
        $('.js_newsAdd').on('click', function() {
            operateType = 'add';
            //重置title

            $('#js_newsDialog')[0].reset();
            $('#newsid').attr('value', '');
            $('#js_newsDialog .thumbnail-wrap img').attr('src', '');
            $('.js_newsDialogTitle').textbox('setValue', '');
            $('.js_newsDialogFrom').textbox('setValue', '');
            $('.js_newsDialogDate').datebox('setValue', '');
            $('.js_newsDialogImg').filebox('setValue', '');
            $('#js_newsDialog .thumbnail-wrap img').attr('src', '');
            $('#js_newsDialog input[name="img"]').val('');


            $('#newsDialog').dialog({
                title: '新增媒体报道',
                width: 700,
                closed: false,
                cache: false,
                modal: true
            });
            //重置内容
            if (!editor) {
                editor = kindEditor('js_newsDialogEditor', 300, 200);
            }
            editor.html('');
        })

        //删除
        $(document).on('click', '.js_removeNews', function() {
            var self = $(this),
                id = self.attr('data-id')

            $.messager.confirm('确认', '您确认想要删除记录吗？', function(r) {
                if (r) {
                    arr = { id: id }
                    $.post('./delNews', arr, function(data) {
                        $('#news-list').datagrid('reload')
                    })
                }
            });
        })

        //提交
        $('.js_newsDialogSave').on('click', function() {
            var title = $('.js_newsDialogTitle').textbox('getValue'),
                img = $('#js_newsDialog input[name="img"]').val(),
                from = $('.js_newsDialogFrom').textbox('getValue'),
                date = $('.js_newsDialogDate').datebox('getValue'),
                content = editor.html(),
                newsid = $('#newsid').val() || null;


            if (title == '') {
                $.messager.alert('提示', '请设置标题字段', 'warning');
                return;
            }
            if (img == '') {
                $.messager.alert('提示', '请上传缩略图', 'warning');
                return;
            }
            if (content == '') {
                $.messager.alert('提示', '请设置内容字段', 'warning');
                return;
            }
            if (from == '') {
                $.messager.alert('提示', '请设置文章来源字段', 'warning');
                return;
            }
            if (date == '') {
                $.messager.alert('提示', '请设置时间点字段', 'warning');
                return;
            }

            var self = $(this);
            var ajaxData = {
                url: 'news',
                data: {
                    title: title,
                    id: newsid,
                    img: img,
                    content: content,
                    from: from,
                    date: date
                }
            }

            ajax(ajaxData, self).then(function(data) {
                $('#newsDialog').dialog('close');
                $('#news-list').datagrid('reload');
            })

        })


        //图片预览
        $(document).on('change', '.js_imgUpload', function() {
            var self = $(this);
            imgUploadPrev(self, function(e) {
                $('#js_newsDialog .thumbnail-wrap img').attr('src', e.target.result);
                $('#js_newsDialog input[name="img"]').val(e.target.result);
            })
        })
    })();


    //法律声明
    ~(function() {
        //创建编辑器
        var aboutEditor = kindEditor('legalEditor', 400, 300);
        //保存操作
        $('.js_saveLegalEditor').on('click', function() {

            var self = $(this);
            var ajaxData = {
                url: 'about',
                data: {
                    type: '法律声明',
                    title: '法律声明',
                    content: aboutEditor.html()
                }
            }
            ajax(ajaxData, self).then(function(data) {
                $.messager.show({
                    title: '提示',
                    msg: data.msg,
                    showType: 'null',
                    timeout: 600,
                    style: {
                        right: '',
                        top: '30%',
                        bottom: ''
                    }
                });
            }, function(rs){
                $.messager.show({
                    title: '提示',
                    msg: '请先修改内容',
                    showType: 'null',
                    timeout: 800,
                    style: {
                        right: '',
                        top: '30%',
                        bottom: ''
                    }
                });
            })
        })
    })();

    //联系我们
    ~(function() {
        $('.js_saveGz').on('click', function() {
            var company = $('.companyGz').textbox('getValue')
            fuwu = $('.fuwuGz').textbox('getValue'),
                phone = $('.phoneGz').textbox('getValue'),
                fax = $('.faxGz').textbox('getValue'),
                mail = $('.mailGz').textbox('getValue')
            address = $('.addressGz').textbox('getValue')
            if (company == '') {
                $.messager.alert('提示', '公司名不能为空', 'warning');
                return;
            }
            if (fuwu == '') {
                $.messager.alert('提示', '服务热线不能为空', 'warning');
                return;
            }
            if (fax == '') {
                $.messager.alert('提示', '传真不能为空', 'warning');
                return;
            }
            if (mail == '') {
                $.messager.alert('提示', '邮箱不能为空', 'warning');
                return;
            }
            if (phone == '') {
                $.messager.alert('提示', '固话不能为空', 'warning');
                return;
            }
            if (address == '') {
                $.messager.alert('提示', '公司地址不能为空', 'warning');
                return;
            }


            var self = $(this);
            var ajaxData = {
                url: 'contact',
                data: { title: '广州分公司', company: company, fuwu: fuwu, fax: fax, mail: mail, phone: phone, address: address }
            }
            ajax(ajaxData, self).then(function(data) {
                $.messager.show({
                    title: '提示',
                    msg: data.msg,
                    showType: 'null',
                    timeout: 600,
                    style: {
                        right: '',
                        top: '50%',
                        bottom: ''
                    }
                });
            }, function(rs){
                $.messager.show({
                    title: '提示',
                    msg: '请先修改内容',
                    showType: 'null',
                    timeout: 1000,
                    style: {
                        right: '',
                        top: '50%',
                        bottom: ''
                    }
                });
            })
        })


        //佛山
        $('.js_saveFs').on('click', function() {
            var company = $('.companyFs').textbox('getValue')
            fuwu = $('.fuwuFs').textbox('getValue'),
                phone = $('.phoneFs').textbox('getValue'),
                fax = $('.faxFs').textbox('getValue'),
                mail = $('.mailFs').textbox('getValue')
            address = $('.addressFs').textbox('getValue')
            if (company == '') {
                $.messager.alert('提示', '公司名不能为空', 'warning');
                return;
            }
            if (fuwu == '') {
                $.messager.alert('提示', '服务热线不能为空', 'warning');
                return;
            }
            if (fax == '') {
                $.messager.alert('提示', '传真不能为空', 'warning');
                return;
            }
            if (mail == '') {
                $.messager.alert('提示', '邮箱不能为空', 'warning');
                return;
            }
            if (phone == '') {
                $.messager.alert('提示', '固话不能为空', 'warning');
                return;
            }
            if (address == '') {
                $.messager.alert('提示', '公司地址不能为空', 'warning');
                return;
            }

            var self = $(this);
            var ajaxData = {
                url: 'contact',
                data: { title: '佛山总公司', company: company, fuwu: fuwu, fax: fax, mail: mail, phone: phone, address: address }
            }
            ajax(ajaxData, self).then(function(data) {
                $.messager.show({
                    title: '提示',
                    msg: data.msg,
                    showType: 'null',
                    timeout: 600,
                    style: {
                        right: '',
                        top: '30%',
                        bottom: ''
                    }
                });
            }, function(rs){
                $.messager.show({
                    title: '提示',
                    msg: '请先修改内容',
                    showType: 'null',
                    timeout: 1000,
                    style: {
                        right: '',
                        top: '30%',
                        bottom: ''
                    }
                });
            })
        })

        //商务合作
        $('.js_saveCooperation').on('click', function() {
            var provider = $('.provider').textbox('getValue'),
                providephone = $('.providephone').textbox('getValue'),
                providemail = $('.providemail').textbox('getValue'),
                provideqq = $('.provideqq').textbox('getValue'),

                buyer = $('.buyer').textbox('getValue'),
                buyphone = $('.buyphone').textbox('getValue'),
                buymail = $('.buymail').textbox('getValue'),
                buyqq = $('.buyqq').textbox('getValue'),

                extender = $('.extender').textbox('getValue'),
                extendphone = $('.extendphone').textbox('getValue'),
                extendmail = $('.extendmail').textbox('getValue'),
                extendqq = $('.extendqq').textbox('getValue'),

                invest = $('.invest').textbox('getValue'),
                investphone = $('.investphone').textbox('getValue'),
                investmail = $('.investmail').textbox('getValue'),
                investqq = $('.investqq').textbox('getValue'),

                kefu = $('.kefu').textbox('getValue'),
                kefuphone = $('.kefuphone').textbox('getValue'),
                kefumail = $('.kefumail').textbox('getValue'),
                kefuqq = $('.kefuqq').textbox('getValue')

            if (provider == '') {
                $.messager.alert('提示', '商务合作联系人不能为空', 'warning');
                return;
            }
            if (providephone == '') {
                $.messager.alert('提示', '商务合作固话不能空', 'warning');
                return;
            }
            if (providemail == '') {
                $.messager.alert('提示', '商务合作邮箱不能空', 'warning');
                return;
            }
            if (provideqq == '') {
                $.messager.alert('提示', '商务合作qq不能为空', 'warning');
                return;
            }
            //采购合作
            if (buyer == '') {
                $.messager.alert('提示', '采购合作联系人不能为空', 'warning');
                return;
            }
            if (buyphone == '') {
                $.messager.alert('提示', '采购合作固话不能空', 'warning');
                return;
            }
            if (buymail == '') {
                $.messager.alert('提示', '采购合作邮箱不可能空', 'warning');
                return;
            }
            if (buyqq == '') {
                $.messager.alert('提示', '采购合作qq不能为空', 'warning');
                return;
            }
            //品牌推广
            if (extender == '') {
                $.messager.alert('提示', '品牌推广联系人不能为空', 'warning');
                return;
            }
            if (extendphone == '') {
                $.messager.alert('提示', '品牌推广固话不能空', 'warning');
                return;
            }
            if (extendmail == '') {
                $.messager.alert('提示', '品牌推广邮箱不能空', 'warning');
                return;
            }
            if (extendqq == '') {
                $.messager.alert('提示', '品牌推广qq不能为空', 'warning');
                return;
            }
            //投资洽谈
            if (invest == '') {
                $.messager.alert('提示', '投资洽谈联系人不能为空', 'warning');
                return;
            }
            if (investphone == '') {
                $.messager.alert('提示', '投资洽谈固话不能空', 'warning');
                return;
            }
            if (investmail == '') {
                $.messager.alert('提示', '投资洽谈邮箱不能空', 'warning');
                return;
            }
            if (investqq == '') {
                $.messager.alert('提示', '投资洽谈qq不能为空', 'warning');
                return;
            }
            //客户服务
            if (kefu == '') {
                $.messager.alert('提示', '客户服务联系人不能为空', 'warning');
                return;
            }
            if (kefuphone == '') {
                $.messager.alert('提示', '客户服务固话不能空', 'warning');
                return;
            }
            if (kefumail == '') {
                $.messager.alert('提示', '客户服务邮箱不能空', 'warning');
                return;
            }
            if (kefuqq == '') {
                $.messager.alert('提示', '客户服务qq不能为空', 'warning');
                return;
            }
            //投诉建议
            // if (complain == '') {
            //     $.messager.alert('提示', '投诉建议联系人不能为空', 'warning');
            //     return;
            // }
            // if (complainphone == '') {
            //     $.messager.alert('提示', '投诉建议固话不能空', 'warning');
            //     return;
            // }
            // if (complainmail == '') {
            //     $.messager.alert('提示', '投诉建议邮箱不能空', 'warning');
            //     return;
            // }
            // if (complainqq == '') {
            //     $.messager.alert('提示', '投诉建议qq不能为空', 'warning');
            //     return;
            // }

            var self = $(this);
            var ajaxData = {
                url: 'cooperate',
                data: { provider: provider, providephone: providephone, providemail: providemail, provideqq: provideqq, buyer: buyer, buyphone: buyphone, buymail: buymail, buyqq: buyqq, extender: extender, extendphone: extendphone, extendmail: extendmail, extendqq: extendqq, invest: invest, investphone: investphone, investmail: investmail, investqq: investqq, kefu: kefu, kefuphone: kefuphone, kefumail: kefumail, kefuqq: kefuqq }
            }
            ajax(ajaxData, self).then(function(data) {
                $.messager.show({
                    title: '提示',
                    msg: '修改成功',
                    showType: 'null',
                    timeout: 600,
                    style: {
                        right: '',
                        top: '',
                        bottom: '-10%'
                    }
                });
            }, function(rs){
                $.messager.show({
                    title: '提示',
                    msg: '请先修改内容',
                    showType: 'null',
                    timeout: 1000,
                    style: {
                        right: '',
                        top: '',
                        bottom: '-10%'
                    }
                });
            })

        })

    })();

});
