<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/6
 * Time: 下午3:14
 */

class BookController extends BaseController{

    public function toAllListView(){
        $bookDB = new Book();
        $list = $bookDB->getAllBooks();

        return View::make('book.all-list', array(
            'path' => $this->resourcePath,
            'list' => $list
        ));
    }

    public function toMyListView(){
        // 时间：本月，本年
        $month = getMonth();
        $year = getYear();
        $user_id = $this->getLoginUser();

        $bookDB = new Book();
        $noteDB = new Note();
        $commentDB = new Comment();

        $countArray = array();
        // 1.数字：今年已读，累计已读
        $collectCountYear = $bookDB->getMyCollectBookCountByYear($user_id, $year);
        $collectCountAll = $bookDB->getMyCollectBookCount($user_id);
        array_push($countArray,$collectCountYear);
        array_push($countArray,$collectCountAll);

        // 2.数字，所有在读书目，本月已读
        $doCount = $bookDB->getMyDoBookCount($user_id);
        $collectCountMonth = $bookDB->getMyCollectBookCountByMonth($user_id, $month);
        array_push($countArray, $doCount);
        array_push($countArray, $collectCountMonth);

        $wishCount = $bookDB->getMyWishBookCount($user_id);


        // 3.数字，本月笔记，本月评论
        $noteCountMonth = $noteDB->getMyNoteCountByMonth($user_id, $month);
        $commentCountMonth = $commentDB->getMyCommentCountByMonth($user_id, $month);
        array_push($countArray, $noteCountMonth);
        array_push($countArray, $commentCountMonth);

        // 4.在读书目清单，想读书目清单
        $doList = $bookDB->getMyBooksByReadStatus($user_id, $this->book_read_status_do, 0, 12, $this->book_order_updatetime);
        $wishList = $bookDB->getMyBooksByReadStatus($user_id, $this->book_read_status_wish, 0, 6, $this->book_order_updatetime);
        $collectList = $bookDB->getMyBooksByReadStatus($user_id, $this->book_read_status_collect, 0, 6, $this->book_order_updatetime);

        // 5.读书统计，每月曲线；类型统计

        // 6.最新书目，最新笔记，最新书评
        $latestBooks = $bookDB->getLatestBooks();
        $latestNotes = $noteDB->getLatestNotes($user_id);
        $latestComments = $commentDB->getLatestComments($user_id);

        $noteCount = $noteDB->getMyNoteCount($user_id);
        $commentCount = $commentDB->getMyCommentCount($user_id);

        return View::make('book.my-list', array(
            'path' => $this->resourcePath,
            'year' => $year,
            'count_array' => $countArray,
            'do_list' => $doList,
            'do_count' => $doCount,
            'wish_list' => $wishList,
            'wish_count' => $wishCount,
            'collect_list' => $collectList,
            'collect_count' => $collectCountAll,
            'latest_books' => $latestBooks,
            'latest_notes' => $latestNotes,
            'note_count' => $noteCount,
            'comments' => $latestComments,
            'comment_count' => $commentCount,
            'rest_days' => $this->rest_days_array
        ));
    }

