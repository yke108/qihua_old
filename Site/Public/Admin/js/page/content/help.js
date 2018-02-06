$(function() {

    var currentID = null;
    var currentParentTree = $('#tree-1');
    var currentChildTree = $('#tree-2');
    var editor = null;
    //填充所在部门的树
    $('#tree-1').tree({
        url:'helpList',//数据的接口
        dnd: true,
        onBeforeDrop: function(target, source, point) {
            //实现只在同级拖动
            if (point === 'append') {
                return false;
            }
        },
        onDrop: function(target, source, point) {
            console.log($(this).tree('getNode', target), source, point)
        },
        onClick: function(node) {
            $('.btns-sub .addPartner').linkbutton('disable');
            $('.btns-sub .removePartner').linkbutton('disable');
            $('.wrapper').hide();
            $('.edit-wrap').hide();

            var id = node.id
                type = node.attributes.type,
                title = node.text;

            currentID = id;

            $('#dataForm').attr('data-operate', 'edit');

            if(type == 1) {
                $('.btns .addPartner').linkbutton('enable');
                $('.btns .removePartner').linkbutton('disable');
                $('.wrapper').hide();
                return;
            } else {
                $('.btns .addPartner').linkbutton('disable');
                $('.btns .removePartner').linkbutton('enable');
                $('.wrapper').show();
                $('.help-title').textbox('setValue', title);
            }

            $('#tree-2').tree({
                    url: 'helpList',
                    queryParams: {
                        id: id
                    },
                    dnd: true,
                    onBeforeDrop: function(target, source, point) {
                        //实现只在同级拖动
                        if (point === 'append') {
                            return false;
                        }
                    },
                    onDrop: function(target, source, point) {
                        console.log($(this).tree('getNode', target), source, point)
                    },
                    onClick: function(node) {
                        $('.btns .addPartner').linkbutton('disable');
                        $('.btns .removePartner').linkbutton('disable');

                        var id = node.id
                            type = node.attributes.type;

                        currentID = id;

                        if (!editor) {
                            editor = kindEditor('editor', 400, 200);
                        }

                        if(type == 1) {
                            $('.btns-sub .addPartner').linkbutton('enable');
                            $('.btns-sub .removePartner').linkbutton('disable');
                            $('#dataForm').attr('data-operate', 'add');
                            $('.wrapper').hide();
                            return;
                        } else {
                            $('.btns-sub .addPartner').linkbutton('disable');
                            $('.btns-sub .removePartner').linkbutton('enable');
                            $('.wrapper').show();
                            $('.edit-wrap').show();
                            $('#dataForm').attr('data-operate', 'edit');
                        }


                        var title = node.text;
                        var content = node.attributes.content;



                        editor.html(content);

                        $('.help-title').textbox('setValue', title);
                        $('.help-title').textbox('setText', title);

                        $('.wrapper').show();

                    }
                })
                //显示二级菜单按钮
            $('.btns-sub').show();
        }
    })

    //新增一级-弹窗
    $('.btns .addPartner').linkbutton({
        onClick: function() {
                $('#dlg').dialog({
                    title: '新增',
                    width: 520,
                    height: 200,
                    closed: false,
                    cache: false,
                    modal: true,
                    buttons: 'btns',
                    onOpen:function(){
                }
            });
        }
    })
    //新增-保存
    $('#dlg .js_dlgSave').on('click', function(){
        if(!$('#dlgForm').form('validate')) return;

        var ajaxData = {
            url: 'addhelp',
            data: {
                id: 0,
                text: $('#dlg input[name="title"]').val()
            }
        }
        ajax(ajaxData).then(function(data) {
            $('#dlg').dialog('close');
            currentParentTree.tree('reload');
        })
    })

    //新增二级
    $('.btns-sub .addPartner').linkbutton({
        onClick: function() {
            $('.wrapper').show();
            $('.edit-wrap').show();
            editor.html('');
            $('.help-title').textbox('setValue', '');
        }
    })

    //新增二级-保存
    $('#dataForm .js_saveHelp').on('click', function(){
        var type = $('#dataForm').attr('data-operate');
        var url = '';
        var content = '';

        if(!$('#dataForm').form('validate')) return;

        if(type == 'add') {
            url = 'addhelp';
        } else if( type == 'edit') {
            url = 'addpartner';
        }

        if(editor) {
            content = editor.html()
        }

        var ajaxData = {
            url: url,
            data: {
                id: currentID,
                content: content,
                text: $('#dataForm input[name="title"]').val()
            }
        }

        ajax(ajaxData).then(function(data) {
            currentParentTree.tree('reload');
            currentChildTree.tree('reload');
            $('.wrapper').hide();
        })
    })

    //删除
    $('.btns .removePartner').linkbutton({
        onClick: function() {
            var ajaxData = {
                url: 'delhelp',
                data: {
                    id: currentID
                }
            }
            ajax(ajaxData).then(function(data){
                currentParentTree.tree('reload');
                currentChildTree.tree('loadData', []);
                $('.wrapper').hide();
            })
        }
    })
    $('.btns-sub .removePartner').linkbutton({
        onClick: function() {
            var ajaxData = {
                url: 'delpartner',
                data: {
                    id: currentID
                }
            }
            ajax(ajaxData).then(function(data){
                currentChildTree.tree('reload');
                $('.wrapper').hide();
            })
        }
    })


    //打开新窗口查看编辑或者预览
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
        url = $(this).attr("data-href");
        //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
        window.parent.addTab(tabTitle, url);
    });


});
