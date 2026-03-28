<style>
    .shop-page {
        background:
            radial-gradient(circle at top, rgba(18, 61, 102, 0.22), transparent 30%),
            linear-gradient(180deg, #09131d 0%, #08111a 100%);
        color: #f4f7fb;
        min-height: 100vh;
        padding: 120px 0 72px;
    }

    .shop-shell {
        max-width: 1320px;
    }

    .shop-hero,
    .shop-panel,
    .shop-card,
    .shop-summary,
    .shop-form-card {
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: linear-gradient(180deg, rgba(13, 24, 36, 0.96), rgba(8, 16, 26, 0.98));
        box-shadow: 0 22px 48px rgba(0, 0, 0, 0.22);
    }

    .shop-hero,
    .shop-panel,
    .shop-form-card,
    .shop-summary {
        border-radius: 28px;
    }

    .shop-card {
        border-radius: 22px;
        overflow: hidden;
        height: 100%;
        transition: transform 0.22s ease, border-color 0.22s ease;
    }

    .shop-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255, 210, 79, 0.35);
    }

    .shop-kicker {
        color: #ffd24f;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .shop-title {
        color: #ffffff;
        font-size: clamp(2rem, 3vw, 3.25rem);
        font-weight: 700;
        letter-spacing: -0.04em;
        line-height: 1.04;
    }

    .shop-subtitle,
    .shop-muted {
        color: rgba(231, 238, 247, 0.72);
    }

    .shop-card__image,
    .shop-product-media img {
        width: 100%;
        display: block;
        object-fit: cover;
        background: #101c28;
    }

    .shop-card__image {
        aspect-ratio: 4 / 5;
    }

    .shop-product-media img {
        aspect-ratio: 4 / 4;
        border-radius: 24px;
    }

    .shop-card__body {
        padding: 18px;
    }

    .shop-price {
        color: #ffd24f;
        font-weight: 700;
        font-size: 1.08rem;
    }

    .shop-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .shop-meta span {
        display: inline-flex;
        align-items: center;
        min-height: 34px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #f4f7fb;
        font-size: 12px;
    }

    .shop-btn-primary,
    .shop-btn-secondary,
    .shop-btn-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 12px 18px;
        border: 0;
        border-radius: 999px;
        font-weight: 700;
        transition: transform 0.18s ease, opacity 0.18s ease;
    }

    .shop-btn-primary:hover,
    .shop-btn-secondary:hover,
    .shop-btn-danger:hover {
        transform: translateY(-2px);
    }

    .shop-btn-primary {
        background: linear-gradient(135deg, #ffd24f, #f5ab00);
        color: #09131d;
    }

    .shop-btn-secondary {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .shop-btn-danger {
        background: rgba(255, 90, 90, 0.12);
        color: #ffd4d4;
        border: 1px solid rgba(255, 90, 90, 0.18);
    }

    .shop-input,
    .shop-select,
    .shop-textarea {
        width: 100%;
        min-height: 48px;
        padding: 12px 14px;
        border-radius: 14px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.04);
        color: #ffffff;
    }

    .shop-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .shop-label {
        display: block;
        margin-bottom: 8px;
        color: #ffffff;
        font-weight: 600;
    }

    .shop-table {
        width: 100%;
        color: #f4f7fb;
    }

    .shop-table th,
    .shop-table td {
        padding: 16px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        vertical-align: middle;
    }

    .shop-table tr:last-child td {
        border-bottom: 0;
    }

    .shop-summary__row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .shop-summary__row:last-child {
        border-bottom: 0;
    }

    .shop-empty {
        padding: 48px 28px;
        text-align: center;
    }

    .shop-flash {
        padding: 14px 18px;
        border-radius: 16px;
        margin-bottom: 18px;
    }

    .shop-flash--success {
        background: rgba(44, 199, 118, 0.14);
        color: #bdf3d4;
        border: 1px solid rgba(44, 199, 118, 0.2);
    }

    .shop-flash--error {
        background: rgba(255, 90, 90, 0.12);
        color: #ffd6d6;
        border: 1px solid rgba(255, 90, 90, 0.18);
    }

    .shop-order-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 34px;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .shop-order-badge::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ffd24f;
    }

    @media (max-width: 767px) {
        .shop-page {
            padding-top: 100px;
        }

        .shop-table thead {
            display: none;
        }

        .shop-table,
        .shop-table tbody,
        .shop-table tr,
        .shop-table td {
            display: block;
            width: 100%;
        }

        .shop-table td {
            padding: 10px 0;
        }
    }
</style>
