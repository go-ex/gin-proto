<?php


namespace GoProto\Golang;

use GoProto\Help\GoMod;
use GoProto\Help\Str;

/**
 * 只解析单go文件, 只严格解析fmt后的文件
 * @package GoProto\Golang
 */
class Parser
{
    protected $file;
    protected $package;
    protected $imports = [];
    protected $struct = [];
    protected $func = [];
    protected $tempDoc = '';

    public function __construct(string $file)
    {
        $this->file = realpath($file);

        $this->parser();
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return ParserStruct[]
     */
    public function getStruct(): array
    {
        return $this->struct;
    }

    /**
     * @return ParserImport[]
     */
    public function getImports(): array
    {
        return $this->imports;
    }

    protected function parser()
    {
        $fileContextArr = file($this->file, FILE_IGNORE_NEW_LINES);

        for ($i = 0; $i < count($fileContextArr);) {
            $str    = $fileContextArr[$i];
            $strArr = explode(' ', $str);

            // 处理完后，返回下个结构的开始点
            switch ($strArr[0]) {
                case 'package':
                    $i++;
                    unset($strArr[0]);
                    $this->package = implode('', $strArr);
                    $this->tempDoc = '';
                    break;
                case 'import':
                    if (Str::checkLast($str, '(')) {
                        $i++;
                        while (!Str::checkLast($fileContextArr[$i], ')')) {
                            $this->imports[] = $this->ParserImport($fileContextArr[$i]);
                            $i++;
                        }
                    } else {
                        $this->imports[] = $this->ParserImport(explode(' ',trim($str))[1]);
                        $i++;
                    }
                    $this->tempDoc = '';
                    break;
                case 'type':
                    $new = $this->parserType($i, $fileContextArr);
                    if ($new == $i) {
                        throw new \ErrorException("无法解析{$str}");
                    }
                    $i = $new;
                    break;
                case 'func':
                    $new = $this->parserFunc($i, $fileContextArr);
                    if ($new == $i) {
                        throw new \ErrorException("无法解析{$str}");
                    }
                    $i = $new;
                    break;
                case '//':
                    $i++;
                    $this->tempDoc .= ((strlen($this->tempDoc) >= 1 ? PHP_EOL : "").$str);
                    break;
                default:
                    $i++;
                    break;
            }
        }
        $this->tempDoc = '';
    }

    protected function ParserImport(string $str): ParserImport
    {
        $str     = trim($str);
        $str     = trim($str, '"');
        $package = new ParserImport();

        $arr = explode(' ', $str);
        if (count($arr) == 2) {
            $package->setPackage(trim($arr[1], '"'));
            $package->setAlias($arr[0]);
        } else {
            $arr = explode('/', $str);
            $package->setPackage($str);
            $package->setAlias(end($arr));
        }

        return $package;
    }

    /**
     * 这里只需要实现注入格式的函数解析
     * @param  int  $j
     * @param  array  $fileContextArr
     * @return int
     */
    protected function parserFunc(int $j, array $fileContextArr): int
    {
        $func = new ParserFunc();
        if ($this->tempDoc) {
            $func->setDoc($this->tempDoc);
            $this->tempDoc = '';
        }

        $next = 'func';
        for ($i = $j; $i < count($fileContextArr); $i++) {
            $str    = $fileContextArr[$i];
            $strArr = explode(' ', $str);

            foreach ($strArr as $vK => $vStr) {
                switch ($next) {
                    case 'func';
                        $next = 'check_struct';
                        break;
                    case 'check_struct';
                        if ($vStr[0] == "(") {
                            // 是结构体的函数
                            $next = 'struct_mame';
                            goto struct_mame;
                        } else {
                            // 一级公民函数
                            $next = 'func_name';
                            goto func_name;
                        }
                        break;
                    case 'struct_mame';
                        struct_mame:
                        // 解析那个结构体
                        $temp = Str::cutStr('(', ')', $str);
                        $func->setStructName(explode(' ', $temp)[1]);
                        $next = 'func_name';
                        goto func_name;
                        break;
                    case 'func_name';
                        func_name:
                        $func->setName(Str::cutStr('', '(', $vStr));
                        $func->setStartLine($j + 1);
                        if (Str::checkLast($vStr, ')')) {
                            $next = 'check_func_return';
                        } else {
                            $next = 'check_func_params';
                            goto check_func_params;
                        }
                        break;
                    case 'check_func_params';
                        check_func_params:
                        // 解析函数输入参数
                        if (!$str) {
                            break;
                        }
                        if (Str::checkLast($str, '(')) {
                            // 多行参数
                            $onParams = true;
                            break 2;
                        } elseif ($vStr[0] === ')') {
                            // 多行参数, 结束行
                            unset($onParams);
                            $next = 'stop';
                            // 结束行，必须带有返回参数
                            $ret = Str::cutChar(')','{', $str);
                            $ret = substr($ret,1,-1);
                            $func->setReturnStr(trim($ret));
                        } elseif (isset($onParams)) {
                            $params = $func->getParams();
                            if (trim($str) == '') {
                                break;
                            }
                            // 这里只关注注入类型结构体，其他种类直接忽略错误
                            $tempParamsArr = explode(' ', trim(trim($str, ',')));
                            if (count($tempParamsArr) > 2) {
                                $params['all'] = trim(trim($str, ','));
                            } elseif (isset($tempParamsArr[1])) {
                                $params[$tempParamsArr[0]] = $tempParamsArr[1];
                            }
                            $func->setParams($params);
                        } else {
                            // 单行参数
                            $ret = Str::cutChar(')','{', $str);
                            $ret = substr($ret,1,-1);
                            $func->setReturnStr(trim($ret));

                            $paramsStr = Str::cutChar('(', ')', substr($str, strpos($str, $func->getName().'(')));
                            $paramsStr = substr($paramsStr, 1, -1);
                            // 这里只关注注入类型结构体，其他种类直接忽略错误
                            $params = [];
                            foreach (explode(', ', $paramsStr) as $k => $paramsValStr) {
                                $tempParamsArr = explode(' ', $paramsValStr);
                                if (count($tempParamsArr) != 2) {
                                    $params['all_'.$k] = $paramsValStr;
                                } else {
                                    $params[$tempParamsArr[0]] = $tempParamsArr[1];
                                }
                            }
                            $func->setParams($params);
                            $next = 'stop';
                        }
                        break;
                    case 'stop':
                        if ($str == '}') {
                            $func->setEndLine($i + 1);
                            $next = 'default';
                        }
                        break;
                    default;
                        break 3;
                }
            }
        }

        if ($func->getStructName()) {
            $structName = trim($func->getStructName(), '*');
            if (isset($this->struct[$structName])) {
                $this->struct[$structName]->func[$func->getName()] = $func;
            }
        } else {
            $this->func[$func->getName()] = $func;
        }

        return ++$i;
    }

    protected function parserType(int $j, array $fileContextArr): int
    {
        $str    = $fileContextArr[$j];
        $str    = str_replace('  ', ' ', trim($str));
        $strArr = explode(' ', trim($str));

        $newI = $j;
        if ($this->isStruct($strArr)) {
            $struct = new ParserStruct();
            $struct->setStartLine($j + 1);
            $struct->setName($strArr[1]); // 严格 type Test struct

            if (Str::checkLast($str, '}')) {
                $struct->setEndLine($j + 1);
                $newI = ++$j;
            } else {
                for ($i = $j + 1; $i < count($fileContextArr); $i++) {
                    // 独占一行，必须是struct的结束
                    if ($fileContextArr[$i] == '}') {
                        $struct->setEndLine($i + 1);
                        $newI = $i;
                        break;
                    }
                }
            }

            if ($this->tempDoc) {
                $struct->setDoc($this->tempDoc);
                $this->tempDoc = '';
            }
            $this->struct[$struct->getName()] = $struct;
        } else {
            $newI = ++$j;
        }
        return $newI;
    }

    protected function isStruct(array $strArr): bool
    {
        if (in_array('struct', $strArr) || in_array('struct{', $strArr)) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getFunc(): array
    {
        return $this->func;
    }

    /**
     * @return string
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @return string
     */
    public function getPackageAllString(): string
    {
        $projectPath    = GoMod::getProjectPath();
        $projectPackage = GoMod::getProjectPackage();

        return str_replace($projectPath, $projectPackage, dirname($this->getFile()));
    }

    /**
     * @return string
     */
    public function getTempDoc(): string
    {
        return $this->tempDoc;
    }
}