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
use think\Config;

class Batch extends Generate
{

    protected $command = null;

    protected function configure()
    {
        parent::configure();
        $this->setName('generate:batch')
            ->addOption('table', null,Option::VALUE_OPTIONAL, "The table of the database")
            ->addOption("view",null,Option::VALUE_REQUIRED, "The view is file name")
            ->addOption("type",null,Option::VALUE_OPTIONAL, "The type is template name")
            ->setDescription('Create all class');
    }
    protected function execute(Input $input, Output $output)
    {
        $name = trim($this->input->getArgument('name'));
        $moduleName = $this->getModuleName($name);
        $classes = [
            ["command" => "\\nbczw8750\\generate\\command\\Model" , "arguments"=>["name"=>$name] , "options" => [] ],
            ["command" => "\\nbczw8750\\generate\\command\\Logic" , "arguments"=>["name"=>$name] , "options" => [] ],
            ["command" => "\\nbczw8750\\generate\\command\\Service" , "arguments"=>["name"=>$name] , "options" => [] ],
            ["command" => "\\nbczw8750\\generate\\command\\Validate" , "arguments"=>["name"=>$name] , "options" => [] ],
            ["command" => "\\nbczw8750\\generate\\command\\Controller" , "arguments"=>["name"=>$name] , "options" => [] ],
            ["command" => "\\nbczw8750\\generate\\command\\Common" , "arguments"=>["name"=>$moduleName ? $moduleName."/common" : "common"] , "options" => [] ],
            ["command" => "\\nbczw8750\\generate\\command\\Config" , "arguments"=>["name"=>$moduleName ? $moduleName."/config" : "config"] , "options" => [] ],
            ["command" => "\\nbczw8750\\generate\\command\\Template" , "arguments"=>["name"=>$name] , "options" => ["view"=>"index"] ],
            ["command" => "\\nbczw8750\\generate\\command\\Template" , "arguments"=>["name"=>$name] , "options" => ["view"=>"edit"] ],

        ];
        foreach ($classes as $item){
            if (class_exists($item["command"])){
                $command = new $item["command"]();
                $command->setInput($input);

                foreach ($item["arguments"] as $k => $v){
                    $command->setArgument($k,$v);
                }

                foreach ($item["options"] as $k => $v){
                    $command->setOption($k,$v);
                }

                $command->make();
                $output->writeln($command->getMsg());
            }else{
                $output->writeln('<error>' . $item["command"] . ' no exists!</error>');
            }

        }
    }
    protected function getStub(){

    }

    /**
     * 获取模块名称
     * @param $namespace
     * @return null
     */
    protected function getModuleName($name)
    {
        if (Config::get('app_multi_module')) {
            $arr = explode('/',$name);
            return isset($arr[1]) ? $arr[0] : "common" ;
        }else{
            return null;
        }
    }

}
