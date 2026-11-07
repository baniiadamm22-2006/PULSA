BIATECH
BUSINESS REQUIREMENT DOCUMENT
Platform Penjualan Produk Digital dan Layanan PPOB

Versi Dokumen: 1.0
Status: Draft kebutuhan bisnis
Tanggal: 3 November 2026
Platform: Web-based application


 INFORMASI DAN KONTROL DOKUMEN
Atribut	Keterangan
Nama proyek	BIATECH
Nama dokumen	Business Requirement Document (BRD)
Versi	1.0
Status	Draft untuk validasi kebutuhan bisnis
Jenis sistem	Website penjualan produk digital / PPOB
Model bisnis	B2C (Business to Consumer)
Platform	Website customer dan Admin Panel
Backend yang direncanakan	Laravel 12
Admin Panel	Filament v3
Frontend	Blade dan Livewire
Database	MariaDB
Infrastruktur	Docker
Payment gateway	Midtrans
Supplier produk digital	Digiflazz melalui akun/kerja sama mitra, sesuai otorisasi

Riwayat Perubahan Dokumen
Versi	Tanggal	Deskripsi	Status
1.0	3 November 2026	Penyusunan awal BRD berdasarkan konsep dan kebutuhan BIATECH	Draft

Daftar Isi
Dokumen ini disusun dengan struktur bab berikut. Untuk membuat daftar isi otomatis di Microsoft Word, gunakan References > Table of Contents setelah membuka dokumen.
•	1. Ringkasan Eksekutif
•	2. Latar Belakang dan Permasalahan Bisnis
•	3. Visi, Misi, dan Tujuan Bisnis
•	4. Ruang Lingkup Proyek
•	5. Stakeholder dan Pengguna Sistem
•	6. Gambaran Solusi dan Arsitektur
•	7. Proses Bisnis (Business Process)
•	8. Kebutuhan Fungsional
•	9. Aturan Bisnis
•	10. Status Pesanan, Pembayaran, dan Transaksi
•	11. Kebutuhan Data dan Database
•	12. Kebutuhan Integrasi
•	13. Kebutuhan Nonfungsional
•	14. Keamanan dan Privasi
•	15. Laporan dan Indikator Keberhasilan
•	16. Risiko dan Mitigasi
•	17. Batasan, Asumsi, dan Ketergantungan
•	18. Tahapan Implementasi
•	19. Kriteria Penerimaan
•	20. Glosarium dan Persetujuan

 1. RINGKASAN EKSEKUTIF
BIATECH merupakan platform bisnis digital berbasis website yang menyediakan layanan penjualan berbagai produk digital, meliputi pulsa reguler, paket data internet, paket data roaming internasional, paket roaming Umroh, dan token listrik prabayar. Platform ini dirancang agar pelanggan dapat membeli kebutuhan digital melalui satu tempat dengan proses yang sederhana, transparan, dan dapat dipantau.
Pelanggan dapat mengakses website tanpa kewajiban membuat akun. Pelanggan memilih kategori, provider, dan produk, memasukkan nomor tujuan atau ID pelanggan, kemudian melakukan pembayaran melalui metode yang tersedia pada payment gateway. Setelah pembayaran terkonfirmasi, sistem meneruskan pesanan ke supplier produk digital yang terintegrasi dan memperbarui status transaksi berdasarkan hasil pemrosesan.
Di sisi operasional, BIATECH menyediakan Admin Panel untuk mengelola kategori, provider, produk, harga, pesanan, pembayaran, transaksi, supplier, laporan, serta aktivitas operasional. Sistem juga direncanakan mendukung notifikasi Telegram untuk membantu pengelola memantau transaksi yang membutuhkan perhatian.
BIATECH diposisikan sebagai platform perdagangan produk digital yang mengintegrasikan katalog, pemesanan, pembayaran elektronik, distribusi produk digital, dan monitoring bisnis. Dokumen ini mendefinisikan kebutuhan bisnis tingkat tinggi sebagai dasar penyusunan SRS, desain database, desain UI/UX, dan implementasi aplikasi.
1.1 Deskripsi Produk
Produk utama BIATECH adalah layanan pembelian produk digital secara online. Nilai utama yang ditawarkan adalah kemudahan akses tanpa registrasi wajib, pilihan produk dalam satu platform, informasi harga sebelum pembayaran, proses transaksi terstruktur, serta pelacakan status pesanan.
1.2 Pernyataan Nilai Bisnis
•	Menyederhanakan pembelian beragam produk digital melalui satu website.
•	Mengurangi pekerjaan operasional manual melalui otomatisasi pemesanan dan integrasi pembayaran.
•	Menyediakan pengelolaan katalog dan harga secara terpusat.
•	Meningkatkan transparansi status pembayaran dan pemrosesan produk.
•	Menyediakan data transaksi yang dapat digunakan untuk evaluasi penjualan dan margin.
2. LATAR BELAKANG DAN PERMASALAHAN BISNIS
Kebutuhan terhadap pulsa, paket internet, dan layanan komunikasi saat bepergian terus menjadi bagian dari aktivitas masyarakat. Pelanggan juga membutuhkan akses yang mudah terhadap paket roaming internasional dan paket khusus perjalanan Umroh. Jika layanan tersebar pada berbagai kanal, pelanggan perlu membandingkan dan berpindah platform untuk menemukan produk yang sesuai.
Dari sisi pengelola bisnis, pengelolaan katalog, harga modal, harga jual, pembayaran, dan status pemrosesan memerlukan pencatatan yang konsisten. Tanpa sistem terpusat, risiko kesalahan harga, keterlambatan pembaruan status, transaksi ganda, serta kesulitan menelusuri kendala dapat meningkat.
2.1 Permasalahan yang Ingin Diselesaikan
•	Belum tersedianya satu kanal terpusat untuk berbagai kategori produk digital yang direncanakan.
•	Proses pembelian perlu dibuat sederhana dan dapat digunakan tanpa registrasi wajib.
•	Pengelola membutuhkan satu panel untuk mengatur kategori, provider, produk, dan harga jual.
•	Status pembayaran dan status pengiriman produk harus dibedakan agar tidak menimbulkan informasi yang keliru.
•	Transaksi yang tertunda atau gagal perlu dapat ditelusuri melalui riwayat dan log.
•	Pengelola membutuhkan ringkasan penjualan dan margin untuk memantau kinerja bisnis.
2.2 Peluang Bisnis
BIATECH berpeluang melayani pelanggan yang membutuhkan pengisian pulsa, paket internet, token listrik, dan konektivitas saat bepergian. Paket roaming dan paket Umroh dapat menjadi kategori pembeda karena membutuhkan informasi negara tujuan, masa aktif, kuota, serta ketentuan penggunaan yang jelas.
3. VISI, MISI, DAN TUJUAN BISNIS
3.1 Visi
Menjadi platform penyedia produk digital yang mudah diakses, terpercaya, dan mampu memberikan pengalaman transaksi praktis bagi masyarakat.
3.2 Misi
•	Menyediakan berbagai kebutuhan produk digital dalam satu platform.
•	Memberikan pengalaman pembelian sederhana tanpa kewajiban registrasi.
•	Menampilkan informasi produk dan harga secara jelas sebelum pembayaran.
•	Mengintegrasikan pembayaran elektronik dan pemrosesan produk digital.
•	Memastikan status transaksi dapat dipantau dan ditelusuri.
•	Membangun sistem operasional yang terpusat, aman, dan mudah dikembangkan.
3.3 Tujuan Bisnis
Kode	Tujuan	Indikator
OBJ-01	Kemudahan transaksi	Pelanggan dapat menyelesaikan pembelian tanpa membuat akun.
OBJ-02	Sentralisasi produk	Kategori dan produk tersedia dalam satu katalog.
OBJ-03	Otomatisasi	Pembayaran dan pengiriman pesanan diproses melalui integrasi.
OBJ-04	Pengelolaan bisnis	Admin dapat mengelola katalog dan harga melalui panel.
OBJ-05	Transparansi	Status pembayaran dan transaksi ditampilkan terpisah.
OBJ-06	Profitabilitas	Sistem menyimpan harga modal dan harga jual untuk menghitung margin.

