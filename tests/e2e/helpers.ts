import { expect, type Page } from '@playwright/test';

export async function login(page: Page, email = 'admin@hmif.test') {
    await page.goto('/admin/login');
    await page.getByLabel(/email/i).fill(email);
    await page.getByLabel(/password/i).first().fill('e2e-password');
    await page.getByRole('button', { name: /sign in|masuk/i }).click();
    await expect(page).not.toHaveURL(/login/);
}
