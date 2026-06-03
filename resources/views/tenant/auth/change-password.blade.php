<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password — ManageMyKos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bg-main:#243447; --bg-card:#2E4159; --accent:#FF8C32; --text-white:#F5F7FA; --text-muted:#B8C2CC; }
        body { background:var(--bg-main); min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',sans-serif; }
        .card-mk { background:var(--bg-card); border:1px solid rgba(255,255,255,0.08); border-radius:20px; padding:2.5rem; width:100%; max-width:420px; }
        .form-mk { background:transparent; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:.75rem 1rem; color:var(--text-white); font-size:.95rem; width:100%; transition:border-color .2s; }
        .form-mk:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(255,140,50,0.15); background:transparent; }
        .form-mk::placeholder { color:rgba(255,255,255,0.2); }
    </style>
</head>
<body>
<div class="card-mk">

    <div style="text-align:center; margin-bottom:2rem;">
        <div style="width:56px; height:56px; background:rgba(255,140,50,0.15);
                    border-radius:14px; display:flex; align-items:center;
                    justify-content:center; margin:0 auto 12px;">
            <i class="bi bi-shield-lock" style="font-size:1.6rem; color:var(--accent);"></i>
        </div>
        <h4 style="color:var(--text-white); font-weight:700; margin-bottom:4px;">
            Ganti Password
        </h4>
        <p style="color:var(--text-muted); font-size:.88rem; margin:0;">
            Buat password baru yang aman untuk akunmu.
        </p>
    </div>

    {{-- Alert --}}
    <div style="background:rgba(234,179,8,0.1); border:1px solid rgba(234,179,8,0.2);
                border-radius:10px; padding:10px 14px; margin-bottom:1.25rem; font-size:.82rem;">
        <i class="bi bi-exclamation-triangle me-2" style="color:#fbbf24;"></i>
        <span style="color:#fbbf24;">Kamu menggunakan password sementara. Harap ganti sekarang.</span>
    </div>

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.2);
                border-radius:10px; padding:10px 14px; margin-bottom:1.25rem;
                color:#f87171; font-size:.85rem;">
        @foreach($errors->all() as $error)
            <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('tenant.password.update') }}">
        @csrf

        <div style="margin-bottom:1rem;">
            <label style="color:var(--text-muted); font-size:.82rem; display:block; margin-bottom:6px;">
                Password Baru <span style="color:#f87171;">*</span>
            </label>
            <input type="password" name="password" class="form-mk"
                   placeholder="Min. 6 karakter" required>
        </div>

        <div style="margin-bottom:1.5rem;">
            <label style="color:var(--text-muted); font-size:.82rem; display:block; margin-bottom:6px;">
                Konfirmasi Password <span style="color:#f87171;">*</span>
            </label>
            <input type="password" name="password_confirmation" class="form-mk"
                   placeholder="Ulangi password baru" required>
        </div>

        <button type="submit"
                style="width:100%; background:var(--accent); color:#fff; border:none;
                       border-radius:10px; padding:13px; font-weight:600; font-size:.95rem;
                       cursor:pointer; transition:opacity .2s;"
                onmouseover="this.style.opacity='.85'"
                onmouseout="this.style.opacity='1'">
            <i class="bi bi-check-lg me-2"></i> Simpan Password Baru
        </button>

    </form>
</div>
</body>
</html>