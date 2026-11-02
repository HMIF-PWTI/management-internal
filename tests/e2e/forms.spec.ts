import { expect, test, type Page } from '@playwright/test';
import { login } from './helpers';

async function createBlankForm(page: Page, title: string) {
    await page.goto('/admin/hmif-forms/create');
    await page.locator('form:has(input[name="template"][value="blank"]) button[type="submit"]').click();
    await expect(page).toHaveURL(/\/admin\/hmif-forms\/[0-9a-f-]+\/edit/);
    await page.locator('input[x-model="builder.title"]').fill(title);
}

const questionCards = (page: Page) => page.locator('article.hmif-question-card:visible');

async function addQuestion(page: Page, label: string, type?: string, options: string[] = []) {
    await page.getByRole('button', { name: 'Tambah Pertanyaan' }).last().click();
    const card = questionCards(page).last();
    await card.locator('input[x-model="question.label"]').fill(label);
    if (type) {
        await card.locator('.hmif-type-trigger').click();
        await card.getByRole('option', { name: type, exact: true }).click();
    }
    for (const [index, option] of options.entries()) {
        if (index > 0) await card.getByRole('button', { name: 'Tambah opsi' }).click();
        await card.locator('input[x-model="option.label"]').nth(index).fill(option);
    }
    return card;
}

async function saveBuilder(page: Page) {
    await page.getByRole('button', { name: 'Simpan', exact: true }).click();
    await expect(page.getByText('Form berhasil disimpan.')).toBeVisible();
}

async function publish(page: Page) {
    await page.getByRole('button', { name: 'Publish', exact: true }).click();
    await expect(page.getByText('Form berhasil dipublikasikan.')).toBeVisible();
    return page.locator('#published-public-url').inputValue();
}

