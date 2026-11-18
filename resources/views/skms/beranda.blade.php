<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKMS UBAYA — Pusat Informasi & FAQ Mahasiswa</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* ===============================================================
           SKMS PUBLIC PORTAL — CLEAN FIGMA UI STYLES
        =============================================================== */
        :root {
            --primary: #6366F1;
            --primary-hover: #4F46E5;
            --primary-light: #EEF2FF;
            --primary-border: #C7D2FE;
            --bg-page: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
        }

        body.skms-body {
            min-height: 100vh;
            background: linear-gradient(135deg, #F0F4FF 0%, #F8FAFC 50%, #FDF4FF 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #334155;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        /* ── Hero Wrapper with Oval Blue Background (Warna UI Petugas) ── */
        .skms-hero-wrapper {
            position: relative;
            background: linear-gradient(135deg, #6366F1 0%, #4F46E5 45%, #8B5CF6 100%);
            border-radius: 0 0 50% 50% / 0 0 70px 70px;
            padding: 24px 24px 64px;
            box-shadow: 0 14px 40px -10px rgba(99, 102, 241, 0.4);
        }

        .skms-hero-wrapper.searched-compact {
            padding: 20px 24px 40px;
            border-radius: 0 0 50% 50% / 0 0 45px 45px;
        }

        /* ── Top Navigation Bar (Transparan di Atas Oval Biru) ─────────── */
        .skms-top-nav {
            margin-bottom: 36px;
        }

        .skms-hero-wrapper.searched-compact .skms-top-nav {
            margin-bottom: 18px;
        }

        .skms-nav-container {
            max-width: 1140px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .skms-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #FFFFFF;
        }

        .skms-brand-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .skms-brand-text {
            display: flex;
            flex-direction: column;
        }

        .skms-brand-title {
            font-size: 18px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.4px;
            line-height: 1.1;
        }

        .skms-brand-subtitle {
            font-size: 11.5px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            letter-spacing: 0.2px;
        }

        /* Identitas Mahasiswa & Tombol Masuk */
        .skms-nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .skms-student-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 16px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            color: #1E293B;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
        }

        .skms-btn-logout-nrp {
            background: none;
            border: none;
            color: #EF4444;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background 0.15s ease;
            font-family: inherit;
        }

        .skms-btn-logout-nrp:hover {
            background: #FEE2E2;
        }

        .skms-btn-login-nrp {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            background: rgba(255, 255, 255, 0.95);
            color: #4F46E5;
            border-radius: 9999px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(8px);
            transition: all 0.2s ease;
        }

        .skms-btn-login-nrp:hover {
            background: #FFFFFF;
            color: #4338CA;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
        }

        .skms-btn-nav-home {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #FFFFFF;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .skms-btn-nav-home:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: translateY(-1px);
            color: #FFFFFF;
        }

        /* ── Hero Center Content ───────────────────────────────────────── */
        .skms-hero {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .skms-hero-container {
            max-width: 760px;
            margin: 0 auto;
        }

        .skms-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            color: #FFFFFF;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
        }

        .skms-hero-title {
            font-size: 38px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.8px;
            margin: 0 0 12px;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .skms-hero-wrapper.searched-compact .skms-hero-title {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .skms-hero-subtitle {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.9);
            margin: 0 0 32px;
            line-height: 1.6;
        }

        .skms-hero-wrapper.searched-compact .skms-hero-subtitle {
            font-size: 13.5px;
            margin-bottom: 18px;
            color: rgba(255, 255, 255, 0.85);
        }

        /* ── Search Bar Box ────────────────────────────────────────────── */
        .skms-search-form {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 20px 45px -10px rgba(79, 70, 229, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.4);
            transition: all 0.25s ease;
            overflow: hidden;
            max-width: 680px;
            margin: 0 auto;
        }

        .skms-search-form:focus-within {
            box-shadow: 0 24px 50px -10px rgba(79, 70, 229, 0.45), 0 0 0 3px rgba(255, 255, 255, 0.8);
            transform: translateY(-1px);
        }

        .skms-search-icon {
            padding-left: 20px;
            color: #94A3B8;
            display: flex;
            align-items: center;
        }

        .skms-search-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 17px 16px;
            font-size: 15.5px;
            font-family: inherit;
            color: #1E293B;
            background: transparent;
        }

        .skms-search-input::placeholder {
            color: #94A3B8;
        }

        .skms-search-btn {
            margin: 6px;
            padding: 12px 26px;
            background: linear-gradient(135deg, #6366F1, #4F46E5);
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .skms-search-btn:hover {
            background: linear-gradient(135deg, #4F46E5, #4338CA);
            transform: translateY(-1px);
        }

        .skms-search-hints {
            margin-top: 18px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
        }

        .skms-search-hints span {
            color: #FFFFFF;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
            padding: 3px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-block;
            margin: 2px;
        }

        .skms-search-hints span:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: translateY(-1px);
        }
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .skms-search-btn:hover {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            transform: translateY(-1px);
        }

        .skms-search-hints {
            margin-top: 16px;
            font-size: 13px;
            color: #94A3B8;
        }

        .skms-search-hints span {
            color: #64748B;
            font-weight: 500;
        }

        /* ── Alert Flash Messages ──────────────────────────────────────── */
        .skms-alert-box {
            max-width: 680px;
            margin: 0 auto 20px;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .skms-alert-success {
            background-color: #ECFDF5;
            border: 1px solid #A7F3D0;
            color: #065F46;
        }

        .skms-alert-info {
            background-color: #EFF6FF;
            border: 1px solid #BFDBFE;
            color: #1E40AF;
        }

        /* ── Search Results Main Section ───────────────────────────────── */
        .skms-results-wrapper {
            flex: 1;
            max-width: 1180px;
            width: 100%;
            margin: 32px auto 60px;
            padding: 0 24px;
            box-sizing: border-box;
        }

        .skms-results-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 12px;
        }

        .skms-results-title {
            font-size: 15px;
            color: #475569;
        }

        .skms-results-title strong {
            color: var(--text-dark);
            font-weight: 700;
        }

        .skms-btn-clear-search {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: color 0.15s ease;
        }

        .skms-btn-clear-search:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .skms-results-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .skms-btn-home-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            background: #FFFFFF;
            border: 1.5px solid #CBD5E1;
            border-radius: 10px;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .skms-btn-home-action:hover {
            border-color: #6366F1;
            background: #EEF2FF;
            color: #4F46E5;
            transform: translateY(-1px);
        }

        /* ── 2-Column Layout (Left: Filter + FAQs, Right: Top FAQs) ────── */
        .skms-search-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 28px;
            align-items: start;
        }

        @media (max-width: 960px) {
            .skms-search-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── Left Column: Category Combobox Filter ─────────────────────── */
        .skms-filter-box {
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 4px 16px -4px rgba(0, 0, 0, 0.04);
        }

        .skms-filter-box-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .skms-filter-box-subtitle {
            font-size: 12.5px;
            color: var(--text-muted);
            margin: 0 0 14px;
        }

        /* Combobox UI */
        .skms-combobox-wrapper {
            position: relative;
        }

        .skms-combobox-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .skms-combobox-icon {
            position: absolute;
            left: 14px;
            color: #94A3B8;
            pointer-events: none;
        }

        .skms-combobox-input {
            width: 100%;
            padding: 11px 40px 11px 38px;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            font-size: 13.5px;
            font-family: inherit;
            color: var(--text-dark);
            outline: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .skms-combobox-input:focus {
            background: #FFFFFF;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .skms-combobox-arrow {
            position: absolute;
            right: 14px;
            color: #94A3B8;
            pointer-events: none;
            transition: transform 0.2s ease;
        }

        .skms-combobox-wrapper.open .skms-combobox-arrow {
            transform: rotate(180deg);
            color: var(--primary);
        }

        /* Combobox Dropdown List */
        .skms-combobox-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            max-height: 250px;
            overflow-y: auto;
            z-index: 100;
            display: none;
            padding: 6px;
        }

        .skms-combobox-wrapper.open .skms-combobox-dropdown {
            display: block;
        }

        .skms-combobox-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13.5px;
            color: #334155;
            cursor: pointer;
            transition: background 0.15s ease;
            user-select: none;
        }

        .skms-combobox-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .skms-combobox-item.selected {
            background: var(--primary-light);
            color: var(--primary-hover);
            font-weight: 600;
        }

        .skms-combobox-item-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .skms-combobox-checkbox {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1.5px solid #CBD5E1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #FFFFFF;
            color: transparent;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .skms-combobox-item.selected .skms-combobox-checkbox {
            background: var(--primary);
            border-color: var(--primary);
            color: #FFFFFF;
        }

        .skms-combobox-count {
            font-size: 11px;
            padding: 2px 7px;
            background: #F1F5F9;
            color: #64748B;
            border-radius: 9999px;
            font-weight: 600;
        }

        .skms-combobox-item.selected .skms-combobox-count {
            background: #E0E7FF;
            color: #4338CA;
        }

        .skms-combobox-empty {
            padding: 14px;
            text-align: center;
            font-size: 13px;
            color: #94A3B8;
        }

        /* Selected Categories Badges / Tags */
        .skms-selected-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .skms-tag-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: var(--primary-light);
            border: 1px solid var(--primary-border);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: var(--primary);
        }

        .skms-tag-remove {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.15);
            color: var(--primary);
            transition: all 0.15s ease;
        }

        .skms-tag-remove:hover {
            background: #EF4444;
            color: #FFFFFF;
        }

        .skms-filter-actions-bar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #F1F5F9;
        }

        .skms-btn-apply-filter {
            padding: 8px 18px;
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .skms-btn-apply-filter:hover {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
        }

        .skms-btn-reset-filter {
            padding: 8px 14px;
            color: #64748B;
            font-size: 13px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.15s ease;
        }

        .skms-btn-reset-filter:hover {
            background: #FEF2F2;
            color: #EF4444;
        }

        /* ── Left Column: FAQ Accordion List ──────────────────────────── */
        .skms-faq-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .skms-faq-item {
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .skms-faq-item:hover {
            border-color: #C7D2FE;
            box-shadow: 0 4px 16px -4px rgba(99, 102, 241, 0.08);
        }

        .skms-faq-item.open {
            border-color: #A5B4FC;
            box-shadow: 0 8px 24px -8px rgba(99, 102, 241, 0.12);
        }

        .skms-faq-question-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 18px 22px;
            cursor: pointer;
            user-select: none;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            font-family: inherit;
            transition: background 0.15s ease;
        }

        .skms-faq-question-btn:hover {
            background: #FAFAFF;
        }

        .skms-faq-q-left {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            flex: 1;
            min-width: 0;
        }

        .skms-faq-q-badge {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 13px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .skms-faq-q-title-group {
            flex: 1;
            min-width: 0;
        }

        .skms-faq-q-text {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.45;
            margin-bottom: 6px;
        }

        .skms-faq-q-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .skms-category-pill {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            background: #F1F5F9;
            color: #475569;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
        }

        /* View Counter Pill */
        .skms-view-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            color: #64748B;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 6px;
        }

        .skms-view-pill svg {
            color: #94A3B8;
        }

        .skms-faq-chevron {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .skms-faq-item.open .skms-faq-chevron {
            background: var(--primary-light);
            color: var(--primary);
            transform: rotate(180deg);
        }

        /* FAQ Answer Content */
        .skms-faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease;
            padding: 0 22px;
        }

        .skms-faq-item.open .skms-faq-answer {
            max-height: 1200px;
            padding: 0 22px 20px;
        }

        .skms-faq-answer-inner {
            padding-top: 14px;
            border-top: 1px solid #F1F5F9;
        }

        .skms-faq-answer-text {
            font-size: 14px;
            color: #334155;
            line-height: 1.75;
        }

        .skms-faq-answer-footer {
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px dashed #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .skms-faq-view-detail {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            color: #64748B;
        }

        .skms-faq-view-detail strong {
            color: var(--text-dark);
        }

        .skms-faq-score-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            background: #ECFDF5;
            color: #059669;
            font-size: 11px;
            font-weight: 700;
            border-radius: 9999px;
        }

        /* ── Tombol Membantu 👍 & Counter ─────────────────────────── */
        .skms-helpful-action-group {
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .skms-btn-helpful {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            background: #FFFFFF;
            border: 1.5px solid #CBD5E1;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            user-select: none;
        }

        .skms-btn-helpful:hover {
            border-color: #6366F1;
            background: #EEF2FF;
            color: #4F46E5;
            transform: translateY(-1px);
        }

        .skms-btn-helpful.active {
            background: linear-gradient(135deg, #4F46E5, #6366F1);
            border-color: #4338CA;
            color: #FFFFFF;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
        }

        .skms-helpful-count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 9999px;
            background: #F1F5F9;
            color: #475569;
            font-size: 11.5px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .skms-btn-helpful.active .skms-helpful-count-badge {
            background: #FFFFFF;
            color: #4F46E5;
        }

        .skms-helpful-caption {
            font-size: 13px;
            color: #64748B;
        }

        .skms-helpful-caption strong {
            color: #1E293B;
            font-weight: 700;
        }

        .skms-helpful-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }

        .skms-helpful-pill:hover {
            background: #EEF2FF;
            border-color: #A5B4FC;
            color: #4F46E5;
            transform: scale(1.03);
        }

        .skms-helpful-pill.active {
            background: linear-gradient(135deg, #4F46E5, #6366F1);
            border-color: #4338CA;
            color: #FFFFFF;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
        }

        .skms-helpful-pill.active span {
            color: #FFFFFF;
        }

        @keyframes skmsBounceThumb {
            0% { transform: scale(1) rotate(0deg); }
            30% { transform: scale(1.3) rotate(-15deg); }
            60% { transform: scale(1.15) rotate(10deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        .skms-helpful-pill.active .skms-helpful-icon,
        .skms-btn-helpful.active .skms-helpful-icon {
            display: inline-block;
            animation: skmsBounceThumb 0.4s ease-out;
        }

        /* ── Right Column: Top FAQ Widget Card ─────────────────────────── */
        .skms-top-faq-card {
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 86px;
        }

        .skms-top-faq-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid #F1F5F9;
        }

        .skms-top-faq-icon {
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

        .skms-top-faq-title-wrap {
            display: flex;
            flex-direction: column;
        }

        .skms-top-faq-title {
            font-size: 14.5px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }

        .skms-top-faq-subtitle {
            font-size: 11.5px;
            color: var(--text-muted);
            margin: 2px 0 0;
        }

        .skms-top-faq-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .skms-top-faq-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            background: #F8FAFC;
            border: 1px solid #F1F5F9;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .skms-top-faq-item:hover {
            background: var(--primary-light);
            border-color: var(--primary-border);
            transform: translateY(-1px);
        }

        .skms-top-rank {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            background: #E2E8F0;
            color: #475569;
            font-size: 11px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .skms-top-faq-item:nth-child(1) .skms-top-rank {
            background: #F59E0B;
            color: #FFFFFF;
        }

        .skms-top-faq-item:nth-child(2) .skms-top-rank {
            background: #64748B;
            color: #FFFFFF;
        }

        .skms-top-faq-item:nth-child(3) .skms-top-rank {
            background: #B45309;
            color: #FFFFFF;
        }

        .skms-top-item-content {
            flex: 1;
            min-width: 0;
        }

        .skms-top-item-question {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.4;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .skms-top-item-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
            color: #64748B;
        }

        .skms-top-views-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #6366F1;
            font-weight: 600;
        }

        /* ── Empty State ───────────────────────────────────────────────── */
        .skms-empty-state {
            text-align: center;
            padding: 60px 24px;
            background: #FFFFFF;
            border: 1px dashed var(--border-color);
            border-radius: 16px;
        }

        .skms-empty-icon {
            width: 64px;
            height: 64px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--primary);
        }

        .skms-empty-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 6px;
        }

        .skms-empty-desc {
            font-size: 13.5px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.5;
        }

        /* ── Footer ────────────────────────────────────────────────────── */
        .skms-footer {
            margin-top: auto;
            text-align: center;
            padding: 24px;
            border-top: 1px solid var(--border-color);
            background: #FFFFFF;
            font-size: 13px;
            color: var(--text-muted);
        }

        .skms-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .skms-footer a:hover {
            text-decoration: underline;
        }

        /* ── Modal Pop-up untuk Top FAQ Preview ────────────────────────── */
        .skms-modal-backdrop {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .skms-modal-box {
            background: #FFFFFF;
            border-radius: 16px;
            max-width: 600px;
            width: 100%;
            padding: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            position: relative;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
        }

        .skms-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-color);
        }

        .skms-modal-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.4;
            margin: 0;
        }

        .skms-modal-close {
            background: #F1F5F9;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #64748B;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .skms-modal-close:hover {
            background: #FEE2E2;
            color: #EF4444;
        }

        .skms-modal-body {
            overflow-y: auto;
            font-size: 14px;
            line-height: 1.7;
            color: #334155;
        }

        .skms-modal-footer {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12.5px;
            color: #64748B;
        }
    </style>
</head>
<body class="skms-body">

    {{-- ══════════════════════════════════════════════════════════════
         HERO SECTION WITH BLUE OVAL BACKGROUND (WARNA UI PETUGAS)
    ══════════════════════════════════════════════════════════════ --}}
    <div class="skms-hero-wrapper {{ $searched ? 'searched-compact' : '' }}">
        {{-- Top Navigation Bar --}}
        <header class="skms-top-nav">
            <div class="skms-nav-container">
                <a href="{{ route('skms.beranda') }}" class="skms-brand">
                    <div class="skms-brand-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <div class="skms-brand-text">
                        <span class="skms-brand-title">SKMS UBAYA</span>
                        <span class="skms-brand-subtitle">Student Knowledge Management System</span>
                    </div>
                </a>

                <div class="skms-nav-actions">
                    @if($searched)
                        <a href="{{ route('skms.beranda') }}" class="skms-btn-nav-home" title="Kembali ke Beranda Awal">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Beranda (Home)</span>
                        </a>
                    @endif
                    @if(!empty($studentNrp))
                        <div class="skms-student-badge">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>NRP: {{ $studentNrp }}</span>
                            <form action="{{ route('skms.logout') }}" method="POST" style="margin: 0; display: inline-flex;">
                                @csrf
                                <button type="submit" class="skms-btn-logout-nrp" title="Keluar sesi mahasiswa">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('skms.login') }}" class="skms-btn-login-nrp">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                <line x1="7" y1="8" x2="13" y2="8"></line>
                                <line x1="7" y1="12" x2="17" y2="12"></line>
                            </svg>
                            <span>Masuk Mahasiswa (NRP)</span>
                        </a>
                    @endif
                </div>
            </div>
        </header>

        {{-- Hero Center Content --}}
        <section class="skms-hero">
            <div class="skms-hero-container">

                @if(session('success'))
                    <div class="skms-alert-box skms-alert-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="skms-alert-box skms-alert-info">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @if(!$searched)
                    <div class="skms-hero-badge">Pusat Bantuan Akademik &amp; Kemahasiswaan</div>
                    <h1 class="skms-hero-title">SKMS UBAYA</h1>
                    <p class="skms-hero-subtitle">
                        Temukan solusi resmi dan informasi seputar kegiatan hingga layanan kemahasiswaan </br>Universitas Surabaya.
                    </p>
                @else
                    <h2 class="skms-hero-title">Pencarian Informasi &amp; FAQ</h2>
                    <p class="skms-hero-subtitle">Cari pertanyaan atau persempit pencarian menggunakan filter kategori</p>
                @endif

                {{-- Form Pencarian FAQ Utama --}}
                <form id="skmsMainSearchForm" action="{{ route('skms.beranda') }}" method="GET" class="skms-search-form">
                    <div class="skms-search-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <input type="text" 
                           id="mainSearchInput"
                           name="search" 
                           value="{{ $keyword }}" 
                           class="skms-search-input" 
                           placeholder="Ketik pertanyaan Anda di sini, misal: cara mengajukan cuti akademik..." 
                           required
                           autofocus
                           autocomplete="off">

                    {{-- Hidden input kategori jika sebelumnya sudah dipilih --}}
                    @foreach($selectedCategories as $catId)
                        <input type="hidden" name="categories[]" value="{{ $catId }}" class="active-category-hidden">
                    @endforeach

                    <button type="submit" class="skms-search-btn">
                        Cari FAQ
                    </button>
                </form>

            </div>
        </section>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         HASIL PENCARIAN (HANYA MUNCUL KETIKA SUDAH SEARCH)
    ══════════════════════════════════════════════════════════════ --}}
    @if($searched)
        <main class="skms-results-wrapper">

            {{-- Info Bar Hasil Pencarian --}}
            <div class="skms-results-header">
                <div class="skms-results-title">
                    @if($keyword !== '')
                        Ditemukan <strong>{{ $results->count() }}</strong> hasil untuk "<strong>{{ $keyword }}</strong>"
                    @else
                        Menampilkan <strong>{{ $results->count() }}</strong> FAQ
                    @endif
                    @if(count($selectedCategories) > 0)
                        pada <strong>{{ count($selectedCategories) }}</strong> kategori
                    @endif
                </div>

                <div class="skms-results-header-actions">
                    @if(count($selectedCategories) > 0)
                        <a href="{{ route('skms.beranda', ['search' => $keyword]) }}" class="skms-btn-clear-search" onclick="clearFilterKeepSearch(event)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            <span>Bersihkan Filter</span>
                        </a>
                    @endif

                    <a href="{{ route('skms.beranda') }}" class="skms-btn-home-action" title="Kembali ke Beranda Awal">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Beranda (Home)</span>
                    </a>
                </div>
            </div>

            <div class="skms-search-grid">

                {{-- ── KOLOM KIRI: Filter Kategori (Combobox) + Hasil FAQ ── --}}
                <div class="skms-main-column">

                    {{-- Filter Kategori (Textbox dengan Combobox Opsi Kategori) --}}
                    <div class="skms-filter-box">
                        <div class="skms-filter-box-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            <span>Filter Kategori</span>
                        </div>
                        <p class="skms-filter-box-subtitle">Ketik pada kolom di bawah untuk memunculkan pilihan kategori</p>

                        <form id="skmsFilterForm" action="{{ route('skms.beranda') }}" method="GET">
                            <input type="hidden" name="search" value="{{ $keyword }}">

                            <div class="skms-combobox-wrapper" id="categoryCombobox">
                                <div class="skms-combobox-input-wrapper">
                                    <svg class="skms-combobox-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    <input type="text" 
                                           id="comboboxInput" 
                                           class="skms-combobox-input" 
                                           placeholder="Ketik untuk mencari kategori..." 
                                           autocomplete="off">
                                    <svg class="skms-combobox-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>

                                {{-- Dropdown Pilihan Kategori --}}
                                <div class="skms-combobox-dropdown" id="comboboxDropdown">
                                    @foreach($categories as $cat)
                                        @php
                                            $isSelected = in_array($cat->id, $selectedCategories);
                                        @endphp
                                        <div class="skms-combobox-item {{ $isSelected ? 'selected' : '' }}" 
                                             data-id="{{ $cat->id }}" 
                                             data-name="{{ strtolower($cat->nama) }}"
                                             onclick="toggleCategoryOption({{ $cat->id }}, '{{ addslashes($cat->nama) }}')">
                                            <div class="skms-combobox-item-left">
                                                <div class="skms-combobox-checkbox" id="check-icon-{{ $cat->id }}">
                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                </div>
                                                <span>{{ $cat->nama }}</span>
                                            </div>
                                            @if($cat->faqs_count > 0)
                                                <span class="skms-combobox-count">{{ $cat->faqs_count }} FAQ</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="skms-combobox-empty" id="comboboxEmpty" style="display: none;">
                                        Kategori tidak ditemukan
                                    </div>
                                </div>
                            </div>

                            {{-- Wadah Hidden Inputs Kategori Terpilih --}}
                            <div id="hiddenCategoriesContainer">
                                @foreach($selectedCategories as $catId)
                                    <input type="hidden" name="categories[]" value="{{ $catId }}" id="hidden-cat-{{ $catId }}">
                                @endforeach
                            </div>

                            {{-- Tag/Chip Kategori yang Sedang Terpilih --}}
                            <div class="skms-selected-tags" id="selectedTagsContainer">
                                @foreach($categories as $cat)
                                    @if(in_array($cat->id, $selectedCategories))
                                        <div class="skms-tag-pill" id="tag-pill-{{ $cat->id }}">
                                            <span>{{ $cat->nama }}</span>
                                            <span class="skms-tag-remove" onclick="removeCategoryTag({{ $cat->id }})" title="Hapus kategori">×</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="skms-filter-actions-bar">
                                @if(count($selectedCategories) > 0)
                                    <a href="{{ route('skms.beranda', ['search' => $keyword]) }}" class="skms-btn-reset-filter">
                                        Reset Filter Kategori
                                    </a>
                                @endif
                                <button type="submit" class="skms-btn-apply-filter">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Daftar Hasil FAQ (Accordion) --}}
                    @if($results->count() > 0)
                        <div class="skms-faq-list">
                            @foreach($results as $faq)
                                <div class="skms-faq-item" id="faq-item-{{ $faq->id }}">
                                    {{-- Tombol Toggle Pertanyaan --}}
                                    <div class="skms-faq-question-btn" onclick="toggleFaqAccordion({{ $faq->id }}, this)" onkeydown="handleAccordionKeydown(event, {{ $faq->id }}, this)" role="button" tabindex="0">
                                        <div class="skms-faq-q-left">
                                            <div class="skms-faq-q-badge">Q</div>
                                            <div class="skms-faq-q-title-group">
                                                <div class="skms-faq-q-text">{{ $faq->pertanyaan }}</div>
                                                <div class="skms-faq-q-meta">
                                                    @foreach($faq->categories as $cat)
                                                        <span class="skms-category-pill">{{ $cat->nama }}</span>
                                                    @endforeach
                                                    <span class="skms-view-pill" id="faq-view-pill-{{ $faq->id }}">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                        <span id="faq-views-count-{{ $faq->id }}">{{ $faq->views ?? 0 }}</span> views
                                                    </span>
                                                    @php
                                                        $isVoted = in_array($faq->id, $helpfulFaqs ?? []);
                                                    @endphp
                                                    <span class="skms-helpful-pill {{ $isVoted ? 'active' : '' }}" 
                                                          id="helpful-pill-{{ $faq->id }}"
                                                          onclick="toggleHelpfulVote({{ $faq->id }}, event)"
                                                          title="{{ $isVoted ? 'Batalkan tanda terbantu' : 'Klik jika informasi ini membantu' }}">
                                                        <span class="skms-helpful-icon">👍</span>
                                                        <span id="helpful-pill-num-{{ $faq->id }}">{{ $faq->helpful ?? 0 }}</span>
                                                        <span class="skms-helpful-pill-label">{{ $isVoted ? 'Terbantu ✓' : 'Membantu' }}</span>
                                                    </span>
                                                    @if(isset($faq->relevance_score) && $faq->relevance_score > 0)
                                                        <span class="skms-faq-score-badge">
                                                            Relevansi: {{ round($faq->relevance_score * 100, 1) }}%
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="skms-faq-chevron">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Panel Jawaban --}}
                                    <div class="skms-faq-answer">
                                        <div class="skms-faq-answer-inner">
                                            <div class="skms-faq-answer-text">
                                                {!! $faq->jawaban !!}
                                            </div>
                                            <div class="skms-faq-answer-footer">
                                                <div class="skms-faq-view-detail">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    <span>Telah dibaca sebanyak <strong id="faq-views-total-{{ $faq->id }}">{{ $faq->views ?? 0 }}</strong> kali oleh mahasiswa</span>
                                                </div>
                                                {{-- Tombol Membantu 👍 & Jumlah Orang Terbantu --}}
                                                <div class="skms-helpful-action-group">
                                                    <button type="button" 
                                                            class="skms-btn-helpful {{ $isVoted ? 'active' : '' }}" 
                                                            id="btn-helpful-{{ $faq->id }}"
                                                            onclick="toggleHelpfulVote({{ $faq->id }}, event)"
                                                            title="{{ $isVoted ? 'Batalkan tanda terbantu' : 'Tandai informasi ini membantu' }}">
                                                        <span class="skms-helpful-icon">👍</span>
                                                        <span class="skms-helpful-btn-label">{{ $isVoted ? 'Terbantu ✓' : 'Membantu' }}</span>
                                                        <span class="skms-helpful-count-badge" id="helpful-badge-{{ $faq->id }}">{{ $faq->helpful ?? 0 }}</span>
                                                    </button>
                                                    <span class="skms-helpful-caption">
                                                        <strong id="helpful-caption-num-{{ $faq->id }}">{{ $faq->helpful ?? 0 }}</strong> orang merasa terbantu
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty State jika tidak ada FAQ ditemukan --}}
                        <div class="skms-empty-state">
                            <div class="skms-empty-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </div>
                            <h3 class="skms-empty-title">Tidak ada informasi yang sesuai</h3>
                            <p class="skms-empty-desc">
                                Pertanyaan tidak ditemukan untuk kata kunci "{{ $keyword }}". Coba gunakan istilah kata lain atau bersihkan filter kategori.
                            </p>
                        </div>
                    @endif

                </div>

                {{-- ── KOLOM KANAN: Top FAQ Berdasarkan Views ── --}}
                <aside class="skms-sidebar-column">
                    <div class="skms-top-faq-card">
                        <div class="skms-top-faq-header">
                            <div class="skms-top-faq-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                            </div>
                            <div class="skms-top-faq-title-wrap">
                                <h3 class="skms-top-faq-title">Top FAQ Terpopuler</h3>
                                <span class="skms-top-faq-subtitle">Paling sering dilihat mahasiswa</span>
                            </div>
                        </div>

                        @if($topFaqs->count() > 0)
                            <div class="skms-top-faq-list">
                                @foreach($topFaqs as $index => $top)
                                    <div class="skms-top-faq-item" 
                                         onclick="openTopFaqModal({{ $top->id }}, '{{ addslashes($top->pertanyaan) }}', '{{ addslashes($top->jawaban) }}', {{ $top->views ?? 0 }})">
                                        <div class="skms-top-rank">#{{ $index + 1 }}</div>
                                        <div class="skms-top-item-content">
                                            <div class="skms-top-item-question">{{ $top->pertanyaan }}</div>
                                            <div class="skms-top-item-meta">
                                                <span class="skms-top-views-badge">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    <span id="top-views-count-{{ $top->id }}">{{ $top->views ?? 0 }}</span> views
                                                </span>
                                                @if($top->categories->first())
                                                    <span>· {{ $top->categories->first()->nama }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="font-size: 13px; color: #94A3B8; text-align: center; padding: 16px 0;">
                                Belum ada statistik Top FAQ
                            </div>
                        @endif
                    </div>
                </aside>

            </div>
        </main>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         MODAL DETAIL TOP FAQ
    ══════════════════════════════════════════════════════════════ --}}
    <div id="topFaqModal" class="skms-modal-backdrop" onclick="closeTopFaqModal(event)">
        <div class="skms-modal-box" onclick="event.stopPropagation()">
            <div class="skms-modal-header">
                <h3 class="skms-modal-title" id="modalFaqTitle">Judul FAQ</h3>
                <button type="button" class="skms-modal-close" onclick="closeTopFaqModal()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="skms-modal-body" id="modalFaqBody">
                Konten jawaban...
            </div>
            <div class="skms-modal-footer">
                <span class="skms-faq-view-detail">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    Dilihat <strong id="modalFaqViews">0</strong> kali
                </span>
                <span style="font-size: 12px; color: #94A3B8;">SKMS Universitas Surabaya</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════════════════════════ --}}
    <footer class="skms-footer">
        <div>
            &copy; {{ date('Y') }} <strong>SKMS UBAYA</strong> — Student Knowledge Management System.
        </div>
    </footer>

    {{-- ══════════════════════════════════════════════════════════════
         JAVASCRIPT LOGIC: COMBOBOX, ACCORDION & REAL-TIME VIEW INCREMENT
    ══════════════════════════════════════════════════════════════ --}}
    <script>
        const helpfulRouteTemplate = "{{ route('skms.faq.helpful', ['faq' => '__FAQ_ID__']) }}";
        const viewRouteTemplate    = "{{ route('skms.faq.view', ['faq' => '__FAQ_ID__']) }}";
        const csrfToken            = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const viewedFaqsInSession  = new Set();
        const votingInProgressFaqs = new Set();

        /* ── Real-Time Increment View Saat FAQ Dibuka ─────────────── */
        function recordFaqView(faqId) {
            if (viewedFaqsInSession.has(faqId)) return;
            viewedFaqsInSession.add(faqId);

            const viewUrl = viewRouteTemplate.replace('__FAQ_ID__', faqId);
            fetch(viewUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ _token: csrfToken })
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.success) {
                    const views = data.views;
                    // Update tampilan badge view di accordion item
                    const countEl = document.getElementById(`faq-views-count-${faqId}`);
                    if (countEl) countEl.textContent = views;

                    const totalEl = document.getElementById(`faq-views-total-${faqId}`);
                    if (totalEl) totalEl.textContent = views;

                    // Update juga jika ada di daftar top faq di sebelah kanan
                    const topCountEl = document.getElementById(`top-views-count-${faqId}`);
                    if (topCountEl) topCountEl.textContent = views;
                }
            })
            .catch(err => {
                console.error('Gagal mencatat view FAQ:', err);
            });
        }

        /* ── Keyboard Handling untuk Accordion Header ─────────────── */
        function handleAccordionKeydown(event, faqId, el) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleFaqAccordion(faqId, el);
            }
        }

        /* ── Accordion Toggle ─────────────────────────────────────── */
        function toggleFaqAccordion(faqId, el) {
            const item = document.getElementById(`faq-item-${faqId}`);
            if (!item) return;

            const isOpen = item.classList.contains('open');

            // Tutup FAQ lain jika terbuka
            document.querySelectorAll('.skms-faq-item.open').forEach(other => {
                if (other !== item) other.classList.remove('open');
            });

            if (!isOpen) {
                item.classList.add('open');
                recordFaqView(faqId);
            } else {
                item.classList.remove('open');
            }
        }

        /* ── Modal Pop-up Top FAQ ─────────────────────────────────── */
        function openTopFaqModal(faqId, title, answer, views) {
            document.getElementById('modalFaqTitle').textContent = title;
            document.getElementById('modalFaqBody').innerHTML = answer;
            document.getElementById('modalFaqViews').textContent = views;
            document.getElementById('topFaqModal').style.display = 'flex';

            recordFaqView(faqId);
        }

        function closeTopFaqModal(e) {
            if (e && e.target !== e.currentTarget && !e.target.closest('.skms-modal-close')) return;
            document.getElementById('topFaqModal').style.display = 'none';
        }

        /* ── Category Combobox Logic (Textbox Dropdown) ───────────── */
        const comboboxWrapper = document.getElementById('categoryCombobox');
        const comboboxInput   = document.getElementById('comboboxInput');
        const comboboxDropdown= document.getElementById('comboboxDropdown');
        const emptyDropdown   = document.getElementById('comboboxEmpty');
        const hiddenContainer = document.getElementById('hiddenCategoriesContainer');
        const tagsContainer   = document.getElementById('selectedTagsContainer');

        if (comboboxInput) {
            // Munculkan combobox dropdown saat input fokus atau diklik
            comboboxInput.addEventListener('focus', () => {
                comboboxWrapper.classList.add('open');
                filterDropdownItems(comboboxInput.value);
            });

            comboboxInput.addEventListener('input', (e) => {
                comboboxWrapper.classList.add('open');
                filterDropdownItems(e.target.value);
            });

            // Tutup combobox dropdown jika klik di luar
            document.addEventListener('click', (e) => {
                if (!comboboxWrapper.contains(e.target)) {
                    comboboxWrapper.classList.remove('open');
                }
            });
        }

        function filterDropdownItems(query) {
            const term = (query || '').toLowerCase().trim();
            const items = document.querySelectorAll('.skms-combobox-item');
            let visibleCount = 0;

            items.forEach(item => {
                const name = item.getAttribute('data-name');
                if (name.includes(term)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (emptyDropdown) {
                emptyDropdown.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        function toggleCategoryOption(catId, catName) {
            const item = document.querySelector(`.skms-combobox-item[data-id="${catId}"]`);
            const existingHidden = document.getElementById(`hidden-cat-${catId}`);

            if (existingHidden) {
                // Hapus pilihan
                existingHidden.remove();
                if (item) item.classList.remove('selected');
                const tag = document.getElementById(`tag-pill-${catId}`);
                if (tag) tag.remove();
            } else {
                // Tambah pilihan
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'categories[]';
                input.value = catId;
                input.id = `hidden-cat-${catId}`;
                hiddenContainer.appendChild(input);

                if (item) item.classList.add('selected');

                // Tambahkan Tag Chip
                const tag = document.createElement('div');
                tag.className = 'skms-tag-pill';
                tag.id = `tag-pill-${catId}`;
                tag.innerHTML = `<span>${catName}</span><span class="skms-tag-remove" onclick="removeCategoryTag(${catId})" title="Hapus kategori">×</span>`;
                tagsContainer.appendChild(tag);
            }
        }

        function removeCategoryTag(catId) {
            const item = document.querySelector(`.skms-combobox-item[data-id="${catId}"]`);
            if (item) item.classList.remove('selected');

            const existingHidden = document.getElementById(`hidden-cat-${catId}`);
            if (existingHidden) existingHidden.remove();

            const tag = document.getElementById(`tag-pill-${catId}`);
            if (tag) tag.remove();
        }

        function setSearchHint(query) {
            const input = document.getElementById('mainSearchInput');
            if (input) {
                input.value = query;
                input.focus();
            }
        }

        function clearFilterKeepSearch(e) {
            if (e) e.preventDefault();
            const searchInput = document.getElementById('mainSearchInput');
            const keyword = searchInput ? searchInput.value.trim() : '{{ addslashes($keyword) }}';
            window.location.href = '{{ route('skms.beranda') }}?search=' + encodeURIComponent(keyword);
        }

        /* ── Toggle Vote Membantu 👍 dengan Proteksi Anti-Spam & Optimistic Feedback ────── */
        function toggleHelpfulVote(faqId, event) {
            if (event) {
                if (typeof event.stopPropagation === 'function') event.stopPropagation();
                if (typeof event.preventDefault === 'function') event.preventDefault();
            }

            if (votingInProgressFaqs.has(faqId)) return;
            votingInProgressFaqs.add(faqId);

            // Elemen UI terkait
            const footerBtn   = document.getElementById(`btn-helpful-${faqId}`);
            const headerPill  = document.getElementById(`helpful-pill-${faqId}`);
            const badgeEl     = document.getElementById(`helpful-badge-${faqId}`);
            const pillNumEl   = document.getElementById(`helpful-pill-num-${faqId}`);
            const captionEl   = document.getElementById(`helpful-caption-num-${faqId}`);
            const footerLabel = footerBtn ? footerBtn.querySelector('.skms-helpful-btn-label') : null;
            const pillLabel   = headerPill ? headerPill.querySelector('.skms-helpful-pill-label') : null;

            // Baca status sebelumnya
            const wasActive = (footerBtn && footerBtn.classList.contains('active')) ||
                              (headerPill && headerPill.classList.contains('active'));
            const prevCount = parseInt((badgeEl ? badgeEl.textContent : (pillNumEl ? pillNumEl.textContent : '0')) || '0', 10);

            // Prediksi state baru (Optimistic UI)
            const nextIsActive = !wasActive;
            const nextCount = Math.max(0, prevCount + (nextIsActive ? 1 : -1));

            function renderHelpfulState(isActive, count) {
                // Update counter angka
                if (badgeEl) badgeEl.textContent = count;
                if (pillNumEl) pillNumEl.textContent = count;
                if (captionEl) captionEl.textContent = count;

                // Update tombol di dalam jawaban
                if (footerBtn) {
                    if (isActive) {
                        footerBtn.classList.add('active');
                        if (footerLabel) footerLabel.textContent = 'Terbantu ✓';
                        footerBtn.title = 'Batalkan tanda terbantu';
                    } else {
                        footerBtn.classList.remove('active');
                        if (footerLabel) footerLabel.textContent = 'Membantu';
                        footerBtn.title = 'Tandai informasi ini membantu';
                    }
                }

                // Update pill di baris pertanyaan
                if (headerPill) {
                    if (isActive) {
                        headerPill.classList.add('active');
                        if (pillLabel) pillLabel.textContent = 'Terbantu ✓';
                        headerPill.title = 'Batalkan tanda terbantu';
                    } else {
                        headerPill.classList.remove('active');
                        if (pillLabel) pillLabel.textContent = 'Membantu';
                        headerPill.title = 'Klik jika informasi ini membantu';
                    }
                }
            }

            // Terapkan perubahan instan ke UI
            renderHelpfulState(nextIsActive, nextCount);

            // Endpoint URL dari named route Laravel
            const voteUrl = helpfulRouteTemplate.replace('__FAQ_ID__', faqId);

            fetch(voteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ _token: csrfToken })
            })
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    // Pastikan akurat sesuai data database
                    renderHelpfulState(Boolean(data.is_helpful), parseInt(data.helpful, 10));
                } else {
                    // Rollback jika server menolak
                    renderHelpfulState(wasActive, prevCount);
                }
            })
            .catch(err => {
                console.error('Gagal mencatat respon membantu:', err);
                // Kembalikan ke state semula
                renderHelpfulState(wasActive, prevCount);
            })
            .finally(() => {
                votingInProgressFaqs.delete(faqId);
            });
        }

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
