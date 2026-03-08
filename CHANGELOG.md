# Changelog

All notable changes to the QueerDispatch WordPress theme are documented here.

This project follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) conventions.
Versions are numbered `MAJOR.MINOR.PATCH` and releases are listed in reverse chronological order.

---

## [1.3.2] — 2026-03-08

### Fixed

- **Auto-updater: robust source directory rename** — The `upgrader_source_selection` filter that renames the extracted ZIP folder to the correct theme slug was bailing out early when `hook_extra['theme']` was not set. This key is only reliably present when the update is triggered from the standard Themes page; updates triggered from the custom admin notice link did not set it, causing WordPress to install a new theme directory instead of overwriting the existing one. The filter now uses three detection layers in order of reliability:
  1. `hook_extra['theme'] === 'queerdispatch-theme'` (explicit match)
  2. `hook_extra['type'] === 'theme'` + source path inside the temp directory (WordPress 5.5+)
  3. Read `style.css` from the extracted folder and check for `Text Domain: queerdispatch` (universal fallback)
- **Stale temp directory cleanup** — A stale `queerdispatch-theme/` directory left behind by a previous failed update attempt is now removed before the rename, preventing silent move failures.

### Notes

Install v1.3.2 manually this one time (download the ZIP and upload via **Appearance → Themes → Add New → Upload Theme → Replace current with uploaded**). All future updates from v1.3.2 onwards will install correctly via **Update Now**.

---

## [1.3.1] — 2026-03-08

### Added

- **Signal contact option on the Submit a Tip page** — A new section below the submission form provides two ways to contact the editorial team via Signal for sources who need stronger anonymity:
  - Phone number: `+1 (609) 334-3796` (deep link to `signal.me/#p/...`)
  - Username: `KPP.78` (deep link to `signal.me/#u/...`)
  - Brief explanation of when Signal is preferable to the web form (whistleblowers, sources at risk of retaliation, end-to-end encryption)
  - Step-by-step instructions for contacting via username without revealing a phone number
  - Styled with Signal brand blue; adapts to all 7 theme styles via CSS variables

---

## [1.3.0] — 2026-03-08

### Added

- **Tip submission system** — A complete plugin-free editorial tip submission system:

  **Front end (`page-submit-tip.php` — Template Name: Submit a Tip)**
  - Category dropdown (11 options: General, Politics & Policy, Community Events, Health & Wellness, Arts & Culture, Business & Economy, Education, Housing & Homelessness, Violence & Safety, International, Other)
  - Tip body textarea (required)
  - Optional file attachment (JPG, PNG, GIF, PDF, DOC, DOCX, TXT, MP4, MOV — 10 MB maximum)
  - Optional name/alias and contact email fields
  - Success and error states with clear messaging
  - Privacy note explaining data handling and HTTPS
  - Fully themed via CSS variables — adapts to all 7 styles
  - Form posts to `admin-post.php` with the `nopriv` hook, supporting fully anonymous submission

  **Back end (`functions.php`)**
  - `qd_tip` custom post type: private, not publicly queryable, only creatable via the form handler
  - 7 registered meta fields: `contact_name`, `contact_email`, `category`, `attachment_path`, `attachment_name`, `ip`, `status`
  - Form handler: validates nonce, sanitises all inputs, handles file upload to `wp-uploads/queerdispatch-tips/` (`.htaccess`-protected against direct browser access), creates private `qd_tip` post, sends email notification to site admin
  - Custom admin list columns: Category, Contact, Status (colour-coded badge), Attachment, Submitted date
  - Tip Details meta box on the edit screen with all submission fields and a status dropdown (New / Reviewing / Actioned / Dismissed)
  - `save_post_qd_tip` hook saves status changes from the meta box

  **Admin enhancements (`inc/tip-admin-columns.php`)**
  - Admin bar badge showing the count of unread (status = New) tips
  - Filter-by-status dropdown above the tips list table
  - Bulk actions: Mark as Reviewing / Actioned / Dismissed
  - Sortable Status and Category columns
  - `pre_get_posts` fix to show private `qd_tip` posts in the list table by default

### Setup required

1. Go to **Pages → Add New**
2. Set the title to **Submit a Tip** and the slug to **submit-a-tip**
3. Under **Page Attributes**, set the Template to **Submit a Tip**
4. Publish the page

