<?php


namespace GoProto\Golang;


class ParserStruct
{
    private $startLine = 0;
    private $endLine = 0;
    private $name;
    private $doc;
    public $func = [];

    /**
     * @return int
     */
    public function getStartLine(): int
    {
        return $this->startLine;
    }

    /**
     * @param  int  $startLine
     */
    public function setStartLine(int $startLine): void
    {
        $this->startLine = $startLine;
    }

    /**
     * @return int
     */
    public function getEndLine(): int
    {
        return $this->endLine;
    }

    /**
     * @param  int  $endLine
     */
    public function setEndLine(int $endLine): void
    {
        $this->endLine = $endLine;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  mixed  $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * @param  mixed  $doc
     */
    public function setDoc($doc): void
    {
        $this->doc = $doc;
    }
}