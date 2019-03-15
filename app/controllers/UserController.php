<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 15/6/17
 * Time: 下午9:13
 */

class UserController extends BaseController {

    public function login(){
        $input_vals = Input::All();
        $user = new SysUser();
        $user_ret = $user->getUser($input_vals['user_name']);
        if(!empty($user_ret)){
            if(md5($input_vals['pw'].$user_ret->salt) == $user_ret->pw){
                $token=array();
                $token['user_name']=$input_vals['user_name'];
                if(empty($user_ret->real_name)){
                    $token['real_name']=$input_vals['user_name'];
                } else {
                    $token['real_name']=$user_ret->real_name;
                }
                $token['user_id']=$user_ret->user_id;
                $token['expiresAt']=date("Y-m-d H:i:s",time()+600000);

                $str = base64_encode(json_encode($token));
                Log::info(json_encode($token));
                Log::info($str);

                Session::put('backend_token', $str);

                return Redirect::to('/dashboard/index');
            }else{
                return Redirect::to('/login');
            }
        }
        else{
            return Redirect::to('/login');
        }
    }

    public function register(){
        $input_vals = Input::All();
        $user=new SysUser();
        $user->regUser($input_vals);
    }

}