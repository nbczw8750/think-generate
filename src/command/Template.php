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

use nbczw8750\generate\Generate;
use think\App;
use think\console\input\Option;

class Template extends Generate
{
    protected function configure()
    {
        parent::configure();
        $this->setName('generate:template')
            ->addOption("view",null,Option::VALUE_REQUIRED, "The view is file name")
            ->addOption("type",null,Option::VALUE_OPTIONAL, "The type is template name")
            ->setDescription('Create a new template file');
    }
    /**
     * 获取生成路径
     * @param $name
     * @return string
     */
    protected function getPathName($name)
    {
        $arr = explode("\\",$name);
        array_splice($arr,-1,0,["view"]);
        $view = $this->input->getOption("view");
        $arr[] = $view;
        $name = str_replace(App::$namespace . '\\', '', implode("\\",$arr));
        return APP_PATH . str_replace('\\', '/', $name) . '.html';
    }

    protected function getStub()
    {
        $stub = $this->input->getOption('stub');
        if ($stub){
            if (file_exists($stub)){
                return $stub;
            }else{
                $this->msg = "stub file non-existent";
                return false;
            }
        }

        $action = $this->input->getOption("type") ? $this->input->getOption("type") : $this->input->getOption("view") ;
        switch ($action){
            case "index" :
                return __DIR__ . '/stubs/view.index.stub';
                break;
            case "modal" :
                return __DIR__ . '/stubs/view.modal.stub';
                break;
            case "edit" :
                return __DIR__ . '/stubs/view.edit.stub';
                break;
            default:
                return __DIR__ . '/stubs/view.edit.stub';
                break;
        }
    }
}
