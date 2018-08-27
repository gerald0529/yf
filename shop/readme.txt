3.2.0
URL重写支持，请使用url函数，如 echo url('Goods_Goods/index',['id'=>1]); 详见http://wiki.yuanfeng.cn/443476
百度BOS支持


3.1.5
修正微信登录、微信支付的问题，原使用开放平台的接口。在微信中现使用mp.weixin.qq.com公众平台的接口。
修正WAP版店铺中的新品上新。
3.1.5 
删除掉 获取平台咨询列表时的条件 $cond_row['status'] = 0（表里面没有这个字段）;