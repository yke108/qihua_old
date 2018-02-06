/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50540
Source Host           : 127.0.0.1:3306
Source Database       : keywa_en_develop

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2018-02-06 10:09:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for kw_area
-- ----------------------------
DROP TABLE IF EXISTS `kw_area`;
CREATE TABLE `kw_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` smallint(5) NOT NULL,
  `createTime` int(11) NOT NULL,
  `text` varchar(60) NOT NULL,
  `depth` tinyint(3) unsigned NOT NULL,
  `path` varchar(20) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_area
-- ----------------------------
INSERT INTO `kw_area` VALUES ('1', '0', '1471570031', '名称', '1', '1', '0');
INSERT INTO `kw_area` VALUES ('2', '0', '1471570181', '二级部门A1', '1', '1', '0');
INSERT INTO `kw_area` VALUES ('3', '0', '1471572744', 'ceshi', '1', '1', '0');
INSERT INTO `kw_area` VALUES ('4', '0', '1471572756', '我是测试66', '1', '1', '0');
INSERT INTO `kw_area` VALUES ('5', '1', '1471573197', '555', '2', '1,5', '0');
INSERT INTO `kw_area` VALUES ('6', '3', '1471573230', 'c-99', '2', '1,6', '0');
INSERT INTO `kw_area` VALUES ('7', '1', '1471573457', '7777', '2', '1,7', '0');
INSERT INTO `kw_area` VALUES ('8', '7', '1471573500', '8888', '3', '1,7,8', '0');
INSERT INTO `kw_area` VALUES ('9', '0', '1471573984', '4449', '1', '1', '1');
INSERT INTO `kw_area` VALUES ('10', '9', '1471573989', '555', '2', '1,10', '0');
INSERT INTO `kw_area` VALUES ('11', '0', '1471652821', '湖南省', '1', '1', '1');
INSERT INTO `kw_area` VALUES ('12', '11', '1471652846', '株洲市', '2', '1,12', '1');
INSERT INTO `kw_area` VALUES ('13', '9', '1471678187', '111', '2', '1,13', '1');
INSERT INTO `kw_area` VALUES ('14', '0', '1471678495', '1', '1', '1', '1');
INSERT INTO `kw_area` VALUES ('15', '14', '1471678501', '2', '2', '1,15', '0');
INSERT INTO `kw_area` VALUES ('16', '0', '1471679127', '浪奇化工', '1', '1', '1');
INSERT INTO `kw_area` VALUES ('17', '0', '1471679223', '湖南省', '1', '1', '1');
INSERT INTO `kw_area` VALUES ('18', '17', '1471679236', '浪奇', '2', '1,18', '0');