4. RUANG LINGKUP PROYEK
4.1 Produk dan Layanan
Kategori	Deskripsi Kebutuhan
Pulsa reguler	Pembelian pulsa elektronik untuk operator seluler yang tersedia. Pelanggan memilih operator, nominal, dan nomor telepon tujuan.
Paket data internet	Pembelian paket internet berdasarkan operator, kuota, masa aktif, dan ketentuan produk.
Roaming internasional	Paket internet untuk perjalanan luar negeri dengan pilihan negara atau wilayah, kuota, masa berlaku, dan ketentuan jaringan.
Roaming Umroh	Paket komunikasi dan internet untuk perjalanan Umroh, terutama Arab Saudi serta negara transit jika produk tersedia.
Token listrik PLN	Pembelian token listrik prabayar menggunakan nomor meter atau ID pelanggan.

4.2 Cakupan Fitur
Area	Termasuk dalam ruang lingkup
Customer Website	Homepage, katalog, kategori, provider, detail produk, input tujuan, checkout, pembayaran, pelacakan transaksi, detail transaksi, bantuan.
Admin Panel	Autentikasi, dashboard, CRUD kategori/provider/produk, pengaturan harga, pengelolaan pesanan, monitoring pembayaran dan transaksi, log, laporan, pengaturan.
Integrasi	Midtrans, supplier produk digital, Telegram untuk notifikasi operasional.
Operasional	Monitoring transaksi gagal/pending, pemeriksaan status supplier, rekonsiliasi, dan pemrosesan ulang yang aman.

4.3 Di Luar Ruang Lingkup Tahap Awal
•	Aplikasi native Android dan iOS khusus.
•	Marketplace multi-penjual atau sistem reseller bertingkat.
•	Program loyalitas, poin, dan referral.
•	Dompet digital internal pelanggan.
•	Fitur langganan atau pembayaran berulang.
•	Ekspansi ke produk di luar daftar yang disepakati tanpa perubahan ruang lingkup.
4.4 Hasil Kerja (Deliverables)
•	Dokumen kebutuhan bisnis (BRD) yang telah divalidasi.
•	Dokumen kebutuhan sistem (SRS) dan diagram proses.
•	Desain UI/UX customer website dan Admin Panel.
•	ERD dan rancangan database.
•	Aplikasi Laravel dengan Admin Panel Filament.
•	Integrasi payment gateway dan supplier sesuai akses resmi.
•	Dokumentasi konfigurasi, pengujian, deployment, dan operasional.
5. STAKEHOLDER DAN PENGGUNA SISTEM
Stakeholder	Peran	Kebutuhan / Tanggung Jawab
Pemilik/Pengelola BIATECH	Pemilik bisnis	Menentukan kebijakan bisnis, harga, supplier, layanan, dan target.
Customer	Pelanggan	Mencari produk, membuat pesanan, membayar, dan memantau status.
Administrator	Operator sistem	Mengelola master data, memantau transaksi, dan menangani kendala.
Supplier	Penyedia produk digital	Menyediakan katalog, harga modal, memproses permintaan, dan mengembalikan status.
Payment Gateway	Penyedia pembayaran	Memproses pembayaran dan mengirimkan notifikasi status.
Tim teknis	Pengelola teknologi	Menjaga aplikasi, integrasi, keamanan, backup, dan monitoring.

