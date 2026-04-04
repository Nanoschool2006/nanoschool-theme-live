<?php 
// Template Name: mentors profile
?>
<html>
    <head>
                <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nanoschool – Mentors</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Tailwind config: extend a tiny bit for brand feel
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50: '#f0f7ff',
              100: '#dceeff',
              200: '#b8dcff',
              300: '#8ec5ff',
              400: '#5ea8ff',
              500: '#2d87ff', // primary
              600: '#1f6bdd',
              700: '#1753b1',
              800: '#123f86',
              900: '#0f336b'
            }
          },
          boxShadow: {
            soft: '0 6px 30px -10px rgba(0,0,0,.15)'
          }
        }
      },
      darkMode: 'class'
    }
  </script>
  <style>
/* Sticky Compare button (always light look) */
#compareBtn.compare-fab{
  position: fixed;
  right: max(16px, env(safe-area-inset-right));
  bottom: max(16px, env(safe-area-inset-bottom));
  z-index: 1000;
  padding: 12px 16px;
  border-radius: 14px;
  background: #2d87ff;           /* brand blue */
  color: #ffffff;
  border: 0;
  box-shadow: 0 12px 30px -10px rgba(0,0,0,.35);
  transition: transform .18s ease, opacity .18s ease, box-shadow .18s ease;
  opacity: 0;
  transform: translateY(14px);
  pointer-events: none;           /* hidden state: no clicks */
}

/* visible */
#compareBtn.compare-fab.is-visible{
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}

/* disabled (when <2 selected) */
#compareBtn.compare-fab:disabled{
  background: #cbd5e1;           /* slate-300 */
  color: #0f172a;                 /* slate-900 */
  box-shadow: none;
  cursor: not-allowed;
}

/* hover bump */
#compareBtn.compare-fab.is-visible:not(:disabled):hover{
  box-shadow: 0 16px 36px -10px rgba(0,0,0,.45);
}

/* hide in print */
@media print { #compareBtn.compare-fab{ display:none !important; } }


    /* Hide scrollbar for aesthetic filter drawers (still scrollable) */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  /* Compare modal */
/* ---- Force LIGHT theme for compare modal ---- */
#compareModal {
  color-scheme: light;           /* hint browsers */
}
#compareModal .cm-panel {
  background: #ffffff !important;
  color: #0f172a !important;     /* slate-900 */
}
#compareModal header {
  border-bottom: 1px solid #e5e7eb !important; /* slate-200 */
}
#compareModal .cm-body {
  background: #ffffff !important;
  color: #0f172a !important;
}
#compareModal table {
  color: #0f172a !important;
}
#compareModal tr {
  background: #f8fafc !important;               /* slate-50 */
}
#compareModal th, 
#compareModal td {
  color: #0f172a !important;
}
#compareModal .best {
  background: #ecfdf5 !important;               /* green-50 */
}

/* Buttons (light) */
#compareModal .btn {
  background: #ffffff !important;
  color: #0f172a !important;
  border: 1px solid #cbd5e1 !important;         /* slate-300 */
}
#compareModal .btn.primary {
  background: #2d87ff !important;               /* your brand blue */
  border-color: #2d87ff !important;
  color: #ffffff !important;
}

/* Backdrop stays dark so the panel pops */
#compareModal .cm-backdrop {
  background: rgba(0,0,0,.6) !important;
}

/* Neutralize Tailwind `.dark` scope if your page toggles dark mode */
html.dark #compareModal *,
:root.dark #compareModal * {
  color: inherit !important;
  background-color: inherit !important;
}

  #compareModal{position:fixed;inset:0;z-index:1000;display:none}
  #compareModal .cm-backdrop{position:absolute;inset:0;background:rgba(0,0,0,.6)}
  #compareModal .cm-panel{position:relative;max-width:1000px;margin:8rem auto;background:#fff;border-radius:16px;box-shadow:0 20px 40px -15px rgba(0,0,0,.35);overflow:hidden}
  @media (prefers-color-scheme: dark){
    #compareModal .cm-panel{background:#0b1220;color:#e5e7eb}
  }
  #compareModal header{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid rgba(0,0,0,.08)}
  #compareModal .cm-body{padding:16px;overflow:auto;max-height:70vh}
  #compareModal table{width:100%;border-collapse:separate;border-spacing:0 10px}
  #compareModal th,#compareModal td{padding:10px 12px;vertical-align:top}
  #compareModal tr{background:#f8fafc;border-radius:12px}
  @media (prefers-color-scheme: dark){#compareModal tr{background:#0f172a}}
  #compareModal .best{background:#ecfdf5;border-radius:10px}
  @media (prefers-color-scheme: dark){#compareModal .best{background:#064e3b}}
  #compareCount{min-width:1ch;display:inline-block;text-align:center}
  .btn{border:1px solid #cbd5e1;border-radius:10px;padding:7px 10px;font-size:14px}
  .btn.primary{background:#2d87ff;color:#fff;border-color:#2d87ff}
  .btn:disabled{opacity:.6;cursor:not-allowed}
   
<style id="mentor-darkmode-fixes">
/* -----------------------------
   Nanoschool Mentors — Dark mode polish
   Drop-in: improves contrast on cards, chips, inputs, and any stray bg-white.
   Safe for light mode (scoped to html.dark).
-------------------------------- */
html.dark {
  color-scheme: dark;
  --dm-bg:        #0b1220; /* page */
  --dm-surface:   #0f172a; /* panels/cards */
  --dm-surface-2: #111827; /* deeper surface */
  --dm-text:      #e5e7eb; /* primary text */
  --dm-muted:     #94a3b8; /* secondary text */
  --dm-border:    #334155; /* borders */
  --dm-chip-bg:   #0f172a; /* chip pill bg */
  --dm-chip-bd:   #334155; /* chip pill border */
  --dm-input:     #0b1220; /* inputs bg */
  --dm-input-bd:  #334155;
  --dm-placeholder:#64748b;
  --dm-brand:     #2d87ff; /* your brand blue */
  --dm-brand-600: #1f6bdd;
  background: var(--dm-bg);
}

/* Page-level text default */
html.dark body,
html.dark .dark\:text-slate-300,
html.dark .text-slate-800,
html.dark .text-slate-700,
html.dark .text-slate-600 {
  color: var(--dm-text) !important;
}
html.dark .text-slate-500,
html.dark .text-slate-400,
html.dark small,
html.dark .text-sm {
  color: var(--dm-muted) !important;
}

/* Any "bg-white" or light panels appearing in dark */
html.dark .bg-white {
  background-color: var(--dm-surface) !important;
}
html.dark .border-slate-200,
html.dark .border-slate-300,
html.dark .border-gray-200 {
  border-color: var(--dm-border) !important;
}

/* Top nav stays translucent but readable */
html.dark header.sticky {
  background-color: rgba(2, 6, 23, 0.9) !important; /* slate-950/90 */
  border-color: var(--dm-border) !important;
}

/* Hero gradient base */
html.dark section[class*="bg-gradient"] {
  background-image: linear-gradient(to bottom, rgba(30,41,59,.4), transparent) !important;
}

/* Sidebar + toolbars */
html.dark .rounded-2xl.border.dark\:bg-slate-800,
html.dark .shadow-soft {
  background-color: var(--dm-surface) !important;
  border-color: var(--dm-border) !important;
}

/* Inputs / selects */
html.dark input[type="text"],
html.dark input[type="email"],
html.dark select,
html.dark textarea {
  background-color: var(--dm-input) !important;
  color: var(--dm-text) !important;
  border-color: var(--dm-input-bd) !important;
}
html.dark input::placeholder,
html.dark textarea::placeholder {
  color: var(--dm-placeholder) !important;
}
html.dark input:focus,
html.dark select:focus,
html.dark textarea:focus {
  outline: none !important;
  box-shadow: 0 0 0 3px rgba(45,135,255,.25) !important;
  border-color: var(--dm-brand) !important;
}

/* Buttons */
html.dark .btn,
html.dark button.border,
html.dark .rounded-xl.border {
  background: var(--dm-surface-2) !important;
  color: var(--dm-text) !important;
  border-color: var(--dm-border) !important;
}
html.dark .btn.primary,
html.dark .bg-brand-500 {
  background: var(--dm-brand) !important;
  border-color: var(--dm-brand) !important;
  color: #fff !important;
}
html.dark .bg-brand-500:hover {
  background: var(--dm-brand-600) !important;
}

/* Compare floating button always readable */
html.dark #compareBtn.compare-fab {
  background: var(--dm-brand) !important;
  color: #fff !important;
  border-color: transparent !important;
}

/* Chips / tags inside cards & modals */
html.dark #mentorResults span.rounded-full,
html.dark .cm-panel span.rounded-full {
  background: var(--dm-chip-bg) !important;
  color: var(--dm-text) !important;
  border: 1px solid var(--dm-chip-bd) !important;
}

/* Mentor cards */
html.dark #mentorResults article {
  background: var(--dm-surface) !important;
  border-color: var(--dm-border) !important;
  color: var(--dm-text) !important;
}
html.dark #mentorResults article:hover {
  box-shadow: 0 16px 36px -12px rgba(0,0,0,.45) !important;
}

/* Small meta lines on cards */
html.dark #mentorResults .text-xs,
html.dark #mentorResults .line-clamp-2 {
  color: var(--dm-muted) !important;
}

