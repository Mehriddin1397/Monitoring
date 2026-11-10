# Kriminologiya Tadqiqot Instituti — Tashqi Tizimlar uchun REST API Qo'llanmasi

Ushbu hujjat **`192.168.40.253`** manzilidagi **"Umumiy statistika va xodimlar boshqaruvi"** tizimini **`192.168.40.104`** manzilidagi **"Mustaqil izlanuvchilar ariza monitoringi"** tizimi bilan API orqali to'liq bog'lash uchun mo'ljallangan.

---

## 1. Umumiy ulanish parametrlari

* **Base URL:**
  * Lokal tarmoq orqali: `http://192.168.40.104/api/v1`
  * Domen orqali: `https://ilm.uzkti.uz/api/v1`
* **Autentifikatsiya:** Bearer Token yoki X-API-KEY sarlavhasi
* **Maxfiy API Token:** `kti_mi_secret_token_2026_xodimlar_kotib_key`
* **So'rov sarlavhalari (Headers):**
  ```http
  Accept: application/json
  Authorization: Bearer kti_mi_secret_token_2026_xodimlar_kotib_key
  ```
  *(yoki `X-API-KEY: kti_mi_secret_token_2026_xodimlar_kotib_key`)*
* **Xavfsizlik (IP Whitelist):** Faqat `192.168.40.253` va ruxsat berilgan tarmoq IP'laridan so'rovlar qabul qilinadi.
* **Xodimni tanlash (ixtiyoriy):** Agar so'rov aniq bir kotib nomidan bajarilishi kerak bo'lsa, quyidagi sarlavhani qo'shish mumkin:
  ```http
  X-Staff-Phone: +998937983107
  ```
  *(ko'rsatilmasa, standart bo'yicha tizimning asosiy Ilmiy kotibi nomidan ish yuritiladi).*

---

## 2. API Endpointlar Ro'yxati

### 1-Qism: Umumiy statistika va Xodimlar boshqaruvi (HR & Analytics)

#### 1.1. Tizimning umumiy ko'rsatkichlari (Dashboard)
Tizimdagi jami izlanuvchilar, loyihalar holati, o'rtacha o'zlashtirish foizi va vazifalar sonini oladi.

* **Metod:** `GET`
* **URL:** `/api/v1/stats/overview`
* **cURL namunasi:**
  ```bash
  curl -X GET "http://192.168.40.104/api/v1/stats/overview" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer kti_mi_secret_token_2026_xodimlar_kotib_key"
  ```
* **Muvaffaqiyatli javob (`200 OK`):**
  ```json
  {
    "success": true,
    "data": {
      "izlanuvchilar": {
        "jami": 119,
        "faol": 119,
        "nofaol": 0,
        "ariza_topshirganlar": 1
      },
      "loyihalar": {
        "jami": 1,
        "jarayonda": 1,
        "yakunlangan": 0,
        "bekor_qilingan": 0,
        "orta_progress_foizi": 0
      },
      "vazifalar": {
        "jami": 25,
        "kutilmoqda": 25,
        "jarayonda": 0,
        "tasdiqlandi": 0,
        "qaytarildi": 0
      },
      "ariza_turlari": [
        {
          "id": 1,
          "name": "Dastlabki muhokama",
          "projects_count": 1
        }
      ]
    }
  }
  ```

---

#### 1.2. Mustaqil izlanuvchilar ro'yxati va ularning o'qish/ariza progressi
Barcha mustaqil izlanuvchilar ro'yxatini, ularning arizalari, qaysi bosqichda turgani va foizini qaytaradi.

* **Metod:** `GET`
* **URL:** `/api/v1/stats/researchers`
* **Parametrlar (Query Params):**
  * `search` (string, ixtiyoriy) — F.I.Sh yoki telefon bo'yicha qidirish
  * `has_project` (int: `1` yoki `0`, ixtiyoriy) — Ariza yuborganlar (`1`) yoki hali yubormaganlar (`0`)
  * `per_page` (int, default: 25) — Har sahifada elementlar soni
  * `page` (int, default: 1) — Sahifa raqami
* **cURL namunasi:**
  ```bash
  curl -X GET "http://192.168.40.104/api/v1/stats/researchers?search=Juraev&per_page=10" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer kti_mi_secret_token_2026_xodimlar_kotib_key"
  ```
