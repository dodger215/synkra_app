<x-layouts.app title="Page Builder - {{ $page->page_name }}">
    @php
        $allPages = $store->pages()->orderBy('sort_order')->get();
    @endphp

    <!-- Moveable.js for Pro Transformation Controls -->
    <script src="https://daybrush.com/moveable/release/latest/dist/moveable.min.js"></script>

    <div class="builder-root">
        {{-- Custom Top Bar --}}
        <header class="builder-topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="builder.toggleSidebar('left')" title="Toggle Elements">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <a href="{{ route('ecommerce.pages.index', $store->id) }}" class="back-btn" title="Back to Pages">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div class="page-selector">
                    <span class="store-name">{{ $store->store_name }}</span>
                    <div class="pages-tabs">
                        @foreach($allPages as $p)
                            <a href="{{ route('ecommerce.pages.builder', [$store->id, $p->id]) }}"
                               class="page-tab {{ $p->id === $page->id ? 'active' : '' }}">
                                {{ $p->page_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="topbar-center">
                <div class="viewport-controls">
                    <button class="vp-btn active" data-vp="desktop" title="Desktop View">
                        <i class="fa-solid fa-desktop"></i>
                    </button>
                    <button class="vp-btn" data-vp="tablet" title="Tablet View">
                        <i class="fa-solid fa-tablet-screen-button"></i>
                    </button>
                    <button class="vp-btn" data-vp="mobile" title="Mobile View">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </button>
                </div>
            </div>

            <div class="topbar-right">
                <div class="zoom-controls">
                    <button id="zoom-out"><i class="fa-solid fa-minus"></i></button>
                    <span id="zoom-level">100%</span>
                    <button id="zoom-in"><i class="fa-solid fa-plus"></i></button>
                </div>
                <a href="{{ route('ecommerce.pages.show', [$store->id, $page->id]) }}" target="_blank" class="sidebar-toggle" title="Preview Page" style="text-decoration: none; width: auto; padding: 0 12px; font-size: 0.85rem; font-weight: 700; gap: 8px;">
                    <i class="fa-solid fa-eye"></i> Preview
                </a>
                <button id="save-content" class="flowexa-btn-primary">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Save
                </button>
                <button class="sidebar-toggle" onclick="builder.toggleSidebar('right')" title="Toggle Properties">
                    <i class="fa-solid fa-sliders"></i>
                </button>
            </div>
        </header>


        <div class="builder-body">
            {{-- Left Sidebar --}}
            <aside class="sidebar left">
                <div class="sidebar-tabs">
                    <button class="sb-tab-btn active" data-tab="elements">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add</span>
                    </button>
                    <button class="sb-tab-btn" data-tab="layers">
                        <i class="fa-solid fa-layers-group"></i>
                        <span>Layers</span>
                    </button>
                    <button class="sb-tab-btn" data-tab="templates">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Templates</span>
                    </button>
                    <button class="sb-tab-btn" data-tab="pages">
                        <i class="fa-solid fa-file-lines"></i>
                        <span>Pages</span>
                    </button>
                </div>

                <div class="sidebar-content">
                    {{-- Elements Tab --}}
                    <div id="tab-elements" class="tab-pane active">
                        <div class="sidebar-search" style="margin-bottom: 1.5rem;">
                            <div style="position: relative;">
                                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.8rem;"></i>
                                <input type="text" placeholder="Search components..." style="width: 100%; padding: 0.6rem 0.6rem 0.6rem 2.2rem; border: 1px solid var(--border-color); border-radius: 10px; font-size: 0.85rem; outline: none; background: #f8fafc;">
                            </div>
                        </div>

                        <div class="section-title">Ecommerce Elements</div>
                        <div class="elements-grid" style="margin-bottom: 2rem;">
                            @php
                                $ecomElements = [
                                    ['type' => 'navbar', 'label' => 'Header / Nav', 'icon' => 'fa-window-maximize', 'desc' => 'Logo & navigation menu'],
                                    ['type' => 'product_grid', 'label' => 'Product Grid', 'icon' => 'fa-table-cells', 'desc' => 'Display multiple products'],
                                    ['type' => 'product_showcase', 'label' => 'Featured Product', 'icon' => 'fa-star', 'desc' => 'Highlight one item'],
                                    ['type' => 'filter_sidebar', 'label' => 'Filter Sidebar', 'icon' => 'fa-filter', 'desc' => 'Categories & price filters'],
                                    ['type' => 'cart_sidebar', 'label' => 'Cart Sidebar', 'icon' => 'fa-shopping-bag', 'desc' => 'Cart summary overlay'],
                                    ['type' => 'cart_button', 'label' => 'Cart Icon', 'icon' => 'fa-shopping-cart', 'desc' => 'Header cart access'],
                                    ['type' => 'category_list', 'label' => 'Categories', 'icon' => 'fa-tags', 'desc' => 'Category navigation'],
                                    ['type' => 'search_bar', 'label' => 'Store Search', 'icon' => 'fa-magnifying-glass', 'desc' => 'Product search field'],
                                ];
                            @endphp
                            @foreach($ecomElements as $et)
                                <div class="element-card" draggable="true" data-type="{{ $et['type'] }}">
                                    <div class="el-icon" style="background: #fff7ed; color: var(--primary-color); border-color: #ffedd5;">
                                        <i class="fa-solid {{ $et['icon'] }}"></i>
                                    </div>
                                    <div class="el-info">
                                        <span class="el-label">{{ $et['label'] }}</span>
                                        <span class="el-desc">{{ $et['desc'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="section-title">Basic Layout</div>
                        <div class="elements-grid">
                            @php
                                $elementTypes = [
                                    ['type' => 'heading', 'label' => 'Heading', 'icon' => 'fa-heading', 'desc' => 'Section titles'],
                                    ['type' => 'text', 'label' => 'Text Block', 'icon' => 'fa-font', 'desc' => 'Paragraph content'],
                                    ['type' => 'image', 'label' => 'Image', 'icon' => 'fa-image', 'desc' => 'Photos & illustrations'],
                                    ['type' => 'button', 'label' => 'Button', 'icon' => 'fa-square', 'desc' => 'Call to action'],
                                    ['type' => 'container', 'label' => 'Section', 'icon' => 'fa-box', 'desc' => 'Wrapper box'],
                                    ['type' => 'card', 'label' => 'Info Card', 'icon' => 'fa-id-card', 'desc' => 'Mixed content card'],
                                    ['type' => 'divider', 'label' => 'Divider', 'icon' => 'fa-grip-lines', 'desc' => 'Separator line'],
                                ];
                            @endphp
                            @foreach($elementTypes as $et)
                                <div class="element-card" draggable="true" data-type="{{ $et['type'] }}">
                                    <div class="el-icon">
                                        <i class="fa-solid {{ $et['icon'] }}"></i>
                                    </div>
                                    <div class="el-info">
                                        <span class="el-label">{{ $et['label'] }}</span>
                                        <span class="el-desc">{{ $et['desc'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    {{-- Layers Tab --}}
                    <div id="tab-layers" class="tab-pane">
                        <div class="section-title">Element Stack</div>
                        <div id="layers-list" class="layers-list">
                            {{-- Populated by JS --}}
                        </div>
                    </div>

                    {{-- Templates Tab --}}
                    <div id="tab-templates" class="tab-pane">
                        <div class="section-title">Design Templates</div>
                        <p style="font-size:0.72rem; color:var(--text-muted); margin-bottom:1rem; line-height:1.5;">Applying a template will <strong>replace all current pages</strong> with the template's pages.</p>
                        <div id="template-catalogue" style="display:flex; flex-direction:column; gap:0.75rem;">
                            <div style="text-align:center; padding:2rem; color:var(--text-muted); font-size:0.8rem;">
                                <i class="fa-solid fa-spinner fa-spin"></i> Loading templates...
                            </div>
                        </div>

                        <div class="section-title" style="margin-top:2rem;">Quick Sections</div>
                        <div style="display:flex; flex-direction:column; gap:0.75rem;">
                            @php
                                $sections = [
                                    ['id' => 'hero_main',    'name' => 'Hero Banner',       'icon' => 'fa-rectangle-ad'],
                                    ['id' => 'prod_grid_4', 'name' => '4-Column Products', 'icon' => 'fa-table-cells'],
                                    ['id' => 'promo_banner','name' => 'Promotion Banner',  'icon' => 'fa-tag'],
                                ];
                            @endphp
                            @foreach($sections as $s)
                                <div class="template-item-card" onclick="builder.importTemplate('{{ $s['id'] }}', true)" style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem; cursor:pointer;">
                                    <div style="width:36px; height:36px; background:#f1f5f9; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <i class="fa-solid {{ $s['icon'] }}" style="color:var(--primary-color); font-size:0.9rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:0.82rem; font-weight:700; color:var(--text-dark);">{{ $s['name'] }}</div>
                                        <div style="font-size:0.7rem; color:var(--text-muted);">Add to canvas</div>
                                    </div>
                                    <i class="fa-solid fa-plus" style="margin-left:auto; color:var(--text-muted); font-size:0.75rem;"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Template Apply Confirm Modal --}}
                    <div id="template-apply-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
                        <div style="background:white; border-radius:16px; padding:2rem; max-width:420px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.2);">
                            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                                <div style="width:40px; height:40px; background:#fff7ed; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa-solid fa-wand-magic-sparkles" style="color:var(--primary-color);"></i>
                                </div>
                                <div>
                                    <div style="font-weight:800; font-size:1rem; color:#0f172a;">Apply Template</div>
                                    <div style="font-size:0.75rem; color:#64748b;" id="modal-template-name"></div>
                                </div>
                            </div>
                            <p style="font-size:0.85rem; color:#475569; margin-bottom:1.5rem; line-height:1.6;">
                                This will <strong>delete all existing pages</strong> for this store and create new pages from the selected template. This action cannot be undone.
                            </p>
                            <div style="display:flex; gap:0.75rem;">
                                <button onclick="document.getElementById('template-apply-modal').style.display='none'" style="flex:1; padding:10px; border:1px solid #e2e8f0; border-radius:8px; background:white; font-weight:600; cursor:pointer; font-size:0.85rem;">Cancel</button>
                                <button id="modal-confirm-btn" style="flex:1; padding:10px; background:var(--primary-color); color:white; border:none; border-radius:8px; font-weight:700; cursor:pointer; font-size:0.85rem;">
                                    <i class="fa-solid fa-check"></i> Apply Template
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Pages Tab --}}
                    <div id="tab-pages" class="tab-pane">
                        <div class="section-title">Store Pages</div>
                        <div class="pages-list" style="display: flex; flex-direction: column; gap: 0.5rem;">
                            @foreach($allPages as $p)
                                <a href="{{ route('ecommerce.pages.builder', [$store->id, $p->id]) }}"
                                   class="page-item-card {{ $p->id === $page->id ? 'active' : '' }}"
                                   style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: {{ $p->id === $page->id ? '#fff7ed' : '#f8fafc' }}; border: 1px solid {{ $p->id === $page->id ? '#ffedd5' : 'var(--border-color)' }}; border-radius: 12px; text-decoration: none; color: {{ $p->id === $page->id ? 'var(--primary-color)' : 'var(--text-dark)' }}; transition: all 0.2s;">
                                    <i class="fa-solid fa-file-lines" style="color: {{ $p->id === $page->id ? 'var(--primary-color)' : 'var(--text-muted)' }};"></i>
                                    <span style="font-weight: 600; font-size: 0.85rem; flex: 1;">{{ $p->page_name }}</span>
                                    @if($p->id === $page->id)
                                        <i class="fa-solid fa-circle" style="font-size: 0.4rem; color: var(--primary-color);"></i>
                                    @endif
                                </a>
                            @endforeach
                            <a href="{{ route('ecommerce.pages.create', $store->id) }}"
                               class="page-item-card"
                               style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem; background: white; border: 1px dashed var(--primary-color); border-radius: 12px; text-decoration: none; color: var(--primary-color); margin-top: 1rem; font-weight: 700; font-size: 0.85rem; transition: all 0.2s;">
                                <i class="fa-solid fa-plus"></i> Add New Page
                            </a>
                        </div>
                    </div>

                    <style>
                        .template-item-card {
                            background: white;
                            border: 1px solid var(--border-color);
                            border-radius: 12px;
                            padding: 0.5rem;
                            cursor: pointer;
                            transition: all 0.2s ease;
                        }
                        .template-item-card:hover {
                            border-color: var(--primary-color);
                            transform: scale(1.02);
                            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                        }
                    </style>

                </div>
            </aside>

            {{-- Canvas Wrapper --}}
            <main class="canvas-wrapper" id="canvas-wrapper">
                <div id="canvas-viewport" class="canvas-viewport desktop">
                    <div id="canvas" class="canvas">
                        <div id="canvas-inner" style="position: relative; width: 100%; height: 100%; min-height: 800px;">
                            <div id="canvas-elements" style="position: absolute; inset: 0;"></div>
                            <div id="canvas-footer" style="position: absolute; left: 0; right: 0;"></div>
                            {{-- Empty Hint --}}
                            <div id="empty-hint" class="empty-hint">
                                <i class="fa-solid fa-plus-circle"></i>
                                <p>Drag and drop elements here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            {{-- Right Sidebar --}}
            <aside class="sidebar right">
                <div class="sidebar-header">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Properties</span>
                </div>
                <div id="properties-panel" class="properties-container">
                    <div class="no-selection">
                        <p>Select an element to edit</p>
                    </div>
                </div>
                <div style="padding: 1rem; border-top: 1px solid var(--border-color); background: #f8fafc;">
                    <button class="flowexa-btn-primary" style="width: 100%; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onclick="document.getElementById('save-content').click();">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </aside>
        </div>
    </div>

    <style>
        :root {
            --sidebar-width: 280px;
            --topbar-height: 64px;
            --builder-bg: #f1f5f9;
            --panel-bg: #ffffff;
            --border-color: #e2e8f0;
            --primary-color: #f97316;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        .builder-root {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            display: flex;
            flex-direction: column;
            background: var(--builder-bg);
            z-index: 1000;
        }

        /* Topbar */
        .builder-topbar {
            height: var(--topbar-height);
            background: var(--panel-bg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.25rem;
            z-index: 100;
        }

        .sidebar-toggle {
            background: #f1f5f9;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            width: 36px;
            height: 36px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .sidebar-toggle:hover { background: white; border-color: var(--primary-color); color: var(--primary-color); }

        .topbar-left { display: flex; align-items: center; gap: 1rem; }
        .back-btn { color: var(--text-muted); font-size: 1.1rem; text-decoration: none; }
        .back-btn:hover { color: var(--text-dark); }

        .page-selector { display: flex; align-items: center; gap: 1rem; }
        .store-name { font-weight: 800; color: var(--text-dark); font-size: 0.95rem; }
        .pages-tabs { display: flex; background: #f1f5f9; padding: 4px; border-radius: 8px; gap: 2px; }
        .page-tab {
            padding: 4px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem;
            font-weight: 600; color: var(--text-muted); transition: all 0.2s;
        }
        .page-tab.active { background: white; color: var(--primary-color); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        .viewport-controls { display: flex; background: #f1f5f9; padding: 4px; border-radius: 10px; }
        .vp-btn {
            border: none; background: transparent; padding: 6px 12px; border-radius: 8px;
            cursor: pointer; color: var(--text-muted); font-size: 0.9rem;
        }
        .vp-btn.active { background: white; color: var(--primary-color); }

        .topbar-right { display: flex; align-items: center; gap: 1.25rem; }
        .zoom-controls { display: flex; align-items: center; gap: 0.75rem; color: var(--text-muted); font-size: 0.85rem; font-weight: 700; }
        .zoom-controls button { border: none; background: transparent; cursor: pointer; color: var(--text-muted); padding: 4px; }

        /* Body */
        .builder-body { flex: 1; display: flex; overflow: hidden; position: relative; }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--panel-bg);
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 50;
        }
        .sidebar.left { border-right: 1px solid var(--border-color); }
        .sidebar.right { border-left: 1px solid var(--border-color); width: 320px; }

        .sidebar.collapsed { width: 0; padding: 0; overflow: hidden; border: none; }

        .sidebar-tabs { display: flex; border-bottom: 1px solid var(--border-color); padding: 0.5rem; gap: 0.5rem; flex-shrink: 0; }
        .sb-tab-btn {
            flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px 0;
            border: none; background: transparent; color: var(--text-muted); cursor: pointer; border-radius: 8px;
        }
        .sb-tab-btn.active { background: #fff7ed; color: var(--primary-color); }
        .sb-tab-btn span { font-size: 0.7rem; font-weight: 700; }

        .sidebar-content { flex: 1; overflow-y: auto; scrollbar-width: thin; }
        .tab-pane { display: none; padding: 1rem; }
        .tab-pane.active { display: block; }
        .section-title { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1rem; letter-spacing: 0.05em; }

        .elements-grid { display: flex; flex-direction: column; gap: 0.75rem; }
        .element-card {
            display: flex; align-items: center; gap: 1rem; padding: 0.75rem; background: #f8fafc;
            border: 1px solid var(--border-color); border-radius: 12px; cursor: grab; transition: all 0.2s;
        }
        .element-card:hover { border-color: var(--primary-color); background: white; transform: translateY(-2px); }
        .el-icon {
            width: 36px; height: 36px; background: white; border: 1px solid var(--border-color);
            display: flex; align-items: center; justify-content: center; border-radius: 10px; color: var(--text-dark);
        }
        .element-card:hover .el-icon { color: var(--primary-color); border-color: var(--primary-color); }
        .el-info { display: flex; flex-direction: column; }
        .el-label { font-size: 0.85rem; font-weight: 700; color: var(--text-dark); }
        .el-desc { font-size: 0.7rem; color: var(--text-muted); }

        /* Canvas */
        .canvas-wrapper { flex: 1; overflow: auto; padding: 4rem; display: flex; justify-content: center; align-items: flex-start; scrollbar-width: thin; }
        .canvas-viewport { background: white; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1); border-radius: 4px; transition: width 0.3s ease; height: auto; min-height: 800px; position: relative; }
        .canvas-viewport.desktop { width: 100%; max-width: 1200px; }
        .canvas-viewport.tablet { width: 768px; }
        .canvas-viewport.mobile { width: 375px; }
        .canvas { position: relative; width: 100%; min-height: 800px; }

        /* Smart Guides & Moveable Styling */
        .moveable-control-box { --moveable-color: #f06292 !important; z-index: 1000 !important; }
        .moveable-line.moveable-snap-line { background: #f06292 !important; width: 1.5px !important; height: 1.5px !important; }
        .moveable-control.moveable-snap-control { border-color: #f06292 !important; border-width: 2px !important; }
        .moveable-guideline { background: #f06292 !important; width: 1px !important; height: 1px !important; }
        .moveable-snap-digit {
            color: #f06292 !important; font-size: 11px; font-weight: 800;
            background: white; padding: 2px 6px; border-radius: 4px;
            border: 1px solid #f06292; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1001;
        }
        .moveable-direction { background: #f06292 !important; border: 2px solid white !important; width: 12px !important; height: 12px !important; border-radius: 50% !important; }
        .moveable-line { background: #f06292 !important; height: 1.5px !important; }

        .empty-hint {
            position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center;
            justify-content: center; color: #cbd5e1; pointer-events: none;
        }
        .empty-hint i { font-size: 2.5rem; margin-bottom: 1rem; }

        /* Canvas Elements */
        .canvas-el {
            position: absolute; cursor: move; border: 1px solid transparent;
            box-sizing: border-box; min-width: 20px; min-height: 20px;
            max-width: 100%;
        }
        .canvas-el:hover { border: 1px solid rgba(249, 115, 22, 0.4); }
        .canvas-el.selected { border: 1px solid var(--primary-color); z-index: 100 !important; }

        /* Moveable Customization */
        .moveable-control-box { z-index: 1000 !important; }

        .el-toolbar {
            position: absolute; top: -36px; right: 0; background: var(--primary-color);
            color: white; border-radius: 6px; padding: 4px 8px; display: none; gap: 8px; z-index: 100;
        }
        .canvas-el.selected .el-toolbar { display: flex; }
        .tool-btn { border: none; background: transparent; color: white; cursor: pointer; font-size: 0.8rem; }

        .flowexa-btn-primary {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .flowexa-btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

        /* Properties */
        .sidebar-header { padding: 1.25rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 0.75rem; font-weight: 700; color: var(--text-dark); flex-shrink: 0; }
        .properties-container { padding: 1.25rem; overflow-y: auto; flex: 1; scrollbar-width: thin; }
        .no-selection { text-align: center; color: var(--text-muted); padding: 3rem 0; font-size: 0.85rem; }

        .prop-group { margin-bottom: 1.5rem; }
        .prop-label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.6rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .prop-input { width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 8px; background: #f8fafc; color: var(--text-dark); font-size: 0.875rem; outline: none; }
        .prop-input:focus { border-color: var(--primary-color); background: white; }
    </style>

    <script>
        class PageBuilder {
            constructor(pageId, initialContent) {
                this.pageId = pageId;

                // Initialize store products early
                if (!window.storeProducts) window.storeProducts = [];

                if (Array.isArray(initialContent)) {
                    this.elements = initialContent;
                    this.footer = this.getDefaultFooter();
                    this.themeCss = '';
                } else {
                    this.elements = initialContent.elements || [];
                    this.footer = initialContent.footer || this.getDefaultFooter();
                    this.themeCss = initialContent.theme_css || '';
                }

                this.selectedId = null;
                this.zoom = 1;
                this.sidebars = { left: true, right: true };
                this.moveable = null;

                this.canvas = document.getElementById('canvas-inner');
                this.elementsContainer = document.getElementById('canvas-elements');
                this.footerContainer = document.getElementById('canvas-footer');
                this.emptyHint = document.getElementById('empty-hint');
                this.propsPanel = document.getElementById('properties-panel');

                this.init();
            }

            getDefaultFooter() {
                return {
                    template: 'standard',
                    styles: { backgroundColor: '#1e293b', color: '#ffffff', padding: '40px 40px', fontSize: '14px', minHeight: '200px' },
                    content: {
                        aboutTitle: 'About Us',
                        aboutText: 'We provide the best shopping experience with curated products.',
                        copyright: '© 2025 ' + document.querySelector('.store-name').innerText + '. All rights reserved.',
                        links: [{ label: 'Privacy Policy', url: '#' }, { label: 'Terms of Service', url: '#' }, { label: 'Returns', url: '#' }]
                    }
                };
            }

            injectThemeCss() {
                if (!this.themeCss) return;
                let el = document.getElementById('canvas-theme-style');
                if (!el) {
                    el = document.createElement('style');
                    el.id = 'canvas-theme-style';
                    document.head.appendChild(el);
                }
                // Scope the CSS to the canvas so it doesn't affect the builder UI
                const scoped = this.themeCss
                    .replace(/@import url\([^)]+\);?\s*/g, '')   // remove font imports (loaded separately)
                    .replace(/body\s*\{/g, '#canvas-inner {')
                    .replace(/\bhtml\b/g, '#canvas-inner');
                el.textContent = scoped;

                // Inject font link if present
                const fontMatch = this.themeCss.match(/@import url\(['"]?(https:\/\/fonts\.googleapis\.com\/[^'")\s]+)/);
                if (fontMatch && !document.querySelector(`link[href*="fonts.googleapis.com"][data-canvas-font]`)) {
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = fontMatch[1];
                    link.setAttribute('data-canvas-font', '1');
                    document.head.appendChild(link);
                }
            }

            init() {
                this.injectThemeCss();
                this.render();
                this.setupEventListeners();
                this.setupDragDrop();
                this.initMoveable();
            }


            initMoveable() {
                this.moveable = new Moveable(this.canvas, {
                    target: null,
                    container: this.canvas,
                    draggable: true,
                    resizable: true,
                    rotatable: true,
                    snappable: true,
                    skewable: true,
                    warpable: true,
                    // Snapping & Guides
                    isDisplaySnapDigit: true,
                    isDisplayInnerSnapDigit: true,
                    snapGap: true,
                    snapThreshold: 5,
                    snapCenter: true,
                    snapDigit: 0,
                    snapDirections: { "top": true, "left": true, "bottom": true, "right": true, "center": true, "middle": true },
                    elementSnapDirections: { "top": true, "left": true, "bottom": true, "right": true, "center": true, "middle": true },
                    elementGuidelines: [],
                    // Guidelines for canvas edges
                    verticalGuidelines: [0, 1000],
                    horizontalGuidelines: [0, 800],
                    // Transformation controls
                    renderDirections: ["nw", "n", "ne", "w", "e", "sw", "s", "se"],
                    origin: false,
                    padding: { left: 0, top: 0, right: 0, bottom: 0 },
                    edge: true,
                    keepRatio: false,
                    throttleResize: 1,
                    throttleDrag: 1,
                    throttleRotate: 1,
                });

                this.moveable.on("drag", e => {
                    e.target.style.left = `${e.left}px`;
                    e.target.style.top = `${e.top}px`;
                    this.updateData(e.target.id, { left: e.left, top: e.top });
                }).on("resize", e => {
                    e.target.style.width = `${e.width}px`;
                    e.target.style.height = `${e.height}px`;
                    e.target.style.transform = e.drag.transform;
                    this.updateData(e.target.id, {
                        width: e.width + 'px',
                        height: e.height + 'px',
                        transform: e.drag.transform
                    });
                }).on("rotate", e => {
                    e.target.style.transform = e.drag.transform;
                    this.updateData(e.target.id, { transform: e.drag.transform });
                }).on("skew", e => {
                    e.target.style.transform = e.drag.transform;
                    this.updateData(e.target.id, { transform: e.drag.transform });
                }).on("warp", e => {
                    e.target.style.transform = e.transform;
                    this.updateData(e.target.id, { transform: e.transform });
                }).on("render", e => {
                    this.updateCanvasHeight();
                }).on("renderEnd", () => {
                    this.updateCanvasHeight();
                });
            }

            updateData(id, styles) {
                const el = this.elements.find(e => e.id === id);
                if (el) {
                    Object.assign(el.styles, styles);
                    this.renderProperties();
                }
            }

            toggleSidebar(side) {
                const sidebar = document.querySelector(`.sidebar.${side}`);
                this.sidebars[side] = !this.sidebars[side];
                if (this.sidebars[side]) {
                    sidebar.classList.remove('collapsed');
                } else {
                    sidebar.classList.add('collapsed');
                }
                setTimeout(() => this.moveable.updateRect(), 300);
            }

            setupEventListeners() {
                try {
                    // Sidebar tabs
                    document.querySelectorAll('.sb-tab-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            document.querySelectorAll('.sb-tab-btn').forEach(b => b.classList.remove('active'));
                            btn.classList.add('active');
                            const tabId = btn.dataset.tab;
                            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                            const targetTab = document.getElementById(`tab-${tabId}`);
                            if (targetTab) targetTab.classList.add('active');
                        });
                    });

                    // Viewport controls
                    document.querySelectorAll('.vp-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            document.querySelectorAll('.vp-btn').forEach(b => b.classList.remove('active'));
                            btn.classList.add('active');
                            const vp = btn.dataset.vp;
                            const canvasVp = document.getElementById('canvas-viewport');
                            if (canvasVp) canvasVp.className = `canvas-viewport ${vp}`;
                        });
                    });

                    // Zoom
                    const zi = document.getElementById('zoom-in');
                    if (zi) zi.onclick = () => this.setZoom(this.zoom + 0.1);
                    const zo = document.getElementById('zoom-out');
                    if (zo) zo.onclick = () => this.setZoom(this.zoom - 0.1);

                    // Deselect
                    const cw = document.getElementById('canvas-wrapper');
                    if (cw) {
                        cw.onclick = (e) => {
                            if (e.target.id === 'canvas-wrapper' || e.target.id === 'canvas' || e.target.id === 'canvas-inner') {
                                this.selectElement(null);
                            }
                        };
                    }

                    // Save
                    const sc = document.getElementById('save-content');
                    if (sc) sc.onclick = () => this.save();
                } catch(e) {
                    console.error('EventListeners setup error', e);
                }
            }

            setupDragDrop() {
                document.querySelectorAll('.element-card').forEach(card => {
                    card.addEventListener('dragstart', (e) => {
                        e.dataTransfer.setData('type', card.dataset.type);
                    });
                });

                this.canvas.addEventListener('dragover', (e) => e.preventDefault());
                this.canvas.addEventListener('drop', (e) => {
                    e.preventDefault();
                    const type = e.dataTransfer.getData('type');
                    const rect = this.canvas.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / this.zoom;
                    const y = (e.clientY - rect.top) / this.zoom;
                    this.addElement(type, x, y);
                });
            }

            setZoom(val) {
                this.zoom = Math.max(0.5, Math.min(2, val));
                this.canvas.style.transform = `scale(${this.zoom})`;
                this.canvas.style.transformOrigin = 'top center';
                document.getElementById('zoom-level').innerText = `${Math.round(this.zoom * 100)}%`;
                if (this.moveable) this.moveable.updateRect();
            }

            addElement(type, x, y) {
                const id = 'el_' + Math.random().toString(36).substr(2, 9);
                const newEl = {
                    id: id,
                    type: type,
                    content: this.getDefaultContent(type),
                    styles: {
                        left: x,
                        top: y,
                        width: 'auto',
                        padding: '16px',
                        backgroundColor: '#ffffff',
                        color: '#0f172a',
                        borderRadius: '8px',
                        fontSize: '16px'
                    }
                };

                this.elements.push(newEl);
                this.render();
                this.selectElement(id);
            }

            getDefaultContent(type) {
                switch(type) {
                    case 'navbar': return { logo: 'flowexa', links: ['Shop', 'Collections', 'About', 'Contact'], style: 'modern' };
                    case 'heading': return 'New Heading';
                    case 'text': return 'Double click to edit text content.';
                    case 'button': return 'Shop Now';
                    case 'image': return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&h=500&fit=crop';
                    case 'product_grid': return { title: 'New Arrivals', limit: 4, columns: 4 };
                    case 'product_showcase': return { product_id: null, layout: 'left' };
                    case 'filter_sidebar': return { categories: ['All', 'Electronics', 'Fashion', 'Home'], priceRange: [0, 1000] };
                    case 'cart_sidebar': return { title: 'Your Bag', items: [] };
                    case 'search_bar': return 'Search products...';
                    case 'html_block': return '<div style="padding:40px;text-align:center;color:#94a3b8;">HTML Block — double click to edit</div>';
                    default: return 'New Element';
                }
            }

            importTemplate(templateId, isSection = false) {
                // Predefined template layouts
                const templates = {
                    'modern_minimal': [
                        { type: 'navbar', content: { logo: '{{ $store->store_name }}', links: ['Products', 'Contact'], style: 'minimal' }, styles: { left: 0, top: 0, width: '100%', padding: '24px 60px', backgroundColor: 'transparent', color: '#ffffff', zIndex: 100 } },
                        { type: 'container', content: '', styles: { left: 0, top: 0, width: '100%', padding: '0', backgroundColor: '#9b113a', borderRadius: '40px', height: '900px' } },
                        { type: 'heading', content: 'Sleep 30 Dissolvable Wafers', styles: { left: 80, top: 280, fontSize: '64px', color: '#ffffff', width: '450px', fontWeight: '800', lineHeight: '1.1' } },
                        { type: 'text', content: '250 mg', styles: { left: 80, top: 480, fontSize: '20px', color: 'rgba(255,255,255,0.7)', width: '200px' } },
                        { type: 'image', content: 'https://images.unsplash.com/photo-1550572566-9592ea8ff67d?w=800', styles: { left: 450, top: 200, width: '400px', height: 'auto', transform: 'rotate(0deg)' } },
                        { type: 'text', content: '{{ $store->currency ?? 'GH₵' }} 25.50', styles: { left: 450, top: 780, fontSize: '32px', color: '#ffffff', fontWeight: '800' } },
                        { type: 'button', content: 'Buy Now', styles: { left: 800, top: 775, backgroundColor: '#ffffff', color: '#9b113a', padding: '12px 32px', borderRadius: '30px', fontWeight: '700', fontSize: '16px' } }
                    ],
                    'retail_catalog': [
                        { type: 'navbar', content: { logo: '{{ $store->store_name }}', links: ['Women', 'Men', 'Kids', 'Sports'], style: 'retail' }, styles: { left: 0, top: 0, width: '100%', padding: '20px 60px', backgroundColor: '#ffffff', borderBottom: '1px solid #eee' } },
                        { type: 'filter_sidebar', content: { categories: ['Shoes', 'Bags', 'Accessories'], priceRange: [0, 500] }, styles: { left: 60, top: 120, width: '240px', padding: '0' } },
                        { type: 'heading', content: 'New Collection', styles: { left: 340, top: 120, fontSize: '32px', color: '#0f172a', fontWeight: '800' } },
                        { type: 'product_grid', content: { title: '', limit: 9, columns: 3 }, styles: { left: 340, top: 180, width: 'calc(100% - 400px)', padding: '0' } }
                    ],
                    'luxury_product': [
                        { type: 'navbar', content: { logo: '{{ $store->store_name }}', links: ['Collection', 'Brand', 'Stores'], style: 'modern' }, styles: { left: 0, top: 0, width: '100%', padding: '30px 80px', backgroundColor: '#ffffff' } },
                        { type: 'product_showcase', content: { product_id: null, layout: 'left' }, styles: { left: 80, top: 120, width: 'calc(100% - 160px)', padding: '60px', backgroundColor: '#f9f9f9', borderRadius: '32px' } },
                        { type: 'heading', content: 'Recommended for you', styles: { left: 80, top: 750, fontSize: '24px', fontWeight: '800' } },
                        { type: 'product_grid', content: { title: '', limit: 4, columns: 4 }, styles: { left: 80, top: 800, width: 'calc(100% - 160px)', padding: '0' } }
                    ],
                    'streetwear_hub': [
                        { type: 'navbar', content: { logo: '{{ $store->store_name }}', links: ['New In', 'Apparel', 'Accessories'], style: 'minimal' }, styles: { left: 0, top: 0, width: '100%', padding: '24px 60px', backgroundColor: 'transparent', color: '#ffffff', zIndex: 100 } },
                        { type: 'container', content: '', styles: { left: 0, top: 0, width: '100%', padding: '0', backgroundColor: '#111111', height: '800px' } },
                        { type: 'heading', content: 'LIMITLESS STYLE', styles: { left: 60, top: 250, fontSize: '120px', color: '#ffffff', fontWeight: '900', letterSpacing: '-5px', opacity: 0.1 } },
                        { type: 'heading', content: 'DROP 01 / WINTER 24', styles: { left: 60, top: 380, fontSize: '48px', color: '#ffffff', fontWeight: '800' } },
                        { type: 'button', content: 'View Drop', styles: { left: 60, top: 480, backgroundColor: '#ffffff', color: '#000000', padding: '16px 48px', borderRadius: '0', fontWeight: '800' } },
                        { type: 'image', content: 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=800', styles: { left: 550, top: 150, width: '400px', height: '550px', borderRadius: '20px' } }
                    ],
                    'organic_living': [
                        { type: 'navbar', content: { logo: '{{ $store->store_name }}', links: ['Face', 'Body', 'Hair'], style: 'modern' }, styles: { left: 0, top: 0, width: '100%', padding: '20px 80px', backgroundColor: '#fdfbf7' } },
                        { type: 'container', content: '', styles: { left: 0, top: 0, width: '100%', height: '600px', backgroundColor: '#fdfbf7' } },
                        { type: 'heading', content: 'Kind to your skin, kind to the planet.', styles: { left: 0, top: 180, width: '100%', textAlign: 'center', fontSize: '42px', fontWeight: '500', color: '#4a5a4a' } },
                        { type: 'text', content: 'Discover our new range of 100% organic skincare products.', styles: { left: 0, top: 250, width: '100%', textAlign: 'center', fontSize: '18px', color: '#7a8a7a' } },
                        { type: 'button', content: 'Explore Range', styles: { left: 500, top: 320, backgroundColor: '#4a5a4a', color: '#ffffff', padding: '12px 32px', borderRadius: '30px' } },
                        { type: 'product_grid', content: { title: 'Best Sellers', limit: 4, columns: 4 }, styles: { left: 80, top: 500, width: 'calc(100% - 160px)', padding: '0' } }
                    ],
                    'hero_main': [
                        { type: 'container', content: '', styles: { left: 0, top: 0, width: '100%', padding: '100px 40px', backgroundColor: '#f8fafc', borderRadius: '0px' } },
                        { type: 'heading', content: 'Elevate Your Style', styles: { left: 40, top: 140, fontSize: '56px', color: '#0f172a', width: '600px', fontWeight: '800' } },
                        { type: 'text', content: 'Discover our latest collection of premium accessories designed for the modern individual.', styles: { left: 40, top: 280, fontSize: '20px', color: '#64748b', width: '500px' } },
                        { type: 'button', content: 'Explore Collection', styles: { left: 40, top: 380, backgroundColor: '#f97316', color: '#ffffff', padding: '14px 36px', borderRadius: '12px', fontSize: '18px', fontWeight: '700' } }
                    ],
                    'prod_grid_4': [
                        { type: 'heading', content: 'Featured Arrivals', styles: { left: 40, top: 60, fontSize: '28px', color: '#1e293b', fontWeight: '800' } },
                        { type: 'product_grid', content: { title: '', limit: 4, columns: 4 }, styles: { left: 0, top: 120, width: '100%', padding: '20px 40px' } }
                    ],
                    'promo_banner': [
                        { type: 'container', content: '', styles: { left: 0, top: 0, width: '100%', padding: '80px', backgroundColor: '#1e293b', borderRadius: '0px' } },
                        { type: 'heading', content: 'SEASON SALE: 50% OFF', styles: { left: 0, top: 100, fontSize: '42px', color: '#ffffff', width: '100%', textAlign: 'center', fontWeight: '800' } },
                        { type: 'button', content: 'Grab Offer Now', styles: { left: 0, top: 180, backgroundColor: '#ffffff', color: '#1e293b', padding: '12px 32px', borderRadius: '8px', fontWeight: '800', width: '200px', margin: '0 auto', position: 'relative' } }
                    ]
                };

                if (templates[templateId]) {
                    const newElements = templates[templateId].map(el => ({
                        ...el,
                        id: 'el_' + Math.random().toString(36).substr(2, 9),
                        styles: { ...this.getDefaultStyles(el.type), ...el.styles }
                    }));

                    if (isSection) {
                        // Append sections to current content
                        const lastY = this.elements.length > 0 ? Math.max(...this.elements.map(el => el.styles.top + (el.styles.height || 100))) : 0;
                        newElements.forEach(el => el.styles.top += (lastY + 40));
                        this.elements.push(...newElements);
                    } else {
                        // Replace full page
                        this.elements = newElements;
                    }

                    this.render();
                    this.selectElement(null);
                }
            }

            getDefaultStyles(type) {
                return {
                    left: 0, top: 0, width: 'auto', padding: '16px',
                    backgroundColor: '#ffffff', color: '#0f172a',
                    borderRadius: '8px', fontSize: '16px'
                };
            }

            selectElement(id) {
                this.selectedId = id;
                this.render();
                this.renderProperties();

                const target = (id && id !== 'footer') ? document.getElementById(id) : null;
                this.moveable.target = target;

                if (id && id !== 'footer') {
                    this.moveable.elementGuidelines = this.elements
                        .filter(el => el.id !== id)
                        .map(el => document.getElementById(el.id))
                        .filter(el => el !== null);
                }
            }

            updateCanvasHeight() {
                let maxBottom = 800;
                this.elements.forEach(el => {
                    const dom = document.getElementById(el.id);
                    if (dom) {
                        const rect = dom.getBoundingClientRect();
                        const canvasRect = this.canvas.getBoundingClientRect();
                        const bottom = (rect.bottom - canvasRect.top) / this.zoom;
                        if (bottom > maxBottom) maxBottom = bottom;
                    }
                });

                // Position footer at the bottom of elements
                const footerTop = maxBottom + 40;
                this.footerContainer.style.top = footerTop + 'px';

                const finalHeight = footerTop + this.footerContainer.offsetHeight;
                this.canvas.style.height = finalHeight + 'px';
                const vp = document.getElementById('canvas-viewport');
                if (vp) vp.style.height = finalHeight + 'px';
            }

            flip(axis) {
                if (!this.selectedId) return;
                const el = this.elements.find(e => e.id === this.selectedId);
                const dom = document.getElementById(this.selectedId);
                if (!el || !dom) return;

                let transform = el.styles.transform || '';
                if (axis === 'h') {
                    if (transform.includes('scaleX(-1)')) transform = transform.replace('scaleX(-1)', '');
                    else transform += ' scaleX(-1)';
                } else {
                    if (transform.includes('scaleY(-1)')) transform = transform.replace('scaleY(-1)', '');
                    else transform += ' scaleY(-1)';
                }
                el.styles.transform = transform;
                dom.style.transform = transform;
                this.moveable.updateRect();
            }

            extractRotate(transform) {
                if (!transform) return 0;
                const match = transform.match(/rotate\(([^)]+)deg\)/);
                return match ? Math.round(parseFloat(match[1])) : 0;
            }

            removeElement(id) {
                this.elements = this.elements.filter(el => el.id !== id);
                if (this.selectedId === id) this.selectedId = null;
                this.render();
                this.renderProperties();
            }

            duplicateElement(id) {
                const el = this.elements.find(e => e.id === id);
                if (!el) return;
                const newEl = JSON.parse(JSON.stringify(el));
                newEl.id = 'el_' + Math.random().toString(36).substr(2, 9);
                newEl.styles.top += 20;
                newEl.styles.left += 20;
                this.elements.push(newEl);
                this.render();
                this.selectElement(newEl.id);
            }

            updateElement(id, data) {
                const el = this.elements.find(e => e.id === id);
                if (el) {
                    Object.assign(el, data);
                    this.render();
                }
            }

            updateStyle(id, styleKey, value) {
                const el = this.elements.find(e => e.id === id);
                if (el) {
                    el.styles[styleKey] = value;
                    this.render();
                }
            }

            render() {
                try {
                    if (this.emptyHint) this.emptyHint.style.display = this.elements.length > 0 ? 'none' : 'flex';

                    this.elementsContainer.innerHTML = '';

                    this.elements.forEach((el, index) => {
                        const dom = document.createElement('div');
                        dom.className = `canvas-el ${this.selectedId === el.id ? 'selected' : ''}`;
                        dom.id = el.id;

                        // Styles
                        const baseStyles = {
                            position: 'absolute',
                            zIndex: this.selectedId === el.id ? 1000 : (index + 1),
                            height: el.styles.height || 'auto',
                        };

                        // Apply all styles from el.styles
                        Object.assign(dom.style, el.styles, baseStyles);

                        // Ensure numeric left/top have 'px'
                        if (typeof el.styles.left === 'number') dom.style.left = el.styles.left + 'px';
                        if (typeof el.styles.top === 'number') dom.style.top = el.styles.top + 'px';

                        // Content
                        let contentHtml = '';
                        let safeContent = (el.content === null || el.content === undefined) ? '' : el.content;

                        // Replace placeholder in builder for preview
                        const storeName = document.querySelector('.store-name')?.innerText || 'Store';
                        if (typeof safeContent === 'string') {
                            safeContent = safeContent.replace(/\[\[store_name\]\]/g, storeName);
                        } else if (typeof safeContent === 'object' && safeContent !== null) {
                            try {
                                safeContent = JSON.parse(JSON.stringify(safeContent).replace(/\[\[store_name\]\]/g, storeName));
                            } catch(e) { console.error('SafeContent error', e); }
                        }

                        if (el.type === 'navbar') contentHtml = this.renderNavbar(safeContent, el.styles);
                        else if (el.type === 'heading') contentHtml = `<h2 style="margin:0; font-size:inherit; color:inherit; font-family:inherit; text-align:inherit; font-weight:inherit; line-height:inherit; letter-spacing:inherit;">${safeContent}</h2>`;
                        else if (el.type === 'button') contentHtml = `<button class="flowexa-btn-primary" style="pointer-events:none; background:inherit; color:inherit; border-radius:inherit; padding:inherit; width:100%; border:none; display:block; font-family:inherit; font-weight:inherit; font-size:inherit; letter-spacing:inherit;">${safeContent}</button>`;
                        else if (el.type === 'image') contentHtml = `<img src="${safeContent || 'https://via.placeholder.com/400x300?text=No+Image'}" style="max-width:100%; border-radius:inherit; display:block; height:inherit; object-fit:inherit;">`;
                        else if (el.type === 'divider') contentHtml = `<hr style="border:0; border-top:1px solid #e2e8f0; width:100%;">`;
                        else if (el.type === 'product_grid') contentHtml = this.renderProductGrid(safeContent);
                        else if (el.type === 'product_showcase') contentHtml = this.renderProductShowcase(safeContent);
                        else if (el.type === 'filter_sidebar') contentHtml = this.renderFilterSidebar(safeContent);
                        else if (el.type === 'cart_sidebar') contentHtml = this.renderCartSidebar(safeContent);
                        else if (el.type === 'cart_button') contentHtml = `<div style="display:inline-flex; align-items:center; gap:10px; background:white; border:1px solid #e2e8f0; border-radius:inherit; padding:10px 20px; color:#1e293b; font-weight:700;"><i class="fa-solid fa-shopping-cart"></i><span>Cart (0)</span></div>`;
                        else if (el.type === 'category_list') contentHtml = `<div style="display:flex; gap:15px; overflow:auto; padding:10px 0;">Electronics, Fashion, Beauty</div>`;
                        else if (el.type === 'html_block') contentHtml = safeContent;
                        else contentHtml = safeContent;

                        dom.innerHTML = `
                            <div class="el-toolbar">
                                <button class="tool-btn" title="Move Up" onclick="event.stopPropagation(); builder.moveLayer('${el.id}', 'up')"><i class="fa-solid fa-arrow-up"></i></button>
                                <button class="tool-btn" title="Move Down" onclick="event.stopPropagation(); builder.moveLayer('${el.id}', 'down')"><i class="fa-solid fa-arrow-down"></i></button>
                                <button class="tool-btn" title="Duplicate" onclick="event.stopPropagation(); builder.duplicateElement('${el.id}')"><i class="fa-solid fa-copy"></i></button>
                                <button class="tool-btn" title="Delete" onclick="event.stopPropagation(); builder.removeElement('${el.id}')"><i class="fa-solid fa-trash"></i></button>
                            </div>
                            <div class="el-content-wrapper">${contentHtml}</div>
                        `;

                        dom.onclick = (e) => {
                            e.stopPropagation();
                            this.selectElement(el.id);
                        };

                        this.elementsContainer.appendChild(dom);
                    });

                    this.renderFooter();
                    this.renderLayers();
                    this.updateCanvasHeight();
                } catch(e) {
                    console.error('Render error', e);
                }
            }

            renderFooter() {
                try {
                    this.footerContainer.innerHTML = '';
                    this.footerContainer.className = `footer-section ${this.selectedId === 'footer' ? 'selected' : ''}`;

                    Object.assign(this.footerContainer.style, this.footer.styles, {
                        minHeight: this.footer.styles.minHeight || '200px',
                        border: this.selectedId === 'footer' ? '2px solid var(--primary-color)' : (this.footer.styles.border || 'none'),
                        position: 'absolute',
                        left: '0',
                        right: '0'
                    });

                    let contentHtml = '';
                    const storeName = document.querySelector('.store-name')?.innerText || 'Store';

                    if (this.footer.template === 'simple') {
                        contentHtml = `<div style="text-align:center;">${this.footer.content.copyright || ''}</div>`;
                    } else if (this.footer.template === 'standard') {
                        contentHtml = `
                            <div style="display:grid; grid-template-columns: 2fr 1fr; gap:40px;">
                                <div>
                                    <h4 style="margin-bottom:15px; color:inherit;">${this.footer.content.aboutTitle || storeName}</h4>
                                    <p style="opacity:0.8; line-height:1.6;">${this.footer.content.aboutText || ''}</p>
                                </div>
                                <div>
                                    <h4 style="margin-bottom:15px; color:inherit;">Quick Links</h4>
                                    <ul style="list-style:none; padding:0; opacity:0.8;">
                                        ${(this.footer.content.links || []).map(l => `<li style="margin-bottom:8px;">${l.label}</li>`).join('')}
                                    </ul>
                                </div>
                            </div>
                            <div style="margin-top:40px; padding-top:20px; border-top:1px solid rgba(255,255,255,0.1); text-align:center; opacity:0.6; font-size:0.8rem;">
                                ${this.footer.content.copyright || ''}
                            </div>
                        `;
                    } else if (this.footer.template === 'minimal') {
                        contentHtml = `
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-weight:700;">${storeName}</span>
                                <div style="display:flex; gap:20px; opacity:0.8;">
                                    ${(this.footer.content.links || []).map(l => `<span>${l.label}</span>`).join('')}
                                </div>
                            </div>
                        `;
                    }

                    this.footerContainer.innerHTML = contentHtml;
                    this.footerContainer.onclick = (e) => {
                        e.stopPropagation();
                        this.selectElement('footer');
                    };
                } catch(e) {
                    console.error('Footer render error', e);
                }
            }

            renderNavbar(config, styles) {
                const isMinimal = config.style === 'minimal';
                const isRetail = config.style === 'retail';

                return `
                    <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                        <div style="font-size:1.5rem; font-weight:900; letter-spacing:-1px;">${config.logo}</div>
                        <div style="display:flex; gap:30px; ${isMinimal ? 'background:rgba(255,255,255,0.1); padding:8px 24px; border-radius:20px; backdrop-filter:blur(10px);' : ''}">
                            @foreach($allPages as $p)
                                <span style="font-size:0.9rem; font-weight:600; cursor:pointer;">{{ $p->page_name }}</span>
                            @endforeach
                        </div>
                        <div style="display:flex; gap:20px; align-items:center;">
                            ${isRetail ? '<i class="fa-solid fa-magnifying-glass"></i>' : ''}
                            <i class="fa-regular fa-user"></i>
                            <div style="position:relative;">
                                <i class="fa-solid fa-shopping-bag"></i>
                                <span style="position:absolute; top:-8px; right:-8px; background:var(--primary-color); color:white; font-size:0.6rem; width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center;">2</span>
                            </div>
                        </div>
                    </div>
                `;
            }

            renderFilterSidebar(config) {
                return `
                    <div style="width:100%;">
                        <div style="margin-bottom:30px;">
                            <h4 style="font-size:0.8rem; text-transform:uppercase; margin-bottom:15px; color:#94a3b8;">Categories</h4>
                            @forelse($categories as $cat)
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; font-weight:600; font-size:0.9rem;">
                                    <span>{{ $cat->name }}</span>
                                    <span style="opacity:0.3; font-weight:400;">{{ $cat->products_count ?? 0 }}</span>
                                </div>
                            @empty
                                ${(config.categories || []).map(c => `
                                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; font-weight:600; font-size:0.9rem;">
                                        <span>${c}</span>
                                        <span style="opacity:0.3; font-weight:400;">12</span>
                                    </div>
                                `).join('')}
                            @endforelse
                        </div>
                        <div>
                            <h4 style="font-size:0.8rem; text-transform:uppercase; margin-bottom:15px; color:#94a3b8;">Price Range</h4>
                            <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                                <input type="number" readonly value="0" style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:6px; font-size:0.85rem;" placeholder="Min">
                                <span>-</span>
                                <input type="number" readonly value="1000" style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:6px; font-size:0.85rem;" placeholder="Max">
                            </div>
                            <button style="width:100%; padding:8px; background:#f1f5f9; border:none; border-radius:6px; font-weight:700; font-size:0.8rem; pointer-events:none; color:var(--text-dark);">Apply</button>
                        </div>
                    </div>
                `;
            }

            renderCartSidebar(config) {
                return `
                    <div style="width:100%; background:white; border:1px solid #eee; border-radius:16px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.05);">
                        <div style="padding:20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                            <h4 style="margin:0;">${config.title}</h4>
                            <span style="font-size:0.8rem; background:#f1f5f9; padding:2px 8px; border-radius:10px; font-weight:700;">2 Items</span>
                        </div>
                        <div style="padding:20px;">
                            <div style="display:flex; gap:15px; margin-bottom:20px;">
                                <div style="width:60px; height:60px; background:#f8fafc; border-radius:8px;"></div>
                                <div style="flex:1;">
                                    <div style="font-weight:700; font-size:0.85rem;">Premium Sneakers</div>
                                    <div style="font-size:0.75rem; color:#64748b;">Size: 42, Color: White</div>
                                    <div style="margin-top:5px; font-weight:800;">{{ $store->currency ?? 'GH₵' }} 199.00</div>
                                </div>
                            </div>
                        </div>
                        <div style="padding:20px; background:#f8fafc;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-weight:700;">
                                <span>Total</span>
                                <span>{{ $store->currency ?? 'GH₵' }} 199.00</span>
                            </div>
                            <button class="flowexa-btn-primary" style="width:100%; border:none;">Checkout Now</button>
                        </div>
                    </div>
                `;
            }

            renderProductGrid(config) {
                try {
                    let items = '';
                    const limit = config.limit || 4;
                    const products = window.storeProducts || [];
                    const displayProducts = products.slice(0, limit);

                    if (displayProducts.length > 0) {
                        displayProducts.forEach(prod => {
                            const imgHtml = prod.image ? `<img src="${prod.image}" style="width:100%; height:100%; object-fit:cover;">` : `<i class="fa-solid fa-image fa-2x"></i>`;
                            items += `
                                <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                                    <div style="aspect-ratio:1/1; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#cbd5e1; overflow:hidden;">${imgHtml}</div>
                                    <div style="padding:12px;">
                                        <div style="font-weight:700; font-size:0.95rem; margin-bottom:4px; color:#0f172a;">${prod.name || 'Product'}</div>
                                        <div style="color:var(--primary-color); font-weight:800; font-size:1rem;">{{ $store->currency ?? 'GH₵' }} ${prod.price || '0.00'}</div>
                                    </div>
                                </div>
                            `;
                        });

                        // Fill remaining spots if less products than limit
                        for(let i=displayProducts.length; i<limit; i++) {
                            items += `
                                <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                                    <div style="aspect-ratio:1/1; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#cbd5e1;"><i class="fa-solid fa-image fa-2x"></i></div>
                                    <div style="padding:12px;">
                                        <div style="height:12px; width:70%; background:#f1f5f9; border-radius:4px; margin-bottom:8px;"></div>
                                        <div style="height:10px; width:40%; background:#f1f5f9; border-radius:4px;"></div>
                                    </div>
                                </div>
                            `;
                        }
                    } else {
                        for(let i=0; i<limit; i++) {
                            items += `
                                <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                                    <div style="aspect-ratio:1/1; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#cbd5e1;"><i class="fa-solid fa-image fa-2x"></i></div>
                                    <div style="padding:12px;">
                                        <div style="height:12px; width:70%; background:#f1f5f9; border-radius:4px; margin-bottom:8px;"></div>
                                        <div style="height:10px; width:40%; background:#f1f5f9; border-radius:4px;"></div>
                                    </div>
                                </div>
                            `;
                        }
                    }

                    return `
                        <div style="width:100%;">
                            ${config.title ? `<h3 style="margin-bottom:20px; font-weight:800; color:var(--text-dark);">${config.title}</h3>` : ''}
                            <div style="display:grid; grid-template-columns: repeat(${config.columns || 4}, 1fr); gap:20px;">
                                ${items}
                            </div>
                        </div>
                    `;
                } catch(e) {
                    console.error('Product grid render error', e);
                    return '<div style="padding:20px; color:red;">Grid Error</div>';
                }
            }

            renderProductShowcase(config) {
                const isRight = config.layout === 'right';

                let prod = null;
                if (config.product_id) {
                    prod = window.storeProducts.find(p => p.id === config.product_id);
                }
                if (!prod && window.storeProducts.length > 0) {
                    prod = window.storeProducts[0];
                }

                let imgPart = '';
                let contentPart = '';

                if (prod) {
                    const imgHtml = prod.image ? `<img src="${prod.image}" style="width:100%; height:100%; object-fit:cover;">` : `<i class="fa-solid fa-image fa-4x"></i>`;
                    imgPart = `<div style="aspect-ratio:1/1; background:#f1f5f9; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#cbd5e1; overflow:hidden;">${imgHtml}</div>`;
                    contentPart = `
                        <div>
                            <div style="height:20px; width:40%; background:#f1f5f9; border-radius:4px; margin-bottom:15px; color:var(--primary-color); font-weight:800; font-size:0.8rem;">FEATURED</div>
                            <h2 style="margin-bottom:20px; font-size:2rem; color:#0f172a;">${prod.name}</h2>
                            <div style="height:60px; width:100%; background:#f1f5f9; border-radius:4px; margin-bottom:30px;"></div>
                            <div style="font-size:1.5rem; font-weight:800; margin-bottom:20px; color:var(--text-dark);">{{ $store->currency ?? 'GH₵' }} ${prod.price}</div>
                            <button class="flowexa-btn-primary" style="pointer-events:none;">Buy Now</button>
                        </div>
                    `;
                } else {
                    imgPart = `<div style="aspect-ratio:1/1; background:#f1f5f9; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#cbd5e1;"><i class="fa-solid fa-image fa-4x"></i></div>`;
                    contentPart = `
                        <div>
                            <div style="height:20px; width:40%; background:#f1f5f9; border-radius:4px; margin-bottom:15px;"></div>
                            <div style="height:32px; width:80%; background:#f1f5f9; border-radius:4px; margin-bottom:20px;"></div>
                            <div style="height:60px; width:100%; background:#f1f5f9; border-radius:4px; margin-bottom:30px;"></div>
                            <div style="height:48px; width:50%; background:var(--primary-color); border-radius:12px; opacity:0.2;"></div>
                        </div>
                    `;
                }

                return `
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px; align-items:center; width:100%;">
                        ${isRight ? contentPart + imgPart : imgPart + contentPart}
                    </div>
                `;
            }

            moveLayer(id, direction) {
                const index = this.elements.findIndex(el => el.id === id);
                if (index === -1) return;

                if (direction === 'up' && index < this.elements.length - 1) {
                    [this.elements[index], this.elements[index + 1]] = [this.elements[index + 1], this.elements[index]];
                } else if (direction === 'down' && index > 0) {
                    [this.elements[index], this.elements[index - 1]] = [this.elements[index - 1], this.elements[index]];
                } else if (direction === 'top') {
                    const el = this.elements.splice(index, 1)[0];
                    this.elements.push(el);
                } else if (direction === 'bottom') {
                    const el = this.elements.splice(index, 1)[0];
                    this.elements.unshift(el);
                }

                this.render();
                if (id === this.selectedId) this.selectElement(id);
            }

            renderLayers() {
                const list = document.getElementById('layers-list');
                list.innerHTML = '';

                if (this.elements.length === 0) {
                    list.innerHTML = '<div style="padding:2rem; text-align:center; color: #ffffff; font-size:0.8rem;">No elements added yet</div>';
                    return;
                }

                // Render in reverse order because last in array is top-most in CSS
                [...this.elements].reverse().forEach((el, revIdx) => {
                    const actualIdx = this.elements.length - 1 - revIdx;
                    const item = document.createElement('div');
                    item.className = `layer-item ${this.selectedId === el.id ? 'active' : ''}`;
                    item.draggable = true;
                    item.dataset.id = el.id;
                    item.dataset.index = actualIdx;

                    item.style = "display:flex; align-items:center; gap:0.75rem; padding:0.6rem 0.8rem; border-radius:12px; cursor:grab; margin-bottom:6px; font-size:0.8rem; font-weight:600; transition: all 0.2s; border: 1px solid transparent; position: relative; z-index: 1;";

                    if (this.selectedId === el.id) {
                        item.style.backgroundColor = "#fff7ed";
                        item.style.borderColor = "#ffedd5";
                        item.style.color = "var(--primary-color)";
                    } else {
                        item.style.backgroundColor = "#f8fafc";
                    }

                    const labels = {
                        product_grid: 'Product Grid',
                        product_showcase: 'Featured Product',
                        cart_button: 'Cart Icon',
                        category_list: 'Categories',
                        search_bar: 'Store Search',
                        heading: 'Heading',
                        text: 'Text Block',
                        image: 'Image',
                        button: 'Button',
                        container: 'Section',
                        card: 'Info Card',
                        divider: 'Divider'
                    };

                    item.innerHTML = `
                        <div style="display:flex; flex-direction:column; gap:2px; color:#cbd5e1; font-size:0.5rem; pointer-events:none;">
                            <i class="fa-solid fa-grip-vertical"></i>
                        </div>
                        <span style="flex:1; pointer-events:none;">${labels[el.type] || el.type}</span>
                        <div style="display:flex; gap:8px;">
                            <i class="fa-solid fa-layer-group" style="opacity:0.3; font-size:0.7rem;" onclick="event.stopPropagation(); builder.moveLayer('${el.id}', 'top')" title="Bring to Front"></i>
                            <i class="fa-solid fa-trash" style="opacity:0.3; font-size:0.7rem;" onclick="event.stopPropagation(); builder.removeElement('${el.id}')"></i>
                        </div>
                    `;

                    item.onclick = () => this.selectElement(el.id);

                    // Drag and Drop for Layers
                    item.ondragstart = (e) => {
                        e.dataTransfer.setData('layerId', el.id);
                        item.style.opacity = '0.5';
                    };

                    item.ondragend = () => {
                        item.style.opacity = '1';
                        document.querySelectorAll('.layer-item').forEach(li => li.style.borderTop = '1px solid transparent');
                    };

                    item.ondragover = (e) => {
                        e.preventDefault();
                        item.style.borderTop = '2px solid var(--primary-color)';
                    };

                    item.ondragleave = () => {
                        item.style.borderTop = '1px solid transparent';
                    };

                    item.ondrop = (e) => {
                        e.preventDefault();
                        const draggedId = e.dataTransfer.getData('layerId');
                        if (draggedId !== el.id) {
                            this.reorderElements(draggedId, el.id);
                        }
                    };

                    list.appendChild(item);
                });

                // Add Footer to layers
                const footerItem = document.createElement('div');
                footerItem.className = `layer-item ${this.selectedId === 'footer' ? 'active' : ''}`;
                footerItem.style = "display:flex; align-items:center; gap:0.75rem; padding:0.6rem 0.8rem; border-radius:12px; cursor:pointer; margin-top:12px; border-top: 1px solid var(--border-color); font-size:0.8rem; font-weight:700; background: #f1f5f9;";
                if (this.selectedId === 'footer') {
                    footerItem.style.backgroundColor = "#fff7ed";
                    footerItem.style.color = "var(--primary-color)";
                }
                footerItem.innerHTML = `
                    <i class="fa-solid fa-shoe-prints" style="color:#cbd5e1; transform: rotate(-90deg);"></i>
                    <span style="flex:1">Page Footer</span>
                    <i class="fa-solid fa-lock" style="font-size:0.6rem; color:#cbd5e1;"></i>
                `;
                footerItem.onclick = () => this.selectElement('footer');
                list.appendChild(footerItem);
            }

            renderProperties() {
                try {
                    if (this.selectedId === 'footer') {
                        this.renderFooterProperties();
                        return;
                    }

                    const el = this.elements.find(e => e.id === this.selectedId);
                    if (!el) {
                        this.propsPanel.innerHTML = '<div class="no-selection"><p>Select an element to edit</p></div>';
                        return;
                    }

                    let specificProps = '';
                    if (el.type === 'navbar') {
                        specificProps = `
                            <div class="prop-group">
                                <label class="prop-label">Navbar Settings</label>
                                <div style="margin-bottom:0.8rem;">
                                    <label style="font-size:0.7rem; color:#94a3b8;">Logo Text</label>
                                    <input type="text" class="prop-input config-input" data-key="logo" value="${el.content.logo || ''}">
                                </div>
                                <div style="margin-bottom:0.8rem;">
                                    <label style="font-size:0.7rem; color:#94a3b8;">Style</label>
                                    <select class="prop-input config-input" data-key="style">
                                        <option value="modern" ${el.content.style === 'modern' ? 'selected' : ''}>Modern</option>
                                        <option value="minimal" ${el.content.style === 'minimal' ? 'selected' : ''}>Minimal (Glassmorphism)</option>
                                        <option value="retail" ${el.content.style === 'retail' ? 'selected' : ''}>Retail</option>
                                    </select>
                                </div>
                            </div>
                        `;
                    } else if (el.type === 'product_grid') {
                        specificProps = `
                            <div class="prop-group">
                                <label class="prop-label">Grid Configuration</label>
                                <div style="margin-bottom:0.8rem;">
                                    <label style="font-size:0.7rem; color:#94a3b8;">Title</label>
                                    <input type="text" class="prop-input config-input" data-key="title" value="${el.content.title || ''}">
                                </div>
                                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem;">
                                    <div>
                                        <label style="font-size:0.7rem; color:#94a3b8;">Products</label>
                                        <input type="number" class="prop-input config-input" data-key="limit" value="${el.content.limit || 4}">
                                    </div>
                                    <div>
                                        <label style="font-size:0.7rem; color:#94a3b8;">Columns</label>
                                        <select class="prop-input config-input" data-key="columns">
                                            <option value="2" ${el.content.columns == 2 ? 'selected' : ''}>2 Columns</option>
                                            <option value="3" ${el.content.columns == 3 ? 'selected' : ''}>3 Columns</option>
                                            <option value="4" ${el.content.columns == 4 ? 'selected' : ''}>4 Columns</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else if (el.type === 'product_showcase') {
                        specificProps = `
                            <div class="prop-group">
                                <label class="prop-label">Showcase Layout</label>
                                <select class="prop-input config-input" data-key="layout">
                                    <option value="left" ${el.content.layout === 'left' ? 'selected' : ''}>Image Left</option>
                                    <option value="right" ${el.content.layout === 'right' ? 'selected' : ''}>Image Right</option>
                                </select>
                            </div>
                        `;
                    } else if (el.type === 'image') {
                        specificProps = `
                            <div class="prop-group">
                                <label class="prop-label">Image URL</label>
                                <input type="text" class="prop-input content-input" value="${el.content}">
                            </div>
                        `;
                    } else if (typeof el.content === 'string') {
                        specificProps = `
                            <div class="prop-group">
                                <label class="prop-label">Content</label>
                                <textarea class="prop-input content-input" rows="3">${el.content}</textarea>
                            </div>
                        `;
                    }

                    const formatVal = (v) => {
                        if (typeof v === 'number') return Math.round(v);
                        if (!v) return '';
                        return v;
                    };

                    this.propsPanel.innerHTML = `
                        ${specificProps}
                        <div class="prop-group">
                            <label class="prop-label">Typography</label>
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem; margin-bottom:0.5rem;">
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Font Size</label>
                                    <input type="text" data-prop="fontSize" class="prop-input style-input" value="${el.styles.fontSize || '16px'}">
                                </div>
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Text Color</label>
                                    <input type="color" data-prop="color" class="prop-input style-input" value="${el.styles.color || '#000000'}" style="height:38px; padding:2px;">
                                </div>
                            </div>
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem; margin-bottom:0.5rem;">
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Line Height</label>
                                    <input type="text" data-prop="lineHeight" class="prop-input style-input" value="${el.styles.lineHeight || '1.2'}">
                                </div>
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Letter Spacing</label>
                                    <input type="text" data-prop="letterSpacing" class="prop-input style-input" value="${el.styles.letterSpacing || '0px'}">
                                </div>
                            </div>
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem;">
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Weight</label>
                                    <select data-prop="fontWeight" class="prop-input style-input">
                                        <option value="normal" ${el.styles.fontWeight === 'normal' ? 'selected' : ''}>Normal</option>
                                        <option value="500" ${el.styles.fontWeight == 500 ? 'selected' : ''}>Medium</option>
                                        <option value="600" ${el.styles.fontWeight == 600 ? 'selected' : ''}>Semi-Bold</option>
                                        <option value="700" ${el.styles.fontWeight == 700 ? 'selected' : ''}>Bold</option>
                                        <option value="800" ${el.styles.fontWeight == 800 ? 'selected' : ''}>Extra-Bold</option>
                                        <option value="900" ${el.styles.fontWeight == 900 ? 'selected' : ''}>Black</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Align</label>
                                    <select data-prop="textAlign" class="prop-input style-input">
                                        <option value="left" ${el.styles.textAlign === 'left' ? 'selected' : ''}>Left</option>
                                        <option value="center" ${el.styles.textAlign === 'center' ? 'selected' : ''}>Center</option>
                                        <option value="right" ${el.styles.textAlign === 'right' ? 'selected' : ''}>Right</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="prop-group">
                            <label class="prop-label">Layout</label>
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem; margin-bottom: 0.5rem;">
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">X Position</label>
                                    <input type="text" data-prop="left" class="prop-input style-input" value="${formatVal(el.styles.left)}">
                                </div>
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Y Position</label>
                                    <input type="text" data-prop="top" class="prop-input style-input" value="${formatVal(el.styles.top)}">
                                </div>
                            </div>
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem;">
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Width</label>
                                    <input type="text" data-prop="width" class="prop-input style-input" value="${el.styles.width || 'auto'}">
                                </div>
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Height</label>
                                    <input type="text" data-prop="height" class="prop-input style-input" value="${el.styles.height || 'auto'}">
                                </div>
                            </div>
                        </div>
                        <div class="prop-group">
                            <label class="prop-label">Design & Border</label>
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem; margin-bottom:0.5rem;">
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Background</label>
                                    <input type="color" data-prop="backgroundColor" class="prop-input style-input" value="${el.styles.backgroundColor || '#ffffff'}" style="height:38px; padding:2px;">
                                </div>
                                <div>
                                    <label style="font-size:0.7rem; color:#94a3b8;">Opacity</label>
                                    <input type="number" step="0.1" min="0" max="1" data-prop="opacity" class="prop-input style-input" value="${el.styles.opacity || 1}">
                                </div>
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <label style="font-size:0.7rem; color:#94a3b8;">Border Radius</label>
                                <input type="text" data-prop="borderRadius" class="prop-input style-input" value="${el.styles.borderRadius || '0px'}">
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <label style="font-size:0.7rem; color:#94a3b8;">Border</label>
                                <input type="text" data-prop="border" class="prop-input style-input" value="${el.styles.border || 'none'}">
                            </div>
                            <div>
                                <label style="font-size:0.7rem; color:#94a3b8;">Box Shadow</label>
                                <input type="text" data-prop="boxShadow" class="prop-input style-input" value="${el.styles.boxShadow || 'none'}">
                            </div>
                        </div>
                    `;

                    // Bind inputs (same logic as before but wrapped in try/catch)
                    this.propsPanel.querySelectorAll('.prop-input').forEach(input => {
                        input.oninput = (e) => {
                            try {
                                if (input.classList.contains('content-input')) {
                                    el.content = e.target.value;
                                } else if (input.classList.contains('config-input')) {
                                    el.content[input.dataset.key] = e.target.value;
                                } else if (input.classList.contains('style-input')) {
                                    const prop = input.dataset.prop;
                                    let val = e.target.value;
                                    if (prop === 'left' || prop === 'top') {
                                        if (!isNaN(val) && val.trim() !== '') val = parseFloat(val);
                                    }
                                    el.styles[prop] = val;
                                }
                                this.render();
                            } catch(err) { console.error('Prop input error', err); }
                        };
                        if (input.tagName === 'SELECT') input.onchange = input.oninput;
                    });
                } catch(e) {
                    console.error('Properties render error', e);
                }
            }

            reorderElements(draggedId, targetId) {
                const draggedIdx = this.elements.findIndex(el => el.id === draggedId);
                const targetIdx = this.elements.findIndex(el => el.id === targetId);

                if (draggedIdx === -1 || targetIdx === -1) return;

                const [draggedItem] = this.elements.splice(draggedIdx, 1);
                // Re-find target index as it might have shifted
                const newTargetIdx = this.elements.findIndex(el => el.id === targetId);
                this.elements.splice(newTargetIdx, 0, draggedItem);

                this.render();
            }

            renderFooterProperties() {
                this.propsPanel.innerHTML = `
                    <div class="prop-group">
                        <label class="prop-label">Footer Template</label>
                        <select class="prop-input" id="footer-template-select">
                            <option value="standard" ${this.footer.template === 'standard' ? 'selected' : ''}>Standard (Grid)</option>
                            <option value="simple" ${this.footer.template === 'simple' ? 'selected' : ''}>Simple (Centered)</option>
                            <option value="minimal" ${this.footer.template === 'minimal' ? 'selected' : ''}>Minimal (Inline)</option>
                        </select>
                    </div>

                    <div class="prop-group">
                        <label class="prop-label">Design & Colors</label>
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem; margin-bottom:0.8rem;">
                            <div>
                                <label style="font-size:0.7rem; color:#94a3b8;">Background</label>
                                <input type="color" data-prop="backgroundColor" class="prop-input footer-style-input" value="${this.footer.styles.backgroundColor}" style="height:38px; padding:2px;">
                            </div>
                            <div>
                                <label style="font-size:0.7rem; color:#94a3b8;">Text Color</label>
                                <input type="color" data-prop="color" class="prop-input footer-style-input" value="${this.footer.styles.color}" style="height:38px; padding:2px;">
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem; margin-bottom:0.8rem;">
                            <div>
                                <label style="font-size:0.7rem; color:#94a3b8;">Padding</label>
                                <input type="text" data-prop="padding" class="prop-input footer-style-input" value="${this.footer.styles.padding}">
                            </div>
                            <div>
                                <label style="font-size:0.7rem; color:#94a3b8;">Min Height</label>
                                <input type="text" data-prop="minHeight" class="prop-input footer-style-input" value="${this.footer.styles.minHeight || '200px'}">
                            </div>
                        </div>
                        <div style="margin-bottom:0.8rem;">
                            <label style="font-size:0.7rem; color:#94a3b8;">Border Radius</label>
                            <input type="text" data-prop="borderRadius" class="prop-input footer-style-input" value="${this.footer.styles.borderRadius || '0px'}">
                        </div>
                        <div style="margin-bottom:0.8rem;">
                            <label style="font-size:0.7rem; color:#94a3b8;">Border Top</label>
                            <input type="text" data-prop="borderTop" class="prop-input footer-style-input" value="${this.footer.styles.borderTop || 'none'}">
                        </div>
                    </div>

                    <div class="prop-group">
                        <label class="prop-label">Information</label>
                        <div style="margin-bottom:0.8rem;">
                            <label style="font-size:0.7rem; color:#94a3b8;">Copyright Text</label>
                            <input type="text" data-key="copyright" class="prop-input footer-content-input" value="${this.footer.content.copyright}">
                        </div>
                        ${this.footer.template === 'standard' ? `
                            <div style="margin-bottom:0.8rem;">
                                <label style="font-size:0.7rem; color:#94a3b8;">About Title</label>
                                <input type="text" data-key="aboutTitle" class="prop-input footer-content-input" value="${this.footer.content.aboutTitle}">
                            </div>
                            <div>
                                <label style="font-size:0.7rem; color:#94a3b8;">About Description</label>
                                <textarea data-key="aboutText" class="prop-input footer-content-input" rows="3">${this.footer.content.aboutText}</textarea>
                            </div>
                        ` : ''}
                    </div>
                `;

                document.getElementById('footer-template-select').onchange = (e) => {
                    this.footer.template = e.target.value;
                    this.render();
                    this.renderProperties();
                };

                this.propsPanel.querySelectorAll('.footer-style-input').forEach(input => {
                    input.oninput = (e) => {
                        this.footer.styles[input.dataset.prop] = e.target.value;
                        this.render();
                    };
                });

                this.propsPanel.querySelectorAll('.footer-content-input').forEach(input => {
                    input.oninput = (e) => {
                        this.footer.content[input.dataset.key] = e.target.value;
                        this.render();
                    };
                });
            }

            async save() {
                const btn = document.getElementById('save-content');
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
                btn.disabled = true;

                // Pre-process elements to keep placeholders if they match store name
                const storeName = '{{ $store->store_name }}';
                const processedElements = this.elements.map(el => {
                    let content = el.content;
                    if (typeof content === 'string') {
                        content = content.replace(new RegExp(storeName, 'g'), '[[store_name]]');
                    } else if (typeof content === 'object' && content !== null) {
                        content = JSON.parse(JSON.stringify(content).replace(new RegExp(storeName, 'g'), '[[store_name]]'));
                    }
                    return { ...el, content };
                });

                try {
                    const response = await fetch('{{ route('ecommerce.pages.save-content', [$store->id, $page->id]) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            content: {
                                elements: processedElements,
                                footer: this.footer
                            }
                        })
                    });

                    if (response.ok) {
                        btn.innerHTML = '<i class="fa-solid fa-check"></i> Saved!';
                        setTimeout(() => {
                            btn.innerHTML = originalHtml;
                            btn.disabled = false;
                        }, 2000);
                    } else {
                        throw new Error('Save failed');
                    }
                } catch (err) {
                    alert('Error saving content: ' + err.message);
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
            }
        }

        @php
            $mappedProducts = $products->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => number_format($p->unit_price, 2),
                    'image' => $p->imageUrl()
                ];
            })->values()->all();
        @endphp
        window.storeProducts = @json($mappedProducts);

        window.builder = new PageBuilder('{{ $page->id }}', @json($page->content ?? []));

        // ── Template Catalogue Loader ──────────────────────────────

        let pendingTemplateId = null;


        async function loadTemplateCatalogue() {
            const container = document.getElementById('template-catalogue');
            try {
                const res = await fetch('{{ route("ecommerce.templates.index", $store->id) }}');
                const templates = await res.json();
                if (!templates.length) { container.innerHTML = '<p style="font-size:0.8rem;color:#94a3b8;text-align:center;">No templates found.</p>'; return; }
                container.innerHTML = templates.map(t => {
                    const previewImg = t.preview || 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=240&fit=crop';
                    const desc = t.desc || (t.pages.join(', ') + ' pages');
                    const pageBadges = t.pages.map(p => `<span style="font-size:0.6rem;background:#f1f5f9;padding:2px 6px;border-radius:4px;font-weight:600;">${p}</span>`).join('');
                    return `
                        <div onclick="confirmApplyTemplate('${t.id}','${t.name}')" style="cursor:pointer;border:1px solid var(--border-color);border-radius:12px;overflow:hidden;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--primary-color)';this.style.transform='translateY(-1px)'" onmouseout="this.style.borderColor='var(--border-color)';this.style.transform='none'">
                            <img src="${previewImg}" style="width:100%;height:100px;object-fit:cover;display:block;">
                            <div style="padding:0.6rem;">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                                    <span style="font-size:0.82rem;font-weight:800;color:var(--text-dark);">${t.name}</span>
                                    <i class="fa-solid fa-wand-magic-sparkles" style="color:var(--primary-color);font-size:0.75rem;"></i>
                                </div>
                                <p style="font-size:0.68rem;color:var(--text-muted);margin:0 0 6px;">${desc}</p>
                                <div style="display:flex;flex-wrap:wrap;gap:3px;">${pageBadges}</div>
                            </div>
                        </div>`;
                }).join('');
            } catch(e) {
                container.innerHTML = '<p style="font-size:0.8rem;color:#ef4444;text-align:center;">Could not load templates.</p>';
            }
        }

        function confirmApplyTemplate(id, name) {
            pendingTemplateId = id;
            document.getElementById('modal-template-name').textContent = name;
            document.getElementById('template-apply-modal').style.display = 'flex';
        }

        document.getElementById('modal-confirm-btn').addEventListener('click', async function() {
            if (!pendingTemplateId) return;
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Applying...';
            try {
                const res = await fetch('{{ route("ecommerce.templates.apply", $store->id) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ template_id: pendingTemplateId })
                });
                const data = await res.json();
                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.error || 'Failed to apply template.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Apply Template';
                }
            } catch(e) {
                alert('Error applying template. Please try again.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Apply Template';
            }
        });

        // Load templates when the Templates tab is clicked
        document.querySelectorAll('.sb-tab-btn[data-tab="templates"]').forEach(btn => {
            btn.addEventListener('click', () => { if (!document.getElementById('template-catalogue').dataset.loaded) { loadTemplateCatalogue(); document.getElementById('template-catalogue').dataset.loaded = '1'; } });
        });
    </script>
</x-layouts.app>
