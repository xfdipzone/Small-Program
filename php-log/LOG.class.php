<?php
/**
 * php日志类
 * Date:    2017-08-27
 * Author:  fdipzone
 * Version: 1.0
 *
 * Description: 
 * 1.自定义日志根目录及日志文件名称。
 * 2.使用日期时间格式自定义日志目录。
 * 3.自动创建不存在的日志目录。
 * 4.记录不同分类的日志，例如信息日志，警告日志，错误日志。
 * 5.可自定义日志配置，日志根据标签调用不同的日志配置。
 *
 * Func
 * public  static set_config 设置配置
 * public  static get_logger 获取日志类对象
 * public  info              写入信息日志
 * public  warn              写入警告日志
 * public  error             写入错误日志
 * private add               写入日志
 * private create_log_path   创建日志目录
 * private get_log_file      获取日志文件名称
 */
class LOG { // class start

    // 日志根目录
    private $_log_path = '.';

    // 日志文件
    private $_log_file = 'default.log';

    // 日志自定义目录
    private $_format = 'Y/m/d';

    // 日志标签
    private $_tag = 'default';

    // 总配置设定
    private static $_CONFIG;

    /**
     * 设置配置
     * @param  Array $config 总配置设定
     */
    public static function set_config($config=array()){
        self::$_CONFIG = $config; 
    }

    /**
     * 获取日志类对象
     * @param  Array $config 总配置设定
     * @return Obj
     */
    public static function get_logger($tag='default'){

        // 根据tag从总配置中获取对应设定，如不存在使用default设定
        $config = isset(self::$_CONFIG[$tag])? self::$_CONFIG[$tag] : (isset(self::$_CONFIG['default'])? self::$_CONFIG['default'] : array());

        // 设置标签
        $config['tag'] = $tag!='' && $tag!='default'? $tag : '-';

        // 返回日志类对象
        return new LOG($config);

    }

    /**
     * 初始化
     * @param Array $config 配置设定
     */
    public function __construct($config=array()){

        // 日志根目录
        if(isset($config['log_path'])){
            $this->_log_path = $config['log_path'];
        }

        // 日志文件
        if(isset($config['log_file'])){
            $this->_log_file = $config['log_file'];
        }

        // 日志自定义目录
        if(isset($config['format'])){
            $this->_format = $config['format'];
        }

        // 日志标签
        if(isset($config['tag'])){
            $this->_tag = $config['tag'];
        }

    }

    /**
     * 写入信息日志
     * @param  String $data 信息数据
     * @return Boolean
     */
    public function info($data){
        return $this->add('INFO', $data);
    }

    /**
     * 写入警告日志
     * @param  String  $data 警告数据
     * @return Boolean
     */
    public function warn($data){
        return $this->add('WARN', $data);
    }

    /**
     * 写入错误日志
     * @param  String  $data 错误数据
     * @return Boolean
     */
    public function error($data){
        return $this->add('ERROR', $data);
    }

    /**
     * 写入日志
     * @param  String  $type 日志类型
     * @param  String  $data 日志数据
     * @return Boolean
     */
    private function add($type, $data){

        // 获取日志文件
        $log_file = $this->get_log_file();

        // 创建日志目录
        $is_create = $this->create_log_path(dirname($log_file));

        // 创建日期时间对象
        $dt = new DateTime;

        // 日志内容
        $log_data = sprintf('[%s] %-5s %s %s'.PHP_EOL, $dt->format('Y-m-d H:i:s'), $type, $this->_tag, $data);

        // 写入日志文件
        if($is_create){
            return file_put_contents($log_file, $log_data, FILE_APPEND);
        }

        return false;

    }

    /**
     * 创建日志目录
     * @param  String  $log_path 日志目录
     * @return Boolean
     */
    private function create_log_path($log_path){
        if(!is_dir($log_path)){
            return mkdir($log_path, 0777, true);
        }
        return true;
    }

    /**
     * 获取日志文件名称
     * @return String
     */
    private function get_log_file(){

        // 创建日期时间对象
        $dt = new DateTime;

        // 计算日志目录格式
        return sprintf("%s/%s/%s", $this->_log_path, $dt->format($this->_format), $this->_log_file);
    
    }

} // class end
?>