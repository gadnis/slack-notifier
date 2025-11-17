<?php
namespace Edbox\Slack;

class DefaultContextProvider implements ContextProviderInterface
{
    public function getPrefix(): array
    {
        $host = $_SERVER['HTTP_HOST'] ?? 'unknown-host';
        $ip = $_SERVER['SERVER_ADDR'] ?? gethostbyname($host);
        return [$host, $ip];
    }
}
