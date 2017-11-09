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

class Service extends Generate
{

    protected $type = "Service";

    protected function configure()
    {
        parent::configure();
        $this->setName('generate:service')
            ->addOption('plain', null, Option::VALUE_NONE, 'Generate an empty service class.')
            ->setDescription('Create a new resource service class');
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
        return __DIR__ . '/stubs/service.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\service';
    }
}
