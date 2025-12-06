# Contributing

Contributions are welcome. This project accepts pull requests on [GitHub][].

This project adheres to a [code of conduct](CODE_OF_CONDUCT.md). By
participating in this project and its community, you are expected to uphold this
code.

## Communication Channels

You can find help and discussion in the following places:

-   GitHub Issues: <https://github.com/csoellinger/php-fon-webservices/issues>

## Reporting Bugs

Report bugs using the project's [issue tracker][issues].

⚠️ _**ATTENTION!!!** DO NOT include passwords or other sensitive information in
your bug report._

When submitting a bug report, please include enough information to reproduce the
bug. A good bug report includes the following sections:

-   **Description**

    Provide a short and clear description of the bug.

-   **Steps to reproduce**

    Provide steps to reproduce the behavior you are experiencing. Please try to
    keep this as short as possible. If able, create a reproducible script outside
    of any framework you are using. This will help us to quickly debug the issue.

-   **Expected behavior**

    Provide a short and clear description of what you expect to happen.

-   **Screenshots or output**

    If applicable, add screenshots or program output to help explain your problem.

-   **Environment details**

    Provide details about the system where you're using this package, such as PHP
    version and operating system.

-   **Additional context**

    Provide any additional context that may help us debug the problem.

## Fixing Bugs

This project welcomes pull requests to fix bugs!

If you see a bug report that you'd like to fix, please feel free to do so.
Following the directions and guidelines described in the "Adding New Features"
section below, you may create bugfix branches and send pull requests.

## Adding New Features

If you have an idea for a new feature, it's a good idea to check out the
[issues][] or active [pull requests][] first to see if anyone is already working
on the feature. If not, feel free to submit an issue first, asking whether the
feature is beneficial to the project. This will save you from doing a lot of
development work only to have your feature rejected. We don't enjoy rejecting
your hard work, but some features don't fit with the goals of the project.

When you do begin working on your feature, here are some guidelines to consider:

-   Your pull request description should clearly detail the changes you have made.
    We will use this description to update the CHANGELOG. If there is no
    description, or it does not adequately describe your feature, we may ask you
    to update the description.
-   csoellinger/php-fon-webservices follows **[PSR-12 coding standard][psr-12]**.
    Please ensure your code does, too. _Hint: run `composer format:check` to check._
-   Please **write tests** for any new features you add using Pest.
-   Please **ensure that tests pass** before submitting your pull request.
    csoellinger/php-fon-webservices automatically runs tests for pull requests. However,
    running the tests locally will help save time. _Hint: run `composer test`._
-   **Use topic/feature branches.** Please do not ask to pull from your main branch.
    -   For more information, see "[Understanding the GitHub flow][gh-flow]."
-   **Submit one feature per pull request.** If you have multiple features you
    wish to submit, please break them into separate pull requests.
-   **Write clear commit messages.** While we don't enforce a specific format,
    clear and descriptive commit messages help reviewers understand your changes.

## Developing

To develop this project, you will need [PHP](https://www.php.net) 8.1 or greater
and [Composer](https://getcomposer.org).

After cloning this repository locally, execute the following commands:

```bash
cd /path/to/repository
composer install
```

Now, you are ready to develop!

### Tooling

This project uses modern PHP development tools:

- **[Pest](https://pestphp.com/)** - Modern testing framework
- **[PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)** - Code formatting
- **[PHPStan](https://github.com/phpstan/phpstan)** - Static analysis (max level)
- **[Psalm](https://github.com/vimeo/psalm)** - Static analysis (level 1) + security taint analysis
- **[Rector](https://github.com/rectorphp/rector)** - Automated refactoring

### Available Commands

Quality checks:
```bash
composer lint              # Check PHP syntax
composer format            # Auto-fix code style
composer format:check      # Check code style (for CI)
composer analyse           # Run both PHPStan and Psalm
composer analyse:phpstan   # Run PHPStan only
composer analyse:psalm     # Run Psalm only
composer check             # Run all quality checks
```

Testing:
```bash
composer test              # Run Pest tests
composer test:coverage     # Run tests with coverage report
```

Refactoring:
```bash
composer rector            # Preview refactoring suggestions
composer rector:fix        # Apply automated refactorings
```

### Coding Standards

This project follows [PSR-12](https://www.php-fig.org/psr/psr-12/)
coding standards, enforced by [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).

Format your code:

```bash
# Auto-fix code style
composer format

# Check without fixing (used in CI)
composer format:check
```

### Static Analysis

This project uses both [PHPStan](https://github.com/phpstan/phpstan) (max level)
and [Psalm](https://github.com/vimeo/psalm) (level 1) to provide comprehensive static analysis.

Run static analysis:

```bash
# Run both tools
composer analyse

# Run individually
composer analyse:phpstan
composer analyse:psalm
```

### Project Structure

This project uses [pds/skeleton](https://github.com/php-pds/skeleton) as its
base folder structure and layout.

### Running Tests

This project uses [Pest](https://pestphp.com/) for testing. All tests must pass
before we will accept a pull request. Be sure to run `composer install` first.

Run tests:

```bash
# Run all tests
composer test

# Run with coverage report
composer test:coverage

# Run all quality checks + tests
composer check && composer test
```

**Note:** GitHub Actions CI will automatically run all quality checks and tests
when you push to the remote repository.

### Multi-Version Testing

Test across all supported PHP versions using Podman Compose:

```bash
# Test specific PHP version
podman-compose run --rm php81
podman-compose run --rm php82

# Test all versions (8.1, 8.2, 8.3, 8.4)
bash bin/test-all-versions.sh
```

### Writing Tests

We use Pest's expressive syntax. See the [Pest documentation](https://pestphp.com/docs) for details.

Example test:

```php
test('session can login and logout', function () {
    $session = new SessionWs($this->fonCredential);

    $session->login();
    expect($session->getID())->not->toBeEmpty();

    $session->logout();
    expect($session->isLoggedIn())->toBeFalse();
});
```

[github]: https://github.com/csoellinger/php-fon-webservices
[issues]: https://github.com/csoellinger/php-fon-webservices/issues
[pull requests]: https://github.com/csoellinger/php-fon-webservices/pulls
[psr-12]: https://www.php-fig.org/psr/psr-12/
[gh-flow]: https://guides.github.com/introduction/flow/
