// Code generated by Wire. DO NOT EDIT.

//go:generate go run github.com/google/wire/cmd/wire
//+build !wireinject

package login

// Injectors from login.controller.wire.go:

func InitializeNewLoginProvider() *Login {
	login := NewLoginProvider()
	return login
}