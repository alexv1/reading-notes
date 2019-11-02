<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/20
 * Time: 下午9:52
 */

class Booklist extends Eloquent{

    public function getBooklists($page, $size){
        return DB::table('booklist')
            ->orderBy('create_time', 'desc')
            ->orderByRaw('convert(`name` USING gbk) COLLATE gbk_chinese_ci asc')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();
    }

    public function getBooklistsCount(){
        $obj = DB::table('booklist')
            ->select(DB::raw('count(id) as value'))
            ->first();
        return $obj->value;
    }

    public function searchBooklists($keyword, $page, $size){
        return DB::table('booklist')
            ->where('name', 'like', '%'.$keyword.'%')
            ->orderByRaw('convert(`name` USING gbk) COLLATE gbk_chinese_ci asc')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();
    }

    public function searchBooklistsCount($keyword){
        $obj = DB::table('booklist')
            ->select(DB::raw('count(id) as value'))
            ->where('name', 'like', '%'.$keyword.'%')
            ->first();
        return $obj->value;
    }

    public function getBooklistById($id){
        return DB::table('booklist')
            ->where('id', '=', $id)
            ->first();
    }

    public function getBooksByBooklist($listId){
        return DB::table('book')
            ->join('booklist_item', 'book.bid', '=', 'booklist_item.bid')
            ->select(DB::raw('book.bid,book.bname,book.pic_url,book.author,book.tid,book.dou_rate,book.subtitle,book.share_url'))
            ->where('booklist_item.list_id', '=', $listId)
            ->orderBy('book.bname', 'asc')
            ->get();
    }

    public function getMyBooksByBooklist($listId, $uid){
        return DB::table('book')
            ->join('booklist_item', 'book.bid', '=', 'booklist_item.bid')
            ->leftJoin('user_book', 'book.bid', '=', 'user_book.bid')
            ->select(DB::raw('book.bid,book.bname,book.pic_url,book.author,book.tid,book.dou_rate,book.subtitle,book.share_url,
                user_book.note_count,user_book.read_status,user_book.tags,user_book.update_time'))
            ->where('booklist_item.list_id', '=', $listId)
            ->where('user_book.uid', '=', $uid)
            ->orderBy('user_book.read_status')
            ->orderByRaw('convert(`bname` USING gbk) COLLATE gbk_chinese_ci asc')
            ->get();
    }

    public function isBookInBooklist($booklistId, $bookId){
        $obj = DB::table('booklist_item')
            ->select(DB::raw('count(1) as value'))
            ->where('list_id', $booklistId)
            ->where('bid', $bookId)
            ->first();
        return ($obj->value > 0 ) ? true : false ;
    }

    public function isBooklistNameExisted($name){
        $obj = DB::table('booklist')
            ->select(DB::raw('count(1) as value'))
            ->where('name', $name)
            ->first();
        return ($obj->value > 0 ) ? true : false ;
    }

    public function addBookInBooklist($booklistId, $bookId){
        DB::table('booklist_item')->insert(array(
            'list_id' => $booklistId,
            'bid' => $bookId,
            'create_time' => date('Y-m-d H:i:s', time())
        ));
    }

    public function updateBooklistIntro($booklistId, $bookId){
        $bookDB = new Book();
        $book = $bookDB->getBookById($bookId);
        $bookTitle = $book->bname;
        $this->updateBooklistIntroByTitle($booklistId, $bookTitle);
    }

    public function updateBooklistIntroByTitle($booklistId, $title){
        
        $booklist = $this->getBooklistById($booklistId);
        $array = explode('<br>', wrapTextToHtml($booklist->intro));
        $matchArray = array();
        foreach($array as $name){
            # 对中文友好
            $pos1 = stripos($name, $title);
            // Log::info($name.'#'.$title.' - '.$pos1);
            if($pos1 !== false) {
                array_push($matchArray, $name."   ✔");
            } else {
                array_push($matchArray, $name);
            }
        }

        DB::table('booklist')->where('id', $booklistId)->update(array(
            'intro' => join(PHP_EOL, $matchArray)
        ));
    }


    public function detachBookFromBooklist($booklistId, $bookId){
        DB::table('booklist_item')
            ->where('list_id', '=', $booklistId)
            ->where('bid', '=', $bookId)
            ->delete();;
    }

    public function getBooklistsInSameCrator($creator, $blid){
        return DB::table('booklist')
            ->where('creator', '=', $creator)
            ->where('id', '!=', $blid)
            ->orderBy('create_time', 'desc')
            ->skip(0)
            ->take(5)
            ->get();
    }

    public function getBooklistCountInSameCrator($creator, $blid){
        $obj = DB::table('booklist')
            ->select(DB::raw('count(id) as value'))
            ->where('creator', '=', $creator)
            ->where('id', '!=', $blid)
            ->first();
        return empty($obj) ? 0 : $obj->value;
    }

}