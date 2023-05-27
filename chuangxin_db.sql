/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50051
Source Host           : localhost:3306
Source Database       : chuangxin_db

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2018-11-22 21:48:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_admin`
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `username` varchar(20) NOT NULL default '',
  `password` varchar(32) default NULL,
  PRIMARY KEY  (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_admin
-- ----------------------------
INSERT INTO `t_admin` VALUES ('a', 'a');

-- ----------------------------
-- Table structure for `t_classinfo`
-- ----------------------------
DROP TABLE IF EXISTS `t_classinfo`;
CREATE TABLE `t_classinfo` (
  `classNo` varchar(20) NOT NULL COMMENT 'classNo',
  `className` varchar(20) NOT NULL COMMENT '班级名称',
  `collegeName` varchar(20) NOT NULL COMMENT '所在学院',
  `specialName` varchar(20) NOT NULL COMMENT '所在专业',
  `mainTeacher` varchar(20) NOT NULL COMMENT '班主任',
  `bornDate` varchar(20) default NULL COMMENT '成立日期',
  PRIMARY KEY  (`classNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_classinfo
-- ----------------------------
INSERT INTO `t_classinfo` VALUES ('BJ001', '计算机3班', '信息工程学院', '计算机专业', '李向阳', '2018-11-13');
INSERT INTO `t_classinfo` VALUES ('BJ002', '计算机4班', '信息工程学院', '计算机专业', '李明涛', '2018-11-03');

