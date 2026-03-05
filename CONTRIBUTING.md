# Contributing to Yandex SpeechKit PHP SDK

Thank you for your interest in contributing to the Yandex SpeechKit PHP SDK! This document provides guidelines and instructions for contributing.

## Code of Conduct

By participating in this project, you agree to maintain a respectful and collaborative environment.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue on GitHub with:

- A clear, descriptive title
- Steps to reproduce the issue
- Expected behavior
- Actual behavior
- Your environment (PHP version, Laravel version if applicable, OS)
- Code samples or error messages

### Suggesting Enhancements

Enhancement suggestions are welcome! Please create an issue with:

- A clear, descriptive title
- Detailed description of the proposed feature
- Use cases and benefits
- Any relevant examples or mockups

### Pull Requests

1. **Fork the repository** and create your branch from `main`:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Make your changes**:
   - Follow PSR-12 coding standards
   - Add tests for new functionality
   - Update documentation as needed
   - Ensure all tests pass

4. **Run tests**:
   ```bash
   vendor/bin/phpunit
   ```

5. **Commit your changes**:
   - Use clear, descriptive commit messages
   - Reference any related issues

6. **Push to your fork**:
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Create a Pull Request**:
   - Provide a clear description of the changes
   - Reference any related issues
   - Ensure CI checks pass

## Development Guidelines

### Coding Standards

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding style
- Use PHP 8.0+ features (constructor property promotion, named arguments, etc.)
- Write clear, self-documenting code
- Add PHPDoc blocks for all public methods

### Testing

- Write unit tests for all new functionality
- Maintain or improve code coverage
- Use Mockery for mocking dependencies
- Test both success and failure scenarios

### Documentation

- Update README.md for user-facing changes
- Update README-ru.md (Russian translation)
- Add inline code comments for complex logic
- Update PHPDoc blocks when changing method signatures

### Commit Messages

Use clear, descriptive commit messages:

```
Add speaker labeling support

- Implement SpeakerLabeling model
- Add speaker labeling to RecognitionRequest
- Update tests and documentation
```

## Project Structure

```
yandex-speechkit-php/
├── config/               # Publishable Laravel config
├── src/
│   ├── Exceptions/       # Custom exception classes
│   ├── Laravel/          # Laravel integration
│   ├── Models/           # DTO/Model classes
│   ├── config/           # Internal config
│   └── YandexSpeechKitClient.php
├── tests/
│   ├── Unit/             # Unit tests
│   └── Feature/          # Feature tests
├── composer.json
├── phpunit.xml
└── README.md
```

## Testing Your Changes

### Unit Tests

```bash
vendor/bin/phpunit --testsuite Unit
```

### Feature Tests

```bash
vendor/bin/phpunit --testsuite Feature
```

### All Tests with Coverage

```bash
vendor/bin/phpunit --coverage-html coverage
```

## Release Process

Releases are managed by the maintainer. Version numbers follow [Semantic Versioning](https://semver.org/):

- **MAJOR** version for incompatible API changes
- **MINOR** version for new functionality in a backward-compatible manner
- **PATCH** version for backward-compatible bug fixes

## Questions?

If you have questions about contributing, feel free to:

- Open an issue on GitHub
- Contact the maintainer: sovletig@yandex.ru

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

## Recognition

Contributors will be recognized in the project documentation. Thank you for helping improve the Yandex SpeechKit PHP SDK!
