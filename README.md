# gin-proto 这不是框架，不是框架，不是框架。
gin, proto, wire缝合怪。

实现一些有趣的想法。

使用php解析proto文件，生成gin的路由信息和基础控制器空文件
~~~~
make proto-gin
~~~~
使用php解析go代码，根据固定命名规则，生成依赖图。
~~~~
make gin-inject
~~~~

`gin-inject` 工具根据传入参数，直接维护对应结构体的提供者函数+wire库的Build定义
~~~~go
func NewHomeProvider(account *login.Account) *Home  {
	return &Home{}
}
~~~~
例如上面代码，函数名符合 New{struceName}Provider 的命名格式；

`gin-inject` 工具就会解析该函数，直接为参数login.Account生成NewAccountProvider提供者，同时生成可被依赖提供函数。
这些都是自动，无需关心。

任何地方无脑用就可以了。完全使用php,laravel 的依赖注入习惯。