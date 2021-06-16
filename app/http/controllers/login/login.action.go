package login

import (
	"github.com/gin-gonic/gin"
	"net/http"
)

// Login 登陆
func (h *Login) Login(c *gin.Context) {
	c.String(http.StatusOK,"Login")
}