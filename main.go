package main

import (
	"github.com/go-ex/gin-proto/app/http"
	"github.com/go-ex/gin-proto/app/provoders"
	"github.com/go-ex/gin-proto/bootstrap/constraint"
)

func main() {
	app := provoders.GetApp()

	app.Run([]constraint.ServerInterfaces{
		http.GetServer(":8080"),
	})
}


