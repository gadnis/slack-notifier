<?php
namespace Edbox\Slack;

interface ContextProviderInterface {
    public function getPrefix(): array;
}
