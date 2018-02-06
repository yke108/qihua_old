$(function() {
    //模拟数据
    var treeData = [{
        "id": 1,
        "text": "Folder1",
        "iconCls": "icon-save",
        "children": [{
            "id": 109,
            "text": "File1",
            "checked": true
        }, {
            "id": 3,
            "text": "Books",
            "state": "open",
            "attributes": {
                "url": "/demo/book/abc",
                "price": 100
            },
            "children": [{
                "id": 9,
                "text": "PhotoShop",
                "checked": true
            }, {
                "id": 11,
                "text": "Sub Bookds"
            }]
        }]
    },{
        "id": 18,
        "text": "Languages",
        "state": "closed",
        "children": [{
            "id": 102,
            "text": "Java"
        }, {
            "id": 103,
            "text": "C#"
        }]
    }]
    var roleData = [{
            "id": 1,
            "text": "text1",
            "selected": true
        }, {
            "id": 2,
            "text": "text2"
        }, {
            "id": 3,
            "text": "text3"
        }, {
            "id": 4,
            "text": "text4"
        }, {
            "id": 5,
            "text": "text5"
        }]
        
    //选择部门
    $('#js_department').combotree({
        data: treeData,
        lines: true,
        onClick: function(node) {

            console.log(node)
                //动态加载部门角色并显示选择框
                //$('#role').combobox('reload', 'combobox_data1.json')
            $('#role').combobox('loadData', roleData);
            $('.role-wrap').css('visibility', 'visible');
        }
    })


    //提交表单
    $('.js_submit').on('click', function(e) {
        $.messager.progress(); // 显示进度条
        $('#js_addUserForm').form('submit', {
            url: 'xxx',
            onSubmit: function() {
                var isValid = $(this).form('validate');
                if (!isValid) {
                    $.messager.progress('close'); // 如果表单是无效的则隐藏进度条
                }
                return isValid; // 返回false终止表单提交
            },
            success: function() {
                $.messager.progress('close'); // 如果提交成功则隐藏进度条
            }
        });

        return false;
    })

    
    
});
