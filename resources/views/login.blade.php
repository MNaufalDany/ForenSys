<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ForenSys Login</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    .dot-pattern {
      background-image: radial-gradient(#d9deea 0.7px, transparent 0.7px);
      background-size: 22px 22px;
    }
  </style>
</head>

<body class="min-h-screen bg-[#fafbff]">

  <!-- Background -->
  <div class="fixed inset-0 dot-pattern opacity-60"></div>

  <!-- Right Panel -->
  <aside class="hidden xl:flex fixed right-0 top-0 h-full w-[24%] border-l border-[#d8ddeb] bg-[#f7f9ff]/50 items-center justify-center">
    <div class="flex flex-col gap-12 opacity-25">
      <div class="flex flex-col items-center gap-4">
        <span class="material-symbols-outlined text-[70px] text-[#0a2a7a]">analytics</span>
        <div class="w-24 h-1 bg-[#0a2a7a]/30 rounded-full"></div>
      </div>

      <div class="flex flex-col items-center gap-4 opacity-70">
        <span class="material-symbols-outlined text-[70px] text-[#0a2a7a]">dns</span>
        <div class="w-24 h-1 bg-[#0a2a7a]/30 rounded-full"></div>
      </div>

      <div class="flex flex-col items-center gap-4 opacity-50">
        <span class="material-symbols-outlined text-[70px] text-[#0a2a7a]">article</span>
        <div class="w-24 h-1 bg-[#0a2a7a]/30 rounded-full"></div>
      </div>
    </div>
  </aside>

  <!-- Main -->
  <main class="relative z-10 min-h-screen flex items-center justify-center px-4 py-8 xl:pr-[24%]">
    <div class="w-full max-w-[430px]">

      <!-- Logo -->
      <div class="text-center mb-5">
        <div class="mx-auto w-14 h-14 bg-[#082b80] rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/20 mb-3">
          <span class="material-symbols-outlined text-white text-[34px]">biotech</span>
        </div>

        <h1 class="text-[#082b80] text-3xl font-extrabold tracking-tight">
          ForenSys
        </h1>

        <p class="text-[#2f3545] tracking-[0.24em] text-xs mt-1 font-medium">
          MEDICAL EXAMINER PORTAL
        </p>
      </div>

      <!-- Card -->
      <div class="bg-white/95 border border-[#cfd5e5] rounded-2xl shadow-xl shadow-slate-200/70 p-6 backdrop-blur">

        <div class="border-b border-[#cfd5e5] pb-3 mb-5">
          <h2 class="text-xl font-bold text-[#171a23]">
            Authorized Access
          </h2>
          <p class="text-sm text-[#6f7482] font-medium mt-1">
            Enter your institutional credentials
          </p>
        </div>

        <form action="{{ route('login.process') }}" method="POST" class="space-y-4">
            @csrf

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-semibold tracking-wide text-[#303644] mb-2">
              Work Email
            </label>

            <div class="relative">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#747b8c] text-[22px]">
                mail
              </span>

              <input
                id="email"
                name="email"
                type="email"
                placeholder="j.doe@forensys.gov"
                required
                class="w-full h-12 pl-12 pr-4 rounded-lg border border-[#c1c7d6] bg-[#f1f2f7] text-[#20242e] placeholder:text-[#9ba1af] outline-none focus:ring-2 focus:ring-[#082b80] focus:border-[#082b80] transition"
              />
            </div>
          </div>

          <!-- Password -->
          <div>
            <div class="flex justify-between items-center mb-2">
              <label for="password" class="block text-sm font-semibold tracking-wide text-[#303644]">
                Security Password
              </label>

              <a href="#" class="text-sm font-semibold text-[#082b80] hover:underline">
                Forgot?
              </a>
            </div>

            <div class="relative">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#747b8c] text-[22px]">
                lock
              </span>

              <input
                id="password"
                name="password"
                type="password"
                placeholder="••••••••••••"
                required
                class="w-full h-12 pl-12 pr-12 rounded-lg border border-[#c1c7d6] bg-[#f1f2f7] text-[#20242e] placeholder:text-[#9ba1af] outline-none focus:ring-2 focus:ring-[#082b80] focus:border-[#082b80] transition"
              />

              <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#747b8c] hover:text-[#082b80]">
                <span id="eyeIcon" class="material-symbols-outlined text-[22px]">
                  visibility
                </span>
              </button>
            </div>
          </div>

          <!-- Remember -->
          <div class="flex items-center gap-3 pt-1">
            <input
              id="remember"
              type="checkbox"
              class="w-4 h-4 rounded border-[#c1c7d6] text-[#082b80] focus:ring-[#082b80]"
            />

            <label for="remember" class="text-[#343947] text-sm">
              Remember device for 12 hours
            </label>
          </div>

          <!-- Button -->
          <button
            id="login-button"
            type="submit"
            class="w-full h-14 bg-[#082b80] hover:bg-[#062267] text-white rounded-lg text-xl font-bold flex items-center justify-center gap-3 shadow-lg shadow-blue-900/20 active:scale-[0.98] transition"
          >
            Secure Login
            <span class="material-symbols-outlined text-2xl">
              arrow_forward
            </span>
          </button>
        </form>

        <!-- Secure Session -->
        <div class="border-t border-[#cfd5e5] mt-6 pt-5 flex items-center justify-center gap-2 text-[#31415b]">
          <span class="material-symbols-outlined text-sm">
            shield
          </span>
          <p class="text-[11px] font-semibold tracking-[0.16em]">
            END-TO-END ENCRYPTED SESSION
          </p>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center mt-4">
        <p class="text-xs sm:text-sm text-[#737987] leading-relaxed">
          This is a secure system restricted to authorized medical personnel.
          Access is monitored and logged in accordance with institutional policy.
        </p>

        <div class="flex justify-center gap-5 mt-4 text-sm font-semibold text-[#1d2230]">
          <a href="#" class="hover:text-[#082b80]">Privacy Policy</a>
          <span class="text-[#aab0bd]">•</span>
          <a href="#" class="hover:text-[#082b80]">Security Standards</a>
        </div>
      </div>

    </div>
  </main>

  <script>
    function togglePassword() {
      const password = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');

      if (password.type === 'password') {
        password.type = 'text';
        eyeIcon.textContent = 'visibility_off';
      } else {
        password.type = 'password';
        eyeIcon.textContent = 'visibility';
      }
    }
  </script>

</body>
</html>
