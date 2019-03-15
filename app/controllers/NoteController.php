<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/7
 * Time: 下午5:00
 */

class NoteController extends BaseController{

    public function toMyListView(){
        $input_vals = \Input::All();
        $page = isset($input_vals['p']) ? $input_vals['p'] : 1;
        $size = $this->page_size;

        $noteDB = new Note();
        $userId = $this->getLoginUser();
        $notes = $noteDB->getMyNotes($userId, $page, $size);

        $noteCount = $noteDB->getMyNoteCount($userId);

        $floor = floor($noteCount/$size);
        $pageCount = ($noteCount%$size == 0) ? $floor : ($floor + 1);

        return View::make('note.my-list', array(
            'path' => $this->resourcePath,
            'notes' => $notes,
            'note_count' => $noteCount,
            'page' => $page,
            'page_count' => $pageCount,
            'page_size' => $size,
        ));
    }

    public function toAddView(){
        $input_vals = \Input::All();
        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '';
        $userId = $this->getLoginUser();

        $bookDB = new Book();
        $data = $bookDB->getBookById($bid);

        $noteDB = new Note();
        $draft = $noteDB->getNoteDraftByBook($userId, $bid);

        return View::make('note.add', array(
            'path' => $this->resourcePath,
            'data' => $data,
            'draft' => $draft
        ));
    }

    public function toView(){
        $input_vals = \Input::All();
        $nid = isset($input_vals['nid']) ? $input_vals['nid'] : '';
        $user_id = $this->getLoginUser();

        $noteDB = new Note();
        $note = $noteDB->getNoteById($nid);

        $bookDB = new Book();
        $book = $bookDB->getBookById($note->bid);

        return View::make('note.view', array(
            'path' => $this->resourcePath,
            'book' => $book,
            'note' => $note,
            'read_status' => $this->book_read_status
        ));
    }

    public function toMyView(){
        $input_vals = \Input::All();
        $nid = isset($input_vals['nid']) ? $input_vals['nid'] : '';
        $user_id = $this->getLoginUser();

        $noteDB = new Note();
        $note = $noteDB->getNoteById($nid);

        $bookDB = new Book();
        $book = $bookDB->getMyBookById($note->bid, $user_id);

        return View::make('note.my-view', array(
            'path' => $this->resourcePath,
            'book' => $book,
            'note' => $note,
            'read_status' => $this->book_read_status
        ));
    }

    public function doAdd(){
        $input_vals = \Input::All();
        $userId = $this->getLoginUser();

        // 草稿
        $did = isset($input_vals['did']) ? $input_vals['did'] : '0';

        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '0';
        $title = isset($input_vals['title']) ? $input_vals['title'] : '';
        $content = isset($input_vals['content']) ? $input_vals['content'] : '';
        $abstract = isset($input_vals['abstract']) ? $input_vals['abstract'] : '';
        $is_public = isset($input_vals['is_public']) ? $input_vals['is_public'] : '0';

        $fileUrl = isset($input_vals['file_url']) ? $input_vals['file_url'] : '';
        $fileType = isset($input_vals['file_type']) ? $input_vals['file_type'] : '0';
        $shareUrl = isset($input_vals['share_url']) ? $input_vals['share_url'] : '';

        $noteDB = new Note();
        $noteCount = $noteDB->getMyBookNoteCount($userId, $bid);

        DB::beginTransaction();
        $note_id = DB::table('user_note')->insertGetId(array(
            'bid' => $bid,
            'uid' => $userId,
            'title' => $title,
            'abstract' => $abstract,
            'content' => $content,
            'is_public' => $is_public,
            'file_url' => $fileUrl,
            'file_type' => $fileType,
            'share_url' => $shareUrl,
            'is_deleted' => 0,
            'create_time' => date('Y-m-d H:i:s', time())
        ));

        DB::table('user_book')->where('uid', $userId)->where('bid', $bid)->update(array(
            'note_count' => ($noteCount + 1)
        ));

        DB::table('user_note_draft')->where('did', $did)->update(array(
            'is_deleted' => 1
        ));

        DB::commit();
        return Redirect::action('NoteController@toMyView', array(
            'code' => 0,
            'nid' => $note_id
        ));

    }

