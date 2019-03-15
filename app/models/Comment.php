<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/7
 * Time: 上午11:23
 */

class Comment extends Eloquent{

    private function getCommentQuery(){
        return DB::table('user_comment')
            ->leftJoin('book', 'user_comment.bid', '=', 'book.bid')
            ->leftJoin('user_book', function($join){
                $join->on('user_comment.uid','=','user_book.uid')
                    ->on('user_comment.bid','=', 'user_book.bid');
            })
            ->leftJoin('user_common', 'user_comment.uid', '=', 'user_common.user_id')
            ->select(DB::raw('book.bid,book.bname,book.pic_url,book.author,book.share_url,book.subtitle,
                user_comment.title,user_comment.star,user_comment.content,user_comment.create_time,
                user_common.user_name,user_common.pic,
                ifnull(user_book.read_status,0) as read_status, ifnull(user_book.tags,"") as tags'));
    }

    /**
     * 某月评论数
     * @param $user_id
     * @param $month
     * @return mixed
     */
    public function getMyCommentCountByMonth($user_id, $month){
        $month_first = $month.'-01';
        $month_last = $month.'-31';

        $count = DB::table('user_comment')
            ->select(DB::raw('count(user_comment.cid) as value'))
            ->where('uid',$user_id)
            ->where('create_time','>=', $month_first)
            ->where('create_time','<=', $month_last.' 23:59:59')
            ->first();
        return $count->value;
    }

    public function getMyCommentCount($user_id){
        $count = DB::table('user_comment')
            ->select(DB::raw('count(user_comment.cid) as value'))
            ->where('uid',$user_id)
            ->first();
        return $count->value;
    }

    public function getLatestComments($user_id){
        return $this->getCommentQuery()
            ->where('user_comment.uid', $user_id)
            ->skip(0)
            ->take(5)
            ->orderBy('user_comment.create_time', 'desc')
            ->get();
    }

    /**
     * 用于“我的书评”列表
     * @param $user_id
     * @return mixed
     */
    public function getMyComments($user_id, $page=1, $size=10){
        return $this->getCommentQuery()
            ->where('user_comment.uid', $user_id)
            ->orderBy('user_comment.create_time', 'desc')
            ->orderBy('user_comment.cid', 'desc')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();
    }

    /**
     * 用于“系统设置”列表
     * @param $user_id
     * @return mixed
     */
    public function getCommentsByBook($book_id){
        return $this->getCommentQuery()
            ->where('user_comment.bid', $book_id)
            ->orderBy('user_comment.create_time', 'desc')
            ->get();
    }

    /**
     * 用于“系统设置”列表
     * @param $user_id
     * @return mixed
     */
    public function getComments(){
        return $this->getCommentQuery()
            ->orderBy('user_comment.create_time', 'desc')
            ->get();
    }

    /**
     * 用于“我的书评”列表
     * @param $user_id
     * @return mixed
     */
    public function getMyCommentByBook($book_id, $user_id){
        return $this->getCommentQuery()
            ->where('user_comment.uid', $user_id)
            ->where('user_comment.bid', $book_id)
            ->first();
    }

    public function getCommentById($comment_id){
        return $this->getCommentQuery()
            ->where('cid', '=', $comment_id)
            ->first();
    }

}