5.1 Customer
Customer merupakan pengguna umum yang dapat melakukan pembelian tanpa akun. Informasi minimum yang diperlukan bergantung pada produk, misalnya nomor telepon, ID pelanggan, atau negara tujuan. Sistem hanya meminta data yang relevan untuk pemrosesan pesanan.
5.2 Administrator
Administrator mengakses Admin Panel melalui autentikasi. Hak akses perlu dibatasi berdasarkan peran apabila terdapat lebih dari satu jenis administrator. Aktivitas penting seperti perubahan harga, perubahan status, dan pemrosesan ulang harus tercatat.
5.3 Supplier
Supplier adalah sistem eksternal yang menyediakan produk digital. Dalam rencana awal, BIATECH membahas penggunaan akun buyer Digiflazz milik mitra. Implementasi wajib mengikuti izin pemilik akun, ketentuan layanan, mekanisme autentikasi, dan batasan API yang berlaku.
5.4 Payment Gateway
Midtrans berperan sebagai pihak pemroses pembayaran. Sistem BIATECH harus memvalidasi notifikasi pembayaran secara aman dan tidak boleh menganggap pembayaran berhasil hanya berdasarkan halaman redirect di browser pelanggan.
6. GAMBARAN SOLUSI DAN ARSITEKTUR
Solusi BIATECH terdiri atas Customer Website, backend aplikasi, database, Admin Panel, payment gateway, supplier produk digital, dan kanal notifikasi operasional. Backend menjadi pusat pengendali aturan bisnis dan komunikasi antarsistem.
Komponen	Teknologi / Sistem Rencana	Tanggung Jawab
Customer Frontend	Blade + Livewire	Antarmuka katalog, checkout, pembayaran, dan pelacakan.
Backend	Laravel 12	Validasi, aturan bisnis, order, pembayaran, supplier, dan status.
Admin Panel	Filament v3	Administrasi dan monitoring.
Database	MariaDB	Penyimpanan data master, order, payment, transaction, dan log.
Infrastructure	Docker	Lingkungan pengembangan dan deployment yang konsisten.
Payment Gateway	Midtrans	Instruksi pembayaran dan notifikasi pembayaran.
Supplier	Digiflazz melalui akses mitra	Katalog dan pemrosesan produk digital.
Notification	Telegram Bot / Hermes (rencana)	Notifikasi dan perintah operasional terbatas.

6.1 Prinsip Arsitektur
•	Semua aturan transaksi dijalankan di backend, bukan hanya di browser.
•	Integrasi eksternal dipisahkan dalam service/adapter agar mudah diganti atau diuji.
•	Status pembayaran dipisahkan dari status pemrosesan produk.
•	Setiap order memiliki identitas unik dan referensi eksternal yang dapat ditelusuri.
•	Webhook harus diverifikasi dan diproses secara idempotent.
•	Pemrosesan supplier dilakukan secara aman melalui antrean pekerjaan (queue) bila diperlukan.
•	Data sensitif seperti API key disimpan pada konfigurasi rahasia, bukan di source code.
6.2 Integrasi Hermes dan Telegram
Pembahasan sebelumnya mencakup AI bernama Hermes yang berkomunikasi melalui Telegram dan meneruskan perintah ke server mitra. Komponen tersebut dapat dipertimbangkan sebagai alat bantu operasional atau jembatan integrasi khusus. Namun, jalur transaksi utama sebaiknya tetap menggunakan API resmi supplier secara langsung atau melalui service server-to-server yang terdokumentasi. Perintah Telegram tidak boleh menjadi satu-satunya sumber kebenaran status transaksi.
6.3 Gambaran Aliran Data
Customer mengirim permintaan order ke backend. Backend memvalidasi produk, tujuan, dan harga, lalu membuat order. Sistem membuat instruksi pembayaran melalui Midtrans. Setelah notifikasi pembayaran terverifikasi, backend menjadwalkan pemrosesan supplier. Hasil supplier disimpan sebagai status transaksi dan ditampilkan kepada customer serta administrator.
7. PROSES BISNIS (BUSINESS PROCESS)
7.1 Proses Pembelian Customer
Tahap	Aktor	Aktivitas	Output
1	Customer	Membuka website BIATECH.	Halaman utama tampil.
2	Customer	Memilih kategori produk.	Kategori terpilih.
3	Customer	Memilih provider/operator bila relevan.	Provider terpilih.
4	Customer	Memilih produk/nominal/paket.	Produk dan harga tampil.
5	Customer	Memasukkan nomor tujuan atau ID pelanggan.	Data tujuan tersedia.
6	Sistem	Memvalidasi format, status produk, dan harga.	Data valid atau pesan kesalahan.
7	Sistem	Membuat order dan invoice unik.	Order berstatus menunggu pembayaran.
8	Customer	Memilih metode pembayaran dan membayar.	Instruksi pembayaran dijalankan.
9	Midtrans/Sistem	Mengirim dan memverifikasi notifikasi pembayaran.	Payment status diperbarui.
10	Sistem	Mengirim order ke supplier setelah pembayaran terkonfirmasi.	Permintaan supplier tercatat.
11	Supplier	Memproses produk dan mengembalikan respons.	Status supplier diterima.
12	Sistem	Memperbarui status transaksi dan menyimpan log.	Hasil dapat dilihat customer/admin.

7.2 Proses Pengelolaan Produk
1.	Admin login ke Admin Panel.
2.	Admin membuat atau memperbarui kategori.
3.	Admin mengelola provider/operator.
4.	Admin menambahkan produk dan menghubungkannya ke kategori serta provider.
5.	Admin mengisi kode produk supplier, harga modal, harga jual, deskripsi, dan status.
6.	Admin memeriksa data lalu mengaktifkan produk.
7.	Produk aktif ditampilkan pada Customer Website.
7.3 Proses Penanganan Transaksi Bermasalah
•	Sistem mendeteksi transaksi pending terlalu lama, gagal, atau tidak memiliki respons final.
•	Admin membuka detail transaksi dan memeriksa payment status, supplier reference, serta transaction log.
•	Sistem melakukan inquiry/pengecekan status ke supplier jika tersedia.
•	Jika supplier menyatakan transaksi sukses, sistem memperbarui transaksi menjadi success.
•	Jika supplier menyatakan gagal secara final, sistem memperbarui transaksi menjadi failed dan menjalankan prosedur refund sesuai kebijakan.
•	Jika status belum pasti, transaksi tetap ditahan untuk rekonsiliasi dan tidak langsung dikirim ulang.
•	Pemrosesan ulang hanya dapat dilakukan setelah pemeriksaan status dan mekanisme anti-duplikasi.
7.4 Alur Transaksi Tingkat Tinggi
Customer memilih produk → sistem memvalidasi data → order dibuat → Midtrans memproses pembayaran → webhook diverifikasi → supplier menerima permintaan → supplier memproses produk → sistem menyimpan hasil → customer dan admin melihat status.
7.5 Kondisi Pengecualian
Kondisi	Perilaku Sistem yang Diharapkan
Pembayaran belum selesai	Order tetap menunggu pembayaran; supplier tidak dipanggil.
Pembayaran kedaluwarsa	Payment ditandai expired; order tidak diproses.
Webhook terlambat	Sistem melakukan pengecekan status ke gateway dan rekonsiliasi.
Supplier timeout	Transaksi masuk status menunggu/unknown sesuai kebijakan; jangan langsung mengirim ulang tanpa inquiry.
Supplier gagal	Transaksi ditandai gagal setelah respons final dan proses refund ditangani.
Produk nonaktif	Customer tidak dapat membuat order baru untuk produk tersebut.
Harga berubah sebelum checkout	Harga terbaru ditampilkan dan dikonfirmasi sebelum order dibuat.