/* Images placeholder bg */
html.dark #mentorResults img,
html.dark .cm-panel img {
  background: #1f2937 !important; /* slate-800 */
}

/* “Become a Mentor” CTA stays brand-forward */
html.dark #become .text-white,
html.dark #become .bg-white {
  color: #0b1220 !important; /* ensure white button text readable if inverted */
}
html.dark #become .bg-white {
  background: #fff !important;
  border-color: transparent !important;
}

/* Footer */
html.dark footer {
  background: transparent !important;
  border-color: var(--dm-border) !important;
  color: var(--dm-muted) !important;
}

/* ---- Optional: keep Compare modal LIGHT for readability (overrides any inherit rules) ---- */
html.dark #compareModal .cm-panel,
html.dark #compareModal .cm-body,
html.dark #compareModal header,
html.dark #compareModal table,
html.dark #compareModal tr,
html.dark #compareModal th,
html.dark #compareModal td {
  background: #ffffff !important;
  color: #0f172a !important;
  border-color: #e5e7eb !important;
}
html.dark #compareModal .best { background: #ecfdf5 !important; }

/* Focus rings for accessibility */
html.dark a:focus-visible,
html.dark button:focus-visible,
html.dark [role="button"]:focus-visible,
html.dark input:focus-visible,
html.dark select:focus-visible {
  outline: none !important;
  box-shadow: 0 0 0 3px rgba(45,135,255,.35) !important;
}

/* Dismiss blue-on-blue in any accidental .text-slate-900 inside dark surfaces */
html.dark .text-slate-900 { color: var(--dm-text) !important; }

/* Ensure any lingering .bg-slate-100 pills don’t look lifted */
html.dark .bg-slate-100 { background-color: var(--dm-surface-2) !important; }

</style>
<style id="mentor-darkmode-fixes">
/* -----------------------------
   Nanoschool Mentors — Dark mode polish
   Drop-in: improves contrast on cards, chips, inputs, and any stray bg-white.
   Safe for light mode (scoped to html.dark).
-------------------------------- */
html.dark {
  color-scheme: dark;
  --dm-bg:        #0b1220; /* page */
  --dm-surface:   #0f172a; /* panels/cards */
  --dm-surface-2: #111827; /* deeper surface */
  --dm-text:      #e5e7eb; /* primary text */
  --dm-muted:     #94a3b8; /* secondary text */
  --dm-border:    #334155; /* borders */
  --dm-chip-bg:   #0f172a; /* chip pill bg */
  --dm-chip-bd:   #334155; /* chip pill border */
  --dm-input:     #0b1220; /* inputs bg */
  --dm-input-bd:  #334155;
  --dm-placeholder:#64748b;
  --dm-brand:     #2d87ff; /* your brand blue */
  --dm-brand-600: #1f6bdd;
  background: var(--dm-bg);
}

/* Page-level text default */
html.dark body,
html.dark .dark\:text-slate-300,
html.dark .text-slate-800,
html.dark .text-slate-700,
html.dark .text-slate-600 {
  color: var(--dm-text) !important;
}
html.dark .text-slate-500,
html.dark .text-slate-400,
html.dark small,
html.dark .text-sm {
  color: var(--dm-muted) !important;
}

/* Any "bg-white" or light panels appearing in dark */
html.dark .bg-white {
  background-color: var(--dm-surface) !important;
}
html.dark .border-slate-200,
html.dark .border-slate-300,
html.dark .border-gray-200 {
  border-color: var(--dm-border) !important;
}

/* Top nav stays translucent but readable */
html.dark header.sticky {
  background-color: rgba(2, 6, 23, 0.9) !important; /* slate-950/90 */
  border-color: var(--dm-border) !important;
}

