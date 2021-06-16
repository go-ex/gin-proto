package home

import (
	"github.com/gin-gonic/gin"
	"net/http"
)

// Index 获取首页信息
func (h *Home) Index(c *gin.Context) {
	c.String(http.StatusOK,"Index")
}