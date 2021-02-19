<?php

namespace Servebolt\Sdk\Traits;

trait HasMessages
{

    private array $messages = [];

    public function hasMessages() : bool
    {
        return count($this->messages) > 0;
    }

    public function hasSingleMessage() : bool
    {
        return $this->hasMessages() && count($this->getMessages()) === 1;
    }

    public function getMessages() : array
    {
        return $this->messages;
    }

    /**
     * @return null|object
     */
    public function getFirstMessage()
    {
        if ($this->hasMessages()) {
            return current($this->getMessages());
        }
    }
}
