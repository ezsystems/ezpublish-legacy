<?php
class ezpRestTestController extends ezpRestMvcController
{
    public function doTest()
    {
        $ret = ezpRestMvcResult();
        $ret->variables['testVar'] = 'test';
        if( isset( $this->dummyVar ) )
        {
            $ret['dummy'] = $this->dummyVar;
        }
        
        return $ret;
    }
}
?>