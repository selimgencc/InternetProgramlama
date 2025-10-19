<?php
/**
 * Admin Panel - Kitap Yönetimi API
 * Selim Library - İstanbul Medeniyet Üniversitesi
 */

require_once '../config/database.php';

// Session kontrolü
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Yetkisiz erişim']);
    exit;
}

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($method) {
    case 'GET':
        if ($action === 'books') {
            getBooks();
        } elseif ($action === 'stats') {
            getStats();
        }
        break;
        
    case 'POST':
        if ($action === 'add_book') {
            addBook();
        }
        break;
        
    case 'PUT':
        if ($action === 'update_book') {
            updateBook();
        }
        break;
        
    case 'DELETE':
        if ($action === 'delete_book') {
            deleteBook();
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Geçersiz metod']);
}

function getBooks() {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sql = "SELECT * FROM kitaplar";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " WHERE (baslik LIKE :search OR yazar LIKE :search)";
        $params[':search'] = "%$search%";
    }
    
    $sql .= " ORDER BY eklenme_tarihi DESC";
    $books = fetchAll($sql, $params);
    
    echo json_encode(['success' => true, 'data' => $books]);
}

function getStats() {
    $totalBooks = fetchOne("SELECT COUNT(*) as total FROM kitaplar")['total'];
    $totalUsers = fetchOne("SELECT COUNT(*) as total FROM kullanici")['total'];
    $totalCategories = fetchOne("SELECT COUNT(DISTINCT kategori) as total FROM kitaplar WHERE kategori IS NOT NULL")['total'];
    
    echo json_encode([
        'success' => true,
        'data' => [
            'totalBooks' => $totalBooks,
            'totalUsers' => $totalUsers,
            'totalCategories' => $totalCategories
        ]
    ]);
}

function addBook() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $baslik = isset($input['title']) ? trim($input['title']) : '';
    $yazar = isset($input['author']) ? trim($input['author']) : '';
    $yil = isset($input['year']) ? trim($input['year']) : '';
    $kategori = isset($input['category']) ? trim($input['category']) : '';
    $aciklama = isset($input['description']) ? trim($input['description']) : '';
    
    if (empty($baslik) || empty($yazar)) {
        echo json_encode(['success' => false, 'message' => 'Başlık ve yazar alanları gerekli']);
        return;
    }
    
    $sql = "INSERT INTO kitaplar (baslik, yazar, yayin_yili, kategori, aciklama, eklenme_tarihi) 
            VALUES (:baslik, :yazar, :yil, :kategori, :aciklama, NOW())";
    
    $params = [
        ':baslik' => $baslik,
        ':yazar' => $yazar,
        ':yil' => $yil,
        ':kategori' => $kategori,
        ':aciklama' => $aciklama
    ];
    
    $result = insertData($sql, $params);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Kitap başarıyla eklendi', 'id' => $result]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Kitap eklenirken hata oluştu']);
    }
}

function updateBook() {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = isset($input['id']) ? (int)$input['id'] : 0;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz kitap ID']);
        return;
    }
    
    $baslik = isset($input['title']) ? trim($input['title']) : '';
    $yazar = isset($input['author']) ? trim($input['author']) : '';
    $yil = isset($input['year']) ? trim($input['year']) : '';
    $kategori = isset($input['category']) ? trim($input['category']) : '';
    $aciklama = isset($input['description']) ? trim($input['description']) : '';
    
    $sql = "UPDATE kitaplar SET baslik = :baslik, yazar = :yazar, yayin_yili = :yil, 
            kategori = :kategori, aciklama = :aciklama WHERE id = :id";
    
    $params = [
        ':id' => $id,
        ':baslik' => $baslik,
        ':yazar' => $yazar,
        ':yil' => $yil,
        ':kategori' => $kategori,
        ':aciklama' => $aciklama
    ];
    
    $result = updateData($sql, $params);
    
    if ($result > 0) {
        echo json_encode(['success' => true, 'message' => 'Kitap başarıyla güncellendi']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Kitap güncellenirken hata oluştu']);
    }
}

function deleteBook() {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = isset($input['id']) ? (int)$input['id'] : 0;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz kitap ID']);
        return;
    }
    
    $sql = "DELETE FROM kitaplar WHERE id = :id";
    $result = updateData($sql, [':id' => $id]);
    
    if ($result > 0) {
        echo json_encode(['success' => true, 'message' => 'Kitap başarıyla silindi']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Kitap silinirken hata oluştu']);
    }
}
?>
