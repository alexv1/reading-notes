<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/6
 * Time: 下午3:14
 */

class Book extends Eloquent{

    private function getBookQuery(){
        return DB::table('book')
            ->select(DB::raw('book.bid,book.bname,book.author,book.pic_url,book.sid,book.tid,book.subtitle,book.share_url,
                book.dou_rate,book.dou_id,book.is_deleted'));
    }

    private function getUserBookCommentQuery(){
        return DB::table('user_book')
            ->join('book', 'user_book.bid', '=', 'book.bid')
            ->leftJoin('sys_category_second', 'book.sid', '=', 'sys_category_second.sid')
            ->leftJoin('sys_category_third', 'book.tid', '=', 'sys_category_third.tid')
            ->leftJoin('user_comment', function($join){
                $join->on('user_comment.uid','=','user_book.uid')
                    ->on('user_comment.bid','=', 'user_book.bid');
            })
            ->select(DB::raw('book.bid,book.bname,book.pic_url,book.author,book.sid,book.tid,book.publisher,
                book.dou_id,book.dou_rate,book.subtitle,book.share_url,
                sys_category_second.name as sname, sys_category_third.name as tname,
                user_book.read_status,user_book.note_count,user_book.update_time,user_book.tags,user_book.uid,
                user_book.source,user_book.done_time,
                ifnull(user_comment.star,0) as star,user_comment.cid,user_comment.title as c_title, user_comment.content as c_content'));
    }

    private function getUserBookQuery(){
        return DB::table('user_book')
            ->join('book', 'user_book.bid', '=', 'book.bid')
            ->leftJoin('sys_category_second', 'book.sid', '=', 'sys_category_second.sid')
            ->leftJoin('sys_category_third', 'book.tid', '=', 'sys_category_third.tid')
            ->select(DB::raw('book.bid,book.bname,book.pic_url,book.author,book.sid,book.tid,book.publisher,
                book.dou_id,book.dou_rate,book.subtitle,book.share_url,
                sys_category_second.name as sname, sys_category_third.name as tname,
                user_book.read_status,user_book.note_count,user_book.update_time,user_book.tags,user_book.uid,
                user_book.source,user_book.done_time'));
    }

    public function getAllBooks(){
        return $this->getBookQuery()
            ->orderBy('book.create_time', 'desc')
            ->get();
    }

    public function getLatestBooks(){
        return $this->getBookQuery()
            ->skip(0)
            ->take(5)
            ->orderBy('book.create_time', 'desc')
            ->get();
    }

    public function getMyBooks($user_id, $order){
        $db_part =  $this->getUserBookCommentQuery()
            ->where('user_book.uid', '=', $user_id);

        if($order == 0){
            // 按最近更新时间排序
            return $db_part->orderBy('user_book.update_time', 'desc')
                ->get();
        } else {
            // 按名称排序
            return $db_part->orderBy('book.bname', 'asc')
                ->get();
        }
    }

    public function searchBooks($word, $page, $size){
        return $this->getUserBookQuery()
            ->where(function($query) use($word)
            {
                $query->where('book.bname', 'like', '%'.$word.'%')
                    ->orWhere('book.subtitle', 'like', '%'.$word.'%')
                    ->orWhere('book.author', 'like', '%'.$word.'%');
            })
            ->orderByRaw('convert(`bname` USING gbk) COLLATE gbk_chinese_ci asc')
            ->orderBy('book.bid', 'desc')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();
    }

    public function searchBooksCount($word){
        $obj = DB::table('book')
            ->select(DB::raw('count(bid) as value'))
            ->where(function($query) use($word)
            {
                $query->where('bname', 'like', '%'.$word.'%')
                    ->orWhere('subtitle', 'like', '%'.$word.'%')
                    ->orWhere('author', 'like', '%'.$word.'%');
            })
            ->first();
        return $obj->value;
    }

    public function searchBooksByTag($tag, $page, $size){
        return $this->getUserBookQuery()
            ->where('tags', 'like', '%'.$tag.'%')
            ->orderByRaw('convert(`bname` USING gbk) COLLATE gbk_chinese_ci asc')
            ->orderBy('book.bid', 'desc')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();
    }

    public function searchBooksCountByTag($tag){
        $obj = DB::table('user_book')
            ->select(DB::raw('count(bid) as value'))
            ->where('tags', 'like', '%'.$tag.'%')
            ->first();
        return $obj->value;
    }

    public function getBooklistByBook($bookId){
        return DB::table('booklist')
            ->join('booklist_item', 'booklist.id', '=', 'booklist_item.list_id')
            ->join('book', 'booklist_item.bid', '=', 'book.bid')
            ->select(DB::raw('booklist.id,booklist.name,booklist.url,booklist.intro,booklist.create_time'))
            ->where('book.bid', $bookId)
            ->orderBy('booklist.create_time', 'desc')
            ->get();
    }

    public function getMyBooksByReadStatus($user_id, $status, $page=1, $size=10, $order=0){
        $db_part =  $this->getUserBookCommentQuery()
            ->where('user_book.uid', '=', $user_id)
            ->where('user_book.read_status', '=', $status)
            ->skip(($page - 1) * $size)
            ->take($size);

        // 增加 bid，防止乱序；
        if($order == 0){
            // 按最近更新时间排序
            return $db_part->orderBy('user_book.update_time', 'desc')
                ->orderBy('book.bid', 'asc')
                ->get();
        } else if($order == 2){
            // 按完成时间排序
            return $db_part->orderBy('user_book.done_time', 'desc')
                ->orderBy('book.bid', 'asc')
                ->get();
        } else if($order == 3){
            // 按 分类
            return $db_part->orderBy('book.sid', 'desc')
                ->orderBy('book.tid', 'desc')
                ->orderBy('user_book.update_time', 'desc')
                ->orderBy('book.bid', 'asc')
                ->get();
        }
        else {
            // 按名称排序
            return $db_part->orderBy('book.bname', 'asc')
                ->orderBy('book.bid', 'asc')
                ->get();
        }
    }

    public function getBookById($book_id){
        return $this->getUserBookCommentQuery()
            ->where('book.bid', '=', $book_id)
            ->first();
    }

    public function getBookByDoubanId($douban_id){
        return DB::table('book')
            ->where('book.dou_id', '=', $douban_id)
            ->first();
    }

    public function getMyBookById($book_id, $user_id){
        return $this->getUserBookCommentQuery()
            ->where('user_book.bid', '=', $book_id)
            ->where('user_book.uid', '=', $user_id)
            ->first();
    }

    /**
     * 查询我的书目的阅读状态
     * @return mixed
     */
    public function getMyBookCount($user_id){
        return DB::table('user_book')
            ->select(DB::raw('user_book.read_status as read_status, count(user_book.bid) as book_count'))
            ->where('user_book.uid', '=', $user_id)
            ->groupBy('user_book.read_status')
            ->get();
    }

    /**
     * 某月已读书目
     * @param $user_id
     * @param $month
     * @return mixed
     */
    public function getMyCollectBookCountByMonth($user_id, $month){
        $month_first = $month.'-01';
        $month_last = $month.'-31';

        $count = DB::table('user_book')
            ->select(DB::raw('count(user_book.bid) as value'))
            ->where('uid',$user_id)
            ->where('read_status', 2)
            ->where('done_time','>=', $month_first)
            ->where('done_time','<=', $month_last.' 23:59:59')
            ->first();
        return $count->value;
    }

    /**
     * 某年已读书目
     * @param $user_id
     * @param $year
     * @return mixed
     */
    public function getMyCollectBookCountByYear($user_id, $year){
        $year_first = $year.'-01-01';
        $year_last = $year.'-12-31';

        $count = DB::table('user_book')
            ->select(DB::raw('count(user_book.bid) as value'))
            ->where('uid',$user_id)
            ->where('read_status', 2)
            ->where('done_time','>=', $year_first)
            ->where('done_time','<=', $year_last.' 23:59:59')
            ->first();
        return $count->value;
    }

    /**
     * 查询我的书目的阅读状态
     * @return mixed
     */
    public function getMyBookCountByStatus($user_id, $status){
        $count = DB::table('user_book')
            ->select(DB::raw('count(user_book.bid) as value'))
            ->where('uid',$user_id)
            ->where('read_status', $status)
            ->first();
        return $count->value;
    }

    /**
     * 累计已读书目
     * @param $user_id
     * @return mixed
     */
    public function getMyCollectBookCount($user_id){
        return $this->getMyBookCountByStatus($user_id, 2);
    }

    /**
     * 累计在读书目
     * @param $user_id
     * @return mixed
     */
    public function getMyDoBookCount($user_id){
        return $this->getMyBookCountByStatus($user_id, 1);
    }

    /**
     * 累计想读书目
     * @param $user_id
     * @return mixed
     */
    public function getMyWishBookCount($user_id){
        return $this->getMyBookCountByStatus($user_id, 0);
    }

    public function getBookIdByName($name){
        $book = DB::table('book')
            ->where('bname', '=', $name)
            ->first();
        return empty($book) ? -1 : $book->bid ;
    }

    public function getBookIdByNameLike($name){
        $book = DB::table('book')
            ->where('bname', 'like', '%'.$name.'%')
            ->first();
        return empty($book) ? -1 : $book->bid ;
    }


    public function searchBooksBySecondCategory($sid, $page, $size, $bid=0){
        $query = $this->getUserBookCommentQuery()
            ->where('book.sid', '=', $sid);
        if($bid != 0){
            $query = $query->where('book.bid', '!=', $bid);
        }
        return $query->orderByRaw('convert(`bname` USING gbk) COLLATE gbk_chinese_ci asc')
            ->orderBy('book.bid', 'desc')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();
    }

    public function searchBooksCountBySecondCategory($sid){
        $obj = DB::table('user_book')
            ->join('book', 'user_book.bid', '=', 'book.bid')
            ->select(DB::raw('count(user_book.bid) as value'))
            ->where('book.sid', '=', $sid)
            ->first();
        return $obj->value;
    }

    public function searchBooksByThirdCategory($tid, $page, $size, $bid=0){
        $query = $this->getUserBookCommentQuery()
            ->where('book.tid', '=', $tid);
        if($bid != 0){
            $query = $query->where('book.bid', '!=', $bid);
        }
        return $query->orderByRaw('convert(`bname` USING gbk) COLLATE gbk_chinese_ci asc')
            ->orderBy('book.bid', 'desc')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();
    }

    public function searchBooksCountByThirdCategory($tid){
        $obj = DB::table('user_book')
            ->join('book', 'user_book.bid', '=', 'book.bid')
            ->select(DB::raw('count(user_book.bid) as value'))
            ->where('book.tid', '=', $tid)
            ->first();
        return $obj->value;
    }


    public function searchBooksByAuthor($author, $page, $size, $bid){
         $query = $this->getUserBookCommentQuery()
            ->where('book.author', '=', $author);
         if($bid != 0){
             $query = $query->where('book.bid', '!=', $bid);
         }

         return $query->orderByRaw('convert(`bname` USING gbk) COLLATE gbk_chinese_ci asc')
            ->orderBy('book.bid', 'desc')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();
    }

    public function advancedSearchBooks($user_id, $filter, $page, $size){
        $word = isset($filter['word']) ? $filter['word'] : '';

        // 两级分类
        $sid = isset($filter['sid']) ? $filter['sid'] : '-1';
        $tid = isset($filter['tid']) ? $filter['tid'] : '-1';

        $source = isset($filter['source']) ? $filter['source'] : '-1';
        $status = isset($filter['status']) ? $filter['status'] : '-1';

        $start = isset($filter['start']) ? $filter['start'] : '';
        $end = isset($filter['end']) ? $filter['end'] : '';

        $db_part =  $this->getUserBookCommentQuery()->where('user_book.uid', '=', $user_id);

        if($sid != '-1'){
            $db_part = $db_part->where('book.sid', '=', $sid);
        }
        if($tid != '-1'){
            $db_part = $db_part->where('book.tid', '=', $tid);
        }

        if($source != '-1'){
            $db_part = $db_part->where('user_book.source', '=', $source);
        }
        if($status != '-1'){
            $db_part = $db_part->where('user_book.read_status', '=', $status);
        }

        if($start != ''){
            if($status == 2){
                $db_part = $db_part->where('user_book.done_time', '>=', $start);
            } else {
                $db_part = $db_part->where('user_book.update_time', '>=', $start);
            }
        }
        if($end != ''){
            if($status == 2){
                $db_part = $db_part->where('user_book.done_time', '<=', $end.' 23:59:59');
            } else {
                $db_part = $db_part->where('user_book.update_time', '<=', $end.' 23:59:59');
            }
        }

        if($word != ''){
            $db_part = $db_part->where(function($query) use($word)
                {
                    $query->where('book.bname', 'like', '%'.$word.'%')
                        ->orWhere('book.subtitle', 'like', '%'.$word.'%')
                        ->orWhere('book.author', 'like', '%'.$word.'%')
                        ->orWhere('user_book.tags', 'like', '%'.$word.'%');
                });
        }

        // 增加 bid，防止乱序；
        return $db_part->orderBy('user_book.update_time', 'desc')
            ->orderBy('book.bid', 'asc')
            ->skip(($page - 1) * $size)->take($size)
            ->get();

    }


    public function advancedSearchBookCount($user_id, $filter){
        $word = isset($filter['word']) ? $filter['word'] : '';

        // 两级分类
        $sid = isset($filter['sid']) ? $filter['sid'] : '-1';
        $tid = isset($filter['tid']) ? $filter['tid'] : '-1';

        $source = isset($filter['source']) ? $filter['source'] : '-1';
        $status = isset($filter['status']) ? $filter['status'] : '-1';

        $start = isset($filter['start']) ? $filter['start'] : '';
        $end = isset($filter['end']) ? $filter['end'] : '';

        $db_part =  DB::table('user_book')
            ->join('book', 'user_book.bid', '=', 'book.bid')
            ->leftJoin('sys_category_second', 'book.sid', '=', 'sys_category_second.sid')
            ->leftJoin('sys_category_third', 'book.tid', '=', 'sys_category_third.tid')
            ->select(DB::raw('count(user_book.bid) as value'))
            ->where('user_book.uid', '=', $user_id);

        if($sid != '-1'){
            $db_part = $db_part->where('book.sid', '=', $sid);
        }
        if($tid != '-1'){
            $db_part = $db_part->where('book.tid', '=', $tid);
        }

        if($source != '-1'){
            $db_part = $db_part->where('user_book.source', '=', $source);
        }
        if($status != '-1'){
            $db_part = $db_part->where('user_book.read_status', '=', $status);
        }

        if($start != ''){
            if($status == 2){
                $db_part = $db_part->where('user_book.done_time', '>=', $start);
            } else {
                $db_part = $db_part->where('user_book.update_time', '>=', $start);
            }
        }
        if($end != ''){
            if($status == 2){
                $db_part = $db_part->where('user_book.done_time', '<=', $end.' 23:59:59');
            } else {
                $db_part = $db_part->where('user_book.update_time', '<=', $end.' 23:59:59');
            }
        }

        if($word != ''){
            $db_part = $db_part->where(function($query) use($word)
            {
                $query->where('book.bname', 'like', '%'.$word.'%')
                    ->orWhere('book.subtitle', 'like', '%'.$word.'%')
                    ->orWhere('book.author', 'like', '%'.$word.'%')
                    ->orWhere('user_book.tags', 'like', '%'.$word.'%');
            });
        }

        $obj = $db_part->first();
        return $obj->value;
    }

    public function getBook2ndCategoryStats($uid, $readStatus, $date1, $date2){
        $query = DB::table('user_book')
            ->join('book', 'user_book.bid', '=', 'book.bid')
            ->leftJoin('sys_category_second','book.sid', '=', 'sys_category_second.sid')
            ->select(DB::raw('ifnull(count(user_book.bid),0) as value, sys_category_second.name'))
            ->where('user_book.uid', $uid);

        if($readStatus != '-1'){
            $query = $query->where('user_book.read_status', $readStatus);
        }

        if($date1 != null || $date1 != ''){
            if($readStatus == 2){
                $query = $query->where('user_book.done_time', '>=', $date1);
            } else {
                $query = $query->where('user_book.update_time', '>=', $date1);
            }
        }
        if($date2 != null || $date2 != ''){
            if($readStatus == 2){
                $query = $query->where('user_book.done_time', '<=', $date2.' 23:59:59');
            } else {
                $query = $query->where('user_book.update_time', '<=', $date2.' 23:59:59');
            }
        }

        return $query
            ->groupBy('book.sid')
            ->orderBy('book.sid','asc')
            ->get();
    }

    public function getBook3rdCategoryStats($uid, $readStatus, $date1, $date2){
        $query = DB::table('user_book')
            ->join('book', 'user_book.bid', '=', 'book.bid')
            ->leftJoin('sys_category_third','book.tid', '=', 'sys_category_third.tid')
            ->select(DB::raw('ifnull(count(user_book.bid),0) as value, sys_category_third.name'))
            ->where('user_book.uid', $uid);

        if($readStatus != '-1'){
            $query = $query->where('user_book.read_status', $readStatus);
        }

        if($date1 != null || $date1 != ''){
            if($readStatus == 2){
                $query = $query->where('user_book.done_time', '>=', $date1);
            } else {
                $query = $query->where('user_book.update_time', '>=', $date1);
            }
        }
        if($date2 != null || $date2 != ''){
            if($readStatus == 2){
                $query = $query->where('user_book.done_time', '<=', $date2.' 23:59:59');
            } else {
                $query = $query->where('user_book.update_time', '<=', $date2.' 23:59:59');
            }
        }

        return $query
            ->groupBy('book.tid')
            ->orderBy('book.tid','asc')
            ->get();
    }

    public function getBookSourceStats($uid, $readStatus, $date1, $date2){
        $query = DB::table('user_book')
            ->join('sys_book_source','sys_book_source.id', '=', 'user_book.source')
            ->select(DB::raw('ifnull(count(user_book.bid),0) as value, sys_book_source.name'))
            ->where('user_book.uid', $uid);

        if($readStatus != '-1'){
            $query = $query->where('user_book.read_status', $readStatus);
        }

        if($date1 != null || $date1 != ''){
            if($readStatus == 2){
                $query = $query->where('user_book.done_time', '>=', $date1);
            } else {
                $query = $query->where('user_book.update_time', '>=', $date1);
            }
        }
        if($date2 != null || $date2 != ''){
            if($readStatus == 2){
                $query = $query->where('user_book.done_time', '<=', $date2.' 23:59:59');
            } else {
                $query = $query->where('user_book.update_time', '<=', $date2.' 23:59:59');
            }
        }

        return $query
            ->groupBy('user_book.source')
            ->orderBy('user_book.source','asc')
            ->get();
    }





}