/* Hero gradient base */
html.dark section[class*="bg-gradient"] {
  background-image: linear-gradient(to bottom, rgba(30,41,59,.4), transparent) !important;
}

/* Sidebar + toolbars */
html.dark .rounded-2xl.border.dark\:bg-slate-800,
html.dark .shadow-soft {
  background-color: var(--dm-surface) !important;
  border-color: var(--dm-border) !important;
}

/* Inputs / selects */
html.dark input[type="text"],
html.dark input[type="email"],
html.dark select,
html.dark textarea {
  background-color: var(--dm-input) !important;
  color: var(--dm-text) !important;
  border-color: var(--dm-input-bd) !important;
}
html.dark input::placeholder,
html.dark textarea::placeholder {
  color: var(--dm-placeholder) !important;
}
html.dark input:focus,
html.dark select:focus,
html.dark textarea:focus {
  outline: none !important;
  box-shadow: 0 0 0 3px rgba(45,135,255,.25) !important;
  border-color: var(--dm-brand) !important;
}

/* Buttons */
html.dark .btn,
html.dark button.border,
html.dark .rounded-xl.border {
  background: var(--dm-surface-2) !important;
  color: var(--dm-text) !important;
  border-color: var(--dm-border) !important;
}
html.dark .btn.primary,
html.dark .bg-brand-500 {
  background: var(--dm-brand) !important;
  border-color: var(--dm-brand) !important;
  color: #fff !important;
}
html.dark .bg-brand-500:hover {
  background: var(--dm-brand-600) !important;
}

/* Compare floating button always readable */
html.dark #compareBtn.compare-fab {
  background: var(--dm-brand) !important;
  color: #fff !important;
  border-color: transparent !important;
}

/* Chips / tags inside cards & modals */
html.dark #mentorResults span.rounded-full,
html.dark .cm-panel span.rounded-full {
  background: var(--dm-chip-bg) !important;
  color: var(--dm-text) !important;
  border: 1px solid var(--dm-chip-bd) !important;
}

/* Mentor cards */
html.dark #mentorResults article {
  background: var(--dm-surface) !important;
  border-color: var(--dm-border) !important;
  color: var(--dm-text) !important;
}
html.dark #mentorResults article:hover {
  box-shadow: 0 16px 36px -12px rgba(0,0,0,.45) !important;
}

/* Small meta lines on cards */
html.dark #mentorResults .text-xs,
html.dark #mentorResults .line-clamp-2 {
  color: var(--dm-muted) !important;
}

/* Images placeholder bg */
html.dark #mentorResults img,
html.dark .cm-panel img {
  background: #1f2937 !important; /* slate-800 */
}

/* “Become a Mentor” CTA stays brand-forward */
html.dark #become .text-white,
html.dark #become .bg-white {
  color: #0b1220 !important; /* ensure white button text readable if inverted */
}
html.dark #become .bg-white {
  background: #fff !important;
  border-color: transparent !important;
}

/* Footer */
html.dark footer {
  background: transparent !important;
  border-color: var(--dm-border) !important;
  color: var(--dm-muted) !important;
}

/* ---- Optional: keep Compare modal LIGHT for readability (overrides any inherit rules) ---- */
html.dark #compareModal .cm-panel,
html.dark #compareModal .cm-body,
html.dark #compareModal header,
html.dark #compareModal table,
html.dark #compareModal tr,
html.dark #compareModal th,
html.dark #compareModal td {
  background: #ffffff !important;
  color: #0f172a !important;
  border-color: #e5e7eb !important;
}
html.dark #compareModal .best { background: #ecfdf5 !important; }

/* Focus rings for accessibility */
html.dark a:focus-visible,
html.dark button:focus-visible,
html.dark [role="button"]:focus-visible,
html.dark input:focus-visible,
html.dark select:focus-visible {
  outline: none !important;
  box-shadow: 0 0 0 3px rgba(45,135,255,.35) !important;
}

/* Dismiss blue-on-blue in any accidental .text-slate-900 inside dark surfaces */
html.dark .text-slate-900 { color: var(--dm-text) !important; }

/* Ensure any lingering .bg-slate-100 pills don’t look lifted */
html.dark .bg-slate-100 { background-color: var(--dm-surface-2) !important; }
</style>
    </head>
    <body>



  <!-- Top Bar -->
  <header class="sticky top-0 z-40 backdrop-blur supports-[backdrop-filter]:bg-white/70 bg-white/90 dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-2 py-1 flex items-center gap-3">
      <nav class="ml-auto flex items-center gap-2">
        <button id="toggleDark" class="rounded-xl px-3 py-2 text-sm bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700" aria-pressed="false">
          <span class="hidden sm:inline">Dark mode</span>
          <svg class="w-5 h-5 inline sm:ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        </button>
        <a href="https://nanoschool.in/mentor/registration/" class="rounded-xl px-3 py-2 text-sm bg-brand-500 text-white hover:bg-brand-600 shadow-soft" style="text-decoration:none;" draggable="false">Become a Mentor</a>
      </nav>
    </div>
  </header>

  <!-- Hero -->
  <section class="bg-gradient-to-b from-brand-50 to-transparent dark:from-slate-800/30">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-2 py-4">
      <div class="grid lg:grid-cols-2 gap-8 items-center">
        <div class="">
          <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Meet the Mentors</h1>
          <p style="color: black;">Learn from industry professionals and academic experts dedicated to helping you succeed. Filter by domain, expertise, institution, and more.</p>
          <div class="mt-4 flex flex-wrap gap-2">
            <span class="px-3 py-1 text-xs rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700">Verified profiles</span>
            <span class="px-3 py-1 text-xs rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700">Live availability</span>
            <span class="px-3 py-1 text-xs rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700">1:1 &amp; cohort sessions</span>
          </div>
        </div>
       
      </div>
    </div>
  </section>

  <!-- Content -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 grid lg:grid-cols-12 gap-6">
    <!-- Sidebar Filters -->
