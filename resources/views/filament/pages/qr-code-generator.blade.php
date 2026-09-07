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

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-success-50 px-4 py-3 text-sm font-semibold text-success-700 ring-1 ring-success-600/20 dark:bg-success-500/10 dark:text-success-300">
            {{ session('status') }}
        </div>
    @endif

    @php
        $initialShape = old('shape', ($latestQrCode?->shape === 'dot' ? 'dots' : $latestQrCode?->shape) ?? 'square');
        $qrInitialState = [
            'link' => old('link', ''),
            'qr_color' => old('qr_color', $latestQrCode?->qr_color ?? '#000000'),
            'background_color' => old('background_color', $latestQrCode?->background_color ?? '#ffffff'),
            'frame' => old('frame', $latestQrCode?->frame ?? 'scan'),
            'shape' => $initialShape,
            'scan_tracking' => (bool) old('scan_tracking', $latestQrCode?->scan_tracking ?? true),
            'total_scans' => (int) ($latestQrCode?->total_scans ?? 0),
            'unique_scans' => (int) ($latestQrCode?->unique_scans ?? 0),
            'top_location' => $latestQrCode?->top_location ?? '-',
            'logo_url' => $latestQrCode?->logo_path
                ? \Illuminate\Support\Facades\Storage::disk('public')->url($latestQrCode->logo_path)
                : null,
        ];
    @endphp

    <div class="qr-shell" x-data="qrCodeGenerator(@js($qrInitialState))" x-init="init()">
        <form class="qr-workspace" method="POST" action="{{ route('qr-code-generator.store') }}" enctype="multipart/form-data" x-on:submit="prepareAndSubmit($event)">
            @csrf
            <input type="hidden" name="qr_image_data" x-ref="qrImageData">
            <input type="hidden" name="scan_tracking" :value="scanTracking ? '1' : '0'">

            <div>
                <section class="qr-panel">
                    <div class="qr-panel-title">CONTENT <span>⌃</span></div>
                    <div class="mt-8">
                        <label class="qr-label" for="qr-url">YOUR WEBSITE URL</label>
                        <div class="qr-url-wrap">
                            <input id="qr-url" name="link" class="qr-url-input" type="url" x-model.debounce.300ms="url">
                            <span class="qr-typewriter" x-show="!url || !url.trim()">www.my-website.com/donations</span>
                        </div>
                        @error('link')
                            <p class="mt-2 text-sm font-semibold text-danger-600">{{ $message }}</p>
                        @enderror
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
                                accept=".png,.jpg,.jpeg,.gif,.svg" 
                                x-on:change="setLogoPreview($event)">
                            <input type="hidden" name="remove_logo" x-model="removeLogoValue">

                            <div x-show="!logoUrl" class="qr-upload-box" x-on:click="$refs.logoInput.click()">
                                <span class="qr-upload-icon">☁</span>
                                <div>
                                    <div class="qr-upload-title">Upload your logo</div>
                                    <div class="qr-upload-note">File types: png, .jpg, .gif or .svg — max size of 2 MB</div>
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

                            @error('logo')
                                <p class="mt-2 text-sm font-semibold text-danger-600">{{ $message }}</p>
                            @enderror
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
                                <input type="text" name="qr_color" x-model="qrColor" x-on:input="updateMainQr()">
                                <input type="color" x-model="qrColor" x-on:input="updateMainQr()">
                            </label>
                            <label class="qr-color-field">
                                <span>Background Color</span>
                                <input type="text" name="background_color" x-model="bgColor" x-on:input="updateMainQr()">
                                <input type="color" x-model="bgColor" x-on:input="updateMainQr()">
                            </label>
                        </div>
                        <input type="hidden" name="shape" x-model="selectedShape">
                        @error('qr_color')
                            <p class="mt-2 text-sm font-semibold text-danger-600">{{ $message }}</p>
                        @enderror
                        @error('background_color')
                            <p class="mt-2 text-sm font-semibold text-danger-600">{{ $message }}</p>
                        @enderror
                        @error('shape')
                            <p class="mt-2 text-sm font-semibold text-danger-600">{{ $message }}</p>
                        @enderror
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
                            <img x-show="!mainQrReady" :src="qrImageUrl()" crossorigin="anonymous" alt="Generated QR Code">
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

                <div class="qr-download" :class="{ 'opacity-50 cursor-not-allowed': !hasUrl() }">
                    <button type="button" :disabled="!hasUrl()" x-on:click="downloadJpg()">⇩ DOWNLOAD JPG</button>
                    <button type="button" :disabled="!hasUrl()" aria-label="Download options">⌄</button>
                </div>

                <button type="submit" 
                    class="mt-4 rounded-full bg-slate-950 px-8 py-3 text-sm font-black text-white shadow transition-all hover:bg-slate-800"
                    :class="{ 'opacity-50 cursor-not-allowed hover:bg-slate-950': !hasUrl() }"
                    :disabled="!hasUrl()">
                    SAVE QR CODE
                </button>

                @if ($latestQrCode)
                    <p class="mt-3 text-center text-xs font-semibold text-slate-500">
                        Last saved: {{ $latestQrCode->created_at->format('d M Y H:i') }}
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
                panel: 'color',
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
                mainQr: null,
                frames: ['simple', 'scan'],
                presets: {
                    scanme: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100"><rect width="100" height="100" rx="20" fill="%230f172a"/><text x="50" y="44" fill="%23ffffff" font-family="sans-serif" font-size="24" font-weight="900" text-anchor="middle">SCAN</text><text x="50" y="76" fill="%2338bdf8" font-family="sans-serif" font-size="28" font-weight="900" text-anchor="middle">ME</text></svg>',
                    web: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100"><rect width="100" height="100" rx="20" fill="%230284c7"/><circle cx="50" cy="50" r="30" fill="none" stroke="%23ffffff" stroke-width="5"/><ellipse cx="50" cy="50" rx="14" ry="30" fill="none" stroke="%23ffffff" stroke-width="5"/><line x1="20" y1="50" x2="80" y2="50" stroke="%23ffffff" stroke-width="5"/></svg>'
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
                logoTitle() {
                    if (this.selectedLogoType === 'scanme') return 'Preset: SCAN ME Badge';
                    if (this.selectedLogoType === 'web') return 'Preset: Web / Globe';
                    return this.logoFileName || 'Logo Kustom';
                },
                setPresetLogo(type) {
                    if (this.presets[type]) {
                        this.selectedLogoType = type;
                        this.logoUrl = this.presets[type];
                        this.removeLogoValue = '0';
                        if (this.$refs.logoInput) {
                            this.$refs.logoInput.value = '';
                        }
                        const fileInput = document.getElementById('qr-logo');
                        if (fileInput) {
                            fileInput.value = '';
                        }
                        this.updateMainQr();
                    }
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
                    this.$watch('selectedShape', () => { if (this.hasUrl()) this.updateMainQr(); });
                    this.$watch('qrColor', () => { if (this.hasUrl()) this.updateMainQr(); });
                    this.$watch('bgColor', () => { if (this.hasUrl()) this.updateMainQr(); });
                    this.$watch('logoUrl', () => { if (this.hasUrl()) this.updateMainQr(); });
                    this.$watch('frame', () => { if (this.hasUrl()) this.updateMainQr(); });
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
                    if (this.hasUrl()) {
                        this.updateMainQr();
                    }
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
                initMainQr() {
                    if (typeof QRCodeStyling === 'undefined' || !this.hasUrl()) {
                        this.mainQrReady = false;
                        return;
                    }
                    const target = document.getElementById('qr-main-target');
                    if (!target) return;
                    target.innerHTML = '';

                    const dotType = this.selectedShape || 'square';
                    this.mainQr = new QRCodeStyling({
                        width: 220,
                        height: 220,
                        type: 'canvas',
                        data: this.url.trim(),
                        image: this.logoUrl || undefined,
                        dotsOptions: {
                            type: dotType,
                            color: this.qrColor || '#000000',
                        },
                        backgroundOptions: {
                            color: this.bgColor || '#ffffff',
                        },
                        cornersSquareOptions: {
                            type: this.getCornerSquareType(dotType),
                            color: this.qrColor || '#000000',
                        },
                        cornersDotOptions: {
                            type: this.getCornerDotType(dotType),
                            color: this.qrColor || '#000000',
                        },
                        imageOptions: {
                            crossOrigin: 'anonymous',
                            margin: 4,
                            hideBackgroundDots: true,
                            imageSize: 0.35,
                        },
                        qrOptions: {
                            errorCorrectionLevel: 'H',
                        }
                    });

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
                    const dotType = this.selectedShape || 'square';
                    this.mainQr.update({
                        data: this.url.trim(),
                        image: this.logoUrl || undefined,
                        dotsOptions: {
                            type: dotType,
                            color: this.qrColor || '#000000',
                        },
                        backgroundOptions: {
                            color: this.bgColor || '#ffffff',
                        },
                        cornersSquareOptions: {
                            type: this.getCornerSquareType(dotType),
                            color: this.qrColor || '#000000',
                        },
                        cornersDotOptions: {
                            type: this.getCornerDotType(dotType),
                            color: this.qrColor || '#000000',
                        },
                        imageOptions: {
                            crossOrigin: 'anonymous',
                            margin: 4,
                            hideBackgroundDots: true,
                            imageSize: 0.35,
                        },
                        qrOptions: {
                            errorCorrectionLevel: 'H',
                        }
                    });
                    this.mainQrReady = true;
                },
                cleanColor(value) {
                    return (value || '').replace('#', '').toUpperCase();
                },
                encodedUrl() {
                    return encodeURIComponent(this.url ? this.url.trim() : '');
                },
                qrImageUrl(format = 'png') {
                    if (!this.hasUrl()) return '';
                    return `https://api.qrserver.com/v1/create-qr-code/?size=300x300&format=${format}&color=${this.cleanColor(this.qrColor)}&bgcolor=${this.cleanColor(this.bgColor)}&ecc=H&data=${this.encodedUrl()}`;
                },
                async prepareAndSubmit(event) {
                    event.preventDefault();

                    if (!this.hasUrl()) {
                        alert('Silakan masukkan link/URL website terlebih dahulu.');
                        return;
                    }

                    const form = event.target;

                    try {
                        // 1. Try to get canvas image data directly from DOM
                        const canvas = document.querySelector('#qr-main-target canvas');
                        if (canvas && this.$refs.qrImageData) {
                            this.$refs.qrImageData.value = canvas.toDataURL('image/png');
                            form.submit();
                            return;
                        }

                        // 2. Otherwise get raw data from QRCodeStyling
                        if (this.mainQr) {
                            const blob = await this.mainQr.getRawData('png');
                            if (blob && this.$refs.qrImageData) {
                                const reader = new FileReader();
                                reader.onloadend = () => {
                                    this.$refs.qrImageData.value = reader.result;
                                    form.submit();
                                };
                                reader.readAsDataURL(blob);
                                return;
                            }
                        }
                    } catch (e) {
                        console.error('Failed to capture QR image data', e);
                    }

                    form.submit();
                },
                async downloadJpg() {
                    if (!this.hasUrl()) {
                        alert('Silakan masukkan link/URL website terlebih dahulu.');
                        return;
                    }

                    try {
                        const qrSize = 720;
                        const tempQr = new QRCodeStyling({
                            width: qrSize,
                            height: qrSize,
                            type: 'canvas',
                            data: this.url.trim(),
                            image: this.logoUrl || undefined,
                            dotsOptions: {
                                type: this.selectedShape || 'square',
                                color: this.qrColor || '#000000',
                            },
                            backgroundOptions: {
                                color: this.bgColor || '#ffffff',
                            },
                            cornersSquareOptions: {
                                type: this.getCornerSquareType(this.selectedShape),
                                color: this.qrColor || '#000000',
                            },
                            cornersDotOptions: {
                                type: this.getCornerDotType(this.selectedShape),
                                color: this.qrColor || '#000000',
                            },
                            imageOptions: {
                                crossOrigin: 'anonymous',
                                margin: 12,
                                hideBackgroundDots: true,
                                imageSize: 0.35,
                            },
                            qrOptions: {
                                errorCorrectionLevel: 'H',
                            }
                        });

                        const rawBlob = await tempQr.getRawData('png');
                        if (!rawBlob) return;

                        const qrImg = await new Promise((resolve, reject) => {
                            const img = new Image();
                            img.onload = () => resolve(img);
                            img.onerror = reject;
                            img.src = URL.createObjectURL(rawBlob);
                        });

                        if (this.frame === 'none') {
                            const canvas = document.createElement('canvas');
                            canvas.width = qrSize;
                            canvas.height = qrSize;
                            const ctx = canvas.getContext('2d');
                            ctx.fillStyle = this.bgColor || '#ffffff';
                            ctx.fillRect(0, 0, qrSize, qrSize);
                            ctx.drawImage(qrImg, 0, 0, qrSize, qrSize);

                            const link = document.createElement('a');
                            link.download = 'qr-code.jpg';
                            link.href = canvas.toDataURL('image/jpeg', 0.95);
                            link.click();
                        } else if (this.frame === 'simple') {
                            const totalSize = 900;
                            const canvas = document.createElement('canvas');
                            canvas.width = totalSize;
                            canvas.height = totalSize;
                            const ctx = canvas.getContext('2d');
                            ctx.fillStyle = '#ffffff';
                            ctx.fillRect(0, 0, totalSize, totalSize);

                            const borderW = 18;
                            const boxPadding = 50;
                            const boxSize = totalSize - boxPadding * 2;
                            ctx.lineWidth = borderW;
                            ctx.strokeStyle = '#050505';
                            ctx.strokeRect(boxPadding, boxPadding, boxSize, boxSize);

                            const qrOffset = boxPadding + 36;
                            const qrDrawSize = boxSize - 72;
                            ctx.drawImage(qrImg, qrOffset, qrOffset, qrDrawSize, qrDrawSize);

                            const link = document.createElement('a');
                            link.download = 'qr-code.jpg';
                            link.href = canvas.toDataURL('image/jpeg', 0.95);
                            link.click();
                        } else {
                            const width = 900;
                            const height = 1080;
                            const canvas = document.createElement('canvas');
                            canvas.width = width;
                            canvas.height = height;
                            const ctx = canvas.getContext('2d');
                            ctx.fillStyle = '#ffffff';
                            ctx.fillRect(0, 0, width, height);

                            const borderW = 20;
                            const boxPadding = 60;
                            const boxWidth = width - boxPadding * 2;
                            const boxHeight = 800;
                            ctx.lineWidth = borderW;
                            ctx.strokeStyle = '#050505';
                            ctx.strokeRect(boxPadding, boxPadding, boxWidth, boxHeight);

                            const qrOffset = boxPadding + 30;
                            const qrDrawSize = boxWidth - 60;
                            ctx.drawImage(qrImg, qrOffset, qrOffset, qrDrawSize, qrDrawSize);

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

                            const link = document.createElement('a');
                            link.download = 'qr-code.jpg';
                            link.href = canvas.toDataURL('image/jpeg', 0.95);
                            link.click();
                        }
                    } catch (err) {
                        console.error('Download failed:', err);
                    }
                },
                setLogoPreview(event) {
                    const [file] = event.target.files;
                    if (!file) return;

                    this.logoFileName = file.name;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.logoUrl = e.target.result;
                        this.selectedLogoType = 'custom';
                        this.removeLogoValue = '0';
                        if (this.hasUrl()) {
                            this.updateMainQr();
                        }
                    };
                    reader.readAsDataURL(file);
                },
                removeLogo() {
                    this.logoUrl = null;
                    this.logoFileName = '';
                    this.selectedLogoType = 'none';
                    this.removeLogoValue = '1';
                    if (this.$refs.logoInput) {
                        this.$refs.logoInput.value = '';
                    }
                    const fileInput = document.getElementById('qr-logo');
                    if (fileInput) {
                        fileInput.value = '';
                    }
                    if (this.hasUrl()) {
                        this.updateMainQr();
                    }
                },
            };
        }
    </script>
</x-filament-panels::page>
