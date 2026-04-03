# MonkeyStats — Claude Context

## Project Overview

MonkeyStats is a PHP MVC web application that displays typing statistics from [Monkeytype](https://monkeytype.com). It fetches user profiles and global leaderboards from the Monkeytype API, caches them in a local MySQL database, and renders them as HTML.

Originally built as a French educational project (SAE 203).

## Architecture

**Pattern:** Classic MVC, no framework — pure PHP.

**Request flow:**
```
index.php (front controller, ?action routing)
  → Controller (instantiates Model, selects View)
  → Model (DB cache check → API call → DB write)
  → View (HTML output with htmlspecialchars escaping)
```

**Routes (GET params):**
| URL | Controller::method | Description |
|---|---|---|
| `/?action=home` (default) | `UserController::home()` | Search form |
| `/?action=search&username=X` | `UserController::search()` | User profile |
| `/?action=leaderboard&time=15\|60` | `LeaderboardController::showLeaderboard()` | Top 50 |

## File Structure

```
index.php                  # Front controller + router
config/database.php        # DB credentials + MONKEYTYPE_APE_KEY constant (gitignored)
database.sql               # Schema (run once to initialize)
controller/
  UserController.php
  LeaderboardController.php
model/
  UserModel.php            # API fetch + 10-min DB cache logic
  LeaderboardModel.php
view/
  homeView.php
  userStatsView.php
  leaderboardView.php
css/
  home.css
  user.css
  leaderboard.css
```

## Database

Two tables, populated and cached from the Monkeytype API.

**Users** (PK: `name`)
- `name`, `tests_completed`, `time_typing` (seconds), `wpm_15`, `acc_15`, `wpm_60`, `acc_60`, `xp`, `level`, `discord_name`, `discord_avatar`, `badge_id`, `last_updated`
- Cache TTL: 600 seconds — if `last_updated` is older than 10 min, re-fetch from API.
- `level` is calculated dynamically: `max(1, floor(sqrt(xp) / 5))`

**Leaderboard**
- `id`, `name`, `wpm`, `acc`, `rank_pos`, `mode` (15 or 60)
- Populated once per mode; no TTL (manual refresh only).

## External API

Base URL: `https://api.monkeytype.com`

| Endpoint | Auth | Purpose |
|---|---|---|
| `GET /users/{username}/profile` | `ApeKey` header | User stats & personal bests |
| `GET /leaderboards?language=english&mode=time&mode2={15\|60}` | None | Top 50 leaderboard |

The API key is stored as `MONKEYTYPE_APE_KEY` constant in `config/database.php` (gitignored). Requests use `file_get_contents()` with a stream context.

## Configuration

`config/database.php` is **gitignored** and must be created locally. It defines:
- PDO connection (host, dbname, user, password, charset utf8mb4)
- `MONKEYTYPE_APE_KEY` constant

## Dev Setup

```bash
# 1. Clone
git clone https://github.com/di0xus/monkeystats.git && cd monkeystats

# 2. Create DB
mysql -u root -p < database.sql

# 3. Create config/database.php with credentials + API key

# 4. Serve
php -S localhost:8000
```

Requirements: PHP 8.0+ with `pdo_mysql`, MySQL/MariaDB.

## Security Notes

- All DB queries use PDO prepared statements — no raw interpolation.
- All output uses `htmlspecialchars()` — no raw echoing of user/API data.
- `config/database.php` is gitignored to keep credentials out of version control.
- No input sanitization beyond escaping is needed since the only user input is a username passed to a prepared statement and URL-encoded for API calls.

## No Build Step, No Tests

- No npm, no Composer, no bundler — just PHP files served directly.
- No test suite. Manual testing only.
- No CI/CD pipeline.

## Language

UI is mixed French/English: homepage and nav labels are in French; user profile and leaderboard data labels are in English.
