<?php
namespace Edbox\Slack;

interface WebhookProviderInterface {
    public function getWebhookUrl(): ?string;
}
