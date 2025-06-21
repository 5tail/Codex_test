# AGENTS.md

This file records a summary of repository changes and a short description of each Pull Request.

## Pull Request Summaries

### PR: Convert timer page to PHP
- Renamed the static `index.html` to `index.php` and added all timer and scramble functionality.
- Implemented SpeedStacks-style timer logic with hold detection, +2/DNF penalties, and an optional one-hour mode.
- Added scramble generation for 2x2, 3x3, and 4x4 puzzles and a toggle to hide the running time during solves.


### PR: Create readme
- Added an empty `readme` placeholder file.

### PR: Add AGENTS summary
- Created `AGENTS.md` to track pull request descriptions and change history.
- Included initial summary for converting the timer to PHP and creating the placeholder readme.

### PR: Document timer usage
- Replaced the placeholder readme with a short overview of `index.php`, local server instructions, and note that all code lives in one file.

### PR: Use cubejs scrambles
- Loaded cubejs from a CDN in `index.php`.
- Implemented `generateScramble()` using cubejs for 2x2, 3x3, and 4x4 events.