8. KEBUTUHAN FUNGSIONAL
Prioritas: High = wajib untuk rilis awal; Medium = penting tetapi dapat dijadwalkan setelah fungsi inti stabil; Low = pengembangan lanjutan.
8.1 Customer Website
ID	Fitur	Deskripsi Kebutuhan	Prioritas
FR-001	Homepage	Sistem menampilkan informasi BIATECH, kategori, produk unggulan, dan navigasi.	High
FR-002	Kategori	Sistem menampilkan kategori produk yang aktif.	High
FR-003	Provider	Sistem menampilkan provider yang terkait dengan kategori/produk.	High
FR-004	Katalog Produk	Sistem menampilkan produk aktif beserta harga dan informasi penting.	High
FR-005	Detail Produk	Sistem menampilkan detail, masa aktif, kuota, wilayah, dan ketentuan jika relevan.	High
FR-006	Input Tujuan	Sistem menerima nomor telepon, ID pelanggan, atau data tujuan lain sesuai produk.	High
FR-007	Validasi Tujuan	Sistem memvalidasi format data sebelum order dibuat.	High
FR-008	Checkout	Sistem menampilkan ringkasan produk, tujuan, harga, dan total.	High
FR-009	Pembuatan Order	Sistem membuat invoice/order ID unik.	High
FR-010	Pembayaran	Sistem menyediakan metode pembayaran yang diaktifkan di Midtrans.	High
FR-011	Status Pembayaran	Customer dapat melihat status pembayaran.	High
FR-012	Status Transaksi	Customer dapat melihat status pemrosesan produk.	High
FR-013	Detail Transaksi	Customer dapat melihat invoice, produk, tujuan tersamarkan, waktu, dan hasil.	High
FR-014	Pelacakan Guest	Customer dapat mencari transaksi dengan referensi dan mekanisme verifikasi aman.	High
FR-015	Bantuan	Sistem menyediakan kanal kontak/dukungan pelanggan.	Medium

8.2 Admin Panel
ID	Fitur	Deskripsi Kebutuhan	Prioritas
FR-016	Admin Login	Admin wajib terautentikasi untuk mengakses panel.	High
FR-017	Dashboard	Menampilkan ringkasan transaksi, omzet, status, dan tren.	High
FR-018	Category CRUD	Admin dapat membuat, melihat, mengubah, dan menonaktifkan kategori.	High
FR-019	Provider CRUD	Admin dapat mengelola provider/operator.	High
FR-020	Product CRUD	Admin dapat mengelola produk, kode supplier, detail, dan status.	High
FR-021	Price Management	Admin dapat mengatur harga modal dan harga jual dengan validasi.	High
FR-022	Supplier Management	Admin dapat mengatur data koneksi dan pemetaan supplier sesuai kewenangan.	Medium
FR-023	Order Management	Admin dapat mencari dan melihat seluruh order.	High
FR-024	Payment Monitoring	Admin dapat memeriksa status dan referensi pembayaran.	High
FR-025	Transaction Monitoring	Admin dapat memeriksa status supplier dan hasil transaksi.	High
FR-026	Reprocess Transaction	Admin dapat meminta pemeriksaan/pemrosesan ulang dengan validasi anti-duplikasi.	High
FR-027	Transaction Logs	Admin dapat melihat riwayat perubahan dan respons integrasi.	High
FR-028	Sales Reports	Admin dapat melihat laporan penjualan, modal, dan margin.	Medium
FR-029	User Management	Admin berwenang dapat mengelola akun dan peran administrator.	Medium
FR-030	System Settings	Admin berwenang dapat mengelola konfigurasi non-rahasia.	Medium

8.3 Aturan Detail Fitur
•	Produk harus terhubung ke kategori dan provider yang sesuai.
•	Produk dapat memiliki atribut tambahan seperti negara tujuan, kuota, masa aktif, dan ketentuan penggunaan.
•	Data produk yang telah digunakan pada order harus disimpan sebagai snapshot agar perubahan katalog tidak mengubah histori.
•	Admin tidak boleh mengubah status transaksi sukses menjadi status lain tanpa prosedur koreksi dan audit yang jelas.
•	Fitur reprocess harus memeriksa status terakhir dari supplier dan mencatat identitas admin, alasan, serta waktu tindakan.
•	Customer guest harus memiliki cara aman untuk mengakses detail transaksi tanpa membuka data pelanggan lain.
9. ATURAN BISNIS
ID	Aturan Bisnis
BR-001	Customer dapat melakukan pembelian tanpa registrasi atau login.
BR-002	Hanya produk aktif dan tersedia yang dapat dipesan.
BR-003	Harga jual ditetapkan oleh administrator sesuai kebijakan bisnis.
BR-004	Sistem menyimpan harga modal dan harga jual sebagai dasar perhitungan margin.
BR-005	Setiap order memiliki nomor invoice unik.
BR-006	Supplier hanya dipanggil setelah pembayaran dinyatakan terkonfirmasi, kecuali ada kebijakan bisnis lain yang disetujui.
BR-007	Status pembayaran diverifikasi melalui notifikasi terautentikasi atau inquiry resmi gateway.
BR-008	Status pembayaran dan status transaksi produk disimpan terpisah.
BR-009	Transaksi sukses tidak boleh dikirim ulang.
BR-010	Transaksi dengan status supplier tidak pasti harus diperiksa sebelum retry.
BR-011	Setiap perubahan status dan tindakan administratif penting dicatat.
BR-012	Perubahan harga produk tidak mengubah nilai order yang telah dibuat.
BR-013	Refund mengikuti status aktual pembayaran, supplier, dan kebijakan yang berlaku.
BR-014	Akses API key dan data rahasia dibatasi kepada komponen server yang berwenang.

