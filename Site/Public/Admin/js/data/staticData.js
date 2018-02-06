if (!Array.isArray) {
    Array.isArray = function(vArg) {
        return Object.prototype.toString.call(vArg) === "[object Array]";
    };
}

if (typeof Array.prototype.forEach != "function") {
    Array.prototype.forEach = function(fn, scope) {
        var i, len;
        for (i = 0, len = this.length; i < len; ++i) {
            if (i in this) {
                fn.call(scope, this[i], i, this);
            }
        }
    };
}

if (typeof Array.prototype.map != "function") {
    Array.prototype.map = function(fn, context) {
        var arr = [];
        if (typeof fn === "function") {
            for (var k = 0, length = this.length; k < length; k++) {
                arr.push(fn.call(context, this[k], k, this));
            }
        }
        return arr;
    };
}

if (typeof Array.prototype.filter != "function") {
    Array.prototype.filter = function(fn, context) {
        var arr = [];
        if (typeof fn === "function") {
            for (var k = 0, length = this.length; k < length; k++) {
                fn.call(context, this[k], k, this) && arr.push(this[k]);
            }
        }
        return arr;
    };
}

if (typeof Array.prototype.some != "function") {
    Array.prototype.some = function(fn, context) {
        var passed = false;
        if (typeof fn === "function") {
            for (var k = 0, length = this.length; k < length; k++) {
                if (passed === true) break;
                passed = !!fn.call(context, this[k], k, this);
            }
        }
        return passed;
    };
}

if (typeof Array.prototype.every != "function") {
    Array.prototype.every = function(fn, context) {
        var passed = true;
        if (typeof fn === "function") {
            for (var k = 0, length = this.length; k < length; k++) {
                if (passed === false) break;
                passed = !!fn.call(context, this[k], k, this);
            }
        }
        return passed;
    };
}

if (typeof Array.prototype.indexOf != "function") {
    Array.prototype.indexOf = function(searchElement, fromIndex) {
        var index = -1;
        fromIndex = fromIndex * 1 || 0;

        for (var k = 0, length = this.length; k < length; k++) {
            if (k >= fromIndex && this[k] === searchElement) {
                index = k;
                break;
            }
        }
        return index;
    };
}

if (typeof Array.prototype.lastIndexOf != "function") {
    Array.prototype.lastIndexOf = function(searchElement, fromIndex) {
        var index = -1,
            length = this.length;
        fromIndex = fromIndex * 1 || length - 1;

        for (var k = length - 1; k > -1; k -= 1) {
            if (k <= fromIndex && this[k] === searchElement) {
                index = k;
                break;
            }
        }
        return index;
    };
}

if (typeof Array.prototype.reduce != "function") {
    Array.prototype.reduce = function(callback, initialValue) {
        var previous = initialValue,
            k = 0,
            length = this.length;
        if (typeof initialValue === "undefined") {
            previous = this[0];
            k = 1;
        }

        if (typeof callback === "function") {
            for (k; k < length; k++) {
                this.hasOwnProperty(k) && (previous = callback(previous, this[k], k, this));
            }
        }
        return previous;
    };
}

if (typeof Array.prototype.reduceRight != "function") {
    Array.prototype.reduceRight = function(callback, initialValue) {
        var length = this.length,
            k = length - 1,
            previous = initialValue;
        if (typeof initialValue === "undefined") {
            previous = this[length - 1];
            k--;
        }
        if (typeof callback === "function") {
            for (k; k > -1; k -= 1) {
                this.hasOwnProperty(k) && (previous = callback(previous, this[k], k, this));
            }
        }
        return previous;
    };
}


