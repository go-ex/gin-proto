//go:generate wire
//+build wireinject

package home

import (
	"github.com/go-ex/gin-proto/app/logics/login"
	"github.com/google/wire"
)

func InitializeNewHomeProvider() *Home {
	wire.Build(NewHomeProvider, login.InitializeNewAccountProvider)

	return nil
}
