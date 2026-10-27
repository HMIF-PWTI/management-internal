<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\RfidCard;
use Illuminate\Database\Seeder;

class RfidCardSeeder extends Seeder
{
    public function run(): void
    {
        $divisiMap = Divisi::all()->pluck('id', 'singkatan')->toArray();

        $cards = [
            // Admin Cards
            [
                'uid' => '93DBD1CF',
                'nama' => 'admin',
                'jabatan' => 'admin',
                'divisi' => 'superadmin',
                'nim' => 'ADMIN_93DBD1CF',
            ],
            [
                'uid' => '14AFD3E6',
                'nama' => 'admin',
                'jabatan' => 'admin',
                'divisi' => 'superadmin',
                'nim' => 'ADMIN_14AFD3E6',
            ],
            [
                'uid' => 'E376440E',
                'nama' => 'admin',
                'jabatan' => 'admin',
                'divisi' => 'superadmin',
                'nim' => 'ADMIN_E376440E',
            ],
            // Member Cards
            ['uid' => '3D5D5AC3', 'nama' => 'Barka Tirta Rama Abdurrahman', 'jabatan' => 'Ketua Umum HMIF', 'divisi' => 'superadmin', 'nim' => '10123365'],
            ['uid' => '2D4762C3', 'nama' => 'Alfa Riza Maftu Eka Sakti', 'jabatan' => 'Wakil Ketua Umum HMIF', 'divisi' => 'superadmin', 'nim' => '10123327'],
            ['uid' => '5DDF9BC3', 'nama' => 'Bhadriko Theo Pramudya', 'jabatan' => 'Sekretaris Umum HMIF', 'divisi' => 'superadmin', 'nim' => '10123375'],
            ['uid' => '7D8F7EC3', 'nama' => 'Riska Oktaviani', 'jabatan' => 'Bendahara Umum HMIF', 'divisi' => 'superadmin', 'nim' => '10123142'],
            ['uid' => '0DBE5DC3', 'nama' => 'Dwi Putra Juniargi', 'jabatan' => 'Kepala Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10123132'],
            ['uid' => '8DEC6EC3', 'nama' => 'Rikza Danan Irdian', 'jabatan' => 'Pengurus Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10123040'],
            ['uid' => '2D4B98C3', 'nama' => 'Cheryl Jessica Oktavia', 'jabatan' => 'Pengurus Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10123019'],
            ['uid' => '2D6465C3', 'nama' => 'Elvin Juniansha', 'jabatan' => 'Pengurus Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10123164'],
            ['uid' => '8D3B6CC3', 'nama' => 'Haifa Afina', 'jabatan' => 'Pengurus Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10123415'],
            ['uid' => '2DF467C3', 'nama' => 'Reyhan Tahira', 'jabatan' => 'Pengurus Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10123010'],
            ['uid' => 'CD6C64C3', 'nama' => 'Daffa Firizqi', 'jabatan' => 'Pengurus Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10123086'],
            ['uid' => '1DAB69C3', 'nama' => 'Najwa Nurul Aziz', 'jabatan' => 'Anggota Muda Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10124158'],
            ['uid' => '8D708AC3', 'nama' => 'Naufal Daffa Dzulfaqor', 'jabatan' => 'Anggota Muda Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10124362'],
            ['uid' => 'FDEC57C3', 'nama' => 'Nico Luthfiano Santoso', 'jabatan' => 'Anggota Muda Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10124096'],
            ['uid' => '0D026DC3', 'nama' => 'Sybil Khoirunnisa Nur Azqa', 'jabatan' => 'Anggota Muda Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10124400'],
            ['uid' => '5DAA74C3', 'nama' => 'Muhammad Reza Raihan Mutty', 'jabatan' => 'Anggota Muda Divisi KESMA', 'divisi' => 'kesma', 'nim' => '10124472'],
            ['uid' => 'AD618CC3', 'nama' => 'Hadi Rabbani', 'jabatan' => 'Kepala Divisi KESRA', 'divisi' => 'kesra', 'nim' => '10123001'],
            ['uid' => '5DBF92C3', 'nama' => 'Mochammad Iqbal Nurhafidz', 'jabatan' => 'Pengurus Divisi KESRA', 'divisi' => 'kesra', 'nim' => '10123356'],
            ['uid' => '5D9982C3', 'nama' => 'Narisa Syahril', 'jabatan' => 'Pengurus Divisi KESRA', 'divisi' => 'kesra', 'nim' => '10123326'],
            ['uid' => '7D6799C3', 'nama' => 'Salsabila Nurhaliza', 'jabatan' => 'Pengurus Divisi KESRA', 'divisi' => 'kesra', 'nim' => '10123306'],
            ['uid' => '7D128CC3', 'nama' => 'Senzie Apparel Gunoya', 'jabatan' => 'Pengurus Divisi KESRA', 'divisi' => 'kesra', 'nim' => '10123226'],
            ['uid' => 'BD5987C3', 'nama' => 'Gifari Raya Shahizidan', 'jabatan' => 'Anggota Muda Divisi KESRA', 'divisi' => 'kesra', 'nim' => '10124067'],
            ['uid' => 'EDC176C3', 'nama' => 'Iviani Gerbian', 'jabatan' => 'Anggota Muda Divisi KESRA', 'divisi' => 'kesra', 'nim' => '10124262'],
            ['uid' => '4D405AC3', 'nama' => 'Izharul Haq', 'jabatan' => 'Anggota Muda Divisi KESRA', 'divisi' => 'kesra', 'nim' => '10124414'],
            ['uid' => '5D3795C3', 'nama' => 'Muhammad Dwi Putra', 'jabatan' => 'Anggota Muda Divisi KESRA', 'divisi' => 'kesra', 'nim' => '10124049'],
            ['uid' => '5D025AC3', 'nama' => 'Rendi Aditia Syahriyandani', 'jabatan' => 'Anggota Muda Divisi KESRA', 'divisi' => 'kesra', 'nim' => '10124254'],
            ['uid' => 'CD4B97C3', 'nama' => 'Randy Fawwaz Aditya', 'jabatan' => 'Kepala Divisi KOMINFO', 'divisi' => 'kominfo', 'nim' => '10123106'],
            ['uid' => 'AD105DC3', 'nama' => 'Salsya Fadillah', 'jabatan' => 'Pengurus Divisi KOMINFO', 'divisi' => 'kominfo', 'nim' => '10123307'],
            ['uid' => 'AD4D5AC3', 'nama' => 'Adri Faiz Shiddiq', 'jabatan' => 'Pengurus Divisi KOMINFO', 'divisi' => 'kominfo', 'nim' => '10123346'],
            ['uid' => '8DAD76C3', 'nama' => 'Sukma Novianti Tulak', 'jabatan' => 'Pengurus Divisi KOMINFO', 'divisi' => 'kominfo', 'nim' => '10123345'],
            ['uid' => '9D7E8FC3', 'nama' => 'Muhammad Rifky Cahyadi', 'jabatan' => 'Pengurus Divisi KOMINFO', 'divisi' => 'kominfo', 'nim' => '10123450'],
            ['uid' => 'CD0078C3', 'nama' => 'Andika Rizki Fo\'eraera Laia', 'jabatan' => 'Angkatan Muda Divisi KOMINFO', 'divisi' => 'kominfo', 'nim' => '10124466'],
            ['uid' => '7DB99BC3', 'nama' => 'Muhammad Faudzan Adzima', 'jabatan' => 'Angkatan Muda Divisi KOMINFO', 'divisi' => 'kominfo', 'nim' => '10124194'],
            ['uid' => '2D8E62C3', 'nama' => 'Hadyan Prasetyo Busono', 'jabatan' => 'Angkatan Muda Divisi KOMINFO', 'divisi' => 'kominfo', 'nim' => '10124280'],
            ['uid' => 'FDE46DC3', 'nama' => 'Nazlah Sakinah A', 'jabatan' => 'Angkatan Muda Divisi KOMINFO', 'divisi' => 'kominfo', 'nim' => '10124412'],
            ['uid' => 'BDC692C3', 'nama' => 'Raihan Raksaguna', 'jabatan' => 'Kepala Divisi SOSMA', 'divisi' => 'sosma', 'nim' => '10123107'],
            ['uid' => 'ED829BC3', 'nama' => 'M Denish Kafaulloh Arasyid', 'jabatan' => 'Pengurus Divisi SOSMA', 'divisi' => 'sosma', 'nim' => '10123443'],
            ['uid' => 'BD0067C3', 'nama' => 'M Zaidan Azhari', 'jabatan' => 'Pengurus Divisi SOSMA', 'divisi' => 'sosma', 'nim' => '10123136'],
            ['uid' => 'DDFE5DC3', 'nama' => 'M Dafa Andika Radithia', 'jabatan' => 'Pengurus Divisi SOSMA', 'divisi' => 'sosma', 'nim' => '10123382'],
            ['uid' => '3D9688C3', 'nama' => 'Nabila Dwi Kusni Abdillah', 'jabatan' => 'Pengurus Divisi SOSMA', 'divisi' => 'sosma', 'nim' => '10123320'],
            ['uid' => '4D278EC3', 'nama' => 'Nadhira Aprillia', 'jabatan' => 'Pengurus Divisi SOSMA', 'divisi' => 'sosma', 'nim' => '10123425'],
            ['uid' => '7D9F91C3', 'nama' => 'Daffa Fachrur Rizqi', 'jabatan' => 'Angkatan Muda Divisi SOSMA', 'divisi' => 'sosma', 'nim' => '10124113'],
            ['uid' => '4DFC96C3', 'nama' => 'Muhammad Ihsan', 'jabatan' => 'Angkatan Muda Divisi SOSMA', 'divisi' => 'sosma', 'nim' => '10124384'],
            ['uid' => '0D9699C3', 'nama' => 'Mochammad Syafiq Eka Prasetyo', 'jabatan' => 'Angkatan Muda Divisi SOSMA', 'divisi' => 'sosma', 'nim' => '10124085'],
            ['uid' => '2D8C58C3', 'nama' => 'Zaki Imamul Umam', 'jabatan' => 'Angkatan Muda Divisi SOSMA', 'divisi' => 'sosma', 'nim' => '10124286'],
            ['uid' => '2D6C87C3', 'nama' => 'Vincent Luhulima', 'jabatan' => 'Kepala Divisi PWTI', 'divisi' => 'pwti', 'nim' => '10123309'],
            ['uid' => 'BDA893C3', 'nama' => 'Ai Irma Anjelin', 'jabatan' => 'Pengurus Divisi PWTI', 'divisi' => 'pwti', 'nim' => '10123430'],
            ['uid' => 'ADD46FC3', 'nama' => 'Devina Prima Angelista', 'jabatan' => 'Pengurus Divisi PWTI', 'divisi' => 'pwti', 'nim' => '10123277'],
            ['uid' => 'ADB796C3', 'nama' => 'Muhamad Rizki Fadilah', 'jabatan' => 'Pengurus Divisi PWTI', 'divisi' => 'pwti', 'nim' => '10123085'],
            ['uid' => 'DDA37AC3', 'nama' => 'Jorge Fielnero Sauman', 'jabatan' => 'Pengurus Divisi PWTI', 'divisi' => 'pwti', 'nim' => '10123318'],
            ['uid' => '5D9D75C3', 'nama' => 'Valyza Safina Zoia Azzura', 'jabatan' => 'Anggota Muda Divisi PWTI', 'divisi' => 'pwti', 'nim' => '10124233'],
            ['uid' => '6D6198C3', 'nama' => 'Amhar Razka Ariyanto', 'jabatan' => 'Anggota Muda Divisi PWTI', 'divisi' => 'pwti', 'nim' => '10124011'],
            ['uid' => '7D7C63C3', 'nama' => 'Haky Jawwad AL Hakim Effendi', 'jabatan' => 'Anggota Muda Divisi PWTI', 'divisi' => 'pwti', 'nim' => '10124390'],
            ['uid' => '2D385FC3', 'nama' => 'Rizki Maulana Hakim', 'jabatan' => 'Anggota Muda Divisi PWTI', 'divisi' => 'pwti', 'nim' => '10124317'],
            ['uid' => '1D1658C3', 'nama' => 'Muhammad Faishal Rahmani', 'jabatan' => 'Kepala Divisi PAO', 'divisi' => 'pao', 'nim' => '10123135'],
            ['uid' => '6D4B67C3', 'nama' => 'Nur Azizah Naswa', 'jabatan' => 'Pengurus Divisi PAO', 'divisi' => 'pao', 'nim' => '10123261'],
            ['uid' => '0DB267C3', 'nama' => 'Hasna Nur Maulida', 'jabatan' => 'Pengurus Divisi PAO', 'divisi' => 'pao', 'nim' => '10123258'],
            ['uid' => '9D8D83C3', 'nama' => 'Revana Yusuf Maulid', 'jabatan' => 'Pengurus Divisi PAO', 'divisi' => 'pao', 'nim' => '12123355'],
            ['uid' => 'ADF294C3', 'nama' => 'Panji Raditya Gaib', 'jabatan' => 'Pengurus Divisi PAO', 'divisi' => 'pao', 'nim' => '10123223'],
            ['uid' => '9D9E66C3', 'nama' => 'Idin Naufal Hakim', 'jabatan' => 'Pengurus Divisi PAO', 'divisi' => 'pao', 'nim' => '10123157'],
            ['uid' => '0D2769C3', 'nama' => 'Reisya Seinanda Awalia', 'jabatan' => 'Anggota Muda Divisi PAO', 'divisi' => 'pao', 'nim' => '10124302'],
            ['uid' => '9D7C93C3', 'nama' => 'Muhamad Farrel Chandrawijaya', 'jabatan' => 'Anggota Muda Divisi PAO', 'divisi' => 'pao', 'nim' => '10124467'],
            ['uid' => '4D0187C3', 'nama' => 'L.Hidayat Hijri', 'jabatan' => 'Anggota Muda Divisi PAO', 'divisi' => 'pao', 'nim' => '10124116'],
            ['uid' => '5D416AC3', 'nama' => 'Muhammad Nabil Nurhidayat', 'jabatan' => 'Anggota Muda Divisi PAO', 'divisi' => 'pao', 'nim' => '10124091'],
            ['uid' => 'ED317EC3', 'nama' => 'Abdul Malik Febrian Zulkifli', 'jabatan' => 'Kepala Divisi KWU', 'divisi' => 'kwu', 'nim' => '10123308'],
            ['uid' => '6DAA5EC3', 'nama' => 'Anisa', 'jabatan' => 'Pengurus Divisi KWU', 'divisi' => 'kwu', 'nim' => '10123234'],
            ['uid' => 'AD586BC3', 'nama' => 'Ars\'ael Mirat Dhiazahya HDT', 'jabatan' => 'Pengurus Divisi KWU', 'divisi' => 'kwu', 'nim' => '10123253'],
            ['uid' => '8D7B6DC3', 'nama' => 'Zahara Nanda Alvianti', 'jabatan' => 'Pengurus Divisi KWU', 'divisi' => 'kwu', 'nim' => '10123325'],
            ['uid' => 'CD3A98C3', 'nama' => 'Nabila Syifa Az zahra', 'jabatan' => 'Pengurus Divisi KWU', 'divisi' => 'kwu', 'nim' => '10123125'],
            ['uid' => '7DDC68C3', 'nama' => 'Hasbi Arsyan Anugrah Firdaus', 'jabatan' => 'Pengurus Divisi KWU', 'divisi' => 'kwu', 'nim' => '10123237'],
            ['uid' => '5D4291C3', 'nama' => 'Ramdhan Husna A\'liyasa', 'jabatan' => 'Pengurus Divisi KWU', 'divisi' => 'kwu', 'nim' => '10123260'],
            ['uid' => '4DF980C3', 'nama' => 'Mochamad Syabill Putra Ramadhan', 'jabatan' => 'Pengurus Divisi KWU', 'divisi' => 'kwu', 'nim' => '10123436'],
            ['uid' => '7D3382C3', 'nama' => 'Muhammad Abdurrahman Arrody', 'jabatan' => 'Pengurus Divisi KWU', 'divisi' => 'kwu', 'nim' => '10123462'],
            ['uid' => '8DC76AC3', 'nama' => 'Fariduddin Attar Darmaputra', 'jabatan' => 'Angkatan Muda Divisi KWU', 'divisi' => 'kwu', 'nim' => '10124048'],
            ['uid' => '4DAC70C3', 'nama' => 'Reza Alam', 'jabatan' => 'Angkatan Muda Divisi KWU', 'divisi' => 'kwu', 'nim' => '10124052'],
            ['uid' => '3D6878C3', 'nama' => 'Regita Setiani', 'jabatan' => 'Angkatan Muda Divisi KWU', 'divisi' => 'kwu', 'nim' => '10124239'],
            ['uid' => 'AD1A63C3', 'nama' => 'Rafli Zeirofi Pohan', 'jabatan' => 'Angkatan Muda Divisi KWU', 'divisi' => 'kwu', 'nim' => '10124025'],
        ];

        foreach ($cards as $card) {
            $divisiId = $divisiMap[$card['divisi']] ?? null;

            RfidCard::updateOrCreate(
                ['uid' => strtoupper($card['uid'])],
                [
                    'nim' => $card['nim'],
                    'nama' => $card['nama'],
                    'jabatan' => $card['jabatan'],
                    'divisi_id' => $divisiId,
                    'is_active' => true,
                ]
            );
        }
    }
}
