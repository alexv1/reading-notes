<?php
/**
 * Created by PhpStorm.
 * User: ali
 * Date: 15/4/21
 * Time: 下午4:44
 */

class System extends Eloquent{

    public function getCampusOptions(){
        return DB::table('sys_campus')
            ->where('disabled', 0)
            ->orderBy('campus_name', 'asc')
            ->get();
    }

    public function getAllCampus(){
        return DB::table('sys_campus')
            ->orderBy('campus_name', 'asc')
            ->get();
    }

    public function getCampusById($id){
        return DB::table('sys_campus')
            ->where('campus_id', $id)
            ->first();
    }
    /**
     *
     */
    public function getUserOptions(){
        return DB::table('sys_user')
            ->where('disabled', 0)
            ->orderBy('real_name', 'asc')
            ->get();
    }

    public function getRoomsOfCampus($campus_id){
        return $this->getRoomQuery()
            ->where('sys_campus_room.campus_id', $campus_id)
//            ->where('sys_campus_room.disabled', 0)
            ->orderBy('sys_campus_room.room_order', 'asc')
            ->get();
    }

    public function getAllRooms(){
        return $this->getRoomQuery()
            ->orderBy('sys_campus.campus_name', 'asc')
            ->orderBy('sys_campus_room.room_order', 'desc')
            ->orderBy('sys_campus_room.room_name', 'asc')
            ->get();
    }

    public function getAvailableRooms(){
        return $this->getRoomQuery()
            ->where('sys_campus_room.disabled', 0)
            ->orderBy('sys_campus.campus_name', 'asc')
            ->orderBy('sys_campus_room.room_name', 'asc')
            ->get();
    }

    private function getRoomQuery(){
        return DB::table('sys_campus_room')
            ->join('sys_campus','sys_campus.campus_id','=','sys_campus_room.campus_id')
            ->select(DB::raw('sys_campus_room.room_id,concat(sys_campus.campus_name,"-",sys_campus_room.room_name) as full_name,
                sys_campus.campus_id,sys_campus.campus_name,sys_campus_room.room_name,sys_campus_room.seat_num,
                sys_campus_room.disabled,sys_campus_room.room_order,sys_campus_room.intro'));
    }

    public function getRoomById($id){
        return $this->getRoomQuery()
            ->where('sys_campus_room.room_id', $id)
            ->first();
    }

    public function increaseRoomNum($campus_id){
        DB::table('sys_campus')->where('campus_id', $campus_id)
            ->update(array(
                'room_num' => DB::raw('room_num+1')
            ));
    }

    public function decreaseRoomNum($campus_id){
        DB::table('sys_campus')->where('campus_id', $campus_id)
            ->update(array(
                'room_num' => DB::raw('room_num-1')
            ));
    }

    public function getRoomNumOfCampus(){
        return DB::table('sys_campus_room')
            ->select(DB::raw('campus_id,count(room_id) as room_num'))
            ->where('disabled','=', 0)
            ->groupBy('campus_id')
            ->get();
    }

    public function syncRoomNum(){
        $campus_stats = $this->getRoomNumOfCampus();
        foreach($campus_stats as $campus){
            DB::table('sys_campus')->where('campus_id', $campus->campus_id)
                ->update(array(
                    'room_num' => $campus->room_num
                ));
        }
    }
}