<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk - RoboMath</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --ink:#0f172a; --muted:#64748b; --line:#e8e2d9;
  --cream:#fbf6ef; --white:#fff;
  --red:#ff3b30; --blue:#3478f6; --orange:#ff9500; --green:#10b981; --purple:#7c3aed;
}
*{box-sizing:border-box;margin:0}
html,body{min-height:100%}
body{
  font-family:'Inter',system-ui,sans-serif; color:var(--ink);
  background:
    radial-gradient(60% 50% at 30% 10%, #fff1d6 0%, transparent 70%),
    radial-gradient(50% 50% at 85% 90%, #ffe4e1 0%, transparent 70%),
    var(--cream);
  display:flex; align-items:center; justify-content:center; padding:24px;
  overflow-x:hidden;
}
.shell{
  width:100%; max-width:1040px; display:grid; grid-template-columns:1.05fr 1fr; gap:28px; align-items:stretch;
}

/* Left: playful panel */
.hero{display:flex;flex-direction:column;justify-content:center;gap:24px;padding:8px 8px 8px 0;position:relative}

/* Official Logo Brand */
.logo-wrap{
  display:inline-flex; align-items:center; text-decoration:none; width:fit-content;
  transition:transform .25s cubic-bezier(0.34,1.56,0.64,1);
}
.logo-wrap:hover{transform:scale(1.05) rotate(-1deg)}
.brand-logo{
  height:42px; width:auto; display:block; filter:drop-shadow(0 2px 8px rgba(0,0,0,.06));
}

/* Playful Multicolored Headline */
.hero-title{
  font-family:'Poppins',sans-serif; font-weight:800;
  font-size:clamp(32px, 4.4vw, 50px); line-height:1.14; letter-spacing:-.035em;
  color:var(--ink); user-select:none;
}
.hero-lead{
  color:#0f172a; display:inline-block; margin-bottom:2px;
}
.word-group{
  display:inline-block; white-space:nowrap;
}
.char{
  display:inline-block; cursor:default;
  transition:transform 0.24s cubic-bezier(0.34,1.56,0.64,1), filter 0.22s ease;
  will-change:transform;
}
.char:hover{
  transform:translateY(-9px) scale(1.22) rotate(var(--r, 3deg));
  filter:drop-shadow(0 8px 14px rgba(0,0,0,0.18));
}

.hero p{color:var(--muted);line-height:1.6;max-width:420px}
.tiles{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;max-width:440px}
.tile{
  aspect-ratio:1;border-radius:26px;color:#fff;display:grid;place-items:center;
  font-family:'Poppins',sans-serif;font-weight:800;font-size:44px;
  box-shadow:0 14px 28px -12px rgba(0,0,0,.28);
}
.tile{
  --rot:0deg; --y:0px; transform:translateY(var(--y)) rotate(var(--rot));
  animation:pop .6s cubic-bezier(.34,1.56,.64,1) both, float 4s ease-in-out infinite;
  animation-delay:var(--d,0s), calc(var(--d,0s) + .6s);
}
.tile.r{background:var(--red);--rot:-5deg;--d:.1s}
.tile.b{background:var(--blue);--rot:3deg;--y:10px;--d:.25s}
.tile.o{background:var(--orange);--rot:-2deg;--d:.4s}
@keyframes pop{from{opacity:0;transform:translateY(24px) scale(.6) rotate(calc(var(--rot) - 12deg))}
  to{opacity:1;transform:translateY(var(--y)) scale(1) rotate(var(--rot))}}
@keyframes float{0%,100%{transform:translateY(var(--y)) rotate(var(--rot))}
  50%{transform:translateY(calc(var(--y) - 8px)) rotate(calc(var(--rot) + 2deg))}}
.tile small{display:block;font:600 12px 'Inter',sans-serif;opacity:.9;text-align:center;margin-top:-6px}

/* Right: form card */
.card{
  background:rgba(255,255,255,.9); border:1px solid #fff; border-radius:32px; padding:44px 40px;
  box-shadow:0 30px 60px -28px rgba(15,23,42,.25), 0 0 0 1px var(--line);
}
.card h2{font-family:'Poppins',sans-serif;font-weight:800;font-size:32px;letter-spacing:-.03em}
.card .sub{color:var(--muted);margin:6px 0 28px;font-size:15px}
.field{margin-bottom:18px}
.row{display:flex;justify-content:space-between;align-items:baseline}
label{font-weight:600;font-size:13px}
.input{position:relative;margin-top:8px}
input[type=email],input[type=password],input[type=text]{
  width:100%;height:54px;border-radius:999px;border:1.5px solid var(--line);background:#fff;
  padding:0 22px;font:500 15px 'Inter',sans-serif;color:var(--ink);transition:border-color .15s, box-shadow .15s;
}
input::placeholder{color:#a3acb9}
input:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 4px rgba(52,120,246,.15)}
.input.err input{border-color:var(--red)}
.toggle{position:absolute;right:8px;top:7px;height:40px;padding:0 14px;border:0;border-radius:999px;background:transparent;color:var(--muted);font:600 13px 'Inter',sans-serif;cursor:pointer}
.toggle:hover{background:#f4f1ea}
.toggle:focus-visible{outline:3px solid var(--blue)}
.msg{color:#d92d20;font-size:13px;margin:8px 0 0 6px}
.remember{display:flex;align-items:center;gap:10px;font-size:14px;color:var(--muted);margin:4px 0 24px;cursor:pointer}
.remember input{width:20px;height:20px;accent-color:var(--red);border-radius:6px}
.btn{
  width:100%;height:56px;border:0;border-radius:999px;color:#fff;cursor:pointer;
  font:700 16px 'Poppins',sans-serif;letter-spacing:-.01em;
  background:linear-gradient(135deg,var(--red),#ff6a3d);
  box-shadow:0 14px 24px -10px rgba(255,59,48,.6);transition:transform .15s, box-shadow .15s;
}
.btn:hover{transform:translateY(-2px);box-shadow:0 18px 28px -10px rgba(255,59,48,.65)}
.btn:active{transform:translateY(0)}
.btn:focus-visible{outline:4px solid rgba(52,120,246,.4);outline-offset:3px}
.back{display:block;text-align:center;margin-top:22px;font-size:14px;font-weight:600;color:var(--muted);text-decoration:none}
.back:hover{color:var(--ink)}
.alert{background:#fff1f0;border:1px solid #ffd3cf;color:#b42318;border-radius:18px;padding:12px 16px;font-size:14px;margin-bottom:18px}

/* After Effects / Cursor Sparkle Particles */
.cursor-particle {
  position: fixed;
  pointer-events: none;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  animation: particleFade 0.65s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
  z-index: 99999;
}
@keyframes particleFade {
  0% {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
  }
  100% {
    opacity: 0;
    transform: translate(calc(-50% + var(--dx, 0px)), calc(-50% + var(--dy, -24px))) scale(0.2);
  }
}

@media (max-width:860px){
  .shell{grid-template-columns:1fr;max-width:460px}
  .hero{gap:16px;padding:0;align-items:center;text-align:center}
  .hero-title,.hero p,.tiles{display:none}
  .logo-wrap{margin:0 auto}
  .brand-logo{height:36px}
  .card{padding:32px 24px;border-radius:28px}
}
@media (prefers-reduced-motion:reduce){*{transition:none!important;animation:none!important}.cursor-particle{display:none!important}}
</style>
</head>
<body>
<main class="shell">
 
  <section class="hero" aria-hidden="false">
    <!-- Logo RoboMath Kita -->
    <a class="logo-wrap" href="{{ url('/') }}" aria-label="RoboMath">
      <img src="{{ asset('images/logo.png') }}" alt="RoboMath Logo" class="brand-logo">
    </a>

    <!-- Headline Warna-Warni Sesuai Desain & Interaktif -->
    <h1 class="hero-title">
      <span class="hero-lead">Yuk, lanjutkan</span><br>
      <span class="word-group">
        <span class="char" style="color:#FF3B30; --r:-4deg;">p</span><span class="char" style="color:#FF5E1A; --r:4deg;">e</span><span class="char" style="color:#FF8500; --r:-3deg;">t</span><span class="char" style="color:#FF9500; --r:3deg;">u</span><span class="char" style="color:#FFA600; --r:-4deg;">a</span><span class="char" style="color:#FFB700; --r:4deg;">l</span><span class="char" style="color:#E5B800; --r:-3deg;">a</span><span class="char" style="color:#C6B800; --r:3deg;">n</span><span class="char" style="color:#8CC61E; --r:-4deg;">g</span><span class="char" style="color:#10B981; --r:4deg;">a</span><span class="char" style="color:#10B981; --r:-3deg;">n</span>
      </span><br>
      <span class="word-group">
        <span class="char" style="color:#10B981; --r:-4deg;">m</span><span class="char" style="color:#0EA57A; --r:4deg;">a</span><span class="char" style="color:#0B9B74; --r:-3deg;">t</span><span class="char" style="color:#00A88F; --r:3deg;">e</span><span class="char" style="color:#0299B8; --r:-4deg;">m</span><span class="char" style="color:#0284C7; --r:4deg;">a</span><span class="char" style="color:#1D78F5; --r:-3deg;">t</span><span class="char" style="color:#3478F6; --r:3deg;">i</span><span class="char" style="color:#4F62ED; --r:-4deg;">k</span><span class="char" style="color:#6D4FE6; --r:4deg;">a</span><span class="char" style="color:#7C3AED; --r:-3deg;">m</span><span class="char" style="color:#7C3AED; --r:3deg;">u</span><span class="char" style="color:#7C3AED; --r:-4deg;">!</span>
      </span>
    </h1>

    <p>Kumpulkan poin, selesaikan tantangan hari ini, dan raih prestasi terbaikmu.</p>

    <div class="tiles" aria-hidden="true">
      <div class="tile r">+</div>
      <div class="tile b">&times;</div>
      <div class="tile o">&divide;</div>
    </div>
  </section>
 
  <section class="card">
    <h2>Selamat datang!</h2>
    <p class="sub">Masuk untuk lanjutkan belajar matematika.</p>
 
    @if (isset($errors) && $errors->any())
      <div class="alert" role="alert">{{ $errors->first() }}</div>
    @endif
 
    <form method="POST" action="{{ route('login') }}">
      @csrf
 
      <div class="field">
        <label for="email">Alamat email</label>
        <div class="input @error('email') err @enderror">
          <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" autocomplete="email" required autofocus>
        </div>
      </div>
 
      <div class="field">
        <div class="row">
          <label for="password">Kata sandi</label>
        </div>
        <div class="input">
          <input id="password" type="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
          <button type="button" class="toggle" id="toggle" aria-label="Tampilkan kata sandi">Lihat</button>
        </div>
      </div>
 
      <label class="remember">
        <input type="checkbox" name="remember"> Ingat saya
      </label>
 
      <button type="submit" class="btn">Masuk sekarang</button>

      <a class="back" href="{{ url('/') }}">Kembali ke beranda</a>
    </form>
 
  </section>
 
</main>

<script>
  // Toggle password visibility
  const pw = document.getElementById('password'), tg = document.getElementById('toggle');
  if (tg && pw) {
    tg.addEventListener('click', () => {
      const show = pw.type === 'password';
      pw.type = show ? 'text' : 'password';
      tg.textContent = show ? 'Sembunyi' : 'Lihat';
      tg.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    });
  }

  // After Effects / Playful Cursor Particle Trail Effect
  const colors = ['#FF3B30', '#FF9500', '#FFCC00', '#10B981', '#00A88F', '#3478F6', '#7C3AED', '#EC4899'];
  let lastX = 0, lastY = 0, lastTime = 0;

  window.addEventListener('mousemove', (e) => {
    const now = Date.now();
    const dist = Math.hypot(e.clientX - lastX, e.clientY - lastY);
    if (now - lastTime > 35 && dist > 8) {
      lastTime = now;
      lastX = e.clientX;
      lastY = e.clientY;
      createParticle(e.clientX, e.clientY);
    }
  });

  function createParticle(x, y) {
    const p = document.createElement('div');
    p.className = 'cursor-particle';
    const size = Math.floor(Math.random() * 6) + 5; // 5px - 10px
    const color = colors[Math.floor(Math.random() * colors.length)];
    const dx = (Math.random() - 0.5) * 36;
    const dy = - (Math.random() * 24 + 12);

    p.style.width = size + 'px';
    p.style.height = size + 'px';
    p.style.background = color;
    p.style.left = x + 'px';
    p.style.top = y + 'px';
    p.style.setProperty('--dx', dx + 'px');
    p.style.setProperty('--dy', dy + 'px');

    document.body.appendChild(p);
    setTimeout(() => { p.remove(); }, 650);
  }
</script>
</body>
</html>
