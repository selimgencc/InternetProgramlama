<?php
/**
 * Login İşlemi Backend
 * Selim Library - İstanbul Medeniyet Üniversitesi
 */

require_once '../config/database.php';

// JSON response için header ayarla
header('Content-Type: application/json');

// Sadece POST isteklerini kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Sadece POST istekleri kabul edilir']);
    exit;
}

// Gelen verileri al
$input = json_decode(file_get_contents('php://input'), true);
$username = isset($input['username']) ? trim($input['username']) : '';
$password = isset($input['password']) ? trim($input['password']) : '';

// Boş alan kontrolü
if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Kullanıcı adı ve şifre gerekli']);
    exit;
}

try {
    // Kullanıcıyı veritabanından ara
    $sql = "SELECT id, kullanici_adi, sifre, ad_soyad, email, rol FROM kullanici WHERE kullanici_adi = :username";
    $user = fetchOne($sql, [':username' => $username]);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Kullanıcı bulunamadı']);
        exit;
    }
    
    // Şifre kontrolü (gerçek projede hash'lenmiş şifreler kullanılmalı)
    if ($password !== $user['sifre']) {
        echo json_encode(['success' => false, 'message' => 'Hatalı şifre']);
        exit;
    }
    
    // Session başlat
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['kullanici_adi'];
    $_SESSION['name'] = $user['ad_soyad'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['rol'];
    $_SESSION['logged_in'] = true;
    
    // Başarılı giriş
    echo json_encode([
        'success' => true, 
        'message' => 'Giriş başarılı',
        'user' => [
            'id' => $user['id'],
            'username' => $user['kullanici_adi'],
            'name' => $user['ad_soyad'],
            'email' => $user['email'],
            'role' => $user['rol']
        ],
        'redirect' => $user['rol'] === 'admin' ? 'admin.php' : 'kitaplar.php'
    ]);
    
} catch (Exception $e) {
    error_log("Login hatası: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Sistem hatası oluştu']);
}
?>
