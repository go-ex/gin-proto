package api

type Config = map[string]string

func Get(str string) *Config {
	config := make(Config)
	config["method"] = "get"
	config["url"] = str

	return &config
}

func Post(str string) *Config {
	config := make(Config)
	config["method"] = "post"
	config["url"] = str

	return &config
}