<aside class="lg:col-span-3">
  <div class="lg:hidden mb-2">
    <button id="openFilters" class="w-full rounded-xl border bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 px-4 py-3 flex items-center justify-between">
      <span class="font-medium">Filters</span>
      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 5h18M6 12h12M10 19h4"></path></svg>
    </button>
  </div>
  <div id="filtersPanel" class="hidden lg:block lg:sticky lg:top-20 lg:max-h-[75vh] lg:overflow-y-auto no-scrollbar">
    <div class="rounded-2xl border bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 p-4 space-y-4 shadow-soft">
      <div>
        <label class="text-sm font-medium" for="q">Keyword</label>
        <input id="q" type="text" placeholder="e.g. nanocomposites" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900">
      </div>
      <div>
        <label class="text-sm font-medium">Domain</label>
        <div id="domainChips" class="mt-2 flex flex-wrap gap-2">
          <label><input type="checkbox" value="engineering"> Engineering</label>
					<label><input type="checkbox" value="Biotechnology"> Biotechnology</label>
					<label><input type="checkbox" value="AI"> AI</label>
					<label><input type="checkbox" value="Sustainability"> Sustainability</label>
          <label><input type="checkbox" value="nanotechnology"> Nanotechnology</label>
          <label><input type="checkbox" value="Law"> Law</label>
          <label><input type="checkbox" value="physics"> Physics</label>
          <label><input type="checkbox" value="Other"> Other</label>
        </div>
      </div>
      <div>
        <label class="text-sm font-medium" for="institution">Institution</label>
        <select id="institution" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900" style="padding:4px;">
          <option value="">All</option>
          <option value="iit">IIT</option>
          <option value="nstc">NSTC</option>
          <option value="aiims">AIIMS</option>
        </select>
      </div>
      <div>
        <label class="text-sm font-medium" for="designation">Designation</label>
        <select id="designation" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900" style="padding:4px;">
          <option value="">All</option>
          <option value="professor">Professor</option>
          <option value="scientist">Scientist</option>
          <option value="engineer">Engineer</option>
        </select>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="text-sm font-medium" for="sort">Sort</label>
          <select id="sort" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900" style="padding:4px;">
            <option value="relevance">Relevance</option>
            <option value="name">Name A→Z</option>
            <option value="experience">Experience (high)</option>
          </select>
        </div>
        <div>
          <label class="text-sm font-medium" for="availability">Availability</label>
          <select id="availability" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900" style="padding:4px;">
            <option value="any">Any</option>
            <option value="this week">This week</option>
            <option value="next week">Next week</option>
          </select>
        </div>
				      <div class="form-group">
  <label for="expMin" class="block mb-1">Experience (years)</label>
  <div class="grid" style="grid-template-columns: 1fr 1fr; gap: .5rem ">
    <input style="width:100px;" type="number" id="expMin" placeholder="Min Exp" min="0" step="0.5" class="input">
    <input style="width:100px;" type="number" id="expMax" placeholder="Max Exp" min="0" step="0.5" class="input">
  </div>
</div>
      </div>
      <div class="flex gap-2 pt-2">
        <button id="applyFilters" class="flex-1 rounded-xl bg-brand-500 hover:bg-brand-600 text-white py-2">Apply</button>
        <button id="resetFilters" class="flex-1 rounded-xl border border-slate-300 dark:border-slate-600 py-2">Reset</button>
      </div>
      <div class="pt-3 border-t border-slate-200 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400">
        Tip: Save a filter preset in the toolbar to reuse later.
      </div>
    </div>
  </div>
</aside>

    <!-- Main Grid -->
    <section class="lg:col-span-9 space-y-3">
      <!-- Toolbar -->
      <div class="" style="display:none1; height:0;">
        <div id="activeChips" class="flex flex-wrap gap-2"></div>
        <div class="ml-auto flex items-center gap-2">
           <button id="compareBtn" class="rounded-xl border border-slate-300 dark:border-slate-600 px-3 py-2 text-sm">Compare (<span id="compareCount">0</span>)</button>
          <div class="hidden sm:block w-px h-6 bg-slate-200 dark:bg-slate-700"></div>
          <label style="display: none;" class="text-sm flex items-center gap-2"><input id="toggleDense" type="checkbox" class="rounded"> Dense view</label>
        </div>
      </div>

      <!-- Results summary -->
      <div id="resultsSummary" class="text-sm text-slate-600 dark:text-slate-300"></div>

      <!-- Cards grid -->
<section id="mentorResults" class="" style="margin:5px;">
<?php echo FrmViewsDisplaysController::get_shortcode( array( 'id' => 82458 ) ); ?>
</section>
	<div id="mentorPager"></div>
    
    </section>
  </main>

  <!-- Become a Mentor CTA -->
  <section id="become" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
    <div style="justify-items: right;" class="rounded-2xl bg-gradient-to-r from-brand-300 to-brand-500 text-white p-8 shadow-soft grid md:grid-cols-2 gap-6">
      <div>
        <h3 class="text-2xl font-semibold">Are you a subject expert?</h3>
        <p class="mt-2 text-brand-50">Join our mentor community and help learners grow through 1:1 guidance, projects, and workshops.</p>
      </div>
      <form class="grid sm:grid-cols-1 gap-1 items-center" autocomplete="new-password">
       <a href="https://nanoschool.in/mentor/registration/" draggable="false"><button type="button" class="rounded-xl bg-white text-brand-700 hover:bg-brand-50 px-4 py-2 sm:col-span-2">Apply to Mentor</button></a>
      </form>
    </div>
  </section>
