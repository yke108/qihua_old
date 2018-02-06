$(function() {
    //初始化表格配置参数
    var pageGridConfig = {
        footer: '#footerBar',
        url: '/Admin/Resource/overdue', //请求路径
        columns: [
            [
                {field: '_', checkbox: true },
                { field: 'title', title: '商品名称', align: 'center', width: '8%' },
                { field: 'cas', title: 'CAS号', align: 'center', width: '6%' },
                { field: 'brand', title: '品牌名', align: 'center', width: '6%' },
                { field: 'spec', title: '纯度/规格', align: 'center', width: '6%' },
                { field: 'price', title: '参考价格', align: 'center', width: '6%',
                    formatter: function(v, r, i) {
                               var str = '￥';
                               if (r.currency == 2) {
                                   str = '$'
                               }
                               return str + r.price + "\/" + r.weightUnit;
                           }
                 },
                { field: 'area', title: '货物所在地', align: 'center', width: '8%' },
                { field: 'expire', title: '有效期截止', align: 'center', width: '8%' },
                { field: 'company', title: '公司名称', align: 'center', width: '10%' },
                { field: 'updateTime', title: '创建/修改时间', align: 'center', width: '10%' },
                { field: 'state', title: '状态', align: 'center', width: '12%'},
                { field: 'check', title: '审核时间', align: 'center', width: '12%',
                    formatter: function(value, row, index) {
                         return '<p>'+row.operator+'</p><p>'+row.check+'</p>';
                    }
                }
            ]
        ],
        singleSelect: false
    }



    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
    //渲染表格

    //渲染表格
    $('#dataGrid').datagrid(config);

    $('#footerBar').css('visibility', 'visible')

         //省市联动
    ajax({ url: '/Home/Area/areas' }).then(function(rs) {
        $('#js_province').combobox('loadData', rs.data);
    });
    $('#js_province').combobox({
        valueField: 'id',
        textField: 'text',
        onSelect: function(param, b) {

            ajax({ url: '/Home/Area/areas', data: { id: param.id } }).then(function(rs) {
                $('.js_select_city').show();
                $('#js_city').combobox('loadData', rs.data);
            });
        }
    });
    $('#js_city').combobox({
        valueField: 'id',
        textField: 'text'
    });


    //获取地区数据
    $('#js_getAreaData').on('click', function() {
            //先获取省数据
            $(this).hide();
            $('.js_select_province').show();
        })
        //搜索功能 提交表格
    $('#js_search').on('click', function() {
        var cas = $('#js_goodName').textbox('getValue')
            province = $('#js_province').combobox('getValue'),
            city = $('#js_city').combobox('getValue'),
            company = $('#js_company').textbox('getValue'),
            arr = [],
            area = ''

        if (province) {
            arr.push(province)
        }
        if (city) {
            arr.push(city)
        }

        var queryParams = {
            cas: cas,
            area: arr.join(','),
            company: company
        }

        $('#dataGrid').datagrid('load', queryParams);
    })

//批量删除
    $('.removeSome').linkbutton({
        onClick: function() {
           postDataGridMulti('./delresource', $(this), '#dataGrid');
        }
    })
});
