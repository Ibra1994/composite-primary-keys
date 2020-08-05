<?php

namespace MaksimM\CompositePrimaryKeys\Http\Traits;

trait OptionalBinaryTransformation
{
    protected $hexBinaryColumns = false;

    private $shouldMutate = true;

    public function hexBinaryColumns()
    {
        return $this->hexBinaryColumns;
    }

    public function disableBinaryMutators()
    {
        $this->shouldMutate = false;
    }

    public function enableBinaryMutators()
    {
        $this->shouldMutate = true;
    }

    protected function isAllowedToMutateBinaryAttributes()
    {
        return $this->shouldMutate;
    }

    protected function shouldProcessBinaryAttribute($key)
    {
        return $this->hexBinaryColumns() && in_array($key, $this->getBinaryColumns());
    }

    public function hasGetMutator($key)
    {
        if ($this->shouldProcessBinaryAttribute($key) && isset($this->{$key}) && $this->isAllowedToMutateBinaryAttributes()) {
            return true;
        }

        return parent::hasGetMutator($key);
    }

    public function mutateAttribute($key, $value)
    {
        if ($this->shouldProcessBinaryAttribute($key)) {
            return strtoupper(bin2hex($value));
        }

        return parent::mutateAttribute($key, $value);
    }

    public function hasSetMutator($key)
    {
        if ($this->shouldProcessBinaryAttribute($key) && $this->isAllowedToMutateBinaryAttributes()) {
            return true;
        }

        return parent::hasSetMutator($key);
    }

    public function setMutatedAttributeValue($key, $value)
    {
        if ($this->shouldProcessBinaryAttribute($key)) {
            $value = hex2bin($value);
            $this->attributes[$key] = $value;

            return $value;
        }

        return parent::setMutatedAttributeValue($key, $value);
    }
}
