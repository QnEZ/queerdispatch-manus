# QueerDispatch WordPress Theme

**Version:** 1.0.0
**Author:** QueerDispatch Team
**License:** GPL v2 or later

Independent LGBTQIA2S+ news & community theme with **7 switchable aesthetic styles**.

---

## Installation

1. Download the `queerdispatch-theme.zip` file.
2. In your WordPress admin, go to **Appearance → Themes → Add New → Upload Theme**.
3. Upload the zip file and click **Activate**.

---

## Style Switcher

A floating **"Style"** tab appears on the right side of every page. Visitors can click it to open the style panel and choose their preferred aesthetic. The selection is saved in a browser cookie for one year.

### Available Styles

| Style | Aesthetic | Key Colors |
|---|---|---|
| **Anarchist** | Black & red, DIY punk zine, distressed textures | `#cc0000` / `#0d0d0d` |
| **Goth** | Dark academia, ornate Victorian, gothic serif | `#8b1a4a` / `#080810` |
| **Witchy** | Mystical, tarot card, moon phases, gold accents | `#c8a020` / `#0e0a1a` |
| **Pastel Rainbow Goth** | Kawaii-goth hybrid, soft pastels on dark | `#ff9de2` / `#0d0a14` |
| **Cyberpunk Queer** | Neon pink/cyan, glitch effects, tech noir | `#ff00ff` / `#050510` |
| **Cottagecore Queer** | Sage greens, warm cream, floral, cozy nature | `#5a7a3a` / `#f5f0e8` |
| **Riot Grrrl** | Hot pink & black, feminist punk, zine energy | `#ff0080` / `#0d0d0d` |

---

## Theme Features

- **Responsive** magazine-style layout with hero, grid, and list sections
- **7 switchable aesthetic styles** with cookie-based persistence
- **Breaking news ticker** (configurable via Customizer)
- **Custom post type**: Events (`qd_event`)
- **3 navigation menus**: Primary, Footer, Social
- **3 widget areas**: Sidebar, Footer 1, Footer 2
- **Custom logo** support
- **Featured image sizes**: Hero (1200×700), Card (600×380), List (280×200)
- **Block editor** support with wide/full alignment
- **Accessibility**: ARIA labels, keyboard navigation for style switcher, skip link
- **Performance**: Emoji scripts removed, Google Fonts preloaded

---

## File Structure

```
queerdispatch-theme/
├── style.css                    # Theme header + base styles
├── functions.php                # Theme setup, enqueues, style switcher logic
├── index.php                    # Homepage / blog index
├── single.php                   # Single post
├── page.php                     # Static pages
├── archive.php                  # Category / tag / date archives
├── search.php                   # Search results
├── 404.php                      # 404 error page
├── header.php                   # Site header + navigation
├── footer.php                   # Site footer + style switcher
├── sidebar.php                  # Sidebar with newsletter, recent posts
├── searchform.php               # Custom search form
├── screenshot.png               # Theme screenshot (1200×900)
├── README.md                    # This file
├── css/
│   └── themes/
│       ├── anarchist.css        # Anarchist style
│       ├── goth.css             # Goth style
│       ├── witchy.css           # Witchy style
│       ├── pastel-rainbow-goth.css  # Pastel Rainbow Goth style
│       ├── cyberpunk.css        # Cyberpunk Queer style
│       ├── cottagecore.css      # Cottagecore Queer style
│       └── riot-grrrl.css       # Riot Grrrl style
├── js/
│   ├── style-switcher.js        # Style switcher functionality
│   └── navigation.js            # Mobile menu toggle
├── inc/
│   └── fallback-menu.php        # Fallback nav menu
└── template-parts/
    ├── home-hero.php             # Homepage hero section
    ├── content-card.php          # Article card template
    └── content-list.php          # Article list item template
```

---

## Customizer Options

Navigate to **Appearance → Customize** to configure:

- **Theme Style** — Set the default style for visitors who haven't chosen one
- **Breaking News Bar** — Enable/disable and set the ticker text (separate items with ` | `)

---

## Adding a New Style

1. Create a new CSS file in `css/themes/your-style-name.css`
2. Override the CSS custom properties (variables) defined in `style.css`
3. Add the style to the `queerdispatch_get_styles()` array in `functions.php`
4. The style switcher will automatically include it

---

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher

---

## Credits

Built for [QueerDispatch.org](https://queerdispatch.org/) — Independent LGBTQIA2S+ news & community reporting.
