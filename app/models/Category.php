<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/6/11
 * Time: 下午8:02
 */

class Category extends Eloquent{

    public function get3rdCategory($tid){
        return DB::table('sys_category_third')
            ->join('sys_category_second', 'sys_category_second.sid', '=', 'sys_category_third.sid')
            ->select(DB::raw('sys_category_third.tid,sys_category_third.name as tname,
                sys_category_second.sid, sys_category_second.name as sname'))
            ->where('sys_category_third.tid', '=', $tid)
            ->first();
    }

    public function get2ndCategory($sid){
        return DB::table('sys_category_second')
            ->select(DB::raw('sys_category_second.sid, sys_category_second.name as sname'))
            ->where('sys_category_second.sid', '=', $sid)
            ->first();
    }

    public function get2ndCategoryByFid($fid){
        return DB::table('sys_category_second')
            ->select(DB::raw('sid as id, name'))
            ->where('fid', $fid)
            ->get();
    }

    public function getChildrenCategoryBySid($sid){
        return DB::table('sys_category_third')
            ->select(DB::raw('tid as id, name as name'))
            ->where('sid', $sid)
            ->orderByRaw('convert(`name` USING gbk) COLLATE gbk_chinese_ci asc')
            ->get();
    }


}