    public function toDoListView(){
        $input_vals = \Input::All();
        $page = isset($input_vals['p']) ? $input_vals['p'] : 1;
        $size = 10000;

        $user_id = $this->getLoginUser();
        $bookDB = new Book();
        $bookList = $bookDB->getMyBooksByReadStatus($user_id, $this->book_read_status_do, $page, $size, $this->book_order_sid);
        $bookCount = $bookDB->getMyDoBookCount($user_id);

        $categoryDB = new Category();
        $secondCategory = $categoryDB->get2ndCategoryByFid($this->fid_book);

        $floor = floor($bookCount/$size);
        $pageCount = ($bookCount%$size == 0) ? $floor : ($floor + 1);

        // 统计数据
        $readStatus = $this->book_read_status_do;

        $secondCategoryList = $bookDB->getBook2ndCategoryStats($user_id, $readStatus, null, null);
        $secondCategoryArray = array();
        foreach($secondCategoryList as $obj){
            array_push($secondCategoryArray, $obj->name);
        }

        $thirdCategoryList = $bookDB->getBook3rdCategoryStats($user_id, $readStatus, null, null);
        $thirdCategoryArray = array();
        foreach($thirdCategoryList as $obj){
            array_push($thirdCategoryArray, $obj->name);
        }

        $sourceList = $bookDB->getBookSourceStats($user_id, $readStatus, null, null);
        $sourceArray = array();
        foreach($sourceList as $obj){
            array_push($sourceArray, $obj->name);
        }


        return View::make('book.my-do-list', array(
            'path' => $this->resourcePath,
            'books' => $bookList,
            'book_count' => $bookCount,
            'page' => $page,
            'page_count' => $pageCount,
            'page_size' => $size,
            'source_options' => $this->userbook_source_options,
            'source_array' => $this->userbook_source_array,
            'second_category' => $secondCategory,
            'legend_data_second_category' => json_encode($secondCategoryArray),
            'series_data_second_category' => json_encode($secondCategoryList),
            'legend_data_third_category' => json_encode($thirdCategoryArray),
            'series_data_third_category' => json_encode($thirdCategoryList),
            'legend_data_source' => json_encode($sourceArray),
            'series_data_source' => json_encode($sourceList),
            'read_status_options' => $this->book_read_status_options,
            'read_status_array' => $this->book_read_status
        ));
    }

    public function toWishListView(){
        $input_vals = \Input::All();
        $page = isset($input_vals['p']) ? $input_vals['p'] : 1;
        $size = $this->page_size;

        $user_id = $this->getLoginUser();
        $bookDB = new Book();
        $bookList = $bookDB->getMyBooksByReadStatus($user_id, $this->book_read_status_wish, $page, $size, $this->book_order_updatetime);
        $bookCount = $bookDB->getMyWishBookCount($user_id);

        $categoryDB = new Category();
        $secondCategory = $categoryDB->get2ndCategoryByFid($this->fid_book);

        $floor = floor($bookCount/$size);
        $pageCount = ($bookCount%$size == 0) ? $floor : ($floor + 1);

        // 统计数据
        $readStatus = $this->book_read_status_wish;

        $secondCategoryList = $bookDB->getBook2ndCategoryStats($user_id, $readStatus, null, null);
        $secondCategoryArray = array();
        foreach($secondCategoryList as $obj){
            array_push($secondCategoryArray, $obj->name);
        }

        $thirdCategoryList = $bookDB->getBook3rdCategoryStats($user_id, $readStatus, null, null);
        $thirdCategoryArray = array();
        foreach($thirdCategoryList as $obj){
            array_push($thirdCategoryArray, $obj->name);
        }

        $sourceList = $bookDB->getBookSourceStats($user_id, $readStatus, null, null);
        $sourceArray = array();
        foreach($sourceList as $obj){
            array_push($sourceArray, $obj->name);
        }


        return View::make('book.my-wish-list', array(
            'path' => $this->resourcePath,
            'books' => $bookList,
            'book_count' => $bookCount,
            'page' => $page,
            'page_count' => $pageCount,
            'page_size' => $size,
            'source_options' => $this->userbook_source_options,
            'source_array' => $this->userbook_source_array,
            'second_category' => $secondCategory,
            'legend_data_second_category' => json_encode($secondCategoryArray),
            'series_data_second_category' => json_encode($secondCategoryList),
            'legend_data_third_category' => json_encode($thirdCategoryArray),
            'series_data_third_category' => json_encode($thirdCategoryList),
            'legend_data_source' => json_encode($sourceArray),
            'series_data_source' => json_encode($sourceList),
            'read_status_options' => $this->book_read_status_options,
            'read_status_array' => $this->book_read_status
        ));
    }

