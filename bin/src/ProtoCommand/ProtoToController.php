<?php


namespace GoProto\ProtoCommand;


use GoProto\Proto\GenCode;
use GoProto\Help\DirsHelp;
use GoProto\Help\Str;
use GoProto\Proto\ProtoParser;
use GoProto\Proto\ProtoService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProtoToController
{
    public function run(InputInterface $input, OutputInterface $output)
    {
        $protoPath = $input->getOption('proto_path');
        $outHttp   = $input->getOption('out_http');

        $output->writeln("搜索目录 {$protoPath} 下的proto文件");

        $myRoute = new MyRouteGen($outHttp);
        foreach (DirsHelp::getDirs($protoPath, '.proto') as $file) {
            $parser = new ProtoParser($file);

            foreach ($parser->getServices() as $server) {
                (new MyControllerGen($outHttp))->gen($parser, $server);
            }

            $myRoute->scan($parser);
        }
        $myRoute->makeFile();
    }
}