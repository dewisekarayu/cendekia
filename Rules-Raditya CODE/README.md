# Rules-Raditya CODE (Antislop System for AI Agents)

> **Kumpulan Aturan & Panduan Standar AI Coding Agent untuk Mencegah Hasil Generik (*Anti-AI Slop*)**  
> Dibuat & Dikelola oleh: **Raditya Rai Zeeshan**  
> Lisensi: **MIT License** (© 2026 Raditya Rai Zeeshan)

---

## 📌 Tentang Repositori

**Rules-Raditya CODE** adalah kumpulan modul panduan (*skills* dan *rulesets*) terstruktur yang dirancang untuk asisten pemrograman berbasis kecerdasan buatan (AI Coding Agents seperti Claude Code, Antigravity, Cursor, Gemini CLI, Copilot, Windsurf, dan sejenisnya).

Tujuan utama dari sistem ini adalah memfilter dan mencegah kecenderungan **"AI Slop"** yaitu kebiasaan AI menghasilkan kode, tata letak antarmuka (UI), tulisan (*copywriting*), logika arsitektur berlebih (*over-engineering*), dan komentar yang terasa generik, klise, atau tidak mempertimbangkan pengguna nyata. Dengan aturan ini, hasil kerja AI dipandu agar memiliki standar kualitas tinggi setara desainer produk, copywriter, dan software engineer profesional.

---

## 📂 Struktur Direktori & Modul

Berikut adalah ringkasan struktur folder dan fungsi dari masing-masing modul di dalam repositori ini:

```text
Rules-Raditya CODE/
├── agents/
│   └── antislop-critic.md         # Persona Auditor Desain & Arsitektur Independen
├── skills/
│   ├── antislop/                  # Modul Utama (Core Filter, Dials, & Delivery Gate)
│   │   └── SKILL.md
│   ├── antislop-code/             # Aturan Kebersihan Komentar Kode
│   │   └── SKILL.md
│   ├── antislop-logic/            # Kebersihan Logika Kode, Arsitektur & Anti-Bloat
│   │   └── SKILL.md
│   ├── antislop-copywriting/      # Standar Penulisan Teks & Konten Bebas Klise AI
│   │   └── SKILL.md
│   ├── antislop-human/            # Aksesibilitas & Human-Centric UI (+ Tools Kontras)
│   │   ├── SKILL.md
│   │   ├── contrast-check.py
│   │   └── contrast-mcp.py
│   ├── antislop-layoutmobile/     # Desain Responsif & Tata Letak Mobile Reflow
│   │   └── SKILL.md
│   ├── antislop-ui/               # Estetika UI, Warna, Hierarki & Komponen Visual
│   │   └── SKILL.md
│   └── security-audit/            # Audit Keamanan & Kerentanan Kode (Cloudflare AppSec)
│       ├── SKILL.md
│       ├── RECONNAISSANCE.md
│       ├── HUNTING.md
│       ├── VALIDATION-AND-REPORTING.md
│       └── ATTACK-CLASSES.md (+ domain guides & validators)
├── templates/
│   └── DESIGN-TEMPLATE.md         # Template Penentu Arah Desain & Identitas Brand
├── LICENSE                        # Lisensi Resmi MIT oleh Raditya Rai Zeeshan
└── README.md                      # Dokumentasi Utama
```

---

## 🔍 Penjelasan Rinci Setiap Modul

### 1. `skills/antislop/` (Core System)
- **File Utama:** [`SKILL.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/skills/antislop/SKILL.md)
- **Fungsi:** Fondasi utama dari seluruh sistem antislop. Modul ini mendefinisikan filosofi inti, sistem penilaian (*Purpose Test*), 3 tingkatan aturan (Hard Bans, Guardrails, Directional), kalibrasi intensitas (*ENERGY / RHYTHM / MOTION Dials*), serta *Delivery Gate* sebelum pekerjaan diserahkan. Menyediakan panduan inisialisasi (*First-Run Install Wizard*).

### 2. `skills/antislop-code/` (Code Comment Hygiene)
- **File Utama:** [`SKILL.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/skills/antislop-code/SKILL.md)
- **Fungsi:** Menyaring komentar kode agar tetap bersih dan profesional. Menghapus komentar dekoratif seperti banner (`// ====================`), penjelasan berulang yang sudah jelas dari nama variabel, dan komentar bising lainnya, sambil tetap menjaga logika kode asli tanpa mengubah kode eksekusi sedikit pun.

