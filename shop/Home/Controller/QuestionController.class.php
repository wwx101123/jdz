<?php
namespace Home\Controller;

use Common\Model\Question;
use Think\Controller;
/*
 * 问答区
 * */
class QuestionController  extends CommonController{

    private function getQustion(){
        return new Question();
    }
    /*
     * 问答大厅
     * */
    public function index(){
        if(IS_GET) {
            echo 11;
        }elseif(IS_POST){
            echo 22;
        }
    }

    /*
     * 我的所有问答
     * */
    public function myAllQuestion(){

    }

    /*
     * 问答内容详情
     * @param $questionId int 问题Id
     * */
    public function questionDetail(){
        if(IS_GET){
            $questionId=I("questionId");
            return $this->display();
        }elseif(IS_POST){//对问题进行回答

        }
    }

    /*
     * 采纳回答
     * */
    public function adoptQuestion(){

    }

    /*
     *点赞回复 24小时内点赞一次
     * @param answerId  int  回复序列id
     * */
    public function praise(){
        if(IS_POST){
             $answerId=I("answerId");
             if(is_numeric($answerId)){
                 if(cookie("praise".$answerId)){
                     //当天该问题已经赞过
                 }else {
                     //执行点赞操作

                     //设置cookie
                     cookie("praise".$answerId,1,86400);
                 }
             }
        }
    }

    /*
     * 搜索热更新
     * */
    public function hostQuestion(){
        if(IS_POST){

        }else{
            //默认四条热点
        }
    }

    /**
     * 问题页
     */
    public function question()
    {
        $this->display();
    }

    /* 问题列表 */
    public function questionlist()
    {
        $this->display();
    }
    /* 搜索 */
    public function search()
    {
        $this->display();
    }
}