<?php


namespace GoProto\Help;


class GoMod
{
    /**
     * 项目package
     * @return string
     */
    public static function getProjectPackage():string
    {
        $fileContextArr = file(self::getProjectPath()."/go.mod", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        for ($i = 0; $i < count($fileContextArr);) {
            $str    = trim($fileContextArr[$i]);
            $strArr = explode(' ', $str);
            $check  = reset($strArr);

            if ($check == 'module') {
                return end($strArr);
            }
        }
        return '__not_find_go_mod__';
    }

    /**
     * 项目根目录
     * @return string
     */
    public static function getProjectPath():string
    {
        return project_path;
    }
}