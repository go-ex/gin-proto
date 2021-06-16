<?php


namespace GoProto\Proto;


class ProtoService
{
    public $name;
    public $rpc = [];
    public $option = [];

    /**
     * @return ProtoServiceRpc[]
     */
    public function getRpcList(): array
    {
        return $this->rpc;
    }

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
}