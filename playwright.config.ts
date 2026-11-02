import { defineConfig, devices } from '@playwright/test';
import path from 'node:path';

const port = 8123;

// Process env wins over .env, so the suite never touches the MySQL dev database.
export const e2eEnv = {
    APP_ENV: 'e2e',
    APP_DEBUG: 'true',
    APP_URL: `http://127.0.0.1:${port}`,
    QR_PUBLIC_URL: `http://127.0.0.1:${port}`,
    DB_CONNECTION: 'sqlite',
    DB_URL: '',
    DB_DATABASE: path.resolve('database/e2e.sqlite'),
    SESSION_DRIVER: 'file',
    CACHE_STORE: 'file',
    QUEUE_CONNECTION: 'sync',
    MAIL_MAILER: 'array',
};

export default defineConfig({
    testDir: 'tests/e2e',
    globalSetup: './tests/e2e/global-setup.ts',
    outputDir: 'test-results',
    // php artisan serve handles one request at a time on Windows.
    workers: 1,
    fullyParallel: false,
    timeout: 60_000,
    expect: { timeout: 10_000 },
    reporter: [['list']],
    use: {
        baseURL: `http://127.0.0.1:${port}`,
        trace: 'retain-on-failure',
        screenshot: 'only-on-failure',
    },
    projects: [{ name: 'chromium', use: { ...devices['Desktop Chrome'] } }],
    webServer: {
        command: `php artisan serve --host=127.0.0.1 --port=${port} --no-reload`,
        url: `http://127.0.0.1:${port}/admin/login`,
        reuseExistingServer: false,
        timeout: 120_000,
        env: e2eEnv,
    },
});
