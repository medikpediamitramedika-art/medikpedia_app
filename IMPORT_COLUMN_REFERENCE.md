# Import Column Reference Guide

## Quick Reference

### For Obat Biasa & Resep (AdminMedicineImportController)
**Template Columns:**
```
nama_obat | pabrik | komposisi | indikasi | golongan | retail | stok
```

**Column Details:**
- `nama_obat` - Nama lengkap obat (wajib)
- `pabrik` - Nama perusahaan farmasi (wajib)
- `komposisi` - Komposisi/kandungan obat (wajib)
- `indikasi` - Kegunaan/indikasi obat (wajib)
- `golongan` - BEBAS atau KERAS (wajib) → determines `is_resep` flag
- `retail` - Harga retail (angka saja, tanpa Rp) (wajib)
- `stok` - Jumlah stok (angka saja) (wajib)

**Database Mapping:**
```
nama_obat  → nama_obat
pabrik     → kategori
komposisi  → deskripsi
indikasi   → (stored in deskripsi)
golongan   → is_resep (KERAS=true, BEBAS=false)
retail     → harga
stok       → stok
```

---

### For Produk Resep (AdminPrescriptionImportController)
**Template Columns:**
```
nama_obat | pabrik | komposisi | indikasi | retail | stok
```

**Column Details:**
- `nama_obat` - Nama lengkap obat (wajib)
- `pabrik` - Nama perusahaan farmasi (wajib)
- `komposisi` - Komposisi/kandungan obat (wajib)
- `indikasi` - Kegunaan/indikasi obat (wajib)
- `retail` - Harga retail (angka saja, tanpa Rp) (wajib)
- `stok` - Jumlah stok (angka saja) (wajib)

**Database Mapping:**
```
nama_obat  → nama_obat
pabrik     → kategori
komposisi  → deskripsi
indikasi   → (stored in deskripsi)
retail     → harga
stok       → stok
is_resep   → true (auto, semua produk resep)
```

---

### For Produk Resep Khusus (AdminPrescriptionProductImportController)
**Template Columns:**
```
nama_obat | pabrik | komposisi | indikasi | retail | stok
```

**Column Details:**
- `nama_obat` - Nama lengkap obat (wajib)
- `pabrik` - Nama perusahaan farmasi (wajib)
- `komposisi` - Komposisi/kandungan obat (wajib)
- `indikasi` - Kegunaan/indikasi obat (wajib)
- `retail` - Harga retail (angka saja, tanpa Rp) (wajib)
- `stok` - Jumlah stok (angka saja) (wajib)

**Database Mapping:**
```
nama_obat  → nama_obat
pabrik     → kategori
komposisi  → deskripsi
indikasi   → (stored in deskripsi)
retail     → harga
stok       → stok
is_resep   → true (auto, semua produk resep)
```

---

## Important Notes

1. **Kolom Wajib**: Semua kolom yang ditandai "wajib" harus diisi, tidak boleh kosong
2. **Format Angka**: Kolom `retail` dan `stok` harus berupa angka saja (tanpa simbol atau teks)
3. **Golongan**: Hanya untuk import Obat Biasa & Resep, gunakan "BEBAS" atau "KERAS"
4. **is_resep**: Untuk import Produk Resep, `is_resep` otomatis diset ke `true`
5. **Indikasi**: Disimpan bersama dengan komposisi di field `deskripsi`

## File Format Support

- ✅ CSV (Comma Separated Values)
- ✅ XLS (Excel XML SpreadsheetML)
- ✅ XLSX (Excel) - Harus disimpan sebagai CSV terlebih dahulu

## Error Handling

Jika ada error saat import:
1. Periksa format file (harus CSV atau XLS)
2. Pastikan semua kolom wajib terisi
3. Pastikan format angka benar (tanpa Rp, koma, atau simbol lain)
4. Pastikan tidak ada baris kosong di tengah data
5. Gunakan template yang sudah disediakan sebagai referensi

## Download Template

- **Obat Biasa & Resep**: `/admin/medicines/import/template`
- **Produk Resep**: `/admin/prescriptions/products/import/template`
- **Produk Resep (Legacy)**: `/admin/prescriptions/import/template`
