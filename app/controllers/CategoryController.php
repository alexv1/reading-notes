<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/6/11
 * Time: 下午7:59
 */

class CategoryController extends BaseController{

    public function getTidBySidInJson(){
        $input_vals = \Input::All();
        $sid = isset($input_vals['sid']) ? $input_vals['sid'] : '0';
        $catatoryDB = new Category();
        $data = $catatoryDB->getChildrenCategoryBySid($sid);
        return json_encode(array(
            'code' => 0,
            'data' => $data
        ));
    }

}