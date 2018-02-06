;(function ($) {
  $(function () {
        // 初始化表格配置参数
    var pageGridConfig = {
      url: '/Admin/member/memberList', // 请求路径
      columns: [
        [
                    { field: '_', checkbox: true }, {
                      field: 'username',
                      title: '用户名',
                      align: 'center',
                      width: '6%',
                      formatter: function (v, r, i) {
                        return '<a href="javascript:void(0);" data-title="商家详情-' + r.companyName + '" data-href="/Admin/member/memberDetail?id=' + r.id + '" data-id="' + r.id + '" class="js_iframeLink">' + r.username + '</a>'
                      }
                    }, {
                      field: 'email',
                      title: '邮箱',
                      align: 'center',
                      width: '8%',
                      formatter: function (v, r, i) {
                        var imgStr = '',
                          emailStr = r.email

                        if (emailStr == '') {
                          emailStr = '未填写'
                        }
                        if (r.emailBind == 1) {
                          imgStr = '<img src="success">'
                        }

                        return '<span>' + emailStr + '</span>' + imgStr
                      }
                    }, {
                      field: 'phone',
                      title: '手机',
                      align: 'center',
                      width: '8%',
                      formatter: function (v, r, i) {
                        var imgStr = ''
                        if (r.phoneBind == 1) {
                          imgStr = '<img src="success">'
                        }
                        return '<span>' + r.phone + '</span>' + imgStr
                      }
                    }, {
                      field: 'companyName',
                      title: '公司名称',
                      align: 'center',
                      width: '12%',
                      formatter: function (v, r, i) {
                        var imgStr = '',
                          companyNameStr = r.companyName

                        if (r.companyAuth == 1) {
                          imgStr = '<img src="success">'
                        }

                        if (companyNameStr == false) {
                          companyNameStr = '未填写'
                        }
                        return '<a href="javascript:void(0);" data-title="商家详情-' + companyNameStr + '-' + r.id + '" data-href="/Admin/member/memberDetail?id=' + r.id + '" data-id="' + r.id + '" class="js_iframeLink">' + companyNameStr + '</a>'
                      }
                    }, {
                      field: 'contact',
                      title: '指定联系人',
                      align: 'center',
                      width: '6%',
                      formatter: function (v, r, i) {
                        var imgStr = '',
                          contactStr = r.contact
                        if (r.companyState == 1) {
                          imgStr = '<img style="width: 18px;margin-left: 5px;" src="/Public/Admin/img/auth.png">'
                        }
                        if (contactStr == false) {
                          contactStr = '未填写'
                        }
                        return '<span>' + contactStr + '</span>' + imgStr
                      }
                    }, {
                      field: 'area',
                      title: '所在地区',
                      align: 'center',
                      width: '10%',
                      formatter: function (v, r, i) {
                        if (r.area == '') {
                          return '未填写'
                        }
                        return r.area
                      }
                    }, {
                      field: 'fullInfo',
                      title: '完善资料',
                      align: 'center',
                      width: '6%',
                      formatter: function (v, r, i) {
                        if (r.perfectInformation == 0) {
                          return '否'
                        }
                        return '是'
                      }
                    }, {
                      field: 'companyState',
                      title: '企业认证',
                      align: 'center',
                      width: '5%',
                      formatter: function (v, r, i) {
                        var str = ''
                        if (r.companyState == 1) {
                          str = '有效'
                        } else if (r.companyState === false) {
                          str = '未认证'
                        } else if (r.companyState == 0) {
                          str = '审核不通过'
                        } else if (r.companyState == 2) {
                          str = '待审核'
                        } else if (r.companyState == 3) {
                          str = '已撤销'
                        }
                        return '<span>' + str + '</span>'
                      }
                    }, {
                      field: 'addTime',
                      title: '注册时间',
                      align: 'center',
                      width: '10%',
                      formatter: function (v, r, i) {
                        return formatDate(r.addTime)
                      }
                    }, {
                      field: 'status',
                      title: '状态',
                      align: 'center',
                      width: '5%',
                      formatter: function (v, r, i) {
                        var str = '禁用'
                        if (r.status == 1) {
                          str = '正常'
                        }
                        return '<span>' + str + '</span>'
                      }
                    }, {
                      field: 'country',
                      title: '国家',
                      align: 'center',
                      width: '4%'
                    }, {
                      field: 'sourceTip',
                      title: '来源',
                      align: 'center',
                      width: '4%'
                    }, {
                      field: 'typeTip',
                      title: '类型',
                      align: 'center',
                      width: '4%'
                    },
          {
            field: 'operate',
            title: '操作',
            width: '8%',
            align: 'center',
            formatter: function (v, r, i) {
              var str = '取消禁用'
              var className = 'js_disable'
              if (r.status == 1) {
                str = '禁用'
                className = 'js_enable'
              }

              var deletClass = 'js_delet'
              var deletstr = '删除'

              return '<a href="javascript:void(0)"  data-id="' + r.id + '" class="operate-btn  ' + className + '">' + str + '</a> <a href="javascript:void(0)"  data-id="' + r.id + '" class="operate-btn  ' + deletClass + '">' + deletstr + '</a>'
            }
          }
        ]
      ],
      footer: '#footerBar',
      singleSelect: false
    }

    var config = $.extend(true, {}, dataGridConfig, pageGridConfig)
            // 渲染表格
    $('#dataGrid').datagrid(config)

    var memberOperate = function (button, gridId, status) {
      var dataGrid = null,
        title = status == 1 ? '启用' : '禁用'

      gridId ? dataGrid = $(gridId) : dataGrid = $('#dataGrid')

      var selectedArray = dataGrid.datagrid('getChecked'),
        i = 0,
        length = selectedArray.length,
        idArray = []

      if (length === 0) {
        $.messager.alert('提示', '请选择操作数据项', 'warning')
        return
      }

      $('#js_revokeForm').form('clear')

      $('#dlg').dialog({
        title: title,
        width: 400,
        height: 200,
        closed: false,
        cache: false,
        modal: true,
        buttons: [{
          text: title,
          iconCls: 'icon-ok',
          handler: function () {
            var isValid = $('#js_revokeForm').form('validate')
            if (!isValid) {
              return
            }

            for (; i < length; i++) {
              idArray.push(selectedArray[i].id)
            }

            ids = idArray.join(',')

            var postData = {
              url: '/Admin/member/memberOperate',
              data: {
                id: ids,
                status: status,
                reason: $('#js_revokeReason').val()
              }
            }
            ajax(postData, button).then(function (data) {
              $('#dlg').dialog('close')
              dataGrid.datagrid('uncheckAll')
              dataGrid.datagrid('reload')
            })
          }
        }, {
          text: '取消',
          iconCls: 'icon-cancel',
          handler: function () {
            $('#dlg').dialog('close')
          }
        }]

      })
    }

        // 批量启用
    $('.js_multiEnable').linkbutton({
      onClick: function () {
        memberOperate($(this), '#dataGrid', 1)
      }
    })
            // 批量禁用
    $('.js_multiDisable').linkbutton({
      onClick: function () {
        memberOperate($(this), '#dataGrid', 2)
      }
    })

        // 取消禁用
    $(document).on('click', '.js_disable', function () {
      var self = $(this),
        id = self.attr('data-id')

      operateOne(id, 1)
    })

        // 禁用
    $(document).on('click', '.js_enable', function () {
      var self = $(this),
        id = self.attr('data-id')

      operateOne(id, 2)
    })

        // 删除
    $(document).on('click', '.js_delet', function () {
      var self = $(this),
        id = self.attr('data-id')

      operateDelet(id)
    })

    function operateOne (id, status) {
      var title = status == 1 ? '取消禁用' : '禁用'
      $('#js_revokeForm').form('clear')

      $('#dlg').dialog({
        title: title,
        width: 400,
        height: 200,
        closed: false,
        cache: false,
        modal: true,
        buttons: [{
          text: title,
          iconCls: 'icon-ok',
          handler: function () {
            var isValid = $('#js_revokeForm').form('validate')
            if (!isValid) {
              return
            }
            var postData = {
              url: '/Admin/member/memberOperate',
              data: {
                id: id,
                status: status,
                reason: $('#js_revokeReason').val()
              }
            }
            ajax(postData).then(function (data) {
              $('#dlg').dialog('close')
              $('#dataGrid').datagrid('uncheckAll')
              $('#dataGrid').datagrid('reload')
            })
          }
        }, {
          text: '取消',
          iconCls: 'icon-cancel',
          handler: function () {
            $('#dlg').dialog('close')
          }
        }]

      })
    }

        // 删除
    function operateDelet (id) {
      var title = '删除'
      $('#js_revokeForm').form('clear')
      $('#dlg').dialog({
        title: title,
        width: 400,
        height: 200,
        closed: false,
        cache: false,
        modal: true,
        buttons: [{
          text: title,
          iconCls: 'icon-ok',
          handler: function () {
            var isValid = $('#js_revokeForm').form('validate')
            if (!isValid) {
              return
            }
            var postData = {
              url: '/Admin/member/memberOperateDel',
              data: {
                id: id,
                status: 0,
                reason: $('#js_revokeReason').val()
              }
            }
            ajax(postData).then(function (data) {
              $('#dlg').dialog('close')
              $('#dataGrid').datagrid('uncheckAll')
              $('#dataGrid').datagrid('reload')
            })
          }
        }, {
          text: '取消',
          iconCls: 'icon-cancel',
          handler: function () {
            $('#dlg').dialog('close')
          }
        }]

      })
    }
        // 批量导出
        // $(document).on('click', '#js_express', function(){
        //     $.messager.confirm('确认提示', '您确认要批量导出吗', function(r){
        //         if (r){
        //             window.location.href = '/Admin/Member/expMember';
        //         }
        //     });
        // });

        // 批量导出
    $(document).on('click', '#js_express', function () {
      var selectedArray = $('#dataGrid').datagrid('getChecked')
      var idArray = []

      if (selectedArray.length) {
        for (var i = 0, len = selectedArray.length; i < len; i++) {
          idArray.push(selectedArray[i].id)
        }
      }

      $.messager.confirm('确认提示', '您确认要批量导出吗', function (r) {
        var userName = $('#js_userName').textbox('getValue'),
          phone = $('#js_phone').textbox('getValue'),
          companyName = $('#js_company').textbox('getValue'),
          startDate = $('#js_bgTime').datebox('getValue'),
          endDate = $('#js_edTime').datebox('getValue'),
          companyState = $('#js_authSelect').combobox('getValue'),
          status = $('#js_statusSelect').combobox('getValue'),
          provinceId = $('#js_province').combobox('getValue'),
          cityId = $('#js_city').combobox('getValue'),
          districtId = $('#js_area').combobox('getValue'),
          source = $('#js_sourceSelect').combobox('getValue'),
          type = $('#js_typeSelect').combobox('getValue')
        if (r) {
          window.location.href = '/Admin/Member/expMember?id=' + idArray.join(',') + '&source=' + source + '&type=' + type + '&userName=' + userName + '&phone=' + phone + '&companyName=' + companyName + '&startDate=' + startDate + '&endDate=' + endDate + '&companyState=' + companyState + '&status=' + status + '&provinceId=' + provinceId + '&cityId=' + cityId + '&districtId=' + districtId
        }
      })
    })
        // 打开新窗口查看编辑或者预览
    $(document).on('click', '.js_iframeLink', function () {
      var tabTitle = $(this).attr('data-title')
      url = $(this).attr('data-href')
            // 获取父方法在本地静态打开时获取不到的，使用服务器环境或者使用firefox调试
      window.parent.addTab(tabTitle, url)
    })

        // 获取地区数据
    $('#js_getAreaData').on('click', function () {
            // 先获取省数据
      $(this).hide()
      $('.js_select_province').show()
    })

        // 省市联动
    ajax({ url: '/Home/Area/areas' }).then(function (rs) {
      $('#js_province').combobox('loadData', rs.data)
    })
    $('#js_province').combobox({
      valueField: 'id',
      textField: 'text',
      onSelect: function (param, b) {
        ajax({ url: '/Home/Area/areas', data: { id: param.id } }).then(function (rs) {
          $('.js_select_city').show()
          $('#js_city').combobox('loadData', rs.data)
        })
      }
    })
    $('#js_city').combobox({
      valueField: 'id',
      textField: 'text',
      onSelect: function (param, b) {
        ajax({ url: '/Home/Area/areas', data: { id: param.id } }).then(function (rs) {
          $('.js_select_area').show()
          $('#js_area').combobox('loadData', rs.data)
        })
      }
    })

    $('#js_area').combobox({
      valueField: 'id',
      textField: 'text'
    })

        // 搜索
    $('#js_userListSearch').on('click', function () {
      var userName = $('#js_userName').textbox('getValue'),
        phone = $('#js_phone').textbox('getValue'),
        companyName = $('#js_company').textbox('getValue'),
        startDate = $('#js_bgTime').datebox('getValue'),
        endDate = $('#js_edTime').datebox('getValue'),
        companyState = $('#js_authSelect').combobox('getValue'),
        status = $('#js_statusSelect').combobox('getValue'),
        provinceId = $('#js_province').combobox('getValue'),
        cityId = $('#js_city').combobox('getValue'),
        districtId = $('#js_area').combobox('getValue'),
        source = $('#js_sourceSelect').combobox('getValue'),
        type = $('#js_typeSelect').combobox('getValue')

      var queryParams = {
        username: userName,
        phone: phone,
        companyName: companyName,
        startDate: startDate,
        endDate: endDate,
        companyState: companyState,
        status: status,
        provinceId: provinceId,
        cityId: cityId,
        districtId: districtId,
        page: 1,
        source: source,
        type: type
      }
      $('#dataGrid').datagrid('load', queryParams)
    })

        // ;(function(){

        //     //渲染选择的函数
        //     //参数是点击的星星的索引
        //     function render(starIndex){
        //         $('星星的class').each(function(i,el){
        //             if(i<=starIndex){
        //                 el.style.backgroundUrl = '亮的星的url';
        //             }else{
        //                 el.style.backgroundUrl = '不亮的';
        //             }
        //         })
        //     }
        //     //点击星星 渲染
        //     $('星星的class').each(function(index,item){
        //         render(index);
        //     })
        // })();
  })
})(window.jQuery)