-- ----------------------------
-- Table structure for kw_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `kw_auth_group`;
CREATE TABLE `kw_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为正常；0为删除;2为禁止',
  `rules` text NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `aid` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '创建人id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_auth_group
-- ----------------------------
INSERT INTO `kw_auth_group` VALUES ('1', '超级管理员', '1', '', '1470641930', '1');
INSERT INTO `kw_auth_group` VALUES ('8', '前端', '0', '3,20,21,23,24,25,27,28,29,30,31,32,33,35,36,37,38,2,22,26,34', '1471331622', '1');
INSERT INTO `kw_auth_group` VALUES ('9', 'PHP', '0', '3,20,21,23,24,25,27,28,29,30,31,32,33,35,36,37,38,2,22,26,34', '1471335977', '1');
INSERT INTO `kw_auth_group` VALUES ('11', '333', '0', '3,23,2,22', '1471515230', '1');
INSERT INTO `kw_auth_group` VALUES ('12', '咳咳咳', '0', '3,31,2,26', '1471516627', '1');
INSERT INTO `kw_auth_group` VALUES ('13', '444', '0', '35,38,34', '1471516937', '1');
INSERT INTO `kw_auth_group` VALUES ('14', '33', '0', '23,27,29,32,22,26', '1471516945', '1');
INSERT INTO `kw_auth_group` VALUES ('15', '测试看看262', '0', '3,23,27,28,31,35,38,2,22,26,34', '1471517342', '1');
INSERT INTO `kw_auth_group` VALUES ('16', '测试', '0', '3,20,21,23,24,25,27,28,29,30,31,32,33,35,36,37,38,2,22,26,34', '1474456597', '1');
INSERT INTO `kw_auth_group` VALUES ('17', '1111', '0', '3,23,27,28,35,2,22,26,34', '1474456892', '1');
INSERT INTO `kw_auth_group` VALUES ('18', '2222222222', '0', '3,23,28,35,36,2,22,26,34', '1474456908', '1');
INSERT INTO `kw_auth_group` VALUES ('19', '客服', '0', '3,20,21,23,24,25,27,28,29,30,31,32,33,35,36,37,38,2,22,26,34', '1474888368', '1');
INSERT INTO `kw_auth_group` VALUES ('20', '客服人员', '1', '49,53,54,59,63,197,50,56,60,198,51,52,57,58,61,62,199,66,67,68,69,70,71,72,73,200,201,202,203,76,77,78,79,80,81,82,83,204,205,206,207,86,87,88,89,90,91,208,209,210,211,212,93,94,95,96,97,98,99,100,103,104,105,213,108,218,112,116,117,118,119,120,121,122,123,124,125,126,131,133,134,220,234,130,132,219,129,127,128,221,137,138,139,140,152,153,154,155,143,144,145,146,148,149,150,151,157,158,159,160,162,163,164,165,167,168,169,170,175,176,177,178,180,181,182,183,226,227,228,223,224,244,246,247,248,46,47,48,65,75,85,102,107,110,111,114,115,136,141,142,147,156,161,166,174,179,194,195,222,225,243', '1476688497', '1');
INSERT INTO `kw_auth_group` VALUES ('21', '运营人员', '1', '49,53,59,63,197,50,56,60,198,51,52,57,58,61,62,199,66,67,68,69,70,71,72,73,200,201,202,203,76,77,78,79,80,81,82,83,204,205,206,207,86,87,88,89,90,91,208,209,210,211,212,93,94,95,96,97,98,99,100,103,104,105,213,108,218,112,116,117,118,119,120,121,122,123,124,125,126,131,133,134,220,234,130,132,219,129,127,128,221,137,138,139,140,152,153,154,155,143,144,145,146,148,149,150,151,157,158,159,160,162,163,164,165,167,168,169,170,175,176,177,178,180,181,182,183,226,227,228,223,224,244,245,246,247,248,46,47,48,65,75,85,102,107,110,111,114,115,136,141,142,147,156,161,166,174,179,194,195,222,225,243', '1477536027', '1');
INSERT INTO `kw_auth_group` VALUES ('22', '架构师', '0', '3,20,21,23,24,25,27,28,29,30,31,32,33,35,36,37,38,2,22,26,34', '1479361384', '1');
INSERT INTO `kw_auth_group` VALUES ('23', '用户1', '0', '49,53,54,59,63,197,50,56,60,198,51,52,57,58,61,62,199,66,67,68,69,70,71,72,73,200,201,202,203,76,77,78,79,80,81,82,83,204,205,206,207,86,87,88,89,90,91,208,209,210,211,212,93,94,95,96,97,98,99,100,103,104,105,213,108,218,112,116,117,118,119,120,121,122,123,124,125,126,131,133,134,130,132,219,129,127,128,221,137,138,139,140,152,153,154,155,143,144,145,146,148,149,150,151,157,158,159,160,162,163,164,165,167,168,169,170,175,176,177,178,180,181,182,183,223,224,46,47,48,65,75,85,102,107,110,111,114,115,136,141,142,147,156,161,166,174,179,194,195,222', '1479713499', '1');
INSERT INTO `kw_auth_group` VALUES ('24', '程序猿', '1', '3,20,21,196,230,231,232,23,24,25,27,28,29,30,31,32,33,233,35,36,37,38,49,53,54,59,63,197,50,56,60,198,51,52,57,58,61,62,199,66,67,68,69,70,71,72,73,200,201,202,203,76,77,78,79,80,81,82,83,204,205,206,207,86,87,88,89,90,91,208,209,210,211,212,93,94,95,96,97,98,99,100,103,104,105,213,108,218,112,116,117,118,119,120,121,122,123,124,125,126,131,133,134,220,234,130,132,219,129,127,128,221,137,138,139,140,152,153,154,155,143,144,145,146,148,149,150,151,157,158,159,160,162,163,164,165,167,168,169,170,175,176,177,178,180,181,182,183,185,186,187,188,226,227,228,250,251,252,223,224,244,245,246,247,248,2,22,26,34,46,47,48,65,75,85,102,107,110,111,114,115,136,141,142,147,156,161,166,174,179,184,194,195,222,225,243,249', '1481247686', '1');
INSERT INTO `kw_auth_group` VALUES ('25', '测试角色', '1', '196,27,28,35,53,63,197,56,198,57,199,66,200,201,202,203,76,77,204,205,206,207,86,208,209,210,211,93,94,95,96,97,104,105,213,108,218,117,121,123,125,131,134,220,219,129,127,221,137,152,143,148,157,162,167,175,180,188,226,224,237,238,239,240,2,22,26,34,46,47,48,65,75,85,102,107,110,111,114,115,136,141,142,147,156,161,166,174,179,184,194,222,225,236', '1481523514', '1');
INSERT INTO `kw_auth_group` VALUES ('26', '1', '0', '3,2', '1481768987', '1');
INSERT INTO `kw_auth_group` VALUES ('27', '2', '0', '3,2', '1481768994', '1');

-- ----------------------------
-- Table structure for kw_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `kw_auth_rule`;
CREATE TABLE `kw_auth_rule` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `title` varchar(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常；0删除；2禁用',
  `condition` varchar(100) NOT NULL DEFAULT '',
  `parentid` smallint(4) NOT NULL DEFAULT '0',
  `parentidlist` varchar(32) NOT NULL DEFAULT '0' COMMENT '分类的层级关系，从最高级到自己',
  `depth` smallint(4) unsigned NOT NULL DEFAULT '1' COMMENT '层级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_auth_rule
-- ----------------------------
INSERT INTO `kw_auth_rule` VALUES ('1', 'Admin/Auth', '系统管理', '1', '1', '', '0', '1', '1');
INSERT INTO `kw_auth_rule` VALUES ('2', 'Admin/Auth/index', '用户列表', '1', '1', '', '1', '1,2', '2');
INSERT INTO `kw_auth_rule` VALUES ('3', 'Admin/Auth/userAdd', '新增', '1', '1', '', '2', '1,2,3', '3');
INSERT INTO `kw_auth_rule` VALUES ('20', 'Admin/Auth/userSave', '修改', '1', '1', '', '2', '1,2,20', '3');
INSERT INTO `kw_auth_rule` VALUES ('21', 'Admin/Auth/userDel', '删除', '1', '1', '', '2', '1,2,21', '3');
INSERT INTO `kw_auth_rule` VALUES ('22', 'Admin/Auth/department', '部门列表', '1', '1', '', '1', '1,22', '2');
INSERT INTO `kw_auth_rule` VALUES ('23', 'Admin/Auth/departmentAdd', '新增', '1', '1', '', '22', '1,22,23', '3');
INSERT INTO `kw_auth_rule` VALUES ('24', 'Admin/Auth/departmentSave', '修改', '1', '1', '', '22', '1,22,24', '3');
INSERT INTO `kw_auth_rule` VALUES ('25', 'Admin/Auth/departmentDel', '删除', '1', '1', '', '22', '1,22,25', '3');
INSERT INTO `kw_auth_rule` VALUES ('26', 'Admin/Auth/group', '角色列表', '1', '1', '', '1', '1,26', '2');
INSERT INTO `kw_auth_rule` VALUES ('27', 'Admin/Auth/departmentList', '查看', '1', '1', '', '22', '1,22,27', '3');
INSERT INTO `kw_auth_rule` VALUES ('28', 'Admin/Auth/groupList', '查看', '1', '1', '', '26', '1,26,28', '3');
INSERT INTO `kw_auth_rule` VALUES ('29', 'Admin/Auth/groupAdd', '新增', '1', '1', '', '26', '1,26,29', '3');
INSERT INTO `kw_auth_rule` VALUES ('30', 'Admin/Auth/groupSave', '修改', '1', '1', '', '26', '1,26,30', '3');
INSERT INTO `kw_auth_rule` VALUES ('31', 'Admin/Auth/groupDel', '删除', '1', '1', '', '26', '1,26,31', '3');
INSERT INTO `kw_auth_rule` VALUES ('32', 'Admin/Auth/groupBatchActive', '批量启用', '1', '1', '', '26', '1,26,32', '3');
INSERT INTO `kw_auth_rule` VALUES ('33', 'Admin/Auth/groupBatchInactive', '批量禁用', '1', '1', '', '26', '1,26,33', '3');
INSERT INTO `kw_auth_rule` VALUES ('34', 'Admin/Auth/rule', '权限列表', '1', '1', '', '1', '1,34', '2');
INSERT INTO `kw_auth_rule` VALUES ('35', 'Admin/Auth/ruleList', '查看', '1', '1', '', '34', '1,34,35', '3');
INSERT INTO `kw_auth_rule` VALUES ('36', 'Admin/Auth/ruleAdd', '新增', '1', '1', '', '34', '1,34,36', '3');
INSERT INTO `kw_auth_rule` VALUES ('37', 'Admin/Auth/ruleSave', '修改', '1', '1', '', '34', '1,34,37', '3');
INSERT INTO `kw_auth_rule` VALUES ('38', 'Admin/Auth/ruleDel', '删除', '1', '1', '', '34', '1,34,38', '3');
INSERT INTO `kw_auth_rule` VALUES ('45', 'Admin/Member', '商家管理', '1', '1', '', '0', ',45', '1');
INSERT INTO `kw_auth_rule` VALUES ('46', 'Admin/Member/index', '商家列表', '1', '1', '', '45', ',45,46', '2');
INSERT INTO `kw_auth_rule` VALUES ('47', 'Admin/Member/companyAuth', '企业认证列表', '1', '1', '', '45', ',45,47', '2');
INSERT INTO `kw_auth_rule` VALUES ('48', 'Admin/Member/memberSign', '签约管理', '1', '1', '', '45', ',45,48', '2');
INSERT INTO `kw_auth_rule` VALUES ('49', 'Admin/Member/memberOperate', '禁用/取消禁用', '1', '1', '', '46', ',45,46,49', '3');
INSERT INTO `kw_auth_rule` VALUES ('50', 'Admin/Member/companyVerify', '审核', '1', '1', '', '47', ',45,47,50', '3');
INSERT INTO `kw_auth_rule` VALUES ('51', 'Admin/Member/signAdd', '录入签约', '1', '1', '', '48', ',45,48,51', '3');
INSERT INTO `kw_auth_rule` VALUES ('52', 'Admin/Member/signSave', '修改签约', '1', '1', '', '48', ',45,48,52', '3');
INSERT INTO `kw_auth_rule` VALUES ('53', 'Admin/Member/memberDetail', '商家详情', '1', '1', '', '46', ',45,46,53', '3');
INSERT INTO `kw_auth_rule` VALUES ('54', 'Admin/Member/restPass', '重置密码', '1', '1', '', '46', ',45,46,54', '3');
INSERT INTO `kw_auth_rule` VALUES ('56', 'Admin/Member/companyAuthList', '认证列表', '1', '1', '', '47', ',45,47,56', '3');
INSERT INTO `kw_auth_rule` VALUES ('57', 'Admin/Member/memberSignList', '签约列表', '1', '1', '', '48', ',45,48,57', '3');
INSERT INTO `kw_auth_rule` VALUES ('58', 'Admin/Member/signVerify', '审核操作', '1', '1', '', '48', ',45,48,58', '3');
INSERT INTO `kw_auth_rule` VALUES ('59', 'Admin/Member/expMember', '数据导出', '1', '1', '', '46', ',45,46,59', '3');
INSERT INTO `kw_auth_rule` VALUES ('60', 'Admin/Member/expAuth', '认证数据导出', '1', '1', '', '47', ',45,47,60', '3');
INSERT INTO `kw_auth_rule` VALUES ('61', 'Admin/Member/expSign', '签约数据导出', '1', '1', '', '48', ',45,48,61', '3');
INSERT INTO `kw_auth_rule` VALUES ('62', 'Admin/Member/expSignAuth', '审核数据导出', '1', '1', '', '48', ',45,48,62', '3');
INSERT INTO `kw_auth_rule` VALUES ('63', 'Admin/Member/memberList', '列表', '1', '1', '', '46', ',45,46,63', '3');
INSERT INTO `kw_auth_rule` VALUES ('64', 'Admin/store', '商品仓库', '1', '1', '', '0', ',64', '1');
INSERT INTO `kw_auth_rule` VALUES ('65', 'Admin/Store/productDepotList', '列表', '1', '1', '', '64', ',64,65', '2');
INSERT INTO `kw_auth_rule` VALUES ('66', 'Admin/Store/goodsDetails', '详情', '1', '1', '', '65', ',64,65,66', '3');
INSERT INTO `kw_auth_rule` VALUES ('67', 'Admin/Store/changeStatus', '撤销通过', '1', '1', '', '65', ',64,65,67', '3');
INSERT INTO `kw_auth_rule` VALUES ('68', 'Admin/Store/examStatus', '审核通过', '1', '1', '', '65', ',64,65,68', '3');
INSERT INTO `kw_auth_rule` VALUES ('69', 'Admin/Store/failStatus', '审核不通过', '1', '1', '', '65', ',64,65,69', '3');
INSERT INTO `kw_auth_rule` VALUES ('70', 'Admin/Store/renewStatus', '恢复通过', '1', '1', '', '65', ',64,65,70', '3');
INSERT INTO `kw_auth_rule` VALUES ('71', 'Admin/Store/rStatus', '重审通过', '1', '1', '', '65', ',64,65,71', '3');
INSERT INTO `kw_auth_rule` VALUES ('72', 'Admin/Store/del', '批量删除', '1', '1', '', '65', ',64,65,72', '3');
INSERT INTO `kw_auth_rule` VALUES ('73', 'Admin/Store/expStore', '数据导出', '1', '1', '', '65', ',64,65,73', '3');
INSERT INTO `kw_auth_rule` VALUES ('74', 'Admin/Sell', '商城销售管理', '1', '1', '', '0', ',74', '1');
INSERT INTO `kw_auth_rule` VALUES ('75', 'Admin/Sell/productList', '列表', '1', '1', '', '74', ',74,75', '2');
INSERT INTO `kw_auth_rule` VALUES ('76', 'Admin/Sell/getGoodsHistories', '操作历史', '1', '1', '', '75', ',74,75,76', '3');
INSERT INTO `kw_auth_rule` VALUES ('77', 'Admin/Sell/details', '详情', '1', '1', '', '75', ',74,75,77', '3');
INSERT INTO `kw_auth_rule` VALUES ('78', 'Admin/Sell/changeOff', '下架', '1', '1', '', '75', ',74,75,78', '3');
INSERT INTO `kw_auth_rule` VALUES ('79', 'Admin/Sell/examStatus', '审核通过', '1', '1', '', '75', ',74,75,79', '3');
INSERT INTO `kw_auth_rule` VALUES ('80', 'Admin/Sell/failStatus', '审核不通过', '1', '1', '', '75', ',74,75,80', '3');
INSERT INTO `kw_auth_rule` VALUES ('81', 'Admin/Sell/renewStatus', '上架', '1', '1', '', '75', ',74,75,81', '3');
INSERT INTO `kw_auth_rule` VALUES ('82', 'Admin/Sell/rStatus', '重审通过', '1', '1', '', '75', ',74,75,82', '3');
INSERT INTO `kw_auth_rule` VALUES ('83', 'Admin/Sell/del', '批量删除', '1', '1', '', '75', ',74,75,83', '3');
INSERT INTO `kw_auth_rule` VALUES ('84', 'Admin/hot', '限时抢购', '1', '1', '', '0', ',84', '1');
INSERT INTO `kw_auth_rule` VALUES ('85', 'Admin/Hot/productList', '列表', '1', '1', '', '84', ',84,85', '2');
INSERT INTO `kw_auth_rule` VALUES ('86', 'Admin/Hot/details', '详情', '1', '1', '', '85', ',84,85,86', '3');
INSERT INTO `kw_auth_rule` VALUES ('87', 'Admin/Hot/changeOff', '下架', '1', '1', '', '85', ',84,85,87', '3');
INSERT INTO `kw_auth_rule` VALUES ('88', 'Admin/Hot/examStatus', '审核通过', '1', '1', '', '85', ',84,85,88', '3');
INSERT INTO `kw_auth_rule` VALUES ('89', 'Admin/Hot/failStatus', '审核不通过', '1', '1', '', '85', ',84,85,89', '3');
INSERT INTO `kw_auth_rule` VALUES ('90', 'Admin/Hot/renewStatus', '上架', '1', '1', '', '85', ',84,85,90', '3');
INSERT INTO `kw_auth_rule` VALUES ('91', 'Admin/Hot/rStatus', '重审通过', '1', '1', '', '85', ',84,85,91', '3');
INSERT INTO `kw_auth_rule` VALUES ('92', 'Admin/Resource', '资源单管理', '1', '1', '', '0', ',92', '1');
INSERT INTO `kw_auth_rule` VALUES ('93', 'Admin/Resource/valided', '有效的资源单', '1', '1', '', '194', ',92,194,93', '3');
INSERT INTO `kw_auth_rule` VALUES ('94', 'Admin/Resource/pends', '待审核的资源单', '1', '1', '', '194', ',92,194,94', '3');
INSERT INTO `kw_auth_rule` VALUES ('95', 'Admin/Resource/overdues', '已过期的资源单', '1', '1', '', '194', ',92,194,95', '3');
INSERT INTO `kw_auth_rule` VALUES ('96', 'Admin/Resource/fails', '审核不通过的资源单', '1', '1', '', '194', ',92,194,96', '3');
INSERT INTO `kw_auth_rule` VALUES ('97', 'Admin/Resource/quashs', '已撤销的资源单', '1', '1', '', '194', ',92,194,97', '3');
INSERT INTO `kw_auth_rule` VALUES ('98', 'Admin/Resource/failed', '撤销通过', '1', '1', '', '195', ',92,195,98', '3');
INSERT INTO `kw_auth_rule` VALUES ('99', 'Admin/Resource/pass', '恢复通过', '1', '1', '', '195', ',92,195,99', '3');
INSERT INTO `kw_auth_rule` VALUES ('100', 'Admin/Resource/search', '搜索', '1', '1', '', '195', ',92,195,100', '3');
INSERT INTO `kw_auth_rule` VALUES ('101', 'Admin/BuyOffer', '求购管理', '1', '1', '', '0', ',101', '1');
INSERT INTO `kw_auth_rule` VALUES ('102', 'Admin/BuyOffer/findgoods', '列表', '1', '1', '', '101', ',101,102', '2');
INSERT INTO `kw_auth_rule` VALUES ('103', 'Admin/BuyOffer/review', '审核', '1', '1', '', '102', ',101,102,103', '3');
INSERT INTO `kw_auth_rule` VALUES ('104', 'Admin/BuyOffer/details', '详情', '1', '1', '', '102', ',101,102,104', '3');
INSERT INTO `kw_auth_rule` VALUES ('105', 'Admin/BuyOffer/expFind', '数据导出', '1', '1', '', '102', ',101,102,105', '3');
INSERT INTO `kw_auth_rule` VALUES ('106', 'Admin/Order', '订单管理', '1', '1', '', '0', ',106', '1');
INSERT INTO `kw_auth_rule` VALUES ('107', 'Admin/Order/OrderChildLists', '列表', '1', '1', '', '106', ',106,107', '2');
INSERT INTO `kw_auth_rule` VALUES ('108', 'Admin/Order/orderdetail', '详情', '1', '1', '', '107', ',106,107,108', '3');
INSERT INTO `kw_auth_rule` VALUES ('109', 'Admin/Content', '内容管理', '1', '1', '', '0', ',109', '1');
INSERT INTO `kw_auth_rule` VALUES ('110', 'Admin/Content/about_Us', '关于我们', '1', '1', '', '109', ',109,110', '2');
INSERT INTO `kw_auth_rule` VALUES ('111', 'Admin/Content/helpList', '帮助中心', '1', '1', '', '109', ',109,111', '2');
INSERT INTO `kw_auth_rule` VALUES ('112', 'Admin/Content/protocol', '用户服务协议', '1', '1', '', '110', ',109,110,112', '3');
INSERT INTO `kw_auth_rule` VALUES ('114', 'Admin/Content/logo', '网站LOGO', '1', '1', '', '109', ',109,114', '2');
INSERT INTO `kw_auth_rule` VALUES ('115', 'Admin/Content/partner', '合作伙伴', '1', '1', '', '109', ',109,115', '2');
INSERT INTO `kw_auth_rule` VALUES ('116', 'Admin/Content/about', '新增', '1', '1', '', '110', ',109,110,116', '3');
INSERT INTO `kw_auth_rule` VALUES ('117', 'Admin/Content/noticeList', '网站公告列表', '1', '1', '', '110', ',109,110,117', '3');
INSERT INTO `kw_auth_rule` VALUES ('118', 'Admin/Content/notice', '新增/编辑网站公告', '1', '1', '', '110', ',109,110,118', '3');
INSERT INTO `kw_auth_rule` VALUES ('119', 'Admin/Content/delnotice', '删除', '1', '1', '', '110', ',109,110,119', '3');
INSERT INTO `kw_auth_rule` VALUES ('120', 'Admin/Content/news', '新增媒体报道', '1', '1', '', '110', ',109,110,120', '3');
INSERT INTO `kw_auth_rule` VALUES ('121', 'Admin/Content/newsList', '媒体报道列表', '1', '1', '', '110', ',109,110,121', '3');
INSERT INTO `kw_auth_rule` VALUES ('122', 'Admin/Content/history', '新增/修改发展历程', '1', '1', '', '110', ',109,110,122', '3');
INSERT INTO `kw_auth_rule` VALUES ('123', 'Admin/Content/historyList', '发展历程列表', '1', '1', '', '110', ',109,110,123', '3');
INSERT INTO `kw_auth_rule` VALUES ('124', 'Admin/Content/offer', '新增/修改招聘', '1', '1', '', '110', ',109,110,124', '3');
INSERT INTO `kw_auth_rule` VALUES ('125', 'Admin/Content/offerList', '招聘列表', '1', '1', '', '110', ',109,110,125', '3');
INSERT INTO `kw_auth_rule` VALUES ('126', 'Admin/Content/deljob', '删除招聘', '1', '1', '', '110', ',109,110,126', '3');
INSERT INTO `kw_auth_rule` VALUES ('127', 'Admin/Content/partnerList', '列表', '1', '1', '', '115', ',109,115,127', '3');
INSERT INTO `kw_auth_rule` VALUES ('128', 'Admin/Content/addpartner', '新增', '1', '1', '', '115', ',109,115,128', '3');
INSERT INTO `kw_auth_rule` VALUES ('129', 'Admin/Content/upload', 'LOGO上传', '1', '1', '', '114', ',109,114,129', '3');
INSERT INTO `kw_auth_rule` VALUES ('130', 'Admin/Content/addhelp', '新增', '1', '1', '', '111', ',109,111,130', '3');
INSERT INTO `kw_auth_rule` VALUES ('131', 'Admin/Content/contact', '联系我们', '1', '1', '', '110', ',109,110,131', '3');
INSERT INTO `kw_auth_rule` VALUES ('132', 'Admin/Content/delpartner', '删除', '1', '1', '', '111', ',109,111,132', '3');
INSERT INTO `kw_auth_rule` VALUES ('133', 'Admin/Content/cooperate', '商务合作', '1', '1', '', '110', ',109,110,133', '3');
INSERT INTO `kw_auth_rule` VALUES ('134', 'Admin/Content/contactList', '联系我们列表', '1', '1', '', '110', ',109,110,134', '3');
INSERT INTO `kw_auth_rule` VALUES ('135', 'Admin/Data', '数据管理', '1', '1', '', '0', ',135', '1');
INSERT INTO `kw_auth_rule` VALUES ('136', 'Admin/Data/area', '地区管理', '1', '1', '', '135', ',135,136', '2');
INSERT INTO `kw_auth_rule` VALUES ('137', 'Admin/Data/areaList', '列表', '1', '1', '', '136', ',135,136,137', '3');
INSERT INTO `kw_auth_rule` VALUES ('138', 'Admin/Data/addArea', '新增', '1', '1', '', '136', ',135,136,138', '3');
INSERT INTO `kw_auth_rule` VALUES ('139', 'Admin/Data/updateArea', '修改', '1', '1', '', '136', ',135,136,139', '3');
INSERT INTO `kw_auth_rule` VALUES ('140', 'Admin/Data/delArea', '删除地区', '1', '1', '', '136', ',135,136,140', '3');
INSERT INTO `kw_auth_rule` VALUES ('141', 'Admin/Data/producer', '生产商', '1', '1', '', '135', ',135,141', '2');
INSERT INTO `kw_auth_rule` VALUES ('142', 'Admin/Data/brand', '品牌管理', '1', '1', '', '135', ',135,142', '2');
INSERT INTO `kw_auth_rule` VALUES ('143', 'Admin/Data/brandList', '列表', '1', '1', '', '142', ',135,142,143', '3');
INSERT INTO `kw_auth_rule` VALUES ('144', 'Admin/Data/addBrand', '新增', '1', '1', '', '142', ',135,142,144', '3');
INSERT INTO `kw_auth_rule` VALUES ('145', 'Admin/Data/modify', '修改', '1', '1', '', '142', ',135,142,145', '3');
INSERT INTO `kw_auth_rule` VALUES ('146', 'Admin/Data/delBrand', '删除', '1', '1', '', '142', ',135,142,146', '3');
INSERT INTO `kw_auth_rule` VALUES ('147', 'Admin/Data/Category', '商品类别', '1', '1', '', '135', ',135,147', '2');
INSERT INTO `kw_auth_rule` VALUES ('148', 'Admin/Data/getCategory', '列表', '1', '1', '', '147', ',135,147,148', '3');
INSERT INTO `kw_auth_rule` VALUES ('149', 'Admin/Data/addCategory', '新增', '1', '1', '', '147', ',135,147,149', '3');
INSERT INTO `kw_auth_rule` VALUES ('150', 'Admin/Data/updateCate', '修改', '1', '1', '', '147', ',135,147,150', '3');
INSERT INTO `kw_auth_rule` VALUES ('151', 'Admin/Data/delCate', '删除', '1', '1', '', '147', ',135,147,151', '3');
INSERT INTO `kw_auth_rule` VALUES ('152', 'Admin/Data/getProducer', '列表', '1', '1', '', '141', ',135,141,152', '3');
INSERT INTO `kw_auth_rule` VALUES ('153', 'Admin/Data/addProducer', '新增', '1', '1', '', '141', ',135,141,153', '3');
INSERT INTO `kw_auth_rule` VALUES ('154', 'Admin/Data/updateProducer', '修改', '1', '1', '', '141', ',135,141,154', '3');
INSERT INTO `kw_auth_rule` VALUES ('155', 'Admin/Data/delProducer', '删除', '1', '1', '', '141', ',135,141,155', '3');
INSERT INTO `kw_auth_rule` VALUES ('156', 'Admin/Data/trade', '所在行业', '1', '1', '', '135', ',135,156', '2');
INSERT INTO `kw_auth_rule` VALUES ('157', 'Admin/Data/tradeList', '列表', '1', '1', '', '156', ',135,156,157', '3');
INSERT INTO `kw_auth_rule` VALUES ('158', 'Admin/Data/tradeAdd', '新增', '1', '1', '', '156', ',135,156,158', '3');
INSERT INTO `kw_auth_rule` VALUES ('159', 'Admin/Data/tradeEdit', '修改', '1', '1', '', '156', ',135,156,159', '3');
INSERT INTO `kw_auth_rule` VALUES ('160', 'Admin/Data/tradeDel', '删除', '1', '1', '', '156', ',135,156,160', '3');
INSERT INTO `kw_auth_rule` VALUES ('161', 'Admin/Data/property', '单位性质', '1', '1', '', '135', ',135,161', '2');
INSERT INTO `kw_auth_rule` VALUES ('162', 'Admin/Data/propertyList', '列表', '1', '1', '', '161', ',135,161,162', '3');
INSERT INTO `kw_auth_rule` VALUES ('163', 'Admin/Data/propertyAdd', '新增', '1', '1', '', '161', ',135,161,163', '3');
INSERT INTO `kw_auth_rule` VALUES ('164', 'Admin/Data/propertyEdit', '修改', '1', '1', '', '161', ',135,161,164', '3');
INSERT INTO `kw_auth_rule` VALUES ('165', 'Admin/Data/propertyDel', '删除', '1', '1', '', '161', ',135,161,165', '3');
INSERT INTO `kw_auth_rule` VALUES ('166', 'Admin/Data/model', '经营模式', '1', '1', '', '135', ',135,166', '2');
INSERT INTO `kw_auth_rule` VALUES ('167', 'Admin/Data/modelList', '列表', '1', '1', '', '166', ',135,166,167', '3');
INSERT INTO `kw_auth_rule` VALUES ('168', 'Admin/Data/modelAdd', '新增', '1', '1', '', '166', ',135,166,168', '3');
INSERT INTO `kw_auth_rule` VALUES ('169', 'Admin/Data/modelEdit', '修改', '1', '1', '', '166', ',135,166,169', '3');
INSERT INTO `kw_auth_rule` VALUES ('170', 'Admin/Data/modelDel', '删除', '1', '1', '', '166', ',135,166,170', '3');
INSERT INTO `kw_auth_rule` VALUES ('174', 'Admin/Data/turnover', '年营业额', '1', '1', '', '135', ',135,174', '2');
INSERT INTO `kw_auth_rule` VALUES ('175', 'Admin/Data/turnoverList', '列表', '1', '1', '', '174', '135,174,175', '3');
INSERT INTO `kw_auth_rule` VALUES ('176', 'Admin/Data/turnoverAdd', '新增', '1', '1', '', '174', '135,174,176', '3');
INSERT INTO `kw_auth_rule` VALUES ('177', 'Admin/Data/turnoverEdit', '修改', '1', '1', '', '174', '135,174,177', '3');
INSERT INTO `kw_auth_rule` VALUES ('178', 'Admin/Data/turnoverDel', '删除', '1', '1', '', '174', '135,174,178', '3');
INSERT INTO `kw_auth_rule` VALUES ('179', 'Admin/Data/employees', '单位人数', '1', '1', '', '135', '135,179', '2');
INSERT INTO `kw_auth_rule` VALUES ('180', 'Admin/Data/employeesList', '列表', '1', '1', '', '179', ',135,179,180', '3');
INSERT INTO `kw_auth_rule` VALUES ('181', 'Admin/Data/employeesAdd', '新增', '1', '1', '', '179', ',135,179,181', '3');
INSERT INTO `kw_auth_rule` VALUES ('182', 'Admin/Data/employeesEdit', '修改', '1', '1', '', '179', ',135,179,182', '3');
INSERT INTO `kw_auth_rule` VALUES ('183', 'Admin/Data/employeesDel', '删除', '1', '1', '', '179', ',135,179,183', '3');
INSERT INTO `kw_auth_rule` VALUES ('184', 'Admin/Data/wxappsecret', '微信密码管理', '1', '1', '', '135', ',135,184', '2');
INSERT INTO `kw_auth_rule` VALUES ('185', 'Admin/Data/addAppSecret', '新增', '1', '1', '', '184', ',135,184,185', '3');
INSERT INTO `kw_auth_rule` VALUES ('186', 'Admin/Data/editAppSecret', '修改', '1', '1', '', '184', ',135,184,186', '3');
INSERT INTO `kw_auth_rule` VALUES ('187', 'Admin/Data/delAppSecret', '删除', '1', '1', '', '184', ',135,184,187', '3');
INSERT INTO `kw_auth_rule` VALUES ('188', 'Admin/Data/getAppSecretLists', '列表', '1', '1', '', '184', ',135,184,188', '3');
INSERT INTO `kw_auth_rule` VALUES ('194', 'Admin/Resource/list', '列表', '1', '1', '', '92', ',92,194', '2');
INSERT INTO `kw_auth_rule` VALUES ('195', 'Admin/Resource/cao', '操作', '1', '1', '', '92', ',92,195', '2');
INSERT INTO `kw_auth_rule` VALUES ('196', 'Admin/Auth/userList', '查看', '1', '1', '', '2', '1,2,196', '3');
INSERT INTO `kw_auth_rule` VALUES ('197', 'Admin/Member/index', '商家列表', '1', '1', '', '46', ',45,46,197', '3');
INSERT INTO `kw_auth_rule` VALUES ('198', 'Admin/Member/companyAuth', '企业认证列表', '1', '1', '', '47', ',45,47,198', '3');
INSERT INTO `kw_auth_rule` VALUES ('199', 'Admin/Member/memberSign', '签约管理列表', '1', '1', '', '48', ',45,48,199', '3');
INSERT INTO `kw_auth_rule` VALUES ('200', 'Admin/store/valid', '有效的商品', '1', '1', '', '65', ',64,65,200', '3');
INSERT INTO `kw_auth_rule` VALUES ('201', 'Admin/store/pending', '待审核的商品', '1', '1', '', '65', ',64,65,201', '3');
INSERT INTO `kw_auth_rule` VALUES ('202', 'Admin/store/fail', '审核不通过的商品', '1', '1', '', '65', ',64,65,202', '3');
INSERT INTO `kw_auth_rule` VALUES ('203', 'Admin/store/quash', '已撤销的商品', '1', '1', '', '65', ',64,65,203', '3');
INSERT INTO `kw_auth_rule` VALUES ('204', 'Admin/Sell/valid', '有效的销售商品', '1', '1', '', '75', ',74,75,204', '3');
INSERT INTO `kw_auth_rule` VALUES ('205', 'Admin/Sell/pending', '待审核的销售商品', '1', '1', '', '75', ',74,75,205', '3');
INSERT INTO `kw_auth_rule` VALUES ('206', 'Admin/Sell/fail', '审核不通过的销售商品', '1', '1', '', '75', ',74,75,206', '3');
INSERT INTO `kw_auth_rule` VALUES ('207', 'Admin/Sell/soldout', '已下架的销售商品', '1', '1', '', '75', ',74,75,207', '3');
INSERT INTO `kw_auth_rule` VALUES ('208', 'Admin/hot/valid', '有效的抢购活动', '1', '1', '', '85', ',84,85,208', '3');
INSERT INTO `kw_auth_rule` VALUES ('209', 'Admin/hot/pending', '待审核的抢购活动', '1', '1', '', '85', ',84,85,209', '3');
INSERT INTO `kw_auth_rule` VALUES ('210', 'Admin/hot/fail', '审核不通过的抢购活动', '1', '1', '', '85', ',84,85,210', '3');
INSERT INTO `kw_auth_rule` VALUES ('211', 'Admin/hot/soldout', '已下架的抢购活动', '1', '1', '', '85', ',84,85,211', '3');
INSERT INTO `kw_auth_rule` VALUES ('212', 'Admin/hot/index', '抢购推荐', '1', '1', '', '85', ',84,85,212', '3');
INSERT INTO `kw_auth_rule` VALUES ('213', 'Admin/BuyOffer/lists', '求购管理', '1', '1', '', '102', ',101,102,213', '3');
INSERT INTO `kw_auth_rule` VALUES ('215', 'Admin/Finance', '金融管理', '1', '1', '', '0', ',215', '1');
INSERT INTO `kw_auth_rule` VALUES ('218', 'Admin/Order/child', '订单管理列表', '1', '1', '', '107', ',106,107,218', '3');
INSERT INTO `kw_auth_rule` VALUES ('219', 'Admin/Content/help', '帮助中心', '1', '1', '', '111', ',109,111,219', '3');
INSERT INTO `kw_auth_rule` VALUES ('220', 'Admin/Content/aboutUs', '关于我们', '1', '1', '', '110', ',109,110,220', '3');
INSERT INTO `kw_auth_rule` VALUES ('221', 'Admin/Content/partner', '合作伙伴', '1', '1', '', '115', ',109,115,221', '3');
INSERT INTO `kw_auth_rule` VALUES ('222', 'Admin/Finance/lists', '金融管理列表', '1', '1', '', '215', ',215,222', '2');
INSERT INTO `kw_auth_rule` VALUES ('223', 'Admin/Finance/del', '删除', '1', '1', '', '222', ',215,222,223', '3');
INSERT INTO `kw_auth_rule` VALUES ('224', 'Admin/Finance/dataList', '列表', '1', '1', '', '222', ',215,222,224', '3');
INSERT INTO `kw_auth_rule` VALUES ('225', 'Admin/Data/phone', '手机白名单', '1', '1', '', '135', ',135,225', '2');
INSERT INTO `kw_auth_rule` VALUES ('226', 'Admin/Data/PhoneLists', '列表', '1', '1', '', '225', ',135,225,226', '3');
INSERT INTO `kw_auth_rule` VALUES ('227', 'Admin/Data/AddPhone', '新增', '1', '1', '', '225', ',135,225,227', '3');
INSERT INTO `kw_auth_rule` VALUES ('228', 'Admin/Data/DelPhone', '删除', '1', '1', '', '225', ',135,225,228', '3');
INSERT INTO `kw_auth_rule` VALUES ('229', 'ttt', 'ttt', '1', '0', '', '2', '1,2,229', '3');
INSERT INTO `kw_auth_rule` VALUES ('230', 'Admin/Auth/userBatchActive', '用户批量启用', '1', '1', '', '2', '1,2,230', '3');
INSERT INTO `kw_auth_rule` VALUES ('231', 'Admin/Auth/userBatchInactive', '用户批量禁用', '1', '1', '', '2', '1,2,231', '3');
INSERT INTO `kw_auth_rule` VALUES ('232', 'Admin/Auth/expUser', '数据导出', '1', '1', '', '2', '1,2,232', '3');
INSERT INTO `kw_auth_rule` VALUES ('233', 'Admin/Auth/expAuth', '数据导出', '1', '1', '', '26', '1,26,233', '3');
INSERT INTO `kw_auth_rule` VALUES ('234', 'Admin/Content/delNews', '删除媒体报道', '1', '1', '', '110', ',109,110,234', '3');
INSERT INTO `kw_auth_rule` VALUES ('235', 'Admin/Supply/index', '供应管理', '1', '0', '', '0', ',235', '1');
INSERT INTO `kw_auth_rule` VALUES ('242', 'Admin/Supply', '供应管理', '1', '1', '', '0', ',242', '1');
INSERT INTO `kw_auth_rule` VALUES ('243', 'Admin/Supply/findGoods', '列表', '1', '1', '', '242', ',242,243', '2');
INSERT INTO `kw_auth_rule` VALUES ('244', 'Admin/Supply/review', '审核', '1', '1', '', '243', ',242,243,244', '3');
INSERT INTO `kw_auth_rule` VALUES ('245', 'Admin/Supply/details', '详情', '1', '1', '', '243', ',242,243,245', '3');
INSERT INTO `kw_auth_rule` VALUES ('246', 'Admin/Supply/expFind', '数据导出', '1', '1', '', '243', ',242,243,246', '3');
INSERT INTO `kw_auth_rule` VALUES ('247', 'Admin/Supply/lists', '列表', '1', '1', '', '243', ',242,243,247', '3');
INSERT INTO `kw_auth_rule` VALUES ('248', 'Admin/Supply/SupplyHistory', '历史查询', '1', '1', '', '243', ',242,243,248', '3');
INSERT INTO `kw_auth_rule` VALUES ('249', 'Admin/Data/indicator', '关键指标', '1', '1', '', '135', ',135,249', '2');
INSERT INTO `kw_auth_rule` VALUES ('250', 'Admin/Data/indicatorList', '列表', '1', '1', '', '249', ',135,249,250', '3');
INSERT INTO `kw_auth_rule` VALUES ('251', 'Admin/Data/indicatorAdd', '添加', '1', '1', '', '249', ',135,249,251', '3');
INSERT INTO `kw_auth_rule` VALUES ('252', 'Admin/Data/indicatorEdit', '修改', '1', '1', '', '249', ',135,249,252', '3');

-- ----------------------------
-- Table structure for kw_brand
-- ----------------------------
DROP TABLE IF EXISTS `kw_brand`;
CREATE TABLE `kw_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` smallint(5) NOT NULL,
  `createTime` int(11) NOT NULL,
  `text` varchar(60) NOT NULL,
  `depth` tinyint(3) unsigned NOT NULL,
  `path` varchar(20) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_brand
-- ----------------------------

-- ----------------------------
-- Table structure for kw_category
-- ----------------------------
DROP TABLE IF EXISTS `kw_category`;
CREATE TABLE `kw_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` smallint(5) NOT NULL,
  `createTime` int(11) NOT NULL,
  `text` varchar(60) NOT NULL,
  `depth` tinyint(3) unsigned NOT NULL,
  `path` varchar(20) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_category
-- ----------------------------
INSERT INTO `kw_category` VALUES ('1', '0', '1471679143', 'xxx', '1', '1', '1');

-- ----------------------------
-- Table structure for kw_collect
-- ----------------------------
DROP TABLE IF EXISTS `kw_collect`;
CREATE TABLE `kw_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(1) NOT NULL,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `times` int(11) NOT NULL,
  `status` smallint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_collect
