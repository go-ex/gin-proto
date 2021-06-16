//go:generate wire
//+build wireinject

package models

import (
	"github.com/google/wire"
)

func InitializeNewUserProvider() *User {
	wire.Build(NewUserProvider)

	return nil
}
