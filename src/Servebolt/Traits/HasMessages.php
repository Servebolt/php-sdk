<?php

namespace Servebolt\Sdk\Traits;

trait HasMessages
{

    private $messages = [];

    /**
     * @param array $messages
     */
    private function setMessages($messages) : void
    {
        $this->messages = $messages;
    }

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
     * @return null|string
     */
    public function getFirstMessageString()
    {
        if ($message = $this->getFirstMessage()) {
            return $message->message;
        }
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
