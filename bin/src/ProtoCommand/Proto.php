<?php


namespace GoProto\ProtoCommand;


use GoProto\Help\GoMod;
use GoProto\Proto\ProtoService;

class Proto
{
    public static function hasRoute(ProtoService $server): bool
    {
        if (!$server->getOptions('http.Route')) {
            return false;
        }
        foreach ($server->getRpcList() as $rpc) {
            foreach ($rpc->getOptions() as $type => $option) {
                if (in_array($type, [
                    'http.Get',
                    'http.Head',
                    'http.Post',
                    'http.Put',
                    'http.Delete',
                    'http.Options',
                ])) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function getModule(): string
    {
        return GoMod::getProjectPackage();
    }
}