var dataGridConfig = {
    url: '',
    title: '',
    checkbox: true,
    height: 'auto',
    rownumbers: true, //行号
    fitColumns: true,
    nowrap: false, //如果为true，则在同一行中显示数据。设置为true可以提高加载性能。
    iconCls: "icon-add", //图标
    collapsible: true, //隐藏按钮
    loadMsg: '数据加载中......',
    toolbar: '#toolbar',
    pageSize: 20,
    rownumbers: true,
    pagination: true,
    singleSelect: true,
    checkOnSelect: false,
    selectOnCheck: false,
    onSelect: function() {
        $(this).datagrid('unselectAll');
    }
}
var menuData = [
    //商家管理
    {
        "id": "Admin/Member",
        "icon": "icon-sys",
        "name": "商家管理",
        "sub": [{
            "id": "Admin/Member/index",
            "name": "商家列表",
            "icon": "icon-add",
            "url": "Admin/Member/index"
        },{
           "id": "Admin/Member/companyAuth",
           "name": "企业认证列表",
           "icon": "icon-add",
           "url": "Admin/Member/companyAuth"
        }
            // {
            //    "id": "Admin/Member/memberSign",
            //    "name": "签约管理",
            //    "icon": "icon-add",
            //    "url": "Admin/Member/memberSign"
            //}
        ]
    }, //商品仓库
    //{
    //    "id": "Admin/store",
    //    "icon": "icon-sys",
    //    "name": "商品仓库",
    //    "sub": [{
    //        "id": "Admin/store/valid",
    //        "name": "有效的商品",
    //        "icon": "icon-add",
    //        "url": "Admin/store/valid"
    //    }, {
    //        "id": "Admin/store/pending",
    //        "name": "待审的商品",
    //        "icon": "icon-add",
    //        "url": "Admin/store/pending"
    //    }, {
    //        "id": "Admin/store/fail",
    //        "name": "审核不通过的商品",
    //        "icon": "icon-add",
    //        "url": "Admin/store/fail"
    //    }, {
    //        "id": "Admin/store/quash",
    //        "name": "已撤销的商品",
    //        "icon": "icon-add",
    //        "url": "Admin/store/quash"
    //    }]
    //}, //商城销售管理
    {
        "id": "Admin/Sell",
        "icon": "icon-sys",
        "name": "商城销售管理",
        "sub": [{
            "id": "Admin/Sell/valid",
            "name": "有效的销售商品",
            "icon": "icon-add",
            "url": "Admin/Sell/valid"
        }, {
            "id": "Admin/Sell/pending",
            "name": "待审的销售商品",
            "icon": "icon-add",
            "url": "Admin/Sell/pending"
        }, {
            "id": "Admin/Sell/fail",
            "name": "审核不通过的销售商品",
            "icon": "icon-add",
            "url": "Admin/Sell/fail"
        }, {
            "id": "Admin/Sell/soldout",
            "name": "已下架的销售商品",
            "icon": "icon-add",
            "url": "Admin/Sell/soldout"
        }]
    },  //限时抢购管理
    /* {
     "id": "Admin/hot",
     "icon": "icon-sys",
     "name": "限时抢购管理",
     "sub": [{
     "id": "Admin/hot/valid.html",
     "name": "有效的抢购活动",
     "icon": "icon-add",
     "url": "Admin/hot/valid.html"
     }, {
     "id": "Admin/hot/pending.html",
     "name": "待审的抢购活动",
     "icon": "icon-add",
     "url": "Admin/hot/pending.html"
     }, {
     "id": "Admin/hot/fail.html",
     "name": "审核不通过的抢购活动",
     "icon": "icon-add",
     "url": "Admin/hot/fail.html"
     }, {
     "id": "Admin/hot/soldout.html",
     "name": "已下架的抢购活动",
     "icon": "icon-add",
     "url": "Admin/hot/soldout.html"
     },{
     "id": "Admin/hot/hot-index.html",
     "name": "抢购推荐",
     "icon": "icon-add",
     "url": "Admin/hot/hot-index.html"
     }]
     }, //资源单管理
     {
     "id": "Admin/Resource",
     "icon": "icon-sys",
     "name": "资源单管理",
     "sub": [{
     "id": "Admin/Resource/pends",
     "name": "待审核的资源单",
     "icon": "icon-add",
     "url": "Admin/Resource/pends"
     }, {
     "id": "Admin/Resource/valided",
     "name": "有效的资源单",
     "icon": "icon-add",
     "url": "Admin/Resource/valided"
     }, {
     "id": "Admin/Resource/fails",
     "name": "审核不通过的资源单",
     "icon": "icon-add",
     "url": "Admin/Resource/fails"
     }, {
     "id": "Admin/Resource/quashs",
     "name": "已撤消的资源单",
     "icon": "icon-add",
     "url": "Admin/Resource/quashs"
     }, {
     "id": "Admin/Resource/overdues",
     "name": "已过期的资源单",
     "icon": "icon-add",
     "url": "Admin/Resource/overdues"
     }]
     }, */{
        "id": "Admin/BuyOffer",
        "icon": "icon-sys",
        "name": "求购管理",
        "url": "Admin/BuyOffer/lists",
        "sub": []
    }, {
        "id": "Admin/Supply",
        "icon": "icon-sys",
        "name": "供应管理",
        "url": "Admin/Supply/lists",
        "sub": []
    },//订单管理
    /*{
     "id": "Admin/Order",
     "icon": "icon-sys",
     "name": "订单管理",
     "sub": [{
     "id": "Admin/Order/child.html",
     "name": "子订单列表",
     "icon": "icon-add",
     "url": "Admin/Order/child.html"
     }]
     },
     */
    {
        "id": "Admin/Content",
        "icon": "icon-sys",
        "name": "内容管理",
        "sub": [{
            "id": "Admin/Content/aboutUs",
            "name": "关于我们",
            "icon": "icon-add",
            "url": "Admin/Content/aboutUs"
        },/* {
         "id": "Admin/Content/help.html",
         "name": "帮助中心",
         "icon": "icon-add",
         "url": "Admin/Content/help.html"
         }, */{
            "id": "Admin/Content/protocol",
            "name": "用户服务协议",
            "icon": "icon-add",
            "url": "Admin/Content/protocol"
        }, /*{
         "id": "Admin/Content/logo.html",
         "name": "网站Logo设置",
         "icon": "icon-add",
         "url": "Admin/Content/logo.html"
         },*/ {
            "id": "Admin/Content/partner",
            "name": "合作伙伴",
            "icon": "icon-add",
            "url": "Admin/Content/partner"
        }]
    }, //系统管理
    {
        "id": "Admin/Auth",
        "icon": "icon-sys",
        "name": "系统管理",
        "sub": [{
            "id": "Admin/Auth/index",
            "name": "用户列表",
            "icon": "icon-add",
            "url": "Admin/Auth/index"
        }, {
            "id": "Admin/Auth/department",
            "name": "部门列表",
            "icon": "icon-add",
            "url": "Admin/Auth/department"
        }, {
            "id": "Admin/Auth/group",
            "name": "角色列表",
            "icon": "icon-add",
            "url": "Admin/Auth/group"
        }, {
            "id": "Admin/Auth/rule",
            "name": "权限列表",
            "icon": "icon-add",
            "url": "Admin/Auth/rule"
        }]
    },
    {
        "id": "Admin/Data",
        "icon": "icon-sys",
        "name": "数据管理",
        "sub": [{
            "id": "Admin/Data/area",
            "name": "地区管理",
            "icon": "icon-add",
            "url": "Admin/Data/area"
        }, {
            "id": "Admin/Data/producer",
            "name": "生产商",
            "icon": "icon-add",
            "url": "Admin/Data/producer"
        }, {
            "id": "Admin/Data/brand",
            "name": "品牌",
            "icon": "icon-add",
            "url": "Admin/Data/brand"
        }, {
            "id": "Admin/Data/category",
            "name": "商品类别",
            "icon": "icon-add",
            "url": "Admin/Data/category"
        }, {
            "id": "Admin/Data/trade",
            "name": "所在行业",
            "icon": "icon-add",
            "url": "Admin/Data/trade"
        }, {
            "id": "Admin/Data/property",
            "name": "单位性质",
            "icon": "icon-add",
            "url": "Admin/Data/property"
        }, {
            "id": "Admin/Data/model",
            "name": "经营模式",
            "icon": "icon-add",
            "url": "Admin/Data/model"
        }, {
            "id": "Admin/Data/turnover",
            "name": "年营业额",
            "icon": "icon-add",
            "url": "Admin/Data/turnover"
        }, {
            "id": "Admin/Data/employees",
            "name": "单位人数",
            "icon": "icon-add",
            "url": "Admin/Data/employees"
        }, {
            "id": "Admin/Data/wxappsecret",
            "name": "微信密码",
            "icon": "icon-add",
            "url": "Admin/Data/wxappsecret"
        },{
            "id": "Admin/Data/phone",
            "name": "手机白名单",
            "icon": "icon-add",
            "url": "Admin/Data/phone"
        },{
            "id": "Admin/Data/indicator",
            "name": "关键指标",
            "icon": "icon-add",
            "url": "Admin/Data/indicator"
        }]
    }
];


