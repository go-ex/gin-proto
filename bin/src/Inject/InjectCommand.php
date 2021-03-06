<?php

namespace GoProto\Inject;

use GoProto\Golang\Parser;
use GoProto\Golang\ParserFunc;
use GoProto\Golang\ParserStruct;
use GoProto\Help\File;
use GoProto\Help\GoPackage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InjectCommand
{
    public $input;
    public $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;
    }

    public function run(string $beanDirs = '', string $createStructProvider = '', bool $star = false)
    {
        if (!$beanDirs) {
            $beanDirs = $this->input->getOption('bean_path');

            if (!$beanDirs) {
                $beanDirs = getcwd();
            }
            $beanDirs = realpath($beanDirs);
        }

        foreach (scandir($beanDirs) as $v) {
            if (in_array($v, ['.', '..'])) {
                continue;
            }

            $file = $beanDirs.'/'.$v;
            if (!is_file($file)) {
                continue;
            }

            if (!pathinfo($file, PATHINFO_EXTENSION) == 'go') {
                continue;
            }

            $parser = new Parser($file);
            $funcs  = $parser->getFunc();

            if ($createStructProvider) {
                $structs = $parser->getStruct();
                if (isset($structs[$createStructProvider])) {
                    $providerName = "New{$createStructProvider}Provider";
                    if (!isset($funcs[$providerName])) {
                        $this->makeProvider($createStructProvider, $parser, $star);
                        $parser = new Parser($file);
                        $funcs  = $parser->getFunc();
                    }
                }
            }

            foreach ($parser->getStruct() as $struct) {
                $structName   = $struct->getName();
                $providerName = "New{$structName}Provider";
                if (isset($funcs[$providerName])) {
                    $func = $funcs[$providerName];
                    $this->checkHasProvider($struct, $func, $parser);
                }
            }
        }
    }

    /**
     * ??????????????????
     * ??????????????????????????????????????????????????????
     * @param  ParserFunc  $func
     * @param  Parser  $parser
     */
    public function checkHasProvider(ParserStruct $parserStruct, ParserFunc $func, Parser $parser)
    {
        $package = [];
        foreach ($parser->getImports() as $import) {
            if (!isset($package[$import->getAlias()])) {
                $package[$import->getAlias()] = $import->getPackage();
            }
        }
        foreach ($func->getParams() as $name => $param) {
            $arr = explode('.', $param);
            if (count($arr) == 1) {
                $star = $param[0] == '*';
                // ??????????????????
                $this->makeProvider(trim($param, '*'), $parser, $star);
                $this->makeProviderWire(trim($param, '*'), $parser, $star);
            } else {
                $star          = ($arr[0][0] == '*');
                $packageName   = $star ? trim($arr[0], '*') : $arr[0];
                $struct        = $arr[1];
                $structPackage = GoPackage::toFile($package[$packageName]);
                foreach (scandir($structPackage) as $tempDir) {
                    if (in_array($tempDir, ['.', '..']) || is_dir($tempDir)) {
                        continue;
                    }
                    $tempParser = new Parser($structPackage.'/'.$tempDir);
                    $tempStruct = $tempParser->getStruct();

                    if (!isset($tempStruct[$struct])) {
                        continue;
                    }

                    // ??????NewProvider
                    $this->makeProvider($struct, $tempParser, $star);
                    // ??????NewProvider ??? wire
                    $this->makeProviderWire($struct, $tempParser, $star);
                }
            }
        }

        $this->makeProviderWire($parserStruct->getName(), $parser, true);
    }

    /**
     * ?????????????????????
     * @param  string  $structName
     * @param  Parser  $parser
     * @param  bool  $star
     * @return bool true ?????????
     */
    private function makeProvider(string $structName, Parser $parser, bool $star): bool
    {
        $tempFunc = $parser->getFunc();
        if (!isset($tempFunc["New{$structName}Provider"])) {
            $tempStruct = $parser->getStruct();
            if (isset($tempStruct[$structName])) {
                $struct       = $tempStruct[$structName];
                $providerCode = $this->getProviderCode();
                $providerCode = str_replace(
                    ['{struct}', '{*star}', '{&star}'],
                    [$structName, $star ? '*' : "", $star ? '&' : ""],
                    $providerCode
                );
                File::insert($parser->getFile(), PHP_EOL.PHP_EOL."{$providerCode}".PHP_EOL, $struct->getEndLine());
                return true;
            } else {
                // ??????????????????????????????
                $dir             = dirname($parser->getFile());
                $checkTempParser = $this->getStructParser($structName, $dir);
                $this->makeProvider($structName, $checkTempParser, true);
                $this->makeProviderWire($structName, $checkTempParser, true);
            }
        }
        return false;
    }

    public function getStructParser(string $structName, $dir): Parser
    {
        foreach (scandir($dir) as $fileName) {
            $file = $dir.'/'.$fileName;
            if (!in_array($fileName, ['.', '..'])) {
                $checkTempParser = new Parser($file);
                $tempStruct      = $checkTempParser->getStruct();
                if (isset($tempStruct[$structName])) {
                    return $checkTempParser;
                }
            }
        }
    }

    /**
     * @param  string  $structName
     * @param  Parser  $parser
     * @param  bool  $star
     */
    private function makeProviderWire(string $structName, Parser $parser, bool $star)
    {
        $file     = $parser->getFile();
        $file     = str_replace(['.go'], ['.wire.go'], $file);
        $tempFunc = $parser->getFunc();

        $funcProviderName = "New{$structName}Provider";
        if (isset($tempFunc[$funcProviderName])) {
            $func = $tempFunc[$funcProviderName];
        } else {
            $parser   = $this->getStructParser($structName, dirname($parser->getFile()));
            $file     = $parser->getFile();
            $file     = str_replace(['.go'], ['.wire.go'], $file);
            $tempFunc = $parser->getFunc();
            $func     = $tempFunc[$funcProviderName];
        }

        $allImport = [];
        foreach ($parser->getImports() as $import) {
            if (!isset($allImport[$import->getAlias()])) {
                $allImport[$import->getAlias()] = $import->getPackage();
            }
        }

        $import = [];
        $inject = [];
        /** @var ParserFunc $func */
        foreach ($func->getParams() as $param) {
            $arr = explode('.', $param);
            if (count($arr) == 1) {
                $paramStruct = trim($param, '*');
                $inject[]    = "InitializeNew{$paramStruct}Provider";
                $alias       = $parser->getPackage();
                $paramStar   = $param != $paramStruct;
            } else {
                $alias          = trim($arr[0], '*');
                $paramStruct    = trim($arr[1], '*');
                $inject[]       = "{$alias}.InitializeNew{$paramStruct}Provider";
                $import[$alias] = '"'.$allImport[$alias].'"';
                $paramStar      = $alias != $arr[0];
            }
            // ??????????????????
            if(isset($allImport[$alias])){
                $otherPackage = $allImport[$alias];
                $this->checkOtherPackage($otherPackage, $paramStruct, $paramStar);
            }
        }
        $providerCode = str_replace(
            ['{package}', '{import}', '{struct}', '{*star}', '{&star}', '{inject}'],
            [
                $parser->getPackage(),
                implode(PHP_EOL, $import),
                $structName, $star ? '*' : "", $star ? '&' : "",
                $inject ? ','.implode(', ', $inject) : ''
            ],
            $this->getProviderCodeWire()
        );

        file_put_contents($file, $providerCode);
        @system("go fmt {$file}");
        $fileDir = dirname($file);
        $this->output->writeln("cd {$fileDir}  &&wire");
        @system("cd {$fileDir} && wire");
    }

    private $checkOtherPackageCache = [];

    private function checkOtherPackage(string $package, string $createStructProvider = '', bool $star = false)
    {
        $path = GoPackage::toFile($package);
        if ($path) {
            $this->output->writeln($path);
            $this->run($path, $createStructProvider, $star);
        }
    }

    protected $providerCode;

    protected function getProviderCode()
    {
        if (!$this->providerCode) {
            $this->providerCode = file_get_contents(__DIR__.'/template/provider');
        }

        return $this->providerCode;
    }


    protected $providerCodeWire;

    protected function getProviderCodeWire()
    {
        if (!$this->providerCodeWire) {
            $this->providerCodeWire = file_get_contents(__DIR__.'/template/provider_wire');
        }

        return $this->providerCodeWire;
    }
}