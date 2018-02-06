$(function() {
    var currentTabTitle = $('.tabs-selected .tabs-title').html();
    var treeData = null;
    var currentId = null;
    var currenTree = $('#' + currentTabTitle + 'Tree');

    function reset() {
        currentId = null;
        $('#addDlg').dialog('close');
        $('#js_addMenu').form('clear');
    }


    function renderTree() {
        ajax({ url: authRuleUrl }).then(function(data) {
            treeData = data.data;

            setContent(currentTabTitle, treeData);
        })
    }

    function setContent(sys, treeData) {
        //填充系统树
        $('#' + sys + 'Tree').tree({
            data: treeData[sys],
            onClick: function(node) {
                var type = node.attributes.type;
                var id = node.id;
                var parentid = node.attributes.parentid;
                var title = node.text;
                var name = node.name;

                //保存当前点击的id
                currentId = id;

                if (type == 1) {
                    //顶级 -- 新增菜单
                    $('#' + sys + 'Toolbar .addMenu').linkbutton('enable');
                    $('#' + sys + 'Toolbar .addControl').linkbutton('disable');
                    $('#' + sys + 'Toolbar .remove').linkbutton('enable');
                    $('#' + sys + 'Form').hide();

                    $('#js_addMenu').form('clear');

                    $('#' + sys + 'Toolbar .addMenu').linkbutton({
                        onClick: function() {
                            $('#addDlg').dialog({
                                title: '新增菜单',
                                width: 360,
                                height: 200,
                                closed: false,
                                cache: false,
                                modal: true,
                                buttons: 'btns',
                                onOpen: function() {
                                    $("#js_addMenu .parentid").val(node.id);
                                }
                            });
                        }
                    })
                } else  {
                        //二级目录 -- 新增操作功能
                        $('#' + sys + 'Toolbar .addMenu').linkbutton('disable');
                        if(type == 2) {
                            $('#' + sys + 'Toolbar .addControl').linkbutton('enable');
                        } else {
                            $('#' + sys + 'Toolbar .addControl').linkbutton('disable');
                        }

                        $('#' + sys + 'Toolbar .remove').linkbutton('enable');
                        $('#' + sys + 'Form').show();

                        $('#js_addControl').form('clear');

                        $('#' + sys + 'Toolbar .addControl').linkbutton({
                            onClick: function() {
                                $('#addDlg').dialog({
                                    title: '新增操作功能',
                                    width: 360,
                                    height: 200,
                                    closed: false,
                                    cache: false,
                                    modal: true,
                                    buttons: 'btns',
                                    onOpen: function() {
                                        $("#js_addMenu .parentid").val(node.id);
                                    }
                                });
                            }
                        })

                        $('#' + sys + 'Form').form('load', {
                            title: title,
                            name: name,
                        })
                }
            }
        })

        $('#' + sys + 'Toolbar .addTop').linkbutton({
            onClick: function() {
                $('#addDlg').dialog({
                    title: '新增顶级',
                    width: 360,
                    height: 200,
                    closed: false,
                    cache: false,
                    modal: true,
                    buttons: 'btns',
                    onOpen: function() {
                        $("#js_addMenu .parentid").val(0);
                    }
                });
            }
        })
    }

    renderTree()

    $('.easyui-tabs').css('visibility', 'visible');

    $('#js_sysTab').tabs({
        onSelect: function(title, index) {
            //所有按钮不能点击
            $('.toolbar .easyui-linkbutton').linkbutton('disable');
            currenTree = $('#' + title + 'Tree');
        }
    })

    
    // //新增保存
    $(document).on('click', '.js_saveAdd', function() {
        var self = $(this);
        var name = $('#js_addMenu input[name="name"]').val(),
            title = $('#js_addMenu input[name="title"]').val(),
            parentid = $('#js_addMenu .parentid').val()


        var ajaxData = {
            url: ruleAddUrl,
            data: {
                parentid: parentid,
                title: title,
                name: name
            }
        }

        ajax(ajaxData, self).then(function(data) {
            reset();
            renderTree();
        })

    })
    //提交修改
    $(document).on('click', '.js_submit', function() {
        var self = $(this);
        var name = $('#'+currentTabTitle+'Form input[name="name"]').val();
        var title = $('#'+currentTabTitle+'Form input[name="title"]').val();

        if(!currentId) return;
        var ajaxData = {
            url: ruleSaveUrl,
            data: {
                id:currentId, 
                title: title,
                name: name
            }
        }

        ajax(ajaxData, self).then(function(data){
            console.log('ajax')
            $('#'+currentTabTitle+'Form').hide();
            $('.addControl').linkbutton('disable');
            currentId = null;
            renderTree();
        }, function(){
            //console.log(ajaxData)
        })

        return false;
    })

    //删除操作
    $(document).on('click', '.remove', function() {
        var self = $(this);

        if(!currentId) return;

        var ajaxData = {
            url: ruleDelUrl,
            data: {
                id:currentId
            }
        }

        $.messager.confirm('确认','您确认想要删除吗？',function(r){    
            if (r){    
                ajax(ajaxData, self).then(function(data){
                    $('#'+currentTabTitle+'Form').hide();
                    $('.addControl').linkbutton('disable');
                    currentId = null;
                    renderTree();
                }) 
            }    
        });
        


    })

});
