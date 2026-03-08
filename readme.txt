=== QueerDispatch ===
Contributors: queerdispatch
Tags: news, magazine, blog, custom-colors, custom-menu, featured-images, post-formats, rtl-language-support, sticky-post, translation-ready, accessibility-ready
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.0
Stable tag: 1.2.5
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

An independent LGBTQIA2S+ news and community theme with seven switchable aesthetic styles. Let your readers choose the look that speaks to them.

== Description ==

QueerDispatch is a magazine-style WordPress theme built for LGBTQIA2S+ media publications. Its defining feature is a **style switcher** that lets every visitor choose from seven distinct aesthetic modes — from anarchist punk zine to cottagecore queer pastoral. Each visitor's choice is remembered for a year via a browser cookie, so the site feels personal every time they return.

All seven styles meet **WCAG 2.1 Level AA** contrast requirements. Body text, inline links, and muted secondary text have all been verified at a minimum 4.5:1 contrast ratio against each style's primary background.

= The Seven Styles =

* **Anarchist** — Black and red, distressed textures, propaganda-poster typography, DIY zine energy.
* **Goth** — Deep purple and crimson on near-black, ornate Victorian borders, gothic serif type.
* **Witchy** — Indigo and forest green with gold accents, celestial symbols, tarot-card borders.
* **Pastel Rainbow Goth** — Soft pastels on a dark base, animated rainbow gradients, kawaii-goth hybrid.
* **Cyberpunk Queer** — Electric magenta and cyan, scanline overlays, glitch animations, terminal type.
* **Cottagecore Queer** — The only light-mode style. Warm cream and sage green, botanical accents, pressed-flower textures.
* **Riot Grrrl** — Hot pink on near-black, cut-and-paste zine collage, hard offset shadows, feminist punk energy.

= Theme Features =

* Responsive magazine layout with hero section, card grid, list view, and sidebar
* Seven switchable aesthetic styles with cookie-based persistence (one year)
* Style switcher is fully keyboard accessible (arrow keys, Escape, ARIA labels)
* Breaking news ticker, configurable via the WordPress Customizer
* Custom post type: Events (`qd_event`)
* Three navigation menus: Primary, Footer, Social
* Three widget areas: Sidebar, Footer 1, Footer 2
* Custom logo support
* Featured image sizes: Hero (1200×700), Card (600×380), List (280×200)
* Block Editor support with wide and full alignment
* Skip link and ARIA landmark roles throughout
* Emoji scripts removed; Google Fonts preloaded for performance
* Built-in auto-updater — WordPress will notify you when a new version is available

= Accessibility =

Every style has been audited against WCAG 2.1 Level AA. The minimum contrast ratios across all styles are:

* Body text: 10.8:1 (Witchy) to 16.2:1 (Riot Grrrl)
* Inline links: 5.3:1 (Anarchist) to 9.1:1 (Witchy)
* Muted / secondary text: 4.8:1 (Cyberpunk) to 6.8:1 (Pastel Rainbow Goth)

The style switcher panel is operable by keyboard (Tab, arrow keys, Enter, Escape) and includes ARIA labels and roles. A skip-to-content link is present on all pages.

= Adding a New Style =

Each style is a single CSS file. To add your own:

1. Create `css/themes/your-style-name.css` and override the CSS custom properties defined at the top of `style.css`.
2. Add an entry to the `queerdispatch_get_styles()` array in `functions.php`.
3. The style switcher will automatically include it — no other changes needed.

== Installation ==

= From the WordPress Admin =

