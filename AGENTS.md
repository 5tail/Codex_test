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


### PR: Refactor timer script
- Extracted all timer logic into `timer.js` as an ES module.
- Imported `generateScramble()` and `startInspection()` in `index.php` to initialize the page.

### PR: Support more scrambles
- Extended `generateScramble()` in `timer.js` to cover 5x5–7x7 and non-cube puzzles.
- Updated the readme to note cubejs provides scrambles for common WCA events.

### PR: Add time mode selector
- Replaced the one-hour checkbox with a dropdown to choose 10 minutes, 1 hour, or infinite time.
- Updated `formatTime()` in `timer.js` to respect the selected limit.

### PR: Improve inspection countdown
- Display the remaining time during inspection, updating every 100 ms.
- Highlight 8–12 seconds in yellow and 12–15 seconds in red.
- Show “DNF” after 17 seconds and reset the countdown whenever a new scramble is generated.
