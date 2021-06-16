package models

import (
	"fmt"
)

type User struct {

}

//go:generate gin-inject
//已经引入了依赖注入, 这里直接可以修改传入参数
//修改参数后, 手动执行 gin-inject
func NewUserProvider() *User {
	return &User{}
}

func (u *User) Hei(str string) {
	fmt.Println("hei User " + str)
}