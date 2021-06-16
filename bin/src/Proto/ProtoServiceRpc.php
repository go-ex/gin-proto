<?php


namespace GoProto\Proto;


class ProtoServiceRpc
{
    public $doc = '';
    public $name;
    public $option = [];
    public $params = [];

    public function getOptions($key = null)
    {
        if ($key) {
            return $this->option[$key] ?? '';
        }
        return $this->option;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDoc(): string
    {
        return $this->doc;
    }
}