-- ----------------------------

-- ----------------------------
-- Table structure for kw_company
-- ----------------------------
DROP TABLE IF EXISTS `kw_company`;
CREATE TABLE `kw_company` (
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(120) NOT NULL COMMENT '公司名称',
  `trade` smallint(3) NOT NULL COMMENT '所属行业',
  `model` smallint(3) NOT NULL COMMENT '经营模式',
  `property` smallint(3) NOT NULL COMMENT '单位性质',
  `createTime` int(11) NOT NULL COMMENT '成立日期',
  `timeup` varchar(30) NOT NULL COMMENT '营业期限',
  `num` smallint(3) NOT NULL COMMENT '单位人数',
  `turnover` smallint(3) NOT NULL COMMENT '年营业额',
  `phone` varchar(20) NOT NULL COMMENT '联系人电话',
  `data` varchar(10000) NOT NULL COMMENT '其他信息，序列化后存储',
  `status` tinyint(1) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_company
-- ----------------------------
INSERT INTO `kw_company` VALUES ('1', '奇化', '2', '3', '2', '3', '2', '2', '3', '23', '2', '1');

-- ----------------------------
-- Table structure for kw_companydata
-- ----------------------------
DROP TABLE IF EXISTS `kw_companydata`;
CREATE TABLE `kw_companydata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL,
  `parentId` smallint(5) NOT NULL,
  `createTime` int(11) NOT NULL,
  `text` varchar(60) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_companydata
-- ----------------------------
INSERT INTO `kw_companydata` VALUES ('1', '1', '1', '1471576253', '666', '1');

-- ----------------------------
-- Table structure for kw_company_info
-- ----------------------------
DROP TABLE IF EXISTS `kw_company_info`;
CREATE TABLE `kw_company_info` (
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `companyName` varchar(255) NOT NULL COMMENT '公司名称',
  `contact` varchar(100) NOT NULL COMMENT '用户名',
  `model` int(11) NOT NULL COMMENT '经营模式id',
  `establishmentDate` int(11) NOT NULL COMMENT '成立日期',
  `employee` int(11) NOT NULL COMMENT '单位人数id',
  `trade` int(11) NOT NULL COMMENT '所在行业id',
  `property` int(11) NOT NULL COMMENT '单位性质id',
  `businessTerm` varchar(255) NOT NULL COMMENT '营业期限(时间戳)',
  `turnover` int(11) NOT NULL COMMENT '年营业额(id)',
  `businessScope` text NOT NULL COMMENT '经营范围',
  `companyIntroduction` text NOT NULL COMMENT '公司介绍',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1,正常,0，删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_company_info
-- ----------------------------

-- ----------------------------
-- Table structure for kw_contact
-- ----------------------------
DROP TABLE IF EXISTS `kw_contact`;
CREATE TABLE `kw_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `phone` varchar(60) NOT NULL,
  `mail` varchar(120) NOT NULL,
  `other` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of kw_contact
-- ----------------------------
INSERT INTO `kw_contact` VALUES ('1', '佛山总公司', '0757-63812329', 'qihua@keywa.com', 'a:4:{s:7:\"company\";s:48:\"广东奇化化工交易中心股份有限公司\";s:4:\"fuwu\";s:12:\"020-82598600\";s:3:\"fax\";s:13:\"0757-63812328\";s:7:\"address\";s:99:\"广东省佛山市禅城区华宝南路13号佛山国家火炬创新创业园C1-9、C1-10、C1-11-1\";}');
INSERT INTO `kw_contact` VALUES ('4', '供应商合作', '13434139393', 'liangdongwen@keywa.com', 'a:2:{s:4:\"name\";s:9:\"梁先生\";s:2:\"qq\";s:7:\"4911897\";}');
INSERT INTO `kw_contact` VALUES ('5', '采购合作', '020-82598060', 'procurement@keywa.com', 'a:2:{s:4:\"name\";s:9:\"莫先生\";s:2:\"qq\";s:8:\"63481665\";}');
INSERT INTO `kw_contact` VALUES ('6', '品牌推广', '0757-63812329', 'marketing@keywa.com', 'a:2:{s:4:\"name\";s:9:\"张先生\";s:2:\"qq\";s:8:\"13399157\";}');
INSERT INTO `kw_contact` VALUES ('3', '广州分公司', '020-82598600', 'qihua@keywa.com', 'a:4:{s:7:\"company\";s:48:\"广东奇化化工交易中心股份有限公司\";s:4:\"fuwu\";s:12:\"020-82598600\";s:3:\"fax\";s:12:\"020-82598949\";s:7:\"address\";s:48:\"广东省广州市天河区黄埔大道东128号\";}');
INSERT INTO `kw_contact` VALUES ('7', '投资洽谈', '0757-63812338', 'investment@keywa.com', 'a:2:{s:4:\"name\";s:9:\"梁小姐\";s:2:\"qq\";s:9:\"896485086\";}');
INSERT INTO `kw_contact` VALUES ('8', '客户服务', '020-82598600', 'sujunrui@keywa.com', 'a:2:{s:4:\"name\";s:9:\"苏先生\";s:2:\"qq\";s:10:\"1556690394\";}');
INSERT INTO `kw_contact` VALUES ('9', '投诉建议', '0757-63813461', 'hr@keywa.com', 'a:2:{s:4:\"name\";s:9:\"李小姐\";s:2:\"qq\";s:10:\"3409532575\";}');

-- ----------------------------
-- Table structure for kw_contents
-- ----------------------------
DROP TABLE IF EXISTS `kw_contents`;
CREATE TABLE `kw_contents` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `type` varchar(30) NOT NULL COMMENT '内容的类型',
  `title` varchar(255) NOT NULL COMMENT '文章标题',
  `content` longtext NOT NULL COMMENT '文章内容',
  `createTime` int(11) DEFAULT NULL,
  `other` text COMMENT '建创时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=167 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of kw_contents
-- ----------------------------
INSERT INTO `kw_contents` VALUES ('41', '用户服务协议', '《奇化网用户服务协议》', '&lt;p class=&quot;MsoNormal&quot; align=&quot;center&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;strong&gt;《奇化网用户注册协议》&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;br /&gt;\n&lt;/span&gt;\n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 欢迎阅读奇化网服务使用协议&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;下称&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;本协议&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;。奇化网，指包括但不限于域名为&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;等广东奇化化工交易中心股份有限公司所有的网站，奇化网平台所提供的各项服务的所有权和运营权，均归属于广东奇化化工交易中心股份有限公司。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 本协议阐述之条款和条件适用于您使用奇化网&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(www.keywa.com)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;所提供的包括化工商城、化工团购、化工供求信息、知识交易、化工行情资讯等服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;下称&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 本协议内容同时包括奇化网已经发布或可能不断发布或更新的相关协议、业务规则等内容。上述内容一经正式发布，即为本协议不可分割的组成部分。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第一条 接受条款&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、以任何方式注册或登录奇化网即表示您同意自己已经与奇化网订立本协议，且您将受本协议的条款和条件&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;下称&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;条款&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;约束。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、奇化网可根据实际业务开展情况适时更改&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;条款&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;。如您不同意相关变更，可以选择停止使用&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;。经修订的&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;条款&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;一经在奇化网公布后，立即自动生效。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、您在第一次登录后仔细阅读修订后的&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;条款&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;，有权选择停止或继续使用&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;；一旦您继续使用&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;，则表示您已接受经修订的&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;条款&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;，当您与奇化网发生争议时，应以最新的服务协议为准。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、除另行明确声明外，任何使&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;范围扩大或功能增强的新内容均受本协议约束。除非经奇化网的授权高层管理人员签订书面协议，本协议不得另行作出修订。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;5&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、本&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;仅供能够根据相关法律订立具有法律约束力的合约的公司使用。如您代表一家公司或其他法律主体在本网站登记，则您声明和保证，您有权使该公司或其他法律主体受本协议&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;条款&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;约束。 如您不符合资格，请勿注册，否则奇化网保留随时中止或终止您用户资格的权利。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;6&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、奇化网&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt; “&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;不会提供给被暂时或永久中止资格的奇化网会员使用。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第二条 会员注册方法&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 奇化网会员实行免费会员制度&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;,&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;注册用户可免费使用奇化网提供的功能和服务，注册会员对所提交的任何信息的真实性和合法性负责。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第三条 会员的权力和义务&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、会员的权利：&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）参与交易及与交易相关的活动（包括但不限于发布要约、进行承诺、缔结协议、履行协议等）；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）使用奇化网的有关设施，享用奇化网提供的市场行情类信息和相关服务；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）申请暂停、恢复或注销会员资格；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）奇化网规定的其他权利。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（二）会员的义务：&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）遵守国家法律、法规以及奇化网已公布的相关规定，接受奇化网的监督与管理；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）造成奇化网及其他会员损失的，应承担赔偿责任；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）保护自己的账号和交易密码，并对因其在奇化网使用所生的结果负责；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）主动了解奇化网发布的业务信息、公告和各项制度，并承担未尽合理关注造成的损失；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;5&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）按奇化网规定的标准配备相关网络设施；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;6&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）发生重大情况变更时及时通知奇化网。 &lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第四条 会员注册时已保证&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、会员注册时必须保证知悉本协议及其他已公开的所有文件规定的条款、条件，并承诺遵守该等条款、条件。本协议对奇化网所有注册会员具有绝对约束力。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、提交的会员资料真实、准确、完整；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、维持并及时更新会员资料，并保持会员资料的连续性，否则奇化网有权终止会员资格，并拒绝在任何时间向您提供任何服务；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、对因资料不实引发的经济及法律纠纷，由资料提供方负全部责任；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;5&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、您不得在奇化网公布或通过奇化网进行以下行为：买卖可能使奇化网违反任何相关法律、法规、条例或规章的任何物品或买卖，奇化网认为应禁止或不适合通过本网站买卖的任何物品，如有违反，需由您自行承担由此引起的法律责任。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第五条 会员资格的终止&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、会员可以在任何时间停止使用本网站并向奇化网申请终止其会员资格。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、会员同意，奇化网根据本协议及相关规则的任何规定终止会员资格的措施可在不发出事先通知的情况下实施，会员资格终止后，会员的账号立即无效，奇化网将撤销账号以及在账号内的所有相关资料和档案。会员资格终止后，奇化网将没有义务为会员保留原账号及与之相关的任何信息。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、会员资格终止时，会员无条件同意以下规则：&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）全面适当履行所有未完成之交易；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）全面适当履行与第三人在该会员资格终止或届满前有可能确认的交易；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）与奇化网结清所有款项；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、申请终止会员资格时，会员应向奇化网提出书面申请，并办理以下事项：&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）全面适当履行所有未完成之交易；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）了结在奇化网的全部债权、债务及费用；&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）其他应当办理的事项。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;5&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、会员有下列情形之一的，奇化网有权终止会员资格，由此产生的一切法律上和经济上的责任由该会员承担：&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）扰乱交易秩序或恶意损害其他会员利益的；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）提供虚假资料或虚假信息的；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）违反国家法律、法规或奇化网相关规定或制度的；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）奇化网认为应该终止会员资格的其他情形。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第六条 会员注册名、密码和保密&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、您须自行负责对您的会员注册名和密码保密，且须对您在会员注册名和密码下发生的所有活动承担责任。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、您同意：如发现任何人未经授权使用您的会员注册名或密码，或发生违反保密规定的任何其他情况，您会立即通知奇化网；及确保您在每个上网时段结束时，以正确步骤离开网站。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、奇化网不能也不会对因您未能遵守本款规定而发生的任何损失或损毁负责。您不得向任何第三者披露您的密码，或与任何第三者共用您的密码，或为任何未经批准的目的使用您的密码。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第七条 关于您的资料的规则&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;您同意，并保证&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;您的资料&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;和您供在奇化网上交易的任何&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;物品&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（泛指一切可供依法交易的、有形的或无形的、以各种形态存在的某种具体的物品，或某种权利或利益，或某种票据或证券，或某种服务或行为。本协议中&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;物品&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;一词均含此义）符合以下规则：&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）不会有欺诈成份，与售卖伪造或盗窃无涉；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）不会侵犯任何第三者对该物品享有的物权，或版权、专利、商标、商业秘密或其他知识产权，或隐私权、名誉权；&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp; &lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）不会违反任何法律、法规、条例或规章&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt; (&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;包括但不限于关于规范出口管理、贸易配额、保护消费者、不正当竞争或虚假广告的法律、法规、条例或规章&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;；&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp; &lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）不会含有诽谤（包括商业诽谤）、非法恐吓或非法骚扰的内容；&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp; &lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;5&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）不会含有色情、赌博、毒品交易等违法内容；&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp; &lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;6&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）不会含有蓄意毁坏、恶意干扰、秘密地截取或侵占任何系统、数据或个人资料的任何病毒、伪装破坏程序、电脑蠕虫、定时程序炸弹或其他电脑程序；&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp; &lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;7&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）不会直接或间接与下述各项货物或服务连接，或包含对下述各项货物或服务的描述：本协议项下禁止的货物或服务；或您无权连接或包含的货物或服务。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;8&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）您不得在本公司网站公布或通过本公司网站买卖：可能使本公司违反任何相关法律、法规、条例或规章的任何物品；或奇化网认为应禁止或不适合通过本网站买卖的任何物品；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;9&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）此外，您同意不会：在与任何连锁信件、大量胡乱邮寄的电子邮件、滥发电子邮件或任何复制或多余的信息有关的方面使用&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;；未经其他人士同意，利用&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;收集其他人士的电子邮件地址及其他资料；或利用&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;制作虚假的电子邮件地址，或以其他形式试图在发送人的身份或信息的来源方面误导其他人士。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第八条 您授予本公司的许可使用权&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 您授予本公司通用的、永久的、免费的许可使用权利&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;并有权在多个层面对该权利进行再授权&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;，使本公司有权&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;全部或部份地&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;使用、复制、修订、改写、发布、翻译、分发、执行和展示&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&quot;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;您的资料&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&quot;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;或制作其派生作品，和&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;/&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;或以现在已知或日后开发的任何形式、媒体或技术，将&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&quot;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;您的资料&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&quot;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;纳入其他作品内，奇化网可能与第三方合作向用户提供相关的网络服务，在此情况下，如该第三方同意承担与奇化网同等的保护用户隐私的责任，则奇化网有权将&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;您的资料&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;提供给该第三方。奇化网将仅根据本公司的《法律声明》使用&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;您的资料&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;。本公司《法律声明》的全部条款属于本协议的一部份，因此，您必须仔细阅读。请注意，您一旦自愿地在奇化网披露“您的资料”，该等资料即视为可被奇化网合法获取和使用。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第九条 系统完整性&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 您同意，您不得使用任何装置、软件或例行程序干预或试图干预奇化网的正常运作或正在本公司网站上进行的任何交易。您不得采取对任何将不合理或不合比例的庞大负载加诸本公司网络结构的行动，否则，需承担由此而造成的责任。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第十条 服务说明&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、奇化网为会员提供信息收集、交流以及奇化网公布的各类规则所规定的各类网上交易和服务。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、奇化网不承担因会员提供的任何信息而产生的任何责任，也不承担会员与其它会员之间因网上或线下交易而产生的本协议或各类规则规定之外的其他责任。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、会员同意，奇化网有权独立判断会员的行为是否符合本协议及相关规则的要求。奇化网可随时中断对违反本协议及相关规则内容的会员的服务。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、奇化网对于用户提供的、自行收集到的、经认证的个人信息将按照《电信和互联网服务用户个人信息保护规定》及相关法律法规、本协议及平台其他规则予以保护、使用或者披露。奇化网将采用行业标准惯例以保护用户的个人资料，但奇化网不能确保由于技术限制的原因，用户的个人资料不会通过本协议中未列明的途径泄露、盗取出去。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第十一条 责任范围&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 您明确理解和同意，奇化网不对因下述任一情况而发生的任何损害赔偿承担责任，包括但不限于利润、商誉、使用、数据等方面的损失或其他无形损失的损害赔偿&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt; (&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;无论奇化网是否已被告知该等损害赔偿的可能性&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;：&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）您未能正确、合理使用&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）您因通过或从&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;购买或获取任何货物、样品、数据、资料或服务，或通过或从&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;接收任何信息或缔结任何交易所产生的获取替代货物和服务的费用；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）第三方未经批准接入或更改您的传输资料或数据；任何第三方对&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;的声明或关于&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;的行为；或因第三方任何原因而引起的与&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;服务&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;有关的任何其他事宜，包括疏忽。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）对于因本公司合理控制范围以外的原因，包括但不限于自然灾害、罢工或骚乱、物质短缺或定量配给、暴动、战争行为、政府行为、通讯或其他设施故障或严重伤亡事故等，致使本公司延迟或未能履约的，奇化网不对您承担任何责任。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第十二条 责任豁免条款及赔偿&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 会员同意奇化网不对会员或任何第三人因下列情况引起的损失负责：&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）因不可抗力（包括但不限于地震、台风、水灾、火灾、战争及其他不能预见、不能避免且不可克服的客观事件）导致的；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）因奇化网不可预测或无法控制的系统故障、设备故障、通讯故障、停电、黑客攻击等突发事件给会员造成的损失；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）奇化网终止会员资格或拒绝会员接入的；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）有关商品或服务违反或被指称违反任何保证的；&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;5&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）非法使用会员的名称、帐号、密码登录的；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;6&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）非经授权获取或更改会员所传送的信息或数据的；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;7&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）服务中的任何第三人的声明或行为；&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;8&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）在任何情况下，奇化网对会员的损失、损害或请求的全部责任不应超过会员向奇化网已支付的与该争议标的有关的金额。会员保证奇化网不会因会员出售的商品或服务不符合约定或违反保证而遭受任何索赔或承担任何责任；&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;9&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）在任何情况下，奇化网不对任何间接损失承担责任；&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;10&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）其他违反国家法律法规的情况。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第十三条 法律适用及争议解决&lt;/strong&gt;&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、本协议受中华人民共和国法律管辖。发生争议时，如对有关业务规则有不同的理解的，以在网站上最新颁布的为准并据之解释。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、因履行和解释本协议及相关规则发生的争议，奇化网与会员、会员与会员须先依友好协商方式解决。如在一方向另一方发出要求协商解决的书面通知之日起&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;15&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;日内争议仍未得到解决，则任何一方均可向奇化网所在地有管辖权的人民法院提起诉讼。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;、奇化网与会员、会员与会员在对争议事项进行协商、和解、调解、诉讼的过程中，应继续履行本协议除争议事项外的其他条款。&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp; &lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;本会员注册协议条款由广东奇化化工交易中心股份有限公司负责解释。&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp; &lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;', '1476413996', null);
INSERT INTO `kw_contents` VALUES ('37', '法律声明', '法律声明', '&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; “奇化网”提醒您：在使用奇化网平台的各项服务前，请您务必仔细阅读并透彻理解本声明。如果您选择使用奇化网，您的使用行为将被视为对本声明全部内容的认可。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; “奇化网”网站上所有展示信息全部免费，包括提供化工商城、团购、求购、知识交易等，浏览和使用此类服务或信息全部免费！奇化网将从化工交易作为切入口，打通整个化工产业链，免费为买卖双方提供平台服务。。&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第一条 服务声明&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; “&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;奇化网&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;向会员提供化工销售信息、求购信息、化工知识交易等服务。除将来&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;奇化网&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;另有其它明示规定，包含后续所有新推出的产品或新增加的服务功能，均适用本声明条款之规范。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 除适用相关法定许可或征得本公司同意，本网站的信息及其任何组成部分不得被重新编辑、复制、抄袭，或为任何未经本公司允许的商业目的所使用。如果本公司确定用户行为违法或有损本网站和本公司的合法权益，本公司将采取相关法律措施，包括但不限于拒绝提供服务、冻结或删除会员帐户、提起法律诉讼等，。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; “奇化网”致力于打造专业、高效的化工现货交易平台，用户在使用时应当了解明白“奇化网”上所有信息均为会员&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;化工供应商或采购商&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;自由发布，会员应依法对其提供的任何信息承担全部责任。&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;奇化网&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;会对信息进行必要的核查&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;筛选&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;，但最终对信息的合法性、准确性、真实性不承担任何法律责任。如用户发现某些信息中含有虚假、违法内容，请及时联系&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;奇化网&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;，待核实之后，&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;奇化网&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;将根据中国法律法规和政府规范性文件采取措施移除相关内容或屏蔽相关链接。&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;奇化网&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;不对会员所发布的信息之删除或储存失败负责。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 买卖双方通过“奇化网”获取信息完成化工交易的过程中需务必遵守中国的相关法律法规。奇化网提醒您：请认真查验化工供应商三证和代理人授权书等文件的真实性，如涉及危化品或易制毒品时，您还需进一步核实供应商是否拥有相关生产、经营资质许可情况，并签订书面协议以保证买卖双方之间的利益。“奇化网”不对会员之间达成协议过程中的任何纠纷承担法律责任，包括但不限于化工物流、加工配送、使用过程中发生的任何意外状况等。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 如买卖双方在交易过程中发生纠纷，在当事人自愿平等的前提下，买卖双方可提出要求“奇化网”协助调解。“奇化网”会在查明事实、分清是非的基础上，严格遵守国家法律法规基础上提出调解建议。不得因未经调解或者调解不成而阻止对方当事人向人民法院起诉。经调解达成的协议具有法律效力，但奇化网对此协议内容不承担任何法律责任。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第二条 隐私权保护声明&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 奇化网非常重视对用户隐私的保护。奇化网隐私权保护声明系本网站保护用户个人隐私的承诺，适用于您与奇化网的交互行为以及您注册和使用奇化网的在线服务，请您务必仔细阅读。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;一&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;个人资料的收集&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 本公司仅在与交易项目有关的合法目的下，并经用户同意，以合法的方式收集必要的用户个人资料。本平台有可能根据收集到的用户姓名、地址、电话号码、电子邮件等信息向用户发送免费的信息宣传资料，或根据用户留下的真实有效的联系方式主动向用户发起呼叫，与用户联系。由于用户自行处置宣传信息或不接听我方电话而可能遭受的损失，我公司概不承担责任。用户在本平台注册时，须依注册内容之提示提供用户本人及单位的真实、准确、完整信息，并保证个人及单位资料的及时更新。因用户提供个人及单位信息不准确、不完整或未及时更新而可能遭受的任何损害，本公司不承担任何经济及法律责任。因用户提供个人及单位信息不准确、不完整或未及时更新给本公司造成损失的，本公司保留追偿的权利。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;二&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;个人资料的使用&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 本公司有权为内部经营、管理、统计等目的使用您提供的个人及单位资料，包括但不限于：日常管理本公司提供给会员的服务及产品、监控本网站的安全使用、内部调研、对来访数据进行统计和研究；促进更新供会员享用的服务和产品；确认核对联络和消费名单、为宣传推广目的；为解决争议、排除纠纷和执行本法律声明目的等。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;三&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;个人资料的披露&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 奇化网将采取合理的安全手段保护用户提供的个人及单位信息，在未得到用户许可之前，本平台不会擅自将用户信息披露给任何无关的第三方，但涉及下列情形之一的除外：&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）政府权力机关依照法定程序要求提供。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;2&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）为公共安全之目的。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;3&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）由于用户对自身信息保密不当，从而导致用户资料的泄露。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;4&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）由于网络线路、黑客攻击、计算机病毒等原因造成的资料泄露、丢失、被盗用或被篡改等。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;5&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）为了保护本网站其他用户更为重要的权利或财产。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;6&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;）其他特殊或紧急情况。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;四&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)Cookies&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;技术的使用&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 当用户访问设有&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;Cookies&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;装置的本平台时，本平台服务器会自动发送&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;Cookies&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;至用户浏览器中，同时储存进用户的电脑硬盘内，此&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;Cookies&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;便负责记录日后用户访问本平台时的种种操作、浏览消费习惯、信用记录等。运用&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;Cookies&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;技术，奇化网能够为您提供更加周到的个性化服务。奇化网将运用&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt; Cookies&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;技术向用户提供其感兴趣的信息资料或为其储存密码。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第三条 知识产权声明&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（一）用户承诺，用户对提交或发布的任何内容负责，并且该等内容不得侵犯或违反任何其他方的权利或违反任何法律。用户将该等内容提交或发布在公众或其他用户可访问的平台服务区域内，即表示用户声明，用户是该等资料的所有人并且&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;/&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;或者用户拥有分发该等资料的一切必要权利、许可和授权。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（二）用户承诺，用户在使用平台服务中所提供或产生的内容侵犯第三方合法权益的，用户均应当以自己的名义独立承担所有的法律责任，并应确保奇化网免于承担因此产生的损失或增加的费用。奇化网因此遭受第三方的索赔，或受到任何行政管理部门的处罚，用户应当赔偿奇化网因此造成的损失及发生的费用，包括合理的律师费用。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（三）用户认为奇化网项下的内容涉嫌侵犯其合法权益的，可以向奇化网发送侵权举报。奇化网收到权利人有效的通知后，用户同意奇化网有权根据举报材料的有效性及完整性进行独立判断并采取删除、屏蔽或断开链接等措施。一旦奇化网采取了删除、屏蔽或断开链接等措施的，用户放弃以任何理由向奇化网索赔的权利。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（四）奇化网为提供平台服务而使用的任何软件（包括但不限于软件中所含的任何图象、照片、动画、录像、录音、音乐、文字和附加程序、随附的帮助材料）的一切权利均属于奇化网，未经奇化网书面明示许可，用户不得对该软件进行反向工程、反向编译或反汇编。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（五）除非因使用平台服务而必须，未经奇化网明示书面许可，用户不得使用奇化网的商标（不论是否注册）、企业名称、商业标识、域名等，用户以不得自行或委托他人抢注或申请与奇化网有关的任何知识产权。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（六）奇化网在平台服务中提供的内容（包括但不限于网页、文字、图片、音频、视频、图表等）的知识产权归奇化网所有。用户同意，有关服务（包括但不限于图片、用户界面、声音素材、视频素材、编辑内容和安装有关服务所使用的脚本和软件）包含奇化网和&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;/&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;或其许可方拥有的专有信息和材料，受适用的知识产权法律和其他法律的保护。用户同意，用户不会以任何方式使用该等专有信息或材料，但为了根据本条款使用有关服务的目的而使用的除外。除本条款明确允许的以外，不得以任何形式或任何方式复制有关服务的任何部分。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;（七）用户在使用奇化网过程中形成的使用数据的所有权归奇化网所有，未经奇化网明示书面许可，用户承诺不以任何形式复制、模仿、传播、出版、公布、展示，包括但不限于电子的、机械的、复印的、录音录像的方式和形式等。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;strong&gt;第四条 免责条款&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 任何用户因使用本网站而可能遭致的意外及其造成的损失&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;(&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;包括因下载本网站可能链接到的第三方网站内容而感染电脑病毒&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;，我们对此概不负责，亦不承担任何法律责任。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 用户应对使用奇化网得到的信息结果自行承担风险。奇化网仅作为一个促进化工交易、提供化工信息展示交流的平台，网站会员会自行上传资源信息，我们不对信息内容的安全性、准确性、真实性、合法性负责，也不承担任何法律责任。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;第五条 本公司保留在任何时间自行修改、增删本法律声明中任何内容的权利。您每次登陆或使用本网站时均视为同意受当时有效的声明条款的约束。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;第六条 根据化工流通特性，通过奇化网购买的化工商品不宜退货，不适用无理由退货。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;本法律声明由广东奇化化工交易中心股份有限公司负责解释。&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;&quot;&gt;&amp;nbsp; &lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;', null, null);
INSERT INTO `kw_contents` VALUES ('42', '平台简介', '平台简介', '&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:18px;&quot;&gt;&lt;strong&gt;&lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;1&lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;． 网站（公司）简介：&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;奇化网是响应“供给侧改革”和“互联网&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;+”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;国家战略号召，依托先进的化工现货电子交易平台，致力于为全球从事化工行业的企业提供真实、安全、高效的一站式化工运营服务；公司通过优化结构、打造独具特色的产业价值链，以实现客户成本节约与产业效率提升为己任，立志成为国内外化工领域中，具有强大均衡产业发展能力的化工产业互联网领军企业。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;奇化网致力于打造全球化工产业链整合领导者，率先在中国推出&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;B2P&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;商业模式，以&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;“&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;奇妙化学，一网共享&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;”&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;的全球全产业链资源整合为新的战略目标。奇化团队拥有深厚的产业经验及互联网经验，致力于打造专业的线上线下化工产业互联网模式，挖掘链接全球化工产业资源，打造化工产业链无缝链接平台，引领化工产业全球资源共享，撬动数十万亿的化工产业市场。奇化网的高速发展定会以其独树一帜的战略视角领航化工产业发展新蓝海。&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;网站（公司）基本信息：&lt;/strong&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt; &lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;\n	&lt;table class=&quot;MsoTableGrid&quot; style=&quot;border-collapse:collapse;border:none;&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;1&quot;&gt;\n		&lt;tbody&gt;\n			&lt;tr&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;130&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;中文&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;名&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/b&gt;&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt;&lt;/b&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;397&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;奇化&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;网&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;151&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;外文&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;名&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/b&gt;&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt;&lt;/b&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;293&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;K&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;ey&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;wa&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n			&lt;/tr&gt;\n			&lt;tr&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;130&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;总部&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;地点&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/b&gt;&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt;&lt;/b&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;397&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;广东&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;151&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;成立&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;时间&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/b&gt;&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt;&lt;/b&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;293&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;2013&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;年&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;12&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;月&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;18&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;日&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n			&lt;/tr&gt;\n			&lt;tr&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;130&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;经营&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;范围&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/b&gt;&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt;&lt;/b&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td colspan=&quot;3&quot; style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;841&quot; valign=&quot;top&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;对&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;化&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;工&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;交易&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;市场&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;进行&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;投资，&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;化工&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;电子&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;商务、&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;化工&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;产品&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;研发&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;及&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;交易、&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;化工&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;交易&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;产业&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;链&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;金融&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;服务&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;和&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;股权&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;等&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n			&lt;/tr&gt;\n			&lt;tr&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;130&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;商业&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;模式&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/b&gt;&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt;&lt;/b&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;397&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;首创&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;B2P&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;（&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;Business&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;to Platform&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;）&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;模式&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;151&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;网站&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;性质&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/b&gt;&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt;&lt;/b&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;293&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;化&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;工&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;交易&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;网站&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n			&lt;/tr&gt;\n			&lt;tr&gt;\n				&lt;td style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;130&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;b&gt;&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;官网&lt;/span&gt;&lt;/span&gt;&lt;/b&gt;&lt;b&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt;&lt;/b&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n				&lt;td colspan=&quot;3&quot; style=&quot;border:solid windowtext 1.0pt;&quot; width=&quot;841&quot; valign=&quot;top&quot; align=&quot;center&quot;&gt;\n					&lt;p class=&quot;MsoNormal&quot;&gt;\n						&lt;span style=&quot;font-size:16px;font-family:Microsoft YaHei;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;www.keywa.com&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;font-family:&amp;quot;color:windowtext;&quot;&gt;&lt;/span&gt; \n					&lt;/p&gt;\n				&lt;/td&gt;\n			&lt;/tr&gt;\n		&lt;/tbody&gt;\n	&lt;/table&gt;\n&lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;2. &lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;企业文化&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;公司口号：奇妙化学，一网共享&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp; 愿景：全球化工产业链整合领导者&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;使命：通过化工产业链资源整合，帮助全球化工企业引领行业创新和持续发展，构建 化工产业共享共赢生态&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;核心价值观：&lt;/span&gt;&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;strong&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp;&amp;nbsp; K&lt;/span&gt;&lt;/strong&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;eep Being Creative&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp; &amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;持续创新&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;strong&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp; &amp;nbsp; &amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;E&lt;/span&gt;&lt;/span&gt;&lt;/strong&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;fficiency Priority&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt; &amp;nbsp; &amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;效率优先&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;strong&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;Y&lt;/span&gt;&lt;/span&gt;&lt;/strong&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;oung at Heart&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;赤子之心&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;strong&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp;&amp;nbsp; W&lt;/span&gt;&lt;/strong&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;in Together&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;追求共赢&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;strong&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;A&lt;/span&gt;&lt;/span&gt;&lt;/strong&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;ggregation of Talent&lt;/span&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;s&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;汇聚英才&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;3&lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;．公司荣誉&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 佛山禅城区重点招商项目&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;广东省重点建设项目&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;获得诚信网站认证&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 佛山市电子商务协会副会长单位&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;中国电子商务协会会员单位&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;中国洗涤用品工业协会会员单位&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;4. &lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;网站（公司）特色&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;奇&lt;/span&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;化网将电商云技术与传统化工贸易高效融合，通过自营和联营的交易模式，打通供应链价值，开创了化工产业互联网新模式，同时通过产业链资源整合，真正实现“奇妙化学，一网共享”。&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;5&lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;．商业模式&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;奇化网首创了&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;B to P(Business to Platform)&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;的模式&lt;/span&gt;&lt;span&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;6&lt;/strong&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;strong&gt;．经营产品或服务介绍&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p class=&quot;MsoNormal&quot; align=&quot;left&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;color:#333333;font-family:Microsoft YaHei;font-size:16px;&quot;&gt;奇化网立足于化工产业，以丰富的化工产业经营为基础背景，主要自营和联营模式，经营化工行业原料及产品并向外拓展周边相关行业，覆盖日化、农化、石油化、能源等领域原料及产品，同时奇化对化工交易市场进行投资，化工电子商务、化工产品研发及交易、化工交易产业链金融服务和股权。&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;div align=&quot;center&quot;&gt;\n	&lt;br /&gt;\n&lt;/div&gt;', null, null);
INSERT INTO `kw_contents` VALUES ('43', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('47', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('48', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('46', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('49', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('50', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('51', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('52', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('53', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('54', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('55', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('56', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('59', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('60', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('61', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('62', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('63', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('64', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('65', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('66', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('67', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('68', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('69', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('70', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('71', '网站logo', '网站logo', '/Uploads/Admin/base64/1474879010.png', null, null);
INSERT INTO `kw_contents` VALUES ('165', '网站公告', '关于百草枯相关产品的下架和禁售的通知！', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;尊敬的客户，您好！&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 奇化网按农业部、工业和信息化部、国家质检总局于2012年5月10日发布了第1745号公告要求，从2016年7月1日起，停止百草枯相关产品在奇化平台进行交易，请各位用户严格遵守相关法律法规 ，相互监督，感谢各位配合!&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;以下为通告原文：&lt;/span&gt; \n&lt;/p&gt;\n&lt;p align=&quot;center&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;农业部、工业和信息化部、国家质量监督检验检疫总局公告第1745号&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;span style=&quot;font-size:14px;&quot;&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;为维护人民生命健康安全，确保百草枯安全生产和使用，经研究，决定对百草枯采取限制性管理措施。现将有关事项公告如下：&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt; 一、自本公告发布之日起，停止核准百草枯新增母药生产、制剂加工厂点，停止受理母药和水剂（包括百草枯复配水剂，下同）新增田间试验申请、登记申请及生产许可（包括生产许可证和生产批准文件，下同）申请，停止批准新增百草枯母药和水剂产品的登记和生产许可。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt; 二、自2014年7月1日起，撤销百草枯水剂登记和生产许可、停止生产，保留母药生产企业水剂出口境外使用登记、允许专供出口生产，2016年7月1日停止水剂在国内销售和使用。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt; 三、重新核准标签，变更农药登记证和农药生产批准文件。标签在原有内容基础上增加急救电话等内容，醒目标注警示语。农药登记证和农药生产批准文件在原有内容基础上增加母药生产企业名称等内容。百草枯生产企业应当及时向有关部门申请重新核准标签、变更农药登记证和农药生产批准文件。自2013年1月1日起，未变更的农药登记证和农药生产批准文件不再保留，未使用重新核准标签的产品不得上市，已在市场上流通的原标签产品可以销售至2013年12月31日。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt; 四、各生产企业要严格按照标准生产百草枯产品，添加足量催吐剂、臭味剂、着色剂，确保产品质量。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt; 五、生产企业应当加强百草枯的使用指导及中毒救治等售后服务，鼓励使用小口径包装瓶，鼓励随产品配送必要的医用活性炭等产品。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;div align=&quot;right&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;农业部&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt; 工业和信息化部国家质量监督&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt; 检验检疫总局&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt; &amp;nbsp; 二〇一二年四月二十四日&lt;/span&gt;&lt;br /&gt;\n&lt;/div&gt;\n&lt;br /&gt;\n&lt;p&gt;\n	&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt; &lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;公告原文链接：http://www.moa.gov.cn/govpublic/ZZYGLS/201204/t20120427_2613538.htm&lt;/span&gt; \n&lt;/p&gt;', '1476927448', 'a:3:{s:10:\"createTime\";s:19:\"2016-10-18 11:43:20\";s:7:\"creator\";s:5:\"admin\";s:12:\"creator_role\";N;}');
INSERT INTO `kw_contents` VALUES ('164', '网站公告', '奇化网v2.3版本上线啦！', '&lt;p align=&quot;center&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;奇化网v2.3版本已于10月1号正式上线，更完善的功能，更优质的体验，化工B2P交易平台互联新体验。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p align=&quot;center&quot;&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:16px;color:#333333;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161018/1476761988.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;', '1476860641', 'a:3:{s:10:\"createTime\";s:19:\"2016-10-18 11:36:11\";s:7:\"creator\";s:5:\"admin\";s:12:\"creator_role\";N;}');
INSERT INTO `kw_contents` VALUES ('158', '发展历程', '奇化平台交易突破100亿人民币', '奇化平台交易突破100亿人民币', '1472659200', 'a:4:{s:4:\"date\";s:10:\"2016-09-01\";s:10:\"createTime\";s:19:\"2016-10-13 10:13:35\";s:7:\"creator\";s:5:\"admin\";s:12:\"creator_role\";N;}');
INSERT INTO `kw_contents` VALUES ('159', '发展历程', '奇化网战略升级', '奇化网战略升级', '1446307200', 'a:4:{s:4:\"date\";s:10:\"2015-11-01\";s:10:\"createTime\";s:19:\"2016-10-13 10:14:05\";s:7:\"creator\";s:5:\"admin\";s:12:\"creator_role\";N;}');
INSERT INTO `kw_contents` VALUES ('160', '发展历程', '奇化网（www.keywa.com）成功上线', '奇化网（www.keywa.com）成功上线', '1391184000', 'a:4:{s:4:\"date\";s:10:\"2014-02-01\";s:10:\"createTime\";s:19:\"2016-10-13 10:14:37\";s:7:\"creator\";s:5:\"admin\";s:12:\"creator_role\";N;}');
INSERT INTO `kw_contents` VALUES ('161', '发展历程', '广东奇化化工交易中心股份有限公司成立', '广东奇化化工交易中心股份有限公司成立', '1385827200', 'a:4:{s:4:\"date\";s:10:\"2013-12-01\";s:10:\"createTime\";s:19:\"2016-10-13 10:15:38\";s:7:\"creator\";s:5:\"admin\";s:12:\"creator_role\";N;}');
INSERT INTO `kw_contents` VALUES ('153', '媒体报道', '美国哈佛教授：对中国创新型企业有了颠覆性的认识', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 中国的互联网创新正逐步成为全球关注的热点。8月17-19日，来自哈佛大学、波士顿大学等美国著名高校教授一行来到中国，以调研“创新科技及管理”为课题，参观访问了奇化网、微信、小米、翼龙贷和软通动力共5家中国互联网创新方面的代表性企业，这些企业涵盖产业互联网、通讯、互联网金融等多个不同领域。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 这三位学者分别是：曾任前美国总统吉米·卡特政府高级科技顾问的高科技和风险投资专家、哈佛大学管理和政策博士、波士顿大学系主任巴里·昂格尔博士，大型项目专家、哈佛大学和波士顿大学网络法与国家安全教授金妮博士，曾在美国、东京、香港等地知名大学执教的社会与人格心理学尹咏雅博士。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 参访期间，教授们对各个类型的企业都表示出极大的兴趣。参访结束后，他们对中国创新型企业给予了高度评价与认可，认为让他们大开眼界，对中国及中国企业有了颠覆性的重新认识。教授们甚至认为，中国创新型企业在创业创新方面，平台做得比美国更大、更好，更具想象力。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; “这家企业值得投资”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 美国教授们的第一站是奇化网。这家以“奇妙化学，一网共享”为口号的企业是中国产业互联网的代表。中国化工产业规模占全世界的30%，2015年有15万亿的市场规模，但这个巨大的市场面临整体产能过剩、信息不对称、交易方式落后等诸多问题。奇化网在这种背景下应运而生。与其他B2B的产业互联网不同，奇化网不满足于信息提供、撮合交易等基本的中介服务，而是能够深度参与到产业链条的每一个环节中去，发挥最大的价值。因此奇化网创新性地首创了B2P（Business to Platform）模式。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 奇化网的目标并不仅限中国市场。奇化网的董事总经理蒋博士透露，他们正在全球范围内寻找有价值和有特色的原材料将其推荐给中国广大的制造商，并且未来还会采取包括股权并购在内的多种方式整合全球产业链上的优势资源。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 尽管成立才不到3年，奇化网平台交易数据实现数十亿人民币，十分可观，并且年内有望冲百亿。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 巴里·昂格尔博士认为，根据他几十年高科技和风险投资的经验判断，奇化网非常值得投资，蕴藏巨大的潜力。他对奇化网目前的团队结构、经营理念与企业文化都高度认可。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 金妮博士认为，奇化网的创业与创新模式非常有吸引力，奇化网把企业的行业责任和商业价值很好地结合起来，两者兼顾，令人印象深刻。行业资源的充分整合对企业的可持续发展至关重要。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 尹咏雅博士则称赞蒋博士“对自己正在做的事非常清晰，充满个人魅力。”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; “他们将在国际舞台上发挥更重要的作用”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 17日下午，教授们参观了位于广州的微信总部。腾讯公司旗下的微信是目前中国最受欢迎的聊天软件，以“连接一切”为口号，目前微信已经从单纯的聊天工具变成了一种“智慧型”的生活方式。其已经渗透进医疗、酒店、零售、百货、餐饮、票务、快递、高校、电商、民生等数十个行业。腾讯公司最新的报表显示，微信的月活跃用户已经达到8.06 亿。这个数字令人惊叹。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 美国教授们饶有兴致地参观了微信总部园区、办公区域，并在互动座谈中详细了解了微信发展现状、产品研发、管理模式及企业文化等方面的情况。教授们认为，微信团队有激情有活力，产品不断创新，未来一定会在社会化媒体的国际舞台上发挥更重要的作用。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 8月18日教授们参访了位于北京的小米总部。他们先后参观了小米之家和小米总部总参大楼，并与小米高层进行了长达两个小时的深入交流。小米是一家拥有创新基因的科技公司，创造性地用互联网模式来做手机，被称作是中国智能手机领域最大的创新者。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;巴里·昂格尔博士说，小米最有价值的是它的创始人团队，尤其是雷军，他本人就非常喜欢雷军。相信小米未来在全球布局上仍会有所突破。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;随后，教授们还参观了互联网金融企业翼龙贷和软通动力信息技术(集团)有限公司。翼龙贷主要为三农提供金融服务，教授们认为翼龙贷的模式十分适合在非洲、印度等其他发展中国家推广，对国际社会也有着重要意义。他们还认为软通动力的许多软硬件产品达到了全球领先的水平。美国教授们的参观活动将为他们以后的教学和研究提供鲜活的素材和案例。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:12px;color:#333333;&quot;&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:12px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 免责声明：本文仅代表作者个人观点，与环球网无关。其原创性以及文中陈述文字和内容未经本站证实，对本文以及其中全部或者部分内容、文字的真实性、完整性、及时性本站不作任何保证或承诺，请读者仅作参考，并请自行核实相关内容。 &lt;/span&gt; \n&lt;/p&gt;', '1476927312', 'a:7:{s:3:\"img\";s:36:\"/Uploads/Admin/base64/1474942325.png\";s:10:\"createTime\";s:19:\"2016-09-27 10:12:06\";s:7:\"creator\";s:5:\"admin\";s:12:\"creator_role\";s:15:\"超级管理员\";s:4:\"from\";s:9:\"环球网\";s:4:\"date\";s:10:\"2016-08-24\";s:10:\"updateTime\";s:19:\"2016-10-20 09:35:12\";}');
INSERT INTO `kw_contents` VALUES ('154', '媒体报道', '哈佛教授何以盛赞奇化网、微信、小米等公司?', '&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 中国的互联网创新正逐步成为全球关注的热点。&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 来自哈佛大学、波士顿大学等美国高校的教授近日来华交流。他们此行以调研“创新科技及管理”为课题，参访了奇化网、微信、小米、翼龙贷和软通动力等中国互联网创新的代表企业。这些企业涵盖产业互联网、通讯、互联网金融等多个不同领域。&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 教授们对中国创新型企业给予了高度评价与认可，表示令他们大开眼界，对中国及中国企业有了颠覆性的重新认识。他们甚至认为，中国创新型企业在创业创新方面，平台做得比美国更大更好，更具想象力。&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 参访首站是奇化网。这家以“奇妙化学，一网共享”为口号的企业是中国产业互联网的代表。中国化工产业规模占全世界的30%，但巨大的市场面临整体产能过剩、信息不对称、交易方式落后等诸多问题。在此背景下诞生的奇化网不满足于信息提供、撮合交易等基本的中介服务，而希望能深度参与产业链条的每一个环节，发挥最大的价值。因此奇化网首创了B2P（Business to Platform）模式。&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 奇化网董事总经理蒋博士透露，他们正在全球范围内寻找有价值和有特色的原材料将其推荐给中国广大的制造商，未来还会采取包括股权并购在内的多种方式整合全球产业链上的优势资源。尽管成立不到3年，奇化网平台交易数据已突破数十亿人民币，十分可观，年内有望突破百亿。&lt;/span&gt;\n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;教授们对奇化网目前的团队结构、经营理念与企业文化都高度认可。有教授称，奇化网将企业的行业责任与商业价值很好地结合。凭其几十年高科技和风险投资的经验判断，奇化网潜力巨大，非常值得投资。&lt;/span&gt;&lt;br /&gt;', '1476927353', 'a:7:{s:3:\"img\";s:36:\"/Uploads/Admin/base64/1474942896.png\";s:10:\"createTime\";s:19:\"2016-09-27 10:21:36\";s:7:\"creator\";s:5:\"admin\";s:12:\"creator_role\";s:15:\"超级管理员\";s:4:\"from\";s:9:\"搜狐网\";s:4:\"date\";s:10:\"2016-08-24\";s:10:\"updateTime\";s:19:\"2016-10-20 09:35:53\";}');

-- ----------------------------
-- Table structure for kw_department
-- ----------------------------
DROP TABLE IF EXISTS `kw_department`;
CREATE TABLE `kw_department` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(32) NOT NULL COMMENT '部门名称',
  `parentid` smallint(4) NOT NULL DEFAULT '0' COMMENT '父id',
  `parentidlist` varchar(32) NOT NULL DEFAULT '0' COMMENT '分类的层级关系，从最高级到自己',
  `depth` smallint(4) NOT NULL DEFAULT '1' COMMENT '深度',
  `sort` smallint(4) NOT NULL DEFAULT '0' COMMENT '优先级，越大，同级显示的时候越靠前',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常；2删除',
  PRIMARY KEY (`id`),
  KEY `paretid` (`parentid`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_department
-- ----------------------------
INSERT INTO `kw_department` VALUES ('1', '奇化网', '0', '1', '1', '0', '1');
INSERT INTO `kw_department` VALUES ('13', '测试部门1', '1', '1,13', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('14', '测试部门2', '1', '1,14', '2', '0', '2');
INSERT INTO `kw_department` VALUES ('15', '222', '13', '1,13,15', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('16', '测试部门221', '14', '1,14,16', '3', '0', '2');
INSERT INTO `kw_department` VALUES ('17', '测试部门13', '1', '1,17', '2', '0', '2');
INSERT INTO `kw_department` VALUES ('18', '测试部门33', '17', '1,17,18', '3', '0', '2');
INSERT INTO `kw_department` VALUES ('19', 'ss', '1', '1,19', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('20', 'ss1', '19', '1,19,20', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('21', 'aaaaa', '1', '1,21', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('22', 'rrrr', '15', '1,13,15,22', '4', '0', '0');
INSERT INTO `kw_department` VALUES ('23', '我只是试试看', '15', '1,13,15,23', '4', '0', '0');
INSERT INTO `kw_department` VALUES ('24', '我试试一级的新增', '1', '1,24', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('25', '测试部门333', '13', '1,13,25', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('26', '测试部门1-111', '13', '1,13,26', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('27', 't', '1', '1,27', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('28', '测试部门1-222', '13', '1,13,28', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('29', '测试部门1-333', '13', '1,13,29', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('30', '测试部门1-444', '13', '1,13,30', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('31', '新增一级部门测试', '1', '1,31', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('32', '测试部门1-1', '13', '1,13,32', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('33', '测试部门1-2', '13', '1,13,33', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('34', '333', '1', '1,34', '2', '0', '2');
INSERT INTO `kw_department` VALUES ('35', '5555', '14', '1,14,35', '3', '0', '2');
INSERT INTO `kw_department` VALUES ('36', '666-2', '17', '1,17,36', '3', '0', '2');
INSERT INTO `kw_department` VALUES ('37', '777', '1', '1,37', '2', '0', '2');
INSERT INTO `kw_department` VALUES ('38', '测试2-2-3', '14', '1,14,38', '3', '0', '2');
INSERT INTO `kw_department` VALUES ('39', '888', '1', '1,39', '2', '0', '2');
INSERT INTO `kw_department` VALUES ('40', '33-1', '34', '1,34,40', '3', '0', '2');
INSERT INTO `kw_department` VALUES ('41', '测试部门4', '1', '1,41', '2', '0', '2');
INSERT INTO `kw_department` VALUES ('42', '555', '1', '1,42', '2', '0', '2');
INSERT INTO `kw_department` VALUES ('43', '555-1', '42', '1,42,43', '3', '0', '2');
INSERT INTO `kw_department` VALUES ('44', 'IT技术中心', '1', '1,44', '2', '0', '1');
INSERT INTO `kw_department` VALUES ('45', '前端开发', '44', '1,44,45', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('46', '客服部', '1', '1,46', '2', '0', '1');
INSERT INTO `kw_department` VALUES ('47', '测试2-12', '46', '1,46,47', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('48', '测试3', '1', '1,48', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('49', '测试3-13', '48', '1,48,49', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('50', '测试4', '1', '1,50', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('51', '11', '49', '1,48,49,51', '4', '0', '0');
INSERT INTO `kw_department` VALUES ('52', 'ttt112', '45', '1,44,45,52', '4', '0', '0');
INSERT INTO `kw_department` VALUES ('53', 'a', '1', '1,53', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('54', 'e', '1', '1,54', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('55', '测试331', '1', '1,55', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('56', '测试33-333', '55', '1,55,56', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('57', '4444', '45', '1,44,45,57', '4', '0', '0');
INSERT INTO `kw_department` VALUES ('58', '111111', '45', '1,44,45,58', '4', '0', '0');
INSERT INTO `kw_department` VALUES ('59', '销售二部', '45', '1,44,45,59', '4', '0', '0');
INSERT INTO `kw_department` VALUES ('60', '销售三部', '45', '1,44,45,60', '4', '0', '0');
INSERT INTO `kw_department` VALUES ('61', '销售4部', '44', '1,44,61', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('62', '销售5部', '44', '1,44,62', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('63', '营销一部', '46', '1,46,63', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('64', '营销二部', '46', '1,46,64', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('65', '技术开发部', '1', '1,65', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('66', '技术一部', '65', '1,65,66', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('67', '技术二部', '65', '1,65,67', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('68', '技术三部', '65', '1,65,68', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('69', '销售四部', '44', '1,44,69', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('70', '销售二部', '44', '1,44,70', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('71', '销售2部', '44', '1,44,71', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('72', '销售3部', '45', '1,44,45,72', '4', '0', '0');
INSERT INTO `kw_department` VALUES ('73', 'IT技术中心', '1', '1,73', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('74', '技术开发部', '73', '1,73,74', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('75', '产品规划部', '73', '1,73,75', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('76', '用户体验部', '73', '1,73,76', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('77', '产品', '73', '1,73,77', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('78', '设计', '73', '1,73,78', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('79', '客服部', '1', '1,79', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('80', '客服中心', '1', '1,80', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('81', '财务中心', '1', '1,81', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('82', '666666', '1', '1,82', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('83', '测试', '1', '1,83', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('84', '测试1', '83', '1,83,84', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('85', '战略发展中心', '1', '1,85', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('86', 'PHP', '44', '1,44,86', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('87', '软件测试', '44', '1,44,87', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('88', '客服主管', '46', '1,46,88', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('89', '运营', '44', '1,44,89', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('90', 'It', '1', '1,90', '2', '0', '0');
INSERT INTO `kw_department` VALUES ('91', '财务中心', '44', '1,44,91', '3', '0', '0');
INSERT INTO `kw_department` VALUES ('92', '财务中心', '1', '1,92', '2', '0', '1');

-- ----------------------------
-- Table structure for kw_group_access
-- ----------------------------
DROP TABLE IF EXISTS `kw_group_access`;
CREATE TABLE `kw_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_group_access
-- ----------------------------
INSERT INTO `kw_group_access` VALUES ('1', '1');

-- ----------------------------
-- Table structure for kw_group_department
-- ----------------------------
DROP TABLE IF EXISTS `kw_group_department`;
CREATE TABLE `kw_group_department` (
  `gid` mediumint(8) unsigned NOT NULL COMMENT '关联组id',
  `did` mediumint(8) unsigned NOT NULL COMMENT '关联部门id',
  UNIQUE KEY `gid_did` (`gid`,`did`),
  KEY `gid` (`gid`),
  KEY `did` (`did`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_group_department
-- ----------------------------
INSERT INTO `kw_group_department` VALUES ('1', '1');
INSERT INTO `kw_group_department` VALUES ('8', '45');
INSERT INTO `kw_group_department` VALUES ('9', '86');
INSERT INTO `kw_group_department` VALUES ('11', '1');
INSERT INTO `kw_group_department` VALUES ('12', '45');
INSERT INTO `kw_group_department` VALUES ('13', '44');
INSERT INTO `kw_group_department` VALUES ('14', '45');
INSERT INTO `kw_group_department` VALUES ('15', '1');
INSERT INTO `kw_group_department` VALUES ('16', '87');
INSERT INTO `kw_group_department` VALUES ('17', '73');
INSERT INTO `kw_group_department` VALUES ('18', '46');
INSERT INTO `kw_group_department` VALUES ('19', '46');
INSERT INTO `kw_group_department` VALUES ('20', '46');
INSERT INTO `kw_group_department` VALUES ('21', '44');
INSERT INTO `kw_group_department` VALUES ('22', '1');
INSERT INTO `kw_group_department` VALUES ('23', '46');
INSERT INTO `kw_group_department` VALUES ('24', '44');
INSERT INTO `kw_group_department` VALUES ('25', '44');
INSERT INTO `kw_group_department` VALUES ('26', '44');
INSERT INTO `kw_group_department` VALUES ('27', '44');

-- ----------------------------
-- Table structure for kw_member
-- ----------------------------
DROP TABLE IF EXISTS `kw_member`;
CREATE TABLE `kw_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '用户密码',
  `phone` varchar(20) NOT NULL COMMENT '联系电话',
  `img` varchar(255) DEFAULT NULL COMMENT '图像路径',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱路径',
  `addTime` int(11) NOT NULL COMMENT '添加时间（时间戳）',
  `lastLoginIp` varchar(15) DEFAULT NULL COMMENT '最后登入IP',
  `lastLoginTime` int(11) DEFAULT NULL COMMENT '最后登入时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0,删除，1,正常',
  `state` tinyint(3) DEFAULT '2' COMMENT '1,验证通过，2验证未通过',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of kw_member
-- ----------------------------

-- ----------------------------
-- Table structure for kw_memberinfo
-- ----------------------------
DROP TABLE IF EXISTS `kw_memberinfo`;
CREATE TABLE `kw_memberinfo` (
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `companyName` varchar(255) DEFAULT NULL COMMENT '公司名称',
  `contact` varchar(255) DEFAULT NULL COMMENT '联系人',
  `model` tinyint(4) DEFAULT NULL COMMENT '经营模式(id)',
  `establishmentDate` int(11) DEFAULT NULL COMMENT '成立时间',
  `employee` tinyint(4) DEFAULT NULL COMMENT '单位人数(id)',
  `trade` tinyint(4) DEFAULT NULL COMMENT '所在（id）',
  `property` tinyint(4) DEFAULT NULL COMMENT '单位性质(id)',
  `businessTerm` int(11) DEFAULT NULL COMMENT '营业期限（时间戳）',
  `turnover` tinyint(4) DEFAULT NULL COMMENT '年营业额(id)',
  `businessScope` varchar(255) DEFAULT NULL COMMENT '经营范围',
  `companyIntroduct` text COMMENT '公司介绍',
  `other` varchar(255) DEFAULT NULL COMMENT 'tel,fax,zip,area,address',
  `cert` varchar(255) DEFAULT NULL COMMENT '证件类型等信息',
  `status` tinyint(2) DEFAULT '1' COMMENT '1,正常，0删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_memberinfo
-- ----------------------------

-- ----------------------------
-- Table structure for kw_members
-- ----------------------------
DROP TABLE IF EXISTS `kw_members`;
CREATE TABLE `kw_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(120) NOT NULL,
  `passwd` char(32) NOT NULL,
  `phone` char(11) NOT NULL,
  `img` varchar(64) DEFAULT NULL COMMENT '头像',
  `email` varchar(30) DEFAULT NULL,
  `addTime` int(11) NOT NULL COMMENT '注册日期',
  `lastLoginIp` char(15) DEFAULT NULL,
  `lastLoginTime` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_members
-- ----------------------------
INSERT INTO `kw_members` VALUES ('1', '奇化化工', '123456', '123456', null, 'qqq', '1213131444', '1111', '1111', '1');

-- ----------------------------
-- Table structure for kw_member_sign
-- ----------------------------
DROP TABLE IF EXISTS `kw_member_sign`;
CREATE TABLE `kw_member_sign` (
  `uid` int(11) unsigned NOT NULL,
  `code` varchar(32) DEFAULT NULL COMMENT '合同编号',
  `cooperation` smallint(2) unsigned DEFAULT NULL COMMENT '合作年度',
  `contractTime` int(11) unsigned NOT NULL COMMENT '签约时间',
  `expireTime` int(11) unsigned NOT NULL COMMENT '到期时间',
  `signatory` varchar(32) NOT NULL,
  `addTime` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updateTime` int(11) unsigned DEFAULT NULL COMMENT '修改时间',
  `state` tinyint(1) NOT NULL DEFAULT '2',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `content` varchar(1024) DEFAULT NULL COMMENT '内容',
  `attachment` varchar(255) DEFAULT NULL COMMENT '附件序列化'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_member_sign
-- ----------------------------

-- ----------------------------
-- Table structure for kw_offer
-- ----------------------------
DROP TABLE IF EXISTS `kw_offer`;
CREATE TABLE `kw_offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offer` varchar(255) NOT NULL COMMENT '职务',
  `area` varchar(255) NOT NULL COMMENT '工作地点',
  `number` int(11) NOT NULL COMMENT '人数',
  `createTime` int(11) NOT NULL COMMENT '创建时间',
  `creator` varchar(60) NOT NULL COMMENT '创建人',
  `creator_role` varchar(60) NOT NULL COMMENT '创建人角色',
  `content` longtext NOT NULL COMMENT '详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of kw_offer
-- ----------------------------
INSERT INTO `kw_offer` VALUES ('64', '销售中心-日化/石化原料销售主管/工程师', '广州市', '3', '1475912490', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.根据年度销售目标，实施销售计划，协作努力，达成销售目标；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.拜访、联络目标客户，组织谈判和订立贸易合同；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.负责完善、管理和创新业务模式，实施平台化战略；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.协同客服部门，建立并发展新客户关系；向客户传递价值，保证客户满意；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.负责货款回收，负责应收账款追收；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.保证团队高效的业务活动，合理使用预算经费；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;7.完成销售经理指派的其他工作任务。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：大专以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：化学，化工相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.工作经验：日化原料销售/日化原料研发1年以上。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('65', '销售中心-日化/石化原料销售经理 ', '广州市', '2', '1475912496', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.制定年度销售目标，销售计划，营销方案，组织实施，指导、培训、激励和监督团队，协作努力，达成销售目标；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.负责建设，推广和管理销售网络，开发新客户；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.负责完善、管理和创新业务模式，实施平台化战略；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.负责策划指导销售团队成员拜访、联络目标客户，组织谈判和订立贸易合同，指导销售业务员协同客服部门，建立并发展新客户关系；向客户传递价值，保证客户满意；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.负责货款回收，负责应收账款追收；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.保证团队高效的业务活动，合理使用预算经费；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;7.完成销售总经理指派的其他工作任务。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：大专以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：化学，化工相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.工作经验：日化原料销售/日化原料研发5年以上，并有管理经验1年以上。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('58', '风控部-风控经理', '佛山市', '1', '1475912448', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.参与审核、监控公司的经营发展活动，预测、发现、审计、调查、界定可能出现的风险，提出有效的措施管控风险，向公司决策者提出及时合理性建议，保证公司经营发展的顺利进行；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.收集分析外部政经资讯，预测公司发展前景可能的战略性风险，提出并组织落实有效管控方案，保证公司发展规划顺利实施或得到及时合理的调整；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.制定并完善公司的风控管理制度，信用管理制度，审计制度；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.监督或专业指导其他职能部门的工作，接受其他管理部门的监督和专业指导。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.完成上级交办的其他工作任务。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：本科以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：法律相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.经验：7年以上企业法务工作经验，3年以上同类型岗位工作经验，具备丰富的风险监控经验。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;');
INSERT INTO `kw_offer` VALUES ('59', '技术开发部-性能测试工程师', '广州市', '1', '1475912457', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.负责网站的性能测试以及安全测试工作，包括分析性能需求、制定测试计划、设计测试用例、编写测试脚本、执行测试、定位性能瓶颈、测试结果评估等；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.发生性能瓶颈时能够快速准确地定位问题并提出优化建议；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.对性能监视数据进行分析并提交分析报告；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.负责公司产品的web应用及app安全测试，发现安全漏洞，提出技术解决方案，并协助开发人员修复完成。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：大专以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：计算机相关专业：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.熟悉性能测试理论，流程及其各项规范，精通测试用例设计，并有一定的文档撰写能力；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.掌握基础编程能力，至少熟悉一门主流数据库。如Oracle、MySQL等；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.熟练使用LoadRunner或者JMeter进行性能测试，具备独立编写脚本能力；有大型系统性能测试和调优经验者优先；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.熟悉常见Web漏洞及其攻击技术，比如SQL注入、XSS、代码执行、文件包含等；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.具备严谨的逻辑能力，一定的问题分析和解决能力；工作认真负责，学习能力强，喜欢在技术领域内钻研；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;7.三年以上工作经验，至少具有两年以上性能测试工作经验。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('60', '技术开发部-PHP开发工程师', '广州市', '2', '1475912462', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.负责网站后端接口开发和维护，遵照开发规范，按时保质的完成负责开发任务；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.参与需求分析，产品设计，功能开发等；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.负责后端数据库设计，负责后端模块业务功能开发；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.与外部系统对接以及数据分析工作；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.对开发与维护过程中的技术问题进行攻关。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：大专以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：计算机或相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.精通PHP+MYSQL编程以及主流的PHP框架及开源项目进行二次开发，可利用框架单独进行项目开发，熟悉面向对象编程及MVC模式；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.熟练掌握基于mvc架构的php框架，编写过框架的优先考虑；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.熟练掌握XHTML、CSS、DIV、Javascript、jquery等页面技术；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.有良好文档编写能力和编程风格，熟悉开发文档的编写；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.熟悉大型网站架构，具有大数据大流量 web 应用开发经验者优先；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;7.熟悉Linux下服务器环境部署和性能调优，对数据库优化，PHP缓存技术，静态化设计，高并发，数据库安全等方面有自己的见解；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;8.2年以上php后端开发经验。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('61', '技术开发部-前端开发工程师', '广州市', '2', '1475912469', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.负责系统前端开发工作，协调界面设计师和开发人员的工作；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.优化网站前端功能设计，解决各种浏览器的兼容性问题&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.JavaScript程序模块开发，通用类库、框架编写；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.Web前端表现层及与后端交互的设计和开发；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.通过各种前端技术手段，提升交互效果和用户体验并满足性能要求。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：大专以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：计算机或相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.熟悉精通前端网页技术HTML/CSS，熟悉了解兼容样式，熟练使用XHTML、CSS编写页面；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.熟悉HTML DOM对象的JavaScript编程，熟悉对象化JavaScript编程，熟悉AJAX等交互流程；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.有使用js框架经验，了解不同浏览器之间的差异，代码具有良好的兼容性，规范性；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.熟悉服务器端编程语言，如php、python等优先；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.2年以上前端开发经验，并提供相关产品案例。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('62', '技术开发部-DBA数据工程师', '广州市', '2', '1475912475', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.向开发人员提供技术支持，查询数据，协助优化SQL语句，部署测试环境；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.负责数据库系统的架构设计、优化、对数据库整体架构提出建议，制定数据库监控策略，备份策略，容灾策略；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.数据库的日常管理维护，安装、数据库备份、性能优化、日志分析、存储管理、监控等，解决突发问题和疑难问题；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.据项目开发需求，进行数据库设计，包括SQL优化，指导以及配合开发工程师进行开发工作；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.配合研发指定数据库技术方案，分库分表策略，数据迁移方案。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：大专以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：计算机相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.经验：三年以上mysql dba实际工作经验；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.精通MySQL、SQL server数据库，精通SQL脚本编写，有丰富数据库管理、运维调优经验；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.熟悉数据库集群、熟悉MySQL主从复制，主主复制，熟悉No-SQL技术（Redis、MongoDB、Hbase、Memcached等），了解相关高可用技术方案；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.具有数据库备份，数据库还原工作经验；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;7.熟悉MySQL相关监控、分析、开发和管理工具；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;8.熟悉linux/windows操作系统，具有简单的shell脚本编写能力。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('63', '销售中心-销售助理', '广州市', '1', '1475912480', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.负责根据公司的战略规划和部门分配的任务进行按季度、按月份分解目标，并及时、有效的完成销售任务。 &lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.负责根据市场发展和客户需求，及时准确地提交销售预测，确定年度、季节的主推产品，制作不同大客户和主推产品的专案，并根据市场状况及时进行调整。 &lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.负责库存控制，通过提高销售预测的准确性和积极拓展市场来提高库存管理。 &lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.严格执行公司价格政策，确保产品销售的利润水平。 &lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.根据公司新产品推广计划，策划具体的销售方案，积极开拓新产品的市场。 &lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.负责产品销售后货款的跟催与统计工作。 &lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：大专以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：化学，化工相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.工作经验：石化原料销售/日化原料研发1年以上。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('51', '战略资讯部-信息管理主管', '佛山市', '1', '1475912397', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:SimSun;font-size:14px;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;制订并完善企业信息管理制度（包括企业信息保密制度）。执行并监督执行相关制度&lt;/span&gt;&lt;br /&gt;\n&lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-family:SimSun;font-size:14px;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.开拓&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;，维护，管理企业内外的获取和发布信息的渠道。&lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:KaiTi_GB2312;font-size:14px;color:#333333;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.指导、培训监督评估公司&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;各职能部门和子公司的获取、使用、生产和管理专业信息的能力和效果。&lt;/span&gt;&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.负责汇集管理和保存公司的信息资源。负责规划并指导发展公司信息资源。优化公司信息流系统（确保信息资源得到充分、合理的利用。）&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#333333;&quot;&gt;&lt;span style=&quot;font-size:14px;color:#666666;&quot;&gt;5.根据授权，负责编译、&lt;/span&gt;&lt;span style=&quot;font-size:14px;color:#666666;&quot;&gt;并在合适的媒质发布已经审核的信息。&lt;/span&gt;&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.向战略发展中心总监汇报工作，接受其工作支持、指导、培训、监督和工作考核。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;7.完成上级交办的其他工作任务。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;8.支持，指导、培训、监督和工作考核资讯专员的工作。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;9.接受其他职能部门的专业指导和监督。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：本科以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：情报学、信息管理、企业管理专业优先；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.经验：相关岗位2年以上工作经验。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;&lt;br /&gt;');
INSERT INTO `kw_offer` VALUES ('52', '战略资讯部-战略研究主管', '佛山市', '1', '1475912406', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.收集、使用、保存公司的战略发展信息；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2. 定期监测经营发展的战略环境，及时发现潜在的战略风险/机会，编制战略环境报告；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3. 分析公司的经营数据，监测公司经营与发展战略的一致性；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4. 参与公司年度经营发展目标的制定；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5. 对有关战略发展项目进行调查分析，编制可行性调查报告；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6. 监督投资项目的经营状况，评估投资项目的战略一致性；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;7. 指导并监督公司各职能部门制定合适的战略、策略和政策；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;8. 依照公司相关制度，做好战略信息的保密工作；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;9. 执行该岗位相关的其他有关职责。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：本科以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：金融、经济学相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.经验：&lt;/span&gt;&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2年以上在证券投行工作的相关经验。&lt;/span&gt;&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('53', '投资发展部-投融资主管', '佛山市', '1', '1475912411', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.参与制订并执行本部门相关的管理制度和流程；建立完善项目投资管理，包括尽职调查、合同谈判、项目立项、项目筹建和验收交接以及后期投资评估等工作的管理制度和流程规范。&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.参与制定本部门的年度、月度工作计划。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.负责落实公司决定的投资项目的准备、实施和管理等工作。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.建立并分类管理公司投资项目档案，跟踪并评估投资效果。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.参与对外投融资合作的联络和谈判。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.制订和执行公司投资融资管理制度和流程。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;7.负责公司战略投资机密信息的保密管理工作。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;8.接受投资发展部经理的工作指导、支持、培训、监督和考评。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;9.完成上级交办的其他工作任务。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：本科以上学历，&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：经济、财务、金融、投资等相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.熟悉投融资管理；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.具备较强的人际沟通和分析能力；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.三年以上相关企业、投资银行、咨询公司投资部门工作经验。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('54', '品牌市场部-媒介管理', '佛山市', '1', '1475912416', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.负责规划、发展、管理和运用公共关系；协调配合市场推广、品牌推广、战略投资等工作，树立企业形象，为企业发展和品牌建设创造优越社会环境。&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.结合公司的发展规划、营销战略规划、品牌建设规划，制订公关工作的长期战略规划。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.结合部门年度目标年度计划，制订年度公关目标、公关工作计划，编制公关预算；与市场推广、品牌推广、战略投资计划实施等同步，制订公关方案实施公关工作；评估公关效果并进行有效控制。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.制定管理制度和业务流程，规范公关工作，有效管理规避公关危机。&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.接受品牌市场总监的工作指导、支持、培训、监督和考评，完成品牌市场总监交办的其他工作任务。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：本科以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：新闻、广告、市场营销或公关传播等相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.3年以上相关工作经验，有大型公关公司或媒体工作经验背景者优先&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.具有出色的媒介公关策略和优秀的表达力及整合传播技巧，较强的文笔、提案能力和沟通技巧，熟悉商务谈判、公关活动；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.思维敏捷、善于沟通，亲和力强，形象气质佳，良好人际交往与沟通能力，具有良好的职业素养；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.有很强的资源开拓和整合能力，具备开放创新的商业思维；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;7.成功操作过知名公关项目媒介传播内容及企业媒介公关管理，熟悉社会化媒体环境。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('55', '品牌市场部-平台推广', '佛山市', '1', '1475912422', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.根据整体运营计划制定网站推广目标和网络营销计划；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.利用各种互联网资源提高公司网站访问量、注册量及传播效果；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.负责网站流量目标的完成，包括独立IP数、UV、PV等指标完成；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.策划线上、线下推广活动方案，并加以实施，达成推广目标；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.善于运用互联网资源，支持公司开展的线下营销活动；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.及时收集推广反馈数据，及时提出切实可行的改进方案；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;7.负责维护和发展网站推广所需的各种线上、线下资源。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：本科以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：电子商务、传播学、计算机相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.熟悉竞价排名、搜索引擎优化、广告联盟等推广渠道；对资源共享、客户流量转化等关键信息敏感；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.熟练掌握软文、交换链接、邮件推广、SNS推广、论坛推广等推广方式；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.2.两年以上网络营销推广经验，有行业推广资源或大型网站工作经验优先。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');
INSERT INTO `kw_offer` VALUES ('56', '品牌市场部-SEO', '佛山市', '1', '1475912429', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.全面负责公司网站SEO工作规划，制定SEO策略，建立SEO标准，对SEO效果负责；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.负责公司网站面向百度、搜狗、360的自然结果排名优化，提高网站流量；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.精通主流搜索引擎优化排名提升技巧，包括站内优化、站外优化及内外部链接优化、关键词优化、代码优化、图片优化，了解白帽黑帽手法的差异和尺度；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.懂得以结果为导向，做好网站在baidu、搜狗、360收录和排名数据监控与分析，能够定期形成阶段性分析报告，并提出后续的优化方案；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.负责网站SEO、各种流量推广方案的执行与跟踪、监测、分析，不断优化提升整站流量。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：本科以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：市场营销、电子商务、计算机相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.精通SEO优化原理及策略，熟练使用SEO优化工具和技术，有较丰富的实际操作经验；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.具备扎实的数据分析能力，懂得网站异常数据的监控及异常排查、分析、解决能力，尤其是针对网站流量下降，能够快速排查、分析出合理的原因并制定对应的解决方案；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.懂得pc端与移动端的SEO优化差异；具备SEO项目管理能力，制定可行性的SEO优化方案；具有较强的业务分析能力，善于借助SEO分析为其他部门提供改进建议；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.2年以上的SEO优化经验，具备大型网站的SEO实战经验，有成功案例者优先。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;&lt;br /&gt;');
INSERT INTO `kw_offer` VALUES ('57', '风控部-审计主管 ', '佛山市', '1', '1475912440', 'admin', '超级管理员', '&lt;p&gt;\n	&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.规划审计部的自身发展，全面负责部门内部的日常事务管理；&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.负责组织编制年度审计工作计划，并对实际完成情况进行检查、总结；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.负责组织制定、修改和更新公司的审计规范和管理制度，并监督有关规章制度实施；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.负责开展常规审计（包括但不限于：销售与收款、采购与付款、固定资产管理、资金管理、投融资管理等方面的审计）以及各类专项审计；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;5.负责协助外部审计开展工作；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;6.完成上级交办的其它工作任务。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;任职资格：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;1.学历：本科以上；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;2.专业：财务、审计相关专业；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;3.经验：2年以上财务、审计工作经验，熟悉有关的法律，法规和公司的规章制度；&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;4.其它：组织协调能力强，文字和口头表达能力强。&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;简历手机邮箱：hr@keywa.com&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-family:Microsoft YaHei;font-size:14px;color:#666666;&quot;&gt;联系电话：0757-63813458&lt;/span&gt;');

-- ----------------------------
-- Table structure for kw_partner
-- ----------------------------
DROP TABLE IF EXISTS `kw_partner`;
CREATE TABLE `kw_partner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL COMMENT '类型，0表示合作伙伴，1表示帮助中心',
  `parent_id` int(11) NOT NULL,
  `depth` tinyint(4) NOT NULL,
  `path` varchar(30) NOT NULL,
  `text` varchar(60) NOT NULL,
  `content` varchar(10000) DEFAULT NULL,
  `createTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of kw_partner
-- ----------------------------
INSERT INTO `kw_partner` VALUES ('1', '0', '0', '1', '0', '合作伙伴', '{\"type\": 1}', null);
INSERT INTO `kw_partner` VALUES ('4', '1', '0', '1', '4', '帮助中心', '254', null);
INSERT INTO `kw_partner` VALUES ('143', '0', '1', '2', '1,143', '安徽安特', '/Uploads/Admin/base64/1477034681.jpeg', '1477034681');
INSERT INTO `kw_partner` VALUES ('141', '0', '1', '2', '1,141', '方大集团', '/Uploads/Admin/base64/1477034620.jpeg', '1477034620');
INSERT INTO `kw_partner` VALUES ('142', '0', '1', '2', '1,142', '犇星', '/Uploads/Admin/base64/1477034657.jpeg', '1477034657');
INSERT INTO `kw_partner` VALUES ('95', '1', '4', '2', '95', '我要买化工品', null, '1472615752');
INSERT INTO `kw_partner` VALUES ('96', '1', '4', '2', '96', '我要卖化工品', null, '1472615758');
INSERT INTO `kw_partner` VALUES ('97', '1', '95', '3', '95,97', '如何找货', '&lt;span style=&quot;font-size:16px;&quot;&gt;在奇化网，您可以通过以下6个方式进行找货：&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;01.在网站顶部的搜索框内输入所要寻找的产品名称（或简称），点击“搜索”按钮&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161117/1479365640.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;02.在导航栏处点击“商城”，可通过条件筛选出商品，也可在商品列表页上浏览查看全部商品&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161117/1479369670.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;03.在网站顶部搜索框处选择“抢购”，然后在搜索框内输入所要寻找的产品名称（或简称），点击“搜索”按钮&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161117/1479365661.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;04.在导航栏处点击“抢购”，可通过条件筛选出团购商品，也可在商品列表页上浏览查看全部团购商品&lt;br /&gt;\n&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479718664.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;05.通过网站首页左侧的分类栏来进行找货，将鼠标移入您所要的商品所在的大类中，网站会显示出该类目下的二级商品名称，然后点选您所需的商品就可以了&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161117/1479365681.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;06.在首页的商品分类层中直接查看商品列表，选中您所需的商品&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;img src=&quot;/Uploads/Admin/20161117/1479365691.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;/span&gt;&lt;/strong&gt;&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;', '1472615771');
INSERT INTO `kw_partner` VALUES ('137', '0', '1', '2', '1,137', '中铝', '/Uploads/Admin/base64/1477034533.jpeg', '1477034533');
INSERT INTO `kw_partner` VALUES ('138', '0', '1', '2', '1,138', '中轻工', '/Uploads/Admin/base64/1477034544.jpeg', '1477034544');
INSERT INTO `kw_partner` VALUES ('139', '0', '1', '2', '1,139', 'KLK OLEO', '/Uploads/Admin/base64/1477034558.jpeg', '1477034558');
INSERT INTO `kw_partner` VALUES ('136', '0', '1', '2', '1,136', '琪优势', '/Uploads/Admin/base64/1477034511.jpeg', '1477034511');
INSERT INTO `kw_partner` VALUES ('134', '0', '1', '2', '1,134', '中石化', '/Uploads/Admin/base64/1477034837.jpeg', '1477034436');
INSERT INTO `kw_partner` VALUES ('135', '0', '1', '2', '1,135', '轻工浪奇', '/Uploads/Admin/base64/1477034460.jpeg', '1477034460');
INSERT INTO `kw_partner` VALUES ('98', '1', '96', '3', '96,98', '我要开店', '&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一、商家完成企业认证后，进入我的奇化网&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479698075.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤二、在卖家中心点击“供应商入驻”，仔细阅读后拨打客服热线联系网站工作人员，即可商谈合作事宜&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479698083.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;\n&lt;/p&gt;', '1472615815');
INSERT INTO `kw_partner` VALUES ('140', '0', '1', '2', '1,140', 'MIT', '/Uploads/Admin/base64/1477034570.jpeg', '1477034570');
INSERT INTO `kw_partner` VALUES ('100', '1', '95', '3', '95,100', '购买商品', '&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一、商家完成企业认证后，找到您所需的某个商品，点击进入商品的详情页，选择购买数量，点击“加入购物车”或“立即购买”&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479699031.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤二、进入购物车页面中勾选商品后点击“结算”按钮进行订单结算&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479699040.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤三、点击“结算”后进入核对订单信息页面，在此可以填写收货信息、进行核对订单等操作，最后点击“提交订单”按钮，完成购物&lt;/span&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479699047.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;\n&lt;/p&gt;', '1474279214');
INSERT INTO `kw_partner` VALUES ('101', '1', '95', '3', '95,101', '查询订单', '&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一、首先在网站的顶部栏里点击“我的奇化网”进入会员首页&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479718088.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤二、点击“买家中心”进入我的订单，在订单列表中可以精确查询或按条件筛选出需要的订单信息、查看订单信息，也可点击查看“订单详情”或“取消订单”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479718095.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;', '1474279265');
INSERT INTO `kw_partner` VALUES ('102', '1', '95', '3', '95,102', '付款/收货', '&lt;span style=&quot;font-size:16px;&quot;&gt;以合同约定为“先货后款”为例：&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一、购买商品后，进入“我的奇化网”中&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479717078.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤二、点击“买家中心”进入我的订单，可以在订单中搜索查询需要的订单信息，当卖家修改订单后，您确认订单信息无误即可点击“确认订单”按钮并等待卖家发货，若有异议可点击“继续协商”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479717085.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤三、当您收到货后，可点击“确认收货信息”按钮，若有异议则点击“退回发货信息”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479717092.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤四、当您收到货并付款后可点击“登记付款信息”，并按弹框提示填入正确信息，待卖方确认收款后即交易完成。&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479717098.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;', '1474279279');
INSERT INTO `kw_partner` VALUES ('103', '1', '96', '3', '96,103', '发布商品', '&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一、开店成功的商家，可进入“我的奇化网”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710021.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤二、点击“卖家中心”进入“商品仓库”，然后点击“添加新商品”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710030.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤三、 选择商品分类，根据页面的操作提示填写商品详情，待工作人员审核通过后即可发布商城销售或抢购了&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710046.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710058.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710063.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-size:16px;&quot;&gt;发布商城销售的方法一：&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤四、商品仓库审核通过后即可在有效栏中点击“发布商城销售”，根据页面的操作提示填写销售信息，点击“发布”，待审核通过后商品就会出现在商城页中&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710079.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710084.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710090.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-size:16px;&quot;&gt;发布商城销售的方法二、&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤五、商品仓库审核通过后也可在卖家中心的商城销售中点击“发布销售”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710101.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤六、可在搜索框中填写需要的信息，点击“搜索”，然后选择要发布的商品，点击“下一步”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710118.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤七、根据页面的操作提示填写销售信息，点击“发布”，待审核通过后商品就会出现在奇化网的商城页中&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710138.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710148.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;/span&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;发布抢购活动的方法一：&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤八、商品仓库审核通过后即可在有效栏中点击“发布抢购活动”，根据页面的操作提示填写抢购信息，点击“发布”，待审核通过后商品就会出现在奇化网的抢购页中&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710209.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710222.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710227.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-size:16px;&quot;&gt;发布商城抢购的方法二：&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤九、商品仓库审核通过后也可在卖家中心的商城销售中点击“发布抢购”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710330.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤十、可在搜索框中填写需要的信息，点击“搜索”，然后选择要发布的抢购商品，点击“下一步”&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710339.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤十一、根据页面的操作提示填写抢购信息，点击“发布”，待审核通过后商品就会出现在奇化网的抢购页中&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710352.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710365.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;', '1474279454');
INSERT INTO `kw_partner` VALUES ('104', '1', '96', '3', '96,104', '管理订单', '&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一、首先在网站的顶部栏里点击“我的奇化网”进入会员首页&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479717681.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤二、点击“卖家中心”进入“订单管理”，在订单列表中可以精确查询或按条件筛选出需要的订单信息、查看订单详情、修改或取消订单&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479717690.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;', '1474279797');
INSERT INTO `kw_partner` VALUES ('105', '1', '96', '3', '96,105', '收款/发货', '&lt;span style=&quot;font-size:16px;&quot;&gt;以合同约定为“先货后款”为例：&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一、登录状态下，在网站的顶部栏里点击“我的奇化网”进入会员首页&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479716882.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤二、点击“卖家中心”进入“订单管理”，当买家下单后，您可点击“修改订单”对订单的价格数量等进行修改&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479716890.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤三、如果订单需要取消，点击“取消订单”并填写原因后，待买家确认即可&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479716897.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤四、买家确认订单后，当您线下发货完毕即可点击“登记发货信息”并按弹框提示填入正确信息，等待买方收货和付款&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479716904.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤五、收到买家的货款后您可点击“确认收款信息”按钮完成交易，若有异议则点击“退回付款信息”，待买家重新确认付款后完成交易&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479716911.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;', '1474281613');
INSERT INTO `kw_partner` VALUES ('106', '1', '4', '2', '106', '常见问题', null, '1474281644');
INSERT INTO `kw_partner` VALUES ('107', '1', '106', '3', '106,107', '会员注册', '&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一、打开奇化网首页，在左上方点击“免费注册”，进入会员注册界面&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710934.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤二、按页面的操作提示，输入正确的信息，提交完成注册&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479710941.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;', '1474281702');
INSERT INTO `kw_partner` VALUES ('108', '1', '106', '3', '106,108', '资料修改', '&lt;span style=&quot;font-size:16px;&quot;&gt;买家和卖家可对自己注册的资料、信息在一定范围内做出修改，步骤如下：&lt;/span&gt;&lt;br /&gt;\n&lt;span style=&quot;font-size:16px;&quot;&gt;对于账户管理的资料修改如下：&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一、首先在网站的顶部栏里点击“我的奇化网”进入会员首页&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479711744.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤二、在账户管理的基本资料中，点击“编辑”按钮，修改后重新保存。注意：基本资料修改后需要重新进行企业认证&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479711753.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;span style=&quot;font-size:16px;&quot;&gt;对于账户安全的资料修改如下&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一、点击账户安全，根据您的需要按照页面上的提示信息完成内容的录入完成修改&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479711784.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;', '1474281749');
INSERT INTO `kw_partner` VALUES ('109', '1', '106', '3', '106,109', '企业认证', '&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;商家完成会员注册后，如需在奇化网购买化工原料、销售化工原料、发布促销活动、申请奇化网金融服务等，均需完成企业认证。&lt;/span&gt;&lt;/strong&gt;&lt;br /&gt;\n&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;企业认证大致需三个步骤：&lt;/span&gt;&lt;/strong&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;步骤一：已完善企业资料的商家，登录奇化网进入“会员中心”的“企业认证”模块，按指引提交企业认证资料,如下图：&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161118/1479433101.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;步骤二、等待网站工作人员的审核及认证，审核工作预计在1-2个工作日内完成。&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161117/1479375936.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;步骤三、企业认证通过后，会员即可在奇化网上购买商品&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161117/1479375948.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt;&lt;/strong&gt; \n&lt;/p&gt;', '1474281811');
INSERT INTO `kw_partner` VALUES ('110', '1', '106', '3', '106,110', '忘记密码', '&lt;span style=&quot;font-size:18px;&quot;&gt;当您忘记了登录密码时，可以按照以下几个步骤来找回密码（修改密码）：&lt;/span&gt;&lt;br /&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;span style=&quot;font-size:18px;&quot;&gt;步骤一、在登&lt;/span&gt;&lt;span style=&quot;font-size:18px;&quot;&gt;录页处点击“忘记密码”按钮，打开找回密码的操作界面，如图所示：&lt;/span&gt;&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479712191.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:18px;&quot;&gt;步骤二：输入注册时使用的手机号，用于接收短信验证码来重置密码&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479712199.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:18px;&quot;&gt;步骤三、输入新密码后完成修改&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;font-size:16px;&quot;&gt;&lt;img src=&quot;/Uploads/Admin/20161121/1479712206.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;', '1474281835');
INSERT INTO `kw_partner` VALUES ('126', '0', '1', '2', '1,126', '巴斯夫', '/Uploads/Admin/base64/1479721414.jpeg', '1477034235');
INSERT INTO `kw_partner` VALUES ('127', '0', '1', '2', '1,127', '默克', '/Uploads/Admin/base64/1477034272.jpeg', '1477034272');
INSERT INTO `kw_partner` VALUES ('128', '0', '1', '2', '1,128', '杜邦', '/Uploads/Admin/base64/1477034299.jpeg', '1477034299');
INSERT INTO `kw_partner` VALUES ('129', '0', '1', '2', '1,129', '拜耳', '/Uploads/Admin/base64/1477034318.jpeg', '1477034318');
INSERT INTO `kw_partner` VALUES ('130', '0', '1', '2', '1,130', '阿克苏', '/Uploads/Admin/base64/1477034481.jpeg', '1477034331');
INSERT INTO `kw_partner` VALUES ('131', '0', '1', '2', '1,131', '爱思开实业（上海）有限公司', '/Uploads/Admin/base64/1477034817.jpeg', '1477034349');
INSERT INTO `kw_partner` VALUES ('132', '0', '1', '2', '1,132', '壳牌', '/Uploads/Admin/base64/1477034368.jpeg', '1477034368');
INSERT INTO `kw_partner` VALUES ('133', '0', '1', '2', '1,133', '中石油', '/Uploads/Admin/base64/1477034828.jpeg', '1477034426');
INSERT INTO `kw_partner` VALUES ('123', '0', '1', '2', '1,123', '陶式', '/Uploads/Admin/base64/1474424431.jpeg', '1474424431');
INSERT INTO `kw_partner` VALUES ('144', '0', '1', '2', '1,144', '四川联合新澧', '/Uploads/Admin/base64/1477034715.jpeg', '1477034715');
INSERT INTO `kw_partner` VALUES ('145', '0', '1', '2', '1,145', 'cccxxx', '', '1480570999');

-- ----------------------------
-- Table structure for kw_producer
-- ----------------------------
DROP TABLE IF EXISTS `kw_producer`;
CREATE TABLE `kw_producer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` smallint(5) NOT NULL,
  `createTime` int(11) NOT NULL,
  `text` varchar(120) NOT NULL,
  `shorttext` varchar(60) NOT NULL,
  `depth` tinyint(3) unsigned NOT NULL,
  `path` varchar(20) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_producer
-- ----------------------------

-- ----------------------------
-- Table structure for kw_resources
-- ----------------------------
DROP TABLE IF EXISTS `kw_resources`;
CREATE TABLE `kw_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `cas` varchar(30) NOT NULL,
  `spec` varchar(10) NOT NULL,
  `addTime` int(10) NOT NULL,
  `updateTime` int(10) NOT NULL,
  `expire` tinyint(4) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `weightUnit` tinyint(4) NOT NULL,
  `currency` tinyint(4) NOT NULL,
  `brand` int(5) NOT NULL,
  `area` varchar(10) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `member` int(11) NOT NULL,
  `updateIP` char(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_resources
-- ----------------------------

-- ----------------------------
-- Table structure for kw_seekbuy
-- ----------------------------
DROP TABLE IF EXISTS `kw_seekbuy`;
CREATE TABLE `kw_seekbuy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `useid` int(11) NOT NULL COMMENT '用户id',
  `title` varchar(60) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `number` varchar(20) NOT NULL,
  `review` tinyint(1) NOT NULL COMMENT '审核状态',
  `reason` varchar(45) DEFAULT NULL,
  `area` varchar(25) NOT NULL,
  `createTime` int(11) NOT NULL,
  `updateTime` int(11) NOT NULL,
  `timeup` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `expire` smallint(2) unsigned DEFAULT NULL COMMENT '有效期间',
  `checked` int(10) unsigned DEFAULT NULL COMMENT '审核时间',
  `status` tinyint(1) NOT NULL,
  `inventory` int(11) NOT NULL DEFAULT '0',
  `weightUnit` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_seekbuy
-- ----------------------------
INSERT INTO `kw_seekbuy` VALUES ('114', '2098', '求购一批水杨酸', '1', 'QG16101113938', '1', null, '2147,2148,2152', '1476163550', '1476423903', '1482940800', '求购一批水杨酸，用于化妆品', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('115', '2098', '求购甲醇99.9 工业级', '1', 'QG16101113764', '1', null, '2147,2148,2152', '1476163677', '1476423900', '1482940800', '报价要求：需要报含税价\r\n发票要求：无需发票\r\n收货地：江苏无锡', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('116', '2098', '求购可发性聚苯乙烯塑料颗粒', '1', 'QG16101113126', '1', null, '2147,2148,2152', '1476163856', '1476423898', '1482940800', '采购产品：可发性聚苯乙烯塑料颗粒\r\n采购数量：0    \r\n采购用途：自用    \r\n供货商首选地区：河南郑州', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('117', '2098', '求购聚苯乙烯', '1', 'QG16101113207', '1', null, '2147,2148,2152', '1476163902', '1476423895', '1482768000', '高价求购聚苯乙烯，面议', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('118', '2098', '求购无水元明粉', '1', 'QG16101113562', '1', null, '2147,2148,2152', '1476164001', '1476423893', '1475769600', '价格面议', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('119', '2098', '求购化学助剂配方', '2', 'QG16101114725', '1', null, '2147,2148,2152', '1476166456', '1476423890', '1477670400', '采购量：1 个\r\n目标价：面议\r\n规格：面谈 ', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('120', '2098', '求购化学原料技术配方', '2', 'QG16101114454', '1', null, '2147,2148,2152', '1476166512', '1476423888', '1477670400', '求购化学原料技术配方\r\n\r\n采购量：1 个\r\n目标价：面议\r\n规格：面谈 ', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('121', '2098', '化学镀镍配方招标', '2', 'QG16101114968', '3', null, '2147,2148,2152', '1476166554', '1476166554', '1477411199', '本公司化学镀镍 化学镀镍配方招标，有意者，欢迎咨询洽谈', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('122', '1850', '求购AEO7', '1', 'QG16101410424', '2', null, '3,6,3040', '1476412451', '1476424978', '1511798400', '要求天然脂肪醇来源，价格可商议', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('123', '1850', '求购AEO9', '1', 'QG16101410624', '2', null, '3,6,3040', '1476412492', '1476424968', '1514217600', '要求天然脂肪醇来源，价格可议', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('124', '1850', '求购烷基糖苷', '1', 'QG16101410559', '2', null, '3,6,3040', '1476412518', '1476424958', '1498492800', '碳链C12-C14，粘度不低于3000CP', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('125', '1850', '求购二氧化硅', '1', 'QG16101410904', '2', null, '3,6,3040', '1476412533', '1476424950', '1495987200', '牙膏级，摩擦型', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('126', '1850', '求购异丙醇', '1', 'QG16101410869', '2', null, '3,6,3040', '1476412589', '1476424940', '1498233600', '要求：符合国标', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('127', '1850', '求购AES（70%）', '1', 'QG16101410137', '2', null, '3,6,3040', '1476412606', '1476424923', '1500912000', 'EO数2mol', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('128', '1850', '求购柠檬萜烯', '1', 'QG16101410955', '2', null, '3,6,3040', '1476412635', '1476424463', '1505664000', '右旋体，价格可商议', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('129', '1850', '求购K12', '1', 'QG16101410778', '2', null, '3,6,3040', '1476412662', '1476424457', '1502121600', '针状，牙膏级', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('130', '1850', '求购无水乙醇', '1', 'QG16101410243', '2', null, '3,6,3040', '1476412679', '1476424452', '1497888000', '食用级别，特级，玉米酒精', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('131', '1850', '求购薄荷醇', '1', 'QG16101410494', '2', null, '3,6,3040', '1476412710', '1476424446', '1495468800', '要求：晶体，天然来源非合成', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('132', '1850', '求购绿色可降解洗洁精', '2', 'QG16101410835', '2', null, '3,6,3040', '1476412731', '1476424439', '1490457600', '要求符合国标、原料可生物降解、配方价格具有市场竞争力', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('133', '1850', '求购无硅油香波', '2', 'QG16101410575', '2', null, '3,6,3040', '1476412751', '1476424433', '1489939200', '配方要求无硅油添加并能保持良好护发性能', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('134', '1850', '求购金属表面处理剂', '2', 'QG16101410650', '2', null, '3,6,3040', '1476412766', '1476424426', '1485619200', '要求环保、简单处理即可排放、配方价格具有市场竞争力', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('135', '1850', '求购氨基酸洁面乳', '2', 'QG16101410344', '2', null, '3,6,3040', '1476412783', '1476424420', '1512921600', '要求性质温和、低刺激、泡沫丰富、配方价格具有市场竞争力', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('136', '1850', '求购手工精油皂', '2', 'QG16101410631', '2', null, '3,6,3040', '1476412800', '1476424414', '1505059200', '尽量使用植物油脂，添加天然植物精油', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('137', '1850', '求购洗手液发明专利', '3', 'QG16101410805', '2', null, '3,6,3040', '1476412862', '1476424408', '1502553600', '求洗手液发明专利', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('138', '1850', '求购发胶发明专利', '3', 'QG16101410524', '2', null, '3,6,3040', '1476412904', '1476424373', '1513526400', '求发胶的发明专利，价格可商议', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('139', '1850', '求购防冻液发明专利', '3', 'QG16101410983', '2', null, '3,6,3040', '1476412976', '1476424363', '1510588800', '求防冻液发明专利', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('140', '1850', '求购改善洗衣粉溶解性技术', '5', 'QG16101410949', '2', null, '3,6,3040', '1476413020', '1476754604', '1491753600', '能提高洗衣粉在低温洗涤环境下的溶解性', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('141', '1850', '求购膏霜冷配技术', '5', 'QG16101410408', '2', null, '3,6,3040', '1476413041', '1476754598', '1500048000', '冷配技术，减少高温乳化能耗及生产时间，同时保持稳定性', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('142', '1850', '求购改善化妆品稳定性技术', '5', 'QG16101410266', '2', null, '3,6,3040', '1476413097', '1476754591', '1506268800', '求改善化妆品稳定性技术，价格可商议', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('143', '2098', 'd', '2', 'QG16101809195', '4', null, '2147,2148,2152', '1476755441', '1476755441', '1476806399', 'd', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('144', '1850', '求购50Kg中试设备', '4', 'QG16101810142', '2', null, '2147,2148,2152', '1476756986', '1476756986', '1483199999', '要求设备具有夹套，能升降温，带操控台。能同时制备两相', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('145', '1850', '求购1吨混料缸适用地称', '4', 'QG16101810836', '2', null, '2147,2148,2152', '1476757073', '1476757073', '1483199999', '要求精度达到1Kg', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('146', '1850', '求购真空设备', '4', 'QG16101810995', '2', null, '2147,2148,2152', '1476757099', '1476757114', '1482940800', '要求真空度达到3Kpa\r\n', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('147', '1850', '求购过滤设备', '4', 'QG16101810410', '2', null, '2147,2148,2152', '1476757134', '1476757134', '1483199999', '要求不锈钢滤芯，孔径目数达到300至400目', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('148', '1850', '求购微生物解决方案', '4', 'QG16101810298', '2', null, '2147,2148,2152', '1476757151', '1476757151', '1483199999', '针对日用品生产环境现场定制处理微生物解决方案', '0', null, '0', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('149', '1839', '求购AEO7', '1', 'QG16102013490', '3', null, '1,6,3076', '1476943147', '1477293496', '1477294011', '要求天然脂肪醇来源', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('150', '1839', '求购AEO9', '1', 'QG16102013464', '3', null, '1,6,3076', '1476943163', '1477293489', '1477294006', '要求天然脂肪醇来源\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('151', '1839', '求购烷基糖苷', '1', 'QG16102013591', '3', null, '1,6,3076', '1476943181', '1477293482', '1477293816', '碳链C12-C14，粘度不低于3000CP\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('152', '1839', '求购二氧化硅', '1', 'QG16102014539', '3', null, '1,6,3076', '1476943200', '1477293474', '1477293814', '牙膏级，摩擦型\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('153', '1839', '求购异丙醇', '1', 'QG16102014788', '3', null, '1,6,3076', '1476943218', '1477293464', '1477294006', '符合国标\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('154', '1839', '求购AES（70%）', '1', 'QG16102014438', '3', null, '1,6,3076', '1476943236', '1477293451', '1477294004', 'EO数2mol\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('155', '1839', '求购柠檬萜烯', '1', 'QG16102014780', '3', null, '1,6,3076', '1476943254', '1477293436', '1477293878', '右旋体\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('156', '1839', '求购K12', '1', 'QG16102014279', '3', null, '1,6,3076', '1476943287', '1477293429', '1477294050', '针状，牙膏级\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('157', '1839', '求购无水乙醇', '1', 'QG16102014534', '3', null, '1,6,3076', '1476943331', '1477293422', '1477293872', '食用级别，特级，玉米酒精\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('158', '1839', '求购薄荷醇', '1', 'QG16102014882', '3', null, '1,6,3076', '1476943347', '1477293415', '1477293875', '晶体，天然来源非合成\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('159', '1839', '求购绿色可降解洗洁精', '2', 'QG16102014217', '3', null, '1,6,3076', '1476943376', '1477293388', '1477294043', '要求符合国标、原料可生物降解、配方价格具有市场竞争力\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('160', '1839', '求购无硅油香波', '2', 'QG16102014286', '3', null, '1,6,3076', '1476943438', '1477293383', '1477293862', '配方要求无硅油添加并能保持良好护发性能\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('161', '1839', '求购金属表面处理剂', '2', 'QG16102014464', '3', null, '1,6,3076', '1476943468', '1477293375', '1477293855', '要求环保、简单处理即可排放、配方价格具有市场竞争力\n\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('162', '1839', '求购氨基酸洁面乳', '2', 'QG16102014911', '3', null, '1,6,3076', '1476943483', '1477293371', '1477293673', '要求性质温和、低刺激、泡沫丰富、配方价格具有市场竞争力\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('163', '1839', '求购手工精油皂', '2', 'QG16102014370', '3', null, '1,6,3076', '1476943500', '1477293367', '1477293859', '尽量使用植物油脂，添加天然植物精油\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('164', '1839', '求购洗手液发明专利', '3', 'QG16102014711', '3', null, '1,6,3076', '1476943547', '1477293360', '1477294035', '求购洗手液发明专利', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('165', '1839', '求购发胶发明专利', '3', 'QG16102014476', '3', null, '1,6,3076', '1476943562', '1477293355', '1477294026', '求购发胶发明专利\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('166', '1839', '求购防冻液发明专利', '3', 'QG16102014657', '3', null, '1,6,3076', '1476943594', '1477293351', '1477293852', '求购防冻液发明专利', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('167', '1839', '求购改善洗衣粉溶解性技术及配方', '5', 'QG16102014987', '3', null, '1,6,3076', '1476943633', '1477293345', '1477294038', '能提高洗衣粉在低温洗涤环境下的溶解性\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('168', '1839', '求购膏霜冷配技术及配方', '5', 'QG16102014986', '3', null, '1,6,3076', '1476943652', '1477293340', '1477294029', '冷配技术，减少高温乳化能耗及生产时间，同时保持稳定性\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('169', '1839', '求购改善化妆品稳定性技术', '5', 'QG16102014671', '3', null, '1,6,3076', '1476943670', '1477293333', '1477293844', '要求膏霜2000rpm离心2min不破乳，冷冻恢复室温不分层\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('170', '1839', '求购50Kg中试设备', '4', 'QG16102014836', '3', null, '1,6,3076', '1476943711', '1477293321', '1477293840', '要求设备具有夹套，能升降温，带操控台。能同时制备两相\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('171', '1839', '求购1吨混料缸适用地称', '4', 'QG16102014990', '3', null, '1,6,3076', '1476943728', '1477293307', '1477293843', '要求精度达到1Kg\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('172', '1839', '求购真空设备', '4', 'QG16102014379', '3', null, '1,6,3076', '1476943750', '1477293296', '1477293831', '要求真空度达到3Kpa\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('173', '1839', '求购过滤设备', '4', 'QG16102014223', '3', null, '1,6,3076', '1476943771', '1477293391', '1477294014', '要求不锈钢滤芯，孔径目数达到300至400目\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('174', '1839', '求购微生物解决方案', '4', 'QG16102014396', '3', null, '1,6,3076', '1476943791', '1477293277', '1477293649', '针对日用品生产环境现场定制处理微生物解决方案\n', '0', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('175', '2282', 'ccc', '1', 'QG16111714754', '1', null, '2147,2162,2169', '1479363683', '1479363683', '0', '100', '10', null, '1', '100', '1');
INSERT INTO `kw_seekbuy` VALUES ('176', '2298', '求求求购购购原原原料料料料料料', '1', 'QG16111714676', '1', null, '41,66,68', '1479363767', '1479432413', '1481955908', '求求求购购购原原原料料料料料料\n\n价优量大', '30', null, '1', '989', '1');
INSERT INTO `kw_seekbuy` VALUES ('177', '2176', 'xx', '1', 'QGYL1611171734098', '1', null, '2543,2713,2715', '1479375357', '1479375946', '0', '100', '7', null, '1', '1000', '7');
INSERT INTO `kw_seekbuy` VALUES ('178', '2298', '1', '1', 'QGYL1611171778119', '1', null, '41,66,68', '1479375446', '1479375531', '1479807486', '4', '5', null, '1', '2', '1');
INSERT INTO `kw_seekbuy` VALUES ('179', '2298', '2', '1', 'QGYL161117172787', '3', null, '41,66,68', '1479375555', '1479376049', '1479980880', '2', '7', null, '1', '2', '5');
INSERT INTO `kw_seekbuy` VALUES ('180', '2298', '3', '1', 'QGYL1611171769921', '1', null, '41,66,68', '1479375626', '1479375638', '0', '3', '10', null, '1', '3', '2');
INSERT INTO `kw_seekbuy` VALUES ('181', '2176', 'zz', '5', 'QGJS1611171713257', '1', null, '2543,2713,2715', '1479375976', '1479375995', '0', 'zz', '5', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('182', '2363', '甘油', '1', 'QGYL1611181041399', '1', null, '2147,2224,2225', '1479437499', '1479437499', '0', '一百吨', '15', null, '0', '100', '1');
INSERT INTO `kw_seekbuy` VALUES ('183', '2363', '甘油', '1', 'QGYL1611181083193', '2', null, '2147,2224,2225', '1479437503', '1479437503', '1480748799', '一百吨', '15', null, '1', '100', '1');
INSERT INTO `kw_seekbuy` VALUES ('184', '2374', '甘油', '1', 'QGYL1611181118224', '2', null, '2147,2148,2152', '1479440173', '1479440173', '1482032389', '春金甘油，要求正品，便宜，供应稳定。', '30', null, '1', '100', '1');
INSERT INTO `kw_seekbuy` VALUES ('185', '2373', '求购磺酸100吨', '1', 'QGYL1611181153550', '3', null, '21,22,24', '1479441001', '1479441001', '1480045846', '商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息商品详细信息', '7', null, '1', '1000', '3');
INSERT INTO `kw_seekbuy` VALUES ('186', '2363', '元明粉', '1', 'QGYL1611181439488', '2', null, '2147,2224,2225', '1479449478', '1479453082', '1482048147', '质量', '30', null, '1', '20', '1');
INSERT INTO `kw_seekbuy` VALUES ('187', '2372', '1618醇', '1', 'QGYL161118154540', '3', null, '41,82,87', '1479455954', '1479455954', '1480060960', '2', '7', null, '1', '2', '1');
INSERT INTO `kw_seekbuy` VALUES ('188', '2363', '提取', '5', 'QGJS1611181553490', '2', null, '2147,2224,2225', '1479455965', '1479455965', '1482048171', '提取成分', '30', null, '1', '0', '1');
INSERT INTO `kw_seekbuy` VALUES ('189', '2268', '求购商品1', '1', 'QGYL1611251139312', '2', null, '2147,2148,2152', '1480044816', '1480044816', '1480917728', '沙发', '10', null, '1', '100', '1');
INSERT INTO `kw_seekbuy` VALUES ('190', '2268', '石化原料加工的配方', '2', 'QGPF1611251765065', '2', null, '2147,2148,2152', '1480065114', '1480065114', '1482657159', '的    的顶顶顶顶顶顶顶顶顶顶顶顶顶顶顶', '30', null, '1', '0', '1');

-- ----------------------------
-- Table structure for kw_seekbuylog
-- ----------------------------
DROP TABLE IF EXISTS `kw_seekbuylog`;
CREATE TABLE `kw_seekbuylog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT '求购信息id',
  `time` int(11) NOT NULL,
  `operation` varchar(20) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `operator` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=355 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_seekbuylog
-- ----------------------------
INSERT INTO `kw_seekbuylog` VALUES ('72', '114', '1476163550', '新增求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('73', '115', '1476163677', '新增求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('74', '116', '1476163856', '新增求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('75', '117', '1476163902', '新增求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('76', '118', '1476164001', '新增求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('77', '114', '1476164042', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('78', '115', '1476164046', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('79', '116', '1476164048', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('80', '117', '1476164050', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('81', '118', '1476164051', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('82', '118', '1476166390', '修改求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('83', '117', '1476166403', '修改求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('84', '119', '1476166456', '新增求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('85', '120', '1476166512', '新增求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('86', '121', '1476166554', '新增求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('87', '117', '1476167074', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('88', '118', '1476167076', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('89', '119', '1476167078', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('90', '120', '1476167080', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('91', '121', '1476167082', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('92', '122', '1476412451', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('93', '123', '1476412492', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('94', '124', '1476412518', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('95', '125', '1476412533', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('96', '126', '1476412589', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('97', '127', '1476412606', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('98', '128', '1476412635', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('99', '129', '1476412662', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('100', '130', '1476412679', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('101', '131', '1476412710', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('102', '132', '1476412731', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('103', '133', '1476412751', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('104', '134', '1476412766', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('105', '135', '1476412783', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('106', '136', '1476412800', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('107', '137', '1476412862', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('108', '138', '1476412904', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('109', '139', '1476412976', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('110', '140', '1476413020', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('111', '141', '1476413041', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('112', '142', '1476413097', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('113', '142', '1476414627', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('114', '141', '1476414630', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('115', '140', '1476414634', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('116', '139', '1476414637', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('117', '138', '1476414640', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('118', '137', '1476414642', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('119', '136', '1476414645', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('120', '135', '1476414648', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('121', '134', '1476414652', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('122', '122', '1476414658', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('123', '123', '1476414661', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('124', '124', '1476414664', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('125', '125', '1476414666', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('126', '126', '1476414668', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('127', '127', '1476414671', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('128', '128', '1476414673', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('129', '129', '1476414675', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('130', '130', '1476414677', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('131', '131', '1476414679', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('132', '132', '1476414681', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('133', '133', '1476414683', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('134', '120', '1476423888', '修改求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('135', '119', '1476423890', '修改求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('136', '118', '1476423893', '修改求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('137', '117', '1476423895', '修改求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('138', '116', '1476423898', '修改求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('139', '115', '1476423900', '修改求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('140', '114', '1476423903', '修改求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('141', '142', '1476424344', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('142', '141', '1476424351', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('143', '140', '1476424357', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('144', '139', '1476424363', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('145', '138', '1476424373', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('146', '137', '1476424396', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('147', '137', '1476424408', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('148', '136', '1476424414', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('149', '135', '1476424420', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('150', '134', '1476424426', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('151', '133', '1476424433', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('152', '132', '1476424439', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('153', '131', '1476424446', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('154', '130', '1476424452', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('155', '129', '1476424457', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('156', '128', '1476424463', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('157', '127', '1476424923', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('158', '126', '1476424940', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('159', '125', '1476424950', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('160', '124', '1476424958', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('161', '123', '1476424968', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('162', '122', '1476424978', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('163', '122', '1476424999', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('164', '123', '1476425002', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('165', '124', '1476425005', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('166', '125', '1476425006', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('167', '126', '1476425009', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('168', '127', '1476425011', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('169', '128', '1476425013', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('170', '129', '1476425015', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('171', '130', '1476425017', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('172', '131', '1476425018', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('173', '132', '1476425020', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('174', '133', '1476425022', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('175', '134', '1476425024', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('176', '135', '1476425027', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('177', '136', '1476425029', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('178', '137', '1476425031', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('179', '138', '1476425032', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('180', '139', '1476425034', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('181', '140', '1476425036', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('182', '141', '1476425037', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('183', '142', '1476425040', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('184', '142', '1476754591', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('185', '141', '1476754598', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('186', '140', '1476754604', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('187', '142', '1476754651', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('188', '141', '1476754653', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('189', '140', '1476754657', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('190', '143', '1476755441', '新增求购', null, 'test12345');
INSERT INTO `kw_seekbuylog` VALUES ('191', '144', '1476756986', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('192', '145', '1476757073', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('193', '146', '1476757099', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('194', '146', '1476757114', '修改求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('195', '147', '1476757134', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('196', '148', '1476757151', '新增求购', null, 'mixue');
INSERT INTO `kw_seekbuylog` VALUES ('197', '148', '1476757198', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('198', '147', '1476757199', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('199', '146', '1476757202', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('200', '145', '1476757205', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('201', '144', '1476757208', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('202', '143', '1476757210', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('203', '143', '1476757223', '撤销通过', '测试', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('204', '149', '1476943147', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('205', '150', '1476943163', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('206', '151', '1476943181', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('207', '152', '1476943200', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('208', '153', '1476943218', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('209', '154', '1476943236', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('210', '155', '1476943254', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('211', '156', '1476943287', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('212', '157', '1476943331', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('213', '158', '1476943347', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('214', '159', '1476943376', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('215', '160', '1476943438', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('216', '161', '1476943468', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('217', '162', '1476943483', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('218', '163', '1476943500', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('219', '164', '1476943547', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('220', '165', '1476943562', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('221', '166', '1476943594', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('222', '167', '1476943633', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('223', '168', '1476943652', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('224', '169', '1476943670', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('225', '169', '1476943679', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('226', '170', '1476943711', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('227', '171', '1476943728', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('228', '172', '1476943750', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('229', '173', '1476943771', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('230', '174', '1476943791', '新增求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('231', '174', '1476943818', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('232', '173', '1476943821', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('233', '172', '1476943823', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('234', '171', '1476943825', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('235', '170', '1476943827', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('236', '169', '1476943829', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('237', '168', '1476943831', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('238', '167', '1476943832', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('239', '166', '1476943834', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('240', '165', '1476943837', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('241', '164', '1476943839', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('242', '163', '1476943841', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('243', '162', '1476943844', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('244', '161', '1476943846', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('245', '160', '1476943847', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('246', '159', '1476943849', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('247', '158', '1476943851', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('248', '157', '1476943853', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('249', '156', '1476943856', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('250', '155', '1476943857', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('251', '154', '1476943867', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('252', '153', '1476943869', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('253', '152', '1476943872', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('254', '151', '1476943874', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('255', '150', '1476943876', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('256', '149', '1476943877', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('257', '174', '1477292896', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('258', '173', '1477293260', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('259', '174', '1477293277', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('260', '172', '1477293296', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('261', '171', '1477293307', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('262', '170', '1477293321', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('263', '169', '1477293333', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('264', '168', '1477293340', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('265', '167', '1477293345', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('266', '166', '1477293351', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('267', '165', '1477293355', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('268', '164', '1477293360', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('269', '163', '1477293367', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('270', '162', '1477293371', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('271', '161', '1477293375', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('272', '160', '1477293383', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('273', '159', '1477293388', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('274', '173', '1477293391', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('275', '158', '1477293415', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('276', '157', '1477293422', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('277', '156', '1477293429', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('278', '155', '1477293436', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('279', '154', '1477293451', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('280', '153', '1477293464', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('281', '152', '1477293474', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('282', '151', '1477293482', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('283', '150', '1477293489', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('284', '149', '1477293496', '修改求购', null, 'qihua');
INSERT INTO `kw_seekbuylog` VALUES ('285', '154', '1477293628', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('286', '153', '1477293631', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('287', '152', '1477293634', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('288', '151', '1477293636', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('289', '150', '1477293638', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('290', '149', '1477293641', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('291', '174', '1477293649', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('292', '173', '1477293649', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('293', '172', '1477293651', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('294', '171', '1477293653', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('295', '170', '1477293655', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('296', '169', '1477293657', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('297', '168', '1477293659', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('298', '167', '1477293661', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('299', '166', '1477293663', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('300', '165', '1477293666', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('301', '164', '1477293668', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('302', '163', '1477293671', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('303', '162', '1477293673', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('304', '161', '1477293675', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('305', '159', '1477293678', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('306', '160', '1477293682', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('307', '157', '1477293684', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('308', '158', '1477293687', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('309', '156', '1477293690', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('310', '155', '1477293692', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('311', '175', '1479363683', '新增求购', null, 'szqianhaiwf');
INSERT INTO `kw_seekbuylog` VALUES ('312', '176', '1479363767', '新增求购', null, 'bbbbbb');
INSERT INTO `kw_seekbuylog` VALUES ('313', '176', '1479363807', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('314', '176', '1479363900', '撤销通过', '5453525', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('315', '176', '1479363908', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('316', '177', '1479375357', '新增求购', null, 'aaaaaa');
INSERT INTO `kw_seekbuylog` VALUES ('317', '178', '1479375446', '新增求购', null, 'bbbbbb');
INSERT INTO `kw_seekbuylog` VALUES ('318', '178', '1479375476', '修改求购', null, 'bbbbbb');
INSERT INTO `kw_seekbuylog` VALUES ('319', '178', '1479375486', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('320', '178', '1479375531', '修改求购', null, 'bbbbbb');
INSERT INTO `kw_seekbuylog` VALUES ('321', '179', '1479375555', '新增求购', null, 'bbbbbb');
INSERT INTO `kw_seekbuylog` VALUES ('322', '179', '1479375570', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('323', '180', '1479375626', '新增求购', null, 'bbbbbb');
INSERT INTO `kw_seekbuylog` VALUES ('324', '180', '1479375638', '修改求购', null, 'bbbbbb');
INSERT INTO `kw_seekbuylog` VALUES ('325', '177', '1479375687', '修改求购', null, 'aaaaaa');
INSERT INTO `kw_seekbuylog` VALUES ('326', '177', '1479375742', '修改求购', null, 'aaaaaa');
INSERT INTO `kw_seekbuylog` VALUES ('327', '177', '1479375792', '修改求购', null, 'aaaaaa');
INSERT INTO `kw_seekbuylog` VALUES ('328', '177', '1479375836', '修改求购', null, 'aaaaaa');
INSERT INTO `kw_seekbuylog` VALUES ('329', '177', '1479375946', '修改求购', null, 'aaaaaa');
INSERT INTO `kw_seekbuylog` VALUES ('330', '181', '1479375976', '新增求购', null, 'aaaaaa');
INSERT INTO `kw_seekbuylog` VALUES ('331', '181', '1479375984', '修改求购', null, 'aaaaaa');
INSERT INTO `kw_seekbuylog` VALUES ('332', '181', '1479375995', '修改求购', null, 'aaaaaa');
INSERT INTO `kw_seekbuylog` VALUES ('333', '179', '1479376049', '修改求购', null, 'bbbbbb');
INSERT INTO `kw_seekbuylog` VALUES ('334', '179', '1479376080', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('335', '176', '1479432413', '修改求购', null, 'bbbbbb');
INSERT INTO `kw_seekbuylog` VALUES ('336', '182', '1479437499', '新增求购', null, 'menghuaxing1108');
INSERT INTO `kw_seekbuylog` VALUES ('337', '183', '1479437503', '新增求购', null, 'menghuaxing1108');
INSERT INTO `kw_seekbuylog` VALUES ('338', '184', '1479440173', '新增求购', null, 'sunwenjun2016');
INSERT INTO `kw_seekbuylog` VALUES ('339', '184', '1479440372', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('340', '185', '1479441001', '新增求购', null, 'alibaba1688');
INSERT INTO `kw_seekbuylog` VALUES ('341', '185', '1479441030', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('342', '186', '1479449478', '新增求购', null, 'menghuaxing1108');
INSERT INTO `kw_seekbuylog` VALUES ('343', '186', '1479452646', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('344', '183', '1479452784', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('345', '186', '1479453082', '修改求购', null, 'menghuaxing1108');
INSERT INTO `kw_seekbuylog` VALUES ('346', '187', '1479455954', '新增求购', null, 'ppscbjjy');
INSERT INTO `kw_seekbuylog` VALUES ('347', '188', '1479455965', '新增求购', null, 'menghuaxing1108');
INSERT INTO `kw_seekbuylog` VALUES ('348', '187', '1479456145', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('349', '186', '1479456147', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('350', '188', '1479456151', '审核通过', '', 'admin');
INSERT INTO `kw_seekbuylog` VALUES ('351', '189', '1480044816', '新增求购', null, 'linsn');
INSERT INTO `kw_seekbuylog` VALUES ('352', '189', '1480053728', '审核通过', '', 'huanglinsheng');
INSERT INTO `kw_seekbuylog` VALUES ('353', '190', '1480065114', '新增求购', null, 'linsn');
INSERT INTO `kw_seekbuylog` VALUES ('354', '190', '1480065159', '审核通过', '', 'huanglinsheng');

-- ----------------------------
-- Table structure for kw_tmp_cat
-- ----------------------------
DROP TABLE IF EXISTS `kw_tmp_cat`;
CREATE TABLE `kw_tmp_cat` (
  `id` int(11) DEFAULT NULL,
  `catName` varchar(200) DEFAULT NULL,
  `productIdList` text,
  `parentList` varchar(200) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `depth` varchar(200) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_tmp_cat
-- ----------------------------
INSERT INTO `kw_tmp_cat` VALUES ('1', '日用化工1', '', '1', '0', '1', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('2', '表面活性剂', '', '1,2', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('3', '非离子表面活性剂', '394,395', '1,2,3', '2', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('4', '阳离子表面活性剂', '', '1,2,4', '2', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('5', '阴离子表面活性剂', '324', '1,2,5', '2', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('6', '两性离子表面活性剂', '', '1,2,6', '2', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('7', '其他', '243,244,271,272,273', '1,2,7', '2', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('8', '乳化剂', '', '1,8', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('9', '水包油型', '227,231', '1,8,9', '8', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('10', '油包水型', '', '1,8,10', '8', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('11', '螯合剂', '211,212,219,224,236,237,238,249,250,261,262,283,461,462', '1,11', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('12', '助剂', '281,291,296,292,328,330,333,335,337,340,342,362,364,374,375,385,429,445', '1,12', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('13', '保湿剂', '463,466', '1,13', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('14', 'pH调节剂', '', '1,14', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('15', '碱性调节剂', '204,274,276,201,205,206,207,314,264,200,208,275', '1,14,15', '14', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('16', '酸性调节剂', '467,468,469', '1,14,16', '14', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('17', 'pH缓存剂', '', '1,14,17', '14', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('18', '提取物', '', '1,18', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('19', '香精香料', '', '1,19', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('20', '增稠剂', '', '1,20', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('21', '抗氧化剂', '', '1,21', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('22', '提取物', '', '1,22', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('23', '香精香料', '', '1,23', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('24', '增稠剂', '', '1,24', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('25', '抗氧化剂', '', '1,25', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('26', '防腐剂', '', '1,26', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('27', '油脂', '222,235,268,269,230,229,247,453,225,233,270,265,267', '1,27', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('28', '摩擦剂', '', '1,28', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('29', '硅油/消泡剂', '', '1,29', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('30', '调理剂', '', '1,30', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('31', '其他', '', '1,31', '1', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('32', '农业化工2', '', '32', '0', '1', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('33', '除草剂', '294,303,282,289,297,447,313,317,343,347,287,302,392,430,318,319,299,300', '32,33', '32', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('34', '杀虫剂', '239,240,310,311,341,346,460', '32,34', '32', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('35', '杀菌剂', '325,326,327,241,242,431,401,408,419,432,344,348,397,399', '32,35', '32', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('36', '植物生长调节剂', '427,428,332,336,456,334,338,457,345,349,458,459', '32,36', '32', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('37', '农药中间体', '260,192,199,298,301,295,361,418,441', '32,37', '32', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('38', '助剂/增效剂', '417,421,209,422,424', '32,38', '32', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('39', '生产原料及前体', '286,290,293,315,316,312,323', '32,39', '32', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('40', '其他', '', '32,40', '32', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('41', '石油化工3', '', '41', '0', '1', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('42', '醇类', '263,284,232,193,198,226,358,365,382,393,396,426,438,451,455', '41,42', '41', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('43', '芳烃类', '253,255,252,371,380,384,416,363,366,367,368,377,379,383,386,387,388,389,390,391,400,402,403,404,405,407,409,410,411,412,413,433,444,446,448,254,251,373,378,439,440,465,280,279,288,304,305,306,321,322,329,331,339,350,351,352,353,354,355,356,357,359,360,381,398,414,420,423,449,228,256,194,195,277,258,437,443,257,259,196,197,245,442,202,278,307,320', '41,43', '41', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('44', '酚酮', '', '41,44', '41', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('45', '酯酸', '', '41,45', '41', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('46', '醚酯', '', '41,46', '41', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('47', '环氧树脂', '', '41,47', '41', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('48', '环氧乙烷', '', '41,48', '41', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('49', '中间体', '', '41,49', '41', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('50', '其他', '', '41,50', '41', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('51', '食品化工4', '', '51', '0', '1', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('52', '防腐剂', '308,309', '51,52', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('53', '甜味剂', '', '51,53', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('54', '增稠剂', '', '51,54', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('55', '香精香料', '', '51,55', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('56', '护色剂', '', '51,56', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('57', '酸度调节剂', '', '51,57', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('58', '消泡剂', '', '51,58', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('59', '乳化剂', '', '51,59', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('60', '抗氧化剂', '', '51,60', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('61', '抗结剂', '', '51,61', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('62', '漂白剂', '', '51,62', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('63', '膨松剂', '', '51,63', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('64', '胶基糖果基础剂', '', '51,64', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('65', '着色剂', '', '51,65', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('66', '酶制剂', '', '51,66', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('67', '增味剂', '', '51,67', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('68', '其他', '', '51,68', '51', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('69', '医疗化工5', '', '69', '0', '1', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('70', '合成原料/前体', '415,425,450', '69,70', '69', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('71', '消毒剂', '', '69,71', '69', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('72', '中间体', '', '69,72', '69', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('73', '香精香料', '', '69,73', '69', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('74', '催化剂', '', '69,74', '69', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('75', '聚合物', '', '69,75', '69', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('76', '崩解剂', '', '69,76', '69', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('77', '粘合剂', '', '69,77', '69', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('78', '成膜/包衣剂', '', '69,78', '69', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('79', '其他', '', '69,79', '69', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('80', '环保/造纸/能源化工', '', '80', '0', '1', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('81', '环保化工', '', '80,81', '80', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('82', '净水剂', '369,370,372,376', '80,81,82', '81', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('83', '杀菌灭藻剂', '', '80,81,83', '81', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('84', '缓蚀剂', '', '80,81,84', '81', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('85', '阻垢剂', '', '80,81,85', '81', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('86', '脱水剂', '', '80,81,86', '81', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('87', '其他', '', '80,81,87', '81', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('88', '造纸化工', '', '80,88', '80', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('89', '净水剂', '', '80,88,89', '88', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('90', '增白剂', '', '80,88,90', '88', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('91', '制浆助剂', '', '80,88,91', '88', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('92', '抄纸助剂', '', '80,88,92', '88', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('93', '其他', '', '80,88,93', '88', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('94', '能源化工', '', '80,94', '80', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('95', '燃料', '246,248,452,454,464,470,223,234', '80,94,95', '94', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('96', '塑料/橡胶化工', '', '96', '0', '1', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('97', '塑料化工', '', '96,97', '96', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('98', '增塑剂', '', '96,97,98', '97', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('99', '溶剂', '', '96,97,99', '97', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('100', '助剂', '', '96,97,100', '97', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('101', '填料', '', '96,97,101', '97', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('102', '色母', '', '96,97,102', '97', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('103', '通用塑料', '', '96,97,103', '97', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('104', '工程塑料', '', '96,97,104', '97', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('105', '其他', '', '96,97,105', '97', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('106', '橡胶化工', '', '96,106', '96', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('107', '天然橡胶（乳胶）', '', '96,106,107', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('108', '通用合成橡胶', '', '96,106,108', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('109', '特种合成橡胶', '', '96,106,109', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('110', '弹性体', '', '96,106,110', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('111', '促进助剂', '', '96,106,111', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('112', '增塑助剂', '', '96,106,112', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('113', '软化助剂', '', '96,106,113', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('114', '增稠助剂', '', '96,106,114', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('115', '防老助剂', '', '96,106,115', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('116', '有机硅', '', '96,106,116', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('117', '有机硅', '', '96,106,117', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('118', '其他', '', '96,106,118', '106', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('119', '涂料/油墨化工', '', '119', '0', '1', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('120', '涂料化工', '', '119,120', '119', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('121', '溶剂', '', '119,120,121', '120', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('122', '填料', '', '119,120,122', '120', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('123', '助剂', '', '119,120,123', '120', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('124', '颜料', '', '119,120,124', '120', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('125', '乳液', '', '119,120,125', '120', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('126', '树脂', '', '119,120,126', '120', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('127', '其他', '', '119,120,127', '120', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('128', '油墨化工', '', '119,128', '119', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('129', '树脂', '', '119,128,129', '128', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('130', '色料', '', '119,128,130', '128', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('131', '填充助剂', '', '119,128,131', '128', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('132', '稀释助剂', '', '119,128,132', '128', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('133', '防结皮助剂', '', '119,128,133', '128', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('134', '防反印助剂', '', '119,128,134', '128', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('135', '增滑助剂', '', '119,128,135', '128', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('136', '其他', '', '119,128,136', '128', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('137', '电镀/陶瓷化工', '', '137', '0', '1', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('138', '电镀化工', '', '137,138', '137', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('139', '光亮剂', '', '137,138,139', '138', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('140', '络合剂', '', '137,138,140', '138', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('141', '钝化剂', '', '137,138,141', '138', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('142', '整平剂', '', '137,138,142', '138', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('143', '导电剂', '', '137,138,143', '138', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('144', '缓冲剂', '', '137,138,144', '138', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('145', '阳极活性剂', '', '137,138,145', '138', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('146', '应力消除剂', '', '137,138,146', '138', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('147', '润湿剂', '', '137,138,147', '138', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('148', '其他', '', '137,138,148', '138', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('149', '陶瓷化工', '', '137,149', '137', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('150', '粘土', '', '137,149,150', '149', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('151', '石英', '', '137,149,151', '149', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('152', '熔剂', '', '137,149,152', '149', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('153', '着色剂', '', '137,149,153', '149', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('154', '防污溶剂', '', '137,149,154', '149', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('155', '增强溶剂', '', '137,149,155', '149', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('156', '釉料', '', '137,149,156', '149', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('157', '其他', '', '137,149,157', '149', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('158', '纺织/染印化工', '', '158', '0', '1', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('159', '浆料糊料', '', '158,159', '158', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('160', '染料', '', '158,160', '158', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('161', '分散染料', '', '158,160,161', '160', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('162', '活性染料', '', '158,160,162', '160', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('163', '还原染料', '', '158,160,163', '160', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('164', '硫化染料', '', '158,160,164', '160', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('165', '酸性染料', '', '158,160,165', '160', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('166', '其他', '', '158,160,166', '160', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('167', '助剂', '', '158,167', '158', '2', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('168', '稳定剂', '', '158,167,168', '167', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('169', '匀染剂', '', '158,167,169', '167', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('170', '渗透剂', '', '158,167,170', '167', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('171', '柔软剂', '', '158,167,171', '167', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('172', '防水剂', '', '158,167,172', '167', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('173', '防腐剂', '', '158,167,173', '167', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('174', '其他', '', '158,167,174', '167', '3', '1475908779');
INSERT INTO `kw_tmp_cat` VALUES ('175', '其他', '', '158,175', '158', '2', '1475908779');

-- ----------------------------
-- Table structure for kw_user
-- ----------------------------
DROP TABLE IF EXISTS `kw_user`;
CREATE TABLE `kw_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `realname` varchar(32) NOT NULL COMMENT '真实姓名',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别:1男；0女；',
  `mobile` bigint(11) DEFAULT NULL COMMENT '手机号',
  `tel` varchar(16) DEFAULT NULL COMMENT '固定电话',
  `email` varchar(32) DEFAULT NULL COMMENT '邮箱',
  `lastloginip` char(15) DEFAULT NULL,
  `lastlogintime` int(11) unsigned DEFAULT NULL,
  `addtime` int(11) unsigned NOT NULL COMMENT '添加时间',
  `creater` mediumint(8) NOT NULL COMMENT '创建人',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1:开启；0:禁用',
  `updatetime` int(11) unsigned DEFAULT NULL COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:正常；2:删除',
  `department` varchar(32) DEFAULT NULL COMMENT '部门',
  `group` varchar(32) DEFAULT NULL,
  `salt` char(4) DEFAULT NULL COMMENT '随机数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of kw_user
-- ----------------------------
INSERT INTO `kw_user` VALUES ('1', 'admin', 'fafdfbe56c0ff47a4a898fb7fd59451a', '超级管理员', '1', '18620043314', '', '', '61.140.236.63', '1502962795', '1470030383', '39', '1', '1492594493', '1', '奇化网', '超级管理员', '4364');
