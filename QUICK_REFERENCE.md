# Quick Reference - Form CRUD Alignment

## What Changed?

### Before ❌
```
Form had: deskripsi (textarea)
Database stored: deskripsi = "Paracetamol 500 mg Demam & nyeri"
```

### After ✅
```
Form has: komposisi (text) + indikasi (text) + golongan (dropdown)
Database stores: deskripsi = "Paracetamol 500 mg | Demam & nyeri"
                 is_resep = true/false (based on golongan)
```

---

## Form Fields

### Obat (Medicines) - 6 Fields
| Field | Type | Excel Column | Database Field |
|-------|------|--------------|----------------|
| Nama Obat | Text | NAMA PRODUK | nama_obat |
| Pabrik | Dropdown | PABRIK | kategori |
| Retail | Number | RETAIL | harga |
| Stok | Number | - | stok |
| Komposisi | Text | KOMPOSISI | deskripsi (part 1) |
| Indikasi | Text | INDIKASI | deskripsi (part 2) |
| Golongan | Dropdown | GOLONGAN | is_resep |
| Gambar | File | - | gambar |

### Produk Resep (Prescription Products) - 5 Fields
| Field | Type | Excel Column | Database Field |
|-------|------|--------------|----------------|
| Nama Produk | Text | NAMA PRODUK | nama_obat |
| Pabrik | Dropdown | PABRIK | kategori |
| Retail | Number | RETAIL | harga |
| Stok | Number | - | stok |
| Komposisi | Text | KOMPOSISI | deskripsi (part 1) |
| Indikasi | Text | INDIKASI | deskripsi (part 2) |
| Gambar | File | - | gambar |

---

## Data Transformation

### On Save
```
komposisi: "Paracetamol 500 mg"
indikasi: "Demam & nyeri"
golongan: "BEBAS"
    ↓
deskripsi: "Paracetamol 500 mg | Demam & nyeri"
is_resep: false
```

### On Edit
```
deskripsi: "Paracetamol 500 mg | Demam & nyeri"
is_resep: false
    ↓
komposisi: "Paracetamol 500 mg"
indikasi: "Demam & nyeri"
golongan: "BEBAS"
```

---

## Golongan to is_resep Mapping

| Golongan | is_resep | Type |
|----------|----------|------|
| BEBAS | false | Obat Biasa |
| KERAS | true | Obat Resep |
| (auto) | true | Produk Resep |

---

## Files Modified

| File | Change | Status |
|------|--------|--------|
| AdminMedicineController.php | Updated store/update methods | ✅ |
| AdminPrescriptionProductController.php | Updated store/update methods | ✅ |
| medicines/create.blade.php | Already correct | ✅ |
| medicines/edit.blade.php | Rewritten with new fields | ✅ |
| prescriptions/products/create.blade.php | Updated with new fields | ✅ |
| prescriptions/products/edit.blade.php | Updated with new fields | ✅ |

---

## Testing Checklist

- [ ] Create Obat Biasa (BEBAS)
- [ ] Create Obat Resep (KERAS)
- [ ] Edit Obat (verify field parsing)
- [ ] Create Produk Resep
- [ ] Edit Produk Resep (verify field parsing)
- [ ] Upload image in create form
- [ ] Upload image in edit form
- [ ] Test form validation (required fields)
- [ ] Test Excel import (6-column template)
- [ ] Test Excel import (5-column template)
- [ ] Verify deskripsi format in database
- [ ] Verify is_resep value in database

---

## Common Issues & Solutions

### Issue: Komposisi/Indikasi not showing in edit form
**Solution**: Check that deskripsi contains " | " separator. If not, manually update the record.

### Issue: Golongan not pre-selected in edit form
**Solution**: Check is_resep value in database. Should be 0 (BEBAS) or 1 (KERAS).

### Issue: Form validation failing
**Solution**: Ensure all required fields are filled. Check validation rules in controller.

### Issue: Image not uploading
**Solution**: Check file size (max 10MB) and format (JPG, PNG, GIF). Check storage permissions.

---

## Routes

```
GET    /admin/medicines                    → index
GET    /admin/medicines/create             → create form
POST   /admin/medicines                    → store
GET    /admin/medicines/{id}/edit          → edit form
PUT    /admin/medicines/{id}               → update
DELETE /admin/medicines/{id}               → delete

GET    /admin/prescriptions/products       → index
GET    /admin/prescriptions/products/create → create form
POST   /admin/prescriptions/products       → store
GET    /admin/prescriptions/products/{id}/edit → edit form
PUT    /admin/prescriptions/products/{id}  → update
DELETE /admin/prescriptions/products/{id}  → delete
```

---

## Database Schema (Relevant Fields)

```sql
medicines table:
- id (primary key)
- nama_obat (string)
- kategori (string)
- harga (numeric)
- stok (integer)
- deskripsi (text) -- stores "komposisi | indikasi"
- is_resep (boolean) -- 0=BEBAS, 1=KERAS
- gambar (string, nullable)
- created_at, updated_at
```

---

## Excel Template Format

### Obat (6 columns)
```
PABRIK | NAMA PRODUK | RETAIL | KOMPOSISI | INDIKASI | GOLONGAN
KIMIA FARMA | Paracetamol 500mg | 5000 | Paracetamol 500 mg | Demam & nyeri | BEBAS
```

### Produk Resep (5 columns)
```
PABRIK | NAMA PRODUK | RETAIL | KOMPOSISI | INDIKASI
KIMIA FARMA | Amoxicillin 500mg | 12000 | Amoxicillin 500 mg | Infeksi bakteri
```

---

## Status: COMPLETE ✅

All forms are now aligned with Excel template format.
Ready for testing and deployment.
