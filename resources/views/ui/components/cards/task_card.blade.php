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

<div class="flowexa-task-card {{ $class }}" draggable="true">
  <div class="flowexa-task-header">
    <span class="flowexa-task-tag" style="background-color: {{ $tagColor }}">{{ $tag }}</span>
    <button class="flowexa-task-options" aria-label="Task options">
      <i class="fa-solid fa-ellipsis"></i>
    </button>
  </div>

  <h4 class="flowexa-task-title">{{ $title }}</h4>
  <p class="flowexa-task-desc">{{ $description }}</p>

  <div class="flowexa-task-footer">
    <div class="flowexa-task-stats">
      @if($date)
        <div class="flowexa-task-stat" title="Due Date">
          <i class="fa-regular fa-calendar"></i>
          <span>{{ $date }}</span>
        </div>
      @endif

      <div class="flowexa-task-stat" title="Comments">
        <i class="fa-regular fa-comment"></i>
        <span>{{ $commentsCount }}</span>
      </div>

      <div class="flowexa-task-stat" title="Attachments">
        <i class="fa-solid fa-paperclip"></i>
        <span>{{ $attachmentsCount }}</span>
      </div>
    </div>

    @if(!empty($assignees))
      <div class="flowexa-task-assignees">
        @foreach(array_slice($assignees, 0, 3) as $assignee)
          <span class="flowexa-task-avatar" title="{{ $assignee['name'] ?? 'Assignee' }}">
            @if(isset($assignee['avatar']) && $assignee['avatar'])
              <img src="{{ $assignee['avatar'] }}" alt="{{ $assignee['name'] ?? 'Avatar' }}">
            @else
              {{ strtoupper(substr($assignee['name'] ?? 'U', 0, 1)) }}
            @endif
          </span>
        @endforeach
        @if(count($assignees) > 3)
          <span class="flowexa-task-avatar flowexa-task-avatar-more">
            +{{ count($assignees) - 3 }}
          </span>
        @endif
      </div>
    @endif
  </div>
</div>

<style>
.flowexa-task-card {
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

.flowexa-task-card:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
  border-color: var(--primary);
  transform: translateY(-2px);
}

.flowexa-task-card:active {
  cursor: grabbing;
}

.flowexa-task-header {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 0.75rem;
}

.flowexa-task-tag {
  border-radius: 100px;
  padding: 3px 10px;
  font-size: 11px;
  font-weight: 600;
  color: #ffffff;
}

.flowexa-task-options {
  background: transparent;
  border: 0;
  color: var(--text-secondary);
  font-size: 16px;
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.flowexa-task-options:hover {
  background-color: var(--surface-secondary);
  color: var(--text-primary);
}

.flowexa-task-title {
  font-size: 1rem;
  font-weight: 600;
  color: var(--headings);
  margin: 0 0 0.5rem 0;
}

.flowexa-task-desc {
  font-size: 0.875rem;
  color: var(--text-secondary);
  margin: 0 0 1.25rem 0;
  line-height: 1.5;
}

.flowexa-task-footer {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid var(--divider);
  padding-top: 0.75rem;
}

.flowexa-task-stats {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  color: var(--text-secondary);
  font-size: 12px;
}

.flowexa-task-stat {
  display: flex;
  align-items: center;
  gap: 4px;
  cursor: pointer;
}

.flowexa-task-stat:hover {
  color: var(--text-primary);
}

.flowexa-task-assignees {
  display: flex;
  align-items: center;
}

.flowexa-task-avatar {
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

.flowexa-task-avatar:hover {
  transform: translateY(-4px);
  z-index: 10;
}

.flowexa-task-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.flowexa-task-avatar-more {
  background-color: var(--surface-secondary);
  color: var(--text-secondary);
  font-weight: 600;
}
</style>
