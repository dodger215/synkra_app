<nav class="synkra-breadcrumb" aria-label="breadcrumb">
  <ol class="synkra-breadcrumb-list">
    <li class="synkra-breadcrumb-item">
      <a href="/dashboard" class="synkra-breadcrumb-link">
        <i class="fa-solid fa-house"></i>
      </a>
    </li>
    @php 
      $segments = request()->segments(); 
      $url = ''; 
    @endphp
    @foreach($segments as $segment)
      @php $url .= '/'.$segment; @endphp
      <li class="synkra-breadcrumb-separator">
        <i class="fa-solid fa-chevron-right"></i>
      </li>
      @if($loop->last)
        <li class="synkra-breadcrumb-item active" aria-current="page">
          {{ ucfirst(str_replace('-', ' ', $segment)) }}
        </li>
      @else
        <li class="synkra-breadcrumb-item">
          <a href="{{ $url }}" class="synkra-breadcrumb-link">
            {{ ucfirst(str_replace('-', ' ', $segment)) }}
          </a>
        </li>
      @endif
    @endforeach
  </ol>
</nav>

<style>
.synkra-breadcrumb {
  display: flex;
  align-items: center;
  font-size: 0.85rem;
  color: var(--text-secondary);
}

.synkra-breadcrumb-list {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 0;
  padding: 0;
  list-style: none;
}

.synkra-breadcrumb-item {
  display: flex;
  align-items: center;
}

.synkra-breadcrumb-item.active {
  color: var(--headings);
  font-weight: 600;
  letter-spacing: 0.3px;
}

.synkra-breadcrumb-link {
  color: var(--text-secondary);
  text-decoration: none;
  transition: color 0.2s;
  display: flex;
  align-items: center;
}

.synkra-breadcrumb-link:hover {
  color: var(--primary);
}

.synkra-breadcrumb-separator {
  color: var(--text-secondary);
  opacity: 0.5;
  font-size: 0.65rem;
  display: flex;
  align-items: center;
}
</style>
