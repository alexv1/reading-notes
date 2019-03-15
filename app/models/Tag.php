<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/26
 * Time: 上午9:13
 */

class Tag extends Eloquent{

    public function updateUserTags($userId, $bookId, $tags){
        // 1.过滤重复的tag

        // 2.写入or查询标签id
        $tagIdArray = array();
        foreach($tags as $tag){
            $id = $this->getOrInsertTag($tag);
            array_push($tagIdArray, $id);
        }

        // 3.删除用户的所有标签
        DB::table('user_tag')->where('bid', $bookId)->where('uid', $userId)->delete();

        $now = date('Y-m-d H:i:s', time());
        // 4.关联用户的新标签
        foreach($tagIdArray as $tagId){
            DB::table('user_tag')->insert(array(
                'bid' => $bookId,
                'uid' => $userId,
                'tid' => $tagId,
                'create_time' => $now
            ));
        }
    }

    public function getOrInsertTag($tag){
        $id = $this->isTagExisted($tag);
        if($id == -1){
            $id = DB::table('tag')->insertGetId(array(
                'name' => $tag,
                'is_deleted' => 0,
                'create_time' => date('Y-m-d H:i:s', time())
            ));
        }
        return $id;
    }

    public function isTagExisted($tag){
        $obj = DB::table('tag')
            ->where('name', $tag)
            ->first();
        return empty($obj) ? -1 : $obj->tid ;
    }
}