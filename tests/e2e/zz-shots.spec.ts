import { test } from '@playwright/test';
import { login } from './helpers';

const out = 'C:/Users/Vince/AppData/Local/Temp/claude/D--ngoding-pwti-be-management-internal/453e6198-9d6d-4a9d-a718-e537019dcf3d/scratchpad/shots/';

test('shots', async ({ page }) => {
    await login(page);
    await page.setViewportSize({ width: 1440, height: 1000 });
    await page.goto('/admin/hmif-forms');
    await page.screenshot({ path: out + 'dash-light.png', fullPage: true });
    await page.evaluate(() => document.documentElement.classList.add('dark'));
    await page.screenshot({ path: out + 'dash-dark.png', fullPage: true });
    await page.evaluate(() => document.documentElement.classList.remove('dark'));

    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/admin/hmif-forms');
    console.log('overflow', await page.evaluate(() => [document.documentElement.scrollWidth, innerWidth]));
    await page.screenshot({ path: out + 'dash-mobile.png', fullPage: true });

    await page.setViewportSize({ width: 1280, height: 900 });
    await page.goto('/admin/qr-code-generator');
    await page.locator('#qr-url').fill('https://hmif.example/daftar');
    await page.locator('#qr-logo').setInputFiles('tests/e2e/fixtures/logo.png');
    await page.waitForTimeout(1200);
    await page.screenshot({ path: out + 'qr.png', fullPage: true });

    await page.goto('/admin/hmif-forms/responses');
    await page.locator('a[href*="/responses"]', { hasText: 'Pendaftaran E2E' }).first().click();
    await page.waitForTimeout(500);
    await page.screenshot({ path: out + 'responses.png', fullPage: true });
});