* **Muvaffaqiyatli javob (`200 OK`):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 6,
        "full_name": "Juraev Zoxidjon Ashurovich",
        "phone": "+998970698303",
        "is_active": true,
        "created_at": "2026-11-09T12:44:02Z",
        "has_project": true,
        "project": {
          "id": 1,
          "application_type": "Dastlabki muhokama",
          "status": "jarayonda",
          "progress_percent": 12,
          "current_stage": {
            "id": 1,
            "name": "Ariza",
            "order": 1
          },
          "tasks_summary": {
            "approved": 1,
            "total": 8
          },
          "created_at": "2026-09-16T13:02:43Z"
        }
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 10,
      "total": 1
    }
  }
  ```

---

#### 1.3. Xodimlar (Kotiblar) ish yuklamasi va unumdorligi
Xodimlar boshqaruvi tizimi uchun har bir mas'ul xodimga biriktirilgan vazifalar va uning holatini beradi.

* **Metod:** `GET`
* **URL:** `/api/v1/stats/staff-workload`
* **cURL namunasi:**
  ```bash
  curl -X GET "http://192.168.40.104/api/v1/stats/staff-workload" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer kti_mi_secret_token_2026_xodimlar_kotib_key"
  ```
* **Muvaffaqiyatli javob (`200 OK`):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 4,
        "full_name": "Institut Ilmiy kotibi",
        "phone": "+998937983107",
        "roles": ["masul_xodim"],
        "workload": {
          "jami_biriktirilgan": 50,
          "ko_rib_chiqish_kutilmoqda": 4,
          "kutilmoqda": 40,
          "tasdiqlangan": 5,
          "qaytarilgan": 1
        }
      }
    ]
  }
  ```

---

### 2-Qism: Kotib (Mas'ul xodim) operatsiyalari

#### 2.1. Ko'rib chiqilishi kerak bo'lgan vazifalar ro'yxati
Kotib o'z tizimida tekshirishi kerak bo'lgan vazifalar va izlanuvchi yuklagan oxirgi hujjatlar ro'yxati.

* **Metod:** `GET`
* **URL:** `/api/v1/kotib/tasks`
* **Parametrlar:**
  * `status` (string, ixtiyoriy) — `jarayonda` (tekshirish kutilayotgan), `kutilmoqda`, `tasdiqlandi`, `qaytarildi`
  * `search` (string, ixtiyoriy) — Izlanuvchi ismi yoki vazifa nomi
  * `page` (int, default: 1)
* **cURL namunasi:**
  ```bash
  curl -X GET "http://192.168.40.104/api/v1/kotib/tasks?status=jarayonda" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer kti_mi_secret_token_2026_xodimlar_kotib_key"
  ```
