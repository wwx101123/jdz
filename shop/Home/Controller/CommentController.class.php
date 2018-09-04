<?php
/**
 * 本程序仅供娱乐开发学习，如有非法用途与本公司无关，一切法律责任自负！
 */
namespace Home\Controller;
use Think\Controller;
class CommentController extends Controller {
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

