
from datetime import date
from pathlib import Path
import os
import sys

work = os.environ.get("WORK_DONE", "").strip()

if not work:
    print("No work description provided.")
    sys.exit(1)

log_file = Path("DEVELOPMENT_LOG.md")
today = date.today().isoformat()

entry = f"\n## {today}\n\n- {work}\n"

with log_file.open("a", encoding="utf-8") as file:
    file.write(entry)

print(f"Recorded development progress for {today}.")
