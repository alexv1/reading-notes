<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/20
 * Time: 下午9:51
 */

class BooklistController extends BaseController{

    public function toMyListView(){
        $input_vals = \Input::All();
        $size = $this->page_size;
        $page = isset($input_vals['p']) ? $input_vals['p'] : 1;
        $booklistDB = new Booklist();
        $list = $booklistDB->getBooklists($page, $size);
        $listCount = $booklistDB->getBooklistsCount();
        $floor = floor($listCount/$size);
        $pageCount = ($listCount%$size == 0) ? $floor : ($floor + 1);

        return View::make('booklist.my-list', array(
            'path' => $this->resourcePath,
            'list' => $list,
            'list_count' => $listCount,
            'page' => $page,
            'page_count' => $pageCount,
            'page_size' => $size,
        ));
    }

    public function toMyView(){
        $input_vals = \Input::All();
        $blid = isset($input_vals['blid']) ? $input_vals['blid'] : '';
        $user_id = $this->getLoginUser();

        $booklistDB = new Booklist();
        $booklist = $booklistDB->getBooklistById($blid);

        $creator = $booklist->creator;
        $sameCreatorList = $booklistDB->getBooklistsInSameCrator($creator, $blid);
        $sameCreatorCount = $booklistDB->getBooklistCountInSameCrator($creator, $blid);

        $books = $booklistDB->getMyBooksByBooklist($blid, $user_id);
        $want = 0;
        $do = 0;
        $collect = 0;
        foreach($books as $b){
            if($b->read_status == 0){
                $want++;
            } else if($b->read_status == 1){
                $do++;
            } else if($b->read_status == 2){
                $collect++;
            }

        }

        return View::make('booklist.my-view', array(
            'path' => $this->resourcePath,
            'booklist' => $booklist,
            'same_creator_list' => $sameCreatorList,
            'same_creator_count' => $sameCreatorCount,
            'books' => $books,
            'want_count' => $want,
            'do_count' => $do,
            'collect_count' => $collect,
            'book_count' => count($books),
            'read_status' => $this->book_read_status
        ));
    }

    public function doAdd(){
        $input_vals = \Input::All();
        $name = isset($input_vals['name']) ? $input_vals['name'] : '';
        $url = isset($input_vals['url']) ? $input_vals['url'] : '';
        $intro = isset($input_vals['intro']) ? $input_vals['intro'] : '';
        $reason = isset($input_vals['reason']) ? $input_vals['reason'] : '';
        $creator = isset($input_vals['creator']) ? $input_vals['creator'] : '';
        $createTime = isset($input_vals['time']) ? $input_vals['time'] : '';

        $booklistDB = new Booklist();
        if($booklistDB->isBooklistNameExisted($name)){
            return Redirect::action('BooklistController@toMyListView', array(

            ));
        } else {
            $books = explode('<br>',wrapTextToHtml($intro));
            // 中文拼音排序
            $books = pinyinSort($books);

            $bookCount = count($books);
            $bookDB = new Book();
            DB::beginTransaction();
            $id = DB::table('booklist')->insertGetId(array(
                'name' => $name,
                'url' => $url,
                'intro' => join(PHP_EOL, $books),
                'reason' => $reason,
                'book_count' => $bookCount,
                'creator' => $creator,
                'create_time' => $createTime,
                'is_deleted' => 0
            ));

            $now = date('Y-m-d H:i:s', time());

            // 去重复
            $keyArray = array();
            foreach($books as $bookName){
                if($bookName == ''){
                    continue;
                }
                $bid = $bookDB->getBookIdByName($bookName);
                if($bid == -1){
                    $bid = $bookDB->getBookIdByNameLike($bookName);
                }
                if($bid == -1){
                    continue;
                }

                if($this->isContains($keyArray, $bid)){
                    continue;
                } else {
                    array_push($keyArray, $bid);
                }

                DB::table('booklist_item')->insertGetId(array(
                    'list_id' => $id,
                    'bid' => $bid,
                    'create_time' => $now,
                ));
            }

            DB::commit();
            return Redirect::action('BooklistController@toMyView', array(
                'blid' => $id
            ));
        }



    }

    private function isContains($array, $key){
        $isExisted = false;
        $len = count($array);
        for($i = 0; $i < $len; $i ++) {
            if($array[$i] == $key){
                $isExisted = true;
            }
        }
        return $isExisted;
    }


    public function isBooklistNameExisted(){
        $input_vals = \Input::All();
        $name = isset($input_vals['name']) ? $input_vals['name'] : '';

        $booklistDB = new Booklist();
        $status = $booklistDB->isBooklistNameExisted($name);
        return json_encode(array(
            'code' => $status ? 1 : 0
        ));
    }

    public function attachBookToBooklist(){
        $input_vals = \Input::All();
        $bookId = isset($input_vals['book_id']) ? $input_vals['book_id'] : 0;
        $booklistId = isset($input_vals['booklist_id']) ? $input_vals['booklist_id'] : 0;

        if($bookId > 0 && $booklistId!=0){
            $booklistDB = new Booklist();
            DB::beginTransaction();
            // 更新booklist
            if($booklistDB->isBookInBooklist($booklistId, $bookId) == false){
                $booklistDB->addBookInBooklist($booklistId, $bookId);
            }
            DB::commit();
        }

        return json_encode(array(
            'code' => 0
        ));
    }

    public function detachBookFromBooklist(){
        $input_vals = \Input::All();
        $bookId = isset($input_vals['book_id']) ? $input_vals['book_id'] : 0;
        $booklistId = isset($input_vals['booklist_id']) ? $input_vals['booklist_id'] : 0;

        if($bookId > 0 && $booklistId!=0){
            $booklistDB = new Booklist();
            DB::beginTransaction();
            $booklistDB->detachBookFromBooklist($booklistId, $bookId);
            DB::commit();
        }

        return Redirect::action('BooklistController@toMyView', array(
            'blid' => $booklistId
        ));
    }

    public function toEditView(){
        $input_vals = \Input::All();
        $blid = isset($input_vals['blid']) ? $input_vals['blid'] : '';

        $booklistDB = new Booklist();
        $booklist = $booklistDB->getBooklistById($blid);

        return View::make('booklist.edit', array(
            'booklist' => $booklist,
            'path' => $this->resourcePath
        ));
    }

    public function update() {
        $input_vals = \Input::All();
        $blid = isset($input_vals['blid']) ? $input_vals['blid'] : '';

        $name = isset($input_vals['name']) ? $input_vals['name'] : '';
        $url = isset($input_vals['url']) ? $input_vals['url'] : '';
        $intro = isset($input_vals['intro']) ? $input_vals['intro'] : '';
        $reason = isset($input_vals['reason']) ? $input_vals['reason'] : '';
        $creator = isset($input_vals['creator']) ? $input_vals['creator'] : '';
        $createTime = isset($input_vals['time']) ? $input_vals['time'] : '';

        $books = explode('<br>',wrapTextToHtml($intro));
        // 中文拼音排序
        $books = pinyinSort($books);
        $bookCount = count($books);

        DB::beginTransaction();

        DB::table('booklist')->where('id', $blid)->update(array(
            'name' => $name,
            'url' => $url,
            'intro' => join(PHP_EOL, $books),
            'reason' => $reason,
            'book_count' => $bookCount,
            'creator' => $creator,
            'create_time' => $createTime,
        ));

        DB::commit();
        return Redirect::action('BooklistController@toMyView', array(
            'blid' => $blid
        ));
    }


}