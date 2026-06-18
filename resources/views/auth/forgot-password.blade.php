<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — ManageMyKos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-main: #243447; --bg-card: #2E4159;
            --accent: #FF8C32; --text-white: #F5F7FA; --text-muted: #B8C2CC;
        }
        body {
            background: var(--bg-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
        }
        .form-mk {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            padding: .75rem 1rem;
            color: var(--text-white);
            font-size: .95rem;
            width: 100%;
            transition: border-color .2s;
        }
        .form-mk:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255,140,50,0.15);
            background: transparent;
        }
        .form-mk::placeholder { color: rgba(255,255,255,0.2); }
    </style>
</head>
<body>

<div class="login-card">

    {{-- Icon --}}
    <div style="text-align:center; margin-bottom:2rem;">
        <div style="width:56px; height:56px; background:rgba(96,165,250,0.15);
                    border-radius:14px; display:flex; align-items:center;
                    justify-content:center; margin:0 auto 12px;">
            <i class="bi bi-key-fill" style="font-size:1.6rem; color:#60a5fa;"></i>
        </div>
        <h4 style="color:var(--text-white); font-weight:700; margin-bottom:4px;">
            Lupa Password?
        </h4>
        <p style="color:var(--text-muted); font-size:.88rem; margin:0;">
            Admin Panel — ManageMyKos
        </p>
    </div>

    {{-- Deskripsi --}}
    <p style="color:var(--text-muted); font-size:.85rem; line-height:1.7; margin-bottom:1.25rem;">
        Masukkan alamat email akun admin kamu. Kami akan mengirimkan link
        untuk mengatur ulang password ke email tersebut.
    </p>

    {{-- Info penting --}}
    <div style="background:rgba(96,165,250,0.08); border:1px solid rgba(96,165,250,0.2);
                border-radius:10px; padding:.85rem 1rem; margin-bottom:1.25rem;
                display:flex; gap:10px;">
        <i class="bi bi-info-circle-fill" style="color:#60a5fa; flex-shrink:0; margin-top:1px;"></i>
        <p style="color:var(--text-muted); font-size:.8rem; margin:0; line-height:1.6;">
            Link reset password hanya akan dikirim jika email yang dimasukkan
            <strong style="color:#60a5fa;">sudah terdaftar</strong> di sistem sebagai akun admin.
        </p>
    </div>

    {{-- Session Status --}}
    @if(session('status'))
    <div style="background:rgba(34,197,94,0.12); border:1px solid rgba(34,197,94,0.2);
                border-radius:10px; padding:10px 14px; margin-bottom:1.25rem;
                color:#4ade80; font-size:.85rem;">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('status') }}
    </div>
    @endif

    {{-- Error --}}
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.2);
                border-radius:10px; padding:10px 14px; margin-bottom:1.25rem;
                color:#f87171; font-size:.85rem;">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ $errors->first() }}
    </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div style="margin-bottom:1.5rem;">
            <label style="color:var(--text-muted); font-size:.82rem;
                          display:block; margin-bottom:6px;">
                Email Admin
            </label>
            <div style="position:relative;">
                <i class="bi bi-envelope" style="position:absolute; left:14px; top:50%;
                                                  transform:translateY(-50%);
                                                  color:var(--text-muted);"></i>
                <input type="email" name="email" class="form-mk"
                       value="{{ old('email') }}"
                       placeholder="admin@managemykos.com"
                       style="padding-left:42px;"
                       required autofocus>
            </div>
        </div>

        <button type="submit"
                style="width:100%; background:var(--accent); color:#fff; border:none;
                       border-radius:10px; padding:13px; font-weight:600; font-size:.95rem;
                       cursor:pointer; transition:opacity .2s;"
                onmouseover="this.style.opacity='.85'"
                onmouseout="this.style.opacity='1'">
            <i class="bi bi-send-fill me-2"></i> Kirim Link Reset Password
        </button>

    </form>

    {{-- Back --}}
    <div style="text-align:center; margin-top:1.5rem;">
        <a href="{{ route('login') }}"
           style="color:var(--text-muted); font-size:.82rem; text-decoration:none;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
        </a>
    </div>

</div>

</body>
</html>