The Submit a Tip button on the homepage hero links to `/submit-a-tip/` automatically.

---

## [1.2.6] — 2026-03-07

### Fixed

- **Auto-updater: correct ZIP structure** — The release ZIP previously contained both a `queerdispatch-theme/` subdirectory and loose files at the root (because the ZIP was being updated in-place, accumulating entries). WordPress's theme upgrader requires exactly one top-level directory; the mixed structure caused it to install a new directory instead of overwriting the existing theme. The ZIP is now rebuilt cleanly from a staging directory with a single `queerdispatch-theme/` entry at the root.
- **Auto-updater: `upgrader_source_selection` filter added** — A `fix_source_dir` method now renames the extracted ZIP folder to match the theme slug before WordPress moves it into place, ensuring future updates install correctly even if the ZIP's internal folder name drifts from the theme slug.
- **Share Tech font registered** — Added `Share Tech` to the Google Fonts `wp_enqueue_style` call in `functions.php`. The font was referenced in the Cyberpunk CSS since v1.2.3 but not enqueued via WordPress, causing fallback to Courier New on sites that block external font loading.

---

## [1.2.5] — 2026-03-07

### Fixed — Full readability audit across all 7 styles

**Goth**
- Article links: dark crimson `#8b1a4a` (contrast ratio 2.1:1 — WCAG FAIL) replaced with bright rose `#e05080` (7.2:1 — WCAG AA pass). The previous colour was essentially invisible against the near-black `#080810` background.
- Muted text improved from `#7a6e8a` (3.1:1) to `#a898c0` (5.2:1)
- Malformed nav hover selector fixed (was applying dark background to entire `body`)
- Malformed `card-meta .separator` selector fixed

**Witchy**
- Explicit `single-post-content a` link rule added: gold `#f0c840` (9.1:1 against near-black)
- Muted text improved from `#8a7a5a` (3.5:1) to `#b0a070` (5.8:1)
- Malformed nav hover selector fixed

**Riot Grrrl**
- Article links brightened to `#ff4da6` for improved body-text legibility
- Muted text improved from `#a08090` (3.8:1) to `#c8a0b8` (6.1:1)
- Two malformed selectors fixed (nav hover, pagination hover)

**Pastel Rainbow Goth**
- Explicit `single-post-content a` link rule added: `#ff9de2` (6.2:1)
- Muted text improved from `#9a88b0` (3.6:1) to `#c0b0d8` (6.8:1)
- Malformed nav hover selector fixed

**Anarchist / Cyberpunk / Cottagecore**
- No changes — already clean from previous releases

All styles now have `line-height: 1.85` on article body text. All 7 malformed selector patterns eliminated.

---

## [1.2.4] — 2026-03-07

### Fixed — Cottage Queer style contrast and link visibility

- **Link colour** changed from sage green `#5a7a3a` to terracotta `#8b4513`. The previous colour had a contrast ratio of ~2.8:1 against the cream background `#f5f0e8` — well below WCAG AA's 4.5:1 minimum. Terracotta achieves ~5.8:1. Applies to all inline links, read-more text, footer widget titles, and card title hovers.
- Sage green is retained where it works well: buttons, widget title backgrounds, and borders.
- Two malformed selectors fixed: nav hover and pagination hover were applying the green background to the entire `body` element.

---

## [1.2.3] — 2026-03-07

### Fixed — Cyberpunk style article readability

- **Body font** on article pages switched from Courier Prime (full monospace) to Share Tech (clean, slightly techy sans-serif). Headings and UI elements retain Orbitron.
- **Text colour** brightened from `#e0f0ff` to `#e8f4ff`; muted text improved from `#6080a0` to `#8aabcc`
- **Scanline overlay opacity** reduced from 15% to 8% — visible as a subtle texture but no longer darkening body text
- **Font size** increased from 1.05rem to 1.1rem on article pages; **line height** increased from 1.8 to 1.9
- Section headings use bright cyan `#00e5ff` with neon glow and underline divider
- Links in article body use magenta `#ff60ff` with underline offset
- Blockquote contrast improved; list markers replaced with custom ▸ chevron in magenta
- Three additional malformed selectors fixed: cyberpunk nav hover gradient (was applying to entire `body`), cyberpunk input focus, cottagecore input types (3 instances), pastel-rainbow-goth input types and pagination hover

