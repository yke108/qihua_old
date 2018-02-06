$(function() {

    //关于我们
    ~(function(){
        //保存操作
        $('.js_saveLogo').on('click', function(){
            var self = $(this);
            var img = $('input[name="img"').val();
            if(!img) {
                $.messager.alert('提示','请选择logo图片','info');
                return;
            }
            var ajaxData = {
                url:'logo',
                data: {
                    img: img
                }
            }
            ajax(ajaxData, self).then(function(data){
                $.messager.alert('提示',data.msg,'info');
            })
            
        })

        //图片预览
        $(document).on('change', '.js_imgUpload', function(){
            var self = $(this);
            imgUploadPrev(self, function(e){
                $('.thumbnail-wrap img').attr('src', e.target.result);
                $('input[name="img"]').val(e.target.result);
            })
        })
    })();

});
