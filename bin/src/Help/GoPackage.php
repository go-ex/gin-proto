<?php


namespace GoProto\Help;


class GoPackage
{
    public static function toFile(string $package):string
    {
        $projectPath    = GoMod::getProjectPath();
        $projectPackage = GoMod::getProjectPackage();

        return str_replace($projectPackage, $projectPath, $package);
    }
}