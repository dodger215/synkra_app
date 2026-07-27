<x-layouts.app title="Create Page - {{ $store->store_name }}">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('ecommerce.pages.index', $store->id) }}" style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; font-weight: 600;">
                <i class="fa-solid fa-arrow-left"></i> Back to Pages
            </a>
            <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Add New Page</h1>
            <p style="color: var(--text-secondary); margin: 0;">Create a new page for your <strong>{{ $store->store_name }}</strong> store.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('ecommerce.pages.store', $store->id) }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.5rem;">
                    <label for="page_name" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Page Name</label>
                    <input type="text" name="page_name" id="page_name" required placeholder="e.g. Home, About Us, Summer Collection" style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;" value="{{ old('page_name') }}">
                    @error('page_name')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="slug" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Page Slug (URL Path)</label>
                    <div style="display: flex; align-items: center;">
                        <span style="background: var(--surface-secondary); border: 1px solid var(--border); border-right: none; padding: 0.75rem 1rem; border-radius: 12px 0 0 12px; color: var(--text-secondary); font-size: 0.9rem;">/</span>
                        <input type="text" name="slug" id="slug" required placeholder="home" style="flex: 1; padding: 0.75rem 1rem; border-radius: 0 12px 12px 0; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;" value="{{ old('slug') }}">
                    </div>
                    @error('slug')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 1rem;">Select Page Template</label>
                    <input type="hidden" name="page_type" id="page_type" value="custom">

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">
                        {{-- Blank Canvas --}}
                        <div class="template-select-card active" data-type="custom" onclick="selectTemplate(this)">
                            <div class="template-preview" style="background: var(--surface-secondary); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                                <div style="position: absolute; inset: 0; opacity: 0.05; background-image: radial-gradient(var(--text-secondary) 1px, transparent 1px); background-size: 15px 15px;"></div>
                                <i class="fa-solid fa-plus" style="font-size: 2.5rem; color: var(--text-secondary); opacity: 0.3;"></i>
                            </div>
                            <div class="template-info">
                                <span class="template-name">Blank Canvas</span>
                                <span class="template-desc">Build your unique vision from zero</span>
                            </div>
                            <div class="check-badge"><i class="fa-solid fa-check"></i></div>
                        </div>

                        {{-- Home Page --}}
                        <div class="template-select-card" data-type="home" onclick="selectTemplate(this)">
                            <div class="template-preview" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); padding: 15px; display: flex; flex-direction: column; gap: 8px;">
                                <div style="height: 15px; width: 40%; background: var(--primary); opacity: 0.2; border-radius: 4px;"></div>
                                <div style="height: 40px; width: 100%; background: var(--primary); opacity: 0.1; border-radius: 6px;"></div>
                                <div style="display: flex; gap: 6px;">
                                    <div style="height: 25px; flex: 1; background: var(--primary); opacity: 0.1; border-radius: 4px;"></div>
                                    <div style="height: 25px; flex: 1; background: var(--primary); opacity: 0.1; border-radius: 4px;"></div>
                                    <div style="height: 25px; flex: 1; background: var(--primary); opacity: 0.1; border-radius: 4px;"></div>
                                </div>
                            </div>
                            <div class="template-info">
                                <span class="template-name">Home Page</span>
                                <span class="template-desc">Hero banner & featured products</span>
                            </div>
                            <div class="check-badge"><i class="fa-solid fa-check"></i></div>
                        </div>

                        {{-- Collection --}}
                        <div class="template-select-card" data-type="collection" onclick="selectTemplate(this)">
                            <div class="template-preview" style="background: #f0fdf4; padding: 15px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;">
                                @for($i=0; $i<6; $i++)
                                    <div style="aspect-ratio: 1; background: #22c55e; opacity: 0.1; border-radius: 4px;"></div>
                                @endfor
                            </div>
                            <div class="template-info">
                                <span class="template-name">Product List</span>
                                <span class="template-desc">Organized grid for collections</span>
                            </div>
                            <div class="check-badge"><i class="fa-solid fa-check"></i></div>
                        </div>

                        {{-- Product Detail --}}
                        <div class="template-select-card" data-type="product" onclick="selectTemplate(this)">
                            <div class="template-preview" style="background: #eff6ff; padding: 15px; display: flex; gap: 10px;">
                                <div style="width: 45%; height: 100%; background: #3b82f6; opacity: 0.1; border-radius: 6px;"></div>
                                <div style="flex: 1; display: flex; flex-direction: column; gap: 6px;">
                                    <div style="height: 10px; width: 80%; background: #3b82f6; opacity: 0.2; border-radius: 2px;"></div>
                                    <div style="height: 6px; width: 100%; background: #3b82f6; opacity: 0.1; border-radius: 2px;"></div>
                                    <div style="height: 6px; width: 60%; background: #3b82f6; opacity: 0.1; border-radius: 2px;"></div>
                                    <div style="height: 15px; width: 40%; background: #3b82f6; opacity: 0.2; border-radius: 4px; margin-top: auto;"></div>
                                </div>
                            </div>
                            <div class="template-info">
                                <span class="template-name">Product Showcase</span>
                                <span class="template-desc">Detailed view for single items</span>
                            </div>
                            <div class="check-badge"><i class="fa-solid fa-check"></i></div>
                        </div>
                    </div>
                    @error('page_type')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <style>
                    .template-select-card {
                        background: var(--surface-secondary);
                        border: 2px solid var(--border);
                        border-radius: 16px;
                        overflow: hidden;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        position: relative;
                    }
                    .template-select-card:hover {
                        border-color: var(--primary);
                        transform: translateY(-2px);
                        background: var(--surface);
                    }
                    .template-select-card.active {
                        border-color: var(--primary);
                        background: var(--surface);
                        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.1);
                    }
                    .template-preview {
                        height: 120px;
                        width: 100%;
                    }
                    .template-info {
                        padding: 1rem;
                    }
                    .template-name {
                        display: block;
                        font-weight: 700;
                        color: var(--headings);
                        font-size: 0.95rem;
                        margin-bottom: 0.25rem;
                    }
                    .template-desc {
                        display: block;
                        font-size: 0.75rem;
                        color: var(--text-secondary);
                    }
                    .check-badge {
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        background: var(--primary);
                        color: white;
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 0.8rem;
                        opacity: 0;
                        transform: scale(0.5);
                        transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }
                    .template-select-card.active .check-badge {
                        opacity: 1;
                        transform: scale(1);
                    }
                </style>

                <script>
                    function selectTemplate(el) {
                        document.querySelectorAll('.template-select-card').forEach(c => c.classList.remove('active'));
                        el.classList.add('active');
                        document.getElementById('page_type').value = el.dataset.type;
                    }
                </script>


                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="flowexa-btn-primary" style="background: var(--primary); color: white; border: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-save"></i> Save & Open Builder
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-slugify page name
        document.getElementById('page_name').addEventListener('input', function() {
            if (document.getElementById('slug').value === '' ||
                document.getElementById('slug').dataset.manual === 'false') {
                const slug = this.value.toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
                document.getElementById('slug').value = slug;
                document.getElementById('slug').dataset.manual = 'false';
            }
        });

        document.getElementById('slug').addEventListener('input', function() {
            this.dataset.manual = 'true';
        });
    </script>
</x-layouts.app>
