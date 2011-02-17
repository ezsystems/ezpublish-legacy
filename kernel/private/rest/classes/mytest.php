<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oms
 * Date: 14.12.10
 * Time: 12.57
 * To change this template use File | Settings | File Templates.
 */
 
class myController extends ezcMvcController
{
    public function doMyActionOne()
    {
        $res = new ezcMvcResult();
        $res->variables['message'] = "A ringe ding ding";
        return $res;
    }

    public function doMyActionOneBetter()
    {
        $res = new ezcMvcResult();
        $res->variables['message'] = "A ringe ding ding, refined.";
        return $res;
    }
}
