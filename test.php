<?php

/**
 * Created by PhpStorm.
 * User: wangxiaofeng
 * Date: 2/5/16
 * Time: 7:51 PM
 */
class test
{
    public function sum($a, $b)
    {
        return $a + $b;
    }


    public function calc()
    {
        $result = $this->sum(3,2);
        echo $result;
    }
}

$model = new test();
$model->calc();
