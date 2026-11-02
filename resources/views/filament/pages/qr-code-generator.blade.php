<x-filament-panels::page>
    <style>
        .qr-shell{overflow:hidden;border-radius:24px;background:#17294b;padding:8px;box-shadow:0 18px 50px rgba(15,23,42,.2)}
        .qr-workspace{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:28px;border-radius:18px;background:#f8fbff;padding:18px}
        .qr-panel{border:1px solid #cfd8e3;border-radius:12px;background:#fff;padding:18px;box-shadow:0 8px 18px rgba(15,23,42,.08)}
        .qr-panel+.qr-panel{margin-top:16px}
        .qr-panel-title{display:flex;align-items:center;justify-content:space-between;color:#001a44;font-size:14px;font-weight:900}
        .qr-label{display:block;margin-bottom:8px;color:#17294b;font-size:12px;font-weight:800;text-transform:uppercase}
        .qr-url-wrap{position:relative}
        .qr-url-input{height:110px;width:100%;border:1px solid #0b84db;border-radius:8px;padding:16px 18px;color:#1f2937;font-size:20px;font-weight:600;line-height:1.4;outline:none}
        .qr-url-input:focus{border-color:#0b84db;box-shadow:0 0 0 3px rgba(11,132,219,.14)}
        .qr-typewriter{pointer-events:none;position:absolute;left:18px;top:18px;max-width:calc(100% - 36px);overflow:hidden;white-space:nowrap;border-right:2px solid #0b84db;color:#526890;font-size:20px;font-weight:700;animation:qrTyping 4s steps(29,end) infinite alternate,qrCaret .75s step-end infinite}
        @keyframes qrTyping{from{width:0}to{width:29ch}}
        @keyframes qrCaret{50%{border-color:transparent}}
        .qr-help{margin-top:10px;color:#64748b;font-size:12px;font-weight:600}
        .qr-switch-row{margin-top:28px;display:flex;align-items:center;gap:12px;color:#415985;font-size:14px}
        .qr-switch{position:relative;height:24px;width:44px;border-radius:999px;background:#cbd5e1;cursor:pointer;border:none;outline:none;transition:background-color .2s ease;padding:0}
        .qr-switch::after{content:"";position:absolute;left:3px;top:3px;height:18px;width:18px;border-radius:999px;background:#fff;box-shadow:0 1px 4px rgba(15,23,42,.2);transition:transform .2s ease}
        .qr-switch.active{background:#0985db}
        .qr-switch.active::after{transform:translateX(20px)}
        .qr-design-tabs{margin-top:22px;display:grid;grid-template-columns:repeat(3,1fr);border-bottom:1px solid #dce3ec}
        .qr-design-tab{border-bottom:2px solid transparent;padding:14px 8px;color:#4a5d85;text-align:center;font-weight:800;transition:all .15s ease}
        .qr-design-tab.active{border-color:#0788e8;background:#dcefff;color:#0477ca}
        .qr-design-body{padding-top:26px}
        .qr-options{display:flex;flex-wrap:wrap;gap:12px;align-items:center}
        .qr-option{display:flex;height:52px;width:52px;align-items:center;justify-content:center;border:1.5px solid #cbd5e1;border-radius:8px;background:#f8fafc;color:#17294b;font-size:10px;font-weight:900;cursor:pointer;transition:all .15s ease-in-out;outline:none;padding:4px}
        .qr-option:hover{border-color:#38bdf8;background:#f0f9ff}
        .qr-option.active{border:2px solid #008be5 !important;background:#dbeafe !important;box-shadow:0 0 0 1px #008be5}
        .qr-option.none{font-size:26px;color:#526890}
        .qr-preset-badge{border-radius:6px;width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;line-height:1.1;font-weight:900}
        .qr-preset-badge.scanme{background:#0f172a;color:#fff;font-size:9px}
        .qr-preset-badge.scanme span:last-child{color:#38bdf8;font-size:11px}
        .qr-upload-box{margin-top:16px;border:2px dashed #93c5fd;border-radius:12px;padding:16px 20px;background:#f0f9ff;display:flex;align-items:center;gap:14px;cursor:pointer;transition:all .15s ease}
        .qr-upload-box:hover{background:#e0f2fe;border-color:#0284c7}
        .qr-upload-icon{font-size:26px;color:#0284c7}
        .qr-upload-title{font-size:14px;font-weight:800;color:#0369a1}
        .qr-upload-note{color:#64748b;font-size:12px;font-weight:600}
        .qr-shapes-list{display:flex;flex-wrap:wrap;gap:12px;align-items:center}
        .qr-shape-btn{display:flex;height:52px;width:52px;align-items:center;justify-content:center;border:1.5px solid #cbd5e1;border-radius:8px;background:#f8fafc;padding:6px;cursor:pointer;transition:all .15s ease-in-out;outline:none}
        .qr-shape-btn:hover{border-color:#38bdf8;background:#f0f9ff}
        .qr-shape-btn.active{border:2px solid #008be5 !important;background:#dbeafe !important;box-shadow:0 0 0 1px #008be5}
        .qr-shape-icon{display:flex;align-items:center;justify-content:center;width:36px;height:36px;pointer-events:none}
        .qr-shape-icon svg{display:block;width:32px;height:32px}
        .qr-upload{margin-top:28px;color:#0386df;font-weight:900;cursor:pointer}
        .qr-color-row{display:flex;flex-wrap:wrap;gap:14px;margin-top:24px}
        .qr-color-field{position:relative;border:1px solid #cbd5e1;border-radius:6px;padding:10px 42px 8px 12px;color:#263b61;background:#fff}
        .qr-color-field span{position:absolute;top:-11px;left:8px;background:#fff;padding:0 4px;color:#526890;font-size:12px;font-weight:800}
        .qr-color-field input[type=text]{width:90px;border:0;background:transparent;font-weight:700;outline:none;color:#1e293b}
        .qr-color-field input[type=color]{position:absolute;right:8px;bottom:8px;height:24px;width:24px;border:1px solid #cbd5e1;border-radius:4px;padding:0;background:#fff;cursor:pointer}
        .qr-preview{display:flex;min-height:500px;flex-direction:column;align-items:center;justify-content:center}
        .qr-pill{margin-bottom:14px;border-radius:999px;background:#ffaad1;padding:3px 10px;color:#6b1740;font-size:11px;font-weight:800}
        .qr-frame{position:relative;display:flex;flex-direction:column;align-items:center}
        .qr-code-box{border:7px solid #050505;border-radius:9px;background:#fff;padding:14px;display:flex;align-items:center;justify-content:center;min-width:190px;min-height:190px;transition:all .2s ease}
        .qr-code-box.frame-none{border:1px solid #cbd5e1;box-shadow:0 4px 14px rgba(15,23,42,.08)}
        .qr-code-box.frame-simple{border:7px solid #050505}
        .qr-code-box.frame-scan{border:7px solid #050505}
        .qr-canvas-target canvas, .qr-canvas-target svg{display:block !important;max-width:160px !important;max-height:160px !important;height:auto !important;width:auto !important}
        .qr-scan-label{position:relative;margin-top:28px;border-radius:7px;background:#050505;padding:7px 28px;color:#fff;font-size:30px;font-weight:900;line-height:1}
        .qr-scan-label::before{content:"";position:absolute;left:50%;top:-20px;transform:translateX(-50%);border-right:22px solid transparent;border-bottom:22px solid #050505;border-left:22px solid transparent}
        .qr-empty-box{border:2px dashed #93c5fd;border-radius:14px;background:#f0f9ff;padding:24px 16px;display:flex;flex-direction:column;align-items:center;justify-content:center;min-width:210px;min-height:210px;text-align:center;box-sizing:border-box}
        .qr-empty-icon{margin-bottom:8px;color:#0284c7}
        .qr-empty-title{font-size:13px;font-weight:800;color:#0369a1}
        .qr-empty-desc{margin-top:4px;font-size:11px;font-weight:600;color:#64748b;line-height:1.3;max-width:170px}
        .qr-stats{margin-top:16px;width:180px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:10px 14px;box-shadow:0 2px 8px rgba(15,23,42,.04)}
        .qr-stat{display:flex;align-items:center;justify-content:space-between;padding:5px 0;border-bottom:1px solid #f8fafc;font-size:12px;font-weight:700;color:#64748b}
        .qr-stat:last-child{border-bottom:none}
        .qr-stat-val{font-weight:800;color:#0f172a;background:#f1f5f9;border-radius:4px;padding:1px 8px;font-size:11px}
        .qr-download{margin-top:34px;display:flex;align-items:center;overflow:hidden;border-radius:999px;background:#0788df;color:#fff;box-shadow:0 4px 10px rgba(7,136,223,.3);transition:opacity .2s ease}
        .qr-download button:first-child{padding:12px 52px;color:#fff;font-weight:900;text-decoration:none}
        .qr-download button:last-child{height:44px;width:44px;border-left:1px solid rgba(255,255,255,.35);font-size:24px}
        .qr-logo-actions{display:flex;align-items:center;gap:12px;margin-top:16px;background:#f8fafc;border:1px solid #cbd5e1;border-radius:12px;padding:12px 16px}
        .qr-remove-logo{border-radius:999px;background:#fee2e2;padding:6px 14px;color:#b91c1c;font-size:12px;font-weight:800;border:1px solid #fca5a5;cursor:pointer;transition:all .15s ease}
        .qr-remove-logo:hover{background:#fecaca}
        .qr-change-logo{border-radius:999px;background:#fff;padding:6px 14px;color:#334155;font-size:12px;font-weight:800;border:1px solid #cbd5e1;cursor:pointer;transition:all .15s ease}
        .qr-change-logo:hover{background:#f1f5f9;border-color:#94a3b8}
        @media (max-width:900px){.qr-workspace{grid-template-columns:1fr}.qr-preview{min-height:auto;padding:24px 0}.qr-url-input{font-size:16px}.qr-shell{border-radius:16px}.qr-workspace{padding:12px}}
    </style>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-danger-50 px-4 py-3 text-sm font-semibold text-danger-700 ring-1 ring-danger-600/20 dark:bg-danger-500/10 dark:text-danger-300" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @php
        $initialShape = old('shape', ($latestQrCode?->shape === 'dot' ? 'dots' : $latestQrCode?->shape) ?? 'square');
        $qrInitialState = [
            'link' => old('link', $editing?->link ?? ''),
            'qr_color' => old('qr_color', $latestQrCode?->qr_color ?? '#000000'),
            'background_color' => old('background_color', $latestQrCode?->background_color ?? '#ffffff'),
            'frame' => old('frame', $latestQrCode?->frame ?? 'scan'),
            'shape' => $initialShape,
            'scan_tracking' => (bool) old('scan_tracking', $latestQrCode?->scan_tracking ?? true),
            'total_scans' => (int) ($editing?->total_scans ?? 0),
            'unique_scans' => (int) ($editing?->unique_scans ?? 0),
            'top_location' => $editing?->top_location ?? '-',
            // Relative so the logo stays same-origin and can be drawn on the canvas on any host.
            'logo_url' => $editing?->logo_path ? '/storage/' . $editing->logo_path : null,
            'tracking_token' => $trackingToken,
            'tracking_base_url' => \App\Models\QrCode::trackingBaseUrl(),
            'open_logo_panel' => $errors->has('logo'),
        ];
    @endphp

    <div class="qr-shell" x-data="qrCodeGenerator(@js($qrInitialState))">
        <form class="qr-workspace" method="POST" action="{{ route('qr-code-generator.store') }}" enctype="multipart/form-data" x-on:submit="prepareAndSubmit($event)">
            @csrf
            <input type="hidden" name="qr_image_data" x-ref="qrImageData">
            <input type="hidden" name="tracking_token" :value="trackingToken">
            @if ($editing)
                <input type="hidden" name="qr_code_id" value="{{ $editing->id }}">
            @endif
            <input type="hidden" name="scan_tracking" :value="scanTracking ? '1' : '0'">

            <div>
                <section class="qr-panel">
                    <div class="qr-panel-title">CONTENT <span>⌃</span></div>
                    <div class="mt-8">
                        <label class="qr-label" for="qr-url">YOUR WEBSITE URL</label>
                        <div class="qr-url-wrap">
                            <input id="qr-url" name="link" class="qr-url-input" type="text" inputmode="url" autocomplete="url" x-model.debounce.300ms="url">
                            <span class="qr-typewriter" x-show="!url || !url.trim()">www.my-website.com/donations</span>
                        </div>
                        <p class="qr-help">Your QR Code will be generated automatically</p>
                    </div>
                    <div class="qr-switch-row">
                        <button type="button" 
                            class="qr-switch" 
                            :class="{ active: scanTracking }" 
                            x-on:click="scanTracking = !scanTracking" 
                            role="switch" 
                            :aria-checked="scanTracking">
                        </button>
                        <span class="cursor-pointer font-bold select-none text-slate-800" x-on:click="scanTracking = !scanTracking">
                            Scan tracking
                        </span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold transition-all" 
                            :class="scanTracking ? 'bg-sky-100 text-sky-700' : 'bg-slate-200 text-slate-500'" 
                            x-text="scanTracking ? 'Aktif' : 'Nonaktif'">
                        </span>
                    </div>
                </section>

                <section class="qr-panel">
                    <div class="qr-panel-title">DESIGN <span>⌃</span></div>
                    <div class="qr-design-tabs">
                        <button type="button" class="qr-design-tab" :class="{ active: panel === 'frame' }" x-on:click="panel = 'frame'">Frame</button>
                        <button type="button" class="qr-design-tab" :class="{ active: panel === 'logo' }" x-on:click="panel = 'logo'">Logo</button>
                        <button type="button" class="qr-design-tab" :class="{ active: panel === 'color' }" x-on:click="panel = 'color'">Color & Shape</button>
                    </div>

                    <div class="qr-design-body" x-show="panel === 'frame'">
                        <div class="qr-options">
                            <button type="button" class="qr-option none" :class="{ active: frame === 'none' }" x-on:click="frame = 'none'">⊘</button>
                            <template x-for="item in frames" :key="item">
                                <button type="button" class="qr-option" :class="{ active: frame === item }" x-on:click="frame = item">
                                    <span x-text="item === 'scan' ? 'SCAN ME' : 'QR'"></span>
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="frame" x-model="frame">
                    </div>

                    <div class="qr-design-body" x-show="panel === 'logo'">
                        <div class="qr-options">
                            <button type="button" 
                                class="qr-option none" 
                                :class="{ active: !logoUrl || selectedLogoType === 'none' }" 
                                title="Tanpa Logo / Hapus Logo"
                                x-on:click="removeLogo()">
                                ⊘
                            </button>

                            <button type="button" 
                                class="qr-option qr-logo-preset" 
                                :class="{ active: selectedLogoType === 'scanme' }" 
                                title="Preset Logo: SCAN ME"
                                x-on:click="setPresetLogo('scanme')">
                                <div class="qr-preset-badge scanme">
                                    <span>SCAN</span>
                                    <span>ME</span>
                                </div>
                            </button>

                            <button type="button" 
                                class="qr-option qr-logo-preset" 
                                :class="{ active: selectedLogoType === 'web' }" 
                                title="Preset Logo: Website"
                                x-on:click="setPresetLogo('web')">
                                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="#0284c7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                </svg>
                            </button>

                            <template x-if="selectedLogoType === 'custom' && logoUrl">
                                <button type="button" 
                                    class="qr-option active" 
                                    title="Logo Kustom Anda (Klik untuk ganti)"
                                    x-on:click="$refs.logoInput.click()">
                                    <img :src="logoUrl" alt="Custom Logo" class="h-10 w-10 object-contain rounded">
                                </button>
                            </template>

                            <button type="button" 
                                class="qr-option" 
                                title="Upload Logo Kustom Anda"
                                x-on:click="$refs.logoInput.click()">
                                <span class="text-xl font-bold text-sky-600">+</span>
                            </button>
                        </div>

                        <div class="mt-4">
                            <input id="qr-logo" 
                                x-ref="logoInput" 
                                class="hidden" 
                                type="file" 
                                name="logo" 
                                accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml" 
                                x-on:change="setLogoPreview($event)">
                            <input type="hidden" name="remove_logo" x-model="removeLogoValue">

                            <div x-show="!logoUrl" class="qr-upload-box" x-on:click="$refs.logoInput.click()">
                                <span class="qr-upload-icon">☁</span>
                                <div>
                                    <div class="qr-upload-title">Upload your logo</div>
                                    <div class="qr-upload-note">File types: png, jpg, webp, gif atau svg — maksimal 10 MB</div>
                                </div>
                            </div>

                            <div class="qr-logo-actions" x-show="logoUrl">
                                <img class="h-12 w-12 rounded-lg bg-white object-contain p-1 ring-1 ring-slate-200 shadow-sm" 
                                    :src="logoUrl" 
                                    alt="Logo preview">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-slate-800 truncate" x-text="logoTitle()"></div>
                                    <div class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                        <span>✓</span> Logo terpasang di QR code
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button class="qr-change-logo" type="button" x-on:click="$refs.logoInput.click()">
                                        Ganti Logo
                                    </button>
                                    <button class="qr-remove-logo" type="button" x-on:click="removeLogo()">
                                        Hapus Logo
                                    </button>
                                </div>
                            </div>

                            <p class="mt-2 text-sm font-semibold text-danger-600" role="alert" x-show="logoError" x-text="logoError"></p>
                        </div>
                    </div>

                    <div class="qr-design-body" x-show="panel === 'color'">
                        <div class="qr-shapes-list">
                            <template x-for="s in shapes" :key="s.id">
                                <button type="button" 
                                    class="qr-shape-btn" 
                                    :class="{ active: selectedShape === s.id }" 
                                    :title="s.label"
                                    x-on:click="selectShape(s.id)">
                                    <div :id="'qr-shape-preview-' + s.id" class="qr-shape-icon" x-html="s.svg"></div>
                                </button>
                            </template>
                        </div>
                        <div class="qr-color-row">
                            <label class="qr-color-field">
                                <span>QR Code Color</span>
                                <input type="text" name="qr_color" x-model="qrColor">
                                <input type="color" x-model="qrColor">
                            </label>
                            <label class="qr-color-field">
                                <span>Background Color</span>
                                <input type="text" name="background_color" x-model="bgColor">
                                <input type="color" x-model="bgColor">
                            </label>
                        </div>
                        <input type="hidden" name="shape" x-model="selectedShape">
                    </div>
                </section>
            </div>

            <aside class="qr-preview">
                <div class="qr-pill">↻ Dynamic QR Code</div>

                <div class="qr-frame">
                    <!-- TAMPILKAN QR JIKA ADA LINK -->
                    <div x-show="hasUrl()">
                        <div class="qr-code-box" :class="'frame-' + frame" :style="{ backgroundColor: bgColor }">
                            <div id="qr-main-target" class="qr-canvas-target" x-show="mainQrReady"></div>
                        </div>
                        <div class="qr-scan-label" x-show="frame === 'scan'">SCAN ME</div>
                    </div>

                    <!-- PLACEHOLDER JIKA BELUM ADA LINK -->
                    <div x-show="!hasUrl()" class="qr-empty-box">
                        <div class="qr-empty-icon">
                            <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="#0284c7" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                                <path d="M14 14h3v3h-3z"></path>
                                <path d="M20 14v3"></path>
                                <path d="M14 20h6"></path>
                            </svg>
                        </div>
                        <div class="qr-empty-title">Belum Ada Link</div>
                        <div class="qr-empty-desc">Masukkan URL website Anda untuk melihat pratinjau QR Code</div>
                    </div>
                </div>

                <!-- SCAN TRACKING STATS -->
                <div class="qr-stats">
                    <div class="qr-stat">
                        <span class="text-slate-600 font-semibold">Tracking Status</span>
                        <span class="font-bold text-xs" :class="scanTracking ? 'text-emerald-600' : 'text-slate-400'" x-text="scanTracking ? '● Aktif' : '○ Nonaktif'"></span>
                    </div>
                    <div class="qr-stat" :class="{ 'opacity-40': !scanTracking }">
                        <span>Total scans</span>
                        <span class="qr-stat-val" x-text="totalScans">0</span>
                    </div>
                    <div class="qr-stat" :class="{ 'opacity-40': !scanTracking }">
                        <span>Unique scans</span>
                        <span class="qr-stat-val" x-text="uniqueScans">0</span>
                    </div>
                    <div class="qr-stat" :class="{ 'opacity-40': !scanTracking }">
                        <span>Top location</span>
                        <span class="qr-stat-val" x-text="topLocation">-</span>
                    </div>
                </div>

                <p class="mt-4 max-w-56 text-center text-xs font-semibold text-danger-600" role="alert" x-show="saveError" x-text="saveError"></p>
                <div class="qr-download" :class="{ 'opacity-50 cursor-not-allowed': !hasUrl() }">
                    <button type="button" :disabled="!hasUrl()" x-on:click="downloadJpg()">⇩ DOWNLOAD JPG</button>
                    <button type="button" :disabled="!hasUrl()" aria-label="Download options">⌄</button>
                </div>

                <button type="submit" 
                    class="mt-4 rounded-full bg-slate-950 px-8 py-3 text-sm font-black text-white shadow transition-all hover:bg-slate-800"
                    :class="{ 'opacity-50 cursor-not-allowed hover:bg-slate-950': !hasUrl() || saving }"
                    :disabled="!hasUrl() || saving"
                    x-text="saving ? 'MENYIMPAN...' : @js($editing ? 'UPDATE QR CODE' : 'SAVE QR CODE')">
                </button>

                @if ($editing)
                    <p class="mt-3 text-center text-xs font-semibold text-slate-500">
                        Last saved: {{ $editing->updated_at->format('d M Y H:i') }}
                    </p>
                @endif
            </aside>
        </form>
    </div>

    <script src="{{ asset('js/qr-code-styling.js') }}"></script>
    <script>
        function qrCodeGenerator(initialState) {
            return {
                url: initialState.link || '',
                panel: initialState.open_logo_panel ? 'logo' : 'color',
                frame: initialState.frame || 'scan',
                qrColor: initialState.qr_color || '#000000',
                bgColor: initialState.background_color || '#ffffff',
                selectedShape: initialState.shape === 'dot' ? 'dots' : (initialState.shape || 'square'),
                scanTracking: Boolean(initialState.scan_tracking ?? true),
                totalScans: initialState.total_scans ?? 0,
                uniqueScans: initialState.unique_scans ?? 0,
                topLocation: initialState.top_location || '-',
                logoUrl: initialState.logo_url,
                selectedLogoType: initialState.logo_url ? 'custom' : 'none',
                logoFileName: initialState.logo_url ? 'Logo Tersimpan' : '',
                removeLogoValue: '0',
                mainQrReady: false,
                saving: false,
                saveError: '',
                logoError: '',
                trackingToken: initialState.tracking_token,
                trackingBaseUrl: initialState.tracking_base_url,
                mainQr: null,
                frames: ['simple', 'scan'],
                presets: {
                    scanme: 'data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20100%20100%22%20width%3D%22100%22%20height%3D%22100%22%3E%3Crect%20width%3D%22100%22%20height%3D%22100%22%20rx%3D%2220%22%20fill%3D%22%230f172a%22%2F%3E%3Ctext%20x%3D%2250%22%20y%3D%2244%22%20fill%3D%22%23ffffff%22%20font-family%3D%22sans-serif%22%20font-size%3D%2224%22%20font-weight%3D%22900%22%20text-anchor%3D%22middle%22%3ESCAN%3C%2Ftext%3E%3Ctext%20x%3D%2250%22%20y%3D%2276%22%20fill%3D%22%2338bdf8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2228%22%20font-weight%3D%22900%22%20text-anchor%3D%22middle%22%3EME%3C%2Ftext%3E%3C%2Fsvg%3E',
                    web: 'data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20100%20100%22%20width%3D%22100%22%20height%3D%22100%22%3E%3Crect%20width%3D%22100%22%20height%3D%22100%22%20rx%3D%2220%22%20fill%3D%22%230284c7%22%2F%3E%3Ccircle%20cx%3D%2250%22%20cy%3D%2250%22%20r%3D%2230%22%20fill%3D%22none%22%20stroke%3D%22%23ffffff%22%20stroke-width%3D%225%22%2F%3E%3Cellipse%20cx%3D%2250%22%20cy%3D%2250%22%20rx%3D%2214%22%20ry%3D%2230%22%20fill%3D%22none%22%20stroke%3D%22%23ffffff%22%20stroke-width%3D%225%22%2F%3E%3Cline%20x1%3D%2220%22%20y1%3D%2250%22%20x2%3D%2280%22%20y2%3D%2250%22%20stroke%3D%22%23ffffff%22%20stroke-width%3D%225%22%2F%3E%3C%2Fsvg%3E'
                },
                shapes: [
                    {
                        id: 'square',
                        label: 'Square',
                        svg: `<svg viewBox="0 0 34 34" width="32" height="32" fill="#17294b"><rect x="2" y="2" width="10" height="10" fill="none" stroke="#17294b" stroke-width="2.2"/><rect x="5.5" y="5.5" width="3" height="3"/><rect x="22" y="2" width="10" height="10" fill="none" stroke="#17294b" stroke-width="2.2"/><rect x="25.5" y="5.5" width="3" height="3"/><rect x="2" y="22" width="10" height="10" fill="none" stroke="#17294b" stroke-width="2.2"/><rect x="5.5" y="25.5" width="3" height="3"/><rect x="14" y="3" width="2.4" height="2.4"/><rect x="18" y="3" width="2.4" height="2.4"/><rect x="14" y="7" width="2.4" height="2.4"/><rect x="14" y="11" width="2.4" height="2.4"/><rect x="18" y="11" width="2.4" height="2.4"/><rect x="3" y="14" width="2.4" height="2.4"/><rect x="7" y="14" width="2.4" height="2.4"/><rect x="14" y="15" width="2.4" height="2.4"/><rect x="18" y="15" width="2.4" height="2.4"/><rect x="22" y="15" width="2.4" height="2.4"/><rect x="28.5" y="15" width="2.4" height="2.4"/><rect x="14" y="19" width="2.4" height="2.4"/><rect x="14" y="23" width="2.4" height="2.4"/><rect x="18" y="23" width="2.4" height="2.4"/><rect x="14" y="28.5" width="2.4" height="2.4"/><rect x="22" y="19" width="2.4" height="2.4"/><rect x="25" y="22" width="4" height="4"/><rect x="28.5" y="19" width="2.4" height="2.4"/><rect x="22" y="26.5" width="2.4" height="2.4"/><rect x="26.5" y="26.5" width="2.4" height="2.4"/><rect x="22" y="29.5" width="2.4" height="2.4"/></svg>`
                    },
                    {
                        id: 'dots',
                        label: 'Dots',
                        svg: `<svg viewBox="0 0 34 34" width="32" height="32" fill="#17294b"><circle cx="7" cy="7" r="5" fill="none" stroke="#17294b" stroke-width="2.2"/><circle cx="7" cy="7" r="2.2"/><circle cx="27" cy="7" r="5" fill="none" stroke="#17294b" stroke-width="2.2"/><circle cx="27" cy="7" r="2.2"/><circle cx="7" cy="27" r="5" fill="none" stroke="#17294b" stroke-width="2.2"/><circle cx="7" cy="27" r="2.2"/><circle cx="15.2" cy="4.2" r="1.3"/><circle cx="19.2" cy="4.2" r="1.3"/><circle cx="15.2" cy="8.2" r="1.3"/><circle cx="15.2" cy="12.2" r="1.3"/><circle cx="19.2" cy="12.2" r="1.3"/><circle cx="4.2" cy="15.2" r="1.3"/><circle cx="8.2" cy="15.2" r="1.3"/><circle cx="15.2" cy="16.2" r="1.3"/><circle cx="19.2" cy="16.2" r="1.3"/><circle cx="23.2" cy="16.2" r="1.3"/><circle cx="29.8" cy="16.2" r="1.3"/><circle cx="15.2" cy="20.2" r="1.3"/><circle cx="15.2" cy="24.2" r="1.3"/><circle cx="19.2" cy="24.2" r="1.3"/><circle cx="15.2" cy="29.8" r="1.3"/><circle cx="23.2" cy="20.2" r="1.3"/><circle cx="27" cy="23.5" r="2.2"/><circle cx="29.8" cy="20.2" r="1.3"/><circle cx="23.2" cy="27.8" r="1.3"/><circle cx="27.8" cy="27.8" r="1.3"/><circle cx="23.2" cy="30.8" r="1.3"/></svg>`
                    },
                    {
                        id: 'rounded',
                        label: 'Rounded',
                        svg: `<svg viewBox="0 0 34 34" width="32" height="32" fill="#17294b"><rect x="2" y="2" width="10" height="10" rx="3" fill="none" stroke="#17294b" stroke-width="2.2"/><rect x="5.5" y="5.5" width="3" height="3" rx="1.2"/><rect x="22" y="2" width="10" height="10" rx="3" fill="none" stroke="#17294b" stroke-width="2.2"/><rect x="25.5" y="5.5" width="3" height="3" rx="1.2"/><rect x="2" y="22" width="10" height="10" rx="3" fill="none" stroke="#17294b" stroke-width="2.2"/><rect x="5.5" y="25.5" width="3" height="3" rx="1.2"/><rect x="14" y="3" width="2.4" height="2.4" rx="0.9"/><rect x="18" y="3" width="2.4" height="2.4" rx="0.9"/><rect x="14" y="7" width="2.4" height="2.4" rx="0.9"/><rect x="14" y="11" width="2.4" height="2.4" rx="0.9"/><rect x="18" y="11" width="2.4" height="2.4" rx="0.9"/><rect x="3" y="14" width="2.4" height="2.4" rx="0.9"/><rect x="7" y="14" width="2.4" height="2.4" rx="0.9"/><rect x="14" y="15" width="2.4" height="2.4" rx="0.9"/><rect x="18" y="15" width="2.4" height="2.4" rx="0.9"/><rect x="22" y="15" width="2.4" height="2.4" rx="0.9"/><rect x="28.5" y="15" width="2.4" height="2.4" rx="0.9"/><rect x="14" y="19" width="2.4" height="2.4" rx="0.9"/><rect x="14" y="23" width="2.4" height="2.4" rx="0.9"/><rect x="18" y="23" width="2.4" height="2.4" rx="0.9"/><rect x="14" y="28.5" width="2.4" height="2.4" rx="0.9"/><rect x="22" y="19" width="2.4" height="2.4" rx="0.9"/><rect x="25" y="22" width="4" height="4" rx="1.5"/><rect x="28.5" y="19" width="2.4" height="2.4" rx="0.9"/><rect x="22" y="26.5" width="2.4" height="2.4" rx="0.9"/><rect x="26.5" y="26.5" width="2.4" height="2.4" rx="0.9"/><rect x="22" y="29.5" width="2.4" height="2.4" rx="0.9"/></svg>`
                    },
                    {
                        id: 'extra-rounded',
                        label: 'Extra Rounded',
                        svg: `<svg viewBox="0 0 34 34" width="32" height="32" fill="#17294b"><rect x="2" y="2" width="10" height="10" rx="4.5" fill="none" stroke="#17294b" stroke-width="2.2"/><circle cx="7" cy="7" r="2.2"/><rect x="22" y="2" width="10" height="10" rx="4.5" fill="none" stroke="#17294b" stroke-width="2.2"/><circle cx="27" cy="7" r="2.2"/><rect x="2" y="22" width="10" height="10" rx="4.5" fill="none" stroke="#17294b" stroke-width="2.2"/><circle cx="7" cy="27" r="2.2"/><rect x="14" y="3" width="2.4" height="6.4" rx="1.2"/><rect x="18" y="3" width="2.4" height="2.4" rx="1.2"/><rect x="14" y="11" width="6.4" height="2.4" rx="1.2"/><rect x="3" y="14" width="6.4" height="2.4" rx="1.2"/><rect x="14" y="15" width="2.4" height="6.4" rx="1.2"/><rect x="18" y="15" width="2.4" height="2.4" rx="1.2"/><rect x="22" y="15" width="2.4" height="6.4" rx="1.2"/><rect x="28.5" y="15" width="2.4" height="2.4" rx="1.2"/><rect x="14" y="23" width="6.4" height="2.4" rx="1.2"/><rect x="14" y="27" width="2.4" height="4.8" rx="1.2"/><rect x="25" y="22" width="4" height="4" rx="2"/><rect x="28.5" y="19" width="2.4" height="2.4" rx="1.2"/><rect x="22" y="26.5" width="2.4" height="5" rx="1.2"/><rect x="26.5" y="26.5" width="2.4" height="2.4" rx="1.2"/></svg>`
                    },
                    {
                        id: 'classy',
                        label: 'Classy',
                        svg: `<svg viewBox="0 0 34 34" width="32" height="32" fill="#17294b"><rect x="2" y="2" width="10" height="10" rx="3.5" fill="none" stroke="#17294b" stroke-width="2.2"/><rect x="5.5" y="5.5" width="3" height="3" rx="1.5"/><rect x="22" y="2" width="10" height="10" rx="3.5" fill="none" stroke="#17294b" stroke-width="2.2"/><rect x="25.5" y="5.5" width="3" height="3" rx="1.5"/><rect x="2" y="22" width="10" height="10" rx="3.5" fill="none" stroke="#17294b" stroke-width="2.2"/><rect x="5.5" y="25.5" width="3" height="3" rx="1.5"/><path d="M 14 3 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -1.2 -1.2 z"/><path d="M 18 3 h 1.2 a 1.2 1.2 0 0 1 1.2 1.2 v 1.2 h -2.4 z"/><rect x="14" y="7" width="2.4" height="2.4"/><path d="M 14 11 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -1.2 -1.2 z"/><rect x="18" y="11" width="2.4" height="2.4"/><rect x="3" y="14" width="2.4" height="2.4"/><path d="M 7 14 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -1.2 -1.2 z"/><rect x="14" y="15" width="2.4" height="2.4"/><rect x="18" y="15" width="2.4" height="2.4"/><rect x="22" y="15" width="2.4" height="2.4"/><path d="M 28.5 15 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -1.2 -1.2 z"/><rect x="14" y="19" width="2.4" height="2.4"/><path d="M 14 23 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -1.2 -1.2 z"/><rect x="18" y="23" width="2.4" height="2.4"/><rect x="14" y="28.5" width="2.4" height="2.4"/><path d="M 22 19 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -1.2 -1.2 z"/><rect x="25" y="22" width="4" height="4" rx="1.5"/><rect x="28.5" y="19" width="2.4" height="2.4"/><path d="M 22 26.5 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -1.2 -1.2 z"/><rect x="26.5" y="26.5" width="2.4" height="2.4"/><rect x="22" y="29.5" width="2.4" height="2.4"/></svg>`
                    },
                    {
                        id: 'classy-rounded',
                        label: 'Classy Rounded',
                        svg: `<svg viewBox="0 0 34 34" width="32" height="32" fill="#17294b"><rect x="2" y="2" width="10" height="10" rx="4" fill="none" stroke="#17294b" stroke-width="2.2"/><circle cx="7" cy="7" r="2.2"/><rect x="22" y="2" width="10" height="10" rx="4" fill="none" stroke="#17294b" stroke-width="2.2"/><circle cx="27" cy="7" r="2.2"/><rect x="2" y="22" width="10" height="10" rx="4" fill="none" stroke="#17294b" stroke-width="2.2"/><circle cx="7" cy="27" r="2.2"/><path d="M 14 3 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -2.4 0 z"/><path d="M 18 3 h 1.2 a 1.2 1.2 0 0 1 1.2 1.2 v 1.2 h -2.4 z"/><rect x="14" y="7" width="2.4" height="2.4" rx="0.9"/><path d="M 14 11 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -2.4 0 z"/><rect x="18" y="11" width="2.4" height="2.4" rx="0.9"/><rect x="3" y="14" width="2.4" height="2.4" rx="0.9"/><path d="M 7 14 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -2.4 0 z"/><rect x="14" y="15" width="2.4" height="2.4" rx="0.9"/><rect x="18" y="15" width="2.4" height="2.4" rx="0.9"/><rect x="22" y="15" width="2.4" height="2.4" rx="0.9"/><path d="M 28.5 15 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -2.4 0 z"/><rect x="14" y="19" width="2.4" height="2.4" rx="0.9"/><path d="M 14 23 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -2.4 0 z"/><rect x="18" y="23" width="2.4" height="2.4" rx="0.9"/><rect x="14" y="28.5" width="2.4" height="2.4" rx="0.9"/><path d="M 22 19 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -2.4 0 z"/><rect x="25" y="22" width="4" height="4" rx="2"/><rect x="28.5" y="19" width="2.4" height="2.4" rx="0.9"/><path d="M 22 26.5 h 2.4 v 2.4 a 1.2 1.2 0 0 1 -2.4 0 z"/><rect x="26.5" y="26.5" width="2.4" height="2.4" rx="0.9"/><rect x="22" y="29.5" width="2.4" height="2.4" rx="0.9"/></svg>`
                    }
                ],
                hasUrl() {
                    return Boolean(this.url && this.url.trim().length > 0);
                },
                normalizedUrl() {
                    const value = (this.url || '').trim();
                    return /^[a-z][a-z0-9+.-]*:/i.test(value) ? value : `https://${value}`;
                },
                qrData() {
                    return this.scanTracking
                        ? `${this.trackingBaseUrl}/qr/${this.trackingToken}`
                        : this.normalizedUrl();
                },
                logoTitle() {
                    if (this.selectedLogoType === 'scanme') return 'Preset: SCAN ME Badge';
                    if (this.selectedLogoType === 'web') return 'Preset: Web / Globe';
                    return this.logoFileName || 'Logo Kustom';
                },
                async setPresetLogo(type) {
                    if (!this.presets[type]) return;
                    await this.applyLogo(this.presets[type], type, '');
                },
                init() {
                    this.$nextTick(() => {
                        if (this.hasUrl()) {
                            this.initMainQr();
                        }
                        this.initShapePreviews();
                    });
                    this.$watch('url', () => {
                        if (this.hasUrl()) {
                            this.updateMainQr();
                        } else {
                            this.mainQrReady = false;
                        }
                    });
                    ['selectedShape', 'qrColor', 'bgColor', 'logoUrl', 'scanTracking'].forEach((key) => {
                        this.$watch(key, () => this.updateMainQr());
                    });
                },
                getCornerSquareType(shape) {
                    if (shape === 'dots') return 'dot';
                    if (shape === 'rounded' || shape === 'extra-rounded' || shape === 'classy' || shape === 'classy-rounded') {
                        return 'extra-rounded';
                    }
                    return 'square';
                },
                getCornerDotType(shape) {
                    if (shape === 'dots') return 'dot';
                    if (shape === 'rounded' || shape === 'extra-rounded' || shape === 'classy' || shape === 'classy-rounded') {
                        return 'dot';
                    }
                    return 'square';
                },
                selectShape(shapeId) {
                    this.selectedShape = shapeId;
                },
                initShapePreviews() {
                    if (typeof QRCodeStyling === 'undefined') return;

                    this.shapes.forEach(s => {
                        const el = document.getElementById('qr-shape-preview-' + s.id);
                        if (!el) return;
                        try {
                            const mini = new QRCodeStyling({
                                width: 34,
                                height: 34,
                                type: 'svg',
                                margin: 0,
                                data: 'https://example.com',
                                dotsOptions: {
                                    type: s.id,
                                    color: '#0f172a'
                                },
                                backgroundOptions: {
                                    color: 'transparent'
                                },
                                cornersSquareOptions: {
                                    type: this.getCornerSquareType(s.id),
                                    color: '#0f172a'
                                },
                                cornersDotOptions: {
                                    type: this.getCornerDotType(s.id),
                                    color: '#0f172a'
                                }
                            });
                            el.innerHTML = '';
                            mini.append(el);
                        } catch (e) {
                            console.warn('Mini preview error for ' + s.id, e);
                        }
                    });
                },
                qrOptions(size, logoMargin) {
                    const dotType = this.selectedShape || 'square';
                    const color = this.qrColor || '#000000';

                    return {
                        width: size,
                        height: size,
                        type: 'canvas',
                        data: this.qrData(),
                        image: this.logoUrl || undefined,
                        dotsOptions: { type: dotType, color },
                        backgroundOptions: { color: this.bgColor || '#ffffff' },
                        cornersSquareOptions: { type: this.getCornerSquareType(dotType), color },
                        cornersDotOptions: { type: this.getCornerDotType(dotType), color },
                        imageOptions: {
                            crossOrigin: 'anonymous',
                            margin: logoMargin,
                            hideBackgroundDots: true,
                            imageSize: 0.35,
                        },
                        qrOptions: { errorCorrectionLevel: 'H' },
                    };
                },
                initMainQr() {
                    if (typeof QRCodeStyling === 'undefined' || !this.hasUrl()) {
                        this.mainQrReady = false;
                        return;
                    }
                    const target = document.getElementById('qr-main-target');
                    if (!target) return;
                    target.innerHTML = '';

                    this.mainQr = new QRCodeStyling(this.qrOptions(220, 4));
                    this.mainQr.append(target);
                    this.mainQrReady = true;
                },
                updateMainQr() {
                    if (!this.hasUrl()) {
                        this.mainQrReady = false;
                        return;
                    }
                    const target = document.getElementById('qr-main-target');
                    if (!this.mainQr || (target && target.children.length === 0)) {
                        this.initMainQr();
                        return;
                    }
                    this.mainQr.update(this.qrOptions(220, 4));
                    this.mainQrReady = true;
                },
                async renderFramedCanvas() {
                    const qrSize = 720;
                    const rawBlob = await new QRCodeStyling(this.qrOptions(qrSize, 12)).getRawData('png');
                    const qrUrl = URL.createObjectURL(rawBlob);
                    const qrImg = await this.loadImage(qrUrl);
                    URL.revokeObjectURL(qrUrl);

                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    if (this.frame === 'none') {
                        canvas.width = qrSize;
                        canvas.height = qrSize;
                        ctx.drawImage(qrImg, 0, 0, qrSize, qrSize);
                    } else if (this.frame === 'simple') {
                        const totalSize = 900;
                        canvas.width = totalSize;
                        canvas.height = totalSize;
                        ctx.fillStyle = '#ffffff';
                        ctx.fillRect(0, 0, totalSize, totalSize);

                        const boxPadding = 50;
                        const boxSize = totalSize - boxPadding * 2;
                        ctx.lineWidth = 18;
                        ctx.strokeStyle = '#050505';
                        ctx.strokeRect(boxPadding, boxPadding, boxSize, boxSize);
                        ctx.drawImage(qrImg, boxPadding + 36, boxPadding + 36, boxSize - 72, boxSize - 72);
                    } else {
                        const width = 900;
                        const height = 1080;
                        canvas.width = width;
                        canvas.height = height;
                        ctx.fillStyle = '#ffffff';
                        ctx.fillRect(0, 0, width, height);

                        const boxPadding = 60;
                        const boxWidth = width - boxPadding * 2;
                        const boxHeight = 800;
                        ctx.lineWidth = 20;
                        ctx.strokeStyle = '#050505';
                        ctx.strokeRect(boxPadding, boxPadding, boxWidth, boxHeight);
                        ctx.drawImage(qrImg, boxPadding + 30, boxPadding + 30, boxWidth - 60, boxWidth - 60);

                        const pillWidth = 420;
                        const pillHeight = 90;
                        const pillX = (width - pillWidth) / 2;
                        const pillY = boxPadding + boxHeight + 40;

                        ctx.fillStyle = '#050505';
                        ctx.beginPath();
                        ctx.moveTo(width / 2, pillY - 26);
                        ctx.lineTo(width / 2 - 30, pillY + 2);
                        ctx.lineTo(width / 2 + 30, pillY + 2);
                        ctx.closePath();
                        ctx.fill();

                        ctx.beginPath();
                        if (ctx.roundRect) {
                            ctx.roundRect(pillX, pillY, pillWidth, pillHeight, 18);
                        } else {
                            ctx.rect(pillX, pillY, pillWidth, pillHeight);
                        }
                        ctx.fill();

                        ctx.fillStyle = '#ffffff';
                        ctx.font = '900 44px sans-serif';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText('SCAN ME', width / 2, pillY + pillHeight / 2);
                    }

                    return canvas;
                },
                async prepareAndSubmit(event) {
                    event.preventDefault();

                    if (!this.hasUrl() || this.saving) return;

                    this.url = this.normalizedUrl();
                    this.saving = true;
                    this.saveError = '';

                    try {
                        const canvas = await this.renderFramedCanvas();
                        this.$refs.qrImageData.value = canvas.toDataURL('image/png');
                        event.target.submit();
                    } catch (e) {
                        console.error('Failed to render QR image', e);
                        this.saving = false;
                        this.saveError = 'QR Code gagal dibuat. Coba ganti logo lalu simpan lagi.';
                    }
                },
                async downloadJpg() {
                    if (!this.hasUrl()) return;

                    try {
                        const canvas = await this.renderFramedCanvas();
                        const link = document.createElement('a');
                        link.download = 'qr-code.jpg';
                        link.href = canvas.toDataURL('image/jpeg', 0.95);
                        link.click();
                    } catch (e) {
                        console.error('Download failed:', e);
                        this.saveError = 'QR Code gagal diunduh. Coba ganti logo lalu unduh lagi.';
                    }
                },
                loadImage(src) {
                    return new Promise((resolve, reject) => {
                        const img = new Image();
                        img.onload = () => resolve(img);
                        img.onerror = reject;
                        img.src = src;
                    });
                },
                // Logos become a 512px PNG here: broken files stop at this step, and the
                // QR library and the server only ever receive one small, safe format.
                async logoToPng(src) {
                    const img = await this.loadImage(src);
                    const max = 512;
                    // SVG files without width/height report 0.
                    const sourceWidth = img.naturalWidth || max;
                    const sourceHeight = img.naturalHeight || max;
                    const scale = Math.min(max / sourceWidth, max / sourceHeight, 1);

                    const canvas = document.createElement('canvas');
                    canvas.width = Math.max(1, Math.round(sourceWidth * scale));
                    canvas.height = Math.max(1, Math.round(sourceHeight * scale));
                    canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);

                    return canvas;
                },
                async applyLogo(src, type, fileName) {
                    const canvas = await this.logoToPng(src);
                    const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/png'));
                    const transfer = new DataTransfer();
                    transfer.items.add(new File([blob], 'logo.png', { type: 'image/png' }));

                    this.$refs.logoInput.files = transfer.files;
                    this.logoUrl = canvas.toDataURL('image/png');
                    this.selectedLogoType = type;
                    this.logoFileName = fileName;
                    this.removeLogoValue = '0';
                    this.logoError = '';
                },
                async setLogoPreview(event) {
                    const [file] = event.target.files;
                    if (!file) return;

                    if (file.size > 10 * 1024 * 1024) {
                        event.target.value = '';
                        this.logoError = 'Ukuran logo maksimal 10 MB.';
                        return;
                    }

                    const objectUrl = URL.createObjectURL(file);
                    try {
                        await this.applyLogo(objectUrl, 'custom', file.name);
                    } catch (e) {
                        event.target.value = '';
                        this.logoError = `"${file.name}" bukan gambar yang bisa dibaca. Gunakan PNG, JPG, WebP, GIF, atau SVG.`;
                    } finally {
                        URL.revokeObjectURL(objectUrl);
                    }
                },
                removeLogo() {
                    this.logoUrl = null;
                    this.logoFileName = '';
                    this.selectedLogoType = 'none';
                    this.removeLogoValue = '1';
                    this.logoError = '';
                    this.$refs.logoInput.value = '';
                },
            };
        }
    </script>
</x-filament-panels::page>
