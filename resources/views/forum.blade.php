<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUSTENA - MicroForum</title>
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
        
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
 <!-- Sidebar Backdrop -->
<div class="sidebar-backdrop" id="sidebar-backdrop" onclick="closeSidebar()"></div>

<!-- Sidebar Toggle Button -->
<button class="mobile-hamburger" id="hamburger-btn" onclick="toggleMobileSidebar()" aria-label="Toggle navigation menu" aria-expanded="false">☰</button>

<div class="sidebar" id="sidebar">
    
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

    <a href="{{ url('/challenge') }}" class="nav-item">
        <div class="nav-icon">🏆</div>
        <span>Challenges</span>
    </a>
    <a href="{{ url('/forum') }}" class="nav-item active">
        <div class="nav-icon">💬</div>
        <span>MicroForum</span>
    </a>
    <a href="{{ url('/visual-progress') }}" class="nav-item">
        <div class="nav-icon">🌍</div>
        <span>Your Planet</span>
    </a>
    <a href="{{ url('/profile') }}" class="nav-item">
        <div class="nav-icon">👤</div>
        <span>Profile</span>
    </a>
</div>

<div class="floating-icons">
      <a href="{{ url('/analytics') }}" class="floating-icon" title="Analytics">📅</a>
      <a href="{{ url('/streaks') }}" class="floating-icon" title="Streaks">🔥</a>
      <a href="{{ url('/learning-modules') }}" class="floating-icon" title="Learning Modules">🌱</a>
      <a href="{{ url('/leaderboard') }}" class="floating-icon" title="Leaderboard">🏆</a>
      <a href="{{ url('/badges') }}" class="floating-icon" title="Badges">🥇</a>
      <a href="{{ url('/settings') }}" class="floating-icon" title="Settings">⚙️</a>
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
                <div class="author-avatar-wrapper">
                  <div class="author-avatar" data-username="{{ $author }}">{{ $initial }}</div>
                  @if($post->user)
                    <div class="level-badge" style="background: {{ $post->user->level_badge['color'] }};" title="{{ $post->user->level_badge['name'] }} (Level {{ $post->user->forum_level }})">
                      <span class="level-emoji">{{ $post->user->level_badge['emoji'] }}</span>
                      <span class="level-number">{{ $post->user->forum_level }}</span>
                    </div>
                  @endif
                </div>
                <div class="post-meta">
                  <div class="author-name">{{ $author }}</div>
                  <div class="post-topic-row">
                    <div class="post-topic">{{ $post->title }}</div>
                    <div class="post-badges">
                      @if($post->created_at->diffInHours(now()) < 24)
                        <span class="badge badge-new">NEW</span>
                      @endif
                      @if($post->likes_count >= 10)
                        <span class="badge badge-hot">🔥 HOT</span>
                      @endif
                    </div>
                  </div>
                  <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
                </div>
              </div>

              <div class="post-content">{{ $post->content }}</div>

              <div class="post-stats">
                <div class="stat-item reactions-container">
                  @auth
                    <div class="reaction-picker" data-post="{{ $post->id }}">
                      <button class="reaction-trigger" data-post="{{ $post->id }}" aria-label="React">
                        <span class="trigger-icon">👍</span>
                      </button>
                      <div class="reaction-options" data-post="{{ $post->id }}">
                        <button class="reaction-option" data-reaction="helpful" title="Helpful">👍</button>
                        <button class="reaction-option" data-reaction="love" title="Love it">❤️</button>
                        <button class="reaction-option" data-reaction="eco_warrior" title="Eco-warrior">🌱</button>
                        <button class="reaction-option" data-reaction="celebrate" title="Celebrate">🎉</button>
                        <button class="reaction-option" data-reaction="insightful" title="Insightful">💡</button>
                      </div>
                    </div>
                  @else
                    <span class="stat-icon">👍</span>
                  @endauth
                  @php
                    $reactionCounts = $post->likes->groupBy('reaction_type')->map->count();
                    $emojiMap = [
                      'helpful' => '👍',
                      'love' => '❤️',
                      'eco_warrior' => '🌱',
                      'celebrate' => '🎉',
                      'insightful' => '💡'
                    ];
                  @endphp
                  <span class="reactions-summary" data-post="{{ $post->id }}">
                    @if($post->likes_count > 0)
                      <span class="reaction-breakdown">
                        @foreach($reactionCounts as $type => $count)
                          <span class="reaction-count-item">{{ $emojiMap[$type] ?? '👍' }} {{ $count }}</span>
                        @endforeach
                      </span>
                    @else
                      <span class="no-reactions">No reactions yet</span>
                    @endif
                  </span>
                </div>
                <div class="stat-item">
                  <span class="stat-icon">💬</span>
                  <span><span class="comment-count" data-post="{{ $post->id }}">{{ $post->comments_count }}</span> comments</span>
                </div>
              </div>

              <div class="comments" style="margin-top:16px;">
                @foreach($post->comments->take(3) as $c)
                  @php
                    $cAuthor = optional($c->user)->display_name ?? 'User';
                    $cInitial = strtoupper(Str::substr($cAuthor, 0, 1));
                  @endphp
                  <div class="comment-item" id="comment-{{ $c->id }}">
                    <div class="comment-avatar" data-username="{{ $cAuthor }}">{{ $cInitial }}</div>
                    <div class="comment-bubble">
                      <div class="comment-header">
                        <strong class="comment-author">{{ $cAuthor }}</strong>
                        <small class="comment-time">{{ $c->created_at->diffForHumans() }}</small>
                      </div>
                      <div class="comment-content">{{ $c->content }}</div>
                    </div>
                  </div>
                @endforeach
                @if($post->comments->count() > 3)
                  <div class="view-more-comments">
                    <a href="{{ route('forum.show',$post) }}">View all {{ $post->comments->count() }} comments →</a>
                  </div>
                @endif
              </div>

              @auth
              <form class="comment-form" data-post="{{ $post->id }}" action="{{ route('forum.comment.store',$post) }}" method="POST">
                @csrf
                <input name="content" class="comment-input" required placeholder="Write a comment…">
                <button class="comment-submit-btn" type="submit">💬 Reply</button>
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

          {{-- Loading skeleton --}}
          <div id="loading-skeleton" class="loading-skeleton" style="display: none;">
            <div class="skeleton-post-card">
              <div class="skeleton-header">
                <div class="skeleton-avatar"></div>
                <div class="skeleton-meta">
                  <div class="skeleton-name"></div>
                  <div class="skeleton-topic"></div>
                  <div class="skeleton-time"></div>
                </div>
              </div>
              <div class="skeleton-content">
                <div class="skeleton-line"></div>
                <div class="skeleton-line"></div>
                <div class="skeleton-line" style="width: 70%;"></div>
              </div>
              <div class="skeleton-stats">
                <div class="skeleton-stat"></div>
                <div class="skeleton-stat"></div>
              </div>
            </div>
            <div class="skeleton-post-card">
              <div class="skeleton-header">
                <div class="skeleton-avatar"></div>
                <div class="skeleton-meta">
                  <div class="skeleton-name"></div>
                  <div class="skeleton-topic"></div>
                  <div class="skeleton-time"></div>
                </div>
              </div>
              <div class="skeleton-content">
                <div class="skeleton-line"></div>
                <div class="skeleton-line"></div>
                <div class="skeleton-line" style="width: 60%;"></div>
              </div>
              <div class="skeleton-stats">
                <div class="skeleton-stat"></div>
                <div class="skeleton-stat"></div>
              </div>
            </div>
          </div>
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

  // Gradient Avatar Generator - Unique colors per user
  function stringToColor(str) {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
      hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return hash;
  }

  function getGradientForUser(username) {
    const hash = stringToColor(username);
    const colorPalettes = [
      ['#667eea', '#764ba2'], // Purple-Blue
      ['#f093fb', '#f5576c'], // Pink-Red
      ['#4facfe', '#00f2fe'], // Blue-Cyan
      ['#43e97b', '#38f9d7'], // Green-Teal
      ['#fa709a', '#fee140'], // Pink-Yellow
      ['#30cfd0', '#330867'], // Teal-Purple
      ['#a8edea', '#fed6e3'], // Mint-Pink
      ['#ff9a56', '#ff6a88'], // Orange-Pink
      ['#ffecd2', '#fcb69f'], // Peach
      ['#ff6e7f', '#bfe9ff'], // Red-Blue
      ['#08aeea', '#2af598'], // Blue-Green
      ['#ffa8a8', '#fcff00'], // Pink-Yellow
    ];
    const paletteIndex = Math.abs(hash) % colorPalettes.length;
    const [color1, color2] = colorPalettes[paletteIndex];
    return `linear-gradient(135deg, ${color1}, ${color2})`;
  }

  function applyAvatarGradients() {
    document.querySelectorAll('.author-avatar, .comment-avatar').forEach(avatar => {
      const username = avatar.dataset.username;
      if (username) {
        const gradient = getGradientForUser(username);
        avatar.style.background = gradient;
      }
    });
  }

  // Apply gradients on load
  applyAvatarGradients();

  // Toggle reaction picker on click (in addition to hover)
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.reaction-trigger');
    if (trigger) {
      e.stopPropagation();
      const picker = trigger.closest('.reaction-picker');
      const options = picker.querySelector('.reaction-options');

      // Toggle the reaction options
      if (options.classList.contains('show')) {
        options.classList.remove('show');
      } else {
        // Close all other open reaction pickers
        document.querySelectorAll('.reaction-options.show').forEach(opt => {
          opt.classList.remove('show');
        });
        options.classList.add('show');
      }
      return;
    }

    // Close reaction picker when clicking outside
    const reactionOption = e.target.closest('.reaction-option');
    if (!reactionOption) {
      document.querySelectorAll('.reaction-options.show').forEach(opt => {
        opt.classList.remove('show');
      });
    }
  });

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
          <div class="author-avatar" data-username="${data.post.author || 'User'}">${(data.post.author || 'U')[0].toUpperCase()}</div>
          <div class="post-meta">
            <div class="author-name">${data.post.author || 'User'}</div>
            <div class="post-topic">${data.post.title}</div>
            <div class="post-time">just now</div>
          </div>
        </div>
        <div class="post-content">${data.post.content}</div>
        <div class="post-stats">
          <div class="stat-item reactions-container">
            <div class="reaction-picker" data-post="${data.post.id}">
              <button class="reaction-trigger" data-post="${data.post.id}" aria-label="React">
                <span class="trigger-icon">👍</span>
              </button>
              <div class="reaction-options" data-post="${data.post.id}">
                <button class="reaction-option" data-reaction="helpful" title="Helpful">👍</button>
                <button class="reaction-option" data-reaction="love" title="Love it">❤️</button>
                <button class="reaction-option" data-reaction="eco_warrior" title="Eco-warrior">🌱</button>
                <button class="reaction-option" data-reaction="celebrate" title="Celebrate">🎉</button>
                <button class="reaction-option" data-reaction="insightful" title="Insightful">💡</button>
              </div>
            </div>
            <span class="reactions-summary" data-post="${data.post.id}">
              <span class="no-reactions">No reactions yet</span>
            </span>
          </div>
          <div class="stat-item"><span class="stat-icon">💬</span><span><span class="comment-count" data-post="${data.post.id}">0</span> comments</span></div>
        </div>`;
      list.insertBefore(node, document.getElementById('next-page'));
      applyAvatarGradients(); // Apply gradient to new avatar
      postForm.reset();
      document.getElementById('composer')?.removeAttribute('open');
    });
  }

  // Reaction handling (delegated)
  document.addEventListener('click', async (e) => {
    const reactionBtn = e.target.closest('.reaction-option');
    if (!reactionBtn) return;

    const postId = reactionBtn.closest('.reaction-picker').dataset.post;
    const reactionType = reactionBtn.dataset.reaction;

    // Add animation to the clicked reaction
    reactionBtn.classList.add('reaction-pop');
    setTimeout(() => reactionBtn.classList.remove('reaction-pop'), 500);

    // Send reaction to backend
    const formData = new FormData();
    formData.append('reaction_type', reactionType);

    const res = await fetch(`{{ url('/forum') }}/${postId}/like`, {
      method: 'POST',
      headers: {'X-CSRF-TOKEN': token, 'Accept':'application/json'},
      body: formData
    });

    if (!res.ok) return;
    const data = await res.json();

    // Update reaction breakdown
    const emojiMap = {
      'helpful': '👍',
      'love': '❤️',
      'eco_warrior': '🌱',
      'celebrate': '🎉',
      'insightful': '💡'
    };

    const summary = document.querySelector(`.reactions-summary[data-post="${postId}"]`);
    if (summary) {
      if (data.count === 0) {
        summary.innerHTML = '<span class="no-reactions">No reactions yet</span>';
      } else {
        let breakdownHtml = '<span class="reaction-breakdown">';
        for (const [type, count] of Object.entries(data.reactions)) {
          const emoji = emojiMap[type] || '👍';
          breakdownHtml += `<span class="reaction-count-item">${emoji} ${count}</span>`;
        }
        breakdownHtml += '</span>';
        summary.innerHTML = breakdownHtml;
      }
    }

    // Update trigger icon to show the selected reaction
    const trigger = document.querySelector(`.reaction-trigger[data-post="${postId}"] .trigger-icon`);
    if (trigger && data.liked) {
      trigger.textContent = emojiMap[reactionType] || '👍';
    } else if (trigger && !data.liked) {
      trigger.textContent = '👍'; // Reset to default
    }

    // Hide the reaction picker after selection
    const picker = document.querySelector(`.reaction-options[data-post="${postId}"]`);
    if (picker) {
      picker.classList.remove('show');
    }
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
        const author = data.comment.author || 'User';
        const initial = author[0].toUpperCase();
        div.className = 'comment-item';
        div.innerHTML = `
          <div class="comment-avatar" data-username="${author}">${initial}</div>
          <div class="comment-bubble">
            <div class="comment-header">
              <strong class="comment-author">${author}</strong>
              <small class="comment-time">${data.comment.created_at}</small>
            </div>
            <div class="comment-content">${data.comment.content}</div>
          </div>
        `;
        wrap.appendChild(div);
        applyAvatarGradients(); // Apply gradient to new comment avatar
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
    // Show skeleton loader
    const skeleton = document.getElementById('loading-skeleton');
    if (skeleton) skeleton.style.display = 'block';

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

      // Hide skeleton loader
      if (skeleton) skeleton.style.display = 'none';

      cards.forEach(card => {
        postsList.insertBefore(card, nextHolder);
        attachCommentListeners(card);
      });

      // Apply gradients to newly loaded avatars
      applyAvatarGradients();

      // Update next url
      const newNext = (doc.querySelector('#next-page') || {}).dataset?.url || '';
      nextHolder.dataset.url = newNext;

      // Stop observing if there is no more page
      if (!newNext) observer.disconnect();
    } catch (err) {
      console.error('Infinite scroll error:', err);
      // Hide skeleton loader on error
      if (skeleton) skeleton.style.display = 'none';
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
function toggleMobileSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;
    const hamburger = document.getElementById('hamburger-btn');
    const isOpen = sidebar.classList.contains('open');

    sidebar.classList.toggle('open');
    body.classList.toggle('sidebar-open');

    // Update aria-expanded for accessibility
    hamburger.setAttribute('aria-expanded', !isOpen);
}

function closeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;
    const hamburger = document.getElementById('hamburger-btn');

    if (sidebar.classList.contains('open')) {
        sidebar.classList.remove('open');
        body.classList.remove('sidebar-open');
        hamburger.setAttribute('aria-expanded', 'false');
    }
}

// Close sidebar when clicking on nav items
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', closeSidebar);
    });
});

</script>
</body>
</html>
