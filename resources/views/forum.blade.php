<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - MicroForum</title>
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/forumresponsive.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
      /* hide the legacy footer if you keep it */
      .list-footer{ display:none; }
      /* invisible sentinel used by IntersectionObserver */
      .infinite-sentinel{ height: 1px; }
    </style>
</head>
<body>
@php
    use Illuminate\Support\Str;
@endphp

<div class="sidebar" id="sidebar">
    <div class="sidebar-toggle" onclick="toggleSidebar()">☰</div>
    <div class="logo">
        <div class="logo-icon">🌱</div>
        <div class="logo-text">SUSTENA</div>
    </div>

    <a href="{{ url('/landing-page') }}" class="nav-item">
        <div class="nav-icon">🏠</div>
        <span>Home</span>
    </a>
    <a href="{{ url('/footprint-calculator') }}" class="nav-item">
        <div class="nav-icon">👣</div>
        <span>Footprint Tracker</span>
    </a>
    <a href="{{ url('/learning-modules') }}" class="nav-item">
        <div class="nav-icon">📚</div>
        <span>Learn</span>
    </a>
    <a href="{{ url('/challenge') }}" class="nav-item">
        <div class="nav-icon">🏆</div>
        <span>Challenges</span>
    </a>
    <a href="{{ url('/forum') }}" class="nav-item active">
        <div class="nav-icon">💬</div>
        <span>MicroForum</span>
    </a>
    <a href="{{ url('/profile') }}" class="nav-item">
        <div class="nav-icon">👤</div>
        <span>Profile</span>
    </a>
</div>

