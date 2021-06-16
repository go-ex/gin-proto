package help

import (
	"encoding/json"
	"log"
)

// ToString 任意对象转字符串
func ToString(obj interface{}) string {
	str, err := json.Marshal(obj)
	if err != nil {
		return ""
	}
	return string(str)
}

// P 输出任意内容
func P(obj interface{})  {
	log.Println(ToString(obj))
}
