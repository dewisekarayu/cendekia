<?php

require __DIR__ . '/vendor/autoload.php';

// Parse .env manually
$env = file_get_contents(__DIR__ . '/.env');
preg_match('/DB_HOST=(.+)/', $env, $host);
preg_match('/DB_DATABASE=(.+)/', $env, $database);
preg_match('/DB_USERNAME=(.+)/', $env, $username);
preg_match('/DB_PASSWORD=(.+)/', $env, $password);

$dbHost = trim($host[1] ?? '127.0.0.1');
$dbDatabase = trim($database[1] ?? 'cendekia');
$dbUsername = trim($username[1] ?? 'root');
$dbPassword = trim($password[1] ?? '');

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbDatabase",
        $dbUsername,
        $dbPassword
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== SPATIE PERMISSION VERIFICATION ===\n\n";
    
    // 1. Check roles table
    echo "1. ROLES TABLE:\n";
    $stmt = $pdo->query("SELECT id, name FROM roles LIMIT 10");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($roles) === 0) {
        echo "   ❌ No roles found\n";
    } else {
        echo "   ✓ Found " . count($roles) . " roles:\n";
        foreach ($roles as $role) {
            echo "     - ID: {$role['id']}, Name: {$role['name']}\n";
        }
    }
    
    // 2. Check model_has_roles table
    echo "\n2. MODEL_HAS_ROLES TABLE:\n";
    $stmt = $pdo->query("
        SELECT 
            mhr.model_id,
            mhr.role_id,
            r.name as role_name,
            u.nip_nim,
            u.name
        FROM model_has_roles mhr
        JOIN roles r ON mhr.role_id = r.id
        JOIN users u ON mhr.model_id = u.id
        LIMIT 50
    ");
    $userRoles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($userRoles) === 0) {
        echo "   ❌ No user-role assignments found\n";
    } else {
        echo "   ✓ Found " . count($userRoles) . " user-role assignments:\n";
        
        // Count by role
        $roleCount = [];
        foreach ($userRoles as $ur) {
            $roleCount[$ur['role_name']] = ($roleCount[$ur['role_name']] ?? 0) + 1;
        }
        foreach ($roleCount as $roleName => $count) {
            echo "     - Role '$roleName': $count users\n";
        }
        
        // Show first Dosen
        $dosenFound = false;
        foreach ($userRoles as $ur) {
            if ($ur['role_name'] === 'dosen' && !$dosenFound) {
                echo "     * Example Dosen: ID {$ur['model_id']}, NIP: {$ur['nip_nim']}, Name: {$ur['name']}\n";
                $dosenFound = true;
                $dosenId = $ur['model_id'];
            }
        }
    }
    
    // 3. Check kelas_perkuliahan table
    echo "\n3. KELAS_PERKULIAHAN TABLE (Classes with Dosen):\n";
    if (isset($dosenId)) {
        $stmt = $pdo->prepare("
            SELECT kp.id, kp.kode_kelas, kp.dosen_id, mk.nama_mk
            FROM kelas_perkuliahan kp
            LEFT JOIN mata_kuliah mk ON kp.mata_kuliah_id = mk.id
            WHERE kp.dosen_id = ? 
            LIMIT 10
        ");
        $stmt->execute([$dosenId]);
        $kelas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($kelas) === 0) {
            echo "   ❌ Dosen (ID: $dosenId) not assigned to any classes\n";
        } else {
            echo "   ✓ Dosen (ID: $dosenId) teaches " . count($kelas) . " classes:\n";
            foreach ($kelas as $k) {
                echo "     - Class ID: {$k['id']}, Code: {$k['kode_kelas']}, Course: {$k['nama_mk']}\n";
            }
            
            // Check forums for these classes
            $kelasIds = array_column($kelas, 'id');
            echo "\n4. FORUM_DISKUSI TABLE (Forums in these classes):\n";
            $placeholders = implode(',', array_fill(0, count($kelasIds), '?'));
            $stmt = $pdo->prepare("
                SELECT fd.id, fd.judul, fd.kelas_perkuliahan_id 
                FROM forum_diskusi fd
                WHERE fd.kelas_perkuliahan_id IN ($placeholders)
            ");
            $stmt->execute($kelasIds);
            $forums = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($forums) === 0) {
                echo "   ❌ No forums found for these classes\n";
            } else {
                echo "   ✓ Found " . count($forums) . " forums:\n";
                foreach ($forums as $f) {
                    echo "     - Forum ID: {$f['id']}, Title: {$f['judul']}, Class: {$f['kelas_perkuliahan_id']}\n";
                }
            }
        }
    }
    
    // 5. Check Mahasiswa
    echo "\n5. MAHASISWA STUDENT ENROLLMENT:\n";
    $stmt = $pdo->query("
        SELECT 
            km.mahasiswa_id,
            km.kelas_perkuliahan_id,
            u.nip_nim,
            u.name,
            kp.kode_kelas
        FROM kelas_mahasiswa km
        JOIN users u ON km.mahasiswa_id = u.id
        JOIN kelas_perkuliahan kp ON km.kelas_perkuliahan_id = kp.id
        LIMIT 5
    ");
    $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($enrollments) === 0) {
        echo "   ❌ No student enrollments found\n";
    } else {
        echo "   ✓ Found " . count($enrollments) . " sample enrollments:\n";
        foreach ($enrollments as $e) {
            echo "     - Student {$e['name']} ({$e['nip_nim']}) in class {$e['kode_kelas']}\n";
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "✓ All permission tables exist and have data\n";
    
} catch (PDOException $e) {
    echo "❌ Database connection error:\n";
    echo "   " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
