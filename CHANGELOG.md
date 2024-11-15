# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [5.2.0] - 2024-11-15

### Added
- Adding exception trace in `OpenTelemetry` format during array serialization.

### Changed

- Exception messages have been corrected. Additional tests have been added.

### Removed

- Remove class Serialization to array trait.

## [5.1.0] - 2024-10-27

### Added

- Added interface `Throwable` to `BaseExceptionInterface`.
- Class `Error` was extended from `ErrorException`.
- Added `MonologProcessor` for Monolog.

### Fixed

- Fixed the PHPDoc type hints for `PHPStan`.
- Fixed the `ResourceException` class and sibling classes.

### Changed

- Rename method `BaseExceptionInterface::template()` to `BaseExceptionInterface::getTemplate()`.
- Update documentation (English and Russian).
- Exceptions are no longer added to the `exception registry`. 
This functionality is disabled by default.

### Removed

