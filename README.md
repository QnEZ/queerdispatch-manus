# QueerDispatch WordPress Theme

**Version:** 1.3.2 | **License:** GPL v2 or later | **Requires:** WordPress 6.0+, PHP 8.0+

> **Built with [Manus AI](https://manus.im)** — This theme was designed, coded, debugged, and documented entirely through an AI-assisted development workflow using Manus, an autonomous general AI agent. Every style, feature, and fix from v1.0.0 through v1.3.2 was produced in collaboration with Manus.

Independent LGBTQIA2S+ news and community theme with **7 switchable aesthetic styles**, a built-in tip submission system, and WCAG 2.1 AA compliant contrast across all styles.

---

## Installation

1. Download `queerdispatch-theme.zip` from the [latest release](https://github.com/QnEZ/queerdispatch-manus/releases/latest).
2. In your WordPress admin, go to **Appearance → Themes → Add New → Upload Theme**.
3. Upload the ZIP and click **Activate**.
4. Go to **Appearance → Menus** to assign your navigation menus, then **Appearance → Customize** to set the default style and configure the breaking news ticker.

Alternatively, clone the repository directly into your themes directory:

```bash
git clone https://github.com/QnEZ/queerdispatch-manus.git wp-content/themes/queerdispatch-theme
```

The theme includes a built-in auto-updater. When a new version is available, WordPress will show the standard update notification in **Appearance → Themes** and allow one-click installation.

---

## Styles

A floating **"Style"** tab appears on the right side of every page. Visitors open it to choose their preferred aesthetic; the selection is saved in a browser cookie for one year. The default style for new visitors can be set under **Appearance → Customize → Theme Style**.

| Style | Aesthetic | Background | Accent |
|---|---|---|---|
| **Anarchist** | Black & red DIY punk zine, distressed textures | `#0d0d0d` | `#cc0000` |
| **Goth** | Dark academia, ornate Victorian, gothic serif | `#080810` | `#e05080` |
| **Witchy** | Mystical, tarot card, moon phases, gold accents | `#0e0a1a` | `#f0c840` |
| **Pastel Rainbow Goth** | Kawaii-goth hybrid, soft pastels on dark | `#0d0a14` | `#ff9de2` |
| **Cyberpunk Queer** | Neon cyan/magenta, glitch effects, scanline overlay | `#050510` | `#00e5ff` |
| **Cottagecore Queer** | Sage greens, warm cream, floral, cozy nature | `#f5f0e8` | `#8b4513` |
| **Riot Grrrl** | Hot pink & black, feminist punk, zine energy | `#0d0d0d` | `#ff4da6` |

All seven styles meet **WCAG 2.1 Level AA** contrast requirements (>=4.5:1) for body text, links, and muted text. Contrast ratios were audited and corrected in v1.2.2–v1.2.5.

---

## Features

**Editorial — Tip Submission System**

The theme includes a complete tip submission system requiring no additional plugins. A Submit a Tip page template (`page-submit-tip.php`) provides a form with category selection, tip body, optional file attachment (JPG/PNG/GIF/PDF/DOC/DOCX/TXT/MP4/MOV, 10 MB maximum), and optional contact details. Submissions are stored as private `qd_tip` posts in the WordPress database and trigger an email notification to the site admin. A Signal contact section below the form provides an encrypted alternative channel for sources who need stronger anonymity.

To activate the tip submission page:
1. Go to **Pages → Add New**, set the title to `Submit a Tip` and the slug to `submit-a-tip`.
2. Under **Page Attributes**, set the Template to **Submit a Tip**.
3. Publish. The Submit a Tip button on the homepage hero links to `/submit-a-tip/` automatically.

Submitted tips are managed under **Tips** in the WordPress admin, with colour-coded status badges (New / Reviewing / Actioned / Dismissed), filter and bulk-action controls, and an admin bar badge showing the count of unread tips.

**Layout and navigation**

The theme provides a responsive magazine-style layout with a hero section, article card grid, and list view. Three navigation menus are supported (Primary, Footer, Social), along with three widget areas (Sidebar, Footer 1, Footer 2), custom logo support, and featured image sizes optimised for hero (1200×700), card (600×380), and list (280×200) contexts.

**Customizer options**

Navigate to **Appearance → Customize** to configure the default style for new visitors and the breaking news ticker bar (enable/disable, set ticker text with items separated by ` | `).

**Performance and accessibility**

Emoji scripts are removed, Google Fonts are preloaded, and all seven styles use CSS custom properties for zero-JavaScript style switching. The style switcher panel is fully keyboard operable (Tab to open, arrow keys to navigate, Enter to select, Escape to close) with ARIA labels on all interactive elements. A skip-to-content link is included for screen reader users.

---

## File Structure

```
queerdispatch-theme/
├── style.css                        # Theme header + base styles + CSS variable blocks
├── functions.php                    # Theme setup, enqueues, post types, tip form handler
├── index.php                        # Homepage / blog index
├── single.php                       # Single post
├── page.php                         # Static pages
├── page-submit-tip.php              # Tip submission page template
├── archive.php                      # Category / tag / date archives
├── search.php                       # Search results
├── 404.php                          # 404 error page
├── header.php                       # Site header + navigation
├── footer.php                       # Site footer + style switcher
├── sidebar.php                      # Sidebar with newsletter, recent posts
├── searchform.php                   # Custom search form
├── screenshot.png                   # Theme screenshot (1200×900)
├── README.md                        # This file
├── CHANGELOG.md                     # Full version history
├── readme.txt                       # WordPress.org-format documentation
├── css/
│   └── themes/
│       ├── anarchist.css
│       ├── goth.css
│       ├── witchy.css
│       ├── pastel-rainbow-goth.css
│       ├── cyberpunk.css
│       ├── cottagecore.css
│       └── riot-grrrl.css
├── js/
│   ├── style-switcher.js            # Style switcher panel
│   └── navigation.js                # Mobile menu toggle
├── inc/
│   ├── class-github-updater.php     # Auto-updater (GitHub Releases API)
│   ├── tip-admin-columns.php        # Tips list table enhancements
│   └── fallback-menu.php            # Fallback nav menu
└── template-parts/
    ├── home-hero.php
    ├── content-card.php
    └── content-list.php
```

---

## Adding a New Style

1. Create a new CSS file in `css/themes/your-style-name.css` and override the CSS custom properties defined in `style.css`.
2. Add the style to the `queerdispatch_get_styles()` array in `functions.php`.
3. The style switcher will include it automatically.

---

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- No additional plugins required

---

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for the full version history, or browse [GitHub Releases](https://github.com/QnEZ/queerdispatch-manus/releases).

---

## Credits

Built for [QueerDispatch.org](https://queerdispatch.org/) — independent LGBTQIA2S+ news and community reporting.

This theme was built entirely with **[Manus AI](https://manus.im)** — an autonomous general AI agent that handled design, code, debugging, accessibility auditing, and documentation across all releases.
