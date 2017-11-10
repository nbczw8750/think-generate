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
use think\console\input\Option;
use think\console\Input;
use think\console\Output;

class Batch extends Generate
{

    protected $command = null;

    protected function configure()
    {
        parent::configure();
        $this->setName('generate:batch')
            ->addOption('table', null,Option::VALUE_OPTIONAL, "The table of the database")
            ->setDescription('Create all class');
    }
    protected function execute(Input $input, Output $output)
    {
        $name = trim($this->input->getArgument('name'));
        if (strpos($name, '/') !== false) {
            $name = str_replace('/', '\\', $name);
        }
        $moduleName = $this->getModuleName($name);
        $classes = [
            "\\nbczw8750\\generate\\command\\Model"=>[],
            "\\nbczw8750\\generate\\command\\Logic"=>[],
            "\\nbczw8750\\generate\\command\\Service"=>[],
            "\\nbczw8750\\generate\\command\\Validate"=>[],
            "\\nbczw8750\\generate\\command\\Controller"=>[],
            "\\nbczw8750\\generate\\command\\Common"=>["name"=>$moduleName ? $moduleName."/common" : "common"],
            "\\nbczw8750\\generate\\command\\Config"=>["name"=>$moduleName ? $moduleName."/config" : "config"],
        ];
        foreach ($classes as $class => $params){
            if (class_exists($class)){
                $this->command = new $class();
                $this->command->setInput($input);

                foreach ($params as $k => $v){
                    $this->command->getInput()->setArgument($k,$v);
                }
                $this->command->make();
                $output->writeln($this->command->getMsg());
            }else{
                $output->writeln('<error>' . $class . ' no exists!</error>');
            }

        }
    }
    protected function getStub(){

    }

}