<div id="compareModal" aria-hidden="true">
  <div class="cm-backdrop" data-close="1"></div>
  <div class="cm-panel">
    <header>
      <h3 style="font-weight:600">Compare Mentors</h3>
      <div>
        <button id="cmClear" class="btn" title="Clear selection">Clear</button>
        <button id="cmClose" class="btn" data-close="1">Close</button>
      </div>
    </header>
    <div class="cm-body">
      <div id="cmTableWrap"></div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
  const PER_PAGE = 20;
  let currentPage = 1;

  const container         = document.getElementById('mentorResults');
  const pager             = document.getElementById('mentorPager');
  const summaryEl         = document.getElementById('resultsSummary');
  const cards             = Array.from(container.querySelectorAll('article'));

  // controls
  const keywordInput       = document.getElementById('q');
  const domainChipsWrap    = document.getElementById('domainChips');
  const institutionSelect  = document.getElementById('institution');
  const designationSelect  = document.getElementById('designation');
  const sortSelect         = document.getElementById('sort');
  const availabilitySelect = document.getElementById('availability');
  const applyBtn           = document.getElementById('applyFilters');
  const resetBtn           = document.getElementById('resetFilters');
	const expMinInput       = document.getElementById('expMin');
  const expMaxInput       = document.getElementById('expMax');
	
  // helpers
  const norm = s => (s||'').toString().trim().toLowerCase();
  const tokenize = s => norm(s).split(/[,|]/).map(x=>x.trim()).filter(Boolean);

  const textCache = new WeakMap();
  const cardText = c => {
    let t = textCache.get(c);
    if (!t){ t = (c.textContent || '').toLowerCase(); textCache.set(c,t); }
    return t;
  };

  function selectedDomains(){
    return Array.from(domainChipsWrap.querySelectorAll('input[type="checkbox"]:checked'))
      .map(chk => norm(chk.value));
  }

 function matches(card){
  const kw   = norm(keywordInput.value);
  const inst = norm(institutionSelect.value);
  const des  = norm(designationSelect.value);
  const av   = norm(availabilitySelect.value);
  const doms = selectedDomains();

  const cInst  = norm(card.dataset.institution);
  const cDes   = norm(card.dataset.designation);
  const cAvail = norm(card.dataset.availability || 'any');
  const cDom   = tokenize(card.dataset.domains);
  const hay    = cardText(card);

  if (kw && !hay.includes(kw)) return false;
  if (inst && inst !== 'all' && !cInst.includes(inst)) return false;
  if (des && des !== 'all' && cDes !== des) return false;
  if (av && av !== 'any' && cAvail !== av) return false;
  if (doms.length && !doms.some(d => cDom.includes(d))) return false;

  // NEW: experience range
  const minY = parseFloat(expMinInput.value);
  const maxY = parseFloat(expMaxInput.value);
  if (!isNaN(minY) || !isNaN(maxY)){
    const y = yearsOfExp(card);
    // If we can't determine the years when filtering is active, hide it
    if (isNaN(y)) return false;
    if (!isNaN(minY) && y < minY) return false;
    if (!isNaN(maxY) && y > maxY) return false;
  }

  return true;
}

 function sortMatched(arr){
  const mode = sortSelect.value;
  if (mode === 'name'){
    arr.sort((a,b)=> (a.dataset.name||'').localeCompare(b.dataset.name||''));
  } else if (mode === 'experience'){
    arr.sort((a,b)=> (yearsOfExp(b) - yearsOfExp(a)));
  }
  // relevance = keep server order
}
  function setSummary(count){
    if (!summaryEl) return;
    if (count === 0){
      summaryEl.innerHTML = `No mentors found — <button type="button" id="clearSummaryFilters" class="underline">Clear filters</button>`;
      document.getElementById('clearSummaryFilters')?.addEventListener('click', () => resetBtn?.click());
    } else {
      summaryEl.textContent = `${count} mentor${count === 1 ? '' : 's'} found`;
    }
  }

  function buildPager(total, pages){
    const btn = (label, page, opts={}) => {
      const b = document.createElement('button');
      b.textContent = label;
      b.className = 'pg-btn';
      if (opts.current) b.setAttribute('aria-current','page');
      if (opts.disabled) b.disabled = true;
      b.addEventListener('click', () => { if (!b.disabled){ currentPage = page; renderPage(); }});
      return b;
    };

    pager.innerHTML = '';

    // Prev
    pager.appendChild(btn('«', Math.max(1, currentPage-1), {disabled: currentPage===1}));

    // numbers (condensed)
    const pagesToShow = new Set([1, 2, pages-1, pages, currentPage-1, currentPage, currentPage+1].filter(p => p>=1 && p<=pages));
    let last=0;
    for(let p=1; p<=pages; p++){
      if (!pagesToShow.has(p)) continue;
      if (last && p-last>1){
        const ell = document.createElement('span');
        ell.textContent='…';
        ell.style.padding='6px 6px';
        pager.appendChild(ell);
      }
      pager.appendChild(btn(String(p), p, {current: p===currentPage}));
      last=p;
    }

    // Next
    pager.appendChild(btn('»', Math.min(pages, currentPage+1), {disabled: currentPage===pages}));
  }

  function renderPage(){
    const matched = cards.filter(c => c.dataset.match === '1');
    sortMatched(matched);

    // hide all first
    cards.forEach(c => c.style.display = 'none');

    const total = matched.length;
    setSummary(total);

    const pages = Math.max(1, Math.ceil(total / PER_PAGE));
    if (currentPage > pages) currentPage = pages;

    const start = (currentPage - 1) * PER_PAGE;
    const end   = start + PER_PAGE;

    matched.forEach((c, i) => {
      if (i >= start && i < end) {
        c.style.display = '';
        // append in sorted order so grid remains correct
        container.appendChild(c);
      }
    });

    buildPager(total, pages);

    // UX: bring results into view & notify any listeners (e.g., compare FAB)
    container.scrollIntoView({ behavior:'smooth', block:'start' });
    document.dispatchEvent(new Event('mentor:realign'));
  }

  function applyFilters(resetPage=true){
    cards.forEach(c => c.dataset.match = matches(c) ? '1' : '0');
    if (resetPage) currentPage = 1;
    renderPage();
  }

/* Make yearsOfExp globally available */
(function (global) {
  function yearsOfExp(card){
    // 1) Prefer data-* attributes
    const ds = card?.dataset || {};
    for (const key of ['experience','exp','yoe','years']) {
      const v = ds[key];
      if (v != null && v !== '' && !Number.isNaN(parseFloat(v))) {
        return parseFloat(v);
      }
    }
    // 2) Fallback: parse visible text (e.g., "3.5 yrs exp", "22 years experience")
    const text = (card.textContent || '');
    const m = text.match(/(\d+(?:\.\d+)?)\s*(?:\+)?\s*(?:yrs?|years?)\s*(?:of\s+)?(?:experience|exp)?/i);
    return m ? parseFloat(m[1]) : NaN;
  }
  global.yearsOfExp = yearsOfExp;  // expose it
})(window);

  // events
  applyBtn?.addEventListener('click', ()=>applyFilters(true));
  resetBtn?.addEventListener('click', ()=>{
    keywordInput.value=''; sortSelect.selectedIndex=0;
    institutionSelect.selectedIndex=0; designationSelect.selectedIndex=0; availabilitySelect.selectedIndex=0;
    domainChipsWrap.querySelectorAll('input[type="checkbox"]').forEach(c=>c.checked=false);
    cards.forEach(c => c.dataset.match='1');
    currentPage=1; renderPage(); 
  });
  keywordInput?.addEventListener('input', ()=>applyFilters(true));
  domainChipsWrap?.addEventListener('change', e => { if(e.target.matches('input[type="checkbox"]')) applyFilters(true); });
  sortSelect?.addEventListener('change', ()=> renderPage());

  // initial
  cards.forEach(c => c.dataset.match='1');   // default: everything matches
  renderPage();
});

