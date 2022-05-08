## Cara Instalasi Expert System

hal yang dibutuhkan
**1. xampp/lampp
2. composer
3. koneksi internet**

cara instalasi:

1. Pastikan sudah menginstal xampp pada komputer
2.  Download dan install composer ([Cara Instalasi Composer di xampp pada windows](https://www.niagahoster.co.id/blog/cara-install-composer/))
3. aktifkan apache dan mysql pada xampp control panel
4. download file web expert system
5. extract file zip web, lalu rename nama folder `folder awal bernama "expert-system-main", ubah sesuai kebutuhan atau ubah menjadi "expert-system"`
6. copy folder dan pastekan ke htdocs pada xampp
7. buka php myadmin lalu buat database baru lalu pilih *import sql file* pilih `expertsystem-db.sql`  yang terdapat di dalam folder expert-system, tunggu hingga berhasil import
8. masuk kedalam folder expert-system *lalu pada setinggan windows tampilkan hidden files* ubah file `.env.example` menjadi `.env` lalu ubah `username, password, database` yang menyesuaikan pada xampp (*deffault value biasanya hostname => localhost, username dan password => root, sesuaikan database dengan nama database yang telah dibuat*).
9. lalu buka `CMD/terminal` pindahkan ke direktori expert-system pada htdocs xampp lalu `ketik perintah composer install` tunggu hingga selesai.
10. buka browser dan masukan pada url `localhost/expert-system/`
11. instalasi berhasil

-----------------------------------

untuk mengakses halaman admin maka pergi ke url localhost/expert-system/login

username: user
password: 123
