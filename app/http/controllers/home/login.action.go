package home

import (
	"github.com/gin-gonic/gin"
	"net/http"
)

// Login 登陆
func (h *Home) Login(c *gin.Context) {
	c.String(http.StatusOK,"Login")
}