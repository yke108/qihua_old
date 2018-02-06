$(function() {
    var currentID = null;
    //填充所在部门的树
    $('#tree').tree({
        url: './partnerList',
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

            currentID = node.id;

            if (type == 1) {
                //顶级 -- 新增一级部门
                $('#toolbar .addPartner').linkbutton('enable');
                $('#toolbar .removePartner').linkbutton('disable');

                $('#partnerForm').form('clear');
                $('.wrapper').hide();
                $('#partnerForm .thumbnail-wrap img').removeAttr('src');
            }

            if (type == 2) {

                $('#toolbar .addPartner').linkbutton('disable');
                $('#toolbar .removePartner').linkbutton('enable');

                $('.wrapper').show();

                var text = node.text;
                var imgUrl = node.attributes.img;

                $('#partnerForm').form('load', {
                    title: text,
                    imgUrl: imgUrl
                })

                $('#partnerForm .thumbnail-wrap img').attr('src', imgUrl);

            }

        }
    })

    $('.easyui-layout').css('visibility', 'visible');

    //新增
    $('#toolbar .addPartner').linkbutton({
        onClick: function() {
            $('#addPartner').form('clear');
            $('#dlg').dialog({
                title: '新增合作伙伴',
                width: 320,
                height: 430,
                closed: false,
                cache: false,
                modal: true,
                buttons: 'btns'
            });
        }
    })

    //新增合作伙伴--提交
    $(document).on('click', '#addPartner .js_save', function(e) {
        var self = $(this);
        if (!$('#addPartner').form('validate')) return;

        if($('#addPartner input[name="img"]').val() == '') {
            $.messager.show({
                title:'提示',
                msg:'必須设置图片',
                showType:'null',
                timeout: 1000,
                style:{
                    right:'',
                    top:'30%',
                    bottom:''
                }
            });
            return;
        }
        var ajaxData = {
            url: 'addpartner',
            data: {
                text: $('#addPartner input[name="title"]').val(),
                img: $('#addPartner input[name="img"]').val()
            }
        }

        ajax(ajaxData, self).then(function() {
            $('#tree').tree('reload');
            $('#dlg').dialog('close');
            $('#addPartner input[name="title"]').val('');
            $('#addPartner input[name="img"]').val('');
            $('#addPartner .thumbnail-wrap img').removeAttr('src');
        })
    })

    //删除 
    $('#toolbar .removePartner').linkbutton({
        onClick: function() {
            var self = $(this);

            var ajaxData = {
                url: 'delpartner',
                data: {
                    id: currentID
                }
            }
            ajax(ajaxData, self).then(function() {
                $('#tree').tree('reload');
                $('#dlg').dialog('close');
                $('#toolbar .removePartner').linkbutton('disable');
                $('.wrapper').hide();
                currentID = null;
            })
        }
    })

    //修改--提交
    $(document).on('click', '#partnerForm .js_save', function(e) {
        var self = $(this);
        if (!$('#partnerForm').form('validate')) return;

        var ajaxData = {
            url: 'addpartner',
            data: {
                text: $('#partnerForm input[name="title"]').val(),
                id: currentID,
                img: $('#partnerForm input[name="img"]').val()
            }
        }

        ajax(ajaxData, self).then(function() {
            $('#tree').tree('reload');
            $.messager.show({
                title:'提示',
                msg:'修改成功',
                showType:'null',
                timeout: 1000,
                style:{
                    right:'',
                    top:'30%',
                    bottom:''
                }
            });

        })
    })






    //图片预览
    $(document).on('change', '#partnerForm .js_imgUpload', function() {
        var self = $(this);
        imgUploadPrev(self, function(e) {
            $('#partnerForm .thumbnail-wrap img').attr('src', e.target.result);
            $('#partnerForm input[name="img"]').val(e.target.result);
        })
    })

    $(document).on('change', '#addPartner .js_imgUpload', function() {
        var self = $(this);
        imgUploadPrev(self, function(e) {
            $('#addPartner .thumbnail-wrap img').attr('src', e.target.result);
            $('#addPartner input[name="img"]').val(e.target.result);
        })
    })


});
