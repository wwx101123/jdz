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
        if(IS_GET) {
            $this->display();
        }else{

        }
    }
    /* 搜索 */
    public function search()
    {
        $this->display();
    }

    /*
     * 提问
     * */
    public function report(){
        if(IS_GET) {
            return $this->display();
        }elseif(IS_POST){
           $questionArr=I("post.");
          $this->getImgPath($questionArr['content']);
        }
    }

    /*
     * 匹配Img标签并替换标签路径内容
     * */
    public function getImgPath($str){
        $imgpreg = 'src="(.*?)">';
        preg_match($imgpreg,$str,$img);
        var_dump($img);
        $mycount=count($img)-1;
        $imgval = $img[$mycount];
        if(!empty($imgval)){
            echo $imgval;
        }else{
            echo 'no';
        }
    }

    /*
     * 转换图片
     * return imgPath 图片保存路径
     * */
    public function base64ToImg($base64){

        if(strpos($base64,"data:image/png;base64,")>-1){
            $str="data:image/png;base64,";
            $ext="png";
        }elseif(strpos($base64,"data:image/jpg;base64,")>-1){
            $str="data:image/jpg;base64,";
            $ext="jpg";
        }elseif(strpos($base64,"data:image/gif;base64,")>-1){
            $str="data:image/gif;base64,";
            $ext="gif";
        }
        $imgStr=base64_decode(str_replace($str,"",$base64));//解码图片为二进制流
        $filePath="/question/".date("Y-m-d",time());//文件保存目录
        $fileName=md5(time()+rand(1,100000));//md5随机加密
        $imgPath=$filePath."/$fileName.".$ext;//完整图片路径
        file_put_contents($imgPath,$imgStr);//写入文件
        return $imgPath;
    }
}