---

## [1.2.2] — 2026-03-06

### Fixed — Critical CSS selector bugs (scrollbar and `::before` pseudo-elements)

- **Scrollbar pseudo-element selectors** — Selectors of the form `body[data-style="X"], body.style-X ::-webkit-scrollbar-track` were parsed as two separate rules. The first half (`body[data-style="X"]`) became a standalone selector and applied the scrollbar background colour to the entire `body` element, causing style bleeding when switching styles. Fixed across all 7 style files.
- **`::before` pseudo-element selectors** — Same malformed comma-separated pattern fixed for all decorative `::before` elements across all 7 style files.
- **Style variable blocks** — All 7 CSS variable blocks inlined directly in `style.css` to guarantee correct load order and eliminate flash of unstyled content (FOUC).

### Affected files
`css/themes/goth.css`, `css/themes/witchy.css`, `css/themes/pastel-rainbow-goth.css`, `css/themes/cyberpunk.css`, `css/themes/cottagecore.css`, `css/themes/riot-grrrl.css`, `style.css`

---

## [1.2.1] — 2026-03-06

### Fixed — Critical: all 204 malformed selectors corrected

- An automated `sed` command from a previous fix generated selectors like `body[data-style="anarchist"], body.style-anarchist .section-title { font-family: Anton; }`. The comma made the first half a standalone selector, applying `font-family: Anton` to the entire `body` on every page load regardless of active style. This caused wavy underlines on all text, wrong fonts, broken style switching, and colour bleeding across all styles.
- A Python script corrected all 204 malformed selectors across all 7 CSS files to the correct double-scoped form: `body[data-style="X"] .selector, body.style-X .selector`.
- **JS style switcher** fixed to use `classList.add/remove` instead of replacing the entire `className` string, which was stripping WordPress body classes and breaking layout on style switch.
- Removed wavy `text-decoration` from Anarchist body-level rules.

---

## [1.0.0] — Initial release

### Added

- Seven switchable aesthetic styles: **Anarchist** (black/red punk zine), **Goth** (dark crimson and black), **Witchy** (deep purple and gold), **Pastel Rainbow Goth** (soft pastels on dark), **Cyberpunk Queer** (neon cyan/magenta on black), **Cottagecore Queer** (warm cream and sage green), **Riot Grrrl** (black/white/hot pink)
- Style switcher in the site header — persists via cookie across sessions, keyboard accessible
- Breaking news ticker bar (configurable via Customizer)
- Homepage hero with configurable headline, subheadline, and CTA button
- Standard WordPress template hierarchy: index, single, archive, page, search, 404
- Custom post types: Events (`qd_event`)
- Sidebar with newsletter signup widget area, Most Read posts, and category list
- GitHub Releases auto-updater — checks for new versions every 12 hours and shows standard WordPress update notice
- Admin version page under **Appearance → 🏳️‍🌈 Theme Updates** with release history and one-click update
- WCAG 2.1 AA compliant contrast ratios across all 7 styles (verified in v1.2.2–v1.2.5)
- Responsive design, keyboard navigation, skip-to-content link

---

[1.3.2]: https://github.com/QnEZ/queerdispatch-manus/releases/tag/v1.3.2
[1.3.1]: https://github.com/QnEZ/queerdispatch-manus/releases/tag/v1.3.1
[1.3.0]: https://github.com/QnEZ/queerdispatch-manus/releases/tag/v1.3.0
[1.2.6]: https://github.com/QnEZ/queerdispatch-manus/releases/tag/v1.2.6
[1.2.5]: https://github.com/QnEZ/queerdispatch-manus/releases/tag/v1.2.5
[1.2.4]: https://github.com/QnEZ/queerdispatch-manus/releases/tag/v1.2.4
[1.2.3]: https://github.com/QnEZ/queerdispatch-manus/releases/tag/v1.2.3
[1.2.2]: https://github.com/QnEZ/queerdispatch-manus/releases/tag/v1.2.2
[1.2.1]: https://github.com/QnEZ/queerdispatch-manus/releases/tag/v1.2.1
[1.0.0]: https://github.com/QnEZ/queerdispatch-manus/releases/tag/v1.0.0
