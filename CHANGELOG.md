# Changelog
All notable changes to this project will be documented in this file.

The format follows [Semantic Versioning](https://semver.org/).

## [1.1.1] - 2025-11-17
### Fixed
- Corrected directory structure: all moved into `src/`.
- Updated documentation to reflect correct namespace and path structure.

### Added
- `ArrayThrottleStorage` for in-memory throttling within a single request (mainly for testing or fallback).

### Improved
- Cleaner package layout and consistent PSR-4 autoloading.
- Improved example documentation to match new structure.


## [1.1.0] - 2025-11-17
### Added
- **FileThrottleStorage** — new persistent throttle storage implementation using filesystem.
- **Support for nullable storage** in `SlackThrottle` (falls back to in-memory throttling if storage is null).
- Added comments and improved documentation across Slack components.

### Changed
- `SlackThrottle` now accepts `?ThrottleStorageInterface` and supports internal fallback mode.
- Cleaned up architecture to ensure SlackClient handles all logging internally.
- Removed unnecessary PrestaShop-specific dependencies from integration examples.
- Updated package structure to better follow PSR-4 and library best practices.

### Improved
- Increased package decoupling — no framework-specific code inside the package.
- Safer context handling in SlackMessage (immutable pattern).
- Better separation between module integration and package core logic.

---

## [1.0.0] - 2025-11-17
### Initial Release
- Slack webhook client with support for:
  - `info`, `warning`, `error` levels
  - formatted messages
  - context provider (domain/IP prefix)
  - throttling (in-memory)
  - PSR-3 logging
- Production-ready Slack notifier with full PSR-4 autoloading.
