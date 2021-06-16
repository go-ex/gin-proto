<?php


namespace GoProto\Help;


class DirsHelp
{
    public static function getDirs(string $path, string $ext = null): array
    {
        $arr = [];
        if (is_dir($path)) {
            $dir = scandir($path);
            foreach ($dir as $value) {
                $sub_path = $path.'/'.$value;
                if ($value == '.' || $value == '..') {
                    continue;
                } else {
                    if (is_dir($sub_path)) {
                        $arr = array_merge($arr, self::getDirs($sub_path));
                    } else {
                        //.$path 可以省略，直接输出文件名
                        if ($ext === null || strpos($value, $ext) != false) {
                            $arr[] = $path.'/'.$value;
                        }
                    }
                }
            }
        }
        return $arr;
    }
}