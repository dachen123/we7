<?php


if(!pdo_fieldexists('jy_ppp_setting', 'jiange')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `jiange` int(10) DEFAULT '30';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'shangxian')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `shangxian` int(10) DEFAULT '12';");
}


if(!pdo_fieldexists('jy_ppp_setting', 'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `thumb` int(2) NOT NULL DEFAULT '1';");
}

if(!pdo_fieldexists('jy_ppp_hf', 'parentid')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_hf')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_hf', 'type')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_hf')." ADD `type` int(2)  NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_hf', 'media_id')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_hf')." ADD `media_id` varchar(255) NOT NULL;");
}

if(!pdo_fieldexists('jy_ppp_hf', 'picurl')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_hf')." ADD `picurl` text;");
}

if(!pdo_fieldexists('jy_ppp_xinxi', 'picurl')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_xinxi')." ADD `picurl` text;");
}

if(!pdo_fieldexists('jy_ppp_xinxi', 'media_id')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_xinxi')." ADD `media_id` varchar(255) NOT NULL;");
}

if(!pdo_fieldexists('jy_ppp_xinxi', 'leixing')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_xinxi')." ADD `leixing` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_hf', 'sex')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_hf')." ADD `sex` int(2) DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'kjmsg_num')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `kjmsg_num` int(10) DEFAULT '10';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'kjmsg_jiange')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `kjmsg_jiange` int(10) DEFAULT '60';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'kjmsg_jiange2')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `kjmsg_jiange2` int(10) DEFAULT '60';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'moni')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `moni` int(2) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'bd_ak')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `bd_ak` varchar(255) NOT NULL;");
}

if(!pdo_fieldexists('jy_ppp_setting', 'indexui')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `indexui` int(2) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'unzhuce')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `unzhuce` int(2) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'user_addr')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `user_addr` int(2) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'youhuo_pay')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `youhuo_pay` int(2) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'mail_float')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `mail_float` int(2) NOT NULL DEFAULT '1';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'sql_style')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `sql_style` int(2) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'huafei')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `huafei` int(2) NOT NULL DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_member', 'qrcode_id')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_member')." ADD `qrcode_id` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_member', 'parentid')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_member')." ADD `parentid` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_price', 'huafei')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_price')." ADD `huafei` int(10) DEFAULT '0';");
}

if(!pdo_fieldexists('jy_ppp_setting', 'huafei_thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `huafei_thumb` varchar(255) NOT NULL;");
}

if(!pdo_fieldexists('jy_ppp_setting', 'stat_style')) {
	pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `stat_style` int(2) NOT NULL DEFAULT '0';");
}


if(!pdo_tableexists('jy_ppp_mailui')){
    $sql ="CREATE TABLE ".tablename('jy_ppp_mailui')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`weid` int(11)  NOT NULL,
		`displayorder` int(11)  NOT NULL DEFAULT '0' COMMENT 'ForOrder',
		`name` varchar(255) NOT NULL,
		`thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage',
		`description` varchar(255) NOT NULL,
		`enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
		PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_ppp_nickname')){
    $sql ="CREATE TABLE ".tablename('jy_ppp_nickname')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`weid` int(11)  NOT NULL,
		`displayorder` int(11)  NOT NULL DEFAULT '0' COMMENT 'ForOrder',
		`name` varchar(255) NOT NULL,
		`description` varchar(255) NOT NULL,
		`enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
		PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_ppp_hf')){
    $sql ="CREATE TABLE ".tablename('jy_ppp_hf')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`weid` int(11)  NOT NULL,
		`displayorder` int(11)  NOT NULL DEFAULT '0' COMMENT 'ForOrder',
		`name` varchar(255) NOT NULL,
		`type` int(2)  NOT NULL DEFAULT '0' COMMENT '0为文本，1为图片，2为语音，3为组合',
		`parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '批次ID,0为第一级',
		`media_id` varchar(255) NOT NULL,
		`picurl` text,
		`sex` int(2)  NOT NULL DEFAULT '0' COMMENT '0为不限，1为男，2为女',
		`description` varchar(255) NOT NULL,
		`enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
		PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_ppp_xuni_pay')){
    $sql ="CREATE TABLE ".tablename('jy_ppp_xuni_pay')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`weid` int(11)  NOT NULL,
		`displayorder` int(11)  NOT NULL DEFAULT '0' COMMENT 'ForOrder',
		`name` varchar(255) NOT NULL,
		`user` varchar(255) NOT NULL,
		`price` varchar(255) NOT NULL,
		`description` varchar(255) NOT NULL,
		`type` tinyint(1) unsigned NOT NULL DEFAULT '0' ,
		`enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
		PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_ppp_msg')){
    $sql ="CREATE TABLE ".tablename('jy_ppp_msg')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`weid` int(11)  NOT NULL,
		`parentid` int(11)  NOT NULL,
		`type` int(2)  NOT NULL DEFAULT '0' COMMENT '',
		`displayorder` int(11)  NOT NULL DEFAULT '0' COMMENT 'ForOrder',
		`name` varchar(255) NOT NULL,
		`media` text,
	  	`url` text,
	    `picurl` text,
	    `media_id` varchar(255) NOT NULL,
		`description` varchar(255) NOT NULL,
		`enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
		PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_ppp_xinxi_temp')){
    $sql ="CREATE TABLE IF NOT EXISTS ".tablename('jy_ppp_xinxi_temp')." (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `weid` int(10) NOT NULL,
	  `mid` int(10) NOT NULL DEFAULT '0',
	  `sendid` int(10) NOT NULL DEFAULT '0' COMMENT '发送人员',
	  `content` text,
	  `zhaohuid` int(10) DEFAULT '0' COMMENT '打招呼id',
	  `huifuid` int(10) DEFAULT '0' COMMENT '回复打招呼id',
	  `type` int(10) DEFAULT '0' COMMENT '0为打招呼,1为会员推荐,2为最新回信,3为管理员',
	  `leixing` int(10) DEFAULT '0' COMMENT '0为文本,1为图片，2为语音，3为组合',
	  `media_id` varchar(255) NOT NULL,
	  `picurl` text,
	  `yuedu` tinyint(1) DEFAULT '0' COMMENT '0为未读,1为已读',
	  `yuedutime` int(10) NOT NULL,
	  `createtime` int(10) NOT NULL,
	  PRIMARY KEY (`id`),
	  INDEX (`mid`),
	  INDEX (`sendid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_ppp_rule')){
    $sql ="CREATE TABLE IF NOT EXISTS ".tablename('jy_ppp_rule')." (
	    `id` int(11) NOT NULL AUTO_INCREMENT,
		`rulename` varchar(255) NOT NULL,
		`desc` text,
		`ruleid` int(11) NOT NULL,
		`uniacid` int(11) unsigned NOT NULL,
		PRIMARY KEY(`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}
