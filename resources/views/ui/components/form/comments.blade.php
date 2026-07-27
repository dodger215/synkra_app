@php
  $title = $title ?? 'Comments';
  $comments = $comments ?? [
    [
      'user_name' => 'Yassine Zanina',
      'user_avatar' => null,
      'date_time' => 'Wednesday, March 13th at 2:45pm',
      'likes' => 14,
      'content' => 'I\'ve been using this product for a few days now and I\'m really impressed! The interface is intuitive and easy to use.',
      'online' => true
    ]
  ];
  $placeholder = $placeholder ?? 'Add a comment...';
  $submitUrl = $submitUrl ?? '#';
  $class = $class ?? '';
@endphp

<div class="flowexa-comments-card {{ $class }}">
  <h3 class="flowexa-comments-title">{{ $title }}</h3>

  <div class="flowexa-comments-list">
    @foreach($comments as $comment)
      <div class="flowexa-comment-item">
        <div class="flowexa-comment-react">
          <button type="button" class="flowexa-react-btn" aria-label="Like comment">
            <i class="fa-regular fa-heart"></i>
          </button>
          <hr class="flowexa-react-divider">
          <span class="flowexa-react-count">{{ $comment['likes'] ?? 0 }}</span>
        </div>

        <div class="flowexa-comment-container">
          <div class="flowexa-comment-user">
            <div class="flowexa-comment-avatar {{ ($comment['online'] ?? false) ? 'flowexa-user-online' : '' }}">
              @if($comment['user_avatar'] ?? null)
                <img src="{{ $comment['user_avatar'] }}" alt="{{ $comment['user_name'] }}">
              @else
                <i class="fa-regular fa-user"></i>
              @endif
            </div>
            <div class="flowexa-comment-user-info">
              <span class="flowexa-user-name">{{ $comment['user_name'] }}</span>
              <span class="flowexa-comment-date">{{ $comment['date_time'] }}</span>
            </div>
          </div>
          <p class="flowexa-comment-body">
            {{ $comment['content'] }}
          </p>
        </div>
      </div>
    @endforeach
  </div>

  <form action="{{ $submitUrl }}" method="POST" class="flowexa-comment-form">
    @csrf
    <div class="flowexa-comment-textarea-wrapper">
      <textarea name="comment" placeholder="{{ $placeholder }}" required></textarea>
      <div class="flowexa-comment-form-toolbar">
        <div class="flowexa-comment-formatting">
          <button type="button" title="Bold"><i class="fa-solid fa-bold"></i></button>
          <button type="button" title="Italic"><i class="fa-solid fa-italic"></i></button>
          <button type="button" title="Underline"><i class="fa-solid fa-underline"></i></button>
          <button type="button" title="Strikethrough"><i class="fa-solid fa-strikethrough"></i></button>
          <button type="button" title="Emoji"><i class="fa-regular fa-face-smile"></i></button>
        </div>
        <button type="submit" class="flowexa-comment-send" title="Send">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </div>
    </div>
  </form>
</div>

<style>
.flowexa-comments-card {
  width: 100%;
  max-width: 500px;
  background-color: var(--surface);
  border: 1px solid var(--border);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
  border-radius: 16px;
  overflow: hidden;
  color: var(--body-text);
  display: flex;
  flex-direction: column;
}

.flowexa-comments-title {
  padding: 1.25rem;
  border-bottom: 1px solid var(--divider);
  font-size: 1rem;
  font-weight: 700;
  color: var(--headings);
  margin: 0;
}

.flowexa-comments-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding: 1.25rem;
  max-height: 400px;
  overflow-y: auto;
}

.flowexa-comment-item {
  display: grid;
  grid-template-columns: 40px 1fr;
  gap: 1rem;
}

.flowexa-comment-react {
  display: flex;
  flex-direction: column;
  background-color: var(--surface-secondary);
  border: 1px solid var(--border);
  border-radius: 8px;
  align-items: center;
  justify-content: center;
  padding: 6px 0;
  height: fit-content;
}

.flowexa-react-btn {
  background: transparent;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  font-size: 0.85rem;
  padding: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s, transform 0.2s;
}

.flowexa-react-btn:hover {
  color: #ef4444;
  transform: scale(1.15);
}

.flowexa-react-divider {
  width: 60%;
  border: 0;
  border-top: 1px solid var(--border);
  margin: 4px 0;
}

.flowexa-react-count {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--text-primary);
}

.flowexa-comment-container {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.flowexa-comment-user {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.flowexa-comment-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: var(--surface-secondary);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  color: var(--text-secondary);
  position: relative;
}

.flowexa-comment-avatar img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
}

.flowexa-user-online::after {
  content: '';
  width: 8px;
  height: 8px;
  background-color: var(--success);
  border: 2px solid var(--surface);
  border-radius: 50%;
  position: absolute;
  bottom: 0;
  right: 0;
}

.flowexa-comment-user-info {
  display: flex;
  flex-direction: column;
}

.flowexa-user-name {
  font-weight: 700;
  font-size: 0.85rem;
  color: var(--headings);
}

.flowexa-comment-date {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.flowexa-comment-body {
  font-size: 0.85rem;
  line-height: 1.5;
  color: var(--body-text);
  margin: 0;
}

.flowexa-comment-form {
  background-color: var(--surface-secondary);
  padding: 0.75rem;
  border-top: 1px solid var(--border);
}

.flowexa-comment-textarea-wrapper {
  background-color: var(--surface);
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.flowexa-comment-textarea-wrapper textarea {
  width: 100%;
  min-height: 60px;
  border: none;
  outline: none;
  resize: vertical;
  padding: 0.75rem;
  font-size: 0.875rem;
  background-color: var(--surface);
  color: var(--text-primary);
  font-family: inherit;
}

.flowexa-comment-form-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.5rem 0.75rem;
  border-top: 1px solid var(--divider);
  background-color: var(--surface-secondary);
}

.flowexa-comment-formatting {
  display: flex;
  gap: 0.5rem;
}

.flowexa-comment-formatting button {
  background: transparent;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  width: 28px;
  height: 28px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  transition: all 0.2s;
}

.flowexa-comment-formatting button:hover {
  background-color: var(--surface);
  color: var(--text-primary);
}

.flowexa-comment-send {
  background-color: var(--primary);
  border: none;
  color: #ffffff;
  cursor: pointer;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  transition: background-color 0.2s, transform 0.2s;
}

.flowexa-comment-send:hover {
  background-color: var(--primary-hover);
  transform: scale(1.05);
}
</style>
