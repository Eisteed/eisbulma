import 'dotenv/config';
import { spawnSync } from 'node:child_process';

const REMOTE = process.env.REMOTE;

if (!REMOTE) {
  console.error('[Eisbulma] ❌ REMOTE is not set in .env');
  process.exit(1);
}

const args = [
  'sync',
  './',
  REMOTE,
  '--exclude', 'node_modules/**',
  '--exclude', '.vscode/**',
  '--exclude', '.git/**',
  '--delete-excluded',
  '--progress',
];

console.log('[Eisbulma] Running: rclone sync');

const res = spawnSync('rclone', args, {
  stdio: 'inherit',
  // shell: false par défaut → plus de warning
});

process.exit(res.status ?? 0);