-- ----------------------------
-- Table structure for `t_innovate`
-- ----------------------------
DROP TABLE IF EXISTS `t_innovate`;
CREATE TABLE `t_innovate` (
  `innovateId` int(11) NOT NULL auto_increment COMMENT '创新记录id',
  `innovateTypeObj` int(11) NOT NULL COMMENT '创新类型',
  `innovateTitle` varchar(20) NOT NULL COMMENT '创新项目标题',
  `innovateContent` varchar(8000) NOT NULL COMMENT '创新项目描述',
  `innovateFile` varchar(60) NOT NULL COMMENT '创新项目文件',
  `innovateScore` float NOT NULL COMMENT '申请创新学分',
  `studentObj` varchar(30) NOT NULL COMMENT '申请的学生',
  `sqTime` varchar(20) default NULL COMMENT '申请时间',
  `shenHeState` varchar(20) NOT NULL COMMENT '审核状态',
  `innovateChengji` varchar(500) NOT NULL COMMENT '创新项目成绩',
  PRIMARY KEY  (`innovateId`),
  KEY `innovateTypeObj` (`innovateTypeObj`),
  KEY `studentObj` (`studentObj`),
  CONSTRAINT `t_innovate_ibfk_2` FOREIGN KEY (`studentObj`) REFERENCES `t_student` (`user_name`),
  CONSTRAINT `t_innovate_ibfk_1` FOREIGN KEY (`innovateTypeObj`) REFERENCES `t_innovatetype` (`innovateTypeId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_innovate
-- ----------------------------
INSERT INTO `t_innovate` VALUES ('1', '1', '理工大学创新活动', '<p>参加学校举办的创新活动项目，参加学校举办的创新活动项目，参加学校举办的创新活动项目，参加学校举办的创新活动项目，参加学校举办的创新活动项目，参加学校举办的创新活动项目，参加学校举办的创新活动项目，参加学校举办的创新活动项目，参加学校举办的创新活动项目，参加学校举办的创新活动项目，参加学校举办的创新活动项目，</p>', 'upload/670648fa51cd3a3df196675d775de75b.doc', '2', 'STU001', '2018-11-17 16:59:51', '待审核', '--');
INSERT INTO `t_innovate` VALUES ('2', '6', '创办广告公司', '<p>本人在大学大二学习期间，创办了一个广告公司，实现了盈利！</p>', 'upload/2ee81450e7c150f9321aeded3942d74e.doc', '2', 'STU002', '2018-11-22 21:19:49', '审核通过', '90分');

-- ----------------------------
-- Table structure for `t_innovatetype`
-- ----------------------------
DROP TABLE IF EXISTS `t_innovatetype`;
CREATE TABLE `t_innovatetype` (
  `innovateTypeId` int(11) NOT NULL auto_increment COMMENT '创新类型id',
  `innovateTypeName` varchar(20) NOT NULL COMMENT '创新类型名称',
  `sjnr` varchar(800) NOT NULL COMMENT '实践内容',
  `xuefen` float NOT NULL COMMENT '学分',
  `cjjz` varchar(600) NOT NULL COMMENT '成绩记载',
  PRIMARY KEY  (`innovateTypeId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_innovatetype
-- ----------------------------
INSERT INTO `t_innovatetype` VALUES ('1', '创新创业训练A', '参加市级及以上大学生创新创业训练计划项目并结题', '2', '项目负责人：\r\n90分\r\n团队成员：\r\n85分\r\n');
INSERT INTO `t_innovatetype` VALUES ('2', '创新创业训练B', '参加校级大学生创新创业训练计划项目并结题；\r\n参加骆肇尧科创基金等校内其他科创项目并结题', '1', '项目负责人：\r\n90分\r\n团队成员：\r\n85分');
INSERT INTO `t_innovatetype` VALUES ('3', '学科竞赛A', '参加并完成有关国家级A类及国际赛事（见附件2）', '3', '获得三等奖及以上（只认定由上而下设置的3个级别）的项目组成员：90分；');
INSERT INTO `t_innovatetype` VALUES ('4', '学科竞赛B', '参加并完成有关国家级B、C类赛事及市级赛事', '2', '获得三等奖及以上（只认定由上而下设置的3个级别）的项目组成员：90分；');
INSERT INTO `t_innovatetype` VALUES ('5', '学科竞赛C', '参加并完成有关校级赛事', '1', '获得三等奖及以上（只认定由上而下设置的3个级别）的项目组成员：90分；');
INSERT INTO `t_innovatetype` VALUES ('6', '创业实践', '注册公司', '2', '法人代表：\r\n90分');
INSERT INTO `t_innovatetype` VALUES ('7', '科研训练A', '参加由校研究生院组织的“优秀本科生进实验室”项目，项目结题且考核合格', '2', '90分');
INSERT INTO `t_innovatetype` VALUES ('8', '科研训练B', '公开发表学术论文、作品数,获准专利(著作权)\r\n参加国际学术会议并作交流', '3', '90分');
INSERT INTO `t_innovatetype` VALUES ('9', '科研训练C', '参与教师科研项目一学期以上且通过指导教师考核认可', '1', '90分');

-- ----------------------------
-- Table structure for `t_notice`
-- ----------------------------
DROP TABLE IF EXISTS `t_notice`;
CREATE TABLE `t_notice` (
  `noticeId` int(11) NOT NULL auto_increment COMMENT '公告id',
  `title` varchar(80) NOT NULL COMMENT '标题',
  `content` varchar(5000) NOT NULL COMMENT '公告内容',
  `publishDate` varchar(20) default NULL COMMENT '发布时间',
  PRIMARY KEY  (`noticeId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_notice
-- ----------------------------
INSERT INTO `t_notice` VALUES ('1', '大学生创新学分网站成立', '<p>同学们可以来这里提交你们的创新项目，可以获取到相应的学分哦！</p>', '2018-11-17 17:00:02');

-- ----------------------------
-- Table structure for `t_student`
-- ----------------------------
DROP TABLE IF EXISTS `t_student`;
CREATE TABLE `t_student` (
  `user_name` varchar(30) NOT NULL COMMENT 'user_name',
  `password` varchar(30) NOT NULL COMMENT '登录密码',
  `classObj` varchar(20) NOT NULL COMMENT '所在班级',
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `gender` varchar(4) NOT NULL COMMENT '性别',
  `birthDate` varchar(20) default NULL COMMENT '出生日期',
  `userPhoto` varchar(60) NOT NULL COMMENT '学生照片',
  `telephone` varchar(20) NOT NULL COMMENT '联系电话',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `address` varchar(80) default NULL COMMENT '家庭地址',
  `regTime` varchar(20) default NULL COMMENT '注册时间',
  PRIMARY KEY  (`user_name`),
  KEY `classObj` (`classObj`),
  CONSTRAINT `t_student_ibfk_1` FOREIGN KEY (`classObj`) REFERENCES `t_classinfo` (`classNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_student
-- ----------------------------
INSERT INTO `t_student` VALUES ('STU001', '123', 'BJ001', '张小凤', '女', '2018-11-12', 'upload/98ca63c15fd7d4eb0a38c005a649aa4a.jpg', '13058200843', 'xiaofeng@163.com', '四川成都红星路', '2018-11-17 16:58:34');
INSERT INTO `t_student` VALUES ('STU002', '123', 'BJ002', '李明明', '男', '2018-11-01', 'upload/e866060bcaa68b4b5e8b197a61bde645.jpg', '13598083432', 'mingming@163.com', '四川南充航空路12号', '2018-11-22 20:37:51');
