package provoders

import (
	"github.com/go-ex/gin-proto/bootstrap/help"
	"github.com/jinzhu/gorm"
)

type database *gorm.DB

func openDb() database {
	database, err := gorm.Open("sqlite3", "test.db")
	if err != nil {
		panic("链接数据库失败")
	}

	return database
}

func InitDb(conf *config) database {
	help.P(conf)
	// TODO 解析参数
	return openDb()
}

// Db 便捷操作数据
func Db() database {
	return App().Db
}
