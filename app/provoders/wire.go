//go:generate wire
//+build wireinject

package provoders

import (
	"github.com/google/wire"
)

// GetApp 注入声明
func GetApp() *app {
	wire.Build(InitApp, InitDb, InitConfig)

	return nil
}
