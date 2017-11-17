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

class Model extends Generate
{
    protected function configure()
    {
        parent::configure();
        $this->setName('generate:model')
            ->setDescription('Create a new model class');
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
        return __DIR__ . '/stubs/model.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\model';
    }
}
