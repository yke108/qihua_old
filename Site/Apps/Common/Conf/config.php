<?php
require_once('sysConfig.php');
$config = array(
    //'配置项'=>'配置值'
//    'DEFAULT_MODULE'    =>  'Index',//默认模块
    'URL_MODEL'            => '2',//URL模式
    'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' =>  array(
        'in170425' => 'mobile/exhibit/in170425',
    ),
    'SESSION_AUTO_START'   => true,//是否开启session
    'USER_CONFIG'          => array(
        'USER_AUTH' => true,
        'USER_TYPE' => 2,
    ),
    'MODULE_DENY_LIST'     => array('Common', 'Runtime', 'Api'),
    'MODULE_ALLOW_LIST'    => array('Home', 'Admin', 'Wechat', 'User', 'Crontab', 'Mobile'),
//    'DEFAULT_MODULE'       =>    'Home',
    'DEFAULT_AJAX_RETURN'  => 'JSON',
    'URL_CASE_INSENSITIVE' => false,
    'SHOW_PAGE_TRACE'      => false,    // 显示页面Trace信息
    'DB_PARAMS'            => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),
    'AUTH_CONFIG'          => array(
        'AUTH_ON'               => true, //认证开关
        'AUTH_TYPE'             => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP'            => $sysConfig['DB_PREFIX'] . 'auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS'     => $sysConfig['DB_PREFIX'] . 'group_access', //用户组明细表
        'AUTH_RULE'             => $sysConfig['DB_PREFIX'] . 'auth_rule', //权限规则表
        'AUTH_USER'             => $sysConfig['DB_PREFIX'] . 'auth_user',//用户表
        'AUTH_DEPARTMENT'       => $sysConfig['DB_PREFIX'] . 'auth_department',//部门表
        'AUTH_GROUP_DEPARTMENT' => $sysConfig['DB_PREFIX'] . 'group_department'//用户组部门关联表
    ),
    //超级管理员id,拥有全部权限,只要用户uid在这个角色组里的,就跳出认证.可以设置多个值,如array('1','2','3')
    'ADMINISTRATOR'        => array('1'),
    //不需要认证的规则
    'NO_AUTH_RULES'=>array(
        'Admin/Index/index',//后台首页
        'Admin/Index/logout',//退出
        'Admin/Auth/userList',//用户列表
        'Admin/Auth/groupList',//角色列表
        'Admin/Auth/ruleList',//规则列表
        'Admin/Auth/accessList',//权限列表
        'Admin/Auth/AuthUser',//权限控制
        'Admin/Auth/idGetGroupList',//根据id获取组分类
        'Admin/Store/getCategory',//获取分类
        'Admin/Store/getMasterType',//获取销售情况
        'Admin/Auth/checkEmail',//检测邮箱
        'Admin/Sell/getRevoke',
        'Admin/Auth/checkRule',//检测规则
        'Admin/Auth/checkGroup',//检测角色
        'Admin/Auth/checkUser',//检测用户
        'Admin/Member/memberList',//会员列表
        'Admin/Member/groupList',//会员组列表
        'Admin/Member/checkUser',//检测会员
        'Admin/Member/checkGroup',//检测会员组
        'Admin/Member/checkEmail',//检测邮箱
        'Admin/Member/MemberCompanyDetail',//后台企业认证操作历史
        'Admin/Sell/getGoodsHistories',//后台商城销售操作历史
        'Admin/BuyOffer/BuyOfferHistory',//后台求购操作历史
        'Admin/Supply/SupplyHistory',//后台供应详情操作历史
    ),

    //初始密码
    'REST_PASS'            => 'keywa123456',

    'FIND_GOODS_TYPE'      => array(
        '1' => 'Product inquiry',
        '2' => '求购配方',
        '3' => '求购专利',
        '4' => '其他求购',
        '5' => '求购技术'
    ),
    'FIND_GOODS_STATUS'    => array(
        '1' => 'Audit Pending',
        '2' => 'Effective',
        '3' => 'Expired',
        '4' => 'Revoke',
        '5' => 'Audit Disapproved',
    ),

    'MEMBER_COMPANY_INFO' => array(
      '0' => '审核不通过',
      '1' => '审核通过',
      '2' => '待审核',
      '3' => '认证被撤销',
      '4' => '未认证'
    ),

    'FIND_GOODS_OPERATION' => array(
        '1' => "审核通过",
        '2' => "审核不通过",
        '3' => "撤销通过",
        '4' => "恢复通过",
        '5' => "删除",
    ),

    //有效期
    'FIND_GOOD_EXPIRE'     => array(
        '3'  => '3天',
        '5'  => '5天',
        '7'  => '7天',
        '10' => '10天',
        '15' => '15天',
        '20' => '20天',
        '25' => '25天',
        '30' => '30天',
    ),

    //重量单位
    'WEIGHTUNIT'           => array(
        '1' => '吨',
        '2' => 'Kg',
        '3' => 'ml',
        '4' => 'L',
        '5' => 'm³',
        '6' => 'g',
        '7' => 'mg',
    ),

    //币种
    'CURRENCY'             => array(
        '1' => '人民币',
        '2' => '美元',
    ),
    //资源单类型
    'RESOURCE_TYPE'        => array(
        '0' => '审核不通过',
        '1' => '正常',
        '3' => '已过期',
        '2' => '待审核',
        '4' => '已撤销',
    ),

    //商品仓库
    'PRODUCT_DEPOT'        => array(
        'MASTER_TYPE'   => array(
            'ONLY_IN_PRODUCT'              => 1,//为商城在售
            'ONLY_IN_PURCHASE'             => 2,//为抢购在售
            'BATH_IN_PRODUCT_AND_PURCHASE' => 3,//为商城抢购都在售
            'NONE'                         => 0,//既不商城在售也不抢购在售
        ),
        'STATE'         => array(
            'REVIEWING' => 0,//待审核
            'ACTIVE'    => 1,//有效
            'REFUSE'    => 2,//审核不通过
            'REVOKE'    => 3,//已撤销
        ),
        'QUALITY_GRADE' => array(
            1 => '工业级',
            2 => '食品级',
            3 => '医药级',
            4 => '其它',
        ),
    ),

    //商城商品
    'PRODUCT'              => array(
        'STATE'          => array(
            'REFUSE'        => 0,//审核不通过
            'ACTIVE'        => 1,//在售
            'REVIEWING'     => 2,//待审核
            'REVOKE'        => 3,//下架
            'SELLER_REVOKE' => 4,//商家下架
            'ADMIN_REVOKE'  => 5,//工作人员下架
            'SYSTEM_REVOKE' => 6,//系统下架
        ),
        'STOCK'          => array(
            'OUT_OF_STOCK' => 1,
            'LOW_STOCK'    => 2,
        ),
        //支付方法
        'PAYMENT_METHOD' => array(
            'CONTRACT' => array(
                'value' => 1,
                'name'  => '合同约定',
            ),
            'POST_PAY' => array(
                'value' => 2,
                'name'  => '先货后款',
            ),
            'PREPAY'   => array(
                'value' => 3,
                'name'  => '先款后货',
            ),
        ),
        //有效期
        'EXPIRE'         => array(
            '1'  => '1天',
            '3'  => '3天',
            '5'  => '5天',
            '7'  => '7天',
            '10' => '10天',
            '15' => '15天',
            '20' => '20天',
            '30' => '30天',
        ),

    ),

    //公司信息
    'COMPANY'              => array(
        //公司认证
        'STATE'      => array(
            'REFUSE'    => 0,//审核不通过
            'ACTIVE'    => 1,//有效
            'REVIEWING' => 2,//待审核
            'REVOKE'    => 3,//已撤销
        ),
        //公司签约
        'SIGN_STATE' => array(
            'REFUSE'    => 0,//审核不通过
            'ACTIVE'    => 1,//审核通过
            'REVIEWING' => 2,//待审核
            'REVOKE'    => 3,//已撤销
        ),
    ),
    //默认列表记录条数
    'DEFAULT_PAGE_SIZE'    => 20,

    //自营用户ID
    'SELF_SUPPORT_UID'     => array(),

    //合作年度
    'COOPERATION_DATA'     => array(
        '0' => array('id' => 1, 'title' => '第一年度'),
        '1' => array('id' => 2, 'title' => '第二年度'),
        '2' => array('id' => 3, 'title' => '第三年度'),
        '3' => array('id' => 4, 'title' => '第四年度'),
        '4' => array('id' => 5, 'title' => '第五年度'),
        '5' => array('id' => 6, 'title' => '第六年度'),
        '6' => array('id' => 7, 'title' => '第七年度'),
        '7' => array('id' => 8, 'title' => '第八年度'),
        '8' => array('id' => 9, 'title' => '第九年度'),
    ),

    //订单状态
    'ORDER'                => array(
        'TYPE'  => array(
            'WAITING_BOTH_FOR_CONSULT'   => array('name' => '待协商', 'value' => 1),//待协商
            'WAITING_BUYER_FOR_PAY'      => array('name' => '待买方付款', 'value' => 2),//待买方付款
            'CANCELING'                  => array('name' => '申请取消中', 'value' => 3),//申请取消中
            'WAITING_SELLER_FOR_GATHER'  => array('name' => '待卖方收款', 'value' => 4),//待卖方收款
            'WAITING_SELLER_FOR_DELIVER' => array('name' => '待卖方发货', 'value' => 5),//待卖方发货
            'WAITING_BUYER_FOR_RECEIPT'  => array('name' => '待买方收货', 'value' => 6),//待买方收货
            'FINISH'                     => array('name' => '已完成', 'value' => 7),//已完成
            'CANCEL'                     => array('name' => '已取消', 'value' => 8),//已取消
        ),
        'STATE' => array(
            'WAITING_SELLER_FOR_EDIT' => array('name' => '待卖家修改订单', 'value' => 101),//待卖家修改订单（待协商）
            'SELLER_EDITED'           => array('name' => '待买方确认订单', 'value' => 102),//卖方已修改订单（待协商）
            'BUYER_APPLY_EDIT'        => array('name' => '买方要求继续协商', 'value' => 103),//买方要求继续协商（待协商）

            'WAITING_BUYER_FOR_PAY'  => array('name' => '订单已确认生效', 'value' => 201),//订单已确认生效（待买方付款）
            'PAYMENT_INFO_RETURN'    => array('name' => '付款信息被退回', 'value' => 202),//付款信息被退回（待买方付款）
            'BUYER_SURE_RECEIVE'     => array('name' => '买方已确认收货', 'value' => 203),//买方已确认收货（待买方付款）
            'SELLER_DISAGREE_CANCEL' => array('name' => '卖方不同意取消订单', 'value' => 204),//卖方不同意取消订单（待买方付款）
            'BUYER_DISAGREE_CANCEL'  => array('name' => '买方不同意取消订单', 'value' => 205),//买方不同意取消订单（待买方付款）

            'SELLER_REVIEW_CANCELING' => array('name' => '待卖方审核申请', 'value' => 301),//待卖方审核申请（申请取消中）
            'BUYER_REVIEW_CANCELING'  => array('name' => '待买方审核申请', 'value' => 302),//待买方审核申请（申请取消中）

            'BUYER_PAID' => array('name' => '买方已确认付款', 'value' => 401),//买方已确认付款（待卖方收款）

            'WAITING_SELLER_FOR_SHIP'          => array('name' => '订单已确认生效', 'value' => 501),//卖方已确认收款（待卖方发货）
            'SELLER_RECEIVE_PAID'              => array('name' => '卖方已确认收款', 'value' => 502),//卖方已确认收款（待卖方发货）
            'SHIP_INFO_RETURN'                 => array('name' => '发货信息被退回', 'value' => 503),//发货信息被退回（待卖方发货）
            'SELLER_DISAGREE_CANCEL_WHEN_SHIP' => array('name' => '卖方不同意取消订单', 'value' => 504),//卖方不同意取消订单（待卖方发货）
            'BUYER_DISAGREE_CANCEL_WHEN_SHIP'  => array('name' => '买方不同意取消订单', 'value' => 505),//买方不同意取消订单（待卖方发货）

            'SELLER_SHIPPED' => array('name' => '卖方已确认发货', 'value' => 601),//卖方已确认发货（待买方收货）

            'FINISH' => array('name' => '交易结束', 'value' => 701),//交易结束（已完成）

            'BUYER_CANCEL'  => array('name' => '买方取消', 'value' => 801),//买方取消（已取消）
            'SELLER_CANCEL' => array('name' => '卖方取消', 'value' => 802),//卖方取消（已取消）
        ),
    ),

    'FIND_GOODS_TYPE'      => array(
        '1' => 'Product demand',
        '2' => 'Formula demand',
        '3' => 'Patent demand',
        '4' => 'Technology demand'
    ),
    'FIND_GOODS_STATUS'    => array(
        '2' => 'Audit Pending',
        '1' => 'Effective',
        '3' => 'Expired',
        '4' => 'Revoke',
        '0' => 'Audit Disapproved',
    ),

    //有效期
    'FIND_GOOD_EXPIRE'     => array(
        '3'  => '3 days',
        '5'  => '5 days',
        '7'  => '7 days',
        '10' => '10 days',
        '15' => '15 days',
        '20' => '20 days',
        '25' => '25 days',
        '30' => '30 days',
    ),
    //中文站网址
    'CN_KEYWA_SITE' => 'http://test.keywa.com',
    //英文站网址
    'EN_KEYWA_SITE' => 'http://test.en.keywa.com',

    //用户状态绑定(通过位运算上计算, 如果有添加的, 按2的N次方的值添加)
    'STATUS_BIND' => array(
        'BIND_PHONE' => 1,  //绑定手机
        'BIND_EMAIL' => 2,     //绑定邮箱
    ),
);
return array_merge($config, $sysConfig);