<div class="floating-icons">
    <a href="{{ route('analytics') }}" class="floating-icon" title="Analytics">🔥</a>
    <a href="{{ route('learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
    <a href="{{ route('leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
    <a href="{{ route('badges') }}" class="floating-icon" title="Badges">🥇</a>
    <a href="{{ route('settings') }}" class="floating-icon" title="Settings">⚙️</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="content-layout">

      {{-- LEFT HERO PANEL --}}
      <div class="hero-panel">
        <div class="hero-card hero-welcome">
          <div class="hero-title">🌱 SUSTENA <br> MicroForum</div>
          <p class="hero-sub">Share tips, ask questions, and learn sustainable habits together.</p>
        </div>

        <form class="hero-card hero-search" method="GET" action="{{ route('forum.index') }}">
          <input name="q" value="{{ request('q') }}" placeholder="Search posts…" class="hero-input">
          <button class="hero-btn">Search</button>
        </form>

        @if(($posts ?? null) && $posts->count())
          @php
            $words = collect($posts->pluck('title')->join(' '))
                      ->flatMap(function($s){ return collect(preg_split('/\s+/', strtolower($s))); })
                      ->filter(fn($w) => strlen($w) > 3)
                      ->countBy()
                      ->sortDesc()
                      ->keys()
                      ->take(8);
          @endphp
          <div class="hero-card hero-chips">
            <div class="chip-title">Trending</div>
            <div class="chip-wrap">
              @foreach($words as $w)
                <a class="chip" href="{{ route('forum.index', ['q' => $w]) }}">#{{ $w }}</a>
              @endforeach
            </div>
          </div>
        @endif

        @auth
        <div class="hero-card hero-stats">
            <div class="stat-row"><div class="stat-num">{{ $myStats['posts'] ?? 0 }}</div><div class="stat-label">Posts</div></div>
            <div class="stat-row"><div class="stat-num">{{ $myStats['comments'] ?? 0 }}</div><div class="stat-label">Comments</div></div>
            <div class="stat-row"><div class="stat-num">{{ $myStats['likes'] ?? 0 }}</div><div class="stat-label">Likes Given</div></div>
        </div>
        @endauth

        <div class="hero-card hero-cta">
          <div class="cta-emoji">🚴</div>
          <div class="cta-text">
            <div class="cta-title">Weekly Eco-Challenge</div>
            <div class="cta-sub">Bike or walk for 3 short trips this week.</div>
          </div>
          <a class="cta-btn" href="{{ url('/challenge') }}">Join</a>
        </div>

        <div class="hero-card hero-rules">
          <div class="rules-title">Guidelines</div>
          <ul class="rules-list">
              <li>Be kind and constructive.</li>
              <li>Stay on-topic: sustainability & daily habits.</li>
              <li>No spam / promotions.</li>
          </ul>
        </div>
      </div>

      <!-- Forum Content -->
      <div class="forum-content">
        @auth
        {{-- Compact, expandable composer --}}
        <details class="composer" id="composer">
          <summary>
            <div class="composer-summary">
              <span>✍️ Start a post…</span>
              <button type="button" class="composer-open-btn">+ Post</button>
            </div>
          </summary>

          <form id="new-post-form" action="{{ route('forum.post.store') }}" method="POST" class="composer-body">
            @csrf
            <input name="title" maxlength="140" required placeholder="Title / Topic">
            <textarea name="content" rows="4" maxlength="5000" required placeholder="Share your thoughts…"></textarea>

            <div class="composer-actions">
              <button type="submit" class="composer-submit">Post</button>
              <button type="button" class="composer-cancel" onclick="document.getElementById('composer').open=false">Cancel</button>
            </div>
            <div id="post-error" class="composer-error" style="display:none;"></div>
          </form>
        </details>
        @endauth

        <br>

        <!-- Recent Posts -->
        <div class="recent-posts" id="posts-list">
          <h2 class="section-title">Recent Posts</h2>

          @isset($posts)
          @forelse($posts as $post)
            @php
                $author = optional($post->user)->display_name ?? 'User';
                $initial = strtoupper(Str::substr($author, 0, 1));
            @endphp
            <div class="post-card" id="post-{{ $post->id }}">
              <div class="post-header">
                <div class="author-avatar">{{ $initial }}</div>
                <div class="post-meta">
                  <div class="author-name">{{ $author }}</div>
                  <div class="post-topic">{{ $post->title }}</div>
                  <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
                </div>
              </div>

              <div class="post-content">{{ $post->content }}</div>

              <div class="post-stats">
                <div class="stat-item">
                  @auth
                    <button class="like-btn stat-icon" data-post="{{ $post->id }}" aria-label="like" style="background:none;border:none;cursor:pointer;">👍</button>
                  @else
                    <span class="stat-icon">👍</span>
                  @endauth
                  <span><span class="like-count" data-post="{{ $post->id }}">{{ $post->likes_count }}</span> reacts</span>
                </div>
                <div class="stat-item">
                  <span class="stat-icon">💬</span>
                  <span><span class="comment-count" data-post="{{ $post->id }}">{{ $post->comments_count }}</span> comments</span>
                </div>
              </div>

              <div class="comments" style="margin-top:12px;">
                @foreach($post->comments->take(3) as $c)
                  @php $cAuthor = optional($c->user)->display_name ?? 'User'; @endphp
                  <div class="message-bubble" id="comment-{{ $c->id }}">
                    <div class="message-text">
                      <strong>{{ $cAuthor }}</strong> • <small>{{ $c->created_at->diffForHumans() }}</small><br>
                      {{ $c->content }}
                    </div>
                  </div>
                @endforeach
                @if($post->comments->count() > 3)
                  <div class="message-bubble">
                    <div class="message-text"><a href="{{ route('forum.show',$post) }}">View all comments →</a></div>
                  </div>
                @endif
              </div>

              @auth
              <form class="comment-form" data-post="{{ $post->id }}" action="{{ route('forum.comment.store',$post) }}" method="POST" style="margin-top:10px; display:flex; gap:8px;">
                @csrf
                <input name="content" required placeholder="Write a comment…" style="flex:1;padding:10px;border-radius:10px;border:1px solid #cde;">
                <button class="new-post-btn" style="padding:10px 16px;">Reply</button>
              </form>
              @endauth
            </div>
          @empty
            <div class="post-card">No posts yet — be the first to share!</div>
          @endforelse

          {{-- Hidden next-page holder for JS --}}
          <div id="next-page" data-url="{{ $posts->nextPageUrl() }}"></div>
          @endisset

          {{-- Sentinel for IntersectionObserver --}}
          <div id="infinite-sentinel" class="infinite-sentinel"></div>
        </div>
      </div>

           
    </div>
</div>

