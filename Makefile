GOBIN := $(shell go env GOBIN)
ATDIR := $(shell pwd)

# 按照代码工具
# vim ~/.bash_profile
# export GOPATH=$HOME/go PATH=$PATH:$GOPATH/bin
install:
	brew install protobuf
	go get -u github.com/golang/protobuf/proto			# proto 工具链
	go get -u github.com/golang/protobuf/protoc-gen-go	# proto 工具链
	go get github.com/google/wire/cmd/wire				# 依赖注入

# 安装自动更新依赖wire文件
# 需要执行权限
install-php-inject:
	ln -s -f $(ATDIR)/bin/go-inject $(GOBIN)/gin-inject

protoc:
	protoc --proto_path=./proto/http \
	--proto_path=./proto/controllers \
	--go_out=./runtime/proto \
	proto/controllers/*


proto-gin:
	php bin/proto-gin --proto_path=./proto/controllers --out_http=./app/http

gin-inject:
	php bin/go-inject --bean_path=./routes