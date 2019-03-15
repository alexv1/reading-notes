<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/7
 * Time: 上午11:23
 */

class Note extends Eloquent{

    private function getMyNoteQuery(){
        return DB::table('user_note')
            ->leftJoin('book', 'user_note.bid', '=', 'book.bid')
            ->leftJoin('user_common', 'user_note.uid', '=', 'user_common.user_id')
            ->select(DB::raw('book.bid,book.bname,book.pic_url,book.author,book.share_url,book.subtitle,
                user_note.nid,user_note.bid,user_note.uid,user_note.file_url,user_note.file_type,
                user_note.content,user_note.create_time,user_note.is_deleted,user_note.is_public,
                user_note.title,user_note.abstract,user_note.share_url,
                user_common.user_name,user_common.pic as user_pic'));
    }

    /**
     * 某月笔记数
     * @param $user_id
     * @param $month
     * @return mixed
     */
    public function getMyNoteCountByMonth($user_id, $month){
        $month_first = $month.'-01';
        $month_last = $month.'-31';

        $count = DB::table('user_note')
            ->select(DB::raw('count(user_note.nid) as value'))
            ->where('uid',$user_id)
            ->where('is_deleted', 0)
            ->where('create_time','>=', $month_first)
            ->where('create_time','<=', $month_last.' 23:59:59')
            ->first();
        return $count->value;
    }

    public function getMyNoteCount($user_id){
        $count = DB::table('user_note')
            ->select(DB::raw('count(user_note.nid) as value'))
            ->where('uid',$user_id)
            ->where('is_deleted', 0)
            ->first();
        return $count->value;
    }

    public function getMyBookNoteCount($uid, $bid){
        $count = DB::table('user_note')
            ->select(DB::raw('count(user_note.nid) as value'))
            ->where('uid',$uid)
            ->where('bid',$bid)
            ->where('is_deleted', 0)
            ->first();
        return $count->value;
    }

    public function getLatestNotes($user_id){
        return $this->getMyNoteQuery()
            ->where('user_note.uid', $user_id)
            ->where('user_note.is_deleted', 0)
            ->skip(0)
            ->take(5)
            ->orderBy('user_note.create_time', 'desc')
            ->get();
    }

    /**
     * 用于“我的书”详情
     * @param $book_id
     * @param $user_id
     * @return mixed
     */
    public function getMyNotesByBook($book_id, $user_id, $page=1, $size=10){
        return $this->getMyNoteQuery()
            ->where('user_note.bid', $book_id)
            ->where('user_note.uid', $user_id)
            ->where('user_note.is_deleted', 0)
            ->skip(($page - 1) * $size)
            ->take($size)
            ->orderBy('user_note.title', 'asc')
//            ->orderBy('user_note.create_time', 'desc')
            ->orderBy('user_note.nid', 'desc')
            ->get();
    }

    /**
     * 用于“系统设置”详情
     * @param $book_id
     * @param $user_id
     * @return mixed
     */
    public function getNotesByBook($book_id){
        return $this->getMyNoteQuery()
            ->where('user_note.bid', $book_id)
            ->where('user_note.is_deleted', 0)
//            ->orderByRaw('convert(`user_note.title` USING gbk) COLLATE gbk_chinese_ci asc')
            ->orderBy('user_note.id', 'asc')
            ->get();
    }

    /**
     * 用于“我的笔记”列表
     * @param $user_id
     * @return mixed
     */
    public function getMyNotes($user_id, $page=1, $size=10){
        return $this->getMyNoteQuery()
            ->where('user_note.uid', $user_id)
            ->where('user_note.is_deleted', 0)
            ->orderBy('user_note.create_time', 'desc')
            ->orderBy('user_note.nid', 'desc')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();
    }

    public function getNoteById($note_id){
        return $this->getMyNoteQuery()
            ->where('nid', '=', $note_id)
            ->first();
    }

    public function getNoteDraftByBook($uid, $bid){
        return DB::table('user_note_draft')
            ->where('uid', $uid)
            ->where('bid', $bid)
            ->where('is_deleted', 0)
            ->orderBy('create_time', 'desc')
            ->first();
    }

    public function getNoteDraftByNote($nid){
        return DB::table('user_note_draft')
            ->where('nid', $nid)
            ->where('is_deleted', 0)
            ->orderBy('create_time', 'desc')
            ->first();
    }
}