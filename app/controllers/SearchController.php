<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/6/11
 * Time: 上午10:51
 */

class SearchController extends BaseController{

    public function toSearchNameView(){
        $input_vals = \Input::All();
        $q = isset($input_vals['q']) ? $input_vals['q'] : '';
        $p = isset($input_vals['p']) ? $input_vals['p'] : 1;
        // 用于书单 -> 检索 -> 导入流程
        $booklistId = isset($input_vals['blid']) ? $input_vals['blid'] : 0;
        $size = $this->page_size;



        $bookDB = new Book();
        $books = $bookDB->searchBooks($q, $p, $size);
        $bookCount = $bookDB->searchBooksCount($q);
        $floor = floor($bookCount/$size);
        $pageCount = ($bookCount%$size == 0) ? $floor : ($floor + 1);

        $booklistDB = new Booklist();
        $booklists = $booklistDB->searchBooklists($q, $p, $size);

        $importBooklistName = '';
        if($booklistId != 0){
            $importBooklist = $booklistDB->getBooklistById($booklistId);
            $importBooklistName = $importBooklist->name;
        }

        require_once(__DIR__.'/../lib/HttpRequest.php');
        $params = array();
        $searchUrl = $this->douban_search_url.urlencode($q);
        $str = HttpRequest::httpsGet($searchUrl,$params);
        // 产生问题的原因：rate_limit
        Log::info('douban#'.$str);
        $doubanBooks = json_decode($str);
        return View::make('search.book-search', array(
            'path' => $this->resourcePath,
            'q' => $q,
            'booklist_id' => $booklistId,
            'import_booklist_name' => $importBooklistName,
            'books' => $books,
            'booklists' => $booklists,
            'list_count' => $bookCount,
            'page' => $p,
            'page_count' => $pageCount,
            'page_size' => $size,
            'douban_books' => $doubanBooks,
            'read_status' => $this->book_read_status
        ));
    }


    public function toSearchTagView(){
        $input_vals = \Input::All();
        $q = isset($input_vals['q']) ? $input_vals['q'] : '';
        $p = isset($input_vals['p']) ? $input_vals['p'] : 1;

        $size = $this->page_size;

        $bookDB = new Book();
        $books = $bookDB->searchBooksByTag($q, $p, $size);
        $bookCount = $bookDB->searchBooksCountByTag($q);
        $floor = floor($bookCount/$size);
        $pageCount = ($bookCount%$size == 0) ? $floor : ($floor + 1);


        return View::make('search.tag-search', array(
            'path' => $this->resourcePath,
            'q' => $q,
            'books' => $books,
            'list_count' => $bookCount,
            'page' => $p,
            'page_count' => $pageCount,
            'page_size' => $size
        ));
    }

    public function toSearchSecondCategoryView(){
        $input_vals = \Input::All();
        $sid = isset($input_vals['sid']) ? $input_vals['sid'] : '0';
        $p = isset($input_vals['p']) ? $input_vals['p'] : 1;

        $size = $this->page_size;
        $userId = $this->getLoginUser();

        $bookDB = new Book();
        $books = $bookDB->searchBooksBySecondCategory($sid, $p, $size);
        $bookCount = $bookDB->searchBooksCountBySecondCategory($sid);
        $floor = floor($bookCount/$size);
        $pageCount = ($bookCount%$size == 0) ? $floor : ($floor + 1);

        $categoryDB = new Category();
        $secondCategory = $categoryDB->get2ndCategoryByFid($this->fid_book);
        $treeData = $this->getRootTreeData($categoryDB, $secondCategory);

        $category = $categoryDB->get2ndCategory($sid);

        $thirdCategoryList = $this->getBook3rdCategoryStats($userId, $sid);
        $thirdCategoryArray = array();
        foreach($thirdCategoryList as $obj){
            array_push($thirdCategoryArray, $obj->name);
        }


        return View::make('search.second-category-search', array(
            'path' => $this->resourcePath,
            'sid' => $sid,
            'sname' => $category->sname,
            'books' => $books,
            'list_count' => $bookCount,
            'page' => $p,
            'page_count' => $pageCount,
            'page_size' => $size,
            'source_options' => $this->userbook_source_options,
            'source_array' => $this->userbook_source_array,
            'second_category' => $secondCategory,
            'tree_data' => json_encode($treeData),
            'read_status_options' => $this->book_read_status_options,
            'read_status_array' => $this->book_read_status,
            'legend_data_third_category' => json_encode($thirdCategoryArray),
            'series_data_third_category' => json_encode($thirdCategoryList)
        ));
    }

