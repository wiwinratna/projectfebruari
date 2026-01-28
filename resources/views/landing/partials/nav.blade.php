<nav class="fixed top-4 left-0 right-0 z-50 js-nav">
  <div class="mx-auto max-w-7xl px-6">
    <div class="card-surface rounded-2xl px-4 py-3 flex items-center justify-between js-nav-surface">
      <a href="{{ route('landing') }}" class="flex items-center gap-3 font-bold tracking-tight">
        <span class="inline-flex items-center gap-1">
          <span class="h-2 w-2 rounded-full" style="background: var(--blue)"></span>
          <span class="h-2 w-2 rounded-full" style="background: var(--yellow)"></span>
          <span class="h-2 w-2 rounded-full" style="background: var(--green)"></span>
          <span class="h-2 w-2 rounded-full" style="background: var(--red)"></span>
        </span>
        <span>NOCIS</span>
      </a>

      <div class="hidden md:flex items-center gap-6 text-sm">
        <a class="nav-link" href="#jobs">Jobs</a>
        <a class="nav-link js-navlink" href="#about" data-target="about">About</a>
        <a class="nav-link js-navlink" href="#flow" data-target="flow">Flow</a>
        <a class="nav-link js-navlink" href="#features" data-target="features">Features</a>
        <a class="nav-link js-navlink" href="#news" data-target="news">News</a>
        <span class="js-nav-indicator nav-indicator"></span>
      </div>

      <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" class="btn-ghost px-5 py-2 rounded-xl text-sm font-semibold">Log In</a>
        <a href="{{ route('register') }}" class="btn-primary px-5 py-2 rounded-xl text-sm font-semibold">Sign Up</a>
      </div>
    </div>
  </div>
</nav>

<div class="h-24"></div>
