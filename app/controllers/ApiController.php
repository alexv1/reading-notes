<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/29
 * Time: 上午11:12
 */

class ApiController extends BaseController{

    public function getShelfIndex(){
        $input_vals = Input::All();
        $userId = isset($input_vals['uid']) ? $input_vals['uid'] : $this->getLoginUser();
        $bookDB = new Book();
        $doList = $bookDB->getMyBooksByReadStatus($userId, $this->book_read_status_do, 0, 9, $this->book_order_updatetime);
        $wishList = $bookDB->getMyBooksByReadStatus($userId, $this->book_read_status_wish, 0, 6, $this->book_order_updatetime);
        $collectList = $bookDB->getMyBooksByReadStatus($userId, $this->book_read_status_collect, 0, 6, $this->book_order_updatetime);

        return json_encode(array(
            'uid' => $userId,
            'do_list' => $doList,
            'wish_list' => $wishList,
            'collect_list' => $collectList,
        ));
    }

    public function getMyBookById($bid){
        $input_vals = Input::All();
        $userId = isset($input_vals['uid']) ? $input_vals['uid'] : $this->getLoginUser();
        $bookDB = new Book();
        $book = $bookDB->getMyBookById($bid, $userId);
        $booklist = $bookDB->getBooklistByBook($bid);

        $noteDB = new Note();
        $notes = $noteDB->getMyNotesByBook($bid, $userId, 1, 3);

        return json_encode(array(
            'book' => $book,
            'booklists' => $booklist,
            'notes' => $notes
        ));
    }

    public function getMyNotesByBook($bid){
        $input_vals = Input::All();
        $userId = isset($input_vals['uid']) ? $input_vals['uid'] : $this->getLoginUser();
        $page = isset($input_vals['p']) ? $input_vals['p'] : '1';
        $size = isset($input_vals['s']) ? $input_vals['s'] : '10';

        $noteDB = new Note();
        $notes = $noteDB->getMyNotesByBook($bid, $userId, $page, $size);
        return json_encode($notes);
    }

    public function getNoteById($nid){
        $noteDB = new Note();
        $note = $noteDB->getNoteById($nid);

        return json_encode($note);
    }

    public function getBooklistById($blid){
        $user_id = $this->getLoginUser();
        $booklistDB = new Booklist();
        $booklist = $booklistDB->getBooklistById($blid);
        $books = $booklistDB->getMyBooksByBooklist($blid, $user_id);
        return json_encode(array(
            'books' => $books,
            'booklist' => $booklist
        ));
    }

    public function doSearch(){
        $input_vals = \Input::All();
        $q = isset($input_vals['q']) ? $input_vals['q'] : '';
        $p = isset($input_vals['p']) ? $input_vals['p'] : 1;
        $type = isset($input_vals['type']) ? $input_vals['type'] : 0;
        // 用于书单 -> 检索 -> 导入流程
        $size = $this->page_size;


        if($type == 0) {
            $bookDB = new Book();
            $books = $bookDB->searchBooks($q, $p, $size);
            $bookCount = $bookDB->searchBooksCount($q);
            $floor = floor($bookCount/$size);
            $pageCount = ($bookCount%$size == 0) ? $floor : ($floor + 1);

            return json_encode(array(
                'code' => 0,
                'total' => $bookCount,
                'limit' => $size,
                'word' => $q,
                'page' => $p,
                'data' => $books
            ));
        } else if($type == 1){
            $booklistDB = new Booklist();
            $booklists = $booklistDB->searchBooklists($q, $p, $size);
            $count = $booklistDB->searchBooklistsCount($q);
            $floor = floor($count/$size);
            $pageCount = ($count%$size == 0) ? $floor : ($floor + 1);

            return json_encode(array(
                'code' => 0,
                'total' => $count,
                'limit' => $size,
                'word' => $q,
                'page' => $p,
                'data' => $booklists
            ));

        } else if($type == 2){
            require_once(__DIR__.'/../lib/HttpRequest.php');
            $params = array();
            $searchUrl = $this->douban_search_url.'?q='.urlencode($q).'&start=0&count='.$size;
            $doubanBooks = json_decode(HttpRequest::httpsGet($searchUrl,$params));

            $books = array();
            for($i=0; $i<count($doubanBooks->books); $i++){
                $douban = $doubanBooks->books[$i];
                $book = array();
                $book['dou_id'] = $douban->id;
                $book['bname'] = $douban->title;
                $book['dou_rate'] = $douban->rating->average;
                $book['pic_url'] = $douban->image;
                $book['subtitle'] = $douban->subtitle;
                if(count($douban->author)>0){
                    $book['author'] = $douban->author[0];
                } else {
                    $book['author'] = '';
                }
                $book['publisher'] = $douban->publisher;
                $books[$i] = $book;
            }
            return json_encode(array(
                'code' => 0,
                'total' => count($doubanBooks->books),
                'limit' => $size,
                'word' => $q,
                'page' => 1,
                'data' => $books
            ));
        } else {
            return json_encode(array(
                'code' => 1,
                'msg' => 'error type parameter#'.$type
            ));
        }

    }


