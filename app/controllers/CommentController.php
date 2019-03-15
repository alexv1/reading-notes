<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/7
 * Time: 下午5:00
 */

class CommentController extends BaseController{

    public function toMyListView(){
        $input_vals = \Input::All();
        $page = isset($input_vals['p']) ? $input_vals['p'] : 1;
        $size = $this->page_size;

        $commentDB = new Comment();
        $userId = $this->getLoginUser();
        $comments = $commentDB->getMyComments($userId, $page, $size);

        $commentCount = $commentDB->getMyCommentCount($userId);
        $floor = floor($commentCount/$size);
        $pageCount = ($commentCount%$size == 0) ? $floor : ($floor + 1);

        return View::make('comment.my-list', array(
            'path' => $this->resourcePath,
            'comments' => $comments,
            'comment_count' => $commentCount,
            'page' => $page,
            'page_count' => $pageCount,
            'page_size' => $size,
            'read_status' => $this->book_read_status
        ));
    }

    public function toMyView(){
        $input_vals = \Input::All();
        $cid = isset($input_vals['cid']) ? $input_vals['cid'] : '';
        $user_id = $this->getLoginUser();

        $commentDB = new Comment();
        $comment = $commentDB->getCommentById($cid);

        $bookDB = new Book();
        $book = $bookDB->getMyBookById($comment->bid, $user_id);

        return View::make('comment.my-view', array(
            'path' => $this->resourcePath,
            'book' => $book,
            'comment' => $comment,
            'read_status' => $this->book_read_status
        ));
    }

    public function toAddView(){
        $input_vals = \Input::All();
        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '';

        $bookDB = new Book();
        $data = $bookDB->getBookById($bid);
//        Log::info(json_encode($data));
        return View::make('comment.add', array(
            'path' => $this->resourcePath,
            'book' => $book
        ));
    }

    public function toView(){
        $input_vals = \Input::All();
        $cid = isset($input_vals['cid']) ? $input_vals['cid'] : '';
        $user_id = $this->getLoginUser();

        $commentDB = new Comment();
        $comment = $commentDB->getCommentById($cid);

        $bookDB = new Book();
        $book = $bookDB->getMyBookById($comment->bid, $user_id);

        return View::make('comment.view', array(
            'path' => $this->resourcePath,
            'book' => $book,
            'comment' => $comment,
            'read_status' => $this->book_read_status
        ));
    }

    public function doAdd(){

        $input_vals = \Input::All();
        $user_id = $this->getLoginUser();
        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '0';
        $content = isset($input_vals['content']) ? $input_vals['content'] : '';
        $title = isset($input_vals['title']) ? $input_vals['title'] : '';
        $star = isset($input_vals['star']) ? $input_vals['star'] : '';

        DB::beginTransaction();
        $cid = DB::table('user_comment')->insertGetId(array(
            'bid' => $bid,
            'uid' => $user_id,
            'content' => $content,
            'title' => $title,
            'star' => $star,
            'is_deleted' => 0,
            'create_time' => date('Y-m-d H:i:s', time())
        ));


        DB::commit();
        return Redirect::action('CommentController@toView', array(
            'cid' => $cid
        ));

    }

    public function toEditView(){
        return View::make('comment.edit', array(
            'path' => $this->resourcePath
        ));
    }

    public function doEdit(){

    }

    public function doDelete(){

    }
}