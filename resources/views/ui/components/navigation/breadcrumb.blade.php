<nav class="flowexa-breadcrumb" aria-label="breadcrumb">
  <ol class="flowexa-breadcrumb-list">
    <li class="flowexa-breadcrumb-item">
      <a href="/dashboard" class="flowexa-breadcrumb-link">
        <i class="fa-solid fa-house"></i>
      </a>
    </li>
    @php
      $segments = request()->segments();
      $url = '';
    @endphp
    @foreach($segments as $segment)
      @php
        $url .= '/'.$segment;
        // UUIDs are typically 36 chars. Any segment > 20 is likely an ID we want to shorten.
        if (strlen($segment) > 20 || preg_match('/^[a-f0-9]{8}-/i', $segment)) {
            $displaySegment = substr($segment, 0, 5) . '...';
        } else {
            $displaySegment = ucfirst(str_replace('-', ' ', $segment));
        }
      @endphp
      <li class="flowexa-breadcrumb-separator">
        <i class="fa-solid fa-chevron-right"></i>
      </li>
      @if($loop->last)
        <li class="flowexa-breadcrumb-item active" aria-current="page">
          {{ $displaySegment }}
        </li>
      @else
        <li class="flowexa-breadcrumb-item">
          <a href="{{ $url }}" class="flowexa-breadcrumb-link">
            {{ $displaySegment }}
          </a>
        </li>
      @endif
    @endforeach
  </ol>
</nav>

<style>
.flowexa-breadcrumb {
  display: flex;
  align-items: center;
  font-size: 0.85rem;
  color: var(--text-secondary);
}

.flowexa-breadcrumb-list {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 0;
  padding: 0;
  list-style: none;
}

.flowexa-breadcrumb-item {
  display: flex;
  align-items: center;
  white-space: nowrap;
}

.flowexa-breadcrumb-item.active {
  color: var(--headings);
  font-weight: 600;
  letter-spacing: 0.3px;
}

.flowexa-breadcrumb-link {
  color: var(--text-secondary);
  text-decoration: none;
  transition: color 0.2s;
  display: flex;
  align-items: center;
}

.flowexa-breadcrumb-link:hover {
  color: var(--primary);
}

.flowexa-breadcrumb-separator {
  color: var(--text-secondary);
  opacity: 0.5;
  font-size: 0.65rem;
  display: flex;
  align-items: center;
}
</style>