document.addEventListener('DOMContentLoaded', function () {
  const VIEW_ID = '82458'; // ← your Formidable View ID

  // Controls (so they stay pre-filled from URL)
  const keywordInput       = document.getElementById('q');
  const domainChipsWrap    = document.getElementById('domainChips');
  const institutionSelect  = document.getElementById('institution');
  const designationSelect  = document.getElementById('designation');
  const availabilitySelect = document.getElementById('availability');
  const sortSelect         = document.getElementById('sort'); // optional client sort

  // Pager container (your markup)
  const pagerCont = document.querySelector('.frm_pagination_cont');

  const FILTER_KEYS = ['kw', 'inst', 'desig', 'avail', 'domain', 'csort'];

  function getCurrentFiltersFromURL() {
    const q = new URLSearchParams(window.location.search);
    const out = new URLSearchParams();
    for (const k of FILTER_KEYS) if (q.has(k) && q.get(k) !== '') out.set(k, q.get(k));
    return out;
  }

  // Put URL params back into the controls (so UI matches the page content)
  function hydrateControlsFromURL() {
    const q = new URLSearchParams(window.location.search);
    if (keywordInput && q.has('kw')) keywordInput.value = q.get('kw') || '';

    if (institutionSelect && q.has('inst'))  institutionSelect.value  = q.get('inst');
    if (designationSelect && q.has('desig')) designationSelect.value  = q.get('desig');
    if (availabilitySelect && q.has('avail')) availabilitySelect.value = q.get('avail');

    if (domainChipsWrap && q.has('domain')) {
      const want = new Set((q.get('domain') || '').split('|').map(s => s.trim().toLowerCase()));
      domainChipsWrap.querySelectorAll('input[type="checkbox"]').forEach(chk => {
        chk.checked = want.has(chk.value.trim().toLowerCase());
      });
    }

    if (sortSelect && q.has('csort')) sortSelect.value = q.get('csort');
  }

  // Ensure every pager link carries current filters
  function decoratePagerLinks() {
    if (!pagerCont) return;
    const keep = getCurrentFiltersFromURL();

    pagerCont.querySelectorAll('a.page-link').forEach(a => {
      try {
        const url = new URL(a.getAttribute('href'), window.location.origin);
        // preserve Formidable page param frm-page-{VIEW_ID}
        // then add current filters
        keep.forEach((v, k) => url.searchParams.set(k, v));
        a.setAttribute('href', url.toString());
      } catch (e) { /* ignore bad links */ }
    });
  }

  // Intercept clicks just in case theme rewrites hrefs later
  function interceptPagerClicks() {
    if (!pagerCont) return;
    pagerCont.addEventListener('click', function (e) {
      const link = e.target.closest('a.page-link');
      if (!link) return;
      e.preventDefault();

      const keep = getCurrentFiltersFromURL();
      const url = new URL(link.getAttribute('href'), window.location.origin);
      keep.forEach((v, k) => url.searchParams.set(k, v));
      // Defensive: ensure page param for this View still exists
      const pageParam = `frm-page-${VIEW_ID}`;
      if (!url.searchParams.has(pageParam)) {
        // If the link used relative pages like » without param, do nothing special;
        // most Formidable pagers already include frm-page-{id}
      }
      window.location.assign(url.toString());
    });
  }

  hydrateControlsFromURL();
  decoratePagerLinks();
  interceptPagerClicks();
});

