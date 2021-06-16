<?php


namespace GoProto\Golang;


class ParserImport
{
    private $package = '';
    private $alias = '';

    /**
     * @return string
     */
    public function getPackage(): string
    {
        return $this->package;
    }

    /**
     * @param  string  $package
     */
    public function setPackage(string $package): void
    {
        $this->package = $package;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param  string  $alias
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }
}