### 3. `skills/antislop-logic/` (Code Logic & Architecture Hygiene)
- **File Utama:** [`SKILL.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/skills/antislop-logic/SKILL.md)
- **Fungsi:** Menjaga kebersihan kode eksekutif dan arsitektur dari kebiasaan buruk AI: mencegah *premature abstraction* & *factory mania* (YAGNI & Rule of Three), membatasi ukuran berkas monolit (*Rule of 300*), melarang *silent error swallowing* (`catch (e) {}`), menyaring ketergantungan library luar yang membengkak (*dependency bloat* saat runtime native sudah memadai), melarang kebocoran *mock data* di jalur produksi, serta menolak tes tautologis. Dilengkapi tabel *Anti-Rationalization* (alibi AI vs sanggahan).

### 4. `skills/antislop-copywriting/` (Copy & Text Hygiene)
- **File Utama:** [`SKILL.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/skills/antislop-copywriting/SKILL.md)
- **Fungsi:** Standar penulisan judul, Call-to-Action (CTA), dan teks landing page yang natural. Melarang keras penggunaan perbendaharaan kata klise khas AI seperti *delve, elevate, empower, seamless, game-changer, robust, landscape, unlock*, serta melarang pembuatan data/fakta/testimoni palsu yang tidak ada dasarnya.

### 5. `skills/antislop-human/` (Accessibility & Human Needs)
- **File Utama:** [`SKILL.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/skills/antislop-human/SKILL.md)
- **Fungsi:** Memastikan antarmuka dapat digunakan oleh semua pengguna nyata dengan berbagai kondisi penglihatan, tangan, dan perangkat. Mengatur kontras warna sesuai standar WCAG AA, navigasi keyboard (fokus visual `:focus-visible`), dan status interaksi elemen.
- **Skrip Pembantu:**
  - [`contrast-check.py`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/skills/antislop-human/contrast-check.py): Skrip CLI mandiri berbasis Python untuk memverifikasi rasio kontras warna secara matematis (teks normal 4.5:1 dan teks besar 3:1).
  - [`contrast-mcp.py`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/skills/antislop-human/contrast-mcp.py): Server standar MCP (*Model Context Protocol*) via stdio agar AI agent dapat memanggil tool pemeriksaan kontras secara otomatis.

### 6. `skills/antislop-layoutmobile/` (Mobile & Responsive Layout)
- **File Utama:** [`SKILL.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/skills/antislop-layoutmobile/SKILL.md)
- **Fungsi:** Menjamin desain mobile bukan sekadar tampilan desktop yang diperkecil (*shrink*), melainkan tata letak yang mengalami *reflow* dengan baik. Mengatur penataan breakpoint berbasis konten, ukuran target sentuhan (*tap targets* min 44x44px), tipografi fluid (`clamp()`), dan pencegahan kebocoran horizontal scroll.

