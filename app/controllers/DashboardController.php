<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 15/6/19
 * Time: 下午3:01
 */

class DashboardController extends BaseController {

    public function toDashboardView(){


        return View::make('dashboard.index', array(
            'path' => $this->resourcePath,
        ));
    }

    public function root(){
        return Redirect::to('/dashboard/index');
    }

    private function getOrderStats(){
        return DB::table('student_order')
            ->select(DB::raw('MONTH(student_order.create_time) as months, count(student_order.order_id) as order_sum'))
            ->where('disabled',0)
            ->where('pay_status',1)
            ->groupBy('months')
            ->get();
    }

    private function getPayStats(){
        return DB::table('finance_pay')
            ->select(DB::raw('MONTH(pay_day) as months, sum(amount) as pay_sum'))
            ->where('disabled',0)
            ->where('is_confirm',1)
            ->groupBy('months')
            ->get();
    }

    private function getMonthStats($month){
        $month_first = $month.'-01';
        $month_last = $month.'-31';

        $stats = array();
        // 本月确认订单，新学生，交费，课时，学生生日，教师生日
        $order = DB::table('student_order')
            ->select(DB::raw('count(student_order.order_id) as stats_sum'))
            ->where('disabled',0)
            ->where('pay_status',1)
            ->where('create_time','>=', $month_first)
            ->where('create_time','<=', $month_last.' 23:59:59')
            ->first();

        $stats['order'] = $this->getValue($order);


        $student = DB::table('student')
            ->select(DB::raw('count(student_id) as stats_sum'))
            ->where('disabled',0)
            ->where('join_day','>=', $month_first)
            ->where('join_day','<=', $month_last)
            ->first();
        $stats['student'] = $this->getValue($student);


        $stats['pay'] = $this->getIncome($month_first, $month_last);

        $lesson = DB::table('course_lesson')
            ->select(DB::raw('sum(class_hour) as stats_sum'))
            ->where('disabled',0)
            ->where('lesson_day','>=', $month_first)
            ->where('lesson_day','<=', $month_last)
            ->first();
        $stats['lesson'] = $this->getValue($lesson);

        return $stats;
    }

    private function getValue($obj){
        $t = 0;
        if(!empty($obj)){
            $t = $obj->stats_sum;
        }
        return $t;
    }

    private function formatTeacherBirthday($list){
        $size = 5;
        if(count($list) < $size){
            $size = count($list);
        }
        $ret = array();
        for($i=0; $i<$size; $i++){
            $obj = $list[$i];
            if(empty($obj)){
                continue;
            }
            Log::info(json_encode($obj));
            $alert = array();
            $alert['id'] = $obj->teacher_id;
            $alert['name'] = $obj->real_name;
            $alert['info'] = $obj->birthday_search;
            array_push($ret, $alert);
        }
        return $ret;
    }

    private function formatStudentBirthday($list){
        $size = 5;
        if(count($list) < $size){
            $size = count($list);
        }
        $ret = array();
        for($i=0; $i<$size; $i++){
            $obj = $list[$i];
            if(empty($obj)){
                continue;
            }
            $alert = array();
            $alert['id'] = $obj->student_id;
            $alert['name'] = $obj->real_name;
            $alert['info'] = $obj->birthday_search;
            array_push($ret, $alert);
        }
        return $ret;
    }

    private function formatStudentLeave($list){
        $size = 5;
        if(count($list) < $size){
            $size = count($list);
        }
        $ret = array();
        for($i=0; $i<$size; $i++){
            $obj = $list[$i];
            if(empty($obj)){
                continue;
            }
            $alert = array();
            $alert['id'] = $obj->leave_id;
            $alert['name'] = $obj->real_name;
            $alert['info'] = formatDayFromStr($list[$i]->date_from).' ~ '.formatDayFromStr($list[$i]->date_to);
            array_push($ret, $alert);
        }
        return $ret;
    }

    private function formatTeacherLeave($list){
        $size = 5;
        if(count($list) < $size){
            $size = count($list);
        }
        $ret = array();
        for($i=0; $i<$size; $i++){
            $obj = $list[$i];
            if(empty($obj)){
                continue;
            }
            $alert = array();
            $alert['id'] = $obj->leave_id;
            $alert['name'] = $obj->real_name;
            $alert['info'] = formatDayFromStr($list[$i]->date_from).' ~ '.formatDayFromStr($list[$i]->date_to);
            array_push($ret, $alert);
        }
        return $ret;
    }

    private function formatUnpay($list){
        $size = 5;
        if(count($list) < $size){
            $size = count($list);
        }
        $ret = array();
        for($i=0; $i<$size; $i++){
            $obj = $list[$i];
            if(empty($obj)){
                continue;
            }
            $alert = array();
            $alert['id'] = $obj->order_id;
            $alert['name'] = $obj->real_name;
            $alert['info'] = number_format(intval($obj->real_sum) - intval($obj->pay_sum));
            array_push($ret, $alert);
        }
        return $ret;
    }

    private function getIncome($day1, $day2){
        $pay = DB::table('finance_pay')
            ->select(DB::raw('sum(amount) as stats_sum'))
            ->where('disabled',0)
            ->where('is_confirm',1)
            ->where('pay_day','>=', $day1)
            ->where('pay_day','<=', $day2)
            ->first();
        return $this->getValue($pay);
    }

}