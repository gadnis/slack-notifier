# Edbox Slack Notifier

A clean, PSR-4 compliant Slack Webhook notification library for PHP.

This package provides:

- 🔥 Easy Slack message sending  
- ⚠️ Message levels: `info`, `warning`, `error`  
- 🎨 Rich formatting (emoji + Slack code blocks)  
- 🌍 Context providers (domain/IP or custom)  
- 🛡 Throttling with pluggable storage  
- 🧩 Fully framework-agnostic (PrestaShop, Laravel, Symfony, CLI, cron)  
- 📦 Zero dependencies except `psr/log`

---

## 🚀 Installation

Install via Composer:

```bash
composer require edbox/slack-notifier
```

Or if using a local package:

```json
"repositories": [
  {
    "type": "path",
    "url": "slack_notifier_psr"
  }
]
```

---

## 🧩 Basic Usage

### 1. Implement a Webhook Provider

```php
use Edbox\Slack\WebhookProviderInterface;

class MyWebhookProvider implements WebhookProviderInterface {
    public function getWebhookUrl(): ?string {
        return 'https://hooks.slack.com/services/XXXX/YYYY/ZZZZ';
    }
}
```

### 2. Choose throttling storage

```php
use Edbox\Slack\ArrayThrottleStorage;

$storage = new ArrayThrottleStorage(); // In‑memory
```

### 3. Create SlackClient

```php
use Edbox\Slack\SlackClient;
use Edbox\Slack\SlackFormatter;
use Edbox\Slack\SlackThrottle;
use Edbox\Slack\DefaultContextProvider;

$slack = new SlackClient(
    new MyWebhookProvider(),
    $logger,                          // Any PSR‑3 logger
    new SlackFormatter(),
    new SlackThrottle(3600, $storage), // 1 hour throttle
    new DefaultContextProvider()
);
```

### 4. Send messages

```php
use Edbox\Slack\SlackMessage;

$slack->send(SlackMessage::info("Cache refreshed"));
$slack->send(SlackMessage::warning("API limit approaching"));
$slack->send(SlackMessage::error("Token expired"));
```

### 5. Add context

```php
$msg = SlackMessage::error("Feed parsing failed")
    ->withContext(['source' => 'instagram', 'id' => 18]);

$slack->send($msg);
```

Slack output:

```
🔥 [ERROR]
yourdomain.com (1.1.1.1) | Feed parsing failed
Context: {
    "source": "instagram",
    "id": 18
}
```

---

## 🌍 Custom Context Provider

```php
use Edbox\Slack\ContextProviderInterface;

class MyContextProvider implements ContextProviderInterface {
    public function getPrefix(): array {
        return ['billing', 'v2.1'];
    }
}
```

---

## 🛡 Throttling Explained

Throttling prevents Slack spam.

A message is uniquely identified by:

- Context prefix  
- Message level  
- Message text  

Configured via:

```php
new SlackThrottle(3600, $storage); // 1 hour
```

---

## 📜 License

Private — © 2024 EDBOX. All rights reserved.