### 7. `skills/antislop-ui/` (Visual & UI Craftsmanship)
- **File Utama:** [`SKILL.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/skills/antislop-ui/SKILL.md)
- **Fungsi:** Mencegah tampilan antarmuka terlihat seperti template generik AI (seperti gradien biru-ke-ungu yang berulang, neon orbs yang tidak bermakna, bento grid tanpa variasi konten, atau sudut membulat berlebihan). Mengatur harmoni warna (maksimal 2–3 warna inti + 1 aksen), hierarki komponen, ritme tata letak, dan animasi/motion yang proporsional.

### 8. `skills/security-audit/` (Defensive Security & Vulnerability Audit)
- **File Utama:** [`SKILL.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/skills/security-audit/SKILL.md)
- **Fungsi:** Sistem audit keamanan defensif berbasis sumber (source-first) terstruktur dari Cloudflare. Menjalankan alur kerja 6 fase (Reconnaissance, Hunting, Candidate Validation via Devil's Advocate, Schema Validation, Independent Record Verification, Reporting) untuk mendeteksi kerentanan autentikasi, DOM XSS, kebocoran data, cloud deployment, dan batas keamanan LLM/agent secara presisi tanpa false positive.

---

### 9. `templates/` (Design Direction Blueprint)
- **File Utama:** [`DESIGN-TEMPLATE.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/templates/DESIGN-TEMPLATE.md)
- **Fungsi:** Memberikan *jiwa dan karakter* bagi proyek agar antislop tidak menghasilkan antarmuka yang hambar (*sterile default*). Berisi template panduan untuk mengisi identitas brand, tema visual, motif permukaan, batasan palet warna, ritme tipografi, dan kalibrasi dials intensitas (*ENERGY, RHYTHM, MOTION*).

### 10. `agents/` (Specialist Reviewer Persona)
- **File Utama:** [`antislop-critic.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/agents/antislop-critic.md)
- **Fungsi:** Profil persona evaluator independen (berperan sebagai *Senior Staff UI Designer & Principal Frontend Architect*) untuk menguji hasil kerja agent dengan posisi kritis (*adversarial quality review*) berdasarkan standar ketukangan C-1 hingga C-5 sebelum kode diserahkan kepada pengguna.

---

## 🛠️ Cara Penggunaan Alat Bantu

### Menjalankan WCAG Contrast Checker (CLI)
Skrip Python dapat dijalankan langsung di terminal untuk memeriksa apakah kombinasi warna teks dan latar belakang memenuhi standar WCAG:

```bash
# Format pemakaian: python contrast-check.py "<HexTeks>" "<HexBackground>"
python "D:/Raditya Dokumen/Rules-Raditya CODE/skills/antislop-human/contrast-check.py" "#FFFFFF" "#1A1A1A"

# Menjalankan verifikasi mandiri (self-test terhadap tabel referensi di SKILL.md):
python "D:/Raditya Dokumen/Rules-Raditya CODE/skills/antislop-human/contrast-check.py" --selftest
```

### Mengintegrasikan MCP Contrast Checker ke AI Agent
Tambahkan konfigurasi MCP Server ke dalam toolchain AI Anda (misalnya Claude Desktop / Cursor / Antigravity MCP Config):

```json
{
  "mcpServers": {
    "antislop-contrast": {
      "command": "python",
      "args": ["D:/Raditya Dokumen/Rules-Raditya CODE/skills/antislop-human/contrast-mcp.py"]
    }
  }
}
```

---

## 🚀 Panduan Integrasi ke Proyek Anda

Untuk menerapkan aturan ini ke proyek pengembangan Anda:

1. **Salin atau Referensikan Folder Skill** ke direktori konfigurasi AI proyek Anda (misalnya `.skills/` atau `rules/`).
2. **Siapkan `DESIGN.md`**: Salin [`templates/DESIGN-TEMPLATE.md`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/templates/DESIGN-TEMPLATE.md) ke root proyek Anda sebagai `DESIGN.md` untuk memberikan arah gaya visual dan identitas produk.
3. **Tambahkan Penunjuk di Dokumen Konfigurasi Agent** (seperti `AGENTS.md`, `CLAUDE.md`, atau `.cursorrules`):
   ```markdown
   <!-- antislop:start -->
   ## AI Coding Rules & Quality Standard
   Gunakan aturan dari Rules-Raditya CODE sebelum memodifikasi UI, menulis copy, membuat logika/arsitektur, menulis komentar, atau membangun layout:
   - Modul Utama: `skills/antislop/SKILL.md`
   - Logika & Arsitektur: `skills/antislop-logic/SKILL.md`
   - Desain & Estetika UI: `skills/antislop-ui/SKILL.md`
   - Penulisan & Copy: `skills/antislop-copywriting/SKILL.md`
   - Aksesibilitas & Kontras: `skills/antislop-human/SKILL.md`
   - Layout & Mobile: `skills/antislop-layoutmobile/SKILL.md`
   - Komentar Kode: `skills/antislop-code/SKILL.md`
   <!-- antislop:end -->
   ```
4. Sebelum memulai pekerjaan, AI Agent akan secara otomatis mematuhi *delivery gate* dan checklist di setiap modul terkait.

---

## 📄 Lisensi (License)

Dibuat dan disusun oleh: **Raditya Rai Zeeshan**  
Tahun: **2026**

Proyek ini dilisensikan di bawah ketentuan **MIT License**. Anda bebas menggunakan, memodifikasi, mendistribusikan, dan menggabungkan aturan-aturan ini ke dalam proyek pribadi maupun komersial, dengan tetap menyertakan atribusi hak cipta pencipta aslinya.

Detail lisensi lengkap dapat dilihat pada berkas [`LICENSE`](file:///D:/Raditya%20Dokumen/Rules-Raditya%20CODE/LICENSE).

```text
MIT License

Copyright (c) 2026 Raditya Rai Zeeshan

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
