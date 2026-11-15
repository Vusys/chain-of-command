# Chain of Command

An org chart with history: departments, people, roles and pay, viewable on any date, and as the company knew it on any date. It is a demo app built to dogfood the Vusys packages in one realistic place:

| Package | Used for |
| --- | --- |
| [laravel-nestedset](https://github.com/Vusys/laravel-nestedset) | The department tree |
| [laravel-bitemporal](https://github.com/Vusys/laravel-bitemporal) | Each person's department, manager, title and salary over time, with corrections that keep the old belief on record |
| [laravel-quantum-slipstream-drive](https://github.com/Vusys/laravel-quantum-slipstream-drive) | Serving repeated person lookups from memory; the page footer says how many |
| [laravel-smarty](https://github.com/Vusys/laravel-smarty) | Every view |
| [laravel-runabout](https://github.com/Vusys/laravel-runabout) | A shuffled staffing journey with invariants (no loops in the chain of command, nobody reporting to a leaver) |
| [tetryon](https://github.com/Vusys/tetryon) | Recorded browser tests |
| [zefram](https://github.com/Vusys/zefram) | Not yet wired in |

## Running it

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Open `/chart`, step through the months, and set "As we knew it on" to see what the company believed on a past date.

## Checks and tests

```bash
composer check          # Pint, Rector, Larastan level 9
vendor/bin/phpunit      # feature tests and the staffing journey
```

The browser suite runs against a separately served copy of the app:

```bash
composer browser:serve                    # in one terminal
TETRYON_SUITE_REPORT=1 composer browser   # in another; writes tests/Browser/Artifacts/suite-report
```
