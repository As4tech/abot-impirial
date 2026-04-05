<x-guest-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&family=Jost:wght@300;400;500&display=swap');

    :root {
        --forest:   #1a2e1e;
        --forest-2: #243328;
        --moss:     #2e4a33;
        --sage:     #5a7a5e;
        --terra:    #c4784a;
        --terra-lt: #d9956d;
        --ivory:    #f8f4ee;
        --cream:    #f2ece2;
        --linen:    #e8dfd0;
        --ink:      #1e1a17;
        --ink-md:   #5a5248;
        --ink-lt:   #9a9088;
        --border:   #ddd5c8;
        --gold:     #b89a60;
        --gold-lt:  #d4b87a;
        --focus:    rgba(196,120,74,0.2);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    .lp-root {
        min-height: 100vh;
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        font-family: 'Jost', sans-serif;
        background: var(--forest);
    }

    /* ═══ LEFT — immersive atmosphere panel ════════════ */
    .lp-left {
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 3rem;
        min-height: 100vh;
    }

    .lp-left-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 55% 45% at 65% 30%, rgba(196,120,74,0.22) 0%, transparent 65%),
            radial-gradient(ellipse 70% 55% at -10% 100%, rgba(184,154,96,0.18) 0%, transparent 55%),
            linear-gradient(160deg, #0e1c11 0%, #1a2e1e 40%, #0d1a10 100%);
        z-index: 0;
    }

    .lp-left-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
        background-size: 52px 52px;
        z-index: 1;
        pointer-events: none;
    }

    .lp-botanical {
        position: absolute;
        top: -2rem;
        right: -3rem;
        width: 22rem;
        opacity: 0.07;
        z-index: 1;
        pointer-events: none;
        animation: slowDrift 18s ease-in-out infinite alternate;
    }

    @keyframes slowDrift {
        from { transform: translateY(0) rotate(0deg); }
        to   { transform: translateY(-12px) rotate(1.5deg); }
    }

    .lp-brand {
        position: absolute;
        top: 2.75rem;
        left: 3rem;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: fadeIn 1s ease both;
    }

    .lp-brand img {
        height: 2rem;
        width: auto;
        filter: brightness(0) invert(1);
        opacity: 0.85;
    }

    .lp-brand-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem;
        font-weight: 400;
        color: rgba(255,255,255,0.75);
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .lp-badges {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        display: flex;
        gap: 2.5rem;
        opacity: 0.5;
        animation: fadeIn 1.4s ease 0.3s both;
    }

    .lp-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .lp-badge-icon {
        width: 2.5rem;
        height: 2.5rem;
        border: 1px solid rgba(212,184,122,0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(212,184,122,0.06);
    }

    .lp-badge-icon svg {
        width: 1.1rem;
        height: 1.1rem;
        stroke: var(--gold-lt);
        fill: none;
        stroke-width: 1.5;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .lp-badge-label {
        font-size: 0.58rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.35);
        font-weight: 400;
    }

    .lp-copy {
        position: relative;
        z-index: 2;
        animation: fadeUp 1s ease 0.4s both;
    }

    .lp-tagline-small {
        font-size: 0.68rem;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--terra-lt);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .lp-tagline-small::before {
        content: '';
        display: inline-block;
        width: 1.75rem;
        height: 1px;
        background: var(--terra-lt);
        opacity: 0.7;
    }

    .lp-headline {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2.4rem, 3.2vw, 3.5rem);
        font-weight: 300;
        color: var(--ivory);
        line-height: 1.15;
        margin-bottom: 1.25rem;
    }

    .lp-headline em {
        font-style: italic;
        color: var(--gold-lt);
        font-weight: 300;
    }

    .lp-rule {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin: 1.5rem 0;
        opacity: 0.25;
    }

    .lp-rule-line { flex: 1; height: 1px; background: var(--gold); }

    .lp-rule-diamond {
        width: 5px;
        height: 5px;
        background: var(--gold);
        transform: rotate(45deg);
        flex-shrink: 0;
    }

    .lp-desc {
        font-size: 0.82rem;
        color: rgba(255,255,255,0.35);
        font-weight: 300;
        line-height: 1.7;
        max-width: 24rem;
    }

    /* ═══ RIGHT — login form ════════════════════════════ */
    .lp-right {
        background: var(--ivory);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 2.5rem;
        position: relative;
    }

    .lp-right::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--terra) 0%, var(--gold) 50%, transparent 100%);
    }

    .lp-form-wrap {
        width: 100%;
        max-width: 24rem;
    }

    .lp-mobile-brand {
        display: none;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        margin-bottom: 2rem;
    }

    .lp-mobile-brand img { height: 1.75rem; width: auto; }

    .lp-mobile-brand-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        font-weight: 500;
        color: var(--forest);
        letter-spacing: 0.08em;
    }

    .lp-icon-cluster {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1.75rem;
        animation: fadeIn 0.7s ease both;
    }

    .lp-icon-pip {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        border: 1px solid var(--linen);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--cream);
    }

    .lp-icon-pip svg {
        width: 0.9rem;
        height: 0.9rem;
        stroke: var(--sage);
        fill: none;
        stroke-width: 1.5;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .lp-form-header {
        text-align: center;
        margin-bottom: 2rem;
        animation: fadeUp 0.6s ease 0.1s both;
    }

    .lp-form-eyebrow {
        font-size: 0.65rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--terra);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .lp-form-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.1rem;
        font-weight: 400;
        color: var(--forest);
        line-height: 1.2;
        margin-bottom: 0.4rem;
    }

    .lp-form-sub {
        font-size: 0.8rem;
        color: var(--ink-lt);
        font-weight: 300;
    }

    .lp-form-divider {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.75rem;
        animation: fadeUp 0.6s ease 0.15s both;
    }

    .lp-form-divider-line { flex: 1; height: 1px; background: var(--linen); }

    .lp-form-divider-icon {
        width: 1.75rem;
        height: 1.75rem;
        border: 1px solid var(--linen);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .lp-form-divider-icon svg {
        width: 0.7rem;
        height: 0.7rem;
        stroke: var(--gold);
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .lp-field { margin-bottom: 1.1rem; }

    .lp-field:nth-child(1) { animation: fadeUp 0.6s ease 0.2s both; }
    .lp-field:nth-child(2) { animation: fadeUp 0.6s ease 0.27s both; }

    .lp-field label {
        display: block;
        font-size: 0.68rem;
        font-weight: 500;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--ink-md);
        margin-bottom: 0.45rem;
    }

    .lp-input-wrap { position: relative; }

    .lp-field input[type="email"],
    .lp-field input[type="password"] {
        width: 100%;
        background: var(--cream);
        border: 1px solid var(--border);
        border-radius: 5px;
        padding: 0.72rem 1rem 0.72rem 2.6rem;
        font-family: 'Jost', sans-serif;
        font-size: 0.875rem;
        font-weight: 300;
        color: var(--ink);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        -webkit-appearance: none;
    }

    .lp-field input::placeholder { color: var(--ink-lt); }

    .lp-field input:focus {
        background: #fff;
        border-color: var(--terra);
        box-shadow: 0 0 0 3px var(--focus);
    }

    .lp-input-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        width: 0.95rem;
        height: 0.95rem;
        stroke: var(--ink-lt);
        fill: none;
        stroke-width: 1.5;
        stroke-linecap: round;
        stroke-linejoin: round;
        pointer-events: none;
        transition: stroke 0.2s;
        z-index: 1;
    }

    .lp-input-wrap:focus-within .lp-input-icon { stroke: var(--terra); }

    .lp-field-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        animation: fadeUp 0.6s ease 0.33s both;
    }

    .lp-remember {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        cursor: pointer;
    }

    .lp-remember input[type="checkbox"] {
        width: 0.95rem;
        height: 0.95rem;
        border: 1px solid var(--border);
        border-radius: 2px;
        background: var(--cream);
        accent-color: var(--forest);
        cursor: pointer;
    }

    .lp-remember span {
        font-size: 0.78rem;
        color: var(--ink-md);
        font-weight: 300;
    }

    .lp-forgot {
        font-size: 0.78rem;
        color: var(--terra);
        text-decoration: none;
        font-weight: 400;
        position: relative;
    }

    .lp-forgot::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0; right: 100%;
        height: 1px;
        background: var(--terra);
        transition: right 0.25s ease;
    }

    .lp-forgot:hover::after { right: 0; }

    .lp-btn {
        width: 100%;
        background: var(--forest);
        color: var(--ivory);
        border: none;
        border-radius: 5px;
        padding: 0.85rem 1.5rem;
        font-family: 'Jost', sans-serif;
        font-size: 0.78rem;
        font-weight: 500;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: background 0.3s, transform 0.15s;
        animation: fadeUp 0.6s ease 0.4s both;
    }

    .lp-btn-shimmer {
        position: absolute;
        inset: 0;
        background: linear-gradient(105deg,
            transparent 35%,
            rgba(212,184,122,0.22) 50%,
            transparent 65%);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }

    .lp-btn:hover { background: var(--moss); }
    .lp-btn:hover .lp-btn-shimmer { transform: translateX(100%); }
    .lp-btn:active { transform: scale(0.993); }

    .lp-form-footer {
        text-align: center;
        margin-top: 1.5rem;
        animation: fadeUp 0.6s ease 0.48s both;
    }

    .lp-form-footer p {
        font-size: 0.7rem;
        color: var(--ink-lt);
        font-weight: 300;
        letter-spacing: 0.04em;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
    }

    .lp-form-footer svg {
        width: 0.75rem;
        height: 0.75rem;
        stroke: var(--sage);
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        flex-shrink: 0;
    }

    /* ═══ Animations ════════════════════════════════════ */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* ═══ Responsive ════════════════════════════════════ */
    @media (max-width: 820px) {
        .lp-root { grid-template-columns: 1fr; }
        .lp-left { display: none; }
        .lp-right { padding: 2.5rem 1.5rem; align-items: flex-start; padding-top: 3.5rem; }
        .lp-mobile-brand { display: flex; }
        .lp-right::before { display: none; }
    }
