# Accessibility Audit

**Project:** Universo da Doçura
**Site URL:** http://universo-da-docura
**Audit Date:** 2026-01-08
**Audited By:** Morgan Jones
**Standards Targeted:** WCAG 2.1 AA

---

## 1. Scope of Audit

Audit focuses on representative page types to id systemic accessibility issues.

**Pages reviewed:**

- Home page('/')

## 2. Tools & Methods Used

**Automated testing**

- Pally (CLI, local environment)

### Automated findings (Pa11y)

A Pa11y scan was run locally against the home page.
Raw output is preserved in:

- `docs/pa11y-home.txt`

The issues below are grouped by pattern rather than individual instances.

## 3. Summary of Findings

Findings were grouped by recurring patterns rather than individual instances.

### High Impact

- Multiple buttons fail minimum color contrast requirements
  Affected classes include `.btn--blue`, `.btn--orange`, and `.btn--dark-orange`
  WCAG 1.4.3 (Contrast – Minimum)

- Search input is missing an accessible label
  WCAG 1.3.1 (Info and Relationships) / 4.1.2 (Name, Role, Value)

- Slider navigation buttons lack accessible names
  Affects `.glide__bullet` buttons
  WCAG 4.1.2 (Name, Role, Value)

### Medium Impact

- Low-contrast utility links using the `.gray` class
  WCAG 1.4.3 (Contrast – Minimum)

- Icon-only links lack accessible names
  Includes header search trigger and footer social links
  WCAG 4.1.2 (Name, Role, Value)

## 4. Issue Tracking

| Issue | WCAG | Scope | Status |
|------|------|-------|--------|
| Button color contrast (all button variants) | 1.4.3 | Theme | Open |
| Search input missing label | 1.3.1 / 4.1.2 | Theme | Open |
| Slider bullets missing accessible names | 4.1.2 | Theme / Plugin | Open |
| Icon-only links missing accessible names | 4.1.2 | Theme | Open |
| Low-contrast utility links (`.gray`) | 1.4.3 | Theme | Open |
