<?php namespace Base;
/**
 * @name Type_Base
 * @desc Base类型
 * @author wenyue1
 */
class Type {

    /**
     * @name get
     * @desc 获取value对应的常量名称
     * @author wenyue1
     * @param int $value
     * @return string/boolean
     */
    public static function get($value) {
        //实例化反射对象
        $reflecation_class = new \ReflectionClass(get_called_class());
        
        //通过反射对象获取constant_list
        $constant_list = $reflecation_class->getConstants();
        
        //验证constant_list
        if (empty($constant_list)) {
            return false;
        }
        
        //交换键值
        $constant_list = array_flip($constant_list);
        
        //验证是否为数字, 显示转换为整型
        if (is_numeric($value)) {
            $value = intval($value);
        }
        
        //验证value是否存在, 返回结果
        if (isset($constant_list[$value])) {
            return $constant_list[$value];
        } else {
            return false;
        }
    }

    /**
     * @name getList
     * @desc 获取对应类的常量列表
     * @author wenyue1
     * @return array
     */
    public static function getList() {
        //实例化反射对象
        $reflecation_class = new \ReflectionClass(get_called_class());
        
        //通过反射对象获取constant_list
        $constant_list = $reflecation_class->getConstants();
        
        //验证constant_list, 返回结果
        if (!empty($constant_list)) {
            return $constant_list;
        } else {
            return array();
        }
    }

    /**
     * @name getDesc
     * @desc 获取value对应的描述
     * @author wenyue1
     * @param int $value
     * @return string/boolean
     */
    public static function getDesc($value) {
        //实例化反射对象
        $reflecation_class = new \ReflectionClass(get_called_class());
        //通过反射对象获取constant_list
        $constant_list = $reflecation_class->getConstants();

        //验证constant_list
        if (empty($constant_list)) {
            return false;
        }
        
        //交换键值
        $constant_list = array_flip($constant_list);

        //验证是否为数字, 显示转换为整型
        if (is_numeric($value)) {
            $value = intval($value);
        }
        
        //验证value是否存在, 返回结果
        if (isset($constant_list[$value])) {
            return ucwords(strtolower(str_replace('_', ' ', $constant_list[$value])));
        } else {
            return 'Error Message';
        }
    }

    /**
     * @name getString
     * @desc 获取value对应的字符串
     * @author wenyue1
     * @param int $value
     * @return string
     */
    public static function getString($value) {
        //实例化反射对象
        $reflecation_class = new \ReflectionClass(get_called_class());
        
        //通过反射对象获取constant_list
        $constant_list = $reflecation_class->getConstants();
        
        //验证constant_list
        if (empty($constant_list)) {
            return '';
        }
        
        //交换键值
        $constant_list = array_flip($constant_list);
        
        //验证是否为数字, 显示转换为整型
        if (is_numeric($value)) {
            $value = intval($value);
        }
        
        //通过反射对象获取property_list
        $property_list = $reflecation_class->getDefaultProperties();
        
        //验证property_list
        if (empty($property_list['_mapping'])) {
            return '';
        }
        
        //验证value和mapping对应的值是否存在, 返回结果
        if (isset($constant_list[$value]) && isset($property_list['_mapping'][$constant_list[$value]])) {
            return $property_list['_mapping'][$constant_list[$value]];
        } else {
            return '';
        }
    }
}