/**
 * 统一处理jQuery ajax
 * @param  {object} data   ajax提交的数据
 * @param  {jQuery object} button 点击提交的按钮
 * @return {jQuery promise}
 */
var ajax = function(data, button) {
    if (button) {
        //禁止多次点击提交
        button.prop("disabled", true);
        //easyui
        button.linkbutton('disable');
    }
    var opt = {
        url: '',
        type: 'POST',
        dataType: 'json'
    }
    var opts = $.extend({}, opt, data);
    return $.ajax(opts).then(function(json) {
        if (json.code != 200) {
            if(json.msg) {
                $.messager.alert('提示', json.msg, 'warning');
            }
            return $.Deferred().reject(json).promise();
        } else {
            return $.Deferred().resolve(json).promise();
        }
    }, function() {
        console.log("服务器错误，请稍后再试");
        $.Deferred().reject().promise();
    })
        .always(function() {
            if (button) {
                button.prop("disabled", false);
                button.linkbutton('enable');
            }
        });
}

/**
 * 统一处理dataGrid表单多选操作
 * @param  {string} url
 * @param  {jQuery object} button 点击提交的按钮
 * @param  {string} gridId
 */
var postDataGridMulti = function(url, button, gridId, title) {
    var dataGrid = null,
        tipsTitle = '您确认想要删除记录吗？';

    if (title) {
        tipsTitle = title;
    }

    gridId ? dataGrid = $(gridId) : dataGrid = $('#dataGrid');

    var selectedArray = dataGrid.datagrid('getChecked'),
        i = 0,
        length = selectedArray.length,
        idArray = []

    if (length === 0) {
        $.messager.alert('提示', '请选择操作数据项', 'warning');
        return;
    }

    $.messager.confirm('确认', tipsTitle, function(r) {
        if (r) {
            for (; i < length; i++) {
                idArray.push(selectedArray[i].id);
            }

            ids = idArray.join(',');

            var postData = {
                url: url,
                data: {
                    id: ids
                }
            }
            ajax(postData, button).then(function(data) {
                dataGrid.datagrid('uncheckAll');
                dataGrid.datagrid('reload');
            }, function(data){
                //$.messager.alert('提示', data.msg, 'warning');
            })
        }
    });
}

