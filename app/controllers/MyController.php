<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/7
 * Time: 上午10:54
 */

class MyController extends BaseController{

    public function toDashboardView(){
        // 时间：本月，本年
        $month = getMonth();
        $year = getYear();
        $user_id = $this->getLoginUser();

        $bookDB = new Book();
        $noteDB = new Note();
        $commentDB = new Comment();

        $countArray = array();
        // 1.数字：今年已读，累计已读
        $collectCountYear = $bookDB->getMyCollectBookCountByMonth($user_id, $month);
        $collectCountAll = $bookDB->getMyCollectBookCount($user_id);
        array_push($countArray,$collectCountYear);
        array_push($countArray,$collectCountAll);

        // 2.数字，所有在读书目，本月已读
        $doCount = $bookDB->getMyDoBookCount($user_id);
        $collectCountMonth = $bookDB->getMyCollectBookCountByMonth($user_id, $month);
        array_push($countArray, $doCount);
        array_push($countArray, $collectCountMonth);


        // 3.数字，本月笔记，本月评论
        $noteCountMonth = $noteDB->getMyNoteCountByMonth($user_id, $month);
        $commentCountMonth = $commentDB->getMyCommentCountByMonth($user_id, $month);
        array_push($countArray, $noteCountMonth);
        array_push($countArray, $commentCountMonth);

        // 4.在读书目清单，想读书目清单
        $doList = $bookDB->getMyBooksByReadStatus($user_id, $this->book_read_status_do, 0, 5, $this->book_order_updatetime);
        $wishList = $bookDB->getMyBooksByReadStatus($user_id, $this->book_read_status_wish, 0, 5, $this->book_order_updatetime);

        // 5.读书统计，每月曲线；类型统计

        // 6.最新书目，最新笔记，最新书评
        $latestBooks = $bookDB->getLatestBooks();
        $latestNotes = $noteDB->getLatestNotes($user_id);
        $latestComments = $commentDB->getLatestComments($user_id);

        return View::make('dashboard.index', array(
            'path' => $this->resourcePath,
            'count_array' => $countArray,
            'do_list' => $doList,
            'wish_list' => $wishList,
            'latest_books' => $latestBooks,
            'latest_notes' => $latestNotes,
            'latest_comments' => $latestComments,
            'rest_days' => $this->rest_days_array
        ));
    }

}