    public function doAddNoteInJson(){
        $input_vals = \Input::All();
        $userId = $this->getLoginUser();

        // 草稿
        $did = isset($input_vals['did']) ? $input_vals['did'] : '0';

        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '0';
        $title = isset($input_vals['title']) ? $input_vals['title'] : '';
        $content = isset($input_vals['content']) ? $input_vals['content'] : '';
        $abstract = isset($input_vals['abstract']) ? $input_vals['abstract'] : '';
        $is_public = isset($input_vals['is_public']) ? $input_vals['is_public'] : '0';

        $fileUrl = isset($input_vals['file_url']) ? $input_vals['file_url'] : '';
        $fileType = isset($input_vals['file_type']) ? $input_vals['file_type'] : '0';
        $shareUrl = isset($input_vals['share_url']) ? $input_vals['share_url'] : '';

        $noteDB = new Note();
        $noteCount = $noteDB->getMyBookNoteCount($userId, $bid);

        DB::beginTransaction();
        $note_id = DB::table('user_note')->insertGetId(array(
            'bid' => $bid,
            'uid' => $userId,
            'title' => $title,
            'abstract' => $abstract,
            'content' => $content,
            'is_public' => $is_public,
            'file_url' => $fileUrl,
            'file_type' => $fileType,
            'share_url' => $shareUrl,
            'is_deleted' => 0,
            'create_time' => date('Y-m-d H:i:s', time())
        ));

        DB::table('user_book')->where('uid', $userId)->where('bid', $bid)->update(array(
            'note_count' => ($noteCount + 1)
        ));

        DB::table('user_note_draft')->where('did', $did)->update(array(
            'is_deleted' => 1
        ));

        DB::commit();

        return json_encode(array(
            'code' => 0,
            'nid' => $note_id
        ));
    }


    public function toEditView(){
        $input_vals = \Input::All();
        $nid = isset($input_vals['nid']) ? $input_vals['nid'] : '';
        $user_id = $this->getLoginUser();

        $noteDB = new Note();
        $note = $noteDB->getNoteById($nid);

        $bookDB = new Book();
        $book = $bookDB->getMyBookById($note->bid, $user_id);

        // 草稿
        $draft = $noteDB->getNoteDraftByNote($nid);

        return View::make('note.edit', array(
            'path' => $this->resourcePath,
            'book' => $book,
            'note' => $note,
            'draft' => $draft,
            'read_status' => $this->book_read_status
        ));
    }

    public function doEdit(){
        $input_vals = \Input::All();
        $did = isset($input_vals['did']) ? $input_vals['did'] : '0';
        $nid = isset($input_vals['nid']) ? $input_vals['nid'] : '0';
        $title = isset($input_vals['title']) ? $input_vals['title'] : '';
        $abstract = isset($input_vals['abstract']) ? $input_vals['abstract'] : '';
        $content = isset($input_vals['content']) ? $input_vals['content'] : '';
        $is_public = isset($input_vals['is_public']) ? $input_vals['is_public'] : '0';
        $fileUrl = isset($input_vals['file_url']) ? $input_vals['file_url'] : '';
        $fileType = isset($input_vals['file_type']) ? $input_vals['file_type'] : '0';
        $shareUrl = isset($input_vals['share_url']) ? $input_vals['share_url'] : '';

        DB::beginTransaction();
        DB::table('user_note')->where('nid', $nid)->update(array(
            'title' => $title,
            'abstract' => $abstract,
            'content' => $content,
            'is_public' => $is_public,
            'file_url' => $fileUrl,
            'file_type' => $fileType,
            'share_url' => $shareUrl,
            'create_time' => date('Y-m-d H:i:s', time())
        ));

        DB::table('user_note_draft')->where('did', $did)->update(array(
            'is_deleted' => 1
        ));

        DB::commit();
        return Redirect::action('NoteController@toMyView', array(
            'nid' => $nid
        ));

    }

    public function doDelete(){
        $input_vals = \Input::All();
        $nid = isset($input_vals['nid']) ? $input_vals['nid'] : '0';

        DB::beginTransaction();
        DB::table('user_note')->where('nid', $nid)->update(array(
            'is_deleted' => 1
        ));

        DB::commit();
        return json_encode(array(
            'code' => 0
        ));
    }

    public function saveNoteDraft(){
        $input_vals = \Input::All();
        $did = isset($input_vals['did']) ? $input_vals['did'] : '0';
        $nid = isset($input_vals['nid']) ? $input_vals['nid'] : '0';
        $bid = isset($input_vals['bid']) ? $input_vals['bid'] : '0';
        $userId = $this->getLoginUser();
        $content = isset($input_vals['content']) ? $input_vals['content'] : '';

        if($content == ''){
            return json_encode(array(
                '1' => 0,
                'msg' => 'null content'
            ));
        }

        $draft = DB::table('user_note_draft')
            ->where('did', $did)
            ->first();
        DB::beginTransaction();
        if(empty($draft)){
            DB::table('user_note_draft')->insert(array(
                'did' => $did,
                'uid' => $userId,
                'nid' => $nid,
                'bid' => $bid,
                'content' => $content,
                'create_time' => date('Y-m-d H:i:s', time())
            ));
        } else {
            DB::table('user_note_draft')->where('did', $did)->update(array(
                'content' => $content,
                'create_time' => date('Y-m-d H:i:s', time())
            ));
        }

        DB::commit();
        return json_encode(array(
            'code' => 0
        ));
    }
}