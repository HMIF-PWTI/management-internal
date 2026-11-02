import { execFileSync } from 'node:child_process';
import { rmSync, writeFileSync } from 'node:fs';
import { e2eEnv } from '../../playwright.config';

export default function globalSetup() {
    rmSync(e2eEnv.DB_DATABASE, { force: true });
    writeFileSync(e2eEnv.DB_DATABASE, '');

    const artisan = (...args: string[]) =>
        execFileSync('php', ['artisan', ...args], { env: { ...process.env, ...e2eEnv }, stdio: 'pipe' });

    artisan('migrate', '--force');
    artisan('db:seed', '--class=E2eSeeder', '--force');
}
