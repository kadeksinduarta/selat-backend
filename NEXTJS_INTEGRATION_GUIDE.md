# Contoh Penggunaan API di Next.js

## 1. Setup API Helper

Salin file `NEXTJS_API_HELPER.js` ke project Next.js Anda:
```
/lib/api.js
```

## 2. Konfigurasi Environment Variable

Buat file `.env.local` di root project Next.js:
```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

## 3. Update User List Page

Ganti `getServerSideProps` di halaman user list:

```javascript
import { getUsers } from "@/lib/api";

export async function getServerSideProps(context) {
    try {
        // Ambil token dari cookies atau session
        const token = context.req.cookies.admin_token;
        
        if (!token) {
            return {
                redirect: {
                    destination: '/login',
                    permanent: false,
                }
            };
        }

        const response = await fetch('http://localhost:8000/api/users', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const data = await response.json();
        
        return { 
            props: { 
                users: data.data || [] 
            } 
        };
    } catch (error) {
        console.error('Error fetching users:', error);
        return { 
            props: { 
                users: [] 
            } 
        };
    }
}
```

## 4. Update Client-Side Functions

Update fungsi delete dan create di component:

```javascript
import { deleteUser, createUser, updateUser } from "@/lib/api";

const handleDelete = async (id) => {
    if (confirm("Apakah Anda yakin ingin menghapus user ini?")) {
        try {
            await deleteUser(id);
            router.reload();
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }
};
```

## 5. Format Data Response

Backend mengembalikan data dalam format:
```json
{
  "success": true,
  "data": [...]
}
```

Jadi pastikan mengakses `response.data` untuk mendapatkan array users/admins.

## 6. Mapping Field Names

Backend menggunakan field:
- `created_at` → untuk "Tanggal Bergabung"
- `last_login` → untuk "Login Terakhir" (admin)
- `phone` → untuk "Telepon"

Update mapping di frontend sesuai kebutuhan.

## 7. Status Field

Backend tidak mengembalikan field `status`. Jika Anda memerlukan status, ada 2 opsi:

**Opsi A:** Tambahkan field `status` di migration dan model
**Opsi B:** Gunakan logic di frontend:
```javascript
const status = user.email_verified_at ? "Active" : "Inactive";
```

## 8. Testing Checklist

- [ ] Login admin berhasil dan token tersimpan
- [ ] List users tampil dari API
- [ ] Create user berfungsi
- [ ] Update user berfungsi
- [ ] Delete user berfungsi
- [ ] List admins tampil dari API
- [ ] Create admin berfungsi
- [ ] Update admin berfungsi
- [ ] Delete admin berfungsi (dengan validasi admin terakhir)