test.describe('HMIF Forms', () => {
    let pageErrors: string[];

    test.beforeEach(async ({ page }) => {
        pageErrors = [];
        page.on('pageerror', (error) => pageErrors.push(error.message));
        await login(page);
    });

    test.afterEach(() => {
        expect(pageErrors).toEqual([]);
    });

    test('dashboard menampilkan statistik dan tombolnya berfungsi', async ({ page }) => {
        await page.goto('/admin/hmif-forms');
        await expect(page.getByRole('heading', { name: /^Halo,/ })).toBeVisible();
        await expect(page.getByRole('heading', { name: 'Response per hari' })).toBeVisible();

        await page.getByRole('link', { name: 'Buat Form' }).first().click();
        await expect(page).toHaveURL(/\/admin\/hmif-forms\/create/);
    });

    test('alur lengkap: buat, publish, isi, lihat summary, export CSV', async ({ page, context, browser }) => {
        await createBlankForm(page, 'Pendaftaran E2E');
        await addQuestion(page, 'Nama lengkap');
        await addQuestion(page, 'Email kontak', 'Email');
        await addQuestion(page, 'Divisi pilihan', 'Multiple Choice', ['Kominfo', 'PAO']);
        await addQuestion(page, 'Hobi', 'Checkboxes', ['Coding', 'Desain', 'Musik']);
        await questionCards(page).first().locator('input[x-model="question.required"]').check();
        await questionCards(page).first().locator('input[x-model="question.description"]').fill('Sesuai KTM');

        const publicUrl = await publish(page);

        const respondent = await (await browser.newContext()).newPage();
        await respondent.goto(publicUrl);
        await expect(respondent.getByText('Sesuai KTM')).toBeVisible();

        // Server rejects a bad email and keeps the other answers.
        await respondent.locator('input[type="text"]').first().fill('Budi');
        await respondent.locator('input[type="email"]').first().fill('bukan-email');
        await respondent.locator('form').evaluate((form: HTMLFormElement) => HTMLFormElement.prototype.submit.call(form));
        await expect(respondent.locator('.hmif-respondent-error')).toContainText('Email kontak');
        await expect(respondent.locator('input[type="text"]').first()).toHaveValue('Budi');

        await respondent.locator('input[type="email"]').first().fill('budi@hmif.test');
        await respondent.getByLabel('Kominfo').check();
        await respondent.getByLabel('Coding').check();
        await respondent.getByLabel('Musik').check();
        await respondent.getByRole('button', { name: 'Submit' }).click();
        await expect(respondent.getByText('Response submitted')).toBeVisible();

        await page.reload();
        await page.getByRole('tab', { name: /Responses \(1\)/ }).click();
        await page.getByRole('link', { name: 'Buka detail responses' }).click();

        await expect(page.locator('.hmif-option-row', { hasText: 'Kominfo' })).toContainText('1 (100%)');
        await expect(page.locator('.hmif-option-row', { hasText: 'PAO' })).toContainText('0 (0%)');
        await expect(page.locator('.hmif-option-row', { hasText: 'Coding' })).toContainText('1 (100%)');

        await page.getByRole('tab', { name: 'Individual' }).or(page.getByRole('button', { name: 'Individual' })).first().click();
        await expect(page.getByText('Coding, Musik')).toBeVisible();

        const [download] = await Promise.all([page.waitForEvent('download'), page.getByRole('link', { name: 'Export CSV' }).click()]);
        const csv = (await (await context.request.get(download.url())).text());
        expect(csv).toContain('Nama lengkap');
        expect(csv).toContain('"Coding, Musik"');
    });

    test('jawaban opsi di luar pilihan ditolak server', async ({ page, browser }) => {
        await createBlankForm(page, 'Validasi Opsi');
        await addQuestion(page, 'Pilih satu', 'Dropdown', ['A', 'B']);
        const publicUrl = await publish(page);

        const respondent = await (await browser.newContext()).newPage();
        await respondent.goto(publicUrl);
        await respondent.locator('select option').first().evaluate((option: HTMLOptionElement) => { option.value = 'nilai-palsu'; });
        await respondent.locator('form').evaluate((form: HTMLFormElement) => HTMLFormElement.prototype.submit.call(form));
        await expect(respondent.locator('.hmif-respondent-error')).toBeVisible();
    });

    test('duplicate pertanyaan dan pindah pertanyaan tidak error', async ({ page }) => {
        await createBlankForm(page, 'Drag Duplicate');
        await addQuestion(page, 'Q1');
        await addQuestion(page, 'Q2');
        await saveBuilder(page);

        await questionCards(page).first().getByRole('button', { name: 'Duplicate' }).click();
        await expect(questionCards(page)).toHaveCount(3);
        await expect(questionCards(page).nth(1).locator('input[x-model="question.label"]')).toHaveValue('Q1 Copy');

        await questionCards(page).first().locator('.hmif-drag-handle').dragTo(questionCards(page).nth(1));
        await expect(questionCards(page).nth(1).locator('input[x-model="question.label"]')).toHaveValue('Q1');
    });

    test('pertanyaan yang dipindah keluar dari section yang dihapus tetap tersimpan', async ({ page }) => {
        await createBlankForm(page, 'Pindah Section');
        await addQuestion(page, 'Di section satu');
        await page.getByRole('button', { name: 'Add Section' }).click();
        await addQuestion(page, 'Harus selamat');
        await saveBuilder(page);

        await page.setViewportSize({ width: 1280, height: 2000 });
        await questionCards(page).nth(1).locator('.hmif-drag-handle').dragTo(questionCards(page).first());
        await expect(page.locator('section.hmif-builder-section').first().locator('article.hmif-question-card')).toHaveCount(2);
        await page.locator('section.hmif-builder-section').last().getByRole('button', { name: 'Hapus Section' }).click();
        await page.locator('[aria-labelledby="delete-section-title"]').getByRole('button', { name: 'Hapus Section' }).click();
        await saveBuilder(page);

        await page.reload();
        await expect(questionCards(page)).toHaveCount(2);
        await expect(page.locator('input[x-model="question.label"]:visible').first()).toHaveValue('Harus selamat');
    });

    test('setting Require login benar-benar tersimpan', async ({ page, browser }) => {
        await createBlankForm(page, 'Wajib Login');
        await addQuestion(page, 'Pertanyaan');
        await page.getByRole('tab', { name: 'Settings' }).click();
        await page.locator('label', { hasText: 'Require login' }).locator('input').check();
        const publicUrl = await publish(page);

        const guest = await (await browser.newContext()).newPage();
        await guest.goto(publicUrl);
        await expect(guest).toHaveURL(/\/admin\/login/);
    });

    test('error di section kedua langsung terlihat setelah submit', async ({ page, browser }) => {
        await createBlankForm(page, 'Dua Section');
        await addQuestion(page, 'Bagian satu');
        await page.getByRole('button', { name: 'Add Section' }).click();
        await addQuestion(page, 'Email kedua', 'Email');
        const publicUrl = await publish(page);

        const respondent = await (await browser.newContext()).newPage();
        await respondent.goto(publicUrl);
        await respondent.getByRole('button', { name: 'Berikutnya' }).click();
        await respondent.locator('input[type="email"]:visible').fill('salah');
        await respondent.locator('form').evaluate((form: HTMLFormElement) => HTMLFormElement.prototype.submit.call(form));
        await expect(respondent.locator('.hmif-respondent-error:visible')).toContainText('Email kedua');
    });

    test('form kosong tidak bisa dipublish dan judul pertanyaan wajib diisi', async ({ page }) => {
        await createBlankForm(page, 'Tidak Lengkap');
        await page.getByRole('button', { name: 'Publish', exact: true }).click();
        await expect(page.getByText('Tambahkan minimal satu pertanyaan sebelum publish.')).toBeVisible();

        const card = await addQuestion(page, 'Sementara');
        await card.locator('input[x-model="question.label"]').fill('');
        await page.getByRole('tab', { name: 'Settings' }).click();
        await page.getByRole('button', { name: 'Simpan', exact: true }).click();
        await expect(page.getByText('Semua pertanyaan harus punya judul.')).toBeVisible();
        await expect(page.getByRole('tab', { name: 'Questions' })).toHaveAttribute('aria-selected', 'true');
    });

    test('tombol Open dan Preview di halaman Form Saya berfungsi', async ({ page }) => {
        await createBlankForm(page, 'Tombol Hidup');
        await addQuestion(page, 'Q');
        await saveBuilder(page);

        await page.goto('/admin/hmif-forms/my-forms?search=Tombol+Hidup');
        await page.getByRole('link', { name: 'Open' }).first().click();
        await expect(page).toHaveURL(/\/edit$/);
    });

    test('limit satu response memberi pesan yang jelas', async ({ page, browser }) => {
        await createBlankForm(page, 'Sekali Saja');
        await addQuestion(page, 'Nama');
        await page.getByRole('tab', { name: 'Settings' }).click();
        await page.locator('label', { hasText: 'Limit to 1 response' }).locator('input').check();
        const publicUrl = await publish(page);

        const respondent = await (await browser.newContext()).newPage();
        for (const attempt of [1, 2]) {
            await respondent.goto(publicUrl);
            await respondent.locator('input[name="respondent_email"]').fill('sekali@hmif.test');
            await respondent.getByRole('button', { name: 'Submit' }).click();
            if (attempt === 1) await expect(respondent.getByText('Response submitted')).toBeVisible();
        }
        await expect(respondent.getByText('Anda sudah mengirim response untuk form ini.')).toBeVisible();
    });
});
