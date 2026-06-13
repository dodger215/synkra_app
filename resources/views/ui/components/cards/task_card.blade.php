@php
  $tag = $tag ?? 'Draggable';
  $tagColor = $tagColor ?? 'var(--primary)';
  $title = $title ?? 'Task Title';
  $description = $description ?? 'Lorem ipsum description text...';
  $date = $date ?? 'Feb 24';
  $commentsCount = $commentsCount ?? 0;
  $attachmentsCount = $attachmentsCount ?? 0;
  $assignees = $assignees ?? [];
  $class = $class ?? '';
@endphp

<div class="synkra-task-card {{ $class }}" draggable="true">
  <div class="synkra-task-header">
    <span class="synkra-task-tag" style="background-color: {{ $tagColor }}">{{ $tag }}</span>
    <button class="synkra-task-options" aria-label="Task options">
      <i class="fa-solid fa-ellipsis"></i>
    </button>
  </div>
  
  <h4 class="synkra-task-title">{{ $title }}</h4>
  <p class="synkra-task-desc">{{ $description }}</p>
  
  <div class="synkra-task-footer">
    <div class="synkra-task-stats">
      @if($date)
        <div class="synkra-task-stat" title="Due Date">
          <i class="fa-regular fa-calendar"></i>
          <span>{{ $date }}</span>
        </div>
      @endif
      
      <div class="synkra-task-stat" title="Comments">
        <i class="fa-regular fa-comment"></i>
        <span>{{ $commentsCount }}</span>
      </div>
      
      <div class="synkra-task-stat" title="Attachments">
        <i class="fa-solid fa-paperclip"></i>
        <span>{{ $attachmentsCount }}</span>
      </div>
    </div>
    
    @if(!empty($assignees))
      <div class="synkra-task-assignees">
        @foreach(array_slice($assignees, 0, 3) as $assignee)
          <span class="synkra-task-avatar" title="{{ $assignee['name'] ?? 'Assignee' }}">
            @if(isset($assignee['avatar']) && $assignee['avatar'])
              <img src="{{ $assignee['avatar'] }}" alt="{{ $assignee['name'] ?? 'Avatar' }}">
            @else
              {{ strtoupper(substr($assignee['name'] ?? 'U', 0, 1)) }}
            @endif
          </span>
        @endforeach
        @if(count($assignees) > 3)
          <span class="synkra-task-avatar synkra-task-avatar-more">
            +{{ count($assignees) - 3 }}
          </span>
        @endif
      </div>
    @endif
  </div>
</div>

<style>
.synkra-task-card {
  position: relative;
  background-color: var(--surface);
  border: 1px solid var(--border);
  padding: 1.25rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
  margin-bottom: 1rem;
  width: 100%;
  max-width: 350px;
  cursor: grab;
  transition: all 0.2s ease-in-out;
  color: var(--body-text);
}

.synkra-task-card:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
  border-color: var(--primary);
  transform: translateY(-2px);
}

.synkra-task-card:active {
  cursor: grabbing;
}

.synkra-task-header {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 0.75rem;
}

.synkra-task-tag {
  border-radius: 100px;
  padding: 3px 10px;
  font-size: 11px;
  font-weight: 600;
  color: #ffffff;
}

.synkra-task-options {
  background: transparent;
  border: 0;
  color: var(--text-secondary);
  font-size: 16px;
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.synkra-task-options:hover {
  background-color: var(--surface-secondary);
  color: var(--text-primary);
}

.synkra-task-title {
  font-size: 1rem;
  font-weight: 600;
  color: var(--headings);
  margin: 0 0 0.5rem 0;
}

.synkra-task-desc {
  font-size: 0.875rem;
  color: var(--text-secondary);
  margin: 0 0 1.25rem 0;
  line-height: 1.5;
}

.synkra-task-footer {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid var(--divider);
  padding-top: 0.75rem;
}

.synkra-task-stats {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  color: var(--text-secondary);
  font-size: 12px;
}

.synkra-task-stat {
  display: flex;
  align-items: center;
  gap: 4px;
  cursor: pointer;
}

.synkra-task-stat:hover {
  color: var(--text-primary);
}

.synkra-task-assignees {
  display: flex;
  align-items: center;
}

.synkra-task-avatar {
  height: 28px;
  width: 28px;
  background-color: var(--primary);
  margin-left: -8px;
  border-radius: 50%;
  border: 2px solid var(--surface);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 10px;
  color: #fff;
  overflow: hidden;
  transition: transform 0.2s;
}

.synkra-task-avatar:hover {
  transform: translateY(-4px);
  z-index: 10;
}

.synkra-task-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.synkra-task-avatar-more {
  background-color: var(--surface-secondary);
  color: var(--text-secondary);
  font-weight: 600;
}
</style>