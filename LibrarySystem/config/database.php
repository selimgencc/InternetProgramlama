<?php
/**
 * Veritabanı Bağlantı Dosyası
 * Selim Library - İstanbul Medeniyet Üniversitesi
 */

// Veritabanı bağlantı bilgileri
$host = 'localhost';
$dbname = 'LibrarySystem';
$username = 'root';
$password = '';

try {
    // PDO bağlantısı oluştur
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Hata modunu ayarla
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Varsayılan fetch modunu ayarla
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Bağlantı başarılı mesajı (geliştirme aşamasında)
    // echo "Veritabanı bağlantısı başarılı!";
    
} catch(PDOException $e) {
    // Hata durumunda mesaj göster
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Yardımcı fonksiyonlar
function getConnection() {
    global $pdo;
    return $pdo;
}

// Güvenli sorgu çalıştırma fonksiyonu
function executeQuery($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        error_log("Sorgu hatası: " . $e->getMessage());
        return false;
    }
}

// Tek satır veri getirme
function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetch() : false;
}

// Çoklu satır veri getirme
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetchAll() : [];
}

// Veri ekleme ve son eklenen ID'yi döndürme
function insertData($sql, $params = []) {
    global $pdo;
    $stmt = executeQuery($sql, $params);
    return $stmt ? $pdo->lastInsertId() : false;
}

// Veri güncelleme/silme ve etkilenen satır sayısını döndürme
function updateData($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->rowCount() : 0;
}
?>
