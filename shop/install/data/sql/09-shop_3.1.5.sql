-- 新分销
CREATE TABLE `yf_fenxiao_cat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_id` int(10) NOT NULL COMMENT '分类id',
  `shop_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id 为零代表平台',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '当前操作用户id 为零代表平台',
  `level` tinyint(1) NOT NULL COMMENT '分销级别',
  `value` decimal(4,2) unsigned NOT NULL COMMENT '分销比例',
  `create` datetime NOT NULL COMMENT '创建时间',
  `update` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分销分类表';



CREATE TABLE `yf_fenxiao_commission` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_goods_id` int(10) NOT NULL COMMENT '订单商品表id 注意此id为fenxiao_order_goods主键',
  `price` decimal(10,2) NOT NULL COMMENT '佣金',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `create` datetime NOT NULL COMMENT '创建时间',
  `end` datetime NOT NULL COMMENT '结束时间（用户确认收货）',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分销佣金流水表';



CREATE TABLE `yf_fenxiao_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) NOT NULL COMMENT '店铺id（冗余）',
  `goods_id` int(10) NOT NULL COMMENT '商品id',
  `version` int(10) NOT NULL DEFAULT '1' COMMENT '版本号',
  `level` tinyint(1) NOT NULL COMMENT '分销级别',
  `value` decimal(4,2) unsigned NOT NULL COMMENT '分销比例',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分销商品表';



CREATE TABLE `yf_fenxiao_order_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '订单商品表id',
  `order_goods_id` int(10) NOT NULL COMMENT '订单商品表id',
  `order_id` varchar(50) NOT NULL COMMENT '订单id',
  `shop_id` int(10) NOT NULL COMMENT '店铺id（冗余）',
  `goods_id` int(10) NOT NULL COMMENT '商品id（冗余）',
  `num` int(10) NOT NULL COMMENT '数量',
  `price` decimal(10,2) NOT NULL COMMENT '实付金额总的（冗余）',
  `version` int(10) NOT NULL COMMENT '关联分销商品表版本号',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分销订单商品表';



CREATE TABLE `yf_fenxiao_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `parent_id` varchar(50) NOT NULL COMMENT '上级用户id',
  `create` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分销会员关系表';

