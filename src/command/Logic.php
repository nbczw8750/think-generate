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

class Logic extends Generate
{

    protected $type = "Logic";

    protected function configure()
    {
        parent::configure();
        $this->setName('generate:logic')
            ->addOption('plain', null, Option::VALUE_NONE, 'Generate an empty service class.')
            ->setDescription('Create a new resource service class');
    }

    protected function getStub()
    {
        if ($this->input->getOption('plain')) {
            return $this->input->getOption('plain');
        }

        return __DIR__ . '/stubs/logic.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\logic';
    }

}
