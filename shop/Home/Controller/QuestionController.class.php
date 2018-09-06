<?php
namespace Home\Controller;

use Faker\Calculator\Iban;
use Think\Controller;
/*
 * 问答区
 * */
class QuestionController  extends CommonController{

    private function getQuestion(){
        return M("Question");
    }

    private function getAnswer(){
        return M("Answer");
    }

    /*
     * 我的所有问答
     * */
    public function myAllQuestion(){

    }

    /*
     * 内容跟帖
     * @param $questionId int 问题Id
     * */
    public function questionDetail(){
        if(IS_POST){
            $answerArr=I("post.");
            if(is_array($answerArr)) {//对问题进行回答
                $data = [
                    "id"=>"",
                    'question_id' => $answerArr['questionId'],
                    'uid' => session("userid"),
                    'content' => $answerArr['content'],
                    'answer_time'=>time()
                ];
               echo $this->getAnswer()->add($data) ? 1 : 0;
            }
        }elseif(IS_GET){
           $questionId=I("get.questionId");
           if(is_numeric($questionId)){
            $qusetionArr=$this->getAnswer()
                ->alias("a")
                ->join("ysk_user b")
                ->where(["a.question_id"=>$questionId])
                ->where("a.uid=b.userid")
                ->field("a.*,b.username,b.img_head")
                ->order("a.is_it_best,a.praise,a.id desc")->select();
           echo $this->htmlDecode($qusetionArr);
           }
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
            $qusetionList=$this->getQuestion()->limit(8)->field("id,title")->order("pv desc")->select();
            echo $this->htmlDecode($qusetionList);
        }else{
        }
    }

    /**
     * 问题页
     */
    public function question()
    {
        if(IS_GET) {
            $this->display();
        }elseif(IS_POST){
            $id=I("post.id");
            if(is_numeric($id)){
                $this->getQuestion()->where(['id'=>$id])->setInc("pv");//该问题pv访问+1
              echo  json_encode($this->getQuestion()->where(['id'=>$id])->select()) ;
            }else{
                echo null;
            }

        }
    }
    public function htmlDecode($qusetionArr){
        $i=0;
        foreach ($qusetionArr as $key){
            $qusetionArr[$i]['content']=html_entity_decode($qusetionArr[$i]['content']);
            $i++;
        }
        return json_encode($qusetionArr);
    }
    /* 问题列表 */
    public function questionlist()
    {
        if(IS_GET) {
            $this->assign("questionArr",$this->getQuestion()->limit(10)->order("id desc")->select());
            $this->display();
        }elseif(IS_POST){
            if(I("post.model")==1){
                $questionId=I("post.questionId");
                //下拉刷新
                $questionList=$this->getQuestion()->where("id>$questionId")->limit(10)->order("id desc")->select();
                /*
                 $this->getQuestion()
                    ->alias("a")
                    ->join("ysk_answer b")
                    ->field("a.*,b.content mesg")
                    ->where("a.id=b.question_id")
                    ->where("a.id",">",$questionId)
                    ->limit(10)
                    ->order("a.id,b.is_it_best desc")
                    ->group("a.id")
                    ->select();
                 * */
            }else{
                //上拉加载
                $page=I("post.page");
                $limit=10;
                $questionList= $this->getQuestion()->limit($page*$limit,$limit)->order("id desc")->select();

            }
            echo $this->htmlDecode($questionList);
        }
    }
    /* 搜索 */
    public function search()
    {
        if(IS_GET) {
            $this->display();
        }elseif(IS_POST){
        }
    }
    /* 搜索 */
    public function searchTitle()
    {
        if(IS_GET) {
            $this->display();
            $searchValue=I("get.search");
            $qusetionList=$this->getQuestion()->limit(8)->field("id,title")->where("title like '%$searchValue%'")->order("id desc")->select();
            echo $this->htmlDecode($qusetionList);
        }elseif(IS_POST){
            $searchValue=I("post.search");
            $page=I("post.page");
            $limit=10;
            $qusetionList=$this->getQuestion()->limit($page*$limit,$limit)->where("title like '%$searchValue%'")->order("id desc")->select();
            echo $this->htmlDecode($qusetionList);
        }
    }


    /*
     * 提问
     * */
    public function report(){
        if(IS_GET) {
            return $this->display();
        }elseif(IS_POST){
           $questionArr=I("post.");

          if(!empty($questionArr['title'])){
              $data=[
                  "title"=>$questionArr['title'],
                  "content"=>$questionArr['content'],
                  'amount'=>$questionArr['amount']?$questionArr['amount'] : 0,
                  'uid'=>session('userid'),
                  'start_time'=>time(),
                  'thumbnail'=>str_replace("&quot;","",$this->getThumbanail($questionArr['content'])),
              ];
             echo $this->getQuestion()->add($data);
          }
        }
    }

    /*
     * 匹配内容中的预览图多图返回第一张
     * return imgPath string  预览图路径
     * */
    public function getThumbanail($content){
       $prc='/(href|src)=([\"|\']?)([^\"\'>]+.(jpg|JPG|jpeg|JPEG|gif|GIF|png|PNG))/i';
        preg_match_all($prc, $content, $out);
        return !empty($out[3][0]) ? $out[3][0] : "" ;//返回第一张正确图片地址
    }

    /*
     * 转换图片
     * return imgPath 图片保存路径
     * */
    public function base64ToImg($base64){

        if(stripos($base64,"data:image/png;base64,")>-1){
            $str="data:image/png;base64,";
            $ext="png";
        }elseif(stripos($base64,"data:image/jpeg;base64,")>-1){
            $str="data:image/jpeg;base64,";
            $ext="jpg";
        }elseif(stripos($base64,"data:image/gif;base64,")>-1){
            $str="data:image/gif;base64,";
            $ext="gif";
        }
        $imgStr=base64_decode(str_replace($str,"",$base64));//解码图片为二进制流
        $filePath="./Public/question/".date("Y-m-d",time());//文件保存目录
        if (!is_dir($filePath)) mkdir($filePath);//文件夹不存在则创建
        $fileName=md5(time()+mt_rand(1,100));//md5随机加密
        $imgPath=$filePath."/$fileName.".$ext;//完整图片路径
        echo file_put_contents($imgPath,$imgStr) ? json_encode(str_replace("./P","/P",$imgPath)) : null;//写入文件并返回数据
    }

    /*
     * 获取用户基本信息
     * 头像昵称id
     * */
    public function getUserInfo(){
        if(IS_POST){
            return json_encode(M("User")->UserInfo(['userid'=>session("userid")],'userid,username,img_head'));
        }
    }

}