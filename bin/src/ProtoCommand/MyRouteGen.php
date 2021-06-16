<?php


namespace GoProto\ProtoCommand;


use GoProto\Help\Str;
use GoProto\Proto\ProtoParser;

class MyRouteGen
{
    private $parser;
    private $outHttp;

    private $package;
    private $routes = [];
    private $import = [];
    private $controllers = []; // 参数格式
    private $providerName = [];
    private $pars = [];

    // 文件模版
    private $routeText;
    private $groupText;
    private $wireText;

    public function __construct(string $outHttp)
    {
        $this->outHttp = $outHttp;

        $this->wireText  = file_get_contents(__DIR__.'/template/route_wire');
        $this->routeText = file_get_contents(__DIR__.'/template/route');
        $this->groupText = file_get_contents(__DIR__.'/template/route_group');
    }

    public function scan(ProtoParser $parser)
    {
        $this->parser  = $parser;
        $this->package = $this->parser->getPackage();

        $outHttp = $this->outHttp;
        $module  = Proto::getModule();

        $outHttp     = str_replace(['.'], [''], $outHttp);
        $httpPackage = "{$module}/{$outHttp}";
        $httpPackage = str_replace(['//'], ['/'], $httpPackage);

        foreach ($this->parser->getServices() as $server) {
            if (!Proto::hasRoute($server)) {
                continue;
            }
            $routeGroup                = $server->getOptions('http.Route');
            $serverName                = $server->getName();
            $importName                = Str::toUnderScore($serverName);
            $this->import[$importName] = '"'."{$httpPackage}/{$this->package}/{$importName}".'"';

            $this->controllers[]  = "{$serverName}Controller *{$importName}.{$serverName}";
            $this->pars[]         = "{$serverName}Controller: {$serverName}Controller,";
            $this->providerName[] = "{$importName}.New{$serverName}Provider";
            foreach ($server->getRpcList() as $rpc) {
                $rpVal = "c.{$serverName}Controller.".$rpc->getName();
                foreach ($rpc->getOptions() as $type => $option) {
                    $this->routes[$routeGroup][] = "{$type}(\"{$option}\"):    {$rpVal},";
                }
            }
        }
    }

    public function makeFile()
    {
        $routeContext = '';
        foreach ($this->routes as $routeGroup => $routeArr) {
            $routeContext .= str_replace(
                    ['{group}', '{route}'],
                    [ucfirst($routeGroup), implode(PHP_EOL, $routeArr)],
                    $this->groupText
                ).PHP_EOL;
        }

        $fileText = str_replace(
                ['{import}', '{-package-}', '{controllers}', '{controllers-var}', '{route}'],
                [
                    implode(PHP_EOL, $this->import),
                    ucfirst($this->package),
                    implode(PHP_EOL, $this->controllers),
                    implode(",".PHP_EOL, $this->controllers).",".PHP_EOL,
                    implode(PHP_EOL, $this->pars),
                ],
                $this->routeText.$routeContext
            ).PHP_EOL;

        echo "输出路由文件： ".$this->getRouteFile();
        file_put_contents($this->getRouteFile(), $fileText);
        @system("go fmt ".$this->getRouteFile());
    }

    /**
     * 根据proto 的 package 设置同名路由文件
     * @return string
     */
    private function getRouteFile(): string
    {
        $protoPackage = $this->parser->getPackage();

        return project_path.'/routes/'.$protoPackage.'.go';
    }
}