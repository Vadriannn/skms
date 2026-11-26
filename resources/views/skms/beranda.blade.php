<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKMS UBAYA — Pusat Pengetahuan Mahasiswa</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --ubaya-maroon: #8B0D18;
            --ubaya-maroon-dark: #6B0810;
            --ubaya-maroon-deep: #4A070D;
            --ubaya-gold: #E5A032;
            --ubaya-gold-soft: #FEF6E6;
            --ubaya-gold-text: #A8640C;
            --ubaya-rose-soft: #FDF2F3;
            --ubaya-rose-pill: #FBE7E9;
            --ubaya-rose-border: #F1CFD3;
            --ubaya-heading: #1F1415;
            --ubaya-text: #3E2F30;
            --ubaya-muted: #6B5B5D;
            --ubaya-bg: #FAF8F7;
            --ubaya-border: #ECE4E5;
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
            background-color: var(--ubaya-bg);
            color: var(--ubaya-text);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ── FLOATING TOP NAVIGATION BAR (06E CRIMSON IMMERSIVE) ──────── */
        .skms-navbar-shell {
            max-width: 1320px;
            width: 100%;
            margin: 16px auto 0;
            padding: 0 24px;
            position: sticky;
            top: 12px;
            z-index: 50;
        }

        .skms-navbar {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border: 1px solid var(--ubaya-border);
            border-radius: 18px;
            box-shadow: 0 8px 24px -10px rgba(74, 7, 13, 0.08);
        }

        .skms-navbar-inner {
            padding: 12px 22px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 16px;
        }

        .skms-logo-link {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            justify-self: start;
        }

        .skms-logo-text {
            display: flex;
            flex-direction: column;
        }

        .skms-logo-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--ubaya-heading);
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        .skms-logo-sub {
            font-size: 11.5px;
            font-weight: 500;
            color: var(--ubaya-muted);
            margin-top: 1px;
        }

        /* Segmented Center Toggle */
        .skms-nav-segment {
            display: inline-flex;
            align-items: center;
            background: #F6EFEF;
            padding: 4px;
            border-radius: 14px;
            gap: 4px;
            justify-self: center;
        }

        .skms-seg-link {
            padding: 10px 22px;
            border-radius: 11px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--ubaya-text);
            text-decoration: none;
            transition: all 0.18s ease;
            white-space: nowrap;
        }

        .skms-seg-link:hover {
            color: var(--ubaya-maroon);
        }

        .skms-seg-link.active {
            background: var(--ubaya-maroon);
            color: #FFFFFF;
            box-shadow: 0 4px 12px -2px rgba(139, 13, 24, 0.35);
        }

        .skms-nav-right {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-self: end;
        }

        .skms-btn-nrp {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background-color: var(--ubaya-maroon);
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 700;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 6px 16px -6px rgba(139, 13, 24, 0.4);
            transition: background-color 0.15s ease, transform 0.15s ease;
            white-space: nowrap;
        }

        .skms-btn-nrp:hover {
            background-color: var(--ubaya-maroon-dark);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        .skms-nrp-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            background: var(--ubaya-rose-soft);
            border: 1px solid var(--ubaya-rose-border);
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            color: var(--ubaya-maroon);
        }

        .skms-nrp-logout {
            background: #FFFFFF;
            border: 1px solid var(--ubaya-rose-border);
            color: var(--ubaya-maroon);
            font-size: 12px;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.15s ease;
        }

        .skms-nrp-logout:hover {
            background: var(--ubaya-maroon);
            color: #FFFFFF;
        }

        /* ── MODE 1: CRIMSON IMMERSIVE PHOTO HERO + FLOATING SEARCH ────── */
        .skms-kiosk-container {
            max-width: 1320px;
            width: 100%;
            margin: 0 auto;
            padding: 20px 24px 36px;
            flex: 1;
        }

        .skms-hero-stage {
            position: relative;
            margin-bottom: 52px;
        }

        .skms-photo-hero-card {
            position: relative;
            border-radius: 26px;
            overflow: hidden;
            min-height: 315px;
            display: flex;
            align-items: center;
            background-color: var(--ubaya-maroon-deep);
            box-shadow: 0 22px 44px -18px rgba(74, 7, 13, 0.35);
        }

        .skms-photo-hero-bg {
            position: absolute;
            inset: 0;
            background-image: url('{{ asset("images/ubaya-campus.jpg") }}');
            background-size: cover;
            background-position: center 42%;
            z-index: 1;
        }

        .skms-photo-hero-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                96deg,
                rgba(68, 6, 12, 0.94) 0%,
                rgba(116, 11, 22, 0.86) 36%,
                rgba(139, 13, 24, 0.42) 64%,
                rgba(74, 7, 13, 0.10) 100%
            );
            z-index: 2;
        }

        .skms-photo-hero-content {
            position: relative;
            z-index: 3;
            padding: 46px 52px 68px;
            max-width: 650px;
        }

        .skms-hero-h1 {
            font-size: 40px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin: 0 0 14px;
            text-shadow: 0 2px 14px rgba(0, 0, 0, 0.25);
        }

        .skms-hero-desc {
            font-size: 15px;
            color: rgba(255, 244, 244, 0.92);
            line-height: 1.6;
            margin: 0;
            max-width: 500px;
        }

        /* Floating Overlapping Search Bar (Bridging Hero Bottom Edge) */
        .skms-floating-search-wrap {
            position: relative;
            z-index: 10;
            max-width: 860px;
            margin: -34px auto 0;
            padding: 0 16px;
        }

        .skms-search-bar {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 1.5px solid #EAE0E1;
            border-radius: 18px;
            padding: 8px 8px 8px 22px;
            box-shadow: 0 18px 38px -12px rgba(74, 7, 13, 0.2), 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .skms-search-bar:focus-within {
            border-color: var(--ubaya-maroon);
            box-shadow: 0 20px 42px -12px rgba(139, 13, 24, 0.28), 0 0 0 4px rgba(139, 13, 24, 0.1);
        }

        .skms-search-bar svg {
            color: var(--ubaya-maroon);
            flex-shrink: 0;
        }

        .skms-search-bar input {
            flex: 1;
            border: none;
            outline: none;
            padding: 14px 16px;
            font-size: 15.5px;
            color: var(--ubaya-heading);
            background: transparent;
            font-family: inherit;
        }

        .skms-search-bar input::placeholder {
            color: #9A8A8C;
        }

        .skms-search-submit {
            padding: 15px 34px;
            background-color: var(--ubaya-maroon);
            color: #FFFFFF;
            border: none;
            border-radius: 13px;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background-color 0.15s ease, transform 0.15s ease;
            white-space: nowrap;
        }

        .skms-search-submit:hover {
            background-color: var(--ubaya-maroon-dark);
            transform: translateY(-1px);
        }

        /* ── BENTO GRID BELOW SEARCH (JELAJAHI TOPIK + SERING DITANYAKAN) ─ */
        .skms-bento-grid {
            display: grid;
            grid-template-columns: 42% 1fr;
            gap: 28px;
            align-items: start;
        }

        .skms-bento-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            min-height: 36px;
        }

        .skms-bento-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--ubaya-heading);
            letter-spacing: -0.02em;
            margin: 0;
        }

        .skms-btn-see-all {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--ubaya-rose-pill);
            color: var(--ubaya-maroon);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .skms-btn-see-all:hover {
            background: var(--ubaya-maroon);
            color: #FFFFFF;
        }

        /* 2x2 Touchscreen Topic Tiles */
        .skms-topics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .skms-topic-tile {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            background: #FFFFFF;
            border: 1px solid var(--ubaya-border);
            border-radius: 18px;
            padding: 20px 18px;
            min-height: 104px;
            text-decoration: none;
            box-shadow: 0 6px 18px -10px rgba(31, 20, 21, 0.08);
            transition: all 0.18s ease;
        }

        .skms-topic-tile:hover {
            border-color: var(--ubaya-maroon);
            box-shadow: 0 12px 24px -10px rgba(139, 13, 24, 0.16);
            transform: translateY(-2px);
        }

        .skms-topic-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .skms-topic-icon.rose {
            background: var(--ubaya-rose-soft);
            color: var(--ubaya-maroon);
        }

        .skms-topic-icon.gold {
            background: var(--ubaya-gold-soft);
            color: var(--ubaya-gold-text);
        }

        .skms-topic-name {
            font-size: 14.5px;
            font-weight: 800;
            color: var(--ubaya-heading);
            margin-bottom: 3px;
            line-height: 1.25;
            padding-right: 16px;
        }

        .skms-topic-sub {
            font-size: 12px;
            color: var(--ubaya-muted);
            line-height: 1.35;
        }

        .skms-topic-arrow {
            position: absolute;
            top: 14px;
            right: 14px;
            color: var(--ubaya-maroon);
            opacity: 0.7;
            transition: transform 0.15s ease, opacity 0.15s ease;
        }

        .skms-topic-tile:hover .skms-topic-arrow {
            opacity: 1;
            transform: translateX(2px);
        }

        /* Popular FAQs Hierarchy (Featured #1 + 2 Side-by-Side) */
        .skms-featured-faq {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            background: linear-gradient(105deg, #FFFDFD 0%, #FDF4F5 100%);
            border: 1px solid var(--ubaya-rose-border);
            border-left: 4px solid var(--ubaya-gold);
            border-radius: 18px;
            padding: 20px 22px;
            margin-bottom: 14px;
            text-decoration: none;
            box-shadow: 0 8px 22px -12px rgba(139, 13, 24, 0.12);
            transition: all 0.18s ease;
        }

        .skms-featured-faq:hover {
            border-color: var(--ubaya-maroon);
            border-left-color: var(--ubaya-gold);
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -12px rgba(139, 13, 24, 0.2);
        }

        .skms-featured-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .skms-pill-gold {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            background: var(--ubaya-gold-soft);
            color: var(--ubaya-gold-text);
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .skms-pill-rose {
            display: inline-flex;
            align-items: center;
            padding: 4px 11px;
            background: var(--ubaya-rose-pill);
            color: var(--ubaya-maroon);
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .skms-featured-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--ubaya-heading);
            line-height: 1.35;
            margin: 0;
        }

        .skms-circle-arrow {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--ubaya-maroon);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.18s ease, background-color 0.18s ease;
        }

        .skms-featured-faq:hover .skms-circle-arrow {
            background: var(--ubaya-maroon-dark);
            transform: translateX(3px);
        }

        .skms-sub-faqs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .skms-sub-faq-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #FFFFFF;
            border: 1px solid var(--ubaya-border);
            border-radius: 16px;
            padding: 18px 20px;
            min-height: 122px;
            text-decoration: none;
            box-shadow: 0 6px 18px -10px rgba(31, 20, 21, 0.08);
            transition: all 0.18s ease;
        }

        .skms-sub-faq-card:hover {
            border-color: var(--ubaya-maroon);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -10px rgba(139, 13, 24, 0.15);
        }

        .skms-sub-faq-title {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--ubaya-heading);
            line-height: 1.4;
            margin: 10px 0 12px;
        }

        .skms-sub-faq-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            font-weight: 600;
            color: var(--ubaya-muted);
        }

        .skms-sub-faq-foot svg {
            color: var(--ubaya-maroon);
            transition: transform 0.15s ease;
        }

        .skms-sub-faq-card:hover .skms-sub-faq-foot svg {
            transform: translateX(3px);
        }

        /* ── MODE 2: PENCARIAN INFORMASI & FAQ (CRIMSON MAROON THEME) ──── */
        .skms-search-hero {
            background: linear-gradient(135deg, #FDF2F3 0%, #FAF3F0 100%);
            border-bottom: 1px solid var(--ubaya-rose-border);
            padding: 28px 24px 32px;
            margin-top: 14px;
        }

        .skms-search-hero-inner {
            max-width: 1280px;
            margin: 0 auto;
        }

        .skms-search-hero-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--ubaya-heading);
            letter-spacing: -0.025em;
            margin: 0 0 6px;
        }

        .skms-search-hero-sub {
            font-size: 14px;
            color: var(--ubaya-muted);
            margin: 0 0 20px;
        }

        .skms-main-container {
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
            padding: 28px 24px 56px;
            flex: 1;
        }

        .skms-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            color: var(--ubaya-muted);
            margin-bottom: 12px;
        }

        .skms-breadcrumb a {
            color: var(--ubaya-muted);
            text-decoration: none;
        }

        .skms-breadcrumb a:hover {
            color: var(--ubaya-maroon);
        }

        /* ── SEARCH INVALID WARNING ALERT ─────────────────────────────── */
        .skms-search-alert-card {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 14px;
            padding: 20px 22px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.08);
        }

        .skms-search-alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #FEF3C7;
            color: #D97706;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .skms-search-alert-content { flex: 1; }
        .skms-search-alert-title { font-size: 15.5px; font-weight: 700; color: #92400E; margin-bottom: 4px; }
        .skms-search-alert-desc { font-size: 13.5px; color: #78350F; line-height: 1.55; margin-bottom: 12px; }
        .skms-search-alert-desc code { background: #FEF3C7; padding: 2px 7px; border-radius: 6px; font-weight: 700; color: #B45309; font-family: inherit; }
        .skms-search-alert-tips { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; font-size: 12.5px; color: #92400E; }
        .skms-search-alert-tips a { display: inline-flex; align-items: center; padding: 5px 12px; border-radius: 999px; background: #FFFFFF; border: 1px solid #FCD34D; color: #92400E; text-decoration: none; font-weight: 600; transition: all 0.15s ease; }
        .skms-search-alert-tips a:hover { background: #FEF3C7; border-color: #B45309; color: #78350F; }

        /* ── CLIENT-SIDE SEARCH TOAST ──────────────────────────────────── */
        .skms-search-toast {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            background: #FFFFFF;
            border: 1px solid #FCD34D;
            border-left: 5px solid #D97706;
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            z-index: 9999;
            max-width: 92%;
            width: 480px;
            opacity: 0;
            pointer-events: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .skms-search-toast.is-show {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }

        .skms-search-toast-icon { width: 36px; height: 36px; border-radius: 9px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .skms-search-toast-body { flex: 1; }
        .skms-search-toast-title { font-size: 14px; font-weight: 700; color: var(--ubaya-heading); margin-bottom: 2px; }
        .skms-search-toast-msg { font-size: 12.5px; color: var(--ubaya-muted); line-height: 1.45; }
        .skms-search-toast-close { background: none; border: none; color: #9A8A8C; font-size: 20px; cursor: pointer; padding: 0 4px; }
        .skms-input-invalid { animation: skmsShake 0.35s ease; }
        @keyframes skmsShake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }

        .skms-results-head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 16px;
            margin-bottom: 22px;
            border-bottom: 1px solid var(--ubaya-border);
            flex-wrap: wrap;
        }

        .skms-results-head-left { display: flex; align-items: baseline; gap: 18px; flex-wrap: wrap; }
        .skms-results-h2 { font-size: 22px; font-weight: 800; color: var(--ubaya-heading); letter-spacing: -0.02em; margin: 0; }
        .skms-results-count { font-size: 13px; color: var(--ubaya-muted); }

        /* 3-Column Search Grid */
        .skms-three-col {
            display: grid;
            grid-template-columns: 275px 1fr 315px;
            gap: 24px;
            align-items: start;
        }

        /* ── COLUMN 1: SEARCHABLE COMBOBOX + CHIP TAG FILTER CARD ──────── */
        .skms-filter-card {
            background: #FFFFFF;
            border: 1px solid var(--ubaya-border);
            border-radius: 16px;
            padding: 20px;
            position: sticky;
            top: 98px;
        }

        .skms-filter-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 4px; }
        .skms-filter-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 700; color: var(--ubaya-heading); margin: 0; }
        .skms-filter-badge-count { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 999px; background: var(--ubaya-rose-pill); color: var(--ubaya-maroon); }
        .skms-filter-sub { font-size: 12px; color: var(--ubaya-muted); margin: 0 0 14px; }

        .skms-combobox-wrap { position: relative; margin-bottom: 12px; }
        .skms-cat-search-box { position: relative; }
        .skms-cat-search-box svg.search-ic { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #9A8A8C; pointer-events: none; }

        .skms-cat-search-input {
            width: 100%;
            padding: 10px 34px 10px 33px;
            background: var(--ubaya-bg);
            border: 1px solid var(--ubaya-border);
            border-radius: 10px;
            font-size: 12.5px;
            color: var(--ubaya-heading);
            font-family: inherit;
            outline: none;
            cursor: pointer;
            transition: border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
        }

        .skms-cat-search-input:focus,
        .skms-combobox-wrap.is-open .skms-cat-search-input {
            background: #FFFFFF;
            border-color: var(--ubaya-maroon);
            box-shadow: 0 0 0 3px rgba(139, 13, 24, 0.1);
            cursor: text;
        }

        .skms-combobox-chevron { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: var(--ubaya-muted); pointer-events: none; transition: transform 0.2s ease, color 0.2s ease; }
        .skms-combobox-wrap.is-open .skms-combobox-chevron { transform: translateY(-50%) rotate(180deg); color: var(--ubaya-maroon); }
        .skms-cat-search-clear { position: absolute; right: 28px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--ubaya-muted); cursor: pointer; padding: 2px 5px; font-size: 14px; line-height: 1; display: none; }

        .skms-combobox-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #FFFFFF;
            border: 1px solid var(--ubaya-rose-border);
            border-radius: 12px;
            box-shadow: 0 14px 32px -8px rgba(31, 20, 21, 0.16);
            padding: 6px;
            z-index: 60;
            display: none;
        }

        .skms-combobox-wrap.is-open .skms-combobox-dropdown { display: block; }
        .skms-cat-list-scroll { max-height: 215px; overflow-y: auto; padding-right: 2px; display: flex; flex-direction: column; gap: 3px; }
        .skms-cat-list-scroll::-webkit-scrollbar { width: 5px; }
        .skms-cat-list-scroll::-webkit-scrollbar-track { background: #F6F1F1; border-radius: 99px; }
        .skms-cat-list-scroll::-webkit-scrollbar-thumb { background: #DFC7CA; border-radius: 99px; }
        .skms-cat-list-scroll::-webkit-scrollbar-thumb:hover { background: var(--ubaya-maroon); }

        .skms-cat-option { display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 8px 9px; border-radius: 8px; cursor: pointer; user-select: none; transition: background 0.15s ease; font-size: 12.5px; color: var(--ubaya-text); }
        .skms-cat-option:hover { background: var(--ubaya-bg); }
        .skms-cat-option.is-checked { background: var(--ubaya-rose-soft); color: var(--ubaya-maroon); font-weight: 600; }
        .skms-cat-option-left { display: flex; align-items: center; gap: 9px; min-width: 0; }
        .skms-cat-option input[type="checkbox"] { accent-color: var(--ubaya-maroon); width: 15px; height: 15px; margin: 0; cursor: pointer; flex-shrink: 0; }
        .skms-cat-name { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .skms-cat-count-pill { font-size: 11px; font-weight: 600; color: var(--ubaya-muted); background: var(--ubaya-bg); padding: 1px 7px; border-radius: 999px; flex-shrink: 0; }
        .skms-cat-option.is-checked .skms-cat-count-pill { background: var(--ubaya-rose-pill); color: var(--ubaya-maroon); }
        .skms-cat-empty { display: none; text-align: center; padding: 14px 8px; font-size: 12px; color: var(--ubaya-muted); }
        .skms-dropdown-footer { display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: var(--ubaya-muted); margin-top: 6px; padding: 6px 8px 2px; border-top: 1px solid #F3ECEC; }
        .skms-dropdown-close-btn { background: none; border: none; padding: 0; font-size: 11.5px; font-weight: 700; color: var(--ubaya-maroon); cursor: pointer; font-family: inherit; }

        .skms-all-active-pill { display: flex; align-items: center; justify-content: space-between; padding: 8px 11px; background: var(--ubaya-rose-soft); border: 1px solid var(--ubaya-rose-border); border-radius: 9px; font-size: 12.5px; font-weight: 600; color: var(--ubaya-maroon); margin-bottom: 4px; }
        .skms-active-chips-wrap { margin-bottom: 4px; }
        .skms-active-chips-header { display: flex; align-items: center; justify-content: space-between; font-size: 11px; font-weight: 600; color: var(--ubaya-muted); margin-bottom: 7px; }
        .skms-clear-all-cats { background: none; border: none; padding: 0; font-size: 11px; font-weight: 600; color: var(--ubaya-maroon); cursor: pointer; font-family: inherit; }
        .skms-active-chips-list { display: flex; flex-wrap: wrap; gap: 6px; }
        .skms-active-chip { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: var(--ubaya-rose-soft); border: 1px solid var(--ubaya-rose-border); border-radius: 7px; font-size: 12px; font-weight: 600; color: var(--ubaya-maroon); }
        .skms-active-chip button { background: none; border: none; padding: 0; color: var(--ubaya-maroon); cursor: pointer; font-size: 14px; line-height: 1; display: inline-flex; align-items: center; }

        .skms-btn-apply-filter {
            width: 100%;
            margin-top: 12px;
            padding: 11px 16px;
            background: var(--ubaya-maroon);
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.15s ease;
        }

        .skms-btn-apply-filter:hover { background: var(--ubaya-maroon-dark); }

        /* ── COLUMN 2: SEARCH RESULT CARDS ─────────────────────────────── */
        .skms-results-col { display: flex; flex-direction: column; gap: 16px; }

        .skms-result-card {
            background: #FFFFFF;
            border: 1px solid var(--ubaya-border);
            border-radius: 16px;
            padding: 22px 24px 18px;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .skms-result-card:hover {
            border-color: var(--ubaya-rose-border);
            box-shadow: 0 10px 24px -10px rgba(139, 13, 24, 0.14);
        }

        .skms-result-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; flex-wrap: wrap; }
        .skms-result-badges { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .skms-result-doc-ic { width: 32px; height: 32px; border-radius: 8px; background: var(--ubaya-rose-soft); color: var(--ubaya-maroon); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .skms-cat-pill { display: inline-flex; align-items: center; padding: 4px 11px; background: var(--ubaya-rose-pill); color: var(--ubaya-maroon); border-radius: 999px; font-size: 11.5px; font-weight: 600; }
        .skms-relevance-pill { display: inline-flex; align-items: center; padding: 4px 11px; background: var(--ubaya-gold-soft); color: var(--ubaya-gold-text); border-radius: 999px; font-size: 11.5px; font-weight: 700; }
        .skms-result-title { font-size: 19px; font-weight: 800; color: var(--ubaya-heading); margin: 0 0 8px; line-height: 1.35; letter-spacing: -0.015em; }
        .skms-result-title a { color: inherit; text-decoration: none; }
        .skms-result-title a:hover { color: var(--ubaya-maroon); }
        .skms-result-excerpt { font-size: 13.5px; color: var(--ubaya-muted); line-height: 1.6; margin: 0 0 16px; }
        .skms-result-meta { display: flex; align-items: center; gap: 16px; font-size: 12px; color: var(--ubaya-muted); margin-bottom: 14px; }
        .skms-result-meta span { display: inline-flex; align-items: center; gap: 5px; }
        .skms-result-footer { padding-top: 13px; border-top: 1px solid #F3ECEC; }
        .skms-result-link { display: flex; align-items: center; justify-content: space-between; text-decoration: none; font-size: 13.5px; font-weight: 700; color: var(--ubaya-maroon); }
        .skms-result-link:hover { color: var(--ubaya-maroon-dark); }

        /* ── COLUMN 3: TOP FAQ TERPOPULER ──────────────────────────────── */
        .skms-top-card { background: #FFFFFF; border: 1px solid var(--ubaya-border); border-radius: 16px; padding: 20px; position: sticky; top: 98px; }
        .skms-top-head { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
        .skms-top-star { width: 36px; height: 36px; border-radius: 10px; background: var(--ubaya-gold-soft); color: var(--ubaya-gold-text); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .skms-top-title { font-size: 15px; font-weight: 700; color: var(--ubaya-heading); margin: 0 0 2px; }
        .skms-top-sub { font-size: 11.5px; color: var(--ubaya-muted); margin: 0; }
        .skms-top-list { display: flex; flex-direction: column; }
        .skms-top-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; border-top: 1px solid #F3ECEC; text-decoration: none; }
        .skms-top-item:first-child { border-top: none; padding-top: 4px; }
        .skms-top-item:last-child { padding-bottom: 2px; }
        .skms-top-rank { width: 24px; height: 24px; border-radius: 6px; background: var(--ubaya-bg); color: var(--ubaya-muted); font-size: 11.5px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
        .skms-top-item:first-child .skms-top-rank { background: var(--ubaya-gold-soft); color: var(--ubaya-gold-text); }
        .skms-top-item-q { font-size: 13px; font-weight: 700; color: var(--ubaya-heading); line-height: 1.4; margin-bottom: 4px; transition: color 0.15s ease; }
        .skms-top-item:hover .skms-top-item-q { color: var(--ubaya-maroon); }
        .skms-top-item-meta { font-size: 11px; color: var(--ubaya-muted); }

        /* ── FOOTER ────────────────────────────────────────────────────── */
        .skms-footer {
            background: #FFFFFF;
            border-top: 1px solid var(--ubaya-border);
            margin-top: auto;
        }

        .skms-footer-inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .skms-footer-brand { font-size: 13px; font-weight: 800; color: var(--ubaya-maroon); }
        .skms-footer-right { display: inline-flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 500; color: var(--ubaya-muted); }

        @media (max-width: 1080px) {
            .skms-bento-grid { grid-template-columns: 1fr; }
            .skms-three-col { grid-template-columns: 250px 1fr; }
            .skms-top-card { grid-column: 1 / -1; position: static; }
        }

        @media (max-width: 768px) {
            .skms-navbar-inner { display: flex; flex-wrap: wrap; justify-content: space-between; }
            .skms-three-col { grid-template-columns: 1fr; }
            .skms-filter-card { position: static; }
            .skms-topics-grid, .skms-sub-faqs-grid { grid-template-columns: 1fr; }
            .skms-hero-h1 { font-size: 28px; }
            .skms-photo-hero-content { padding: 34px 24px 56px; }
            .skms-navbar-shell { margin-top: 10px; padding: 0 14px; }
        }
    </style>
</head>
<body class="skms-student-body">

    <!-- ─── FLOATING TOP NAVBAR ────────────────────────────────────────────── -->
    <div class="skms-navbar-shell">
        <header class="skms-navbar">
            <div class="skms-navbar-inner">
                <a href="{{ route('skms.beranda') }}" class="skms-logo-link">
                    <svg width="34" height="34" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 7.5C4 6.11929 5.11929 5 6.5 5C10.5 5 14.5 6.5 17.5 9V14.5L21 17.5L17.5 20.5V29.5C14.5 27 10.5 25.5 6.5 25.5C5.11929 25.5 4 24.3807 4 23V7.5Z" fill="#8B0D18"/>
                        <path d="M32 7.5C32 6.11929 30.8807 5 29.5 5C25.5 5 21.5 6.5 18.5 9V14L22 17.5L18.5 21V29.5C21.5 27 25.5 25.5 29.5 25.5C30.8807 25.5 32 24.3807 32 23V7.5Z" fill="#E5A032"/>
                    </svg>
                    <div class="skms-logo-text">
                        <span class="skms-logo-title">SKMS UBAYA</span>
                        <span class="skms-logo-sub">Pusat Pengetahuan Mahasiswa</span>
                    </div>
                </a>

                <nav class="skms-nav-segment" aria-label="Navigasi Portal Mahasiswa">
                    <a href="{{ route('skms.beranda') }}" class="skms-seg-link {{ !$searched ? 'active' : '' }}">Beranda</a>
                    <a href="{{ route('skms.beranda', ['all' => 1]) }}" class="skms-seg-link {{ $searched ? 'active' : '' }}">Semua Informasi</a>
                </nav>

                <div class="skms-nav-right">
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
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                            <span>Log In</span>
                        </a>
                    @endif
                </div>
            </div>
        </header>
    </div>

    @if(!$searched)
        <!-- ===================================================================
             MODE 1: BERANDA CRIMSON IMMERSIVE (TOUCHSCREEN KIOSK)
        ==================================================================== -->
        <main class="skms-kiosk-container">
            @if(session('success') || session('info'))
                <div style="margin-bottom: 18px; padding: 13px 18px; border-radius: 12px; background: var(--ubaya-rose-soft); border: 1px solid var(--ubaya-rose-border); color: var(--ubaya-maroon); font-size: 13.5px; font-weight: 600;">
                    {{ session('success') ?? session('info') }}
                </div>
            @endif

            <!-- Photographic Rounded Hero + Overlapping White Search Bar -->
            <section class="skms-hero-stage">
                <div class="skms-photo-hero-card">
                    <div class="skms-photo-hero-bg"></div>
                    <div class="skms-photo-hero-scrim"></div>
                    <div class="skms-photo-hero-content">
                        <h1 class="skms-hero-h1">Temukan jawaban kuliahmu dalam hitungan detik.</h1>
                        <p class="skms-hero-desc">
                            Panduan resmi akademik, keuangan, hingga kemahasiswaan Universitas Surabaya.
                        </p>
                    </div>
                </div>

                <div class="skms-floating-search-wrap">
                    <form action="{{ route('skms.beranda') }}" method="GET" class="skms-search-bar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" name="search" placeholder="Ketik pertanyaanmu di sini (misal: cuti, KRS, SPP, wisuda)..." autocomplete="off" autofocus>
                        <button type="submit" class="skms-search-submit">Cari FAQ</button>
                    </form>
                </div>
            </section>

            <!-- 2-Column Tactile Bento Section -->
            <section class="skms-bento-grid">
                <!-- Left Column: 2x2 Jelajahi Topik -->
                <div>
                    <div class="skms-bento-head">
                        <h2 class="skms-bento-title">Jelajahi Topik</h2>
                    </div>

                    @php
                        $topCategories = $categories->sortByDesc('faqs_count')->take(4)->values();
                    @endphp

                    <div class="skms-topics-grid">
                        @forelse($topCategories as $idx => $cat)
                            <a href="{{ route('skms.beranda', ['categories' => [$cat->id]]) }}" class="skms-topic-tile">
                                <div class="skms-topic-icon {{ $idx === 1 ? 'gold' : 'rose' }}">
                                    @if($idx === 0)
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                        </svg>
                                    @elseif($idx === 1)
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                            <line x1="2" y1="10" x2="22" y2="10"></line>
                                        </svg>
                                    @elseif($idx === 2)
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                        </svg>
                                    @else
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div style="min-width: 0;">
                                    <div class="skms-topic-name">{{ $cat->nama }}</div>
                                    <div class="skms-topic-sub">{{ $cat->faqs_count }} informasi resmi</div>
                                </div>
                                <svg class="skms-topic-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        @empty
                            <div style="grid-column: 1 / -1; background: #FFFFFF; border: 1px solid var(--ubaya-border); border-radius: 16px; padding: 24px; text-align: center; color: var(--ubaya-muted); font-size: 13.5px;">
                                Belum ada kategori topik tersedia.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Right Column: Sering ditanyakan mahasiswa -->
                <div>
                    <div class="skms-bento-head">
                        <h2 class="skms-bento-title">Sering ditanyakan mahasiswa</h2>
                        <a href="{{ route('skms.beranda', ['all' => 1]) }}" class="skms-btn-see-all">
                            <span>Lihat semua</span>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>

                    @php
                        $featuredFaq = $topFaqs->first();
                        $secondaryFaqs = $topFaqs->slice(1, 2);
                    @endphp

                    @if($featuredFaq)
                        <a href="{{ route('skms.faq.show', $featuredFaq->id) }}" class="skms-featured-faq">
                            <div style="min-width: 0;">
                                <div class="skms-featured-badges">
                                    <span class="skms-pill-gold">★ Paling Populer &bull; {{ number_format($featuredFaq->views) }} Dilihat</span>
                                    <span class="skms-pill-rose">{{ $featuredFaq->categories->first()?->nama ?? 'Umum' }}</span>
                                </div>
                                <h3 class="skms-featured-title">{{ $featuredFaq->pertanyaan }}</h3>
                            </div>
                            <span class="skms-circle-arrow">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                        </a>

                        @if($secondaryFaqs->isNotEmpty())
                            <div class="skms-sub-faqs-grid">
                                @foreach($secondaryFaqs as $sIdx => $sFaq)
                                    <a href="{{ route('skms.faq.show', $sFaq->id) }}" class="skms-sub-faq-card">
                                        <div>
                                            <span class="{{ $sIdx === 1 ? 'skms-pill-gold' : 'skms-pill-rose' }}">
                                                {{ $sFaq->categories->first()?->nama ?? 'Umum' }}
                                            </span>
                                            <div class="skms-sub-faq-title">{{ $sFaq->pertanyaan }}</div>
                                        </div>
                                        <div class="skms-sub-faq-foot">
                                            <span>{{ number_format($sFaq->views) }} Dilihat</span>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                                <polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div style="background: #FFFFFF; border: 1px solid var(--ubaya-border); border-radius: 16px; padding: 32px; text-align: center; color: var(--ubaya-muted); font-size: 13.5px;">
                            Belum ada informasi FAQ yang diterbitkan.
                        </div>
                    @endif
                </div>
            </section>
        </main>

    @else
        <!-- ===================================================================
             MODE 2: PENCARIAN INFORMASI & FAQ
        ==================================================================== -->
        <section class="skms-search-hero">
            <div class="skms-search-hero-inner">
                <h1 class="skms-search-hero-title">Pencarian Informasi &amp; FAQ</h1>
                <p class="skms-search-hero-sub">Cari pertanyaan atau persempit pencarian menggunakan filter kategori.</p>

                <form action="{{ route('skms.beranda') }}" method="GET" class="skms-search-bar" style="box-shadow: 0 8px 24px -10px rgba(74, 7, 13, 0.12);">
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
                    <button type="submit" class="skms-search-submit" style="padding: 12px 26px;">Cari FAQ</button>
                </form>
            </div>
        </section>

        <main class="skms-main-container">
            <div class="skms-breadcrumb">
                <a href="{{ route('skms.beranda') }}">Beranda</a>
                <span>/</span>
                <span style="color: var(--ubaya-heading); font-weight: 600;">Pencarian FAQ</span>
            </div>

            @if(!empty($searchWarning))
                <div class="skms-search-alert-card">
                    <div class="skms-search-alert-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <div class="skms-search-alert-content">
                        <div class="skms-search-alert-title">Kata kunci pencarian belum jelas</div>
                        <div class="skms-search-alert-desc">
                            Kamu memasukkan kata kunci yang hanya berupa angka atau tanda baca/simbol (<code>{{ $keyword }}</code>). Agar sistem dapat menemukan jawaban yang tepat, silakan ketik kata atau nama topik yang ingin kamu cari.
                        </div>
                        <div class="skms-search-alert-tips">
                            <span>Coba topik berikut:</span>
                            <a href="{{ route('skms.beranda', ['search' => 'Cuti akademik']) }}">Cuti akademik</a>
                            <a href="{{ route('skms.beranda', ['search' => 'KRS']) }}">KRS</a>
                            <a href="{{ route('skms.beranda', ['search' => 'Pembayaran SPP']) }}">Pembayaran SPP</a>
                            <a href="{{ route('skms.beranda', ['search' => 'Wisuda']) }}">Wisuda</a>
                        </div>
                    </div>
                </div>
            @endif

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
                    <a href="{{ route('skms.beranda', ['all' => 1]) }}" style="font-size: 12.5px; font-weight: 700; color: var(--ubaya-maroon); text-decoration: none;">
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
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--ubaya-maroon)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg>
                                <span>Filter kategori</span>
                            </h3>
                            <span id="selectedCountBadge" class="skms-filter-badge-count" style="{{ empty($selectedCategories) ? 'display:none;' : '' }}">
                                {{ count($selectedCategories) }} dipilih
                            </span>
                        </div>
                        <p class="skms-filter-sub">Persempit pencarianmu.</p>

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

                            <div class="skms-combobox-dropdown" id="catComboboxDropdown">
                                <label class="skms-cat-option {{ empty($selectedCategories) ? 'is-checked' : '' }}" id="allCategoriesOption" style="margin-bottom: 4px; border-bottom: 1px solid #F3ECEC; border-radius: 8px 8px 0 0;">
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

                        <div id="allActiveStatusPill" class="skms-all-active-pill" style="{{ !empty($selectedCategories) ? 'display:none;' : '' }}">
                            <span style="display: inline-flex; align-items: center; gap: 6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Semua kategori
                            </span>
                            <span style="font-size: 11px; background: var(--ubaya-rose-pill); padding: 1px 7px; border-radius: 999px;">{{ $categories->sum('faqs_count') }}</span>
                        </div>

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
                                    {{ number_format($faq->views) }} Dilihat
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
                        <div style="background: #FFFFFF; border: 1px solid var(--ubaya-border); border-radius: 16px; padding: 36px 24px; text-align: center;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--ubaya-rose-soft); color: var(--ubaya-maroon); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 700; color: var(--ubaya-heading); margin: 0 0 6px;">Tidak ada FAQ yang cocok</h3>
                            <p style="font-size: 13px; color: var(--ubaya-muted); margin: 0 auto 20px; max-width: 440px; line-height: 1.55;">
                                Kami tidak menemukan pertanyaan yang sesuai dengan filter atau kata kunci Anda. Anda dapat mengajukan pertanyaan ini langsung kepada staf kami.
                            </p>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                <a href="{{ route('skms.tanya', array_filter(['q' => $keyword])) }}" style="display: inline-flex; align-items: center; gap: 7px; padding: 11px 18px; background: var(--ubaya-maroon); color: #FFFFFF; border-radius: 10px; font-size: 13px; font-weight: 700; text-decoration: none;">
                                    <span>Ajukan pertanyaan baru ke staf</span>
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                                <a href="{{ route('skms.beranda', ['all' => 1]) }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 11px 16px; background: #FFFFFF; border: 1px solid var(--ubaya-border); color: var(--ubaya-heading); border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none;">
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
                                        {{ number_format($top->views) }} Dilihat &bull; {{ $top->categories->first()?->nama ?? 'Umum' }}
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div style="font-size: 12.5px; color: var(--ubaya-muted); padding: 8px 0;">
                                Belum ada data FAQ populer.
                            </div>
                        @endforelse
                    </div>
                </aside>
            </div>
        </main>
    @endif

    <!-- ─── MINIMAL KIOSK FOOTER ───────────────────────────────────────────── -->
    <footer class="skms-footer">
        <div class="skms-footer-inner">
            <div class="skms-footer-brand">SKMS UBAYA &mdash; Universitas Surabaya</div>
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

            function openDropdown() { if (comboboxWrap) comboboxWrap.classList.add('is-open'); }
            function closeDropdown() { if (comboboxWrap) comboboxWrap.classList.remove('is-open'); }

            function syncCategoryState() {
                const checkedBoxes = checkboxes.filter(cb => cb.checked);
                const checkedCount = checkedBoxes.length;

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

            document.addEventListener('click', function (e) {
                if (comboboxWrap && !comboboxWrap.contains(e.target)) closeDropdown();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeDropdown();
            });

            checkboxes.forEach(cb => cb.addEventListener('change', syncCategoryState));

            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    if (this.checked) checkboxes.forEach(cb => { cb.checked = false; });
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
            const IDLE_TIMEOUT_MS = 180000;
            let idleTimer = null;

            function resetIdleTimer() {
                if (idleTimer) clearTimeout(idleTimer);
                idleTimer = setTimeout(function () {
                    window.location.reload();
                }, IDLE_TIMEOUT_MS);
            }

            ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(function (evt) {
                document.addEventListener(evt, resetIdleTimer, { passive: true });
            });

            resetIdleTimer();
        })();

        /* ── Validasi Input Pencarian FAQ (Mencegah Angka/Simbol Murni) ─── */
        (function () {
            const toast = document.getElementById('searchWarningToast');
            const toastClose = document.getElementById('searchToastClose');
            let toastTimer = null;

            function showSearchWarning(input) {
                if (input) {
                    input.classList.add('skms-input-invalid');
                    input.focus();
                    input.select();
                    setTimeout(() => input.classList.remove('skms-input-invalid'), 1200);
                }

                if (toast) {
                    toast.classList.add('is-show');
                    if (toastTimer) clearTimeout(toastTimer);
                    toastTimer = setTimeout(() => toast.classList.remove('is-show'), 5000);
                }
            }

            if (toastClose) {
                toastClose.addEventListener('click', function () {
                    if (toast) toast.classList.remove('is-show');
                });
            }

            document.querySelectorAll('form.skms-search-bar').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    const input = form.querySelector('input[name="search"]');
                    if (!input) return;
                    const val = input.value.trim();
                    if (val === '') return;

                    const isOnlyNumbers = /^[0-9\s]+$/.test(val);
                    const letterMatches = val.match(/[\p{L}]/gu);
                    const letterCount = letterMatches ? letterMatches.length : 0;

                    if (isOnlyNumbers || letterCount < 2) {
                        e.preventDefault();
                        showSearchWarning(input);
                    }
                });
            });
        })();
    </script>

    <!-- ── Floating UI Toast Notifikasi Pencarian Awam ── -->
    <div id="searchWarningToast" class="skms-search-toast" role="alert">
        <div class="skms-search-toast-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <div class="skms-search-toast-body">
            <div class="skms-search-toast-title">Kata kunci belum jelas</div>
            <div class="skms-search-toast-msg">
                Silakan ketik kata atau kalimat (misal: <em>cuti</em>, <em>krs</em>, <em>spp</em>), jangan hanya angka atau tanda baca/simbol ya.
            </div>
        </div>
        <button type="button" id="searchToastClose" class="skms-search-toast-close" title="Tutup">&times;</button>
    </div>
</body>
</html>
