//go:generate wire
//+build wireinject

package login

import (
	"github.com/google/wire"
)

func InitializeNewLoginProvider() *Login {
	wire.Build(NewLoginProvider)

	return nil
}
