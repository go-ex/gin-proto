<?php


namespace GoProto\Proto;

use GoProto\Help;

class ProtoParser
{
    private $file;
    private $syntax;
    private $services = [];
    private $option = [];
    private $import = [];
    private $package;

    public function __construct(string $file)
    {
        $this->file = $file;

        $this->parser();
    }

    public function getFile(): string
    {
        return $this->file;
    }

    private function parser()
    {
        $fileContextArr = file($this->file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        for ($i = 0; $i < count($fileContextArr);) {
            $str     = $fileContextArr[$i];
            $strArr  = explode(' ', $str);
            $trimStr = trim($str);

            // 处理完后，返回下个结构的开始点
            switch ($strArr[0]) {
                case 'syntax':
                    $i++;
                    $this->syntax = Help\Str::cutStr('"', '";', explode('=', $trimStr)[1]);
                    break;
                case 'package':
                    $i++;
                    $this->package = Help\Str::cutStr(' ', ';', $trimStr);
                    break;
                case 'import':
                    $i++;
                    $this->import[] = Help\Str::cutStr('"', '";', $trimStr);
                    break;
                case 'option':
                    $i++;
                    $this->option[trim(Help\Str::cutStr(' ', ' =', $trimStr))] = Help\Str::cutStr('"', '";', explode('=', $trimStr)[1]);
                    break;
                case 'service':
                    $i = $this->loadServices($i, $fileContextArr);
                    break;
                case 'message':// TODO message
                    $i++;
                    break;
                default:
                    $i++;
                    break;
            }
        }
    }

    private function loadServices(int $j, array $fileContextArr): int
    {
        $need = [];
        for ($i = $j; $i <= count($fileContextArr); $i++) {
            $str    = $fileContextArr[$i];
            $need[] = $str;

            if ($str === '}') {
                $i++;
                break;
            }
        }
        $services   = new ProtoService();
        $serviceRpc = new ProtoServiceRpc();
        $docTemp    = '';
        $_count     = 0;
        $_onRpc     = false;
        foreach ($need as $str) {
            $trimStr = trim($str);
            $strArr  = explode(' ', $trimStr);

            switch ($strArr[0]) {
                case '': // 空格 = 重置
                    $docTemp = '';
                    break;
                case 'service':
                    $services->name = trim(Help\Str::cutStr('service ', '{', $trimStr));
                    break;
                case '//': // 注视
                    $docTemp = $trimStr;
                    break;
                case 'rpc':
                    $_onRpc = true;
                    if ($docTemp) {
                        $serviceRpc->doc = trim(trim($docTemp, "//"));
                        $docTemp         = '';
                    }
                    $serviceRpc->name   = trim(Help\Str::cutStr('rpc', '(', $trimStr));
                    $serviceRpc->params = [
                        'request'  => Help\Str::cutStr('(', ')', $trimStr),
                        'response' => Help\Str::cutStr('(', ')', explode('returns', $trimStr)[1]),
                    ];
                    break;
                case 'option':
                    $optType  = Help\Str::cutStr('(', ')', $trimStr);
                    $optValue = Help\Str::cutStr('"', '";', $trimStr);

                    if ($_onRpc) {
                        $serviceRpc->option[$optType] = $optValue;
                    } else {
                        $services->option[$optType] = $optValue;
                    }
                    break;
                case '{':
                    $_count++;
                    break;
                case '}':
                    $_count--;
                    if ($_count <= 0) {
                        if ($serviceRpc->name) {
                            $services->rpc[] = $serviceRpc;
                        }

                        $serviceRpc = new ProtoServiceRpc();
                        $docTemp    = '';
                        $_count     = 0;
                        $_onRpc     = false;
                    }

                    break;
                default:
                    if (strlen($trimStr) >= 1) {
                        $last = strlen($trimStr);

                        if ($trimStr[$last - 1] == '{') {
                            $_count++;
                        }
                    }

                    break;
            }
        }

        $this->services[] = $services;

        return $i;
    }

    /**
     * @return ProtoService[]
     */
    public function getServices(): array
    {
        return $this->services;
    }

    public function geOptions($key = null)
    {
        if ($key) {
            return $this->option[$key] ?? '';
        }
        return $this->option;
    }

    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @return array
     */
    public function getImport(): array
    {
        return $this->import;
    }

    /**
     * @return mixed
     */
    public function getSyntax()
    {
        return $this->syntax;
    }
}