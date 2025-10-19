<?php
/**
 * Admin Panel Ana Sayfası
 * Selim Library - İstanbul Medeniyet Üniversitesi
 */

require_once 'config/database.php';

// Session kontrolü
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit;
}

// İstatistikleri al
$totalBooks = fetchOne("SELECT COUNT(*) as total FROM kitaplar")['total'];
$totalUsers = fetchOne("SELECT COUNT(*) as total FROM kullanici")['total'];
$totalCategories = fetchOne("SELECT COUNT(DISTINCT kategori) as total FROM kitaplar WHERE kategori IS NOT NULL")['total'];

// Son eklenen kitapları al
$recentBooks = fetchAll("SELECT * FROM kitaplar ORDER BY eklenme_tarihi DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Paneli | Selim Library</title>
    <link rel="stylesheet" href="genel.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <img src="logo.png" alt="Selim Library Logo" />
            <h1>Selim Library</h1>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">Ana Sayfa</a></li>
                <li><a href="kitaplar.php">Kitaplar</a></li>
                <li><a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    Çıkış
                </a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-profile">
                <div class="profile-avatar">
                    <img src="logo.png" alt="admin" />
                    <div class="status-indicator"></div>
                </div>
                <div class="profile-info">
                    <h3><?php echo htmlspecialchars($_SESSION['name']); ?></h3>
                    <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                    <span class="role-badge">Yönetici</span>
                </div>
            </div>

            <ul class="admin-menu">
                <li class="active">
                    <i class="fas fa-chart-pie"></i>
                    <span>Panoya Genel Bakış</span>
                </li>
                <li onclick="showBookManagement()">
                    <i class="fas fa-book"></i>
                    <span>Kitap Yönetimi</span>
                </li>
                <li onclick="showUserManagement()">
                    <i class="fas fa-users"></i>
                    <span>Kullanıcılar</span>
                </li>
                <li onclick="showAnnouncements()">
                    <i class="fas fa-bullhorn"></i>
                    <span>Duyurular</span>
                </li>
            </ul>
        </aside>

        <section class="admin-main">
            <div class="dashboard-header">
                <div class="header-left">
                    <h2>Yönetici Paneli</h2>
                    <p class="header-subtitle">Kütüphane yönetim sistemi</p>
                </div>
                <div class="header-actions">
                    <button id="refreshData" class="btn btn-primary">
                        <i class="fas fa-sync"></i>
                        Verileri Yenile
                    </button>
                </div>
            </div>

            <!-- İstatistik Kartları -->
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Toplam Kitap</h4>
                        <p id="totalBooks" class="stat-number"><?php echo $totalBooks; ?></p>
                        <span class="stat-label">Kayıtlı kitap sayısı</span>
                    </div>
                </div>
                <div class="stat-card stat-success">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Toplam Kullanıcı</h4>
                        <p id="totalUsers" class="stat-number"><?php echo $totalUsers; ?></p>
                        <span class="stat-label">Aktif kullanıcı sayısı</span>
                    </div>
                </div>
                <div class="stat-card stat-warning">
                    <div class="stat-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Kategoriler</h4>
                        <p id="totalCategories" class="stat-number"><?php echo $totalCategories; ?></p>
                        <span class="stat-label">Farklı kategori sayısı</span>
                    </div>
                </div>
                <div class="stat-card stat-info">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Sistem Durumu</h4>
                        <p class="stat-number">Aktif</p>
                        <span class="stat-label">Tüm sistemler çalışıyor</span>
                    </div>
                </div>
            </div>

            <!-- Kitap Yönetimi -->
            <div id="bookManagement" class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-book"></i>
                        <h3>Kitap Yönetimi</h3>
                    </div>
                    <p class="muted">Yeni kitap ekleyin, düzenleyin veya silin.</p>
                </div>

                <div class="panel-body two-col">
                    <form id="bookForm" class="panel-form">
                        <div class="form-header">
                            <h4><i class="fas fa-plus-circle"></i> Yeni Kitap Ekle</h4>
                        </div>
                        <div class="form-group">
                            <label for="bookTitle">
                                <i class="fas fa-book"></i>
                                Başlık
                            </label>
                            <input type="text" id="bookTitle" placeholder="Kitap başlığı" required />
                        </div>
                        <div class="form-group">
                            <label for="bookAuthor">
                                <i class="fas fa-user"></i>
                                Yazar
                            </label>
                            <input type="text" id="bookAuthor" placeholder="Yazar adı" required />
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="bookYear">
                                    <i class="fas fa-calendar"></i>
                                    Yayın Yılı
                                </label>
                                <input type="number" id="bookYear" placeholder="2024" min="1000" max="2100" />
                            </div>
                            <div class="form-group">
                                <label for="bookCategory">
                                    <i class="fas fa-tags"></i>
                                    Kategori
                                </label>
                                <input type="text" id="bookCategory" placeholder="Bilgisayar" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bookDescription">
                                <i class="fas fa-align-left"></i>
                                Açıklama
                            </label>
                            <textarea id="bookDescription" rows="3" placeholder="Kitap açıklaması..."></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Ekle
                            </button>
                        </div>
                    </form>

                    <div class="table-section">
                        <div class="table-header">
                            <h4><i class="fas fa-list"></i> Kitap Listesi</h4>
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input id="bookSearch" placeholder="Kitap ara..." />
                            </div>
                        </div>
                        <div class="table-container">
                            <table id="booksTable" class="table">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-book"></i> Başlık</th>
                                        <th><i class="fas fa-user"></i> Yazar</th>
                                        <th><i class="fas fa-calendar"></i> Yıl</th>
                                        <th><i class="fas fa-tags"></i> Kategori</th>
                                        <th><i class="fas fa-cog"></i> İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody id="booksTableBody">
                                    <?php foreach($recentBooks as $book): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($book['baslik']); ?></td>
                                        <td><?php echo htmlspecialchars($book['yazar']); ?></td>
                                        <td><?php echo htmlspecialchars($book['yayin_yili'] ?? '—'); ?></td>
                                        <td><?php echo htmlspecialchars($book['kategori'] ?? '—'); ?></td>
                                        <td class="ops">
                                            <button onclick="editBook(<?php echo $book['id']; ?>)" class="btn btn-outline btn-sm">
                                                <i class="fas fa-edit"></i>
                                                Düzenle
                                            </button>
                                            <button onclick="deleteBook(<?php echo $book['id']; ?>)" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                                Sil
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2025 Selim Library | İstanbul Medeniyet Üniversitesi</p>
    </footer>

    <script>
        // Kitap ekleme formu
        document.getElementById('bookForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                title: document.getElementById('bookTitle').value,
                author: document.getElementById('bookAuthor').value,
                year: document.getElementById('bookYear').value,
                category: document.getElementById('bookCategory').value,
                description: document.getElementById('bookDescription').value
            };
            
            fetch('api/admin.php?action=add_book', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Kitap başarıyla eklendi!');
                    loadBooks();
                    document.getElementById('bookForm').reset();
                } else {
                    alert('Hata: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Hata:', error);
                alert('Bağlantı hatası!');
            });
        });
        
        // Kitap silme
        function deleteBook(id) {
            if (!confirm('Bu kitabı silmek istediğinize emin misiniz?')) return;
            
            fetch('api/admin.php?action=delete_book', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({id: id})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Kitap başarıyla silindi!');
                    loadBooks();
                } else {
                    alert('Hata: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Hata:', error);
                alert('Bağlantı hatası!');
            });
        }
        
        // Kitapları yükle
        function loadBooks() {
            fetch('api/admin.php?action=books')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tbody = document.getElementById('booksTableBody');
                    tbody.innerHTML = '';
                    
                    data.data.forEach(book => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${book.baslik}</td>
                            <td>${book.yazar}</td>
                            <td>${book.yayin_yili || '—'}</td>
                            <td>${book.kategori || '—'}</td>
                            <td class="ops">
                                <button onclick="editBook(${book.id})" class="btn btn-outline btn-sm">
                                    <i class="fas fa-edit"></i>
                                    Düzenle
                                </button>
                                <button onclick="deleteBook(${book.id})" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                    Sil
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                }
            })
            .catch(error => {
                console.error('Hata:', error);
            });
        }
        
        // Arama
        document.getElementById('bookSearch').addEventListener('input', function(e) {
            const search = e.target.value;
            fetch(`api/admin.php?action=books&search=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tbody = document.getElementById('booksTableBody');
                    tbody.innerHTML = '';
                    
                    data.data.forEach(book => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${book.baslik}</td>
                            <td>${book.yazar}</td>
                            <td>${book.yayin_yili || '—'}</td>
                            <td>${book.kategori || '—'}</td>
                            <td class="ops">
                                <button onclick="editBook(${book.id})" class="btn btn-outline btn-sm">
                                    <i class="fas fa-edit"></i>
                                    Düzenle
                                </button>
                                <button onclick="deleteBook(${book.id})" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                    Sil
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                }
            });
        });
        
        // Verileri yenile
        document.getElementById('refreshData').addEventListener('click', function() {
            location.reload();
        });
        
        // Kitap düzenleme (basit)
        function editBook(id) {
            alert('Kitap düzenleme özelliği yakında eklenecek! ID: ' + id);
        }
        
        // Menü fonksiyonları
        function showBookManagement() {
            alert('Kitap yönetimi aktif!');
        }
        
        function showUserManagement() {
            alert('Kullanıcı yönetimi yakında eklenecek!');
        }
        
        function showAnnouncements() {
            alert('Duyuru yönetimi yakında eklenecek!');
        }
    </script>
</body>
</html>
