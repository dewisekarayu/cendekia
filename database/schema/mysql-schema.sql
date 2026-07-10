/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_perkuliahan_id` bigint unsigned NOT NULL,
  `pertemuan_ke` int NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `session_status` enum('draft','buka','tutup') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `rangkuman` text COLLATE utf8mb4_unicode_ci,
  `berita_acara` text COLLATE utf8mb4_unicode_ci,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `waktu_buka` timestamp NULL DEFAULT NULL,
  `waktu_tutup` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absensi_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  CONSTRAINT `absensi_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `absensi_mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensi_mahasiswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `absensi_id` bigint unsigned NOT NULL,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `status` enum('hadir','izin','sakit','alpha') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'alpha',
  `waktu_absensi` timestamp NULL DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absensi_mahasiswa_absensi_id_foreign` (`absensi_id`),
  KEY `absensi_mahasiswa_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `absensi_mahasiswa_absensi_id_foreign` FOREIGN KEY (`absensi_id`) REFERENCES `absensi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_mahasiswa_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `aktivitas_pengguna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktivitas_pengguna` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `kelas_perkuliahan_id` bigint unsigned DEFAULT NULL,
  `aksi` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terjadi_pada` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aktivitas_pengguna_user_id_foreign` (`user_id`),
  KEY `aktivitas_pengguna_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  KEY `aktivitas_pengguna_aksi_terjadi_pada_index` (`aksi`,`terjadi_pada`),
  CONSTRAINT `aktivitas_pengguna_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE SET NULL,
  CONSTRAINT `aktivitas_pengguna_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dosen_materi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen_materi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `materi_id` bigint unsigned NOT NULL,
  `dosen_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dosen_materi_materi_id_dosen_id_unique` (`materi_id`,`dosen_id`),
  KEY `dosen_materi_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `dosen_materi_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosen_materi_materi_id_foreign` FOREIGN KEY (`materi_id`) REFERENCES `materi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `forum_diskusi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forum_diskusi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_perkuliahan_id` bigint unsigned NOT NULL,
  `dibuat_oleh` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_diskusi_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  KEY `forum_diskusi_dibuat_oleh_foreign` (`dibuat_oleh`),
  CONSTRAINT `forum_diskusi_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_diskusi_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jadwal_ujian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_ujian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_perkuliahan_id` bigint unsigned NOT NULL,
  `jenis_ujian` enum('UTS','UAS','Quiz','Remedial') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_ujian` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwal_ujian_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  CONSTRAINT `jadwal_ujian_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kelas_mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas_mahasiswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_perkuliahan_id` bigint unsigned NOT NULL,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelas_mahasiswa_kelas_perkuliahan_id_mahasiswa_id_unique` (`kelas_perkuliahan_id`,`mahasiswa_id`),
  KEY `kelas_mahasiswa_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `kelas_mahasiswa_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_mahasiswa_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kelas_perkuliahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas_perkuliahan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `dosen_id` bigint unsigned NOT NULL,
  `semester_id` bigint unsigned NOT NULL,
  `kode_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `ruangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `program_studi_id` bigint unsigned DEFAULT NULL,
  `tahun_akademik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kuota_mahasiswa` int NOT NULL DEFAULT '30',
  `status_kelas` enum('aktif','nonaktif','selesai','draft') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `dosen_pengampu` json DEFAULT NULL COMMENT 'Array dosen ID untuk team teaching',
  PRIMARY KEY (`id`),
  KEY `kelas_perkuliahan_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  KEY `kelas_perkuliahan_dosen_id_foreign` (`dosen_id`),
  KEY `kelas_perkuliahan_semester_id_foreign` (`semester_id`),
  KEY `kelas_perkuliahan_program_studi_id_semester_id_index` (`program_studi_id`,`semester_id`),
  KEY `kelas_perkuliahan_hari_jam_mulai_index` (`hari`,`jam_mulai`),
  CONSTRAINT `kelas_perkuliahan_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_perkuliahan_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_perkuliahan_program_studi_id_foreign` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_perkuliahan_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `komentar_diskusi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `komentar_diskusi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `forum_diskusi_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `komentar_diskusi_forum_diskusi_id_foreign` (`forum_diskusi_id`),
  KEY `komentar_diskusi_user_id_foreign` (`user_id`),
  CONSTRAINT `komentar_diskusi_forum_diskusi_id_foreign` FOREIGN KEY (`forum_diskusi_id`) REFERENCES `forum_diskusi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `komentar_diskusi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mata_kuliah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_kuliah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `program_studi_id` bigint unsigned NOT NULL,
  `kode_mk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks` int NOT NULL DEFAULT '3',
  `semester_ke` int NOT NULL DEFAULT '1',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_kuliah_kode_mk_unique` (`kode_mk`),
  KEY `mata_kuliah_program_studi_id_foreign` (`program_studi_id`),
  CONSTRAINT `mata_kuliah_program_studi_id_foreign` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `materi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_perkuliahan_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `pertemuan_ke` int NOT NULL DEFAULT '1',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `materi_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  CONSTRAINT `materi_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nilai_akhir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_akhir` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_perkuliahan_id` bigint unsigned NOT NULL,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `nilai_kehadiran` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_tugas` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_quiz` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_project` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_uts` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_uas` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(5,2) NOT NULL DEFAULT '0.00',
  `grade` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'E',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nilai_akhir_kelas_perkuliahan_id_mahasiswa_id_unique` (`kelas_perkuliahan_id`,`mahasiswa_id`),
  KEY `nilai_akhir_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `nilai_akhir_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_akhir_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `kelas_perkuliahan_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('informasi','tugas','ujian','nilai','presensi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'informasi',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dibaca_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifikasi_user_id_foreign` (`user_id`),
  KEY `notifikasi_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  CONSTRAINT `notifikasi_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pengumpulan_tugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengumpulan_tugas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tugas_id` bigint unsigned NOT NULL,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `file_jawaban` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `waktu_kumpul` datetime DEFAULT NULL,
  `nilai` int DEFAULT NULL,
  `feedback_dosen` text COLLATE utf8mb4_unicode_ci,
  `status` enum('belum_dikumpulkan','dikumpulkan','terlambat','dinilai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dikumpulkan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengumpulan_tugas_tugas_id_foreign` (`tugas_id`),
  KEY `pengumpulan_tugas_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `pengumpulan_tugas_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengumpulan_tugas_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pengumuman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengumuman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_perkuliahan_id` bigint unsigned DEFAULT NULL,
  `dibuat_oleh` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `untuk_semua` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengumuman_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  KEY `pengumuman_dibuat_oleh_foreign` (`dibuat_oleh`),
  CONSTRAINT `pengumuman_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengumuman_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `program_studi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_studi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_prodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_prodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenjang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S1',
  `akreditasi` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baik',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `program_studi_kode_prodi_unique` (`kode_prodi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `semesters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `semesters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_semester` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('Ganjil','Genap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tugas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_perkuliahan_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instruksi` text COLLATE utf8mb4_unicode_ci,
  `file_lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deadline` datetime NOT NULL,
  `bobot_nilai` int NOT NULL DEFAULT '100',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tugas_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  CONSTRAINT `tugas_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip_nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_studi_id` bigint unsigned DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_nip_nim_unique` (`nip_nim`),
  KEY `users_program_studi_id_foreign` (`program_studi_id`),
  CONSTRAINT `users_program_studi_id_foreign` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2026_07_02_032018_create_permission_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2026_07_02_064158_create_program_studi_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2026_07_02_064506_create_semesters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2026_07_02_064758_create_mata_kuliah_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2026_07_02_070506_create_kelas_perkuliahan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2026_07_02_070839_create_kelas_mahasiswa_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2026_07_02_070932_create_materi_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2026_07_02_071010_create_tugas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2026_07_02_071046_create_pengumpulan_tugas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2026_07_02_071129_create_pengumuman_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2026_07_02_071407_create_forum_diskusi_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2026_07_02_071448_create_komentar_diskusi_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2026_07_03_054512_add_program_studi_id_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2026_07_07_013958_add_nip_nim_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2026_07_07_064230_create_absensi_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2026_07_07_064305_create_absensi_mahasiswa_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2026_07_07_070000_create_nilai_akhir_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2026_07_07_070010_create_aktivitas_pengguna_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2026_07_07_070020_create_notifikasi_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2026_07_07_070030_create_jadwal_ujian_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2026_07_08_045734_add_status_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2026_07_08_052305_update_status_and_add_fields_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2026_07_08_053345_drop_angkatan_column_from_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2026_07_09_041834_add_akreditasi_and_status_to_program_studi_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2026_07_10_000001_create_dosen_materi_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2026_07_10_033639_add_missing_fields_to_kelas_perkuliahan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2026_07_10_071947_add_session_management_to_absensi',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2026_07_10_072005_add_timestamp_to_absensi_mahasiswa',2);
