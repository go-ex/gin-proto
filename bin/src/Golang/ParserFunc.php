<?php


namespace GoProto\Golang;


class ParserFunc
{
    protected string $name = '';
    protected $startLine = 0;
    protected $endLine = 0;
    protected string $structName = '';
    protected $doc = '';
    protected $params = [];
    protected $returnStr = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

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
     * @return string
     */
    public function getDoc(): string
    {
        return $this->doc;
    }

    /**
     * @param  string  $doc
     */
    public function setDoc(string $doc): void
    {
        $this->doc = $doc;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param  array  $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }


    /**
     * @return string
     */
    public function getStructName(): string
    {
        return $this->structName;
    }

    /**
     * @param  string  $structName
     */
    public function setStructName(string $structName): void
    {
        $this->structName = $structName;
    }

    /**
     * @return string
     */
    public function getReturnStr(): string
    {
        return $this->returnStr;
    }

    /**
     * @param  string  $returnStr
     */
    public function setReturnStr(string $returnStr): void
    {
        $this->returnStr = $returnStr;
    }

}