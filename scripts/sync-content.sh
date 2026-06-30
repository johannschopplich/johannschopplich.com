#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

if [[ -f "$ROOT_DIR/.env" ]]; then
  set -a
  # shellcheck disable=SC1091
  source "$ROOT_DIR/.env"
  set +a
fi

: "${SYNC_SERVER:?SYNC_SERVER not set in .env}"
: "${SYNC_REMOTE_DIR:?SYNC_REMOTE_DIR not set in .env}"

LOCAL_CONTENT="$ROOT_DIR/storage/content/"
REMOTE_CONTENT="${SYNC_SERVER}:${SYNC_REMOTE_DIR}/storage/content/"

RSYNC_FLAGS=(-az --delete-after --max-delete=100 --itemize-changes
  --exclude='.DS_Store'
  --exclude='*.lock')

usage() {
  echo "Usage: $0 (--upload | --download) [--apply] [--exclude=PATTERN]" >&2
  exit 1
}

DIRECTION=""
APPLY=false
for arg in "$@"; do
  case "$arg" in
    --upload|--download)
      [[ -n "$DIRECTION" ]] && { echo "Pick exactly one of --upload / --download." >&2; usage; }
      DIRECTION="${arg#--}" ;;
    --apply) APPLY=true ;;
    --exclude=*) RSYNC_FLAGS+=("$arg") ;;
    *) echo "Unknown argument: $arg" >&2; usage ;;
  esac
done

[[ -z "$DIRECTION" ]] && usage

if [[ "$DIRECTION" == "upload" ]]; then
  SRC="$LOCAL_CONTENT"
  DEST="$REMOTE_CONTENT"
else
  SRC="$REMOTE_CONTENT"
  DEST="$LOCAL_CONTENT"
fi

if ! $APPLY; then
  echo "DRY-RUN ${DIRECTION} (no changes). Re-run with --apply to sync."
  rsync "${RSYNC_FLAGS[@]}" --dry-run "$SRC" "$DEST"
  exit 0
fi

echo "Syncing content (${DIRECTION})..."
rsync "${RSYNC_FLAGS[@]}" "$SRC" "$DEST"

if [[ "$DIRECTION" == "upload" ]]; then
  echo "Flushing the Kirby pages cache..."
  ssh "$SYNC_SERVER" "cd '${SYNC_REMOTE_DIR}' && rm -rf storage/cache/*/pages storage/cache/pages"
fi

echo "Done."
