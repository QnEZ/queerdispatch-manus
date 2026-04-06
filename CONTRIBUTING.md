# Contributing to QueerDispatch

Thank you for your interest in contributing to the QueerDispatch WordPress theme. This project is built and maintained by the QueerDispatch team with assistance from Manus AI. We welcome contributions from the community — whether that's a bug report, a feature suggestion, a CSS fix, or a translation.

---

## Table of Contents

1. [Code of Conduct](#code-of-conduct)
2. [How to Report a Bug](#how-to-report-a-bug)
3. [How to Request a Feature](#how-to-request-a-feature)
4. [How to Submit a Pull Request](#how-to-submit-a-pull-request)
5. [Coding Standards](#coding-standards)
6. [Accessibility Requirements](#accessibility-requirements)
7. [Testing Your Changes](#testing-your-changes)
8. [Contact](#contact)

---

## Code of Conduct

This project is made by and for the LGBTQIA2S+ community. We expect all contributors to treat each other with respect, dignity, and care. Harassment, discrimination, or hostility of any kind will not be tolerated.

If you experience or witness unacceptable behaviour, please contact the team at contact@queerdispatch.org.

---

## How to Report a Bug

Before opening a new issue, please search [existing issues](https://github.com/QnEZ/queerdispatch-manus/issues) to avoid duplicates.

When filing a bug report, include:

- **WordPress version** (e.g. 6.7.1)
- **PHP version** (e.g. 8.2)
- **Theme version** (visible in Appearance → Themes)
- **Active style** (Anarchist, Goth, Cyberpunk, etc.)
- **Steps to reproduce** — be as specific as possible
- **Expected behaviour** — what you expected to happen
- **Actual behaviour** — what actually happened
- **Screenshots** — if the bug is visual, a screenshot is very helpful

Use the **Bug Report** issue template when available.

---

## How to Request a Feature

Feature requests are welcome. Please open an issue with the **Feature Request** template and describe:

- **What problem does this solve?** — explain the use case, not just the solution
- **Who benefits?** — is this useful for all users, or a specific subset?
- **Proposed approach** — if you have a specific implementation in mind, describe it; otherwise leave this open

We prioritise features that benefit accessibility, readability, or the LGBTQIA2S+ community specifically.

---

## How to Submit a Pull Request

### 1. Fork and clone the repository

```bash
git clone https://github.com/YOUR-USERNAME/queerdispatch-manus.git
cd queerdispatch-manus
```

### 2. Create a feature branch

Use a descriptive branch name:

```bash
git checkout -b fix/goth-link-contrast
git checkout -b feature/reading-mode-toggle
```

### 3. Make your changes

Follow the [Coding Standards](#coding-standards) below. Keep changes focused — one fix or feature per pull request.

### 4. Test your changes

See [Testing Your Changes](#testing-your-changes) below. At minimum, test your changes across all 7 theme styles.

### 5. Commit with a clear message

Use the imperative mood in the subject line:

```
Fix: correct link contrast in Goth style (was 2.1:1, now 7.2:1)
Add: reading mode toggle to single post pages
Update: bump version to 1.4.2 in style.css and functions.php
```

### 6. Open a pull request

- Target the `main` branch
- Describe what changed and why
- Reference any related issues with `Fixes #123` or `Closes #123`
- Include before/after screenshots for visual changes

---

## Coding Standards

### PHP

- Follow the [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use tabs for indentation, not spaces
- Prefix all functions, hooks, and global variables with `queerdispatch_`
- Escape all output with the appropriate WordPress function (`esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`)
- Sanitise all input with `sanitize_text_field()`, `sanitize_email()`, `absint()`, etc.
- Use `__()` and `_e()` for all user-facing strings to support translation
- Text domain is `queerdispatch`

### CSS

- All theme-specific styles belong in `css/themes/{style-name}.css`
- All shared/structural styles belong in `style.css`
- **Never** use comma-separated selectors that mix a bare body selector with a descendant selector on the same line. This is the root cause of the style-bleeding bugs fixed in v1.2.2. Always write them on separate lines:

  ```css
  /* ✅ Correct */
  body[data-style="goth"] .nav-menu a:hover,
  body.style-goth .nav-menu a:hover {
      background: var(--color-accent);
  }

  /* ❌ Wrong — applies the rule to the entire body */
  body[data-style="goth"], body.style-goth .nav-menu a:hover {
      background: var(--color-accent);
  }
  ```

- Use CSS custom properties (`--color-accent`, `--font-heading`, etc.) rather than hardcoded values wherever possible
- All new theme styles must define the full set of variables listed in the `:root {}` block in `style.css`

### JavaScript

- All JavaScript belongs in `js/style-switcher.js`
- Use vanilla JS only — no jQuery, no external libraries
- Wrap all code in an IIFE to avoid polluting the global scope
- Use `localStorage` for persisting user preferences (style, reading mode)

### Versioning

- Version numbers follow [Semantic Versioning](https://semver.org/): `MAJOR.MINOR.PATCH`
- Update the version in **both** `style.css` (the `Version:` header field) and `functions.php` (the `@version` doc comment and the `QUEERDISPATCH_VERSION` constant if present)
- Update `CHANGELOG.md` with a new entry for every release

---

## Accessibility Requirements

All contributions must maintain or improve the theme's WCAG 2.1 AA compliance. Specifically:

- **Text contrast** — body text must achieve ≥ 4.5:1 contrast ratio against its background. Muted/secondary text must achieve ≥ 3:1. Use the [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/) to verify.
- **Link contrast** — links must be distinguishable from surrounding body text by colour alone (or by underline), and must meet the 4.5:1 ratio against the background.
- **Focus indicators** — all interactive elements must have a visible focus ring. Do not use `outline: none` without providing an alternative.
- **Keyboard navigation** — all interactive elements must be reachable and operable via keyboard alone.
- **ARIA** — use `aria-pressed`, `aria-expanded`, `aria-label`, and `role` attributes where appropriate. Do not use ARIA to override native semantics unnecessarily.

If your change affects any of the 7 theme styles, verify contrast ratios for that style before submitting.

---

## Testing Your Changes

There is no automated test suite at this time. Manual testing is required.

### Minimum test matrix

Test your changes in each of the 7 theme styles by switching via the style switcher in the site header:

| Style | Key things to check |
|---|---|
| Anarchist | Red/black contrast, diagonal background lines, article body text |
| Goth | Dark background, rose link colour, muted text legibility |
| Witchy | Gold links on dark purple, decorative serif fonts |
| Pastel Rainbow Goth | Pastel palette on near-black, gradient text headings |
| Cyberpunk Queer | Neon on dark, Share Tech body font, scanline overlay |
| Cottage Queer | Cream background, terracotta links, green widget bars |
| Riot Grrrl | High-contrast punk palette, handwritten/stencil fonts |

### Pages to test

- **Front page** — hero, article grid, sidebar
- **Single post** — reading mode toggle, link colours, blockquotes, headings
- **Submit a Tip page** — form fields, Signal section, file upload
- **Style switcher** — switching between all 7 styles, colour swatches

### Browser support

Test in at least two of: Chrome, Firefox, Safari. Mobile viewport (375px width) should be checked for any layout changes.

---

## Contact

For questions about contributing, reach out at contact@queerdispatch.org or open a [GitHub Discussion](https://github.com/QnEZ/queerdispatch-manus/discussions).

For sensitive issues, you can also reach us via Signal: **KPP.78** (username) or **+1 (609) 334-3796**.