    public function toSearchThirdCategoryView(){
        $input_vals = \Input::All();
        $tid = isset($input_vals['tid']) ? $input_vals['tid'] : '0';
        $p = isset($input_vals['p']) ? $input_vals['p'] : 1;

        $size = $this->page_size;

        $bookDB = new Book();
        $books = $bookDB->searchBooksByThirdCategory($tid, $p, $size);
        $bookCount = $bookDB->searchBooksCountByThirdCategory($tid);
        $floor = floor($bookCount/$size);
        $pageCount = ($bookCount%$size == 0) ? $floor : ($floor + 1);

        $categoryDB = new Category();
        $secondCategory = $categoryDB->get2ndCategoryByFid($this->fid_book);
        $categorySelf = $categoryDB->get3rdCategory($tid);

        // 兄弟节点
        $thirdCategory = $categoryDB->getChildrenCategoryBySid($categorySelf->sid);
        $children = $this->getChildrenOfFolder($thirdCategory);

        $item = array(
            'name' => $categorySelf->sname,
            'type' => 'folder',
            'additionalParameters' => array(
                'id' => $categorySelf->sid,
                'children' => $children
            )
        );

        $treeData = array(
            $categorySelf->sid => $item
        );

        return View::make('search.third-category-search', array(
            'path' => $this->resourcePath,
            'tid' => $tid,
            'category_self' => $categorySelf,
            'books' => $books,
            'list_count' => $bookCount,
            'page' => $p,
            'page_count' => $pageCount,
            'page_size' => $size,
            'source_options' => $this->userbook_source_options,
            'source_array' => $this->userbook_source_array,
            'second_category' => $secondCategory,
            'tree_data' => json_encode($treeData),
            'read_status_options' => $this->book_read_status_options,
            'read_status_array' => $this->book_read_status
        ));
    }

    private function getRootTreeData($categoryDB, $secondCategory){

        // 生成trre
        $treeFolder = array();
        foreach($secondCategory as $second){
            $sid = $second->id;
            $name = $second->name;
            $thirdCategory = $categoryDB->getChildrenCategoryBySid($sid);

            $children = $this->getChildrenOfFolder($thirdCategory);

            $item = array(
                'name' => $name,
                'type' => 'folder',
                'additionalParameters' => array(
                    'id' => $sid,
                    'children' => $children
                )
            );
//            array_push($treeFolder, $item);
            $treeFolder[$name] = $item;

        }

        return $treeFolder;
    }

    private function getChildrenOfFolder($thirdCategory){
        // 生成trre
        $folder = array();
        foreach($thirdCategory as $third){
            $name = $third->name;
            $item = array(
                'name' => $name,
                'type' => 'item',
                'additionalParameters' => array(
                    'id' => $third->id
                )
            );
//            array_push($folder, $item);
            $folder[$name] = $item;
        }
        return $folder;
    }

    private function getBook3rdCategoryStats($uid, $sid){
        $query = DB::table('user_book')
            ->join('book', 'user_book.bid', '=', 'book.bid')
            ->leftJoin('sys_category_third', 'book.tid', '=', 'sys_category_third.tid')
            ->select(DB::raw('ifnull(count(user_book.bid),0) as value, sys_category_third.name'))
            ->where('user_book.uid', $uid)
            ->where('book.sid', $sid);
        return $query
            ->groupBy('book.tid')
            ->orderBy('book.tid', 'asc')
            ->get();
    }

}