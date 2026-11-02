import { expect, test, type Page } from '@playwright/test';
import jsQR from 'jsqr';
import { login } from './helpers';

const fixture = (name: string) => `tests/e2e/fixtures/${name}`;
const trackingUrl = /\/qr\/[0-9A-Z]{26}$/;

async function readCanvas(page: Page) {
    return page.locator('#qr-main-target canvas').evaluate((canvas: HTMLCanvasElement) => {
        const context = canvas.getContext('2d')!;
        const { data, width, height } = context.getImageData(0, 0, canvas.width, canvas.height);
        return { data: Array.from(data), width, height, center: Array.from(context.getImageData(width / 2, height / 2, 1, 1).data) };
    });
}

async function decodeCanvas(page: Page) {
    const image = await readCanvas(page);
    return jsQR(new Uint8ClampedArray(image.data), image.width, image.height)?.data;
}

async function decodeSavedImage(page: Page, src: string) {
    const image = await page.evaluate(async (url) => {
        const img = new Image();
        img.src = url;
        await img.decode();
        const canvas = document.createElement('canvas');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        const context = canvas.getContext('2d')!;
        context.drawImage(img, 0, 0);
        return { data: Array.from(context.getImageData(0, 0, canvas.width, canvas.height).data), width: canvas.width, height: canvas.height };
    }, src);
    return jsQR(new Uint8ClampedArray(image.data), image.width, image.height)?.data;
}

async function openGenerator(page: Page, link = 'https://hmif.example/daftar') {
    await page.goto('/admin/qr-code-generator');
    await page.locator('#qr-url').fill(link);
    await expect(page.locator('#qr-main-target canvas')).toBeVisible();
}

async function save(page: Page, button = 'SAVE QR CODE') {
    await page.getByRole('button', { name: button }).click();
    await expect(page).toHaveURL(/\/admin\/qr-codes/);
}

const newestRow = (page: Page) => page.locator('table tbody tr').first();