9.1 Kebijakan Harga
Harga jual ditetapkan berdasarkan harga modal supplier ditambah margin yang ditentukan pengelola. Sistem perlu menyimpan nilai harga modal dan harga jual pada saat order dibuat sebagai snapshot.
Keuntungan kotor per item = harga jual − harga modal. Keuntungan bersih perlu memperhitungkan biaya payment gateway, biaya supplier, pajak, biaya operasional, refund, dan biaya lain yang relevan.
9.2 Contoh Perhitungan
Komponen	Nilai Contoh
Harga modal supplier	Rp10.000
Harga jual BIATECH	Rp12.000
Margin kotor	Rp2.000
Biaya gateway/operasional	Dihitung sesuai biaya aktual
Keuntungan bersih	Margin kotor dikurangi seluruh biaya terkait

10. STATUS PESANAN, PEMBAYARAN, DAN TRANSAKSI
BIATECH wajib memisahkan status order, status payment, dan status pemrosesan supplier. Ketiganya merepresentasikan tahapan yang berbeda.
10.1 Payment Status
Status	Definisi
Pending	Pembayaran belum terkonfirmasi.
Paid	Pembayaran telah diverifikasi berhasil.
Failed	Pembayaran gagal menurut gateway.
Expired	Batas waktu pembayaran telah berakhir.
Refunded	Dana telah dikembalikan sesuai proses refund.

10.2 Transaction Status
Status	Definisi
Pending	Menunggu pemrosesan setelah pembayaran valid.
Processing	Permintaan sedang diproses oleh supplier.
Success	Supplier mengonfirmasi produk berhasil dikirim/diproses.
Failed	Supplier mengonfirmasi kegagalan final.
Unknown / Review	Status belum dapat dipastikan dan membutuhkan inquiry/rekonsiliasi.
Refunded	Pengembalian dana untuk transaksi terkait telah diselesaikan.

10.3 Order Status
Status	Definisi
Awaiting Payment	Order dibuat dan menunggu pembayaran.
Paid	Pembayaran terkonfirmasi.
Processing	Order sedang diproses.
Completed	Produk berhasil diproses.
Cancelled	Order dibatalkan sesuai aturan.
Needs Review	Order membutuhkan pemeriksaan manual.

10.4 Matriks Contoh
Payment Status	Transaction Status	Makna
Pending	Pending	Belum dibayar; supplier belum diproses.
Paid	Processing	Pembayaran berhasil; supplier sedang memproses.
Paid	Success	Pembayaran dan pengiriman produk berhasil.
Paid	Failed	Pembayaran berhasil, tetapi supplier gagal memproses.
Paid	Unknown / Review	Pembayaran berhasil, hasil supplier belum pasti.
Refunded	Refunded	Dana telah dikembalikan dan kasus ditutup.

Status refunded tidak boleh dipasang hanya karena transaksi supplier gagal. Sistem harus memastikan proses pengembalian dana benar-benar dilakukan dan tercatat.
11. KEBUTUHAN DATA DAN DATABASE
Database direncanakan menggunakan MariaDB dengan model relasional. Struktur final akan ditetapkan pada tahap desain ERD dan SRS. Daftar berikut merupakan entitas konseptual, bukan migrasi database final.
11.1 Master Data
Tabel	Atribut Konseptual	Fungsi
users	id, name, email, password, role/status	Akun administrator.
categories	id, name, slug, description, status	Kategori produk.
providers	id, name, code, logo, status	Operator atau penyedia produk.
products	id, category_id, provider_id, name, code, description, modal_price, selling_price, status	Katalog produk.
suppliers	id, name, code, configuration reference, status	Data supplier.
supplier_products	id, supplier_id, product_id, supplier_product_code, supplier_price, status	Pemetaan produk lokal dan produk supplier.

11.2 Data Transaksi
Tabel	Atribut Konseptual	Fungsi
orders	id, invoice_number, customer_contact, total_amount, order_status, created_at	Header pesanan.
order_items	id, order_id, product_id, product_name_snapshot, destination_snapshot, quantity, modal_price_snapshot, selling_price_snapshot	Detail pesanan.
payments	id, order_id, gateway, gateway_reference, payment_method, amount, payment_status	Informasi pembayaran.
transactions	id, order_id, supplier_id, supplier_reference, destination, transaction_status, request/response reference	Pemrosesan produk digital.
transaction_logs	id, transaction_id, event, status, message, response reference, created_at	Riwayat transaksi.
payment_webhooks	id, gateway_event_id, payload reference, signature_valid, processed_at	Audit notifikasi gateway.
refunds	id, payment_id, amount, status, reference, reason	Pengembalian dana.
admin_activity_logs	id, user_id, action, subject, before/after reference, created_at	Audit aktivitas admin.
settings	id, key, value/reference	Konfigurasi aplikasi.

