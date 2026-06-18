<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Penghuni — ManageMyKos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-main: #243447; --bg-card: #2E4159;
            --accent: #FF8C32; --text-white: #F5F7FA; --text-muted: #B8C2CC;
        }
        body { background: var(--bg-main); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .login-card { background: var(--bg-card); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 420px; }
        .form-mk { background: transparent; border: 1px solid rgba(255,255,255,0.12); border-radius: 10px; padding: .75rem 1rem; color: var(--text-white); font-size: .95rem; width: 100%; transition: border-color .2s; }
        .form-mk:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(255,140,50,0.15); background: transparent; }
        .form-mk::placeholder { color: rgba(255,255,255,0.2); }
    </style>
</head>
<body>
<div class="login-card">

    {{-- Logo --}}
    <div style="text-align:center; margin-bottom:2rem;">
        <div style="width:56px; height:56px; background:rgba(255,140,50,0.15);
                    border-radius:14px; display:flex; align-items:center;
                    justify-content:center; margin:0 auto 12px;">
            <i class="bi bi-building" style="font-size:1.6rem; color:var(--accent);"></i>
        </div>
        <h4 style="color:var(--text-white); font-weight:700; margin-bottom:4px;">
            Portal Penghuni
        </h4>
        <p style="color:var(--text-muted); font-size:.88rem; margin:0;">
            ManageMyKos
        </p>
    </div>

    {{-- Error --}}
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.2);
                border-radius:10px; padding:10px 14px; margin-bottom:1.25rem;
                color:#f87171; font-size:.85rem;">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ $errors->first() }}
    </div>
    @endif

    @if(session('warning'))
    <div style="background:rgba(234,179,8,0.12); border:1px solid rgba(234,179,8,0.2);
                border-radius:10px; padding:10px 14px; margin-bottom:1.25rem;
                color:#fbbf24; font-size:.85rem;">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('warning') }}
    </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('tenant.login.post') }}">
        @csrf

        <div style="margin-bottom:1rem;">
            <label style="color:var(--text-muted); font-size:.82rem; display:block; margin-bottom:6px;">
                Nomor HP
            </label>
            <div style="position:relative;">
                <i class="bi bi-phone" style="position:absolute; left:14px; top:50%;
                                               transform:translateY(-50%); color:var(--text-muted);"></i>
                <input type="text" name="phone" class="form-mk"
                       value="{{ old('phone') }}"
                       placeholder="cth: 08123456789"
                       style="padding-left:42px;" required autofocus>
            </div>
        </div>

        <div style="margin-bottom:1.5rem;">
            <label style="color:var(--text-muted); font-size:.82rem; display:block; margin-bottom:6px;">
                Password
            </label>
            <div style="position:relative;">
                <i class="bi bi-lock" style="position:absolute; left:14px; top:50%;
                                              transform:translateY(-50%); color:var(--text-muted);"></i>
                <input type="password" name="password" id="passwordInput" class="form-mk"
                       placeholder="Masukkan password"
                       style="padding-left:42px; padding-right:42px;" required>
                <i class="bi bi-eye" id="togglePassword"
                   style="position:absolute; right:14px; top:50%; transform:translateY(-50%);
                          color:var(--text-muted); cursor:pointer;"
                   onclick="togglePass()"></i>
            </div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <div style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="remember" id="remember"
                    style="accent-color:var(--accent); width:16px; height:16px;">
                <label for="remember"
                    style="color:var(--text-muted); font-size:.85rem; cursor:pointer;">
                    Ingat saya
                </label>
            </div>

            <a href="https://wa.me/6289507107368?text=Halo%20Admin%20ManageMyKos,%20saya%20lupa%20password.%0A%0ATolong%20lengkapi%20data%20diri%20di%20bawah%20ini:%0A%0ANIK%20KTP%20:%20%0ANo%20HP%20:%20%0ANama%20Lengkap%20:%20"
                target="_blank"
                style="
                        color: var(--accent);
                        font-size: .85rem;
                        font-weight: 500;
                        text-decoration: none;
                        transition: opacity .2s;
                "
                onmouseover="this.style.opacity='.8'"
                onmouseout="this.style.opacity='1'">
                    <i class="bi bi-key me-1"></i>Lupa Password?
            </a>
        </div>

        <button type="submit"
                style="width:100%; background:var(--accent); color:#fff; border:none;
                       border-radius:10px; padding:13px; font-weight:600; font-size:.95rem;
                       cursor:pointer; transition:opacity .2s;"
                onmouseover="this.style.opacity='.85'"
                onmouseout="this.style.opacity='1'">
            <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
        </button>

    </form>

    <div style="text-align:center; margin-top:1.5rem;">
        <a href="{{ url('/') }}"
           style="color:var(--text-muted); font-size:.82rem; text-decoration:none;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
        </a>
    </div>

</div>

<script>
    function togglePass() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('togglePassword');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
</body>
</html>