* **Muvaffaqiyatli javob (`200 OK`):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 2,
        "name": "Dissertatsiya",
        "description": "Dissertatsiya fayli",
        "status": "jarayonda",
        "approved_at": null,
        "stage": {
          "id": 1,
          "name": "Ariza",
          "order": 1,
          "status": "jarayonda"
        },
        "project": {
          "id": 1,
          "applicant_name": "Raxmatullayev SH",
          "applicant_phone": "+998947400530",
          "application_type": "Dastlabki muhokama",
          "progress_percent": 0,
          "status": "jarayonda"
        },
        "latest_document": {
          "id": 12,
          "file_name": "dissertatsiya_v1.pdf",
          "version": 1,
          "status": "kutilmoqda",
          "uploaded_at": "2026-11-09T14:30:00Z",
          "download_url": "http://192.168.40.104/api/v1/kotib/documents/12/download"
        },
        "updated_at": "2026-11-09T14:30:00Z"
      }
    ]
  }
  ```

---

#### 2.2. Alohida vazifaning batafsil ma'lumoti
Vazifaning to'liq tarixi, oldin yuklangan barcha fayl versiyalari (v1, v2...) va avvalgi izohlar.

* **Metod:** `GET`
* **URL:** `/api/v1/kotib/tasks/{id}`
* **cURL namunasi:**
  ```bash
  curl -X GET "http://192.168.40.104/api/v1/kotib/tasks/2" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer kti_mi_secret_token_2026_xodimlar_kotib_key"
  ```

---

#### 2.3. Hujjat faylini yuklab olish
Izlanuvchi tomonidan yuklangan faylni ochish yoki yuklab olish.

* **Metod:** `GET`
* **URL:** `/api/v1/kotib/documents/{id}/download`
* **cURL namunasi:**
  ```bash
  curl -O -J "http://192.168.40.104/api/v1/kotib/documents/12/download" \
    -H "Authorization: Bearer kti_mi_secret_token_2026_xodimlar_kotib_key"
  ```
* **Javob:** Binary fayl oqimi (PDF / DOCX / JPG).

---

#### 2.4. Hujjatni tasdiqlash (Approve)
Kotib hujjatni ko'rib chiqib qanoatlansa, uni tasdiqlaydi. Tizim progress foizini qayta hisoblaydi va izlanuvchiga avtomatik Eskiz SMS xabarnoma jo'natadi.

* **Metod:** `POST`
* **URL:** `/api/v1/kotib/tasks/{id}/approve`
* **cURL namunasi:**
  ```bash
  curl -X POST "http://192.168.40.104/api/v1/kotib/tasks/2/approve" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer kti_mi_secret_token_2026_xodimlar_kotib_key"
  ```
* **Muvaffaqiyatli javob (`200 OK`):**
  ```json
  {
    "success": true,
    "message": "Vazifa muvaffaqiyatli tasdiqlandi.",
    "data": {
      "task_id": 2,
      "new_task_status": "tasdiqlandi",
      "project_progress_percent": 8,
      "sms_notified": true
    }
  }
  ```

---

#### 2.5. Hujjatni qaytarish / rad etish (Reject)
Hujjatda kamchilik bo'lsa, kotib sababini izoh sifatida yozib qaytaradi. Izlanuvchiga darhol *"Hujjatingiz rad etildi"* mazmunida SMS boradi va u kabinetidan izohni ko'rib yangi versiya yuklaydi.

* **Metod:** `POST`
* **URL:** `/api/v1/kotib/tasks/{id}/reject`
* **Request Body (JSON):**
  ```json
  {
    "comment": "Taqdim etilgan dissertatsiya avtoreferatida ilmiy rahbar imzosi va muhr qo'yilmagan. Iltimos, qayta rasmiylashtirib yuklang."
  }
  ```
* **cURL namunasi:**
  ```bash
  curl -X POST "http://192.168.40.104/api/v1/kotib/tasks/2/reject" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer kti_mi_secret_token_2026_xodimlar_kotib_key" \
    -d '{"comment": "Ilmiy rahbar imzosi qo\u0027yilmagan."}'
  ```
* **Muvaffaqiyatli javob (`200 OK`):**
  ```json
  {
    "success": true,
    "message": "Vazifa rad etildi va izlanuvchiga xabarnoma yuborildi.",
    "data": {
      "task_id": 2,
      "new_task_status": "qaytarildi",
      "comment_saved": "Ilmiy rahbar imzosi qo'yilmagan.",
      "project_progress_percent": 0,
      "sms_notified": true
    }
  }
  ```

---

## 3. Dasturiy ulanish namunalari (Code Examples)

### PHP (GuzzleHttp orqali)
```php
<?php

use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'http://192.168.40.104/api/v1/',
    'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer kti_mi_secret_token_2026_xodimlar_kotib_key',
    ],
    'timeout' => 10,
]);

// 1. Umumiy statistikani olish
$response = $client->get('stats/overview');
$stats = json_decode($response->getBody(), true);
print_r($stats['data']);

// 2. Vazifani tasdiqlash
$response = $client->post('kotib/tasks/2/approve');
$result = json_decode($response->getBody(), true);
```

### JavaScript / Node.js (Axios orqali)
```javascript
const axios = require('axios');

const api = axios.create({
  baseURL: 'http://192.168.40.104/api/v1',
  headers: {
    'Accept': 'application/json',
    'Authorization': 'Bearer kti_mi_secret_token_2026_xodimlar_kotib_key'
  }
});

// Umumiy statistika
async function getOverview() {
  const res = await api.get('/stats/overview');
  console.log(res.data.data);
}

// Vazifani rad etish
async function rejectTask(taskId, commentText) {
  const res = await api.post(`/kotib/tasks/${taskId}/reject`, {
    comment: commentText
  });
  console.log(res.data.message);
}
```

---

## 4. Xatolik kodlari va qaytariladigan xabarlar

| HTTP Kod | Sabab | Izoh |
|:---|:---|:---|
| **`200 OK`** | Muvaffaqiyatli | So'rov muvaffaqiyatli bajarildi |
| **`401 Unauthorized`** | Token xatosi | Token berilmagan yoki noto'g'ri |
| **`403 Forbidden`** | IP taqiqlangan | So'rov ruxsat berilmagan IP dan kelgan |
| **`404 Not Found`** | Topilmadi | Berilgan ID bo'yicha vazifa yoki hujjat topilmadi |
| **`422 Unprocessable`** | Validatsiya xatosi | Hujjat yuklanmagan vazifani tasdiqlab bo'lmaydi yoki izoh matni kiritilmagan |
