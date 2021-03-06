<?php
/** 用户接口 */
interface IUser{

    // 新增用户
    public function add($data);

    // 读取用户数据
    public function get($id);

}

/** 用户类 */
class User implements IUser{ // class start

    /** 
      * 用户数据
      */
    protected $user = array();

    /**
     * 新增用户
     * @param  Array $data 用户数据
     * @return Int
     */
    public function add($data){
        $this->user[] = $data;
        $keys = array_keys($this->user);
        return end($keys);
    }

    /**
     * 读取用户数据
     * @param  Int    $id 用户id
     * @return Array
     */
    public function get($id){
        if(isset($this->user[$id])){
            return $this->user[$id];
        }else{
            return array();
        }
    }

} // class end

/** VIP用户类 */
class Vip extends User{ // class start

    /**
     * 读取vip用户数据
     * @param  Int    $id 用户id
     * @return Array
     */
    public function getvip($id){
        $data = $this->get($id);
        if($data){
            return $this->format($data);
        }
        return $data;
    }

    /**
     * 修饰数据
     * @param  Array $data 用户数据
     * @return Array
     */
    private function format($data){
        $data['is_vip'] = 1;
        return $data;
    }

} // class end
?>