# thinkphp5 文件生成器

## 安装
~~~
composer require nbczw8750/think-generate dev-master

~~~
## 使用
~~~
创建model
执行下面的指令可以生成test模块的Blog模型类库文件
php think generate:model test/Blog

创建logic
执行下面的指令可以生成test模块的Blog逻辑类库文件
php think generate:logic test/Blog

创建service
执行下面的指令可以生成test模块的Blog服务类库文件
php think generate:service test/Blog

创建service
执行下面的指令可以生成test模块的Blog验证类库文件
php think generate:validate test/Blog

创建controller
执行下面的指令可以生成test模块的Blog控制器类库文件
php think generate:controller test/Blog
指定行为操作表名称 默认不填就是类名
php think generate:controller test/Blog --table blog

创建配置文件
执行下面的指令可以生成test模块验证类库文件
php think generate:config test/config

创建函数文件
执行下面的指令可以生成test模块验证类库文件
php think generate:common test/common
~~~