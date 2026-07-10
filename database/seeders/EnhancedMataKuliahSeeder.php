<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class EnhancedMataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            'TI' => [
                ['IF-201', 'Algoritma dan Pemrograman', 4, 1, 'Mata kuliah dasar yang membahas konsep algoritma, struktur data dasar, dan teknik pemrograman terstruktur menggunakan bahasa pemrograman.'],
                ['IF-202', 'Basis Data', 3, 2, 'Mata kuliah yang mempelajari desain basis data relasional, normalisasi, dan implementasi menggunakan DBMS populer.'],
                ['IF-203', 'Pemrograman Web', 3, 2, 'Mata kuliah yang mencakup pengembangan web frontend dan backend, framework modern, dan best practices web development.'],
                ['IF-204', 'Jaringan Komputer', 3, 3, 'Mata kuliah tentang arsitektur jaringan, protokol komunikasi, dan implementasi infrastruktur jaringan.'],
                ['IF-205', 'Sistem Operasi', 3, 3, 'Mata kuliah yang membahas konsep sistem operasi, manajemen proses, memory management, dan file system.'],
                ['IF-206', 'Struktur Data Lanjut', 3, 2, 'Studi lanjut tentang struktur data kompleks seperti tree, graph, dan aplikasinya dalam problem solving.'],
                ['IF-207', 'Rekayasa Perangkat Lunak', 3, 4, 'Mata kuliah tentang metodologi pengembangan software, design patterns, dan quality assurance.'],
                ['IF-208', 'Mobile Programming', 3, 4, 'Pengembangan aplikasi mobile untuk platform Android dan iOS menggunakan framework modern.'],
                ['IF-209', 'Keamanan Informasi', 3, 4, 'Mata kuliah tentang enkripsi, authentication, dan best practices dalam security systems.'],
                ['IF-210', 'Cloud Computing', 3, 5, 'Implementasi dan manajemen cloud infrastructure menggunakan platform seperti AWS, Azure, atau GCP.'],
            ],
            'SI' => [
                ['SI-201', 'Analisis dan Desain Sistem Informasi', 4, 1, 'Mata kuliah yang mengajarkan metodologi analisis dan desain sistem informasi untuk kebutuhan bisnis.'],
                ['SI-202', 'Manajemen Basis Data', 3, 2, 'Pengelolaan database untuk mendukung sistem informasi enterprise dengan fokus pada integrasi data.'],
                ['SI-203', 'Sistem Informasi Manajemen', 3, 2, 'Studi tentang peran sistem informasi dalam manajemen organisasi dan pengambilan keputusan.'],
                ['SI-204', 'Enterprise Architecture', 3, 3, 'Merancang arsitektur enterprise IT yang terintegrasi dan mendukung transformasi bisnis digital.'],
                ['SI-205', 'Business Intelligence', 3, 3, 'Implementasi BI tools untuk analytics, reporting, dan data-driven decision making.'],
                ['SI-206', 'Audit Sistem Informasi', 3, 4, 'Metodologi audit IT dan compliance terhadap standar informasi security.'],
                ['SI-207', 'Manajemen Proyek Sistem Informasi', 3, 4, 'Pengelolaan proyek SI menggunakan framework agile dan tradisional.'],
                ['SI-208', 'Sistem Informasi Supply Chain', 3, 4, 'Implementasi SI untuk optimasi supply chain dan logistik bisnis.'],
                ['SI-209', 'E-Commerce dan Digital Business', 3, 5, 'Pengembangan platform e-commerce dan strategi digital business.'],
                ['SI-210', 'Tata Kelola TI (IT Governance)', 3, 5, 'Framework governance IT seperti COBIT dan penerapannya dalam organisasi.'],
            ],
            'PPLG' => [
                ['PPLG-201', 'Pemrograman Berorientasi Objek', 4, 1, 'Konsep OOP, design patterns, dan pengimplementasiannya dalam pemrograman aplikasi.'],
                ['PPLG-202', 'Web Framework & Modern Development', 3, 2, 'Penggunaan framework web modern seperti Laravel, Django, atau Spring untuk development cepat.'],
                ['PPLG-203', 'UI/UX Design', 3, 2, 'Prinsip desain user interface dan user experience untuk aplikasi desktop dan web.'],
                ['PPLG-204', 'Testing & Quality Assurance', 3, 3, 'Unit testing, integration testing, dan automation testing untuk quality software.'],
                ['PPLG-205', 'DevOps & Deployment', 3, 3, 'Continuous integration, continuous deployment, dan containerization menggunakan Docker.'],
                ['PPLG-206', 'Software Architecture', 3, 3, 'Desain arsitektur software scalable dan maintainable.'],
                ['PPLG-207', 'API Development', 3, 4, 'Pengembangan RESTful dan GraphQL API untuk komunikasi antar sistem.'],
                ['PPLG-208', 'Performance Optimization', 3, 4, 'Teknik optimasi kinerja aplikasi, caching, dan profiling.'],
                ['PPLG-209', 'Database Optimization', 3, 4, 'Query optimization, indexing, dan performance tuning database.'],
                ['PPLG-210', 'Agile & Software Engineering', 3, 5, 'Metodologi agile, scrum, dan best practices dalam engineering software.'],
            ],
            'TIFO' => [
                ['TIF-201', 'Dasar Infrastruktur IT', 4, 1, 'Pengenalan infrastruktur IT, hardware, networking dasar, dan operating system.'],
                ['TIF-202', 'Administrasi Sistem', 3, 2, 'Manajemen user, permissions, backup, dan maintenance sistem operasi.'],
                ['TIF-203', 'Network Administration', 3, 2, 'Konfigurasi dan manajemen network devices, routing, dan network security.'],
                ['TIF-204', 'Database Administration', 3, 3, 'Administrasi database, backup recovery, dan performance management.'],
                ['TIF-205', 'Server Management', 3, 3, 'Manajemen web server, application server, dan monitoring tools.'],
                ['TIF-206', 'Network Security', 3, 3, 'Implementasi firewall, VPN, intrusion detection, dan network security.'],
                ['TIF-207', 'Cloud Infrastructure', 3, 4, 'Manajemen cloud resources, IaaS, PaaS, dan SaaS.'],
                ['TIF-208', 'Virtualization & Hypervisor', 3, 4, 'Teknologi virtualisasi menggunakan VMware, Hyper-V, atau KVM.'],
                ['TIF-209', 'Disaster Recovery', 3, 4, 'Planning dan implementasi disaster recovery dan business continuity.'],
                ['TIF-210', 'IT Service Management', 3, 5, 'Framework ITIL dan best practices dalam IT service delivery.'],
            ],
        ];

        foreach ($courses as $kodeProdi => $items) {
            $prodi = ProgramStudi::where('kode_prodi', $kodeProdi)->first();

            if (!$prodi) {
                continue;
            }

            foreach ($items as [$kodeMk, $namaMk, $sks, $semesterKe, $deskripsi]) {
                MataKuliah::updateOrCreate(
                    ['kode_mk' => $kodeMk],
                    [
                        'program_studi_id' => $prodi->id,
                        'nama_mk' => $namaMk,
                        'sks' => $sks,
                        'semester_ke' => $semesterKe,
                        'deskripsi' => $deskripsi,
                    ]
                );
            }
        }
    }
}
