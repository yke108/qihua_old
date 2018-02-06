$(function() {

    $('.easyui-layout').css('visibility', 'visible');

    var currentID = null;
    //填充所在部门的树
    $('#tree').tree({
        //data: treeData,
        url: producerListUrl,
        url: producerListUrl,
        dnd: true,
        formatter: function(node) {
            if(node.attributes.type != 1) {
                return node.text+'('+node.attributes.sortName+')';
            } else {
                return node.text;
            }
        },
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

            if (type == 1) {
                $('#toolbar .addChild').linkbutton('enable');
                $('#toolbar .remove').linkbutton('disable');
            }
            if (type == 2) {
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


    $('#toolbar .addChild').linkbutton({
        onClick: function() {
            $('#addChildForm').form('clear');
            $('#addChildDialog').dialog({
                title: '新增生产商',
                width: 360,
                height: 200,
                closed: false,
                cache: false,
                modal: true,
                buttons: 'btns'
            });
        }
    })

    //新增
    $(document).on('click', '.js_saveAddChildForm', function(e) {
        if (!$('#addChildForm').form('validate')) return false;
        var self = $(this);

        var ajaxData = {
            url: addProducerUrl,
            data: {
                id: currentID,
                text: $('#addChildForm .text').textbox('getValue'),
                shorttext: $('#addChildForm .sortName').textbox('getValue')
            }
        }
        ajax(ajaxData, self).then(function(data) {
            $('#tree').tree('reload');
            $('#addChildDialog').dialog('close');
        })
    })


    //提交修改
    $(document).on('click', '.js_dataSubmit', function() {
        if (!$('#dataForm').form('validate')) return false;
        var self = $(this);

        var ajaxData = {
            url: updateProducerUrl,
            data: {
                id: currentID,
                text: $('#dataForm .dataName').textbox('getValue'),
                shorttext: $('#dataForm .sortName').textbox('getValue')
            }
        }
        ajax(ajaxData, self).then(function(data) {
            $('#tree').tree('reload');
            $('#dataForm').hide()
        })
    })

    //删除

    $(document).on('click', '#toolbar .remove', function() {
        var self = $(this);
        if (!currentID) return;
        $.messager.confirm('确认', '您确认想要删除吗？', function(r) {
            if (r) {
                var ajaxData = {
                    url: delProducerUrl,
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
