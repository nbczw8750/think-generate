<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: CC慕斯 <nbczw8750@qq.com>
// +----------------------------------------------------------------------

namespace nbczw8750\generate;

use think\App;
use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\console\input\Option;

abstract class Generate extends Command
{

    protected $type;
    protected $msg  = "";

    abstract protected function getStub();

    protected function configure()
    {
        $this->addArgument('name', Argument::REQUIRED, "The name of the class");
        $this->addOption("stub",null,Option::VALUE_OPTIONAL, "The stub is template file path");
    }

    protected function execute(Input $input, Output $output)
    {
        $this->make();
        $this->output($this->msg);

    }
    protected function output($msg){
        $this->output->writeln($msg);
    }

    /**
     * 开始生成
     * @return bool
     */
    public function make(){
        $name = trim($this->input->getArgument('name'));

        $className = $this->getClassName($name);
        $pathname = $this->getPathName($className);

        if (is_file($pathname)) {
            $this->msg = '<error>' . $this->type . ' already exists!</error>';
            return false;
        }
        if (!is_dir(dirname($pathname))) {
            mkdir(strtolower(dirname($pathname)), 0755, true);
        }

        $content = $this->buildClass($className);
        if (false === $content){
            return false;
        }
        file_put_contents($pathname,$content);

        $this->msg = '<info>' . $this->type . ' created successfully.</info>';
        return true;

    }

    /**
     * 设置input
     * @param $input
     */
    public function setInput($input){
        $this->input = $input;
    }

    /**
     * 获取input
     * @return Input
     */
    public function getInput(){
        return $this->input;
    }

    //获取输出内容
    public function getMsg(){
        return $this->msg;
    }


    /**
     * 创建内容
     * @param $name
     * @return mixed
     */
    protected function buildClass($name)
    {
        $stubPath = $this->getStub();
        if (false === $stubPath){
            return false;
        }
        $stub = file_get_contents($stubPath);

        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
        $moduleName = $this->getModuleName($namespace);

        $class = str_replace($namespace . '\\', '', $name);

        return str_replace(['{%className%}', '{%namespace%}', '{%app_namespace%}','{%moduleName%}'], [
            $class,
            $namespace,
            App::$namespace,
            $moduleName
        ], $stub);

    }

    /**
     * 获取生成路径
     * @param $name
     * @return string
     */
    protected function getPathName($name)
    {
        $name = str_replace(App::$namespace . '\\', '', $name);

        return APP_PATH . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * 获取类名
     * @param $name
     * @return string
     */
    protected function getClassName($name)
    {
        $appNamespace = App::$namespace;

        if (strpos($name, $appNamespace . '\\') === 0) {
            return $name;
        }

        if (Config::get('app_multi_module')) {
            if (strpos($name, '/')) {
                list($module, $name) = explode('/', $name, 2);
            } else {
                $module = 'common';
            }
        } else {
            $module = null;
        }

        if (strpos($name, '/') !== false) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->getNamespace($appNamespace, $module) . '\\' . $name;
    }

    /**
     * 获取命名空间
     * @param $appNamespace
     * @param $module
     * @return string
     */
    protected function getNamespace($appNamespace, $module)
    {
        return $module ? ($appNamespace . '\\' . $module) : $appNamespace;
    }

    /**
     * 获取模块名称
     * @param $namespace
     * @return null
     */
    protected function getModuleName($namespace)
    {
        if (Config::get('app_multi_module')) {
            $arr = explode('\\',$namespace);
            return $arr[1];
        }else{
            return null;
        }
    }

    /**
     * 获取数据库表名
     * @param $tableName
     * @return string
     */
    protected function getTableName($tableName){
        return $this->parseName($tableName);
    }

    /**
     * 字符串命名风格转换
     * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
     * @param string $name 字符串
     * @param integer $type 转换类型
     * @return string
     */
    protected function parseName($name, $type = 0) {
        if ($type) {
            return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {return strtoupper($match[1]);}, $name));
        } else {
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
        }
    }

}
