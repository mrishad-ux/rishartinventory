# OPENCODE_RULES.md
# FlavorDesk — OpenCode Session Rules
_Read this at the start of every session_

---

## Model Selection
- **Default model:** `qwen/qwen3.5-flash` — use for ALL routine tasks
- **Escalate to Plus/Sonnet only if:** Flash produces wrong code 2 times in a row
- **Never use:** Qwen3.5 Plus or Claude Sonnet for simple file edits

## Session Rules
1. **One feature per session** — never carry over unfinished work to a new session
2. **Read PROJECT_STATE.md first** — do not explore the project structure blindly
3. **Read only the files you need** — do not scan all files unless asked
4. **If you read the same file 3 times** — stop, summarise the problem, ask for guidance
5. **Do not retry a failed edit more than 2 times** — stop and report the error instead
6. **Never duplicate code blocks** — if a block already exists, edit it in place

## Before Editing Any File
- Read the file once
- Identify the exact lines to change
- Make the change in one operation
- Do not re-read the whole file to verify unless asked

## Context Budget Guide
| Session type | Expected tokens | Expected cost |
|-------------|----------------|---------------|
| Single file edit | < 20K | < $0.01 |
| Bug fix (2-3 files) | < 50K | < $0.01 |
| New feature (3-5 files) | < 100K | ~$0.02 |
| Large feature | < 200K | ~$0.04 |
| **Warning zone** | > 300K | > $0.05 — stop and review |

## Warning Signs — Stop and Report to User
- Same file read more than 3 times
- Context exceeds 200K tokens
- More than 5 failed edit attempts in a session
- Loop detected (reading → editing → re-reading → same error)

## Files Reference
- `PROJECT_STATE.md` — full project structure, models, routes, features
- `app/Http/Controllers/InventoryController.php` — main logic
- `app/Models/InventoryLog.php` — stock log model
- `resources/views/inventory/daily.blade.php` — daily entry UI

## Start of Session Checklist
- [ ] Read PROJECT_STATE.md
- [ ] Confirm which single feature to work on
- [ ] List only the files needed for that feature
- [ ] Confirm model is qwen3.5-flash
- [ ] Begin
