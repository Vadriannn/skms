<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKMS UBAYA — Student Knowledge Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --skms-teal: #0D7F74;
            --skms-teal-hover: #0A665D;
            --skms-mint: #8ED8C3;
            --skms-mint-soft: #E6F4EF;
            --skms-mint-card: #E8F5F1;
            --skms-mint-border: #C7E6DC;
            --skms-forest: #16322F;
            --skms-text: #2C4642;
            --skms-muted: #647874;
            --skms-cloud: #F6F8F7;
            --skms-border: #E2E9E6;
        }

        * {
            box-sizing: border-box;
        }

        body.skms-student-body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--skms-cloud);
            color: var(--skms-text);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ── TOP NAVBAR ────────────────────────────────────────────────── */
        .skms-navbar {
            background: #FFFFFF;
            border-bottom: 1px solid var(--skms-border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .skms-navbar-inner {
            max-width: 1220px;
            margin: 0 auto;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .skms-logo-link {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .skms-logo-text {
            display: flex;
            flex-direction: column;
        }

        .skms-logo-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--skms-forest);
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        .skms-logo-sub {
            font-size: 11px;
            font-weight: 500;
            color: var(--skms-muted);
            margin-top: 1px;
        }

        .skms-nav-right {
            display: flex;
            align-items: center;
            gap: 26px;
        }

        .skms-nav-link {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--skms-forest);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .skms-nav-link:hover,
        .skms-nav-link.active {
            color: var(--skms-teal);
        }

        .skms-btn-nrp {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            background-color: var(--skms-teal);
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: background-color 0.15s ease, transform 0.15s ease;
        }

        .skms-btn-nrp:hover {
            background-color: var(--skms-teal-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        .skms-nrp-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 7px 14px;
            background: var(--skms-mint-card);
            border: 1px solid var(--skms-mint-border);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--skms-forest);
        }

        .skms-nrp-logout {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            color: #DC2626;
            font-size: 11.5px;
            font-weight: 600;
            padding: 4px 9px;
            border-radius: 6px;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.15s ease;
        }

        .skms-nrp-logout:hover {
            background: #FEF2F2;
            border-color: #FECACA;
        }

        /* ── HERO SECTION: LANDING PAGE (IMAGE 1) ──────────────────────── */
        .skms-landing-hero {
            position: relative;
            background-color: var(--skms-mint-soft);
            border-bottom: 1px solid var(--skms-mint-border);
            overflow: hidden;
            min-height: 410px;
            display: flex;
            align-items: center;
        }

        .skms-landing-hero-photo {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 56%;
            background-image: url('{{ asset("images/ubaya-campus.jpg") }}');
            background-size: cover;
            background-position: center center;
            z-index: 1;
        }

        .skms-landing-hero-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                90deg,
                #E6F4EF 0%,
                #E6F4EF 44%,
                rgba(230, 244, 239, 0.94) 52%,
                rgba(230, 244, 239, 0.55) 66%,
                rgba(230, 244, 239, 0.12) 82%,
                rgba(230, 244, 239, 0.0) 100%
            );
            z-index: 2;
        }

        .skms-landing-hero-inner {
            position: relative;
            z-index: 3;
            max-width: 1220px;
            width: 100%;
            margin: 0 auto;
            padding: 54px 28px 56px;
        }

        .skms-landing-hero-copy {
            max-width: 570px;
        }

        .skms-hero-kicker {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--skms-teal);
            margin-bottom: 14px;
        }

        .skms-hero-h1 {
            font-size: 38px;
            font-weight: 800;
            color: var(--skms-forest);
            letter-spacing: -0.03em;
            line-height: 1.18;
            margin: 0 0 14px;
        }

        .skms-hero-desc {
            font-size: 14.5px;
            color: #4D6762;
            line-height: 1.65;
            margin: 0 0 26px;
            max-width: 490px;
        }

        /* Search Bar Box */
        .skms-search-bar {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 1px solid #D5E4DF;
            border-radius: 12px;
            padding: 6px 6px 6px 16px;
            box-shadow: 0 8px 24px -8px rgba(13, 127, 116, 0.12);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .skms-search-bar:focus-within {
            border-color: var(--skms-teal);
            box-shadow: 0 10px 28px -8px rgba(13, 127, 116, 0.2), 0 0 0 3px rgba(13, 127, 116, 0.1);
        }

        .skms-search-bar svg {
            color: #829894;
            flex-shrink: 0;
        }

        .skms-search-bar input {
            flex: 1;
            border: none;
            outline: none;
            padding: 11px 14px;
            font-size: 14px;
            color: var(--skms-forest);
            background: transparent;
            font-family: inherit;
        }

        .skms-search-bar input::placeholder {
            color: #8FA39F;
        }

        .skms-search-submit {
            padding: 11px 24px;
            background-color: var(--skms-teal);
            color: #FFFFFF;
            border: none;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background-color 0.15s ease;
            white-space: nowrap;
        }

        .skms-search-submit:hover {
            background-color: var(--skms-teal-hover);
        }

        .skms-popular-tags {
            margin-top: 18px;
        }

        .skms-popular-tags-label {
            font-size: 12px;
            color: var(--skms-muted);
            margin-bottom: 6px;
        }

        .skms-popular-tags-list {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 12.5px;
            font-weight: 600;
        }

        .skms-popular-tags-list a {
            color: var(--skms-teal);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .skms-popular-tags-list a:hover {
            color: var(--skms-forest);
            text-decoration: underline;
        }

        .skms-popular-tags-dot {
            color: #9BB2AE;
        }

        /* ── LANDING MAIN CONTENT (IMAGE 1) ────────────────────────────── */
        .skms-main-container {
            max-width: 1220px;
            width: 100%;
            margin: 0 auto;
            padding: 36px 28px 56px;
            flex: 1;
        }

        .skms-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            max-width: 760px;
        }

        .skms-section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--skms-forest);
            letter-spacing: -0.02em;
            margin: 0;
        }

        .skms-section-link {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--skms-teal);
            text-decoration: none;
        }

        .skms-section-link:hover {
            text-decoration: underline;
        }

        .skms-landing-grid {
            display: grid;
            grid-template-columns: 1fr 370px;
            gap: 28px;
            align-items: stretch;
            margin-bottom: 26px;
        }

        .skms-freq-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .skms-freq-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 12px;
            padding: 14px 18px;
            text-decoration: none;
            transition: all 0.18s ease;
        }

        .skms-freq-card:hover {
            border-color: var(--skms-teal);
            box-shadow: 0 6px 18px -6px rgba(13, 127, 116, 0.12);
            transform: translateY(-1px);
        }

        .skms-freq-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .skms-freq-num {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: var(--skms-cloud);
            border: 1px solid var(--skms-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--skms-muted);
            flex-shrink: 0;
        }

        .skms-freq-info {
            min-width: 0;
        }

        .skms-freq-q {
            font-size: 14px;
            font-weight: 700;
            color: var(--skms-forest);
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .skms-freq-cat {
            font-size: 12px;
            color: var(--skms-muted);
        }

        .skms-freq-arrow {
            color: var(--skms-muted);
            flex-shrink: 0;
            transition: transform 0.15s ease, color 0.15s ease;
        }

        .skms-freq-card:hover .skms-freq-arrow {
            color: var(--skms-teal);
            transform: translateX(3px);
        }

        /* Right Feature Card on Landing */
        .skms-feature-card {
            background: var(--skms-mint-soft);
            border: 1px solid var(--skms-mint-border);
            border-radius: 14px;
            padding: 26px 26px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .skms-feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #FFFFFF;
            color: var(--skms-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 2px 6px rgba(13, 127, 116, 0.08);
        }

        .skms-feature-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--skms-forest);
            line-height: 1.35;
            margin: 0 0 10px;
        }

        .skms-feature-desc {
            font-size: 13px;
            color: #4D6762;
            line-height: 1.6;
            margin: 0;
        }

        /* Bottom Help Banner on Landing */
        .skms-help-banner {
            background: var(--skms-mint-soft);
            border: 1px solid var(--skms-mint-border);
            border-radius: 14px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .skms-help-banner-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .skms-help-banner-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            background: #D4EDE4;
            color: var(--skms-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .skms-help-banner-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--skms-forest);
            margin: 0 0 3px;
        }

        .skms-help-banner-sub {
            font-size: 13px;
            color: #4D6762;
            margin: 0;
        }

        .skms-btn-white-cta {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            background: #FFFFFF;
            border: 1px solid var(--skms-mint-border);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            color: var(--skms-teal);
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .skms-btn-white-cta:hover {
            border-color: var(--skms-teal);
            box-shadow: 0 4px 12px rgba(13, 127, 116, 0.1);
            transform: translateY(-1px);
        }

        /* ── SEARCH PAGE HEADER & 3-COLUMN LAYOUT (IMAGE 2) ────────────── */
        .skms-search-hero {
            background-color: var(--skms-mint-soft);
            border-bottom: 1px solid var(--skms-mint-border);
            padding: 32px 28px 34px;
        }

        .skms-search-hero-inner {
            max-width: 1220px;
            margin: 0 auto;
        }

        .skms-search-hero-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--skms-forest);
            letter-spacing: -0.025em;
            margin: 0 0 6px;
        }

        .skms-search-hero-sub {
            font-size: 13.5px;
            color: #4D6762;
            margin: 0 0 20px;
        }

        .skms-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--skms-muted);
            margin-bottom: 12px;
        }

        .skms-breadcrumb a {
            color: var(--skms-muted);
            text-decoration: none;
        }

        .skms-breadcrumb a:hover {
            color: var(--skms-teal);
        }

        .skms-results-head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 16px;
            margin-bottom: 22px;
            border-bottom: 1px solid var(--skms-border);
            flex-wrap: wrap;
        }

        .skms-results-head-left {
            display: flex;
            align-items: baseline;
            gap: 18px;
            flex-wrap: wrap;
        }

        .skms-results-h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--skms-forest);
            letter-spacing: -0.02em;
            margin: 0;
        }

        .skms-results-count {
            font-size: 13px;
            color: var(--skms-muted);
        }

        /* 3-Column Search Grid */
        .skms-three-col {
            display: grid;
            grid-template-columns: 270px 1fr 310px;
            gap: 24px;
            align-items: start;
        }

        /* ── COLUMN 1: SEARCHABLE COMBOBOX + CHIP TAG FILTER CARD ──────── */
        .skms-filter-card {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 14px;
            padding: 20px;
            position: sticky;
            top: 84px;
        }

        .skms-filter-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 4px;
        }

        .skms-filter-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 700;
            color: var(--skms-forest);
            margin: 0;
        }

        .skms-filter-badge-count {
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            background: var(--skms-mint-card);
            color: var(--skms-teal);
        }

        .skms-filter-sub {
            font-size: 12px;
            color: var(--skms-muted);
            margin: 0 0 14px;
        }

        /* Combobox Wrapper */
        .skms-combobox-wrap {
            position: relative;
            margin-bottom: 12px;
        }

        .skms-cat-search-box {
            position: relative;
        }

        .skms-cat-search-box svg.search-ic {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #8FA39F;
            pointer-events: none;
        }

        .skms-cat-search-input {
            width: 100%;
            padding: 10px 34px 10px 33px;
            background: var(--skms-cloud);
            border: 1px solid var(--skms-border);
            border-radius: 9px;
            font-size: 12.5px;
            color: var(--skms-forest);
            font-family: inherit;
            outline: none;
            cursor: pointer;
            transition: border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
        }

        .skms-cat-search-input:focus,
        .skms-combobox-wrap.is-open .skms-cat-search-input {
            background: #FFFFFF;
            border-color: var(--skms-teal);
            box-shadow: 0 0 0 3px rgba(13, 127, 116, 0.1);
            cursor: text;
        }

        .skms-combobox-chevron {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--skms-muted);
            pointer-events: none;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .skms-combobox-wrap.is-open .skms-combobox-chevron {
            transform: translateY(-50%) rotate(180deg);
            color: var(--skms-teal);
        }

        .skms-cat-search-clear {
            position: absolute;
            right: 28px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--skms-muted);
            cursor: pointer;
            padding: 2px 5px;
            font-size: 14px;
            line-height: 1;
            display: none;
        }

        /* Floating Dropdown Panel (Hidden until Combobox is clicked/typed) */
        .skms-combobox-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #FFFFFF;
            border: 1px solid var(--skms-mint-border);
            border-radius: 12px;
            box-shadow: 0 14px 32px -8px rgba(22, 50, 47, 0.16);
            padding: 6px;
            z-index: 60;
            display: none;
        }

        .skms-combobox-wrap.is-open .skms-combobox-dropdown {
            display: block;
        }

        .skms-cat-list-scroll {
            max-height: 215px;
            overflow-y: auto;
            padding-right: 2px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .skms-cat-list-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .skms-cat-list-scroll::-webkit-scrollbar-track {
            background: #F1F5F3;
            border-radius: 99px;
        }

        .skms-cat-list-scroll::-webkit-scrollbar-thumb {
            background: #C4D6D1;
            border-radius: 99px;
        }

        .skms-cat-list-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--skms-teal);
        }

        .skms-cat-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 7px 9px;
            border-radius: 8px;
            cursor: pointer;
            user-select: none;
            transition: background 0.15s ease;
            font-size: 12.5px;
            color: var(--skms-text);
        }

        .skms-cat-option:hover {
            background: var(--skms-cloud);
        }

        .skms-cat-option.is-checked {
            background: var(--skms-mint-soft);
            color: var(--skms-teal);
            font-weight: 600;
        }

        .skms-cat-option-left {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }

        .skms-cat-option input[type="checkbox"] {
            accent-color: var(--skms-teal);
            width: 15px;
            height: 15px;
            margin: 0;
            cursor: pointer;
            flex-shrink: 0;
        }

        .skms-cat-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .skms-cat-count-pill {
            font-size: 11px;
            font-weight: 600;
            color: var(--skms-muted);
            background: var(--skms-cloud);
            padding: 1px 7px;
            border-radius: 999px;
            flex-shrink: 0;
        }

        .skms-cat-option.is-checked .skms-cat-count-pill {
            background: #D2ECE3;
            color: var(--skms-teal);
        }

        .skms-cat-empty {
            display: none;
            text-align: center;
            padding: 14px 8px;
            font-size: 12px;
            color: var(--skms-muted);
        }

        .skms-dropdown-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: var(--skms-muted);
            margin-top: 6px;
            padding: 6px 8px 2px;
            border-top: 1px solid #F0F4F2;
        }

        .skms-dropdown-close-btn {
            background: none;
            border: none;
            padding: 0;
            font-size: 11px;
            font-weight: 700;
            color: var(--skms-teal);
            cursor: pointer;
            font-family: inherit;
        }

        /* Default "Semua kategori" status pill when no chip is selected */
        .skms-all-active-pill {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 11px;
            background: var(--skms-mint-soft);
            border: 1px solid var(--skms-mint-border);
            border-radius: 9px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--skms-teal);
            margin-bottom: 4px;
        }

        /* Active Selected Chips Bar */
        .skms-active-chips-wrap {
            margin-bottom: 4px;
        }

        .skms-active-chips-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 600;
            color: var(--skms-muted);
            margin-bottom: 7px;
        }

        .skms-clear-all-cats {
            background: none;
            border: none;
            padding: 0;
            font-size: 11px;
            font-weight: 600;
            color: #DC2626;
            cursor: pointer;
            font-family: inherit;
        }

        .skms-clear-all-cats:hover {
            text-decoration: underline;
        }

        .skms-active-chips-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .skms-active-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: var(--skms-mint-card);
            border: 1px solid var(--skms-mint-border);
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            color: var(--skms-teal);
        }

        .skms-active-chip button {
            background: none;
            border: none;
            padding: 0;
            color: var(--skms-teal);
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
        }

        .skms-active-chip button:hover {
            color: #DC2626;
        }

        .skms-btn-apply-filter {
            width: 100%;
            margin-top: 12px;
            padding: 10px 16px;
            background: var(--skms-teal);
            color: #FFFFFF;
            border: none;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.15s ease;
        }

        .skms-btn-apply-filter:hover {
            background: var(--skms-teal-hover);
        }

        /* ── COLUMN 2: SEARCH RESULT CARDS ─────────────────────────────── */
        .skms-results-col {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .skms-result-card {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 14px;
            padding: 22px 24px 18px;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .skms-result-card:hover {
            border-color: #B8DDD1;
            box-shadow: 0 8px 24px -10px rgba(13, 127, 116, 0.12);
        }

        .skms-result-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .skms-result-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .skms-result-doc-ic {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--skms-mint-card);
            color: var(--skms-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .skms-cat-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 11px;
            background: var(--skms-mint-card);
            color: var(--skms-teal);
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .skms-relevance-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 11px;
            background: var(--skms-mint-card);
            color: var(--skms-teal);
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .skms-result-title {
            font-size: 19px;
            font-weight: 800;
            color: var(--skms-forest);
            margin: 0 0 8px;
            line-height: 1.35;
            letter-spacing: -0.015em;
        }

        .skms-result-title a {
            color: inherit;
            text-decoration: none;
        }

        .skms-result-title a:hover {
            color: var(--skms-teal);
        }

        .skms-result-excerpt {
            font-size: 13.5px;
            color: var(--skms-muted);
            line-height: 1.6;
            margin: 0 0 16px;
        }

        .skms-result-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 12px;
            color: var(--skms-muted);
            margin-bottom: 14px;
        }

        .skms-result-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .skms-result-footer {
            padding-top: 13px;
            border-top: 1px solid #EEF3F1;
        }

        .skms-result-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            color: var(--skms-teal);
        }

        .skms-result-link:hover {
            color: var(--skms-teal-hover);
        }

        .skms-search-tip-box {
            background: var(--skms-mint-soft);
            border: 1px solid var(--skms-mint-border);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .skms-search-tip-box svg {
            color: var(--skms-teal);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .skms-search-tip-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--skms-forest);
            margin: 0 0 4px;
        }

        .skms-search-tip-desc {
            font-size: 12.5px;
            color: #4D6762;
            margin: 0;
            line-height: 1.55;
        }

        /* ── COLUMN 3: TOP FAQ TERPOPULER ──────────────────────────────── */
        .skms-top-card {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 14px;
            padding: 20px;
            position: sticky;
            top: 84px;
        }

        .skms-top-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .skms-top-star {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #FEF3C7;
            color: #D97706;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .skms-top-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--skms-forest);
            margin: 0 0 2px;
        }

        .skms-top-sub {
            font-size: 11.5px;
            color: var(--skms-muted);
            margin: 0;
        }

        .skms-top-list {
            display: flex;
            flex-direction: column;
        }

        .skms-top-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid #EEF3F1;
            text-decoration: none;
        }

        .skms-top-item:first-child {
            border-top: none;
            padding-top: 4px;
        }

        .skms-top-item:last-child {
            padding-bottom: 2px;
        }

        .skms-top-rank {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: var(--skms-cloud);
            color: var(--skms-muted);
            font-size: 11.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .skms-top-item:first-child .skms-top-rank {
            background: #FEF3C7;
            color: #B45309;
        }

        .skms-top-item-q {
            font-size: 13px;
            font-weight: 700;
            color: var(--skms-forest);
            line-height: 1.4;
            margin-bottom: 4px;
            transition: color 0.15s ease;
        }

        .skms-top-item:hover .skms-top-item-q {
            color: var(--skms-teal);
        }

        .skms-top-item-meta {
            font-size: 11px;
            color: var(--skms-muted);
        }

        /* Bottom Bar on Search Page */
        .skms-search-bottom-bar {
            margin-top: 28px;
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 12px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .skms-search-bottom-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            font-size: 13px;
        }

        .skms-search-bottom-left strong {
            color: var(--skms-forest);
        }

        .skms-search-bottom-left span {
            color: var(--skms-muted);
        }

        .skms-search-bottom-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 700;
            color: var(--skms-teal);
            text-decoration: none;
        }

        .skms-search-bottom-link:hover {
            text-decoration: underline;
        }

        /* ── FOOTER ────────────────────────────────────────────────────── */
        .skms-footer {
            background: #FFFFFF;
            border-top: 1px solid var(--skms-border);
            margin-top: auto;
        }

        .skms-footer-inner {
            max-width: 1220px;
            margin: 0 auto;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .skms-footer-brand {
            font-size: 13px;
            font-weight: 800;
            color: var(--skms-teal);
            margin-bottom: 3px;
        }

        .skms-footer-copy {
            font-size: 11.5px;
            color: var(--skms-muted);
        }

        .skms-footer-right {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--skms-muted);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .skms-three-col {
                grid-template-columns: 250px 1fr;
            }
            .skms-top-card {
                grid-column: 1 / -1;
                position: static;
            }
            .skms-landing-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .skms-three-col {
                grid-template-columns: 1fr;
            }
            .skms-filter-card {
                position: static;
            }
            .skms-landing-hero-photo {
                width: 100%;
                opacity: 0.2;
            }
            .skms-landing-hero-gradient {
                background: rgba(230, 244, 239, 0.88);
            }
            .skms-hero-h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body class="skms-student-body">

    <!-- ─── TOP NAVBAR ─────────────────────────────────────────────────────── -->
    <header class="skms-navbar">
        <div class="skms-navbar-inner">
            <a href="{{ route('skms.beranda') }}" class="skms-logo-link">
                <svg width="34" height="34" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 7.5C4 6.11929 5.11929 5 6.5 5C10.5 5 14.5 6.5 17.5 9V14.5L21 17.5L17.5 20.5V29.5C14.5 27 10.5 25.5 6.5 25.5C5.11929 25.5 4 24.3807 4 23V7.5Z" fill="#0D7F74"/>
                    <path d="M32 7.5C32 6.11929 30.8807 5 29.5 5C25.5 5 21.5 6.5 18.5 9V14L22 17.5L18.5 21V29.5C21.5 27 25.5 25.5 29.5 25.5C30.8807 25.5 32 24.3807 32 23V7.5Z" fill="#8ED8C3"/>
                </svg>
                <div class="skms-logo-text">
                    <span class="skms-logo-title">SKMS UBAYA</span>
                    <span class="skms-logo-sub">Student Knowledge Management System</span>
                </div>
            </a>

            <div class="skms-nav-right">
                <a href="{{ route('skms.beranda') }}" class="skms-nav-link {{ !$searched ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('skms.beranda', ['all' => 1]) }}" class="skms-nav-link {{ $searched ? 'active' : '' }}">Informasi</a>
                @if($studentNrp)
                    <div class="skms-nrp-pill">
                        <span>NRP: {{ $studentNrp }}</span>
                        <form action="{{ route('skms.logout') }}" method="POST" style="margin: 0; display: inline;">
                            @csrf
                            <button type="submit" class="skms-nrp-logout">Keluar</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('skms.login') }}" class="skms-btn-nrp">
                        <span>Masuk Mahasiswa (NRP)</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </header>

    @if(!$searched)
        <!-- ===================================================================
             MODE 1: BERANDA UTAMA MAHASISWA (IMAGE 1)
        ==================================================================== -->
        <section class="skms-landing-hero">
            <div class="skms-landing-hero-photo"></div>
            <div class="skms-landing-hero-gradient"></div>

            <div class="skms-landing-hero-inner">
                <div class="skms-landing-hero-copy">
                    <div class="skms-hero-kicker">PUSAT BANTUAN AKADEMIK &amp; KEMAHASISWAAN</div>
                    <h1 class="skms-hero-h1">Satu tempat untuk setiap pertanyaan kuliahmu.</h1>
                    <p class="skms-hero-desc">
                        Temukan solusi resmi dan informasi seputar kegiatan hingga layanan kemahasiswaan Universitas Surabaya.
                    </p>

                    <form action="{{ route('skms.beranda') }}" method="GET" class="skms-search-bar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" name="search" placeholder="Apa yang ingin kamu ketahui?" autocomplete="off" autofocus>
                        <button type="submit" class="skms-search-submit">Cari FAQ</button>
                    </form>

                    <div class="skms-popular-tags">
                        <div class="skms-popular-tags-label">Sering dicari:</div>
                        <div class="skms-popular-tags-list">
                            <a href="{{ route('skms.beranda', ['search' => 'Cuti akademik']) }}">Cuti akademik</a>
                            <span class="skms-popular-tags-dot">&bull;</span>
                            <a href="{{ route('skms.beranda', ['search' => 'KRS']) }}">KRS</a>
                            <span class="skms-popular-tags-dot">&bull;</span>
                            <a href="{{ route('skms.beranda', ['search' => 'Pembayaran SPP']) }}">Pembayaran SPP</a>
                            <span class="skms-popular-tags-dot">&bull;</span>
                            <a href="{{ route('skms.beranda', ['search' => 'Wisuda']) }}">Wisuda</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <main class="skms-main-container">
            @if(session('success') || session('info'))
                <div style="margin-bottom: 22px; padding: 12px 18px; border-radius: 10px; background: #E6F4EF; border: 1px solid #C7E6DC; color: #0D7F74; font-size: 13.5px; font-weight: 600;">
                    {{ session('success') ?? session('info') }}
                </div>
            @endif

            <div class="skms-section-head">
                <h2 class="skms-section-title">Pertanyaan yang sering dicari</h2>
                <a href="{{ route('skms.beranda', ['all' => 1]) }}" class="skms-section-link">Lihat semua FAQ</a>
            </div>

            <div class="skms-landing-grid">
                <div class="skms-freq-list">
                    @forelse($topFaqs->take(3) as $idx => $faq)
                        <a href="{{ route('skms.faq.show', $faq->id) }}" class="skms-freq-card">
                            <div class="skms-freq-left">
                                <div class="skms-freq-num">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                <div class="skms-freq-info">
                                    <div class="skms-freq-q">{{ $faq->pertanyaan }}</div>
                                    <div class="skms-freq-cat">{{ $faq->categories->pluck('nama')->join(', ') ?: 'Umum' }}</div>
                                </div>
                            </div>
                            <svg class="skms-freq-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    @empty
                        <div style="background: #FFFFFF; border: 1px solid var(--skms-border); border-radius: 12px; padding: 24px; text-align: center; color: var(--skms-muted); font-size: 13.5px;">
                            Belum ada informasi FAQ yang diterbitkan.
                        </div>
                    @endforelse
                </div>

                <div class="skms-feature-card">
                    <div class="skms-feature-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                    </div>
                    <h3 class="skms-feature-title">Informasi resmi,<br>lebih mudah ditemukan.</h3>
                    <p class="skms-feature-desc">
                        Panduan akademik dan kemahasiswaan dalam satu pusat pengetahuan UBAYA.
                    </p>
                </div>
            </div>

            <div class="skms-help-banner">
                <div class="skms-help-banner-left">
                    <div class="skms-help-banner-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            <line x1="8" y1="9" x2="16" y2="9"></line>
                            <line x1="8" y1="13" x2="13" y2="13"></line>
                        </svg>
                    </div>
                    <div>
                        @if($studentNrp)
                            <h3 class="skms-help-banner-title">Sesi Mahasiswa Aktif (NRP: {{ $studentNrp }})</h3>
                            <p class="skms-help-banner-sub">Anda dapat menjelajahi seluruh panduan resmi atau memberi penilaian pada jawaban FAQ.</p>
                        @else
                            <h3 class="skms-help-banner-title">Belum menemukan jawaban?</h3>
                            <p class="skms-help-banner-sub">Masuk dengan NRP untuk mengakses layanan bantuan mahasiswa.</p>
                        @endif
                    </div>
                </div>

                @if($studentNrp)
                    <a href="{{ route('skms.tanya') }}" class="skms-btn-white-cta">
                        <span>Tanya Staf / Ajukan Pertanyaan</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('skms.login', ['redirect' => 'tanya']) }}" class="skms-btn-white-cta">
                        <span>Masuk untuk bantuan</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                @endif
            </div>
        </main>

    @else
        <!-- ===================================================================
             MODE 2: PENCARIAN INFORMASI & FAQ (IMAGE 2)
        ==================================================================== -->
        <section class="skms-search-hero">
            <div class="skms-search-hero-inner">
                <h1 class="skms-search-hero-title">Pencarian Informasi &amp; FAQ</h1>
                <p class="skms-search-hero-sub">Cari pertanyaan atau persempit pencarian menggunakan filter kategori.</p>

                <form action="{{ route('skms.beranda') }}" method="GET" class="skms-search-bar">
                    @foreach($selectedCategories as $catId)
                        <input type="hidden" name="categories[]" value="{{ $catId }}" class="hidden-search-cat">
                    @endforeach
                    @if(empty($selectedCategories) && request()->boolean('all'))
                        <input type="hidden" name="all" value="1">
                    @endif
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="search" value="{{ $keyword }}" placeholder="Cari topik, kata kunci, atau layanan kampus..." autocomplete="off">
                    <button type="submit" class="skms-search-submit">Cari FAQ</button>
                </form>
            </div>
        </section>

        <main class="skms-main-container">
            <div class="skms-breadcrumb">
                <a href="{{ route('skms.beranda') }}">Beranda</a>
                <span>/</span>
                <span style="color: var(--skms-forest); font-weight: 600;">Pencarian FAQ</span>
            </div>

            <div class="skms-results-head">
                <div class="skms-results-head-left">
                    <h2 class="skms-results-h2">Hasil pencarian</h2>
                    <span class="skms-results-count">
                        @if($keyword !== '')
                            Ditemukan {{ $results->count() }} hasil untuk "{{ $keyword }}"
                        @elseif(!empty($selectedCategories))
                            Menampilkan {{ $results->count() }} hasil pada {{ count($selectedCategories) }} kategori terpilih
                        @else
                            Menampilkan {{ $results->count() }} informasi FAQ resmi
                        @endif
                    </span>
                </div>

                @if($keyword !== '' || !empty($selectedCategories))
                    <a href="{{ route('skms.beranda', ['all' => 1]) }}" style="font-size: 12.5px; font-weight: 600; color: var(--skms-teal); text-decoration: none;">
                        Atur ulang pencarian
                    </a>
                @endif
            </div>

            <div class="skms-three-col">
                <!-- ── KOLOM KIRI: FILTER KATEGORI (SEARCHABLE COMBOBOX + CHIP TAG) ── -->
                <aside class="skms-filter-card">
                    <form id="categoryFilterForm" action="{{ route('skms.beranda') }}" method="GET">
                        @if($keyword !== '')
                            <input type="hidden" name="search" value="{{ $keyword }}">
                        @else
                            <input type="hidden" name="all" value="1">
                        @endif

                        <div class="skms-filter-head">
                            <h3 class="skms-filter-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--skms-teal)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg>
                                <span>Filter kategori</span>
                            </h3>
                            <span id="selectedCountBadge" class="skms-filter-badge-count" style="{{ empty($selectedCategories) ? 'display:none;' : '' }}">
                                {{ count($selectedCategories) }} dipilih
                            </span>
                        </div>
                        <p class="skms-filter-sub">Persempit pencarianmu.</p>

                        <!-- Searchable Combobox (Daftar baru muncul saat diklik / diketik) -->
                        <div class="skms-combobox-wrap" id="catComboboxWrap">
                            <div class="skms-cat-search-box">
                                <svg class="search-ic" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input type="text"
                                       id="catLiveSearch"
                                       class="skms-cat-search-input"
                                       placeholder="Pilih / cari kategori ({{ $categories->count() }})..."
                                       autocomplete="off">
                                <button type="button" id="catSearchClearBtn" class="skms-cat-search-clear" title="Bersihkan">&times;</button>
                                <svg class="skms-combobox-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>

                            <!-- Floating Dropdown Menu -->
                            <div class="skms-combobox-dropdown" id="catComboboxDropdown">
                                <!-- Opsi "Semua kategori" -->
                                <label class="skms-cat-option {{ empty($selectedCategories) ? 'is-checked' : '' }}" id="allCategoriesOption" style="margin-bottom: 4px; border-bottom: 1px solid #F0F4F2; border-radius: 8px 8px 0 0;">
                                    <div class="skms-cat-option-left">
                                        <input type="checkbox" id="checkAllCategories" {{ empty($selectedCategories) ? 'checked' : '' }}>
                                        <span class="skms-cat-name">Semua kategori</span>
                                    </div>
                                    <span class="skms-cat-count-pill">{{ $categories->sum('faqs_count') }}</span>
                                </label>

                                @php
                                    $sortedCategories = $categories->sortByDesc(function ($cat) use ($selectedCategories) {
                                        return in_array($cat->id, $selectedCategories) ? 1 : 0;
                                    });
                                @endphp

                                <div class="skms-cat-list-scroll" id="categoryCheckboxList">
                                    @foreach($sortedCategories as $cat)
                                        @php $isChecked = in_array($cat->id, $selectedCategories); @endphp
                                        <label class="skms-cat-option cat-filter-item {{ $isChecked ? 'is-checked' : '' }}"
                                               data-name="{{ strtolower($cat->nama) }}"
                                               data-label="{{ $cat->nama }}"
                                               data-id="{{ $cat->id }}">
                                            <div class="skms-cat-option-left">
                                                <input type="checkbox"
                                                       name="categories[]"
                                                       value="{{ $cat->id }}"
                                                       class="cat-checkbox-input"
                                                       {{ $isChecked ? 'checked' : '' }}>
                                                <span class="skms-cat-name" title="{{ $cat->nama }}">{{ $cat->nama }}</span>
                                            </div>
                                            <span class="skms-cat-count-pill">{{ $cat->faqs_count }}</span>
                                        </label>
                                    @endforeach

                                    <div id="catEmptyNotice" class="skms-cat-empty">
                                        Kategori tidak ditemukan.
                                    </div>
                                </div>

                                <div class="skms-dropdown-footer">
                                    <span id="catVisibleCounter">{{ $categories->count() }} kategori tersedia</span>
                                    <button type="button" id="closeDropdownBtn" class="skms-dropdown-close-btn">Selesai</button>
                                </div>
                            </div>
                        </div>

                        <!-- Status Default jika belum ada kategori spesifik yang dipilih -->
                        <div id="allActiveStatusPill" class="skms-all-active-pill" style="{{ !empty($selectedCategories) ? 'display:none;' : '' }}">
                            <span style="display: inline-flex; align-items: center; gap: 6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Semua kategori
                            </span>
                            <span style="font-size: 11px; background: #D2ECE3; padding: 1px 7px; border-radius: 999px;">{{ $categories->sum('faqs_count') }}</span>
                        </div>

                        <!-- Area Chip Tag Kategori Terpilih -->
                        <div id="activeChipsContainer" class="skms-active-chips-wrap" style="{{ empty($selectedCategories) ? 'display:none;' : '' }}">
                            <div class="skms-active-chips-header">
                                <span>Kategori terpilih:</span>
                                <button type="button" id="clearAllCatsBtn" class="skms-clear-all-cats">Hapus semua</button>
                            </div>
                            <div id="activeChipsList" class="skms-active-chips-list"></div>
                        </div>

                        <button type="submit" class="skms-btn-apply-filter">Terapkan filter</button>
                    </form>
                </aside>

                <!-- ── KOLOM TENGAH: DAFTAR HASIL FAQ ──────────────────────── -->
                <div class="skms-results-col">
                    @forelse($results as $faq)
                        <article class="skms-result-card">
                            <div class="skms-result-top">
                                <div class="skms-result-badges">
                                    <div class="skms-result-doc-ic">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="8" y1="13" x2="16" y2="13"></line>
                                            <line x1="8" y1="17" x2="13" y2="17"></line>
                                        </svg>
                                    </div>
                                    @forelse($faq->categories as $cat)
                                        <span class="skms-cat-pill">{{ $cat->nama }}</span>
                                    @empty
                                        <span class="skms-cat-pill">Umum</span>
                                    @endforelse
                                </div>

                                @if(isset($faq->relevance_score) && $faq->relevance_score > 0)
                                    <span class="skms-relevance-pill">
                                        Relevansi: {{ number_format(min($faq->relevance_score * 100, 99.9), 1) }}%
                                    </span>
                                @endif
                            </div>

                            <h3 class="skms-result-title">
                                <a href="{{ route('skms.faq.show', ['faq' => $faq->id, 'q' => $keyword]) }}">
                                    {{ $faq->pertanyaan }}
                                </a>
                            </h3>

                            <p class="skms-result-excerpt">
                                {{ \Illuminate\Support\Str::limit(strip_tags($faq->jawaban), 155) }}
                            </p>

                            <div class="skms-result-meta">
                                <span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    {{ number_format($faq->views) }} views
                                </span>
                                <span>{{ number_format($faq->helpful ?? 0) }} Membantu</span>
                            </div>

                            <div class="skms-result-footer">
                                <a href="{{ route('skms.faq.show', ['faq' => $faq->id, 'q' => $keyword]) }}" class="skms-result-link">
                                    <span>Lihat jawaban lengkap</span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div style="background: #FFFFFF; border: 1px solid var(--skms-border); border-radius: 14px; padding: 36px 24px; text-align: center;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--skms-mint-soft); color: var(--skms-teal); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 700; color: var(--skms-forest); margin: 0 0 6px;">Tidak ada FAQ yang cocok</h3>
                            <p style="font-size: 13px; color: var(--skms-muted); margin: 0 auto 20px; max-width: 440px; line-height: 1.55;">
                                Kami tidak menemukan pertanyaan yang sesuai dengan filter atau kata kunci Anda. Anda dapat mengajukan pertanyaan ini langsung kepada staf kami.
                            </p>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                <a href="{{ route('skms.tanya', array_filter(['q' => $keyword])) }}" style="display: inline-flex; align-items: center; gap: 7px; padding: 10px 18px; background: var(--skms-teal); color: #FFFFFF; border-radius: 9px; font-size: 13px; font-weight: 700; text-decoration: none;">
                                    <span>Ajukan pertanyaan baru ke staf</span>
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                                <a href="{{ route('skms.beranda', ['all' => 1]) }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: #FFFFFF; border: 1px solid var(--skms-border); color: var(--skms-forest); border-radius: 9px; font-size: 13px; font-weight: 600; text-decoration: none;">
                                    Tampilkan semua FAQ
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- ── KOLOM KANAN: TOP FAQ TERPOPULER ─────────────────────── -->
                <aside class="skms-top-card">
                    <div class="skms-top-head">
                        <div class="skms-top-star">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </div>
                        <div>
                            <h3 class="skms-top-title">Top FAQ Terpopuler</h3>
                            <p class="skms-top-sub">Paling sering dilihat mahasiswa</p>
                        </div>
                    </div>

                    <div class="skms-top-list">
                        @forelse($topFaqs as $index => $top)
                            <a href="{{ route('skms.faq.show', $top->id) }}" class="skms-top-item">
                                <span class="skms-top-rank">{{ $index + 1 }}</span>
                                <div>
                                    <div class="skms-top-item-q">{{ $top->pertanyaan }}</div>
                                    <div class="skms-top-item-meta">
                                        {{ number_format($top->views) }} views &bull; {{ $top->categories->first()?->nama ?? 'Umum' }}
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div style="font-size: 12.5px; color: var(--skms-muted); padding: 8px 0;">
                                Belum ada data FAQ populer.
                            </div>
                        @endforelse
                    </div>
                </aside>
            </div>
        </main>
    @endif

    <!-- ─── FOOTER ─────────────────────────────────────────────────────────── -->
    <footer class="skms-footer">
        <div class="skms-footer-inner">
            <div>
                <div class="skms-footer-brand">SKMS UBAYA</div>
                <div class="skms-footer-copy">&copy; 2026 SKMS UBAYA &mdash; Student Knowledge Management System.</div>
            </div>
            <div class="skms-footer-right">Universitas Surabaya</div>
        </div>
    </footer>

    @if($searched)
    <script>
        (function () {
            const comboboxWrap = document.getElementById('catComboboxWrap');
            const searchInput = document.getElementById('catLiveSearch');
            const clearBtn = document.getElementById('catSearchClearBtn');
            const closeDropdownBtn = document.getElementById('closeDropdownBtn');
            const items = Array.from(document.querySelectorAll('.cat-filter-item'));
            const checkboxes = Array.from(document.querySelectorAll('.cat-checkbox-input'));
            const checkAll = document.getElementById('checkAllCategories');
            const allOptionLabel = document.getElementById('allCategoriesOption');
            const allActiveStatusPill = document.getElementById('allActiveStatusPill');
            const emptyNotice = document.getElementById('catEmptyNotice');
            const visibleCounter = document.getElementById('catVisibleCounter');
            const selectedBadge = document.getElementById('selectedCountBadge');
            const chipsContainer = document.getElementById('activeChipsContainer');
            const chipsList = document.getElementById('activeChipsList');
            const clearAllCatsBtn = document.getElementById('clearAllCatsBtn');
            const totalCategories = items.length;

            function openDropdown() {
                if (comboboxWrap) {
                    comboboxWrap.classList.add('is-open');
                }
            }

            function closeDropdown() {
                if (comboboxWrap) {
                    comboboxWrap.classList.remove('is-open');
                }
            }

            function syncCategoryState() {
                const checkedBoxes = checkboxes.filter(cb => cb.checked);
                const checkedCount = checkedBoxes.length;

                // Update "Semua kategori" & active chips visibility
                if (checkedCount === 0) {
                    checkAll.checked = true;
                    allOptionLabel.classList.add('is-checked');
                    selectedBadge.style.display = 'none';
                    chipsContainer.style.display = 'none';
                    if (allActiveStatusPill) allActiveStatusPill.style.display = 'flex';
                } else {
                    checkAll.checked = false;
                    allOptionLabel.classList.remove('is-checked');
                    selectedBadge.style.display = 'inline-block';
                    selectedBadge.textContent = checkedCount + ' dipilih';
                    chipsContainer.style.display = 'block';
                    if (allActiveStatusPill) allActiveStatusPill.style.display = 'none';
                }

                // Highlight checked rows & build active chips
                chipsList.innerHTML = '';
                items.forEach(item => {
                    const cb = item.querySelector('.cat-checkbox-input');
                    if (cb.checked) {
                        item.classList.add('is-checked');
                        const chip = document.createElement('span');
                        chip.className = 'skms-active-chip';
                        chip.innerHTML = `<span>${item.dataset.label}</span><button type="button" title="Hapus kategori">&times;</button>`;
                        chip.querySelector('button').addEventListener('click', function (e) {
                            e.stopPropagation();
                            cb.checked = false;
                            syncCategoryState();
                        });
                        chipsList.appendChild(chip);
                    } else {
                        item.classList.remove('is-checked');
                    }
                });
            }

            // Live search filter across categories
            function filterCategories() {
                openDropdown();
                const q = (searchInput.value || '').trim().toLowerCase();
                clearBtn.style.display = q.length > 0 ? 'block' : 'none';
                let visible = 0;

                items.forEach(item => {
                    const match = item.dataset.name.includes(q);
                    item.style.display = match ? 'flex' : 'none';
                    if (match) visible++;
                });

                emptyNotice.style.display = visible === 0 ? 'block' : 'none';
                visibleCounter.textContent = q.length > 0
                    ? `${visible} dari ${totalCategories} kategori`
                    : `${totalCategories} kategori tersedia`;
            }

            if (searchInput) {
                searchInput.addEventListener('focus', openDropdown);
                searchInput.addEventListener('click', openDropdown);
                searchInput.addEventListener('input', filterCategories);
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    searchInput.value = '';
                    filterCategories();
                    searchInput.focus();
                });
            }

            if (closeDropdownBtn) {
                closeDropdownBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    closeDropdown();
                });
            }

            // Tutup dropdown saat klik di luar combobox atau tekan Escape
            document.addEventListener('click', function (e) {
                if (comboboxWrap && !comboboxWrap.contains(e.target)) {
                    closeDropdown();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeDropdown();
                }
            });
            checkboxes.forEach(cb => {
                cb.addEventListener('change', syncCategoryState);
            });

            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    if (this.checked) {
                        checkboxes.forEach(cb => { cb.checked = false; });
                    }
                    syncCategoryState();
                });
            }

            if (clearAllCatsBtn) {
                clearAllCatsBtn.addEventListener('click', function () {
                    checkboxes.forEach(cb => { cb.checked = false; });
                    syncCategoryState();
                });
            }

            syncCategoryState();
        })();
    </script>
    @endif

    <script>
        /* ── Auto-Reset Sesi Setelah 3 Menit Tidak Ada Aktivitas ────────── */
        (function () {
            const IDLE_TIMEOUT_MS = 180000; // 3 menit
            let idleTimer = null;

            function resetIdleTimer() {
                if (idleTimer) clearTimeout(idleTimer);
                idleTimer = setTimeout(function () {
                    // Refresh halaman ketika diam 3 menit agar middleware meregenerasi sesi
                    window.location.reload();
                }, IDLE_TIMEOUT_MS);
            }

            // Event-event yang menandakan aktivitas pengguna
            ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(function (evt) {
                document.addEventListener(evt, resetIdleTimer, { passive: true });
            });

            // Mulai timer saat halaman dimuat
            resetIdleTimer();
        })();
    </script>
</body>
</html>
