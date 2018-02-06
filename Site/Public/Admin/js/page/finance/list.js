$(function(){
    var $table=$('#dataGrid');
    var $code=$('input[name="code"]');
    var $companyName=$('input[name="companyName"]');
    var pageGridConfig={
        url:'/Admin/Finance/dataList',
        columns:[
            [
                {
                    title:'申请编号',
                    field:'id',
                    width:"10%",
                    align:'center'
                },
                {
                    title:'公司名称',
                    field:'companyName',
                    width:"25%",
                    align:'center',
                    formatter:function(value,row,index){

                        return '<a href="javascript:void(0);" data-title="商家详情-'+row.companyName+'" data-href="/Admin/member/memberDetail?id='+row.companyId+'" data-id="'+row.companyId+'" class="js_iframeLink">'+row.companyName+'</a>'

                    }
                },
                {
                    title:'从业时长',
                    field:'worktime',
                    width:"8%",
                    align:'center'
                },
                {
                    title:'进货量',
                    field:'consume',
                    width:"8%",
                    align:'center'
                },
                {
                    title:'贷款额度',
                    field:'loanLimit',
                    width:"8%",
                    align:'center'
                },
                {
                    title:'还款周期',
                    field:'repayCycle',
                    width:"8%",
                    align:'center'
                },
                {
                    title:'还款方式',
                    field:'repayType',
                    width:"13%",
                    align:'center'
                },
                {
                    title:'申请时间',
                    field:'addTime',
                    width:"12%",
                    align:'center'
                },
                {
                    title:'操作',
                    field:'handle',
                    width:"8%",
                    align:'center',
                    formatter:function(value,row,index){

                        return '<a href="javascript:void(0);" class="operate-btn js_del" data-code="'+row.id+'">刪除</a>'

                    }
                },
            ]
        ],
        pagination:true,
        pagePosition:'bottom',
        pageNumber:1,
        pageSize:20,
        pageList:[10,20,40],
        queryParams:{
            code:$code.val(),
            companyName:$companyName.val()
        }
    };

    $table.datagrid(pageGridConfig);

    var $js_userListSearch=$('#js_userListSearch');

    $js_userListSearch.on('click',function(event){
        var queryParams={
                code:$code.val(),
                companyName:$companyName.val()
            }
        $table.datagrid('reload',queryParams);
    })

    $(document).on('click','.js_del',function(event){
        var $this=$(this);
        var code=$this.attr('data-code');

        $.messager.confirm('确认提示', '您确认要刪除该申请吗？', function(r) {
            if (r) {
                var postData = {
                    url: '/Admin/Finance/del',
                    data: {
                        code: code
                    }
                }

                ajax(postData).then(function(rs){
                    var queryParams={
                        code:$code.val(),
                        companyName:$companyName.val()
                    }
                    $table.datagrid('reload',queryParams);
                })
            }
        });

    })


    //打开新窗口查看详情
    $(document).on('click', '.js_iframeLink', function() {
        var tabTitle = $(this).attr('data-title')
        url = $(this).attr("data-href");
        //获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
        window.parent.addTab(tabTitle,url);
    });

})