</script>
<script>
(function(){
  // === Config: selectors already present in your page ===
  const RESULTS_SEL = '#mentorResults article';
  const CHECKBOX_SEL = '.cmp';          // compare checkbox on each card
  const BTN_OPEN = '#compareBtn';       // your top bar "Compare (0)" button
  const COUNT_BUBBLE = '#compareCount'; // the (0) counter inside that button

  // === State ===
  const selected = new Map(); // id -> card element (max 3)
  const limit = 3;

  // === Utilities ===
  const $ = sel => document.querySelector(sel);
  const $$ = sel => Array.from(document.querySelectorAll(sel));
  const norm = s => (s||'').toString().trim();
  const num  = s => {
    const m = (s||'').toString().match(/[\d.]+/);
    return m ? parseFloat(m[0]) : 0;
  };

  // generate an ID for a card (prefer data-id; fallback to index)
  function getCardId(card, idx){
    return card.dataset.id || card.getAttribute('data-id') || (card.dataset.name ? card.dataset.name.replace(/\s+/g,'-').toLowerCase() : `card-${idx}`);
  }

  // parse mentor data from a card
  function parseCard(card, idx){
    const nameEl = card.querySelector('h3');
    const name = norm(nameEl ? nameEl.textContent : card.dataset.name || `Mentor ${idx+1}`);
    const designation = norm(card.dataset.designation || (card.textContent.match(/Professor|Scientist|Director|Engineer|Assistant Professor|Associate Professor/i)||[''])[0]);
    const degree = norm(card.dataset.degree || (card.textContent.match(/\b(Ph\.?D|M\.?Tech|B\.?Tech|MSc|BSc|MBA|MD)\b/i)||[''])[0]);
    const exp = card.dataset.experience ? Number(card.dataset.experience) : num(card.textContent.match(/(\d+)\s*yrs? exp/i));
    const rating = card.dataset.rating ? Number(card.dataset.rating) : 0; // optional
    // skills: count hashtags (#tag) or chip spans in the tags rows
    const chips = Array.from(card.querySelectorAll('.mt-3 span, .mt-2 span')).map(s => s.textContent.trim());
    const skillsCount = chips.filter(t => t.startsWith('#') || /react|python|ml|ai|nanotech|chem|bio/i.test(t)).length || chips.length;
    const photo = card.querySelector('img')?.getAttribute('src') || '';

    return { id:getCardId(card,idx), name, designation, degree, exp, rating, skillsCount, photo, card };
  }

  function updateCount(){
    const count = selected.size;
    const span = $(COUNT_BUBBLE);
    if (span) span.textContent = String(count);
    const btn = $(BTN_OPEN);
    if (btn) btn.disabled = count < 2;   // need at least 2 to open
  }

  function toggleSelect(card, checked){
    const id = getCardId(card, $$(RESULTS_SEL).indexOf?.(card) ?? 0);
    if (checked){
      if (selected.size >= limit){
        // uncheck the box and warn
        card.querySelector(CHECKBOX_SEL).checked = false;
        alert(`You can compare up to ${limit} mentors.`);
        return;
      }
      selected.set(id, card);
    } else {
      selected.delete(id);
    }
    updateCount();
  }

  // Bind compare checkboxes
  function bindCheckboxes(){
    $$(RESULTS_SEL).forEach((card, idx) => {
      // ensure each card has a stable id
      card.dataset.id = getCardId(card, idx);

      const cb = card.querySelector(CHECKBOX_SEL);
      if (!cb) return;
      cb.addEventListener('change', (e)=> toggleSelect(card, e.target.checked));
    });
  }

  // Build the compare table and open the modal
  function openModal(){
    const data = Array.from(selected.values()).map(parseCard);
    if (data.length < 2){ alert('Select at least two mentors to compare.'); return; }
    const wrap = document.getElementById('cmTableWrap');
    wrap.innerHTML = buildTableHTML(data);
    highlightBests(wrap, data);
    document.getElementById('compareModal').style.display='block';
  }

  function closeModal(){
    document.getElementById('compareModal').style.display='none';
  }

  function clearSelection(){
    selected.forEach(card => {
      const cb = card.querySelector(CHECKBOX_SEL);
      if (cb) cb.checked = false;
    });
    selected.clear(); updateCount(); closeModal();
  }

// Extract numeric years-of-experience from a mentor card
function yearsOfExp(card){
  // Prefer explicit data attributes if present
  const keys = ['experience','exp','yoe','years'];
  for (const k of keys){
    const v = card?.dataset?.[k];
    if (v !== undefined && v !== '' && !Number.isNaN(parseFloat(v))) {
      return parseFloat(v);
    }
  }
  // Fallback: parse from the card text, e.g. "3.5 yrs exp", "22 years experience"
  const text = (card.textContent || '');
  const m = text.match(/(\d+(?:\.\d+)?)\s*(?:\+)?\s*(?:yrs?|years?)\s*(?:of\s+)?(?:experience|exp)?/i);
  return m ? parseFloat(m[1]) : NaN;
}

  function buildTableHTML(rows){
    // header cards
    const heads = rows.map(m => `
      <th>
        <div style="display:flex;align-items:center;gap:10px;">
          <img src="${m.photo}" alt="" style="width:40px;height:40px;border-radius:10px;object-fit:cover;background:#e2e8f0"/>
          <div>
            <div style="font-weight:600">${m.name}</div>
            <div style="font-size:12px;color:#64748b">${m.designation || '&nbsp;'}</div>
          </div>
        </div>
      </th>`).join('');

    const cells = (getter, fmt=String) => rows.map(m => `<td>${fmt(getter(m))}</td>`).join('');

    return `
      <table>
        <thead><tr><th style="width:200px">Mentor</th>${heads}</tr></thead>
        <tbody>
          <tr><td><strong>Years of Experience</strong></td>${cells(m=>m.exp, v=> `${v||0}`)}</tr>
          <tr><td><strong>Rating/Rank</strong></td>${cells(m=>m.rating, v=> v? v.toFixed(1): '—')}</tr>
          <tr><td><strong>Designation</strong></td>${cells(m=>m.designation||'—')}</tr>
          <tr><td><strong>Degree</strong></td>${cells(m=>m.degree||'—')}</tr>
          <tr><td><strong># Skills/Tags</strong></td>${cells(m=>m.skillsCount||0, v=> `${v}`)}</tr>
        </tbody>
      </table>
    `;
  }
  function highlightBests(wrapper, rows){
    // numeric rows: Experience, Rating, Skills
    const numericRowLabels = ['Years of Experience','Rating/Rank','# Skills/Tags'];
    numericRowLabels.forEach(label=>{
      const tr = Array.from(wrapper.querySelectorAll('tbody tr')).find(r => r.firstElementChild.textContent.trim() === label);
      if (!tr) return;
      const tds = Array.from(tr.querySelectorAll('td')).slice(1);
      const values = tds.map(td => num(td.textContent));
      const max = Math.max(...values);
      tds.forEach((td,i)=>{ if(values[i] === max && max>0) td.classList.add('best'); });
    });
  }

  // Buttons + backdrop
  document.getElementById('cmClose').addEventListener('click', closeModal);
  document.getElementById('cmClear').addEventListener('click', clearSelection);
  document.querySelector('#compareModal .cm-backdrop').addEventListener('click', closeModal);
  const openBtn = $(BTN_OPEN);
  if (openBtn) openBtn.addEventListener('click', openModal);

  // Init
  bindCheckboxes();
  updateCount();
})();

(function(){
  const btn  = document.getElementById('compareBtn');
  const cnt  = document.getElementById('compareCount');
  const BOX  = '#mentorResults .cmp';   // your compare checkbox selector

  if(!btn || !cnt) return;

  // turn the existing button into a floating FAB
  btn.classList.add('compare-fab');

  function updateFAB(){
    const checked = document.querySelectorAll(BOX + ':checked').length;
    cnt.textContent = String(checked);

    // show when >=1 selected
    if (checked >= 1) {
      btn.classList.add('is-visible');
    } else {
      btn.classList.remove('is-visible');
    }

    // enable click only when >=2 selected
    btn.disabled = checked < 2;
  }

  // listen to any compare checkbox changes (event delegation for safety)
  document.addEventListener('change', function(e){
    if (e.target && e.target.matches(BOX)) updateFAB();
  });

  // Also update after client-side filtering/sorting repacks the DOM
  document.addEventListener('mentor:realign', updateFAB); // (optional custom event if you use one)
  window.addEventListener('load', updateFAB);
})();


