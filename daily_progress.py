
from datetime import date
from pathlib import Path
import subprocess

today = date.today().isoformat()
log_file = Path("DEVELOPMENT_LOG.md")

work = input("What did you actually work on today? ").strip()

if not work:
    print("No progress recorded. Nothing committed.")
    raise SystemExit(0)

entry = f"\n## {today}\n\n- {work}\n"

with log_file.open("a", encoding="utf-8") as file:
    file.write(entry)

subprocess.run(["git", "add", str(log_file)], check=True)

subprocess.run(
    ["git", "commit", "-m", f"docs: record development progress {today}"],
    check=True
)

print("Progress recorded locally.")
print("Review and push your commit when ready.")
