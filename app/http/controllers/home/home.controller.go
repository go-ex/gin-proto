// 本文件初始化是基于php-proto工具产生
// 默认下这里只做依赖注入的声明
// 本文件没有有逻辑, 但是文件注释不能删除

package home

import "github.com/go-ex/gin-proto/app/logics/login"

type Home struct {

}

//go:generate gin-inject
//已经引入了依赖注入, 这里直接可以修改传入参数, 依赖的参数必须是指针
func NewHomeProvider(account *login.Account) *Home  {
	return &Home{}
}