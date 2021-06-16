package provoders

type config struct {

}

func InitConfig() *config {
	return &config{}
}

func Config() *config {
	return App().Config
}