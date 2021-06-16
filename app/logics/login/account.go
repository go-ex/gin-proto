package login

type Account struct {
}

//go:generate gin-inject
//已经引入了依赖注入, 这里直接可以修改传入参数, 依赖的参数必须是指针
//修改参数后, 手动执行 gin-inject 或者 wire
func NewAccountProvider() *Account {
	return &Account{}
}
