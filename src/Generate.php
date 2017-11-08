<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 刘志淳 <chun@engineer.com>
// +----------------------------------------------------------------------

namespace nbczw8750\generate;

use think\App;
use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

abstract class Generate extends Command
{

    protected $type;

    abstract protected function getStub();

    protected function configure()
    {
        $this->addArgument('name', Argument::REQUIRED, "The name of the class");
    }

    protected function execute(Input $input, Output $output)
    {

        $name = trim($input->getArgument('name'));

        $classname = $this->getClassName($name);

        $pathname = $this->getPathName($classname);

        if (is_file($pathname)) {
            $output->writeln('<error>' . $this->type . ' already exists!</error>');
            return false;
        }

        if (!is_dir(dirname($pathname))) {
            mkdir(strtolower(dirname($pathname)), 0755, true);
        }

        file_put_contents($pathname, $this->buildClass($classname));

        $output->writeln('<info>' . $this->type . ' created successfully.</info>');

    }


    protected function buildClass($name)
    {
        $stub = file_get_contents($this->getStub());

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

    protected function getPathName($name)
    {
        $name = str_replace(App::$namespace . '\\', '', $name);

        return APP_PATH . str_replace('\\', '/', $name) . '.php';
    }

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

    protected function getNamespace($appNamespace, $module)
    {
        return $module ? ($appNamespace . '\\' . $module) : $appNamespace;
    }

    protected function getModuleName($namespace)
    {
        if (Config::get('app_multi_module')) {
            $arr = explode('\\',$namespace);
            return $arr[1];
        }else{
            return null;
        }
    }

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