11.3 Relasi Konseptual
•	Satu category dapat memiliki banyak products (1:N).
•	Satu provider dapat memiliki banyak products (1:N).
•	Satu order memiliki satu atau lebih order_items (1:N).
•	Satu order dapat memiliki satu atau lebih payment attempt sesuai rancangan gateway (1:N).
•	Satu order dapat memiliki satu atau lebih transaction attempt/logis sesuai model pemrosesan (1:N).
•	Satu transaction memiliki banyak transaction_logs (1:N).
•	Satu supplier dapat memiliki banyak supplier_products (1:N).
•	Satu product dapat dipetakan ke supplier product melalui supplier_products.
11.4 Prinsip Pengelolaan Data
•	Gunakan foreign key dan constraint untuk menjaga integritas relasi.
•	Gunakan decimal dengan presisi yang sesuai untuk nilai uang; hindari float untuk perhitungan finansial.
•	Simpan snapshot nama produk, tujuan, harga modal, harga jual, dan deskripsi penting saat order dibuat.
•	Simpan referensi eksternal supplier dan payment gateway secara unik bila memungkinkan.
•	Simpan payload integrasi secara aman; rahasiakan token, signature, dan data sensitif.
•	Terapkan kebijakan retensi data transaksi dan log sesuai kebutuhan bisnis serta regulasi.
12. KEBUTUHAN INTEGRASI
12.1 Midtrans
Midtrans direncanakan sebagai payment gateway. Integrasi mencakup pembuatan transaksi pembayaran, penyajian instruksi/metode pembayaran yang tersedia, penerimaan notifikasi, verifikasi signature, pemeriksaan status, dan pencatatan referensi pembayaran.
•	Gunakan dokumentasi resmi dan environment sandbox sebelum production.
•	Validasi signature notifikasi pada backend.
•	Jangan mempercayai parameter status yang dikirim langsung dari browser.
•	Pastikan event webhook diproses idempotent agar notifikasi berulang tidak membuat pembayaran atau order diproses dua kali.
•	Lakukan rekonsiliasi jika notifikasi tidak diterima atau status tidak konsisten.
Referensi: https://midtrans.com/ dan dokumentasi teknis resmi Midtrans.
12.2 Digiflazz / Supplier
Supplier produk digital direncanakan menggunakan Digiflazz melalui akses akun mitra, sesuai persetujuan pemilik akun dan ketentuan penggunaan. Integrasi perlu mencakup katalog, harga modal, pemesanan, inquiry status, saldo, dan referensi transaksi sejauh didukung oleh API yang diotorisasi.
•	Konfirmasi hak penggunaan akun dan API kepada pemilik akun mitra.
•	Gunakan kredensial API yang disimpan sebagai secret di server.
•	Petakan kode produk BIATECH ke kode produk supplier.
•	Catat request ID, supplier reference, respons, dan waktu setiap pemanggilan.
•	Gunakan inquiry sebelum retry ketika hasil transaksi tidak pasti.
•	Jangan mengirim ulang transaksi hanya karena terjadi timeout.
Referensi: https://digiflazz.com/ dan dokumentasi API resmi yang diberikan kepada pemegang akun.
12.3 Telegram dan Hermes
Telegram dapat digunakan untuk notifikasi operasional, seperti transaksi gagal, transaksi pending berkepanjangan, atau gangguan supplier. Rencana Hermes sebagai agen/perantara perintah dapat dievaluasi setelah jalur transaksi utama stabil.
•	Batasi ID pengguna/grup Telegram yang dapat menjalankan perintah.
•	Gunakan autentikasi dan otorisasi untuk setiap perintah operasional.
•	Catat siapa yang menjalankan perintah, parameter, waktu, dan hasilnya.
•	Perintah retry harus menjalankan validasi status dan idempotency yang sama dengan Admin Panel.
•	Jangan menaruh API key atau data pelanggan sensitif pada pesan Telegram.
13. KEBUTUHAN NONFUNGSIONAL
ID	Aspek	Kebutuhan
NFR-001	Performance	Halaman utama dan katalog dioptimalkan agar responsif; target awal halaman utama di bawah 3 detik pada kondisi pengujian yang disepakati.
NFR-002	Security	Password di-hash; akses admin dilindungi autentikasi dan otorisasi.
NFR-003	Availability	Layanan dipantau dan memiliki prosedur pemulihan saat terjadi gangguan.
NFR-004	Reliability	Transaksi dan perubahan status dicatat sehingga dapat ditelusuri.
NFR-005	Data Integrity	Relasi dan nilai transaksi dijaga dengan constraint dan transaksi database.
NFR-006	Scalability	Aplikasi mendukung peningkatan trafik dan volume transaksi.
NFR-007	Maintainability	Kode dipisahkan ke modul/service yang mudah diuji dan dikembangkan.
NFR-008	Responsive Design	Customer website dapat digunakan pada ponsel, tablet, dan desktop.
NFR-009	Auditability	Perubahan harga, status, dan tindakan admin penting memiliki audit log.
NFR-010	Backup & Recovery	Database dan konfigurasi penting memiliki backup serta prosedur pemulihan.
NFR-011	Compatibility	Aplikasi mendukung browser modern yang masih mendapat pembaruan keamanan.
NFR-012	Observability	Error aplikasi dan integrasi dicatat serta dapat dipantau.

