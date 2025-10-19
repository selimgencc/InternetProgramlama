<?php
/**
 * Kitapları Listeleme Sayfası
 * Selim Library - İstanbul Medeniyet Üniversitesi
 */

require_once 'config/database.php';

// Sayfalama ayarları
$limit = 12; // Sayfa başına kitap sayısı
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Arama parametreleri
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Toplam kitap sayısını al
$countSql = "SELECT COUNT(*) as total FROM kitaplar";
$countParams = [];

if (!empty($search)) {
    $countSql .= " WHERE (baslik LIKE :search OR yazar LIKE :search OR aciklama LIKE :search)";
    $countParams[':search'] = "%$search%";
}

if (!empty($category)) {
    $countSql .= empty($search) ? " WHERE kategori = :category" : " AND kategori = :category";
    $countParams[':category'] = $category;
}

$totalBooks = fetchOne($countSql, $countParams)['total'];
$totalPages = ceil($totalBooks / $limit);

// Kitapları getir
$sql = "SELECT * FROM kitaplar";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE (baslik LIKE :search OR yazar LIKE :search OR aciklama LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($category)) {
    $sql .= empty($search) ? " WHERE kategori = :category" : " AND kategori = :category";
    $params[':category'] = $category;
}

$sql .= " ORDER BY eklenme_tarihi DESC LIMIT $limit OFFSET $offset";

$books = fetchAll($sql, $params);

// Kategorileri getir
$categories = fetchAll("SELECT DISTINCT kategori FROM kitaplar WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitaplar | Selim Library</title>
    <link rel="stylesheet" href="genel.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Modern Navbar -->
    <header class="navbar modern-navbar">
        <div class="logo-min">
            <img src="logo.png" alt="Selim Library Logo">
        </div>
        <h1 class="site-title">Selim Library</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">
                    <i class="fas fa-home"></i>
                    Ana Sayfa
                </a></li>
                <li><a href="hakkimizda.html">
                    <i class="fas fa-info-circle"></i>
                    Hakkımızda
                </a></li>
                <li><a href="misyon-vizyon.html">
                    <i class="fas fa-bullseye"></i>
                    Misyon & Vizyon
                </a></li>
                <li><a href="iletisim.html">
                    <i class="fas fa-envelope"></i>
                    İletişim
                </a></li>
                <li><a href="kitaplar.php" class="active">
                    <i class="fas fa-book"></i>
                    Kitaplar
                </a></li>
                <li><a href="login.html">
                    <i class="fas fa-sign-in-alt"></i>
                    Giriş
                </a></li>
            </ul>
        </nav>
    </header>

    <!-- Ana İçerik -->
    <main class="books-container">
        <div class="books-header">
            <h2><i class="fas fa-book"></i> Kütüphane Koleksiyonu</h2>
            <p>Toplam <?php echo $totalBooks; ?> kitap bulunmaktadır</p>
        </div>

        <!-- Arama ve Filtreleme -->
        <div class="search-filters">
            <form method="GET" class="search-form">
                <div class="search-group">
                    <div class="search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Kitap, yazar veya konu ara..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="category-filter">
                        <select name="category">
                            <option value="">Tüm Kategoriler</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['kategori']); ?>" 
                                        <?php echo $category === $cat['kategori'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['kategori']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Ara
                    </button>
                    <?php if(!empty($search) || !empty($category)): ?>
                        <a href="kitaplar.php" class="btn btn-outline">
                            <i class="fas fa-times"></i>
                            Temizle
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Kitaplar Grid -->
        <div class="books-grid">
            <?php if(empty($books)): ?>
                <div class="no-books">
                    <i class="fas fa-book-open"></i>
                    <h3>Aradığınız kriterlere uygun kitap bulunamadı</h3>
                    <p>Farklı anahtar kelimeler deneyebilir veya kategori filtresini değiştirebilirsiniz.</p>
                </div>
            <?php else: ?>
                <?php foreach($books as $book): ?>
                    <div class="book-card">
                        <div class="book-cover">
                            <?php if(!empty($book['kapak_resmi'])): ?>
                                <img src="<?php echo htmlspecialchars($book['kapak_resmi']); ?>" 
                                     alt="<?php echo htmlspecialchars($book['baslik']); ?>">
                            <?php else: ?>
                                <div class="default-cover">
                                    <i class="fas fa-book"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title"><?php echo htmlspecialchars($book['baslik']); ?></h3>
                            <p class="book-author">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($book['yazar']); ?>
                            </p>
                            <?php if(!empty($book['kategori'])): ?>
                                <span class="book-category"><?php echo htmlspecialchars($book['kategori']); ?></span>
                            <?php endif; ?>
                            <?php if(!empty($book['yayin_yili'])): ?>
                                <p class="book-year">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo htmlspecialchars($book['yayin_yili']); ?>
                                </p>
                            <?php endif; ?>
                            <?php if(!empty($book['aciklama'])): ?>
                                <p class="book-description">
                                    <?php echo htmlspecialchars(substr($book['aciklama'], 0, 100)) . (strlen($book['aciklama']) > 100 ? '...' : ''); ?>
                                </p>
                            <?php endif; ?>
                            <div class="book-actions">
                                <button class="btn btn-outline btn-sm" onclick="showBookDetails(<?php echo $book['id']; ?>)">
                                    <i class="fas fa-eye"></i>
                                    Detaylar
                                </button>
                                <button class="btn btn-primary btn-sm" onclick="borrowBook(<?php echo $book['id']; ?>)">
                                    <i class="fas fa-bookmark"></i>
                                    Ödünç Al
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Sayfalama -->
        <?php if($totalPages > 1): ?>
            <div class="pagination">
                <?php if($page > 1): ?>
                    <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>" 
                       class="btn btn-outline">
                        <i class="fas fa-chevron-left"></i>
                        Önceki
                    </a>
                <?php endif; ?>

                <span class="page-info">
                    Sayfa <?php echo $page; ?> / <?php echo $totalPages; ?>
                </span>

                <?php if($page < $totalPages): ?>
                    <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>" 
                       class="btn btn-outline">
                        Sonraki
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="modern-footer">
        <div class="footer-content">
            <div class="footer-main">
                <div class="footer-brand">
                    <h3><i class="fas fa-book"></i> Selim Library</h3>
                    <p>İstanbul Medeniyet Üniversitesi'nin dijital kütüphane platformu. Bilgiye erişimi kolaylaştırıyoruz.</p>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Selim Library | İstanbul Medeniyet Üniversitesi. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <script>
        function showBookDetails(bookId) {
            // Kitap detaylarını göster (modal veya yeni sayfa)
            alert('Kitap detayları: ID ' + bookId);
        }

        function borrowBook(bookId) {
            // Ödünç alma işlemi
            if(confirm('Bu kitabı ödünç almak istediğinize emin misiniz?')) {
                alert('Ödünç alma işlemi başlatıldı!');
            }
        }
    </script>
</body>
</html>
