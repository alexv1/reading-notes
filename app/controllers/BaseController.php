<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout(){
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    public $resourcePath = '../';

	protected $status = array("正常", "停用");

    // 列表页面排序
    protected $book_order_updatetime = 0;
    // 优先按分类
    protected $book_order_sid = 3;
    protected $book_order_name = 1;
    protected $book_order_donetime = 2;

    protected $book_read_status_wish = 0;
    protected $book_read_status_do = 1;
    protected $book_read_status_collect = 2;

    protected $book_read_status_options = array(
        array(0,'想读'),array(1,'在读'),array(2,'已读')
    );

    protected $default_time = '0001-01-01 00:00:00';

    // 阅读状态
    protected $book_read_status = array('想读', '在读', '已读');

    protected $rest_days_array = array('周日','周一','周二','周三','周四','周五','周六');


    protected $userbook_source_options = array(
        array(0,'纸书'),array(1,'京东阅读'),array(2,'微信读书'),array(3,'kindle'),array(4,'多看'),array(5,'电子书'),array(6,'掌阅'),array(7,'九丘'),array(8,'小木屋'),array(10,'其它')
    );

    protected $userbook_source_array = array('纸书','京东阅读','微信读书','kindle','多看','电子书','掌阅','九丘','小木屋','其它');

    protected $page_size = 10;

    protected $douban_book_url = "https://api.douban.com/v2/book/";
    protected $douban_search_url = "https://api.douban.com/v2/book/search";

    protected $fid_book = 1;

    /**
     * @param $file_name    文件名
     * @param $sheet_name    表格名
     * @param $rows    数据
     * @param $column_width    表格的列宽
     * @return mixed
     */
    protected function getExcelCreator($file_name, $sheet_name, $rows, $column_width){
        $excel = Excel::create($file_name, function($excel) use ($sheet_name, $rows, $column_width) {
            $excel->sheet($sheet_name, function($sheet) use ($rows, $column_width) {
                $sheet->fromArray($rows, null, 'A1', false, false);
                $sheet->setFontFamily('Calibri');
                $sheet->setFontSize(12);
                $sheet->setFontBold(false);
                $sheet->setWidth($column_width);
            });
        });
        return $excel;
    }

    protected function getLoginUser(){
        $backend_token = Session::get('backend_token');
        if(empty($backend_token)){
            return -1;
        }else{

            $obj = json_decode(base64_decode($backend_token));
            return $obj->user_id;
//            $user = new SysUser();
//            $user_ret =$user->getUser($obj->user_name);
//            if(empty($user_ret)){
//                return -1;
//            }else{
//                return $user_ret->user_id;
//            }
        }
    }

}