Target performa, availability, dan kapasitas harus divalidasi melalui pengujian pada lingkungan yang mewakili kondisi produksi. Angka target dalam dokumen ini merupakan sasaran awal, bukan jaminan performa.
14. KEAMANAN DAN PRIVASI
•	Seluruh komunikasi produksi menggunakan HTTPS.
•	Gunakan proteksi CSRF, validasi input, output escaping, dan query parameterized/ORM.
•	Terapkan rate limiting pada endpoint sensitif, checkout, login, dan pelacakan order.
•	API key, secret, dan kredensial database tidak boleh disimpan dalam repository.
•	Webhook Midtrans diverifikasi sebelum mengubah status pembayaran.
•	Admin Panel menggunakan role/permission dan prinsip least privilege.
•	Nomor telepon dan ID pelanggan ditampilkan secara tersamarkan pada layar yang tidak memerlukan data penuh.
•	Data pelanggan hanya digunakan untuk pemrosesan order dan layanan yang relevan.
•	Log tidak boleh menyimpan kredensial atau data rahasia secara tidak perlu.
•	Backup dienkripsi dan aksesnya dibatasi.
•	Perubahan harga dan tindakan manual terhadap transaksi harus dapat diaudit.
14.1 Akses Guest
Karena customer tidak diwajibkan login, fitur pelacakan transaksi harus memakai invoice/reference dan faktor verifikasi tambahan yang aman, misalnya kode verifikasi yang dikirim ke kontak transaksi atau token akses acak yang sulit ditebak. Nomor invoice saja tidak boleh menjadi satu-satunya perlindungan untuk menampilkan data sensitif.
15. LAPORAN DAN INDIKATOR KEBERHASILAN
15.1 Laporan Admin
•	Ringkasan jumlah order berdasarkan periode.
•	Jumlah transaksi success, processing, pending, failed, dan needs review.
•	Total nilai penjualan/omzet.
•	Total harga modal produk terjual.
•	Margin kotor dan estimasi keuntungan setelah biaya.
•	Produk dan kategori dengan penjualan tertinggi.
•	Riwayat transaksi bermasalah.
•	Ringkasan refund dan biaya pembayaran jika datanya tersedia.
15.2 Indikator Keberhasilan
Indikator	Target Awal / Kriteria
Pencatatan order	Seluruh order yang berhasil dibuat memiliki ID unik dan tercatat.
Konsistensi pembayaran	Status payment hanya berubah berdasarkan verifikasi gateway atau proses rekonsiliasi.
Pencegahan duplikasi	Tidak ada pengiriman ulang otomatis tanpa pemeriksaan status dan idempotency.
Keterlacakan	Setiap transaksi dapat ditelusuri melalui invoice dan referensi supplier.
Akurasi harga	Nilai pada order sesuai snapshot harga saat checkout.
Operasional admin	Admin dapat mengelola produk dan memantau transaksi melalui panel.
Respons halaman	Target awal halaman utama di bawah 3 detik pada skenario pengujian yang ditetapkan.
Ketersediaan data	Riwayat perubahan status dan integrasi tersimpan sesuai kebijakan retensi.

15.3 Definisi Metrik Finansial
Omzet adalah nilai penjualan yang tercatat sesuai definisi laporan bisnis. Margin kotor adalah harga jual dikurangi harga modal produk. Keuntungan bersih harus mengurangi biaya payment gateway, biaya supplier tambahan, pajak, biaya operasional, refund, dan komponen biaya lain. Laporan harus menjelaskan apakah angka yang ditampilkan merupakan gross atau net.
16. RISIKO DAN MITIGASI
Risiko	Dampak	Mitigasi
Supplier tidak tersedia	Pesanan tertunda/gagal.	Monitoring, inquiry status, dan prosedur fallback.
Saldo supplier habis	Order tidak dapat diproses.	Pantau saldo dan tetapkan batas minimum operasional.
Webhook tidak diterima	Status pembayaran tidak sinkron.	Retry webhook, inquiry gateway, dan rekonsiliasi.
Supplier timeout	Hasil transaksi tidak pasti.	Tandai review/unknown dan lakukan inquiry sebelum retry.
Transaksi ganda	Kerugian finansial atau produk terkirim dua kali.	Idempotency key, unique reference, dan kontrol retry.
Nomor tujuan salah	Produk dikirim ke tujuan yang salah.	Validasi format dan konfirmasi ringkasan sebelum bayar.
Harga modal berubah	Margin berkurang atau negatif.	Sinkronisasi harga, snapshot harga, dan aturan margin minimum.
Kredensial bocor	Penyalahgunaan akun/API.	Secret management, rotasi key, dan pembatasan akses.
Refund tidak sinkron	Kerugian dan komplain pelanggan.	Alur refund terpisah, rekonsiliasi, dan audit.
Akun supplier milik mitra	Ketergantungan dan risiko akses.	Perjanjian kerja sama, otorisasi, dan rencana transisi.

17. BATASAN, ASUMSI, DAN KETERGANTUNGAN
17.1 Batasan
•	Ketersediaan produk mengikuti katalog dan layanan supplier.
•	Harga modal dapat berubah mengikuti supplier.
•	Kecepatan pemrosesan bergantung pada jaringan dan sistem eksternal.
•	Metode pembayaran mengikuti aktivasi dan kebijakan akun Midtrans.
•	Keberhasilan produk bergantung pada validitas tujuan dan dukungan operator/penyedia.
•	Refund mengikuti ketentuan gateway, supplier, dan kebijakan bisnis.
•	Penggunaan akun supplier milik mitra memerlukan izin dan kesepakatan yang jelas.
17.2 Asumsi
•	BIATECH beroperasi sebagai penjual/penyedia layanan kepada pelanggan akhir.
•	Customer website mendukung guest checkout.
•	Admin memiliki akses resmi ke Admin Panel.
•	Midtrans digunakan sebagai payment gateway utama pada rilis awal.
•	Supplier menyediakan akses API yang sah untuk proses katalog dan transaksi.
•	Pengelola menentukan harga jual dan kebijakan margin.
17.3 Ketergantungan
•	Ketersediaan akun merchant dan konfigurasi Midtrans.
•	Ketersediaan akun supplier dan otorisasi API.
•	Ketersediaan domain, hosting/server, SSL, dan infrastruktur Docker.
•	Ketersediaan informasi produk, harga, dan ketentuan supplier.
•	Keputusan bisnis mengenai refund, pembatalan, masa berlaku order, dan dukungan pelanggan.
18. TAHAPAN IMPLEMENTASI
Tahap	Aktivitas	Output
1. Discovery & Validasi	Validasi proses bisnis, produk, supplier, pembayaran, dan kebijakan.	BRD disetujui.
2. Analisis Sistem	Menyusun SRS, use case, activity diagram, dan aturan status.	SRS dan diagram.
3. UI/UX	Mendesain customer website dan Admin Panel.	Prototype dan desain final.
4. Database Design	Menyusun ERD, skema, constraint, dan snapshot transaksi.	ERD dan migration plan.
5. Project Setup	Menyiapkan Laravel, Filament, MariaDB, Docker, dan environment.	Fondasi aplikasi.
6. Master Data	Membangun kategori, provider, produk, supplier mapping, dan harga.	Admin master data.
7. Customer Flow	Membangun katalog, input tujuan, checkout, dan invoice.	Alur pembelian.
8. Payment Integration	Integrasi Midtrans sandbox, webhook, dan rekonsiliasi.	Pembayaran teruji.
9. Supplier Integration	Integrasi katalog, pembelian, inquiry, dan status supplier.	Pemrosesan produk.
10. Monitoring & Reporting	Dashboard, logs, penanganan transaksi, dan laporan.	Fitur operasional.
11. Testing	Unit, feature, integration, security, dan UAT.	Laporan pengujian.
12. Deployment	Konfigurasi production, backup, monitoring, dan go-live.	Sistem siap operasi.

