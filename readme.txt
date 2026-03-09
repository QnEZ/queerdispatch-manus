=== QueerDispatch ===
Contributors: queerdispatch
Tags: news, magazine, lgbtq, accessibility, custom-colors, custom-logo, featured-images, full-width-template, sticky-post, theme-options, two-columns, right-sidebar, block-editor-styles, wide-blocks
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.0
Stable tag: 1.3.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Independent LGBTQIA2S+ news and community WordPress theme with 7 switchable aesthetic styles, a built-in tip submission system, and WCAG 2.1 AA compliant contrast.

== Built with Manus AI ==

This theme was designed, coded, debugged, and documented entirely through an AI-assisted development workflow using **Manus AI** (https://manus.im), an autonomous general AI agent. Every style, feature, and accessibility fix from v1.0.0 through v1.3.2 was produced in collaboration with Manus. This is believed to be one of the first publicly released WordPress themes built end-to-end with an autonomous AI agent.

== Description ==

QueerDispatch is a WordPress theme built for independent LGBTQIA2S+ news publications and community sites. It ships with seven distinct aesthetic styles that visitors can switch between at any time using a floating panel on the right side of every page. The selected style is saved in a browser cookie for one year, so returning visitors always see their preferred aesthetic.

**The seven styles are:**

* **Anarchist** — Black and red DIY punk zine with distressed textures and bold typography
* **Goth** — Dark academia with ornate Victorian details, gothic serif fonts, and deep crimson accents
* **Witchy** — Mystical and tarot-inspired with moon phase motifs, deep purple backgrounds, and gold accents
* **Pastel Rainbow Goth** — A kawaii-goth hybrid combining soft pastels with a dark background
* **Cyberpunk Queer** — Neon cyan and magenta on near-black with a scanline overlay and glitch effects
* **Cottagecore Queer** — The only light-mode style: warm cream backgrounds, sage green accents, and floral motifs
* **Riot Grrrl** — Hot pink and black feminist punk energy with zine-style typography

All seven styles meet WCAG 2.1 Level AA contrast requirements (minimum 4.5:1 ratio) for body text, links, and muted text. Contrast ratios were systematically audited and corrected across v1.2.2 through v1.2.5.

**Tip submission system**

The theme includes a complete editorial tip submission system requiring no additional plugins. A dedicated page template provides a form with category selection, tip body, optional file attachment, and optional contact details. Submissions are stored as private posts in the WordPress database and trigger an email notification to the site admin. A Signal contact section below the form provides an encrypted alternative channel for sources who need stronger anonymity. Submitted tips are managed from the WordPress admin with colour-coded status badges, filter controls, and an admin bar badge showing unread tip count.

**Other features**

A configurable breaking news ticker bar, responsive magazine-style layout with hero section and article card grid, three navigation menu locations, three widget areas, custom logo support, Block Editor support with wide and full alignment, keyboard-accessible style switcher with ARIA labels, skip-to-content link, and a built-in GitHub Releases auto-updater.

== Installation ==

= Via WordPress Admin (recommended) =

1. Download `queerdispatch-theme.zip` from https://github.com/QnEZ/queerdispatch-manus/releases/latest
2. Go to **Appearance → Themes → Add New → Upload Theme**
3. Upload the ZIP file and click **Activate**
4. Go to **Appearance → Menus** to assign your navigation menus
5. Go to **Appearance → Customize** to set the default style and configure the breaking news ticker

= Via Git =

Clone the repository directly into your WordPress themes directory:

    git clone https://github.com/QnEZ/queerdispatch-manus.git wp-content/themes/queerdispatch-theme

= Setting up the tip submission page =

1. Go to **Pages → Add New** and set the title to `Submit a Tip` with the slug `submit-a-tip`
2. Under **Page Attributes**, set the Template to **Submit a Tip**
3. Publish the page — the Submit a Tip button on the homepage hero will link to it automatically

= Requirements =

* WordPress 6.0 or higher
* PHP 8.0 or higher
* No additional plugins required

== Frequently Asked Questions ==

= Can visitors choose their own style? =

Yes. A floating "Style" tab appears on the right side of every page. Visitors open it to choose their preferred aesthetic, and the selection is saved in a browser cookie for one year.

= Can I set a default style for new visitors? =

Yes. Go to **Appearance → Customize → Theme Style** and choose the default. Visitors who have already made a choice will see their own preference instead.

= Is the style switcher accessible? =

Yes. The style switcher panel is fully keyboard operable. Tab to the toggle button, press Enter to open the panel, use arrow keys to move between styles, press Enter to select, and press Escape to close. All interactive elements have ARIA labels.

= Does it work with the Block Editor (Gutenberg)? =

Yes. The theme supports wide and full alignment for blocks. Standard block styles are respected across all seven aesthetic modes.

= How do I add a breaking news ticker? =

Go to **Appearance → Customize → Breaking News Bar**. Enable the ticker and enter your items separated by ` | ` (space, pipe, space).

= How do I manage submitted tips? =

Go to **Tips** in the WordPress admin sidebar. Tips are listed with colour-coded status badges (New, Reviewing, Actioned, Dismissed). Click any tip to view the full submission, update the status, and see the attached file if one was included. The admin bar shows a badge with the count of unread (New) tips.

= Can I create my own style? =

Yes — each style is a single CSS file that overrides a set of CSS custom properties. Create a new file in `css/themes/`, add it to `queerdispatch_get_styles()` in `functions.php`, and it will appear in the switcher automatically.

= Will I be notified of updates? =

Yes. The theme includes a built-in auto-updater that checks the GitHub releases feed every 12 hours. When a new version is available, WordPress will show the standard update notification in **Appearance → Themes**.

= Was this theme really built with AI? =

Yes. The entire theme — design, code, debugging, accessibility auditing, and documentation — was produced through an AI-assisted workflow using Manus AI (https://manus.im), an autonomous general AI agent. The human contributor directed the work, reviewed outputs, and made editorial decisions; Manus wrote and iterated on all the code and documentation.

== Screenshots ==

1. The Anarchist style — homepage with hero section and card grid.
2. The Goth style — single article page with sidebar.
3. The Cyberpunk Queer style — homepage with neon scanline overlay.
4. The Cottagecore Queer style — the only light-mode aesthetic.
5. The style switcher panel open, showing all seven options.
6. The tip submission page with Signal contact section.

== Changelog ==

= 1.3.2 — 2026-03-08 =
* Fixed auto-updater: the upgrader_source_selection filter now uses three detection layers to identify the theme regardless of how the update was triggered, resolving the "theme is already up to date" false positive when clicking Update Now.
* Fixed stale temp directory cleanup during updates.

= 1.3.1 — 2026-03-08 =
* Added Signal contact option to the tip submission page with phone number, username deep link, step-by-step instructions for anonymous contact, and explanation of when Signal is preferable to the web form.

= 1.3.0 — 2026-03-08 =
* Added complete tip submission system: page template with category dropdown, file upload, optional contact fields, Signal contact section, and privacy note.
* Added qd_tip custom post type with private storage, email notification, and admin triage interface (colour-coded status badges, filter dropdown, bulk actions, admin bar badge).

= 1.2.6 — 2026-03-07 =
* Fixed auto-updater ZIP structure (was accumulating loose files at root; now always a single queerdispatch-theme/ top-level directory).
* Added upgrader_source_selection filter to rename extracted folder to theme slug.
* Registered Share Tech font in wp_enqueue_style.

= 1.2.5 — 2026-03-07 =
* Full readability audit of all seven styles.
* Goth: article link colour changed from dark crimson (2.1:1 — WCAG fail) to bright rose (7.2:1). Muted text improved from 3.1:1 to 5.2:1. Two malformed CSS selectors fixed.
* Witchy: explicit article link rule added (bright gold, 9.1:1). Muted text improved from 3.5:1 to 5.8:1. Malformed nav hover selector fixed.
* Riot Grrrl: article links brightened (5.6:1). Muted text improved from 3.8:1 to 6.1:1. Two malformed selectors fixed.
* Pastel Rainbow Goth: explicit article link rule added (6.2:1). Muted text improved from 3.6:1 to 6.8:1. Malformed nav hover selector fixed.
* All styles: added line-height: 1.85 to single-post-content.

= 1.2.4 — 2026-03-07 =
* Cottagecore: link colour changed from sage green (2.8:1 — WCAG fail) to terracotta (5.8:1). Two malformed CSS selectors fixed.

= 1.2.3 — 2026-03-07 =
* Cyberpunk: article body font changed from Courier Prime to Share Tech. Text brightened, scanline opacity reduced from 15% to 8%. Additional malformed selectors fixed in Cyberpunk, Cottagecore, and Pastel Rainbow Goth.

= 1.2.2 — 2026-03-06 =
* Fixed malformed CSS comma-separated selectors in all seven theme files causing scrollbar and pseudo-element styles to bleed onto the entire body and break style switching.

= 1.2.1 =
* Initial public release with seven styles and built-in auto-updater.

== Upgrade Notice ==

= 1.3.2 =
Install this version manually (Appearance > Themes > Add New > Upload Theme > Replace current with uploaded). This fixes the auto-updater so all future updates will install correctly via Update Now.

== Credits ==

Built for QueerDispatch.org — independent LGBTQIA2S+ news and community reporting.

This theme was built entirely with Manus AI (https://manus.im) — an autonomous general AI agent that handled design, code, debugging, accessibility auditing, and documentation across all releases.

Google Fonts are loaded via the Google Fonts API. See https://developers.google.com/fonts/faq/privacy for Google's privacy policy regarding font loading.

== License ==

QueerDispatch WordPress Theme, Copyright 2024-2026 QueerDispatch Team.
QueerDispatch is distributed under the terms of the GNU General Public License v2 or later.
Full license text: https://www.gnu.org/licenses/gpl-2.0.html
