//go:generate wire
//+build wireinject

package login

import (
	"github.com/google/wire"
)

func InitializeNewAccountProvider() *Account {
	wire.Build(NewAccountProvider)

	return nil
}