</style>

<div class="lp-root">

    {{-- ════ LEFT — Atmosphere Panel ════ --}}
    <div class="lp-left">
        <div class="lp-left-bg"></div>

        {{-- Botanical SVG ornament --}}
        <svg class="lp-botanical" viewBox="0 0 400 600" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M200 580 C200 580 120 480 80 360 C40 240 100 120 200 80 C300 120 360 240 320 360 C280 480 200 580 200 580Z" stroke="white" stroke-width="1.5"/>
            <path d="M200 580 C200 580 200 400 200 80" stroke="white" stroke-width="1"/>
            <path d="M200 300 C200 300 120 260 90 200" stroke="white" stroke-width="0.75"/>
            <path d="M200 350 C200 350 280 310 310 250" stroke="white" stroke-width="0.75"/>
            <path d="M200 420 C200 420 140 390 110 340" stroke="white" stroke-width="0.75"/>
            <path d="M200 460 C200 460 260 430 300 380" stroke="white" stroke-width="0.75"/>
            <path d="M200 240 C200 240 140 210 110 150" stroke="white" stroke-width="0.75"/>
            <path d="M200 200 C200 200 260 175 295 120" stroke="white" stroke-width="0.75"/>
            <ellipse cx="80" cy="195" rx="30" ry="18" transform="rotate(-30 80 195)" stroke="white" stroke-width="0.75"/>
            <ellipse cx="310" cy="245" rx="30" ry="18" transform="rotate(30 310 245)" stroke="white" stroke-width="0.75"/>
            <ellipse cx="110" cy="335" rx="28" ry="16" transform="rotate(-20 110 335)" stroke="white" stroke-width="0.75"/>
            <ellipse cx="298" cy="375" rx="28" ry="16" transform="rotate(20 298 375)" stroke="white" stroke-width="0.75"/>
        </svg>

        {{-- Brand --}}
        <div class="lp-brand">
            @if(function_exists('setting') && setting('general.logo'))
                <img src="{{ setting('general.logo') }}" alt="Logo">
            @endif
            <span class="lp-brand-name">
                {{ function_exists('setting') ? setting('general.business_name', config('app.name')) : config('app.name') }}
            </span>
        </div>

        {{-- Service badges --}}
        <div class="lp-badges">
            <div class="lp-badge">
                <div class="lp-badge-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 7h18M3 7a2 2 0 00-2 2v8a2 2 0 002 2h18a2 2 0 002-2V9a2 2 0 00-2-2M3 7l2-3h14l2 3"/><circle cx="12" cy="13" r="2"/></svg>
                </div>
                <span class="lp-badge-label">Rooms</span>
            </div>
            <div class="lp-badge">
                <div class="lp-badge-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 2v7c0 1.1.9 2 2 2h2a2 2 0 002-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 00-5 5v6h3l-1 11"/></svg>
                </div>
                <span class="lp-badge-label">Dining</span>
            </div>
            <div class="lp-badge">
                <div class="lp-badge-icon">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </div>
                <span class="lp-badge-label">Bookings</span>
            </div>
        </div>

        {{-- Copy --}}
        <div class="lp-copy">
            <div class="lp-tagline-small">Management Portal</div>
            <h1 class="lp-headline">
                Where <em>hospitality</em><br>meets operations.
            </h1>
            <div class="lp-rule">
                <div class="lp-rule-line"></div>
                <div class="lp-rule-diamond"></div>
                <div class="lp-rule-line"></div>
            </div>
            <p class="lp-desc">
                Manage reservations, rooms, dining, and staff from a single, elegant workspace built for guest houses and restaurants.
            </p>
        </div>
    </div>

    {{-- ════ RIGHT — Login Form ════ --}}
    <div class="lp-right">
        <div class="lp-form-wrap">

            {{-- Mobile brand --}}
            <div class="lp-mobile-brand">
                @if(function_exists('setting') && setting('general.logo'))
                    <img src="{{ setting('general.logo') }}" alt="Logo">
                @endif
                <span class="lp-mobile-brand-name">
                    {{ function_exists('setting') ? setting('general.business_name', config('app.name')) : config('app.name') }}
                </span>
            </div>

            {{-- Icon pips --}}
            <div class="lp-icon-cluster">
                <div class="lp-icon-pip">
                    <svg viewBox="0 0 24 24"><path d="M3 2v7c0 1.1.9 2 2 2h2a2 2 0 002-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 00-5 5v6h3l-1 11"/></svg>
                </div>
                <div class="lp-icon-pip" style="transform:scale(1.18);border-color:var(--terra);background:rgba(196,120,74,0.06)">
                    <svg viewBox="0 0 24 24" style="stroke:var(--terra)"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </div>
                <div class="lp-icon-pip">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
            </div>

            {{-- Header --}}
            <div class="lp-form-header">
                <div class="lp-form-eyebrow">Staff Access</div>
                <h2 class="lp-form-title">Welcome Back</h2>
                <p class="lp-form-sub">Sign in to manage your property & dining.</p>
            </div>

            {{-- Divider --}}
            <div class="lp-form-divider">
                <div class="lp-form-divider-line"></div>
                <div class="lp-form-divider-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2L12 22M2 12L22 12"/></svg>
                </div>
                <div class="lp-form-divider-line"></div>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="lp-field">
                    <label for="email">Email Address</label>
                    <div class="lp-input-wrap">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="your@email.com"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <svg class="lp-input-icon" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="lp-field">
                    <label for="password">Password</label>
                    <div class="lp-input-wrap">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="••••••••••"
                            required
                            autocomplete="current-password"
                        />
                        <svg class="lp-input-icon" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="lp-field-row">
                    <label class="lp-remember" for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="lp-forgot" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="lp-btn">
                    <span class="lp-btn-shimmer"></span>
                    <span style="position:relative;z-index:1;">Access Dashboard</span>
                </button>
            </form>

            <div class="lp-form-footer">
                <p>
                    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Secure, encrypted connection
                </p>
            </div>

        </div>
    </div>

</div>
</x-guest-layout>