    public function toCollectListView(){
        $input_vals = \Input::All();
        $page = isset($input_vals['p']) ? $input_vals['p'] : 1;
        $size = $this->page_size;

        $user_id = $this->getLoginUser();
        $bookDB = new Book();
        $bookList = $bookDB->getMyBooksByReadStatus($user_id, $this->book_read_status_collect, $page, $size, $this->book_order_donetime);
        $bookCount = $bookDB->getMyCollectBookCount($user_id);

        $categoryDB = new Category();
        $secondCategory = $categoryDB->get2ndCategoryByFid($this->fid_book);

        $floor = floor($bookCount/$size);
        $pageCount = ($bookCount%$size == 0) ? $floor : ($floor + 1);

        // 统计数据
        $year = getYear();
//        $year_first = $year.'-01-01';
//        $year_last = $year.'-12-31 23:59:59';
        $last6Month1stDay = getLast6Month();

        $day_first = $last6Month1stDay;
        $day_last = date('Y-m-d',time());
        $readStatus = $this->book_read_status_collect;

        $collectCountYear = $bookDB->getMyCollectBookCountByYear($user_id, $year);

        $secondCategoryList = $bookDB->getBook2ndCategoryStats($user_id, $readStatus, $day_first, $day_last);
        $secondCategoryArray = array();
        foreach($secondCategoryList as $obj){
            array_push($secondCategoryArray, $obj->name);
        }

        $thirdCategoryList = $bookDB->getBook3rdCategoryStats($user_id, $readStatus, $day_first, $day_last);
        $thirdCategoryArray = array();
        foreach($thirdCategoryList as $obj){
            array_push($thirdCategoryArray, $obj->name);
        }

        $sourceList = $bookDB->getBookSourceStats($user_id, $readStatus, $day_first, $day_last);
        $sourceArray = array();
        foreach($sourceList as $obj){
            array_push($sourceArray, $obj->name);
        }

        // 最近n个月的已读数据统计，统计到大类

        $dataLatest6Month = $this->getCollectBookMonthStats($user_id, $last6Month1stDay);

        $monthCategory = array();
        foreach($secondCategory as $obj){
            array_push($monthCategory, $obj->name);
        }

        $latestMonths = array();
        foreach($dataLatest6Month as $obj){
            $key = $obj->ym;
            $latestMonths[$key] = $key;
        }

        $series = array();
        foreach($secondCategory as $c){
            $data = array();
            foreach($latestMonths as $m){
                $isMatch = false;
                foreach($dataLatest6Month as $obj){
                    if($obj->sid == $c->id && $obj->ym == $m){
                        $isMatch = true;
                        array_push($data, $obj->value);
                        break;
                    }
                }
                if($isMatch == false){
                    array_push($data, 0);
                }
            }
            $item = array(
                'name' => $c->name,
                'type' => 'line',
                'stack' => '总量',
                'areaStyle' => array(
                    'normal' => array()
                ),
//                'smooth' => true,
                'data' => $data
            );
            array_push($series, $item);
        }


        return View::make('book.my-collect-list', array(
            'path' => $this->resourcePath,
            'books' => $bookList,
            'book_count' => $bookCount,
            'page' => $page,
            'page_count' => $pageCount,
            'page_size' => $size,
            'book_count_year' => $collectCountYear,
            'source_options' => $this->userbook_source_options,
            'source_array' => $this->userbook_source_array,
            'second_category' => $secondCategory,
            'year' => $day_first.' ~ '.$day_last,
            'n' => $last6Month1stDay,
            'month_legend_data' => json_encode($monthCategory),
            'month_xAxis_category' => json_encode(array_keys($latestMonths)),
            'month_series' => json_encode($series),
            'legend_data_second_category' => json_encode($secondCategoryArray),
            'series_data_second_category' => json_encode($secondCategoryList),
            'legend_data_third_category' => json_encode($thirdCategoryArray),
            'series_data_third_category' => json_encode($thirdCategoryList),
            'legend_data_source' => json_encode($sourceArray),
            'series_data_source' => json_encode($sourceList),
            'read_status_options' => $this->book_read_status_options,
            'read_status_array' => $this->book_read_status
        ));
    }

