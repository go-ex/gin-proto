package http

import (
	"github.com/gin-gonic/gin"
	"github.com/go-ex/gin-proto/bootstrap/constraint"
	"github.com/go-ex/gin-proto/routes"
)

type app struct {
	engine *gin.Engine
	config string
	routes *routes.ControllersRoutes
}

func GetServer(config interface{}) constraint.ServerInterfaces {
	return &app{
		engine: gin.Default(),
		config: config.(string),
		routes: routes.InitializeNewControllersRoutesProvider(),
	}
}

func (a *app) Run()  {
	a.loadApiRoutes()

	_ = a.engine.Run(":8080")
}

func (a *app) loadApiRoutes() {
	for f, fun := range a.routes.GetLoginRoutes() {
		config := *f
		switch config["method"] {
		case "get":
			a.engine.GET(config["url"], fun)
		case "post":
			a.engine.POST(config["url"], fun)
		case "put":
			a.engine.PUT(config["url"], fun)
		case "delete":
			a.engine.DELETE(config["url"], fun)
		}
	}
}