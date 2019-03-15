<?php
/**
 * Created by PhpStorm.
 * User: ali
 * Date: 15/4/21
 * Time: 上午9:51
 */

class TemplateController extends BaseController {

    public function toListView(){
        return View::make('.list', array(
            'path' => $this->resourcePath
        ));
    }

    public function toAddView(){

        return View::make('.add', array(
            'path' => $this->resourcePath
        ));
    }

    public function toView(){
        return View::make('.view', array(
            'path' => $this->resourcePath
        ));
    }

    public function toEditView(){
        return View::make('.edit', array(
            'path' => $this->resourcePath
        ));
    }

    public function doAdd(){

    }

    public function doEdit(){

    }

    public function doDelete(){

    }

}