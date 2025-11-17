<?php
namespace Edbox\Slack;

use Psr\Log\LoggerInterface;

class SlackClient implements SlackNotifierInterface
{
    private WebhookProviderInterface $webhookProvider;
    private LoggerInterface $logger;
    private SlackFormatter $formatter;
    private SlackThrottle $throttle;
    private ContextProviderInterface $contextProvider;

    public function __construct(
        WebhookProviderInterface $provider,
        LoggerInterface $logger,
        SlackFormatter $formatter,
        SlackThrottle $throttle,
        ContextProviderInterface $contextProvider
    ) {
        $this->webhookProvider = $provider;
        $this->logger = $logger;
        $this->formatter = $formatter;
        $this->throttle = $throttle;
        $this->contextProvider = $contextProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function info(string $message, array $context = []): void
    {
        $this->send(SlackMessage::info($message)->withContext($context));
    }

    /**
     * {@inheritdoc}
     */
    public function warning(string $message, array $context = []): void
    {
        $this->send(SlackMessage::warning($message)->withContext($context));
    }

    /**
     * {@inheritdoc}
     */
    public function error(string $message, array $context = []): void
    {
        $this->send(SlackMessage::error($message)->withContext($context));
    }

    /**
     * {@inheritdoc}
     */
    public function notify(string $level, string $message, array $context = []): void
    {
        $level = strtolower($level);

        $allowed = ['info', 'warning', 'error'];

        if (!in_array($level, $allowed, true)) {
            $level = 'error';
        }

        $this->{$level}($message, $context);
    }

    /**
     * @param SlackMessage $msg
     * @return void
     */
    private function send(SlackMessage $msg): void
    {
        $url = $this->webhookProvider->getWebhookUrl();
        if (!$url) {
            $this->logger->warning('Slack webhook URL missing.');
            return;
        }

        $prefix = $this->contextProvider->getPrefix();
        $key = md5(implode('|', $prefix) . '|' . $msg->getLevel() . '|' . $msg->getMessage());

        if ($this->throttle->shouldBlock($key)) {
            return;
        }

        $text = $this->formatter->format($msg, $prefix);
        $payload = json_encode(['text' => $text], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error || $status < 200 || $status >= 300) {
            $this->logger->error('Slack error', ['error'=>$error,'status'=>$status,'response'=>$response]);
        }
    }
}