<script>
  // Open composer
  document.addEventListener('click', e => {
    const btn = e.target.closest('.composer-open-btn');
    if (!btn) return;
    document.getElementById('composer').open = true;
  });

  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Create Post (no reload)
  const postForm = document.getElementById('new-post-form');
  if (postForm) {
    postForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const fd = new FormData(postForm);
      const res = await fetch(postForm.action, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': token, 'Accept':'application/json'},
        body: fd
      });
      if (!res.ok) {
        const data = await res.json().catch(()=>({}));
        const err = document.getElementById('post-error');
        err.style.display='block';
        err.textContent = data.message || 'Error posting';
        return;
      }
      const data = await res.json();
      const list = document.getElementById('posts-list');
      const node = document.createElement('div');
      node.className = 'post-card';
      node.innerHTML = `
        <div class="post-header">
          <div class="author-avatar">${(data.post.author || 'U')[0].toUpperCase()}</div>
          <div class="post-meta">
            <div class="author-name">${data.post.author || 'User'}</div>
            <div class="post-topic">${data.post.title}</div>
            <div class="post-time">just now</div>
          </div>
        </div>
        <div class="post-content">${data.post.content}</div>
        <div class="post-stats">
          <div class="stat-item"><span class="stat-icon">👍</span><span><span class="like-count" data-post="${data.post.id}">0</span> reacts</span></div>
          <div class="stat-item"><span class="stat-icon">💬</span><span><span class="comment-count" data-post="${data.post.id}">0</span> comments</span></div>
        </div>`;
      list.insertBefore(node, document.getElementById('next-page'));
      postForm.reset();
      document.getElementById('composer')?.removeAttribute('open');
    });
  }

  // Like/Unlike (delegated)
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.like-btn');
    if (!btn) return;
    const postId = btn.dataset.post;
    const res = await fetch(`{{ url('/forum') }}/${postId}/like`, {
      method: 'POST',
      headers: {'X-CSRF-TOKEN': token, 'Accept':'application/json'}
    });
    if (!res.ok) return;
    const data = await res.json();
    const countSpan = document.querySelector(`.like-count[data-post="${postId}"]`);
    if (countSpan) countSpan.textContent = data.count;
  });

  // Utility: bind comment listeners (called for initial page & appended ones)
  function attachCommentListeners(scope = document) {
    scope.querySelectorAll('.comment-form').forEach(form => {
      if (form.dataset.bound) return;          // avoid duplicate binding
      form.dataset.bound = '1';
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(form);
        const res = await fetch(form.action, {
          method: 'POST',
          headers: {'X-CSRF-TOKEN': token, 'Accept':'application/json'},
          body: fd
        });
        if (!res.ok) return;
        const data = await res.json();
        const wrap = form.closest('.post-card').querySelector('.comments');
        const div = document.createElement('div');
        div.className = 'message-bubble';
        div.innerHTML = `<div class="message-text"><strong>${data.comment.author || 'User'}</strong> • <small>${data.comment.created_at}</small><br>${data.comment.content}</div>`;
        wrap.appendChild(div);
        const cCount = form.closest('.post-card').querySelector('.comment-count');
        if (cCount) cCount.textContent = data.comment.count;
        form.reset();
      });
    });
  }
  attachCommentListeners();

  // --- Infinite Scroll ---
  const nextHolder   = document.getElementById('next-page');
  const sentinel     = document.getElementById('infinite-sentinel');
  const postsList    = document.getElementById('posts-list');

  async function loadNextPage(url) {
    try {
      const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
      const html = await res.text();

      // Parse the returned HTML and get all .post-card elements
      const parser = new DOMParser();
      const doc    = parser.parseFromString(html, 'text/html');
      let cards    = doc.querySelectorAll('.post-card');

      if (!cards.length) {
        // Fallback in case a partial is returned
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        cards = tmp.querySelectorAll('.post-card');
      }

      cards.forEach(card => {
        postsList.insertBefore(card, nextHolder);
        attachCommentListeners(card);
      });

      // Update next url
      const newNext = (doc.querySelector('#next-page') || {}).dataset?.url || '';
      nextHolder.dataset.url = newNext;

      // Stop observing if there is no more page
      if (!newNext) observer.disconnect();
    } catch (err) {
      console.error('Infinite scroll error:', err);
      observer.disconnect();
    }
  }

  const observer = new IntersectionObserver(async entries => {
    if (!entries[0].isIntersecting) return;
    const nextUrl = nextHolder.dataset.url;
    if (!nextUrl) { observer.disconnect(); return; }
    await loadNextPage(nextUrl);
  }, { rootMargin: '600px' }); // prefetch early

  observer.observe(sentinel);

  function toggleSidebar() {
    const sidebar    = document.querySelector('.sidebar');
    const mainContent= document.querySelector('.main-content');
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  }

  (function(){
  const wrap      = document.getElementById('active-users');
  const sentinel  = document.getElementById('users-sentinel');
  if (!wrap || !sentinel) return;

  let loading = false;

  const observer = new IntersectionObserver(async (entries) => {
    const first = entries[0];
    if (!first.isIntersecting || loading) return;

    const nextUrl = sentinel.dataset.nextUrl;
    if (!nextUrl) { observer.disconnect(); return; }

    loading = true;
    try {
      const res = await fetch(nextUrl, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
      });
      if (!res.ok) return;

      const data = await res.json();
      if (data.html) {
        wrap.insertAdjacentHTML('beforeend', data.html);
      }
      // Update next url; stop observing if none
      sentinel.dataset.nextUrl = data.next_url || '';
      if (!data.next_url) observer.disconnect();
    } catch(e) {
      console.error('Active-users infinite scroll error:', e);
    } finally {
      loading = false;
    }
  }, { root: null, rootMargin: '500px 0px', threshold: 0 });

  observer.observe(sentinel);
})();

 document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }
});
</script>
</body>
</html>