/**
 * 获取url参数
 * @return {object} url参数对象
 */
var getUrlParam = function() {
    var result = {},
        key = '',
        value = '',
        str = '',
        arr = [],
        i = 0,
        len = 0;

    str = location.search.substr(1);
    arr = str.split("&");
    len = arr.length;

    for (; i < len; i++) {
        key = arr[i].split('=')[0];
        result[key] = encodeURIComponent(arr[i].split('=')[1])
    }
    return result;
}

var imgUploadPrev = function(file, callback) {
    if (!file) return;
    var file = file[0].files[0];
    if (!/image\/\w+/.test(file.type)) {
        alert("文件必须为图片！");
        return false;
    }
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(e) {
        callback && callback(e);
    }
}

/**
 * [kindEditor description]
 */
var kindEditor = function(dom, width, height) {
    var w = width || '100%',
        h = height || '300'

    return KindEditor.create('#' + dom, {
        width: w,
        height: h,
        resizeType: 0,
        allowImageUpload: true,
        allowImageRemote: false,
        showRemote: false,
        uploadJson: '/Admin/Content/upload',
        allowFileManager: true,
        beforeUpload: function(request) {

        },
        afterUpload: function(data) {
            console.log(data);
        },
        items: [
            'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
            'insertunorderedlist', '|', 'image', 'link'
        ]
    });
}


/**
 * 根据后台给出的时间戳返回格式的日期字符串
 * @param  {string} 时间戳字符串
 * @return {string} 格式的日期字符串
 */
var formatDate = function(dateTime) {
    var now = new Date(parseInt(dateTime) * 1000);
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var date = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    return year + "-" + month + "-" + date+' '+hour+':'+minute+':'+second;

}

var formatDate2 = function(dateTime) {
    var now = new Date(parseInt(dateTime) * 1000);
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var date = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    return year + "-" + month + "-" + date;
}
