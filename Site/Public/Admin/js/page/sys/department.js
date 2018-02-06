$(function() {

    var currentId = null;
    //填充所在部门的树
    $('#tree').tree({
        //data:treeData,
        url: authUserUrl,
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
            var type = node.attributes.type;
            var id = node.id;
            var parentid = node.attributes.parentid;

            //保存当前点击的id
            currentId = id;

            if (type !== 1) {
                var text = node.text;
                var code = node.attributes.code;
                var remarks = node.attributes.remarks;
            }

            if (type == 1) {
                //顶级 -- 新增一级部门
                $('#toolbar .addParent').linkbutton('enable');
                $('#toolbar .addChild').linkbutton('disable');
                $('#toolbar .remove').linkbutton('disable');
                $('#departmentForm').hide();

                $('#departmentForm').form('clear');

                $('#toolbar .addParent').linkbutton({
                    onClick: function() {
                        $('#addParentForm').form('clear');
                        $('#addParentDialog').dialog({
                            title: '新增一级部门',
                            width: 360,
                            height: 150,
                            closed: false,
                            cache: false,
                            modal: true,
                            buttons: 'btns',
                            onOpen:function(){
                                $("#addParentDialog #addParentForm #parentid").val(node.id);
                            }
                        });
                    }
                })
            } else {

                //二级目录 -- 新增子部门
                $('#toolbar .addParent').linkbutton('disable');
                $('#toolbar .addChild').linkbutton('enable');
                $('#toolbar .remove').linkbutton('enable');
                $('#departmentForm').show();

                $('#toolbar .addChild').linkbutton({
                    onClick: function() {
                        $('#addChildForm').form('clear');
                        $('#addChildDialog').dialog({
                            title: '新增子部门',
                            width: 360,
                            height: 200,
                            closed: false,
                            cache: false,
                            modal: true,
                            buttons: 'btns',
                            onOpen:function(){
                                $("#addChildDialog #addChildForm #parentid").val(node.id);
                            }
                        });
                    }
                })


                $('#departmentForm').form('load', {
                    name: text
                })
            }
        }
    })

    $('.easyui-layout').css('visibility', 'visible');

    function reset() {
        currentId = null;
        $('#departmentForm').hide().form('clear');
    }
    /**
     * 提交后后更新树
     * @param  {obj} data ajax返回数据
     */
    function handlerAddTree(data) {
        data = JSON.parse(data);
        
        reset();

        if(data.code == 200) {
            $('#tree').tree('reload');
        } else {
            $.messager.alert('提示',data.msg,'warning');
        }
    }
    //新增一级部门
    $(document).on('click', '.js_saveAddParentForm', function(e) {
            $('#addParentForm').form('submit', {
                url: '/Admin/Auth/departmentAdd',
                dataType: 'json',
                onSubmit: function() {
                    var isValid = $(this).form('validate');
                    if (!isValid) {
                        $.messager.progress('close'); // 如果表单是无效的则隐藏进度条
                    }
                    return isValid; // 返回false终止表单提交
                },
                success: function(data) {
                    $('#addParentDialog').dialog('close');

                    handlerAddTree(data);
                        
                }
            });
            return false;
        })
    //新增子部门
    $(document).on('click', '.js_saveAddChildForm', function(e) {
        $('#addChildForm').form('submit', {
            url: '/Admin/Auth/departmentAdd',
            onSubmit: function() {
                var isValid = $(this).form('validate');
                if (!isValid) {
                    $.messager.progress('close'); // 如果表单是无效的则隐藏进度条
                }
                return isValid; // 返回false终止表单提交
            },
            success: function(data) {
                $('#addChildDialog').dialog('close');
                $('#addChildForm').form('clear');

                handlerAddTree(data);
            }
        });
        return false;
    })

    //打开新窗口查看编辑或者预览
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
        url = $(this).attr("data-href");
        //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
        window.parent.addTab(tabTitle, url);
    });


    //提交修改
    $(document).on('click', '.js_departmentSubmit', function() {
        var self = $(this);
        var val = $('#name').textbox('getValue');

        if(!currentId) return;
        var ajaxData = {
            url: departmentSave,
            data: {
                id:currentId, 
                name: val
            }
        }
        ajax(ajaxData, self).then(function(data){
            reset();
            $('#tree').tree('reload');
        })

    })

    //删除操作
    $(document).on('click', '.js_removeDepartment', function() {
        var self = $(this);

        if(!currentId || self.hasClass('l-btn-disabled')) return;

        var ajaxData = {
            url: departmentDel,
            data: {
                id:currentId
            }
        }
        
        ajax(ajaxData, self).then(function(data){
            reset();
            $('#tree').tree('reload');
        }, function(rs){
            //$.messager.alert('提示',rs.msg); 
        })

    })

});
