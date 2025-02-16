Artistudio Popup
Artistudio Popup adalah plugin WordPress untuk menampilkan pop-up dinamis yang dapat dikonfigurasi melalui Custom Post Type. Plugin ini juga menyediakan API REST untuk mengambil data popup dan mendukung frontend berbasis React.

Fitur
✅ Custom Post Type "Popup" untuk mengelola pop-up dari dashboard WordPress
✅ API REST untuk mengambil data pop-up
✅ React & React-DOM otomatis dimuat jika belum tersedia
✅ Localized script untuk komunikasi frontend-backend
✅ Validasi login, hanya pengguna yang sudah login dapat mengakses API

Instalasi
1. Unduh & Ekstrak plugin ke folder:
wp-content/plugins/artistudio-popup/

2. Aktifkan Plugin melalui menu Plugins di WordPress

3. Flush Permalinks:
 *Buka Settings > Permalinks
 *Klik Save Changes

4. Pastikan tema memiliki wp_footer() agar popup dapat ditampilkan

*Struktur Plugin
artistudio-popup/
│── assets/                   # Berisi frontend React
│── includes/
│   ├── class-popup-api.php    # API REST untuk popup
│── artistudio-popup.php       # File utama plugin
│── readme.txt                 # Dokumentasi plugin

Endpoint API
🔹 GET /wp-json/artistudio/v1/popup → Mengambil daftar popup
🔹 API hanya dapat diakses oleh pengguna yang sudah login

Konfigurasi
*Tampilan Popup:
Plugin akan menambahkan elemen berikut di footer:
<div id="artistudio-popup-root"></div>

*React Frontend:
Pastikan folder assets/popup-frontend berisi file hasil build React