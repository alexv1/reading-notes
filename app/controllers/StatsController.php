<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 17/2/6
 * Time: 下午3:14
 */

class StatsController extends BaseController{

    public function toReadListView(){
        $user_id = $this->getLoginUser();
        $bookDB = new Book();

        $filter = \Input::All();
        $sid = isset($filter['sid']) ? $filter['sid'] : '-1';
        $tid = isset($filter['tid']) ? $filter['tid'] : '-1';
        $source = isset($filter['source']) ? $filter['source'] : '-1';
        $status = isset($filter['status']) ? $filter['status'] : '-1';
        $start = isset($filter['start']) ? $filter['start'] : null;
        $end = isset($filter['end']) ? $filter['end'] : null;

        $page = isset($filter['p']) ? $filter['p'] : 1;
        $size = 10;
        $bookList = $bookDB->advancedSearchBooks($user_id, $filter, $page, $size);
        $bookCount = $bookDB->advancedSearchBookCount($user_id, $filter);

        $floor = floor($bookCount/$size);
        $pageCount = ($bookCount%$size == 0) ? $floor : ($floor + 1);

        $categoryDB = new Category();
        $secondCategory = $categoryDB->get2ndCategoryByFid($this->fid_book);
        $thirdCategory = array();
        if($sid != '-1'){
            $thirdCategory = $categoryDB->getChildrenCategoryBySid($sid);
        }

        $secondCategoryList = $bookDB->getBook2ndCategoryStats($user_id, $status, $start, $end);
        $secondCategoryArray = array();
        foreach($secondCategoryList as $obj){
            array_push($secondCategoryArray, $obj->name);
        }

        $sourceList = $bookDB->getBookSourceStats($user_id, $status, $start, $end);
        $sourceArray = array();
        foreach($sourceList as $obj){
            array_push($sourceArray, $obj->name);
        }

        return View::make('stats.book-list', array(
            'path' => $this->resourcePath,
            'books' => $bookList,
            'book_count' => $bookCount,
            'page' => $page,
            'page_count' => $pageCount,
            'page_size' => $size,
            'filter' => $filter,
            'sid' => $sid,
            'tid' => $tid,
            'source' => $source,
            'status' => $status,
            'second_category' => $secondCategory,
            'third_category' => $thirdCategory,
            'read_status_options' => $this->book_read_status_options,
            'read_status_array' => $this->book_read_status,
            'source_options' => $this->userbook_source_options,
            'source_array' => $this->userbook_source_array,
            'legend_data_second_category' => json_encode($secondCategoryArray),
            'series_data_second_category' => json_encode($secondCategoryList),
            'legend_data_source' => json_encode($sourceArray),
            'series_data_source' => json_encode($sourceList)
        ));
    }
}