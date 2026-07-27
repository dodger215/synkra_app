<x-layouts.app title="Page Settings - {{ $page->page_name }}">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('ecommerce.pages.index', $store->id) }}" style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; font-weight: 600;">
                <i class="fa-solid fa-arrow-left"></i> Back to Pages
            </a>
            <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Page Settings</h1>
            <p style="color: var(--text-secondary); margin: 0;">Update configuration for <strong>{{ $page->page_name }}</strong>.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('ecommerce.pages.update', [$store->id, $page->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1.5rem;">
                    <label for="page_name" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Page Name</label>
                    <input type="text" name="page_name" id="page_name" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;" value="{{ old('page_name', $page->page_name) }}">
                    @error('page_name')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="slug" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Page Slug (URL Path)</label>
                    <div style="display: flex; align-items: center;">
                        <span style="background: var(--surface-secondary); border: 1px solid var(--border); border-right: none; padding: 0.75rem 1rem; border-radius: 12px 0 0 12px; color: var(--text-secondary); font-size: 0.9rem;">/</span>
                        <input type="text" name="slug" id="slug" required style="flex: 1; padding: 0.75rem 1rem; border-radius: 0 12px 12px 0; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;" value="{{ old('slug', $page->slug) }}">
                    </div>
                    @error('slug')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Publication Status</label>
                    <div style="display: flex; gap: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="is_published" value="1" {{ $page->is_published ? 'checked' : '' }}>
                            <span>Published</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="is_published" value="0" {{ !$page->is_published ? 'checked' : '' }}>
                            <span>Draft</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
                    <button type="button" class="flowexa-btn" style="color: #ef4444; background: transparent; border: none; font-weight: 600; cursor: pointer;" onclick="if(confirm('Are you sure you want to delete this page?')) document.getElementById('delete-form').submit();">
                        <i class="fa-solid fa-trash"></i> Delete Page
                    </button>

                    <button type="submit" class="flowexa-btn-primary" style="background: var(--primary); color: white; border: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Save Changes
                    </button>
                </div>
            </form>

            <form id="delete-form" action="{{ route('ecommerce.pages.destroy', [$store->id, $page->id]) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-layouts.app>
