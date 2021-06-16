//go:generate wire
//+build wireinject

package routes

import (
	"github.com/go-ex/gin-proto/app/http/controllers/home"
	"github.com/go-ex/gin-proto/app/http/controllers/login"
	"github.com/google/wire"
)

func InitializeNewControllersRoutesProvider() *ControllersRoutes {
	wire.Build(NewControllersRoutesProvider, home.InitializeNewHomeProvider, login.InitializeNewLoginProvider)

	return nil
}
