<?php


namespace GoProto\Help;


class File
{
    public static function insert(string $file, string $insertStr, int $iLine, int $index = -1)
    {
        $arrInsert = self::insertContent($file, $insertStr, $iLine, $index);
        $str       = implode("", $arrInsert);

        file_put_contents($file, $str);
    }

    /**
     * @param  string  $source
     * @param $s
     * @param $iLine
     * @param $index
     * @return array
     */
    private static function insertContent($source, $s, $iLine, $index): array
    {
        $file_handle = fopen($source, "r");
        $i           = 0;
        $arr         = array();
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            ++$i;
            if ($i == $iLine) {
                $checkIndex = $index;
                if ($index == -1) {
                    $checkIndex = strlen($line) + 1;
                }

                if ($checkIndex == strlen($line)) {
                    $arr[] = substr($line, 0, strlen($line) - 1).$s;
                } else {
                    $arr[] = substr($line, 0, $checkIndex).$s.substr($line, $checkIndex);
                }

            } else {
                $arr[] = $line;
            }
        }

        fclose($file_handle);
        return $arr;
    }
}