document.addEventListener('DOMContentLoaded', function () {
  const PER_PAGE = 20;
  let currentPage = 1;

  const container         = document.getElementById('mentorResults');
  const pager             = document.getElementById('mentorPager');
  const cards             = Array.from(container.querySelectorAll('article'));

  // controls
  const keywordInput       = document.getElementById('q');
  const domainChipsWrap    = document.getElementById('domainChips');
  const institutionSelect  = document.getElementById('institution');
  const designationSelect  = document.getElementById('designation');
  const sortSelect         = document.getElementById('sort');
  const availabilitySelect = document.getElementById('availability');
  const applyBtn           = document.getElementById('applyFilters');
  const resetBtn           = document.getElementById('resetFilters');
const expMinInput       = document.getElementById('expMin');
const expMaxInput       = document.getElementById('expMax');


  // helpers
  const norm = s => (s||'').toString().trim().toLowerCase();
  const tokenize = s => norm(s).split(/[,|]/).map(x=>x.trim()).filter(Boolean);
  const textCache = new WeakMap();
  const cardText = c => {
    let t = textCache.get(c);
    if (!t){ t = (c.textContent || '').toLowerCase(); textCache.set(c,t); }
    return t;
  };

  function selectedDomains(){
    return Array.from(domainChipsWrap.querySelectorAll('input[type="checkbox"]:checked'))
      .map(chk => norm(chk.value));
  }

 function matches(card){
  const kw   = norm(keywordInput.value);
  const inst = norm(institutionSelect.value);
  const des  = norm(designationSelect.value);
  const av   = norm(availabilitySelect.value);
  const doms = selectedDomains();

  const cInst  = norm(card.dataset.institution);
  const cDes   = norm(card.dataset.designation);
  const cAvail = norm(card.dataset.availability || 'any');
  const cDom   = tokenize(card.dataset.domains);
  const hay    = cardText(card);

  if (kw && !hay.includes(kw)) return false;
  if (inst && inst !== 'all' && !cInst.includes(inst)) return false;
  if (des && des !== 'all' && cDes !== des) return false;
  if (av && av !== 'any' && cAvail !== av) return false;
  if (doms.length && !doms.some(d => cDom.includes(d))) return false;

  // NEW: experience range
  const minY = parseFloat(expMinInput.value);
  const maxY = parseFloat(expMaxInput.value);
  if (!isNaN(minY) || !isNaN(maxY)){
    const y = yearsOfExp(card);
    // If we can't determine the years when filtering is active, hide it
    if (isNaN(y)) return false;
    if (!isNaN(minY) && y < minY) return false;
    if (!isNaN(maxY) && y > maxY) return false;
  }

  return true;
}

function sortMatched(arr){
  const mode = sortSelect.value;
  if (mode === 'name'){
    arr.sort((a,b)=> (a.dataset.name||'').localeCompare(b.dataset.name||''));
  } else if (mode === 'experience'){
    arr.sort((a,b)=> (yearsOfExp(b) - yearsOfExp(a)));
  }
  // relevance = keep server order
}


  function renderPage(){
    // gather all matching cards
    const matched = cards.filter(c => c.dataset.match === '1');
    sortMatched(matched);

    // hide all first
    cards.forEach(c => c.style.display = 'none');

    // show only current page
    const total = matched.length;
    const pages = Math.max(1, Math.ceil(total / PER_PAGE));
    if (currentPage > pages) currentPage = pages;

    const start = (currentPage - 1) * PER_PAGE;
    const end   = start + PER_PAGE;

    matched.forEach((c, i) => {
      if (i >= start && i < end) c.style.display = '';
      // append in sorted order so grid is correct
      container.appendChild(c);
    });

    buildPager(total, pages);
  }

  function buildPager(total, pages){
    // simple condensed pager: prev, 1 … current-1, current, current+1 … last, next
    const btn = (label, page, opts={}) => {
      const b = document.createElement('button');
      b.textContent = label;
      b.className = 'pg-btn';
      if (opts.current) b.setAttribute('aria-current','page');
      if (opts.disabled) b.disabled = true;
      b.addEventListener('click', () => { if (!b.disabled){ currentPage = page; renderPage(); }});
      return b;
    };

    pager.innerHTML = '';

    // Prev
    pager.appendChild(btn('«', Math.max(1, currentPage-1), {disabled: currentPage===1}));

    // numbers (condensed)
    const show = new Set([1, pages, currentPage-1, currentPage, currentPage+1, 2, pages-1].filter(p => p>=1 && p<=pages));
    let last=0;
    for(let p=1; p<=pages; p++){
      if (!show.has(p)) continue;
      if (last && p-last>1){ const ell = document.createElement('span'); ell.textContent='…'; ell.style.padding='6px 6px'; pager.appendChild(ell); }
      pager.appendChild(btn(String(p), p, {current: p===currentPage}));
      last=p;
    }

    // Next
    pager.appendChild(btn('»', Math.min(pages, currentPage+1), {disabled: currentPage===pages}));
  }

  function applyFilters(resetPage=true){
    // compute matches, don't show yet
    cards.forEach(c => c.dataset.match = matches(c) ? '1' : '0');
    if (resetPage) currentPage = 1;
    renderPage();
  }
   
  // events
  applyBtn?.addEventListener('click', ()=>applyFilters(true));
  resetBtn?.addEventListener('click', ()=>{
    keywordInput.value=''; sortSelect.selectedIndex=0;
    institutionSelect.selectedIndex=0; designationSelect.selectedIndex=0; availabilitySelect.selectedIndex=0;
    domainChipsWrap.querySelectorAll('input[type="checkbox"]').forEach(c=>c.checked=false);
    cards.forEach(c => c.dataset.match='1');
    currentPage=1; renderPage();
    applyFilters(true);   
  });
  keywordInput?.addEventListener('input', ()=>applyFilters(true));
  domainChipsWrap?.addEventListener('change', e => { if(e.target.matches('input[type="checkbox"]')) applyFilters(true); });
  sortSelect?.addEventListener('change', ()=> renderPage());

   resetBtn?.addEventListener('click', () => {
  keywordInput.value = '';
  institutionSelect.value = 'all';
  designationSelect.value = 'all';
  availabilitySelect.value = 'any';
  sortSelect.value = 'relevance';
  expMinInput.value = '';
  expMaxInput.value = '';
  applyFilters(true);  
  // re-run filtering/pagination function here
});

  // initial
  cards.forEach(c => c.dataset.match='1');   // default: everything matches
  renderPage();
});

// After you build the pager, tag your ellipsis spans:
document.querySelectorAll('#mentorPager span').forEach(s => s.classList.add('pg-ellipsis'));

(function () {
  const html = document.documentElement;
  const btn  = document.getElementById('toggleDark');
  if (!btn) return;

  // ---- init from saved preference (default = light) ----
  const saved = localStorage.getItem('theme'); // 'dark' | 'light' | null
  if (saved === 'dark') html.classList.add('dark');
  else html.classList.remove('dark');

  btn.setAttribute('aria-pressed', String(html.classList.contains('dark')));
  updateBtnColors();

  // ---- toggle on click ----
  btn.addEventListener('click', () => {
    const isDark = html.classList.toggle('dark');
    btn.setAttribute('aria-pressed', String(isDark));
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateBtnColors();
  });

  function updateBtnColors(){
    // Force readable text color on the button itself
    if (html.classList.contains('dark')) {
      btn.classList.add('text-white');
      btn.classList.remove('text-slate-900');
    } else {
      btn.classList.remove('text-white');
      btn.classList.add('text-slate-900');
    }
  }
})();
</script>
    </body>
</html>