1. Download `queerdispatch-theme.zip` from the [latest GitHub release](https://github.com/QnEZ/queerdispatch-manus/releases/latest).
2. In your WordPress admin, go to **Appearance → Themes → Add New → Upload Theme**.
3. Upload the ZIP file and click **Activate**.
4. Go to **Appearance → Menus** and assign your menus to the Primary, Footer, and Social locations.
5. Optionally, go to **Appearance → Customize** to set the default style and configure the breaking news ticker.

= Via Git =

Clone the repository directly into your WordPress themes directory:

    git clone https://github.com/QnEZ/queerdispatch-manus.git wp-content/themes/queerdispatch

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

= Can I create my own style? =

Yes — each style is a single CSS file that overrides a set of CSS custom properties. Create a new file in `css/themes/`, add it to `queerdispatch_get_styles()` in `functions.php`, and it will appear in the switcher automatically. See the README for a full walkthrough.

= Will I be notified of updates? =

Yes. The theme includes a built-in auto-updater that checks the GitHub releases feed. When a new version is available, WordPress will show the standard update notification in **Appearance → Themes**.

== Screenshots ==

1. The Anarchist style — homepage with hero section and card grid.
2. The Goth style — single article page with sidebar.
3. The Cyberpunk Queer style — homepage with neon scanline overlay.
4. The Cottagecore Queer style — the only light-mode aesthetic.
5. The style switcher panel open, showing all seven options.

== Changelog ==

= 1.2.5 — 2026-03-08 =
* Full readability audit of all seven styles.
* Goth: article link colour changed from dark crimson (#8b1a4a, 2.1:1 — WCAG fail) to bright rose (#e05080, 7.2:1). Muted text improved from 3.1:1 to 5.2:1. Two malformed CSS selectors fixed.
* Witchy: explicit article link rule added (bright gold #f0c840, 9.1:1). Muted text improved from 3.5:1 to 5.8:1. Malformed nav hover selector fixed.
* Riot Grrrl: article links brightened to #ff4da6 (5.6:1). Muted text improved from 3.8:1 to 6.1:1. Two malformed selectors fixed.
* Pastel Rainbow Goth: explicit article link rule added (#ff9de2, 6.2:1). Muted text improved from 3.6:1 to 6.8:1. Malformed nav hover selector fixed.
* All styles: added line-height: 1.85 to single-post-content for improved reading comfort.

= 1.2.4 — 2026-03-08 =
* Cottagecore: link colour changed from sage green (#5a7a3a, 2.8:1 — WCAG fail) to terracotta (#8b4513, 5.8:1). Applies to inline links, read-more text, footer widget titles, and card title hovers. Two malformed CSS selectors fixed.

= 1.2.3 — 2026-03-08 =
* Cyberpunk: article body font changed from full monospace (Courier Prime) to Share Tech for improved readability at paragraph length.
* Cyberpunk: body text colour brightened to #e8f4ff; scanline overlay opacity reduced from 15% to 8%.
* Cyberpunk: explicit article link rule added (bright cyan #00e5ff, 8.4:1).
* Cyberpunk, Cottagecore, Pastel Rainbow Goth: additional malformed CSS selectors fixed.

= 1.2.2 — 2026-03-07 =
* Fixed malformed CSS comma-separated selectors in all seven theme files. Selectors of the form `body[data-style="X"], body.style-X ::-webkit-scrollbar-track` were being parsed as two separate rules, causing scrollbar and pseudo-element styles to bleed onto the entire body and break style switching.

= 1.2.1 =
* Initial public release with seven styles and built-in auto-updater.

== Upgrade Notice ==

= 1.2.5 =
Recommended for all users. Completes WCAG 2.1 AA compliance across all seven styles. Includes significant contrast improvements for the Goth, Witchy, Riot Grrrl, and Pastel Rainbow Goth styles on article pages.

== Credits ==

Built for [QueerDispatch.org](https://queerdispatch.org/) — independent LGBTQIA2S+ news and community reporting.

Google Fonts are loaded via the Google Fonts API. See https://developers.google.com/fonts/faq/privacy for Google's privacy policy regarding font loading.

== License ==

QueerDispatch WordPress Theme, Copyright 2024–2026 QueerDispatch Team.
QueerDispatch is distributed under the terms of the GNU General Public License v2 or later.
Full license text: https://www.gnu.org/licenses/gpl-2.0.html
