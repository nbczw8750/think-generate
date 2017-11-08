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
use nbczw8750\generate\Generate;

class Config extends Generate
{
    protected $type = "Config";

    protected function configure()
    {
        parent::configure();
        $this->setName('generate:config')
            ->setDescription('Create a new config file');
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/config.stub';
    }
}
