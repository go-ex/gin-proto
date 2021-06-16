package login

import (
	"github.com/gin-gonic/gin"
	"net/http"
)

// User User action
func (h *Login) User(c *gin.Context) {
	c.String(http.StatusOK,"User")
}