    public function getBooklists(){
        $input_vals = Input::All();
        $page = isset($input_vals['p']) ? $input_vals['p'] : 1;
        $size = $this->page_size;

        $booklistDB = new Booklist();
        $booklists = $booklistDB->getBooklists($page, $size);
        $count = $booklistDB->getBooklistsCount();
        $floor = floor($count/$size);
        $pageCount = ($count%$size == 0) ? $floor : ($floor + 1);

        return json_encode(array(
            'code' => 0,
            'total' => $count,
            'limit' => $size,
            'page' => $page,
            'data' => $booklists
        ));
    }

    public function getNotes(){
        $input_vals = \Input::All();
        $page = isset($input_vals['p']) ? $input_vals['p'] : 1;
        $size = $this->page_size;

        $noteDB = new Note();
        $userId = $this->getLoginUser();
        $notes = $noteDB->getMyNotes($userId, $page, $size);
        $noteCount = $noteDB->getMyNoteCount($userId);

        return json_encode(array(
            'code' => 0,
            'total' => $noteCount,
            'limit' => $size,
            'page' => $page,
            'data' => $notes
        ));
    }

    public function getMyBooksByStatus(){
        $input_vals = Input::All();
        $status = isset($input_vals['status']) ? $input_vals['status'] : 0;
        $page = isset($input_vals['p']) ? $input_vals['p'] : 1;
        $size = $this->page_size;
        $userId = isset($input_vals['uid']) ? $input_vals['uid'] : $this->getLoginUser();

        $bookDB = new Book();
        $bookList = $bookDB->getMyBooksByReadStatus($userId, $status, $page, $size, $this->book_order_updatetime);
        $bookCount = $bookDB->getMyBookCountByStatus($userId, $status);

        $floor = floor($bookCount/$size);
        $pageCount = ($bookCount%$size == 0) ? $floor : ($floor + 1);

        return json_encode(array(
            'status' => $status,
            'total' => $bookCount,
            'limit' => $size,
            'page' => $page,
            'data' => $bookList
        ));
    }

    private $empty_data = array(
        'id' => 0,
        'username' => '',
        'nickname' => '',
        'avatar' => ''
    );

    public function doLogin(){
        $input_vals = Input::All();
        $user = new SysUser();
        $user_ret = $user->getUser($input_vals['user_name']);

        if(!empty($user_ret)){
            if(md5($input_vals['pw'].$user_ret->salt) == $user_ret->pw){
                $token=array();
                $token['user_name']=$input_vals['user_name'];
                if(empty($user_ret->real_name)){
                    $token['real_name']=$input_vals['user_name'];
                } else {
                    $token['real_name']=$user_ret->real_name;
                }
                $token['user_id']=$user_ret->user_id;
                $token['expiresAt']=date("Y-m-d H:i:s",time()+60000);
                $data = array(
                    'id' => $user_ret->user_id,
                    'username' => $user_ret->user_name,
                    'nickname' => $user_ret->real_name,
                    'avatar' => $user_ret->pic
                );
                $backend_token = base64_encode(json_encode($token));
                Log::info("doLogin#".$backend_token);
                Session::put('backend_token', $backend_token);

                return json_encode(array(
                    'code' => 0,
                    'msg' => '',
                    'data' => $data
                ));
            }else{
                return json_encode(array(
                    'code' => 1,
                    'msg' => '密码不正确!',
                    'data' => $this->empty_data
                ));
            }
        }
        else{
            return json_encode(array(
                'code' => 1,
                'msg' => '该用户不存在',
                'data' => $this->empty_data
            ));
        }
    }

    public function isLogin(){
        $backend_token = Session::get('backend_token');
        Log::info("isLogin#".$backend_token);
        if(empty($backend_token)){
            return json_encode(array(
                'code' => 1,
                'msg' => 'not login'
            ));
        } else {
            $obj = json_decode(base64_decode($backend_token));
            return json_encode(array(
                'code' => 0,
                'msg' => 'id#'.$obj->user_id.', name#'.$obj->user_name
            ));
        }
    }
}