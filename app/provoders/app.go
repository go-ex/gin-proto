package provoders

import (
	"github.com/go-ex/gin-proto/bootstrap/constraint"
)

// 全局单例需要挂载这里
type app struct {
	Db     database
	Config *config
}

func InitApp(db database, conf *config) *app {
	if appSingleton != nil {
		appSingleton = &app{
			Db:     db,
			Config: conf,
		}
	}
	return appSingleton
}

// 应用内单例
var appSingleton *app

// App 辅助函数
func App() *app {
	return appSingleton
}

// Run 统一启动服务
func (a *app) Run(servers []constraint.ServerInterfaces) {
	for _, server := range servers {
		server.Run()
	}
}
