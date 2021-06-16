<?php


namespace GoProto\ProtoCommand;


use GoProto\Help\Str;
use GoProto\Proto\GenCode;
use GoProto\Proto\ProtoParser;
use GoProto\Proto\ProtoService;

class MyControllerGen
{
    private $outHttp;

    public function __construct(string $outHttp)
    {
        $this->outHttp = $outHttp;

        if (!is_dir($this->outHttp)) {
            mkdir($this->outHttp, 0755, true);
        }
    }

    public function gen(ProtoParser $parser, ProtoService $server)
    {
        $serverName        = $server->getName();
        $outControllerPath = $this->getControllerFile($parser, $server);
        if (!is_dir($outControllerPath)) {
            mkdir($outControllerPath, 0755, true);
        }
        if (!Proto::hasRoute($server)) {
            return;
        }
        // 路由创建
        $outControllerFile = $outControllerPath.'/'.Str::toUnderScore($serverName).'.controller.go';
        if (!file_exists($outControllerFile)) {
            $outControllerText = GenCode::getController(
                Str::toUnderScore($serverName),
                $serverName,
                pathinfo($outControllerFile, PATHINFO_BASENAME)
            );
            file_put_contents($outControllerFile, $outControllerText);
            echo "写入控制器 {$outControllerFile} ", PHP_EOL;
        }

        // 函数创建
        foreach ($server->getRpcList() as $rpc) {
            foreach ($rpc->getOptions() as $type => $option) {
                $actionFile = $outControllerPath.'/'.Str::toUnderScore($rpc->getName()).'.action.go';
                if (!file_exists($actionFile)) {
                    $outActionText = GenCode::getAction(
                        Str::toUnderScore($serverName), $serverName, $rpc->getName(),
                        $rpc->getDoc()
                    );
                    file_put_contents($actionFile, $outActionText);
                    echo "写入路由函数 {$actionFile} ", PHP_EOL;
                }
            }
        }
    }

    public function getControllerFile(ProtoParser $parser, ProtoService $server, $outHttp = ''): string
    {
        $serverName = $server->getName();
        return ($this->outHttp ?: $outHttp).'/'.$parser->getPackage().'/'.Str::toUnderScore($serverName);
    }
}