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

namespace nbczw8750\generate\command;

use think\App;
use think\Config;
use nbczw8750\generate\Generate;
use think\console\input\Option;
use think\console\Input;
use think\console\Output;

class Controller extends Generate
{

    protected $type = "Controller";

    protected function configure()
    {
        parent::configure();
        $this->setName('generate:controller')
            ->addOption('table', null,Option::VALUE_OPTIONAL, "The table of the database")
            ->setDescription('Create a new resource controller class');
    }

    protected function buildClass($name)
    {
        $table = $this->input->getOption('table');
        $stub = file_get_contents($this->getStub());

        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
        $moduleName = $this->getModuleName($namespace);

        $class = str_replace($namespace . '\\', '', $name);

        $tableName = $this->getTableName(isset($table) ? $table : $class);

        return str_replace(['{%className%}', '{%namespace%}', '{%app_namespace%}','{%tableName%}','{%moduleName%}'], [
            $class,
            $namespace,
            App::$namespace,
            $tableName,
            $moduleName
        ], $stub);

    }
    protected function getStub()
    {
        return __DIR__ . '/stubs/controller.stub';
    }

    protected function getClassName($name)
    {
        return parent::getClassName($name) . (Config::get('controller_suffix') ? ucfirst(Config::get('url_controller_layer')) : '');
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\controller';
    }
}
