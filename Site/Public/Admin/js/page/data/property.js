$(function() {
    var currentID = null;
    //填充所在树
    $('#tree').tree({
    	url: propertyListUrl,
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
            var type = node.attributes.type,
                text = node.text,
                code = node.attributes.code,
                sortName = node.attributes.sortName,
                id = node.id

            currentID = id;

            if(type == 1) {
                $('#toolbar .addChild').linkbutton('enable');
                $('#toolbar .remove').linkbutton('disable');
            }
            if(type == 2) {
                $('#toolbar .remove').linkbutton('enable');
                $('#toolbar .addChild').linkbutton('disable');
                $('#dataForm').show();

                $('#dataForm').form('load', {
                    dataName: text,
                    sortName: sortName
                })
            }
        }
    }) 

    $('.easyui-layout').css('visibility', 'visible');

    $('#toolbar .addChild').linkbutton({
        onClick: function() {
            $('#addChildForm').form('clear');
            $('#addChildDialog').dialog({
                title: '新增单位性质',
                width: 360,
                height: 200,
                closed: false,
                cache: false,
                modal: true,
                buttons: 'btns'
            });
        }
    })

    //新增单位性质
    $(document).on('click', '.js_saveAddChildForm', function(e) {

        if (!$('#addChildForm').form('validate')) return false;
        var self = $(this);

        var ajaxData = {
            url: addPropertyUrl,
            data: {
                id: currentID,
                type: 2,
                text: $('#addChildName').textbox('getValue')
            }
        }
        ajax(ajaxData, self).then(function(data) {
            $('#tree').tree('reload');
            $('#addChildDialog').dialog('close');
            $('#addChildForm').form('clear');
        })

    })


    //提交修改
    $(document).on('click', '.js_dataSubmit', function() {
        if (!$('#dataForm').form('validate')) return false;
        var self = $(this);

        var ajaxData = {
            url: updatePropertyUrl,
            data: {
                id: currentID,
                text: $('#dataName').textbox('getValue')
            }
        }
        ajax(ajaxData, self).then(function(data) {
            $('#tree').tree('reload');
            $('#dataForm').hide();
            $('#addChildForm').form('clear');
        })
    })
    
    //删除
    $(document).on('click', '#toolbar .remove', function() {
        var self = $(this);
        if (!currentID) return;
        $.messager.confirm('确认', '您确认想要删除吗？', function(r) {
            if (r) {
                var ajaxData = {
                    url: delPropertyUrl,
                    data: {
                        id: currentID
                    }
                }
                ajax(ajaxData, self).then(function(data) {
                    $('#tree').tree('reload');
                    $('#dataForm').hide()
                    currentID = null;
                })
            }
        });
    })

});
