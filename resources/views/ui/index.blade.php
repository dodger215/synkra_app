<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Synkra UI Library - Design System</title>
  
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts: Instrument Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Inject Global app.css Styles directly to ensure perfect rendering regardless of build assets state -->
  <style>
    {!! file_get_contents(resource_path('css/app.css')) !!}
  </style>

  <style>
    body {
      font-family: 'Instrument Sans', sans-serif;
      background-color: var(--background);
      color: var(--text-primary);
      margin: 0;
      padding: 0;
      transition: background-color 0.3s, color 0.3s;
      min-height: 100vh;
    }

    /* Page Header */
    .synkra-doc-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.25rem 2rem;
      background-color: var(--surface);
      border-bottom: 1px solid var(--border);
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .synkra-doc-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 1.35rem;
      font-weight: 700;
      color: var(--primary);
    }

    .synkra-doc-logo i {
      font-size: 1.5rem;
    }

    .synkra-doc-theme-btn {
      background: var(--surface-secondary);
      border: 1px solid var(--border);
      color: var(--text-primary);
      padding: 8px 16px;
      border-radius: 99px;
      cursor: pointer;
      font-weight: 600;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease;
    }

    .synkra-doc-theme-btn:hover {
      background: var(--border);
      transform: translateY(-1px);
    }

    /* Sidebar and Layout */
    .synkra-doc-layout {
      display: grid;
      grid-template-columns: 260px 1fr;
      min-height: calc(100vh - 70px);
    }

    .synkra-doc-sidebar {
      background-color: var(--surface);
      border-right: 1px solid var(--border);
      padding: 2rem 1.5rem;
      position: sticky;
      top: 70px;
      height: calc(100vh - 70px);
      overflow-y: auto;
    }

    .synkra-sidebar-title {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-secondary);
      margin-bottom: 1rem;
      font-weight: 700;
    }

    .synkra-sidebar-menu {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin-bottom: 2rem;
    }

    .synkra-sidebar-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 14px;
      border-radius: 8px;
      color: var(--text-secondary);
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .synkra-sidebar-link:hover {
      color: var(--primary);
      background-color: var(--surface-secondary);
    }

    .synkra-sidebar-link.active {
      color: var(--primary);
      background-color: var(--active-menu);
      border-left: 3px solid var(--active-menu-border);
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
    }

    .synkra-doc-content {
      padding: 3rem 4rem;
      overflow-y: auto;
    }

    /* Showcase Sections */
    .synkra-showcase-section {
      margin-bottom: 4rem;
      scroll-margin-top: 100px;
    }

    .synkra-showcase-title {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--headings);
      margin-bottom: 0.5rem;
      border-bottom: 2px solid var(--primary);
      padding-bottom: 0.5rem;
      width: fit-content;
    }

    .synkra-showcase-desc {
      color: var(--text-secondary);
      font-size: 0.95rem;
      margin-bottom: 2rem;
    }

    .synkra-showcase-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 2rem;
    }

    /* Component Card Showcase */
    .synkra-showcase-card {
      background-color: var(--surface);
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.02);
      display: flex;
      flex-direction: column;
    }

    .synkra-showcase-card-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .synkra-component-name {
      font-weight: 700;
      color: var(--headings);
      font-size: 1rem;
    }

    .synkra-component-badge {
      font-size: 0.7rem;
      background-color: var(--surface-secondary);
      border: 1px solid var(--border);
      padding: 3px 8px;
      border-radius: 99px;
      color: var(--text-secondary);
      font-weight: 600;
    }

    .synkra-showcase-card-body {
      padding: 2rem 1.5rem;
      background-color: var(--surface-secondary);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 200px;
      flex-grow: 1;
      position: relative;
    }

    .synkra-showcase-card-footer {
      border-top: 1px solid var(--border);
      background-color: var(--surface);
    }

    .synkra-code-toggle {
      width: 100%;
      background: transparent;
      border: none;
      padding: 12px;
      font-weight: 600;
      font-size: 0.8rem;
      color: var(--text-secondary);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      transition: color 0.2s;
    }

    .synkra-code-toggle:hover {
      color: var(--primary);
    }

    .synkra-code-block {
      display: none;
      padding: 1.25rem;
      background-color: #0f172a;
      color: #e2e8f0;
      font-family: monospace;
      font-size: 0.8rem;
      overflow-x: auto;
      border-top: 1px solid var(--border);
      position: relative;
    }

    .synkra-code-block.open {
      display: block;
    }

    .synkra-copy-code-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: rgba(255, 255, 255, 0.1);
      border: none;
      color: #fff;
      padding: 4px 8px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 0.7rem;
      font-weight: 600;
      transition: background-color 0.2s;
    }

    .synkra-copy-code-btn:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    /* Full width showcase option */
    .synkra-showcase-card-full {
      grid-column: 1 / -1;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header class="synkra-doc-header">
    <div class="synkra-doc-logo">
      <i class="fa-solid fa-cube"></i>
      <span>Synkra System Component Library</span>
    </div>
    <button class="synkra-doc-theme-btn" id="themeToggler" onclick="toggleTheme()" aria-label="Toggle Theme">
      <i class="fa-solid fa-moon"></i>
      <span id="themeText">Dark Mode</span>
    </button>
  </header>

  <!-- Main Layout -->
  <div class="synkra-doc-layout">
    
    <!-- Sidebar Menu -->
    <aside class="synkra-doc-sidebar">
      <div class="synkra-sidebar-title">Core Abstractions</div>
      <nav class="synkra-sidebar-menu">
        <a href="#buttons" class="synkra-sidebar-link active"><i class="fa-solid fa-circle-play"></i> Buttons & Badges</a>
        <a href="#cards" class="synkra-sidebar-link"><i class="fa-regular fa-square"></i> Cards System</a>
        <a href="#inputs" class="synkra-sidebar-link"><i class="fa-regular fa-keyboard"></i> Inputs & Selects</a>
        <a href="#toggles" class="synkra-sidebar-link"><i class="fa-solid fa-toggle-on"></i> Toggles & Switches</a>
        <a href="#tooltips" class="synkra-sidebar-link"><i class="fa-regular fa-message"></i> Tooltips & Modals</a>
        <a href="#tables" class="synkra-sidebar-link"><i class="fa-solid fa-table-list"></i> Navigation & Tables</a>
        <a href="#loaders" class="synkra-sidebar-link"><i class="fa-solid fa-spinner"></i> Loaders & States</a>
        <a href="#patterns" class="synkra-sidebar-link"><i class="fa-solid fa-border-all"></i> Grid Patterns</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="synkra-doc-content">

      <!-- Buttons & Badges Section -->
      <section id="buttons" class="synkra-showcase-section">
        <h2 class="synkra-showcase-title">Buttons & Badges</h2>
        <p class="synkra-showcase-desc">Modern button variants and status badges built to reflect brand styling variables.</p>
        
        <div class="synkra-showcase-grid">
          
          <!-- Regular Button -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Regular Button</span>
              <span class="synkra-component-badge">Blade Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.button text="Create Account" icon="fa-solid fa-circle-user" variant="primary" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.button text="Create Account" icon="fa-solid fa-circle-user" variant="primary" /&gt;

&lcub;&lcub;-- Or using standard include --&rcub;&rcub;
&commat;include('ui.components.buttons.regular', ['text' => 'Create Account', 'icon' => 'fa-solid fa-circle-user', 'variant' => 'primary'])</code></pre>
            </div>
          </div>

          <!-- Badges -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Badges / Indicators</span>
              <span class="synkra-component-badge">Blade Component</span>
            </div>
            <div class="synkra-showcase-card-body" style="gap: 10px; flex-wrap: wrap;">
              <x-ui.badge text="Primary" variant="primary" />
              <x-ui.badge text="Success" variant="success" pill="true" icon="fa-solid fa-check" />
              <x-ui.badge text="Alert" variant="danger" icon="fa-solid fa-circle-exclamation" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.badge text="Success" variant="success" pill="true" icon="fa-solid fa-check" /&gt;</code></pre>
            </div>
          </div>

        </div>
      </section>

      <!-- Cards Section -->
      <section id="cards" class="synkra-showcase-section">
        <h2 class="synkra-showcase-title">Cards System</h2>
        <p class="synkra-showcase-desc">Information cards, premium feature cards, and draggable project task cards.</p>
        
        <div class="synkra-showcase-grid">
          
          <!-- Regular Card -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Regular Card</span>
              <span class="synkra-component-badge">Container</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.card title="Project Statistics" subtitle="Live updates for workspace operations">
                <p style="margin: 0; font-size: 0.95rem;">Workspace metrics are syncing successfully with our main database clusters.</p>
              </x-ui.card>
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.card title="Project Statistics" subtitle="Live updates for workspace operations"&gt;
  &lt;p&gt;Workspace metrics are syncing successfully...&lt;/p&gt;
&lt;/x-ui.card&gt;</code></pre>
            </div>
          </div>

          <!-- Premium Card -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Premium Ribbon Card</span>
              <span class="synkra-component-badge">Showcase Card</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.premium-card title="AI Copilot" badgeText="Plus" description="Unleash automated workflows with modern AI assistant models." icon="fa-solid fa-wand-magic-sparkles" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.premium-card title="AI Copilot" badgeText="Plus" description="Unleash automated workflows..." icon="fa-solid fa-wand-magic-sparkles" /&gt;</code></pre>
            </div>
          </div>

          <!-- Task Card -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Task Card (Draggable)</span>
              <span class="synkra-component-badge">Kanban Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              @php
                $mockAssignees = [
                  ['name' => 'John Doe', 'avatar' => null],
                  ['name' => 'Alice Smith', 'avatar' => null],
                  ['name' => 'Bob Johnson', 'avatar' => null],
                  ['name' => 'Charlie Rose', 'avatar' => null],
                ];
              @endphp
              <x-ui.task-card tag="In Progress" tagColor="var(--secondary)" title="Refactor Auth Models" description="Clean up Eloquent schemas, add indexes to user columns, and document migrations." commentsCount="12" attachmentsCount="4" :assignees="$mockAssignees" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.task-card tag="In Progress" tagColor="var(--secondary)" title="Refactor Auth Models" description="Clean up Eloquent schemas..." commentsCount="12" attachmentsCount="4" :assignees="$assignees" /&gt;</code></pre>
            </div>
          </div>

        </div>
      </section>

      <!-- Inputs Section -->
      <section id="inputs" class="synkra-showcase-section">
        <h2 class="synkra-showcase-title">Inputs & Selects</h2>
        <p class="synkra-showcase-desc">Beautiful, highly functional text inputs, search fields, quantity adjusters, link-sharing tools, and select dropdowns.</p>
        
        <div class="synkra-showcase-grid">
          
          <!-- Text Input -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Text Input with Icon</span>
              <span class="synkra-component-badge">Form Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.input label="Workspace URL" placeholder="my-awesome-workspace" icon="fa-solid fa-globe" description="This will be used for your public profile routing." required="true" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.input label="Workspace URL" placeholder="my-awesome-workspace" icon="fa-solid fa-globe" description="..." required="true" /&gt;</code></pre>
            </div>
          </div>

          <!-- Select Menu -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Custom Select Dropdown</span>
              <span class="synkra-component-badge">Form Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              @php
                $mockOptions = [
                  ['value' => 'dev', 'label' => 'Developer Mode'],
                  ['value' => 'prod', 'label' => 'Production Mode'],
                  ['value' => 'stg', 'label' => 'Staging Sandbox'],
                ];
              @endphp
              <x-ui.select label="Deployment Environment" :options="$mockOptions" selected="prod" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.select label="Deployment Environment" :options="$options" selected="prod" /&gt;</code></pre>
            </div>
          </div>

          <!-- Search Input -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Pill Search Bar</span>
              <span class="synkra-component-badge">Search Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.search placeholder="Search orders, transactions..." />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.search placeholder="Search orders, transactions..." /&gt;</code></pre>
            </div>
          </div>

          <!-- Quantity Input -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Interactive Quantity Input</span>
              <span class="synkra-component-badge">E-Commerce Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.quantity label="Stock Quantity Selector" min="1" max="100" value="5" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.quantity label="Stock Quantity Selector" min="1" max="100" value="5" /&gt;</code></pre>
            </div>
          </div>

          <!-- Link Share Input -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Link Sharing & Copy Input</span>
              <span class="synkra-component-badge">Utility Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.url-input prefix="https://" value="synkra.io/invite/tenant_a48f93" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.url-input prefix="https://" value="synkra.io/invite/tenant_a48f93" /&gt;</code></pre>
            </div>
          </div>

          <!-- File Upload -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Drag & Drop File Upload</span>
              <span class="synkra-component-badge">Form Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.file-upload text="Drop invoices or click to browse" accept="application/pdf" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.file-upload text="Drop invoices or click to browse" accept="application/pdf" /&gt;</code></pre>
            </div>
          </div>

        </div>
      </section>

      <!-- Toggles Section -->
      <section id="toggles" class="synkra-showcase-section">
        <h2 class="synkra-showcase-title">Toggles & Switches</h2>
        <p class="synkra-showcase-desc">Modern toggles including custom checkboxes, basic sliding switches, lock buttons, and HSL glow toggles.</p>
        
        <div class="synkra-showcase-grid">
          
          <!-- Checkbox -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Custom Checkbox</span>
              <span class="synkra-component-badge">Toggle Component</span>
            </div>
            <div class="synkra-showcase-card-body" style="flex-direction: column; align-items: flex-start; gap: 10px;">
              <x-ui.checkbox label="I accept all developer terms" checked="true" />
              <x-ui.checkbox label="Send me product newsletters" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.checkbox label="I accept all developer terms" checked="true" /&gt;</code></pre>
            </div>
          </div>

          <!-- Switch -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Sliding Switch</span>
              <span class="synkra-component-badge">Toggle Component</span>
            </div>
            <div class="synkra-showcase-card-body" style="flex-direction: column; align-items: flex-start; gap: 10px;">
              <x-ui.switch label="Enable push notifications" checked="true" />
              <x-ui.switch label="Maintenance mode status" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.switch label="Enable push notifications" checked="true" /&gt;</code></pre>
            </div>
          </div>

          <!-- Lock Button -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Lock Toggle Button</span>
              <span class="synkra-component-badge">Interactive Toggle</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.lock checked="true" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.lock checked="true" /&gt;</code></pre>
            </div>
          </div>

          <!-- Advanced Switch -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">HSL Glow Switch</span>
              <span class="synkra-component-badge">Premium Toggle</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.switch-adv label="Multi-factor Authentication" checked="true" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.switch-adv label="Multi-factor Authentication" checked="true" /&gt;</code></pre>
            </div>
          </div>

          <!-- Hamburger Toggle -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Animated Hamburger Menu</span>
              <span class="synkra-component-badge">Toggle Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.hamburger />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.hamburger /&gt;</code></pre>
            </div>
          </div>

        </div>
      </section>

      <!-- Tooltips & Modals Section -->
      <section id="tooltips" class="synkra-showcase-section">
        <h2 class="synkra-showcase-title">Tooltips & Modals</h2>
        <p class="synkra-showcase-desc">User assistance tools, interactive modals, add-to-cart animations, and dismissible alerts.</p>
        
        <div class="synkra-showcase-grid">
          
          <!-- Standard Tooltip -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Hover Tooltip</span>
              <span class="synkra-component-badge">Assistance Tool</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.tooltip text="Need Help?" tooltip="💡 Get assistance with setting up billing models!" icon="fa-regular fa-lightbulb" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.tooltip text="Need Help?" tooltip="💡 Get assistance..." icon="fa-regular fa-lightbulb" /&gt;</code></pre>
            </div>
          </div>

          <!-- FAQ Floating Button -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Floating Help / FAQ</span>
              <span class="synkra-component-badge">Assistance Tool</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.faq tooltip="Open Documentation Portal" icon="fa-solid fa-book-open" url="#" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.faq tooltip="Open Documentation Portal" icon="fa-solid fa-book-open" url="#" /&gt;</code></pre>
            </div>
          </div>

          <!-- Add to Cart with Slide & Price Tooltip -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Animated Add To Cart</span>
              <span class="synkra-component-badge">Animated Button</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.cart-btn price="$49.00" text="Purchase Pack" icon="fa-solid fa-bolt" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.cart-btn price="$49.00" text="Purchase Pack" icon="fa-solid fa-bolt" /&gt;</code></pre>
            </div>
          </div>

          <!-- Modal Trigger and Dialog -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Interactive Modal Dialog</span>
              <span class="synkra-component-badge">Modal Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              <x-ui.modal title="Confirm Database Reset" id="dbResetModal">
                <p style="margin: 0; font-size: 0.9rem; line-height: 1.5;">Are you sure you want to reset the tenant database structure? This action will permanently erase all testing transactions and cannot be undone.</p>
              </x-ui.modal>
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.modal title="Confirm Database Reset" id="dbResetModal"&gt;
  &lt;p&gt;Are you sure you want to reset the database...&lt;/p&gt;
&lt;/x-ui.modal&gt;</code></pre>
            </div>
          </div>

          <!-- Dismissible Alert -->
          <div class="synkra-showcase-card synkra-showcase-card-full">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Alerts & Notifications</span>
              <span class="synkra-component-badge">Alert Component</span>
            </div>
            <div class="synkra-showcase-card-body" style="flex-direction: column; gap: 15px; align-items: stretch;">
              <x-ui.alert type="success" title="Backup Complete" message="Tenant transaction history database tables have been safely exported to secure S3 storage." />
              <x-ui.alert type="warning" title="API Deprecation Alert" message="Restricted keys will expire in 15 days. Please update your backend calls to use standard tokens." />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.alert type="success" title="Backup Complete" message="..." /&gt;</code></pre>
            </div>
          </div>

          <!-- Comments Feed -->
          <div class="synkra-showcase-card synkra-showcase-card-full">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Comments Feed Card</span>
              <span class="synkra-component-badge">Form Component</span>
            </div>
            <div class="synkra-showcase-card-body">
              @php
                $mockComments = [
                  [
                    'user_name' => 'Sarah Connor',
                    'user_avatar' => null,
                    'date_time' => 'Today at 3:12 PM',
                    'likes' => 8,
                    'content' => 'This is looking very clean! Love the transitions and dark mode layout styles.',
                    'online' => true
                  ],
                  [
                    'user_name' => 'Alex Mercer',
                    'user_avatar' => null,
                    'date_time' => 'Yesterday at 9:45 AM',
                    'likes' => 2,
                    'content' => 'I verified the responsiveness on tablet resolutions, components adapt nicely.',
                    'online' => false
                  ]
                ];
              @endphp
              <x-ui.comments title="Task Discussions" :comments="$mockComments" placeholder="Write a response..." />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.comments title="Task Discussions" :comments="$comments" /&gt;</code></pre>
            </div>
          </div>

        </div>
      </section>

      <!-- Navigation & Tables Section -->
      <section id="tables" class="synkra-showcase-section">
        <h2 class="synkra-showcase-title">Navigation & Tables</h2>
        <p class="synkra-showcase-desc">Modern sub-navigation tab lists and clean data tables built with CSS variables.</p>
        
        <div class="synkra-showcase-grid">
          
          <!-- Navigation Tabs -->
          <div class="synkra-showcase-card synkra-showcase-card-full">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Tabs Menu Navigation</span>
              <span class="synkra-component-badge">Navigation Component</span>
            </div>
            <div class="synkra-showcase-card-body" style="align-items: stretch; flex-direction: column;">
              @php
                $mockTabs = [
                  ['id' => 'profile', 'label' => 'Team Profile', 'icon' => 'fa-regular fa-id-card'],
                  ['id' => 'security', 'label' => 'Security Controls', 'icon' => 'fa-solid fa-shield-halved'],
                  ['id' => 'billing', 'label' => 'Billing Details', 'icon' => 'fa-regular fa-credit-card'],
                ];
              @endphp
              <x-ui.tabs :tabs="$mockTabs" activeTab="security" id="settingsTabs" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.tabs :tabs="$tabs" activeTab="security" id="settingsTabs" /&gt;</code></pre>
            </div>
          </div>

          <!-- Data Table -->
          <div class="synkra-showcase-card synkra-showcase-card-full">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Data Table</span>
              <span class="synkra-component-badge">Data Component</span>
            </div>
            <div class="synkra-showcase-card-body" style="align-items: stretch;">
              @php
                $mockHeaders = ['ID', 'Employee', 'Status', 'Designation', 'Actions'];
                $mockRows = [
                  ['1001', 'Alex Mercer', 'Active', 'Principal Engineer', 'Actions'],
                  ['1002', 'Sarah Connor', 'Inactive', 'Systems Administrator', 'Actions'],
                  ['1003', 'John Doe', 'Active', 'Product Manager', 'Actions'],
                ];
              @endphp
              <x-ui.table :headers="$mockHeaders" :rows="$mockRows" />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.table :headers="$headers" :rows="$rows" /&gt;</code></pre>
            </div>
          </div>

        </div>
      </section>

      <!-- Loaders & States Section -->
      <section id="loaders" class="synkra-showcase-section">
        <h2 class="synkra-showcase-title">Loaders & States</h2>
        <p class="synkra-showcase-desc">Beautifully animated components for loading transitions, errors, and connectivity drops.</p>
        
        <div class="synkra-showcase-grid">
          
          <!-- Custom Loader -->
          <div class="synkra-showcase-card">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Custom App Loader</span>
              <span class="synkra-component-badge">Animation Component</span>
            </div>
            <div class="synkra-showcase-card-body" style="min-height: 350px;">
              <x-ui.loader />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.loader /&gt;</code></pre>
            </div>
          </div>

          <!-- 404 Error -->
          <div class="synkra-showcase-card synkra-showcase-card-full">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">404 Error State</span>
              <span class="synkra-component-badge">State Component</span>
            </div>
            <div class="synkra-showcase-card-body" style="padding: 2rem;">
              <x-ui.error-404 />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.error-404 /&gt;</code></pre>
            </div>
          </div>

          <!-- Offline / No Internet State -->
          <div class="synkra-showcase-card synkra-showcase-card-full">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Offline / Connection Lost</span>
              <span class="synkra-component-badge">State Component</span>
            </div>
            <div class="synkra-showcase-card-body" style="padding: 2rem;">
              <x-ui.offline />
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.offline /&gt;</code></pre>
            </div>
          </div>

        </div>
      </section>

      <!-- Patterns Section -->
      <section id="patterns" class="synkra-showcase-section">
        <h2 class="synkra-showcase-title">Grid Patterns</h2>
        <p class="synkra-showcase-desc">Clean background grid overlay systems designed to fit behind landing pages or dashboards.</p>
        
        <div class="synkra-showcase-grid">
          
          <!-- Background Grid -->
          <div class="synkra-showcase-card synkra-showcase-card-full">
            <div class="synkra-showcase-card-header">
              <span class="synkra-component-name">Geometric Grid Background Overlay</span>
              <span class="synkra-component-badge">Pattern Component</span>
            </div>
            <div class="synkra-showcase-card-body" style="padding: 0; min-height: 250px; overflow: hidden; border-radius: 0 0 16px 16px;">
              <x-ui.grid>
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 250px; text-align: center; padding: 2rem;">
                  <h3 style="margin: 0 0 0.5rem 0; font-size: 1.5rem; color: var(--headings);">Dashboard Overlay Area</h3>
                  <p style="margin: 0; font-size: 0.9rem; color: var(--text-secondary); max-width: 450px;">This grid fades out towards the bottom and sides using radial gradient masks, producing a very premium dashboard background layout.</p>
                </div>
              </x-ui.grid>
            </div>
            <div class="synkra-showcase-card-footer">
              <button class="synkra-code-toggle" onclick="toggleCode(this)"><i class="fa-solid fa-code"></i> Show Code</button>
              <pre class="synkra-code-block"><button class="synkra-copy-code-btn" onclick="copySnippet(this)">Copy</button><code>&lt;x-ui.grid&gt;
  &lt;div&gt;Your dashboard content here...&lt;/div&gt;
&lt;/x-ui.grid&gt;</code></pre>
            </div>
          </div>

        </div>
      </section>

    </main>

  </div>

  <!-- Javascript for Interaction -->
  <script>
    // Theme Switcher Logic
    function toggleTheme() {
      const html = document.documentElement;
      const themeToggler = document.getElementById('themeToggler');
      const themeText = document.getElementById('themeText');
      const icon = themeToggler.querySelector('i');
      
      const currentTheme = html.getAttribute('data-theme');
      
      if (currentTheme === 'light') {
        html.setAttribute('data-theme', 'dark');
        themeText.textContent = 'Light Mode';
        icon.className = 'fa-solid fa-sun';
      } else {
        html.setAttribute('data-theme', 'light');
        themeText.textContent = 'Dark Mode';
        icon.className = 'fa-solid fa-moon';
      }
    }

    // Code Accordion Toggle Logic
    function toggleCode(btn) {
      const cardFooter = btn.closest('.synkra-showcase-card-footer');
      const codeBlock = cardFooter.querySelector('.synkra-code-block');
      
      if (codeBlock.classList.contains('open')) {
        codeBlock.classList.remove('open');
        btn.innerHTML = '<i class="fa-solid fa-code"></i> Show Code';
      } else {
        codeBlock.classList.add('open');
        btn.innerHTML = '<i class="fa-solid fa-code"></i> Hide Code';
      }
    }

    // Copy Snippet Logic
    function copySnippet(btn) {
      const code = btn.nextElementSibling.textContent;
      navigator.clipboard.writeText(code).then(() => {
        const originalText = btn.textContent;
        btn.textContent = 'Copied!';
        btn.style.backgroundColor = 'rgba(34, 197, 94, 0.4)';
        
        setTimeout(() => {
          btn.textContent = originalText;
          btn.style.backgroundColor = '';
        }, 1500);
      });
    }

    // Scroll Active Sidebar Indicator Logic
    window.addEventListener('DOMContentLoaded', () => {
      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          const id = entry.target.getAttribute('id');
          if (entry.intersectionRatio > 0.15) {
            document.querySelectorAll('.synkra-sidebar-link').forEach(link => {
              link.classList.remove('active');
              if (link.getAttribute('href') === `#${id}`) {
                link.classList.add('active');
              }
            });
          }
        });
      }, { threshold: [0.15, 0.5] });

      // Track all sections
      document.querySelectorAll('.synkra-showcase-section').forEach(section => {
        observer.observe(section);
      });
    });
  </script>

</body>
</html>
