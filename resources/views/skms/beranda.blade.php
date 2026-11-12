<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKMS — Pusat Informasi & FAQ</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* ================================================
           SKMS PUBLIC BERANDA — STANDALONE STYLES
        ================================================ */
        .skms-body {
            min-height: 100vh;
            background: linear-gradient(135deg, #F0F4FF 0%, #F8FAFC 50%, #FDF4FF 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #475569;
        }

        /* ── Hero Section ──────────────────────────────── */
        .skms-hero {
            text-align: center;
            padding: 36px 24px 40px;
            position: relative;
        }

        .skms-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 310px;
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #A78BFA 100%);
            border-radius: 0 0 48px 48px;
            z-index: 0;
        }

        /* ── Top Navigation & Student Identity ─────────── */
        .skms-top-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 900px;
            margin: 0 auto 24px;
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
            gap: 12px;
        }

        .skms-student-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 5px 14px 5px 6px;
            border-radius: 9999px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .skms-student-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .skms-student-details {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.2;
        }

        .skms-student-name {
            font-size: 12.5px;
            font-weight: 700;
            color: #1E293B;
        }

        .skms-student-nrp {
            font-size: 11px;
            color: #64748B;
        }

        .skms-btn-student-logout {
            background: none;
            border: none;
            color: #EF4444;
            font-size: 11.5px;
            font-weight: 600;
            cursor: pointer;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.15s ease;
            font-family: inherit;
        }

        .skms-btn-student-logout:hover {
            background: #FEF2F2;
        }

        .skms-btn-student-login {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.95);
            color: #4F46E5;
            padding: 7px 16px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }

        .skms-btn-student-login:hover {
            background: #FFFFFF;
            color: #4338CA;
            transform: translateY(-1px);
        }

        .skms-hero-content {
            position: relative;
            z-index: 1;
            max-width: 700px;
            margin: 0 auto;
        }

        .skms-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 9999px;
            margin-bottom: 16px;
        }

        .skms-hero-title {
            font-size: 34px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.8px;
            line-height: 1.2;
            margin-bottom: 28px;
        }

        /* ── Search Box ────────────────────────────────── */
        .skms-search-container {
            max-width: 640px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .skms-search-form {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 20px 50px -12px rgba(99, 102, 241, 0.25), 0 0 0 1px rgba(255,255,255,0.5);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .skms-search-form:focus-within {
            box-shadow: 0 20px 50px -12px rgba(99, 102, 241, 0.35), 0 0 0 3px rgba(99,102,241,0.15);
        }

        .skms-search-icon {
            padding: 0 0 0 20px;
            display: flex;
            align-items: center;
            color: #94A3B8;
        }

        .skms-search-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 18px 16px;
            font-size: 15px;
            font-family: inherit;
            color: #1E293B;
            background: transparent;
        }

        .skms-search-input::placeholder {
            color: #94A3B8;
        }

        .skms-search-btn {
            margin: 8px;
            padding: 10px 24px;
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            color: #FFFFFF;
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

        /* ── Filter Card & Multi-Checkboxes ───────────── */
        .skms-filter-section {
            max-width: 900px;
            margin: 28px auto 0;
            padding: 0 24px;
            position: relative;
            z-index: 1;
        }

        .skms-filter-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.04);
        }

        .skms-filter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #F1F5F9;
        }

        .skms-filter-title-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .skms-filter-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #EEF2FF;
            color: #6366F1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .skms-filter-title {
            font-size: 14px;
            font-weight: 700;
            color: #1E293B;
            line-height: 1.2;
        }

        .skms-filter-subtitle {
            font-size: 12px;
            color: #94A3B8;
            margin-top: 2px;
        }

        .skms-filter-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
        }

        .skms-btn-text {
            background: none;
            border: none;
            color: #6366F1;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
            font-family: inherit;
            transition: color 0.15s ease;
        }

        .skms-btn-text:hover {
            color: #4F46E5;
            text-decoration: underline;
        }

        .skms-divider-dot {
            color: #CBD5E1;
        }

        .skms-checkbox-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .skms-checkbox-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 10px;
            border: 1.5px solid #E2E8F0;
            background: #F8FAFC;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
            font-size: 13.5px;
            font-weight: 500;
            color: #475569;
        }

        .skms-checkbox-item:hover {
            border-color: #C7D2FE;
            background: #EEF2FF;
            color: #4F46E5;
        }

        .skms-checkbox-item.checked {
            border-color: #6366F1;
            background: #EEF2FF;
            color: #4338CA;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.12);
        }

        .skms-checkbox-native {
            position: absolute;
            opacity: 0;
            pointer-events: none;
            width: 0;
            height: 0;
        }

        .skms-custom-checkbox {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 1.5px solid #CBD5E1;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
            color: transparent;
        }

        .skms-checkbox-item.checked .skms-custom-checkbox {
            background: #6366F1;
            border-color: #6366F1;
            color: #FFFFFF;
        }

        .skms-checkbox-badge {
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 9999px;
            background: #E2E8F0;
            color: #64748B;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .skms-checkbox-item.checked .skms-checkbox-badge {
            background: #C7D2FE;
            color: #3730A3;
        }

        .skms-filter-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding-top: 14px;
            border-top: 1px solid #F1F5F9;
        }

        .skms-filter-status {
            font-size: 13px;
            color: #64748B;
        }

        .skms-filter-status strong {
            color: #1E293B;
        }

        .skms-filter-btns {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .skms-btn-apply {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .skms-btn-apply:hover {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }

        .skms-btn-reset-all {
            padding: 8px 14px;
            color: #64748B;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.15s ease;
        }

        .skms-btn-reset-all:hover {
            color: #EF4444;
            background: #FEF2F2;
        }

        /* ── Results Info ──────────────────────────────── */
        .skms-results-info {
            max-width: 900px;
            margin: 28px auto 0;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .skms-results-count {
            font-size: 14px;
            color: #64748B;
            font-weight: 500;
        }

        .skms-results-count strong {
            color: #1E293B;
            font-weight: 700;
        }

        .skms-clear-search {
            font-size: 13px;
            color: #6366F1;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .skms-clear-search:hover {
            text-decoration: underline;
        }

        /* ── FAQ Accordion List ────────────────────────── */
        .skms-faq-section {
            max-width: 900px;
            margin: 24px auto 60px;
            padding: 0 24px;
        }

        .skms-faq-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .skms-faq-item {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .skms-faq-item:hover {
            border-color: #C7D2FE;
            box-shadow: 0 4px 16px -4px rgba(99, 102, 241, 0.1);
        }

        .skms-faq-item.open {
            border-color: #A5B4FC;
            box-shadow: 0 8px 24px -8px rgba(99, 102, 241, 0.15);
        }

        .skms-faq-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 24px;
            cursor: pointer;
            user-select: none;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            font-family: inherit;
            transition: background 0.15s ease;
        }

        .skms-faq-question:hover {
            background: #FAFAFF;
        }

        .skms-faq-question-left {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            flex: 1;
            min-width: 0;
        }

        .skms-faq-q-icon {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
            color: #6366F1;
        }

        .skms-faq-q-content {
            flex: 1;
            min-width: 0;
            padding-top: 2px;
        }

        .skms-faq-q-header {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px 10px;
        }

        .skms-faq-q-text {
            font-size: 15px;
            font-weight: 600;
            color: #1E293B;
            line-height: 1.5;
        }

        .skms-faq-q-categories {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .skms-faq-chevron {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: #94A3B8;
        }

        .skms-faq-item.open .skms-faq-chevron {
            background: #EEF2FF;
            color: #6366F1;
            transform: rotate(180deg);
        }

        .skms-faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, padding 0.35s ease;
            padding: 0 24px;
        }

        .skms-faq-item.open .skms-faq-answer {
            max-height: 1000px;
            padding: 0 24px 24px;
        }

        .skms-faq-answer-inner {
            padding-top: 16px;
            border-top: 1px solid #F1F5F9;
        }

        .skms-faq-answer-text {
            font-size: 14px;
            color: #475569;
            line-height: 1.75;
        }

        .skms-faq-answer-text p {
            margin-bottom: 8px;
        }

        .skms-faq-answer-text p:last-child {
            margin-bottom: 0;
        }

        .skms-faq-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .skms-faq-category-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            background: #EEF2FF;
            color: #6366F1;
            font-size: 11px;
            font-weight: 600;
            border-radius: 9999px;
        }

        .skms-faq-relevance {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: #ECFDF5;
            color: #059669;
            font-size: 11px;
            font-weight: 600;
            border-radius: 9999px;
        }

        /* ── Tombol Membantu 👍 & Counter ─────────────── */
        .skms-helpful-container {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px dashed #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .skms-btn-helpful {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            background-color: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .skms-btn-helpful:hover {
            border-color: #C7D2FE;
            background-color: #EEF2FF;
            color: #4F46E5;
            transform: translateY(-1px);
        }

        .skms-btn-helpful.active {
            background-color: #EEF2FF;
            border-color: #6366F1;
            color: #4338CA;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
        }

        .skms-helpful-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 9999px;
            background-color: #F1F5F9;
            color: #64748B;
            font-size: 11.5px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .skms-btn-helpful.active .skms-helpful-badge {
            background-color: #6366F1;
            color: #FFFFFF;
        }

        .skms-helpful-text {
            font-size: 13px;
            color: #64748B;
        }

        .skms-helpful-text strong {
            color: #1E293B;
            font-weight: 700;
        }

        /* ── Empty State ───────────────────────────────── */
        .skms-empty-state {
            text-align: center;
            padding: 60px 24px;
        }

        .skms-empty-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .skms-empty-title {
            font-size: 18px;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 8px;
        }

        .skms-empty-desc {
            font-size: 14px;
            color: #94A3B8;
            max-width: 400px;
            margin: 0 auto;
        }

        /* ── Footer ────────────────────────────────────── */
        .skms-footer {
            text-align: center;
            padding: 32px 24px;
            border-top: 1px solid #E2E8F0;
            background: #FFFFFF;
        }

        .skms-footer-text {
            font-size: 13px;
            color: #94A3B8;
        }

        .skms-footer-text a {
            color: #6366F1;
            text-decoration: none;
            font-weight: 500;
        }

        .skms-footer-text a:hover {
            text-decoration: underline;
        }

        /* ── Responsive ────────────────────────────────── */
        @media (max-width: 640px) {
            .skms-hero {
                padding: 48px 16px 32px;
            }
            .skms-hero-title {
                font-size: 26px;
                margin-bottom: 20px;
            }
            .skms-search-form {
                flex-direction: column;
                border-radius: 14px;
            }
            .skms-search-input {
                padding: 14px 16px;
                width: 100%;
            }
            .skms-search-btn {
                width: calc(100% - 16px);
                margin: 0 8px 8px;
                text-align: center;
                justify-content: center;
            }
            .skms-search-icon {
                display: none;
            }
            .skms-faq-question {
                padding: 16px 18px;
            }
        }
    </style>
</head>
<body class="skms-body">

    {{-- ═══════════════════════════════════════════════════
         HERO SECTION + SEARCH
    ═══════════════════════════════════════════════════ --}}
    <section class="skms-hero">
        {{-- Top Navigation: Identitas Mahasiswa & Tombol Masuk Mahasiswa --}}
        <div class="skms-top-nav">
            <div class="skms-hero-badge" style="margin-bottom: 0;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                SKMS UBAYA
            </div>

            @if(!empty($studentNrp))
                <div class="skms-student-pill">
                    <div class="skms-student-avatar">
                        {{ strtoupper(substr($studentName ?? 'M', 0, 1)) }}
                    </div>
                    <div class="skms-student-details">
                        <span class="skms-student-name">{{ $studentName }}</span>
                        <span class="skms-student-nrp">NRP: {{ $studentNrp }}</span>
                    </div>
                    <form action="{{ route('skms.logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="skms-btn-student-logout" title="Keluar dari sesi mahasiswa">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('skms.login') }}" class="skms-btn-student-login">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Masuk Mahasiswa (NRP)</span>
                </a>
            @endif
        </div>

        @if(session('success'))
            <div style="max-width: 640px; margin: 0 auto 16px; background-color: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 10px 18px; border-radius: 12px; font-size: 13.5px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div style="max-width: 640px; margin: 0 auto 16px; background-color: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF; padding: 10px 18px; border-radius: 12px; font-size: 13.5px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <div class="skms-hero-content">
            <h1 class="skms-hero-title">Pusat Informasi & FAQ</h1>
        </div>

        {{-- Search Form --}}
        <div class="skms-search-container">
            <div class="skms-search-form">
                <div class="skms-search-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       form="skmsFilterForm"
                       value="{{ $keyword }}" 
                       class="skms-search-input" 
                       placeholder="Ketik pertanyaan Anda di sini, misal: cara mengajukan cuti..."
                       autocomplete="off">
                <button type="submit" form="skmsFilterForm" class="skms-search-btn">
                    Cari
                </button>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════
         CATEGORY FILTER (CHECKBOXES)
    ═══════════════════════════════════════════════════ --}}
    <div class="skms-filter-section">
        <div class="skms-filter-card">
            <div class="skms-filter-header">
                <div class="skms-filter-title-group">
                    <div class="skms-filter-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </div>
                    <div>
                        <h3 class="skms-filter-title">Filter Kategori FAQ</h3>
                        <p class="skms-filter-subtitle">Pilih satu atau beberapa kategori sekaligus</p>
                    </div>
                </div>
                <div class="skms-filter-actions">
                    <button type="button" class="skms-btn-text" onclick="toggleSelectAllCategories(true)">Pilih Semua</button>
                    <span class="skms-divider-dot">·</span>
                    <button type="button" class="skms-btn-text" onclick="toggleSelectAllCategories(false)">Reset Pilihan</button>
                </div>
            </div>

            <form id="skmsFilterForm" action="{{ route('skms.beranda') }}" method="GET">
                <div class="skms-checkbox-grid">
                    @foreach($categories as $cat)
                        @php
                            $isChecked = in_array($cat->id, $selectedCategories);
                        @endphp
                        <label class="skms-checkbox-item {{ $isChecked ? 'checked' : '' }}">
                            <input type="checkbox" 
                                   name="categories[]" 
                                   value="{{ $cat->id }}" 
                                   class="skms-checkbox-native"
                                   {{ $isChecked ? 'checked' : '' }}
                                   onchange="handleCheckboxChange(this)">
                            <span class="skms-custom-checkbox">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </span>
                            <span class="skms-checkbox-label-text">{{ $cat->nama }}</span>
                            @if(isset($cat->faqs_count) && $cat->faqs_count > 0)
                                <span class="skms-checkbox-badge">{{ $cat->faqs_count }}</span>
                            @endif
                        </label>
                    @endforeach
                </div>

                <div class="skms-filter-footer">
                    <div class="skms-filter-status">
                        <span id="selectedCountText">
                            @if(count($selectedCategories) > 0)
                                <strong>{{ count($selectedCategories) }}</strong> dari {{ $categories->count() }} kategori dipilih
                            @else
                                Semua kategori ditampilkan
                            @endif
                        </span>
                    </div>
                    <div class="skms-filter-btns">
                        @if(count($selectedCategories) > 0 || $keyword)
                            <a href="{{ route('skms.beranda') }}" class="skms-btn-reset-all">
                                Bersihkan Filter
                            </a>
                        @endif
                        <button type="submit" class="skms-btn-apply">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         RESULTS INFO BAR
    ═══════════════════════════════════════════════════ --}}
    <div class="skms-results-info">
        <div class="skms-results-count">
            @if($searched)
                Ditemukan <strong>{{ $results->count() }}</strong> hasil untuk "<strong>{{ $keyword }}</strong>"
                @if(count($selectedCategories) > 0)
                    pada <strong>{{ count($selectedCategories) }}</strong> kategori
                @endif
            @else
                Menampilkan <strong>{{ $results->count() }}</strong> informasi yang tersedia
                @if(count($selectedCategories) > 0)
                    pada <strong>{{ count($selectedCategories) }}</strong> kategori terpilih
                @endif
            @endif
        </div>
        @if($searched || count($selectedCategories) > 0)
            <a href="{{ route('skms.beranda') }}" class="skms-clear-search">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                Hapus pencarian & filter
            </a>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════
         FAQ ACCORDION LIST
    ═══════════════════════════════════════════════════ --}}
    <section class="skms-faq-section">
        @if($results->count() > 0)
            <div class="skms-faq-list">
                @foreach($results as $index => $faq)
                    <div class="skms-faq-item" data-faq-id="{{ $faq->id }}">
                        {{-- Question Toggle --}}
                        <button class="skms-faq-question" onclick="toggleFaq(this)">
                            <div class="skms-faq-question-left">
                                <div class="skms-faq-q-icon">Q</div>
                                <div class="skms-faq-q-content">
                                    <div class="skms-faq-q-header">
                                        <span class="skms-faq-q-text">{{ $faq->pertanyaan }}</span>
                                        <div class="skms-faq-q-categories">
                                            @foreach($faq->categories as $cat)
                                                <span class="skms-faq-category-pill">{{ $cat->nama }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="skms-faq-chevron">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                        </button>

                        {{-- Answer Panel --}}
                        <div class="skms-faq-answer">
                            <div class="skms-faq-answer-inner">
                                <div class="skms-faq-answer-text">
                                    {!! $faq->jawaban !!}
                                </div>
                                <div class="skms-faq-meta">
                                    @foreach($faq->categories as $cat)
                                        <span class="skms-faq-category-pill">{{ $cat->nama }}</span>
                                    @endforeach
                                    @if($searched && isset($faq->relevance_score))
                                        <span class="skms-faq-relevance">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                            </svg>
                                            Relevansi: {{ round($faq->relevance_score * 100, 1) }}%
                                        </span>
                                    @endif
                                </div>

                                {{-- Tombol Membantu 👍 & Jumlah Orang Terbantu --}}
                                <div class="skms-helpful-container">
                                    <button type="button" 
                                            class="skms-btn-helpful {{ in_array($faq->id, $helpfulFaqs ?? []) ? 'active' : '' }}" 
                                            onclick="toggleHelpful({{ $faq->id }}, this)"
                                            title="Tandai informasi ini membantu">
                                        <span>Membantu 👍</span>
                                        <span class="skms-helpful-badge" id="helpful-badge-{{ $faq->id }}">{{ $faq->helpful ?? 0 }}</span>
                                    </button>
                                    <span class="skms-helpful-text">
                                        <strong id="helpful-text-count-{{ $faq->id }}">{{ $faq->helpful ?? 0 }}</strong> orang terbantu dengan FAQ ini
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="skms-empty-state">
                <div class="skms-empty-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="8" y1="11" x2="14" y2="11"></line>
                    </svg>
                </div>
                <h3 class="skms-empty-title">
                    @if($searched)
                        Tidak ada hasil ditemukan
                    @else
                        Belum ada informasi FAQ
                    @endif
                </h3>
                <p class="skms-empty-desc">
                    @if($searched)
                        Coba gunakan kata kunci lain atau hapus filter kategori untuk memperluas pencarian.
                    @else
                        Informasi FAQ akan muncul di sini setelah dipublikasikan oleh admin.
                    @endif
                </p>
            </div>
        @endif
    </section>

    {{-- ═══════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════ --}}
    <footer class="skms-footer">
        <p class="skms-footer-text">
            &copy; {{ date('Y') }} <strong>SKMS</strong> — Student Knowledge Management System.
            @auth
                <span style="margin: 0 6px;">·</span>
                <a href="{{ route('dashboard') }}">Masuk ke Dashboard</a>
            @else
                <span style="margin: 0 6px;">·</span>
                <a href="{{ route('login') }}">Login Admin</a>
            @endauth
        </p>
    </footer>

    {{-- ═══════════════════════════════════════════════════
         JAVASCRIPT — Accordion Toggle
    ═══════════════════════════════════════════════════ --}}
    <script>
        function toggleFaq(button) {
            const item = button.closest('.skms-faq-item');
            const isOpen = item.classList.contains('open');

            // Tutup semua FAQ yang sedang terbuka (accordion behavior)
            document.querySelectorAll('.skms-faq-item.open').forEach(el => {
                if (el !== item) {
                    el.classList.remove('open');
                }
            });

            // Toggle item yang diklik
            item.classList.toggle('open', !isOpen);
        }

        // Checkbox category handlers
        function handleCheckboxChange(checkbox) {
            const item = checkbox.closest('.skms-checkbox-item');
            if (checkbox.checked) {
                item.classList.add('checked');
            } else {
                item.classList.remove('checked');
            }
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const total = document.querySelectorAll('.skms-checkbox-native').length;
            const checked = document.querySelectorAll('.skms-checkbox-native:checked').length;
            const textEl = document.getElementById('selectedCountText');
            if (textEl) {
                if (checked > 0) {
                    textEl.innerHTML = `<strong>${checked}</strong> dari ${total} kategori dipilih`;
                } else {
                    textEl.textContent = 'Semua kategori ditampilkan';
                }
            }
        }

        function toggleSelectAllCategories(select) {
            const checkboxes = document.querySelectorAll('.skms-checkbox-native');
            checkboxes.forEach(cb => {
                cb.checked = select;
                const item = cb.closest('.skms-checkbox-item');
                if (select) {
                    item.classList.add('checked');
                } else {
                    item.classList.remove('checked');
                }
            });
            updateSelectedCount();
        }

        // Toggle Helpful (Membantu 👍)
        function toggleHelpful(faqId, btn) {
            fetch(`/skms/faq/${faqId}/helpful`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById(`helpful-badge-${faqId}`);
                    const textCount = document.getElementById(`helpful-text-count-${faqId}`);
                    if (badge) badge.textContent = data.helpful;
                    if (textCount) textCount.textContent = data.helpful;

                    if (data.is_helpful) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                }
            })
            .catch(err => {
                console.error('Error toggling helpful status:', err);
            });
        }

        // Keyboard accessibility: Enter / Space untuk toggle
        document.querySelectorAll('.skms-faq-question').forEach(btn => {
            btn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleFaq(this);
                }
            });
        });
    </script>
</body>
</html>

