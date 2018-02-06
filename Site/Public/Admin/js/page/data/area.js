$(function() {
   $('.easyui-layout').css('visibility', 'visible');

    //填充所在部门的树
    var currentID = null;
    $('#tree').tree({
    	url:areaListUrl, 
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
                remarks = node.attributes.remarks,
                id = node.id

            currentID = id;
            $('#toolbar .addChild').linkbutton('enable');
            $('#toolbar .remove').linkbutton('enable');
            $('#dataForm').show();

            $('#dataForm').form('load', {
                dataName: text
            })

        },
        onBeforeExpand:function(node,param){                         
            $('#tree').tree('options').url = '/Admin/data/getChildArea/id='+node.id;                  
        } 
    })


    $('#toolbar .addParent').linkbutton({
        onClick: function() {
            $('#addParentForm').form('clear');
            $('#addParentDialog').dialog({
                title: '新增顶级',
                width: 360,
                height: 200,
                closed: false,
                cache: false,
                modal: true,
                buttons: 'btns'
            });
        }
    })

    $('#toolbar .addChild').linkbutton({
        onClick: function() {
            $('#addChildForm').form('clear');
            $('#addChildDialog').dialog({
                title: '新增子级',
                width: 360,
                height: 200,
                closed: false,
                cache: false,
                modal: true,
                buttons: 'btns'
            });
        }
    })
    //新增顶级

    $(document).on('click', '.js_saveAddParentForm', function(e) {
        if(!$('#addParentForm').form('validate')) return false;
        var self = $(this);

        var ajaxData = {
            url: addAreaUrl,
            data: {
                text: $('#addParentName').textbox('getValue')
            }
        }
        ajax(ajaxData, self).then(function(data){
            $('#tree').tree('options').url = areaListUrl;
            $('#tree').tree('reload');
            $('#addParentDialog').dialog('close');
        })
    })
    //新增子级
    $(document).on('click', '.js_saveAddChildForm', function(e) {

        if(!$('#addChildForm').form('validate')) return false;
        var self = $(this);

        var ajaxData = {
            url: addAreaUrl,
            data: {
                id: currentID,
                text: $('#addChildName').textbox('getValue')
            }
        }
        ajax(ajaxData, self).then(function(data){
            $('#tree').tree('options').url = areaListUrl;
            $('#tree').tree('reload');
            $('#addChildDialog').dialog('close');
        })
    })


    //提交修改
    $(document).on('click', '.js_dataSubmit', function() {
        if(!$('#dataForm').form('validate')) return false;
        var self = $(this);

        var ajaxData = {
            url: updateAreaUrl,
            data: {
                id: currentID,
                text: $('#dataName').textbox('getValue')
            }
        }
        ajax(ajaxData, self).then(function(data){
            $('#tree').tree('options').url = areaListUrl;
            $('#tree').tree('reload');
            $('#dataForm').hide()
        })
    })

    //删除
    $(document).on('click', '#toolbar .remove', function() {
        var self = $(this);
        if(!currentID) return;
        $.messager.confirm('确认', '您确认想要删除吗？', function(r) {
            if (r) {
               var ajaxData = {
                   url: delAreaUrl,
                   data: {
                       id: currentID
                   }
               }
               ajax(ajaxData, self).then(function(data){
                $('#tree').tree('options').url = areaListUrl;
                   $('#tree').tree('reload');
                   $('#dataForm').hide()
                   currentID = null;
               })
            }
        });
        
    })
});