    private function getCollectBookMonthStats($uid, $date1){
        $query = DB::table('user_book')
            ->join('book', 'user_book.bid', '=', 'book.bid')
            ->leftJoin('sys_category_second','book.sid', '=', 'sys_category_second.sid')
            ->select(DB::raw('ifnull(count(user_book.bid),0) as value, sys_category_second.name,sys_category_second.sid,
                month(user_book.done_time) as month_d, DATE_FORMAT(done_time, "%Y-%m") as ym'))
            ->where('user_book.uid', $uid);
        if($date1 != null){
            $query = $query->where('user_book.done_time', '>=', $date1);
        }
        return $query
            ->groupBy('book.sid')
            ->groupBy('month_d')
            ->orderBy('ym','asc')
            ->orderBy('book.sid','asc')
            ->get();
    }

    public function getMyBookCount(){
        $bookDB = new Book();
        $user_id = $this->getLoginUser();
        $list = $bookDB->getMyBookCount($user_id);

        $dataArray = array(0, 0, 0);
        $myCount = 0;
        foreach ($list as $obj) {
            $state = $obj->read_status;
            $count = $obj->book_count;
            $dataArray[$state] = $count;
            $myCount += $count;
        }


        return json_encode(array(
            'stats' => $dataArray,
            'book_count' => $myCount
        ));
    }


    public function toAddView(){

        $categoryDB = new Category();
        $secondCaregory = $categoryDB->get2ndCategoryByFid($this->fid_book);

        return View::make('book.add', array(
            'second_category' => $secondCaregory,
            'path' => $this->resourcePath
        ));
    }

    /**
     * 显示我的读书
     * @return mixed
     */
    public function toView(){
        $input_vals = \Input::All();
        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '';

        $bookDB = new Book();
        $noteDB = new Note();
        $commentDB = new Comment();

        $book = $bookDB->getBookById($bid);

        // 笔记，书评
        $notes = $noteDB->getNotesByBook($bid);
        $comments = $commentDB->getCommentsByBook($bid);

        return View::make('book.view', array(
            'path' => $this->resourcePath,
            'book' => $book,
            'notes' => $notes,
            'comments' => $comments,
            'read_status' => $this->book_read_status
        ));
    }

    /**
     * 显示我的读书
     * @return mixed
     */
    public function toMyView(){
        $input_vals = \Input::All();
        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '';

        $bookDB = new Book();
        $noteDB = new Note();
        $commentDB = new Comment();
        $userId = $this->getLoginUser();


        $book = $bookDB->getMyBookById($bid, $userId);

        $booklist = $bookDB->getBooklistByBook($bid);

        $page = 1;
        $size = 4;
        $maxSize = 8;
        // 同作者书目
        $recommendBooks = $bookDB->searchBooksByAuthor($book->author, $page, $size, $bid);

        // 同类书目，优选查询 小类别
        $categoryBooks = $bookDB->searchBooksByThirdCategory($book->tid, $page, $size*2, $bid);

        $recommendBooks = $this->mergeArray($recommendBooks, $categoryBooks, $maxSize);
        if(count($recommendBooks) < $maxSize){
            $categoryBooks = $bookDB->searchBooksBySecondCategory($book->sid, $page, $size*2, $bid);
            $recommendBooks = $this->mergeArray($recommendBooks, $categoryBooks, $maxSize);
        }
        // 合并
//        if(!empty($categoryBooks)){
//            foreach($categoryBooks as $d1){
//                if(count($recommendBooks) >=$maxSize){
//                    break;
//                }
//                $isExisted = false;
//                foreach($recommendBooks as $d2){
//                    if($d1->bid == $d2->bid){
//                        $isExisted = true;
//                    }
//                }
//                if(!$isExisted){
//                    array_push($recommendBooks, $d1);
//                }
//            }
//        }

        // 笔记，书评
        $notes = $noteDB->getMyNotesByBook($bid, $userId, 1, 100);
        $comment = $commentDB->getMyCommentByBook($bid, $userId);


        $categoryDB = new Category();
        $secondCategory = $categoryDB->get2ndCategoryByFid($this->fid_book);
        $thirdCategory = $categoryDB->getChildrenCategoryBySid($book->sid);

        return View::make('book.my-view', array(
            'path' => $this->resourcePath,
            'book' => $book,
            'notes' => $notes,
            'comment' => $comment,
            'booklist' => $booklist,
            'recommend_books' => $recommendBooks,
            'read_status' => $this->book_read_status,
            'second_category' => $secondCategory,
            'third_category' => $thirdCategory
        ));
    }

    private function mergeArray($array1, $array2, $maxSize){
        if(!empty($array2)){
            foreach($array2 as $d1){
                if(count($array1) >=$maxSize){
                    break;
                }
                $isExisted = false;
                foreach($array1 as $d2){
                    if($d1->bid == $d2->bid){
                        $isExisted = true;
                    }
                }
                if(!$isExisted){
                    array_push($array1, $d1);
                }
            }
        }
        return $array1;
    }

    public function toEditView(){
        $input_vals = \Input::All();
        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '';
        $userId = $this->getLoginUser();
        $bookDB = new Book();
        $book = $bookDB->getMyBookById($bid, $userId);

        $categoryDB = new Category();
        $secondCategory = $categoryDB->get2ndCategoryByFid($this->fid_book);
        $thirdCategory = $categoryDB->getChildrenCategoryBySid($book->sid);

        return View::make('book.edit', array(
            'second_category' => $secondCategory,
            'third_category' => $thirdCategory,
            'path' => $this->resourcePath,
            'book' => $book,
        ));
    }

    public function doAdd(){
        $input_vals = \Input::All();
        $userId = $this->getLoginUser();
        $bname = isset($input_vals['bname']) ? $input_vals['bname'] : '';
        $doubanId = isset($input_vals['dou_id']) ? $input_vals['dou_id'] : '0';
        $subtitle = isset($input_vals['subtitle']) ? $input_vals['subtitle'] : '';
        $author = isset($input_vals['author']) ? $input_vals['author'] : '';
        $pic_url = isset($input_vals['pic_url']) ? $input_vals['pic_url'] : '';
        $publisher = isset($input_vals['publisher']) ? $input_vals['publisher'] : '';
        $dou_rate = isset($input_vals['dou_rate']) ? $input_vals['dou_rate'] : '0';
        $share_url = isset($input_vals['share_url']) ? $input_vals['share_url'] : '';
        $sid = isset($input_vals['sid']) ? $input_vals['sid'] : '0';
        $tid = isset($input_vals['tid']) ? $input_vals['tid'] : '0';

        DB::beginTransaction();
        $bookId = 0;
        $now = date('Y-m-d H:i:s', time());

        $bookId = DB::table('book')->insertGetId(array(
            'bname' => $bname,
            'dou_id' => $doubanId,
            'subtitle' => $subtitle,
            'author' => $author,
            'pic_url' => $pic_url,
            'publisher' => $publisher,
            'dou_rate' => $dou_rate,
            'is_deleted' => 0,
            'share_url' => $share_url,
            'create_time' => $now,
            'fid' => $this->fid_book,
            'sid' => $sid,
            'tid' => $tid
        ));


        // if($doubanId == 0){
            
        // } else {
        //     $bookId = $this->saveBookFromDouban($doubanId, $bname);
        //     if($bookId == -1){
        //         $bookId = DB::table('book')->insertGetId(array(
        //             'bname' => $bname,
        //             'fid' => $this->fid_book,
        //             'douban_id' => $doubanId,
        //             'create_time' => $now
        //         ));
        //     }
        // }
        $this->addUserBook($bookId, $userId);

        DB::commit();
        return Redirect::action('BookController@toMyView', array(
            'bid' => $bookId
        ));

    }

    public function doEdit() {
        $input_vals = \Input::All();
        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '0';

        $bname = isset($input_vals['bname']) ? $input_vals['bname'] : '';
        $doubanId = isset($input_vals['dou_id']) ? $input_vals['dou_id'] : '0';
        $subtitle = isset($input_vals['subtitle']) ? $input_vals['subtitle'] : '';
        $author = isset($input_vals['author']) ? $input_vals['author'] : '';
        $pic_url = isset($input_vals['pic_url']) ? $input_vals['pic_url'] : '';
        $publisher = isset($input_vals['publisher']) ? $input_vals['publisher'] : '';
        $dou_rate = isset($input_vals['dou_rate']) ? $input_vals['dou_rate'] : '0';
        $sid = isset($input_vals['sid']) ? $input_vals['sid'] : '0';
        $tid = isset($input_vals['tid']) ? $input_vals['tid'] : '0';
        $share_url = isset($input_vals['share_url']) ? $input_vals['share_url'] : '';

        DB::beginTransaction();

        $now = date('Y-m-d H:i:s', time());

        DB::table('book')->where('bid', $bid)->update(array(
            'bname' => $bname,
            'dou_id' => $doubanId,
            'subtitle' => $subtitle,
            'author' => $author,
            'pic_url' => $pic_url,
            'publisher' => $publisher,
            'dou_rate' => $dou_rate,
            'share_url' => $share_url,
            'create_time' => $now,
            'sid' => $sid,
            'tid' => $tid
        ));

        DB::commit();
        return Redirect::action('BookController@toMyView', array(
            'bid' => $bid
        ));
    }

    public function doDelete(){

    }

    public function changeReadState(){
        $input_vals = \Input::All();
        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '0';
        $status = isset($input_vals['status']) ? $input_vals['status'] : 0;
        $userId = isset($input_vals['uid']) ? $input_vals['uid'] : $this->getLoginUser();

        DB::beginTransaction();
        $now = date('Y-m-d H:i:s', time());

        $doneTime = $this->default_time;

        $bookDB = new Book();
        $book = $bookDB->getMyBookById($bid, $userId);

        if($status != $book->read_status){
            // 状态有变化
            if($status == $this->book_read_status_collect){
                $doneTime = $now;
            }

            // 完成时间，避免评论、评分等操作产生误差
            DB::table('user_book')->where('bid', $bid)->where('uid', $userId)
                ->update(array(
                    'read_status' => $status,
                    'update_time' => $now,
                    'done_time' => $doneTime
                ));
        }



        DB::commit();
        return json_encode(array(
            'code' => 0
        ));
    }

    public function updateBookComment(){
        $input_vals = \Input::All();
        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '0';
        $status = isset($input_vals['status']) ? $input_vals['status'] : -1;
        $source = isset($input_vals['source']) ? $input_vals['source'] : '0';
        $sid = isset($input_vals['sid']) ? $input_vals['sid'] : '0';
        $tid = isset($input_vals['tid']) ? $input_vals['tid'] : '0';
        $userId = $this->getLoginUser();

        DB::beginTransaction();
        $bookDB = new Book();

        DB::table('book')->where('bid', $bid)->update(array(
            'sid' => $sid,
            'tid' => $tid
        ));

        $now = date('Y-m-d H:i:s', time());
        if($status != -1){
            // 未传递 status的，可认为是仅仅修改
            $tags = isset($input_vals['tags']) ? $input_vals['tags'] : '';
            // 拆分数组 + 去重；tag_input按逗号处理数组
            $tagArray = explode(',',$tags);
            $tagArray = array_unique($tagArray);

            $doneTime = $this->default_time;;
            if($status == $this->book_read_status_collect){
                $doneTime = $now;
            }

            $book = $bookDB->getMyBookById($bid, $userId);

            if($status != $book->read_status){

                $doneTime = $this->default_time;;
                // 状态有变化
                if($status == $this->book_read_status_collect){
                    $doneTime = $now;
                }

                // 完成时间，避免评论、评分等操作产生误差
                DB::table('user_book')->where('bid', $bid)->where('uid', $userId)
                    ->update(array(
                        'read_status' => $status,
                        'tags' => join(" ", $tagArray),
                        'update_time' => $now,
                        'source' => $source,
                        'done_time' => $doneTime
                    ));
            } else{
                // 状态没有变化,不更新完成时间
                DB::table('user_book')->where('bid', $bid)->where('uid', $userId)
                    ->update(array(
                        'tags' => join(" ", $tagArray),
//                        'update_time' => $now,
                        'source' => $source
                    ));
            }



            $tagDB = new Tag();
            $tagDB->updateUserTags($userId, $bid, $tagArray);
        }

        // 判断user_comment是否存在？
        $commentDB = new Comment();
        $comment = $commentDB->getMyCommentByBook($bid, $userId);

        $title = isset($input_vals['title']) ? $input_vals['title'] : '';
        $content = isset($input_vals['content']) ? $input_vals['content'] : '';
        $star = isset($input_vals['star']) ? $input_vals['star'] : 0;

        if(empty($comment)){
            $cid = DB::table('user_comment')->insertGetId(array(
                'bid' => $bid,
                'uid' => $userId,
                'content' => $content,
                'title' => $title,
                'star' => $star,
                'create_time' => $now
            ));
        } else {
            DB::table('user_comment')->where('uid', $userId)->where('bid', $bid)->update(array(
                'title' => $title,
                'star' => $star,
                'content' => $content,
                'create_time' => $now
            ));
        }

        DB::commit();
        return json_encode(array(
            'code' => 0,
            'bid' => $bid
        ));
    }



    public function importDoubanBook(){
        $input_vals = \Input::All();
        $doubanId = isset($input_vals['douban_id']) ? $input_vals['douban_id'] : '0';
        $booklistId = isset($input_vals['booklist_id']) ? $input_vals['booklist_id'] : '0';

        $url = isset($input_vals['url']) ? $input_vals['url'] : '';
        $title = isset($input_vals['title']) ? $input_vals['title'] : '';
        $pic = isset($input_vals['pic']) ? $input_vals['pic'] : '';
        $author = isset($input_vals['author']) ? $input_vals['author'] : '';


        if($doubanId == 0){
            return json_encode(array(
                'code' => 1
            ));
        }

        $userId = $this->getLoginUser();

        $bookId = -1;
        $bookDB = new Book();
        $booklistDB = new Booklist();
        $book = $bookDB->getBookByDoubanId($doubanId);
        DB::beginTransaction();
        if(empty($book)){
            
            $bookId = $this->saveBookFromDouban($doubanId, $url, $title, $pic, $author);
            $this->addUserBook($bookId, $userId);

        } else {
            $bookId = $book->bid;
        }
        // 书单不为空，才导入
        if($bookId > 0 && $booklistId!=0){
            // $title = filterTitle($title);
            // 更新booklist
            if($booklistDB->isBookInBooklist($booklistId, $bookId) == false){
                $booklistDB->addBookInBooklist($booklistId, $bookId);
                $booklistDB->updateBooklistIntroByTitle($booklistId, $title);
            }
            
        }
        DB::commit();
        return json_encode(array(
            'code' => ($bookId > 0) ? 0 : 1,
            'bid' => $bookId
        ));
    }

    /**
     * 保存从豆瓣抓取的图书信息
     * @param $douban_id
     * @param $name
     * @return mixed
     */
    private function saveBookFromDouban($douban_id, $url, $title, $pic, $author){

        $bookId = DB::table('book')->insertGetId(array(
            'bname' => $title,
            'subtitle' => '',
            'author' => $author,
            'pic_url' => $pic,
            'publisher' => '',
            'dou_id' => $douban_id,
            'dou_rate' => 0,
            'share_url' => $url,
            'is_deleted' => 0,
            'fid' => $this->fid_book,
            'create_time' => date('Y-m-d H:i:s', time())
        ));

        return $bookId;
    }

    private function addUserBook($bookId, $userId){
        if($bookId > 0 && $userId > 0){
            $now = date('Y-m-d H:i:s', time());
            DB::table('user_book')->insertGetId(array(
                'bid' => $bookId,
                'uid' => $userId,
                'read_status' => $this->book_read_status_wish,
                'note_count' => 0,
                'update_time' => $now,
                'create_time' => $now
            ));
        }
    }

}