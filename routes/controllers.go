//go:generate wire
//根据proto定义生成本文件
//整个文件内容都不需要更改, 每次自动跟随proto定义更新

package routes

import (
	"github.com/gin-gonic/gin"
	"github.com/go-ex/gin-proto/app/http/controllers/home"
	"github.com/go-ex/gin-proto/app/http/controllers/login"
	http "github.com/go-ex/gin-proto/bootstrap/http/api"
)

// ControllersRoutes @bean
type ControllersRoutes struct {
	HomeController  *home.Home
	LoginController *login.Login
}

// NewControllersRoutesProvider 提供者格式命名
// NewControllersRoutesProvider New{package}RoutesProvider
func NewControllersRoutesProvider(
	HomeController *home.Home,
	LoginController *login.Login,

) *ControllersRoutes {
	return &ControllersRoutes{
		HomeController:  HomeController,
		LoginController: LoginController,
	}
}

// GetLoginRoutes Get{option (http.Route)}Routes
func (c *ControllersRoutes) GetLoginRoutes() map[*http.Config]func(c *gin.Context) {
	return map[*http.Config]func(c *gin.Context){
		http.Get("/api/home"):       c.HomeController.Index,
		http.Get("/api/home"):       c.HomeController.Login,
		http.Get("/api/login/user"): c.LoginController.User,
		http.Get("/api/login"):      c.LoginController.Login,
	}
}
