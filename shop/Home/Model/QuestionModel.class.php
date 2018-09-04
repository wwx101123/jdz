<?php
/**
 * 问题模型
 */

namespace Common\Model;

class Question extends BaseModel
{
    public function __construct()
    {
        $this->setTableName("question");
    }
}