18.1 Strategi Rilis
•	Rilis internal: master data dan simulasi transaksi pada sandbox.
•	Rilis terbatas: transaksi dengan nilai kecil dan pengawasan admin.
•	Rilis produksi: dibuka setelah webhook, rekonsiliasi, retry, refund, dan monitoring teruji.
•	Evaluasi pascarilis: tinjau tingkat keberhasilan, waktu proses, komplain, biaya, dan margin.
19. KRITERIA PENERIMAAN (ACCEPTANCE CRITERIA)
No.	Skenario Uji	Hasil yang Diharapkan
1	Customer membuka website	Homepage dapat diakses dan tampil responsif.
2	Customer memilih kategori	Produk dalam kategori yang aktif ditampilkan.
3	Customer memilih provider/produk	Informasi produk dan harga ditampilkan.
4	Customer memasukkan tujuan tidak valid	Sistem menolak dan menampilkan pesan yang jelas.
5	Customer melakukan checkout	Order dan invoice unik dibuat.
6	Customer belum membayar	Supplier belum menerima permintaan transaksi.
7	Pembayaran berhasil	Webhook terverifikasi dan payment status menjadi paid.
8	Notifikasi pembayaran dikirim berulang	Sistem tidak membuat pemrosesan ganda.
9	Supplier mengonfirmasi sukses	Transaction status menjadi success.
10	Supplier mengonfirmasi gagal	Transaction status menjadi failed dan prosedur penanganan dijalankan.
11	Supplier timeout	Sistem tidak langsung mengirim ulang tanpa pemeriksaan.
12	Admin mengubah harga produk	Harga katalog berubah, histori order lama tetap.
13	Admin membuka detail transaksi	Informasi order, payment, supplier, dan log dapat ditelusuri.
14	Admin menjalankan reprocess	Sistem melakukan pemeriksaan anti-duplikasi dan mencatat tindakan.
15	Admin membuka dashboard	Ringkasan dan filter periode tampil sesuai data.
16	Guest melacak transaksi	Hanya transaksi yang dapat diverifikasi yang ditampilkan.

19.1 User Acceptance Testing (UAT)
UAT dilakukan oleh pemilik/pengelola bisnis atau perwakilan operasional. Setiap skenario diberi status Pass/Fail, catatan, bukti uji, dan pihak yang menyetujui. Sistem tidak dinyatakan siap produksi jika terdapat kegagalan kritis pada pembayaran, pencatatan transaksi, pencegahan duplikasi, atau keamanan akses.
20. GLOSARIUM DAN PERSETUJUAN
20.1 Glosarium
Istilah	Definisi
PPOB	Payment Point Online Bank; istilah umum untuk layanan pembayaran/pembelian produk digital dan tagihan.
B2C	Business to Consumer, model bisnis yang melayani konsumen akhir.
Guest Checkout	Pembelian tanpa kewajiban membuat akun atau login.
Provider	Operator atau penyedia layanan/produk.
Supplier	Pihak/sistem yang menyediakan dan memproses produk digital.
Payment Gateway	Sistem pihak ketiga yang memproses pembayaran.
Webhook	Notifikasi server-to-server dari layanan eksternal ke aplikasi.
Idempotency	Prinsip agar permintaan yang sama tidak menghasilkan efek transaksi ganda.
Reconciliation	Proses mencocokkan status dan catatan transaksi antara sistem.
Snapshot	Salinan nilai penting pada saat transaksi dibuat agar histori tidak berubah.
Margin Kotor	Selisih harga jual dan harga modal sebelum biaya lain.

20.2 Keputusan yang Masih Memerlukan Konfirmasi
No.	Keputusan	Dampak
1	Apakah integrasi supplier dilakukan langsung ke API Digiflazz menggunakan akses mitra atau melalui server Hermes?	Menentukan arsitektur integrasi, keamanan, dan tanggung jawab operasional.
2	Apakah semua produk wajib dibayar terlebih dahulu sebelum supplier dipanggil?	Menentukan alur order dan risiko saldo.
3	Apakah customer tetap sepenuhnya guest atau disediakan akun opsional?	Menentukan kebutuhan autentikasi customer dan histori akun.
4	Bagaimana kebijakan refund ketika pembayaran berhasil tetapi supplier gagal?	Menentukan proses keuangan dan layanan pelanggan.
5	Apakah harga supplier disinkronkan otomatis atau diperbarui manual?	Menentukan katalog, margin, dan risiko perubahan harga.
6	Apa batas waktu pembayaran dan batas waktu transaksi pending?	Menentukan expiry, monitoring, dan eskalasi.
7	Metode pembayaran Midtrans apa saja yang akan diaktifkan?	Menentukan pengalaman checkout dan biaya.
8	Apakah perlu multi-admin dengan role berbeda?	Menentukan permission dan audit.

20.3 Persetujuan Dokumen
Dokumen BRD ini menjadi dasar kebutuhan bisnis BIATECH. Persetujuan dokumen menandakan bahwa ruang lingkup, proses bisnis, kebutuhan utama, dan batasan telah ditinjau. Perubahan setelah persetujuan harus dicatat sebagai perubahan kebutuhan (change request).
Pihak	Nama	Tanda Tangan	Tanggal
Pemilik/Pengelola BIATECH			
Perwakilan Operasional			
Perwakilan Teknis			

Akhir Dokumen
