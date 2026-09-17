<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Candidate;

class VotingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(['username' => 'admin'], [
            'name' => 'Administrator', 'full_name' => 'Panitia Utama',
            'email' => 'admin@koperasi.test', 'password' => Hash::make('admin123'),
            'role' => 'ADMIN',
        ]);
        foreach ([['voter1', 'Budi Santoso'], ['voter2', 'Siti Aminah'], ['voter3', 'Ahmad Fauzi']] as [$u, $n]) {
            User::updateOrCreate(['username' => $u], [
                'name' => $n, 'full_name' => $n, 'email' => $u . '@koperasi.test',
                'password' => Hash::make('voter123'), 'role' => 'VOTER',
            ]);
        }
        foreach ([
            [1, 'Budi Santoso, S.E.', 'Koperasi digital transparan.', "1. Digitalisasi transaksi\n2. Pelatihan anggota\n3. SHU transparan"],
            [2, 'Siti Nurhaliza, S.H.', 'Koperasi tangguh mandiri sejahtera.', "1. Optimalisasi aset\n2. Kemitraan strategis\n3. Dana talangan"],
            [3, 'Ahmad Fauzi, S.T.', 'Ekonomi anggota berazas kekeluargaan.', "1. Sembako murah\n2. Pendampingan UMKM\n3. Modernisasi simpan pinjam"],
        ] as [$num, $name, $vision, $mission]) {
            Candidate::updateOrCreate(['candidate_number' => $num], [
                'full_name' => $name, 'vision' => $vision, 'mission' => $mission,
            ]);
        }
    }
}
