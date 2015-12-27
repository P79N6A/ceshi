<?php namespace Models;

use Base\Model;
use Base\DB;

class Condition extends Model
{
    protected $connection = 'app';

    public static function getConditionMap($type_name = '')
    {
        $result = Condition::where('status', 1)->get()->toArray();
        //返回数据
        if (!empty($result)) {
            foreach ($result as $k => $v) {
                $ret[$v['type']][$v['id']] = $v;

            }
            if ($type_name == '') {
                return $ret;
            } else {
                return @$ret[$type_name];
            }
        } else {
            return FALSE;
        }
    }

    public static function getConditionTypeMapById()
    {

    }

    public static function getLocationChildren($location, $father = false)
    {
        if ($location && !is_array($location)) {
            $location = array($location);
        }

        $query = DB::table('conditions');
        if (count($location) >= 1) {
            foreach ($location as $id) {
                $query = $query->orWhere(DB::raw("substring(`id`, 1, 3)"), $id);
            }
        }
        $result = $query->where('status', 1)->where('type', 'location')->get();
        $children = [];
        if ($result) {
            foreach ($result as $v) {
                if (!$father && $v['id'] < 1000) {
                    continue;
                }
                $children[$v['id']] = $v['name'];
            }
        }
        return $children;
    }

}