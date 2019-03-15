<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 15/6/17
 * Time: 下午9:05
 */

class SysUser extends Eloquent{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_common';

    function getRandomSalt(){
        $salt = '' ;
        $tem = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = strlen($tem);
        for($i = 0 ; $i < 4 ; $i++){
            $salt .= $tem[rand() % $len];
        }
        return $salt;
    }

    /*
     * 用户注册
     */
    function regUser(array $backenduser){
        $salt = self::getRandomSalt();
        $password = md5($backenduser['pw'].$salt);
        try{
            $user_ret=self::getUser($backenduser['user_name']);
            if(empty($user_ret)){
                DB::table('user_common')
                    ->insert(
                        array(
                            'user_name' => $backenduser['user_name'],
                            'pw' => $password,
                            'salt' => $salt
                        )
                    );
                return true;
            }else{
                return false;
            }
        }catch (Exception $e){
            return false;
        }
    }

    /*
     * 获取用户信息
     */
    public function getUser($username){
        try{
            return DB::table('user_common')
                ->where('user_name','=',$username)
                ->first();
        }catch (Exception $e){
            return ;
        }
    }

    /*
    * 获取用户信息
    */
    public function getUserById($user_id){
        try{
            return DB::table('user_common')
                ->where('user_id','=',$user_id)
                ->first();
        }catch (Exception $e){
            return ;
        }
    }

}