test.describe('QR Code generator', () => {
    let pageErrors: string[];

    test.beforeEach(async ({ page }) => {
        pageErrors = [];
        page.on('pageerror', (error) => pageErrors.push(error.message));
        await login(page);
    });

    test.afterEach(() => {
        expect(pageErrors).toEqual([]);
    });

    for (const [file, color] of [
        ['logo.png', [220, 38, 38]],
        ['logo.jpg', [37, 99, 235]],
        ['logo.webp', [37, 99, 235]],
        ['logo-no-size.svg', [124, 58, 237]],
        ['logo-big.png', [16, 185, 129]],
    ] as const) {
        test(`logo ${file} tampil di preview, bisa discan, dan tersimpan`, async ({ page }) => {
            await openGenerator(page);
            await page.locator('#qr-logo').setInputFiles(fixture(file));

            await expect.poll(async () => (await readCanvas(page)).center.slice(0, 3).every((value, index) => Math.abs(value - color[index]) < 12)).toBe(true);
            expect(await decodeCanvas(page)).toMatch(trackingUrl);

            await save(page);
            await expect(page.getByText('QR Code berhasil disimpan.')).toBeVisible();

            const src = await newestRow(page).locator('img').first().getAttribute('src');
            expect(src).toContain('/storage/qr-codes/');
            expect(await decodeSavedImage(page, src!)).toMatch(trackingUrl);
            await expect(newestRow(page).locator('img')).toHaveCount(2);
        });
    }

    test('file rusak ditolak dengan pesan jelas dan QR tetap utuh', async ({ page }) => {
        await openGenerator(page);
        await page.getByRole('button', { name: 'Logo', exact: true }).click();
        await page.locator('#qr-logo').setInputFiles(fixture('bukan-gambar.png'));

        await expect(page.getByText('bukan gambar yang bisa dibaca')).toBeVisible();
        expect(await decodeCanvas(page)).toMatch(trackingUrl);
    });

    test('logo preset ikut tersimpan', async ({ page }) => {
        await openGenerator(page);
        await page.getByRole('button', { name: 'Logo', exact: true }).click();
        await page.getByTitle('Preset Logo: Website').click();
        await expect(page.getByText('Logo terpasang di QR code')).toBeVisible();

        await save(page);
        await expect(newestRow(page).locator('img')).toHaveCount(2);
    });

    test('hapus logo mengembalikan QR tanpa logo', async ({ page }) => {
        await openGenerator(page);
        const before = (await readCanvas(page)).center;
        await page.locator('#qr-logo').setInputFiles(fixture('logo.png'));
        await expect.poll(async () => (await readCanvas(page)).center).not.toEqual(before);

        await page.getByRole('button', { name: 'Logo', exact: true }).click();
        await page.getByRole('button', { name: 'Hapus Logo' }).click();
        await expect.poll(async () => (await readCanvas(page)).center).toEqual(before);
    });

    test('tanpa scan tracking QR berisi link asli dan https dilengkapi', async ({ page }) => {
        await openGenerator(page, 'hmif.example/tanpa-skema');
        await page.getByRole('switch').click();
        await expect.poll(() => decodeCanvas(page)).toBe('https://hmif.example/tanpa-skema');

        await save(page);
        const src = await newestRow(page).locator('img').first().getAttribute('src');
        expect(await decodeSavedImage(page, src!)).toBe('https://hmif.example/tanpa-skema');
    });

    test('scan menambah statistik lalu redirect ke link tujuan', async ({ page, request }) => {
        await openGenerator(page, 'https://hmif.example/scan-test');
        // The generator remembers the previous QR's tracking choice.
        if ((await page.getByRole('switch').getAttribute('aria-checked')) !== 'true') await page.getByRole('switch').click();
        await expect.poll(() => decodeCanvas(page)).toMatch(trackingUrl);
        const scanned = (await decodeCanvas(page))!;
        await save(page);

        const response = await request.get(new URL(scanned).pathname, { maxRedirects: 0 });
        expect(response.status()).toBe(302);
        expect(response.headers().location).toBe('https://hmif.example/scan-test');

        await page.reload();
        await expect(newestRow(page)).toContainText('Indonesia');
    });

    test('edit mempertahankan URL tracking dan logo lama', async ({ page }) => {
        await openGenerator(page, 'https://hmif.example/sebelum-edit');
        await page.locator('#qr-logo').setInputFiles(fixture('logo.png'));
        await expect.poll(async () => (await readCanvas(page)).center[0]).toBeGreaterThan(200);
        const original = await decodeCanvas(page);
        await save(page);

        await newestRow(page).getByRole('link', { name: 'Edit', exact: true }).click();
        await expect(page.locator('#qr-url')).toHaveValue('https://hmif.example/sebelum-edit');
        await expect.poll(async () => (await readCanvas(page)).center[0]).toBeGreaterThan(200);
        expect(await decodeCanvas(page)).toBe(original);

        await page.locator('#qr-url').fill('https://hmif.example/sesudah-edit');
        await save(page, 'UPDATE QR CODE');
        await expect(newestRow(page)).toContainText('sesudah-edit');
        await expect(newestRow(page).locator('img')).toHaveCount(2);
    });

    test('download menghasilkan JPG', async ({ page }) => {
        await openGenerator(page);
        const [download] = await Promise.all([page.waitForEvent('download'), page.getByRole('button', { name: /DOWNLOAD JPG/ }).click()]);
        expect(download.suggestedFilename()).toBe('qr-code.jpg');
    });

    test('anggota tanpa fitur QR Code tidak bisa membuka generator', async ({ page, context }) => {
        await context.clearCookies();
        await login(page, 'anggota@hmif.test');
        expect((await page.goto('/admin/qr-code-generator'))?.status()).toBe(403);
    });
});
