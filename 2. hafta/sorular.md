## Soru-Cevap

### Bölüm 1: Genel İnternet Programlama Soruları

1. İnternetin temel çalışma prensibini kısaca açıklayınız.
	İnternetin temel prensibi veriyi paketler halinde gönderip almak üzerine kuruludur. İnternet veriyi paketler halinde taşır, IP adresleri ile yönlendirir ve protokollerle iletişimi düzenler

2. IP adresi ve DNS arasındaki farkı açıklayınız.
	IP, cihazın ağdaki kimliğidir. DNS, alan adlarını IP ye çevirir ve erişiöi kolaylaştırır

3. TCP ve UDP arasındaki farkları belirtiniz.
	TCP güvenli, sıralı ve hata kontrolü sağlar web sitelerinde çalışır.
	UDP bağlantısız, daha hızlı acak güvensizdir. Yayınlarda çalışır(paket kaybı olması önemsenmez).

4. HTTP protokolü hangi katmanda çalışır ve temel özellikleri nelerdir?
	Uygulama katmanında çalışır, istemci-sunucu tabanlıdır, stateless (durumsuz) yapıdadır.

5. Web tarayıcıları nasıl çalışır? Bir web sayfasını yükleme sürecini adım adım açıklayınız.
	 DNS çözümü → HTTP isteği → Sunucudan HTML, CSS, JS → Tarayıcı DOM oluşturur → Sayfa render edilir.

6. Frontend ve Backend arasındaki fark nedir? Örneklerle açıklayınız.
	Frontend kullanıcın kullandığı ve Backend ile iletişime geçen arayüzdür.
	Backend ise server ile bağlantı kuran kullanıcın görmediği arka planda çalışan ve asıl işi yapan kısımdır

7. JSON ve XML arasındaki farkları açıklayınız.
	JSON sade, hızlı ve okunabilir; XML etiket tabanlı, daha karmaşık yapıdadır.

8. Restful API nedir? Ne amaçla kullanılır?
	HTTP üzerinden GET, POST, PUT, DELETE yöntemleriyle kaynaklara erişim sağlar.

9. Güvenli internet iletişimi için kullanılan HTTPS protokolünün avantajlarını açıklayınız.
	HTTPS, verileri **TLS/SSL protokolleriyle şifreleyerek** iletir ve böylece üçüncü kişiler tarafından okunmasını veya değiştirilmesini önler.

10. Çerezler (Cookies) nedir? Web sitelerinde nasıl kullanılır?
	Web sitelerinin kullanıcı verilerini (oturum, tercih, sepet) sakladığı küçük dosyalardır.

### Bölüm 2: HTML ve CSS Örnek Soruları

1. Aşağıdaki HTML kodunun çıktısını tahmin ediniz:

```html
<!DOCTYPE html>
<html>
<head>
	<title>Örnek Sayfa</title>
</head>
<body>
	<h1>Merhaba Dünya!</h1>
	<p>Bu bir paragraf</p>
	<a href="https://www.google.com">Google a git</a>
</body>
</html>
```

	Merhaba Dünya! 
	Bu bir paragraf
	ver google a gitmek için bir link

2. `<div> ve <span>` etiketleri arasındaki farkı açıklayınız.:  
    `<div>` blok düzeyinde, `<span>` satır içi elemandır.
    
3. HTML’de form elemanlarından en az 5 tanesini açıklayınız.
    `<input>`, `<textarea>`, `<select>`, `<button>`, `<label>`.
    
4. CSS’te ID ve Class seçicilerinin farkı nedir? Örnek kod vererek açıklayınız.  
    `#id` tek elemana uygulanır, `.class` birden fazla elemana uygulanabilir.

5. Aşağıdaki CSS kodu hangi elementlere uygulanır?
	p {
	color: red;
	font-size: 16px;
	}
	Cevap: paragraf

6. HTML5’te yeni gelen en az 3 etiketi açıklayınız.
	`<header>`, `<article>`, `<footer>` – yapıyı semantik hale getirir.

7. CSS Flexbox ile bir div öğesini yatay ve dikey olarak nasıl ortalarsınız?

```css
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
```

8. Responsive web tasarım nedir? Örnek bir CSS media query yazınız.

Web sayfalarının farklı cihaz ve ekran boyutlarına uyum sağlayacak şekilde tasarlanmasıdır.
```css
@media (max-width: 600px) {
  body { font-size: 14px; }
}
```


9. HTML tablolarında satır ve sütunları birleştirmek için hangi etiketler kullanılır?
	`rowspan` satır, `colspan` sütun birleştirir.


10. CSS ile bir butona hover efekti nasıl eklenir? Örnek kod yazınız.

```css
button {
  background-color: blue;
  color: white;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
}

button:hover {
  background-color: darkblue;
}

```

### Bölüm 3: Ağ Protokolleri ile İlgili Sorular

1. HTTP ve HTTPS arasındaki temel farkları açıklayınız.
	HTTP veriyi şifrelemeden iletir, HTTPS ise TLS/SSL ile şifreleyerek güvenli iletişim sağlar.

2. FTP nedir? Hangi amaçlarla kullanılır?
	FTP (File Transfer Protocol), bilgisayarlar arasında dosya transferi yapmak için kullanılan bir protokoldür.

3. SMTP ve POP3 protokolleri arasındaki farkı açıklayınız.
	SMTP, e-posta gönderimi için kullanılırken, POP3 e-postaları sunucudan çekip almayı sağlar; biri gönderim, diğeri alım işlevi görür.

4. DNS nedir? Çalışma mantığını kısaca anlatınız.
	DNS (Domain Name System), alan adlarını IP adreslerine çeviren sistemdir; kullanıcı bir web sitesine girdiğinde, DNS sunucusu doğru IP’ye yönlendirir.

5. DHCP protokolü ne işe yarar?
	DHCP protokolü, ağdaki cihazlara otomatik olarak IP adresi ve diğer ağ ayarlarını atar, manuel konfigürasyonu ortadan kaldırır.

6. HTTP 404 ve HTTP 500 hata kodları ne anlama gelir?
	HTTP 404, istenen sayfanın bulunamadığını; HTTP 500 ise sunucu tarafında bir hata olduğunu gösterir.

7. Telnet ve SSH arasındaki farkı açıklayınız.
	Telnet, veriyi şifrelemeden uzak sunucuya bağlanırken, SSH tüm veriyi şifreleyerek güvenli bağlantı sağlar.

8. VPN nedir ve hangi amaçlarla kullanılır?
	VPN (Virtual Private Network), internet trafiğini şifreleyip kullanıcıyı başka bir ağ üzerinden yönlendirerek güvenlik ve gizlilik sağlar.

9. WebSockets nedir? Nasıl çalışır?
	WebSockets, tarayıcı ile sunucu arasında sürekli açık bir bağlantı sağlayarak çift yönlü veri iletişimi yapar; gerçek zamanlı uygulamalarda kullanılır.

10. CDN (Content Delivery Network) nedir? Web sitelerinde nasıl kullanılır?
	CDN (Content Delivery Network), web sitesi içeriğini coğrafi olarak dağıtılmış sunucularda barındırarak hızlı ve kesintisiz erişim sağlar; özellikle statik içeriklerde hız ve performans artışı sağlar.