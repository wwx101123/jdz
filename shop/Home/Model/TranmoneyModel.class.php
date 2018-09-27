<?php
namespace Common\Model;

class TranmoneyModel extends BaseModel {
    public function __construct()
    {
        $this->setTableName("tranmoney");
    }
    /*
     * 创建明细数组
     * @param  $uid int 用户id
     * @param  $amount float 金额
     * @param  $type   int 明细类别(0->转账,1->积分相关【转出80%与转入20%（只记录转入数）】、
     * 【余额对换积分（正数）或积分释放余额（负数）（判断2个UID相等）】,2->积分释放到余额（记录余额）,
     * 3->【余额交易】挂求购,4->购买，5->【余额交易】出售，6->取消，7->购买众筹， 8->买入（增加余额），
     * 9->卖出（减余额），10->取消（卖家返回余额），11->后台操作-余额，12->后台操作-积分，13->余额兑换积分（记录余额），
     * 22->转入的人弹出领取红包 23 推荐赠送 24 购物送积分 25 提问悬赏 26 回答奖励 27 卖出保证金
     * 28 卖出保证金退回 29.见点奖 30.vip1奖励 31.vip2奖励 32.推荐奖 33.流通奖)
     * */
    public function createArr($uid,$amount,$type){
       $userInfo= M("store")->where(['uid'=>$uid])->field("cangku_num")->find();//查询用户余额信息
        return [
            'pay_id'=>$uid,
            'get_id'=>$uid,
            'get_nums'=>$amount,
            'get_time'=>time(),
            'get_type'=>$type,
            'now_nums'=>$userInfo['cangku_num']+$amount,
            'now_nums_get'=>$userInfo['cangku_num']+$amount,
            'is_release'=>1
        ];
    }
}