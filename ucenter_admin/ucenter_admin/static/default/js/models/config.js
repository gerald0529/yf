//商品状态
var Goods_CommonModel = {};

Goods_CommonModel.GOODS_STATE_NORMAL  = 1;  //正常
Goods_CommonModel.GOODS_STATE_OFFLINE = 0;  //下架下架
Goods_CommonModel.GOODS_STATE_ILLEGAL = 10; //违规下架-禁售

Goods_CommonModel.GOODS_VERIFY_ALLOW   = 1;  //通过
Goods_CommonModel.GOODS_VERIFY_DENY    = 0;  //未通过
Goods_CommonModel.GOODS_VERIFY_WAITING = 10; //审核中


/*
  *  投诉相关表设置
 */
//1.BaseModel
COMPLAIN_FRESH  = 1;    //新投诉
COMPLAIN_APPEAL = 2;    //待申诉
COMPLAIN_TALK   = 3;    //对话中
COMPLAIN_HANDLE = 4;    //待仲裁
COMPLAIN_FINISH = 0;    //已关闭
VERIFY_PASS     = 1;    //审核通过


$complainStateMap = {
        '1' : '新投诉',
        '2' : '待申诉',    //投诉通过转给被投诉人
        '3' : '对话中',    //被投诉人已申诉
        '4' : '待仲裁',    //提交仲裁
        '0' : '已关闭'}

$complainState = {
        '1' : 'new',
        '2' : 'appeal',    //投诉通过转给被投诉人
        '3' : 'talk',    //被投诉人已申诉
        '4' : 'handle',    //提交仲裁
        '0' : 'finish'};

//2.TalkModel
COMPLAIN_ACCUSER = 1;    //投诉人
COMPLAIN_ACCUSED = 2;    //被投诉人
COMPLAIN_ADMIN             = 3;    //平台管理员
TALK_MASK              = 0;    //屏蔽对话


$complainTalkUserStateMap   = {'1' : '投诉人', '2' : '被投诉店铺', '3' : '管理员'};
$accTypeMap = {'1' : 'accuser', '2' : 'accused', '3' : 'admin'};

//3.SubjectModel
SUBJECT_MASK = 0;   //删除投诉主题

