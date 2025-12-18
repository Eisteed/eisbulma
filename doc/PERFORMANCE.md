# Performance Optimization Guide

This document explains the performance optimizations implemented in the EisBulma theme and provides guidance for further improvements.

## File Structure

All performance optimizations are organized in the `inc/performance/` folder:

```
inc/performance/
├── init.php           # Loader - loads all performance modules
├── critical-css.php   # Inline critical CSS (fallback only, no build process)
├── optimization.php   # Image lazy loading, resource hints, preloading
└── debloat.php       # Remove unnecessary WordPress scripts/styles
```

## Implemented Optimizations

### 1. Image Optimization

**Location:** [inc/performance/optimization.php](inc/performance/optimization.php)

- ✅ **Native lazy loading**: All images automatically get `loading="lazy"` attribute
- ✅ **Async decoding**: Images use `decoding="async"` for non-blocking decode
- ✅ **Priority hints**: Featured/hero images get `fetchpriority="high"` and skip lazy loading
- ✅ **Preloading**: Critical images (logo, featured images) are preloaded
- ✅ **Responsive images**: WordPress srcset is fully enabled

**Impact:** Reduces initial page weight, improves LCP for images below the fold

### 2. Critical CSS

**Location:** [inc/performance/critical-css.php](inc/performance/critical-css.php)

- ✅ **Inline critical CSS**: Minimal above-the-fold CSS inlined in `<head>`
- ✅ **Fallback-only approach**: No build process required
- ✅ **Manually curated**: Optimized for WordPress dynamic content
- ✅ **CLS prevention**: Basic layout styles prevent Cumulative Layout Shift

**Included critical CSS:**
- Base styles (box-sizing, body, html)
- Navbar positioning and layout (fixed positioning)
- Container width constraints
- Image sizing to prevent layout shift
- Basic column layout
- Responsive utilities (is-hidden-touch, is-hidden-desktop)
- Anti-FOUC for WooCommerce

**To customize:** Edit the CSS in [inc/performance/critical-css.php:26-142](inc/performance/critical-css.php#L26-L142)

### 3. JavaScript Optimization

**Location:** [inc/vite.php:141-149](inc/vite.php#L141-L149)

- ✅ **Module scripts**: All scripts use `type="module"` which automatically defers execution
- ✅ **Non-blocking**: Scripts don't block HTML parsing
- ✅ **Order preservation**: Module scripts maintain execution order

**Scripts loaded:**
- `class-inject.js` - Loaded early in head (for FOUC prevention) but non-blocking
- `main.js` - Main application logic, deferred automatically

### 4. Resource Hints

**Location:** [inc/performance/optimization.php:16-40](inc/performance/optimization.php#L16-L40)

- ✅ **Preconnect/DNS-prefetch**: Ready for external resources (CDNs, APIs)
- ✅ **Preload**: Critical assets (logo, featured images)

**To add external domains:**
```php
// In inc/performance/optimization.php, add to eisbulma_resource_hints():
case 'preconnect':
    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = 'https://your-cdn.com';
    break;
```

### 5. Debloating

**Location:** [inc/performance/debloat.php](inc/performance/debloat.php)

Already implemented:
- ✅ jQuery removed on non-WooCommerce pages
- ✅ Emoji detection scripts removed
- ✅ WordPress embed script removed
- ✅ Dashicons removed for non-logged-in users
- ✅ Conditional WooCommerce page detection
