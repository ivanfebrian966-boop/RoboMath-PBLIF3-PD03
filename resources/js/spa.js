/**
 * RoboMath SPA Navigation Engine
 * Memberikan pengalaman Single Page Application (SPA) yang cepat, mulus, tanpa refresh halaman penuh.
 */

class RoboMathProgressBar {
    constructor() {
        this.element = null;
        this.width = 0;
        this.timer = null;
        this.init();
    }

    init() {
        if (document.getElementById('robomath-progress-bar')) {
            this.element = document.getElementById('robomath-progress-bar');
            return;
        }

        const bar = document.createElement('div');
        bar.id = 'robomath-progress-bar';
        bar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            z-index: 99999;
            background: linear-gradient(90deg, #F97316 0%, #EAB308 50%, #6366F1 100%);
            box-shadow: 0 0 10px rgba(249, 115, 22, 0.7);
            pointer-events: none;
            transition: width 0.25s ease-out, opacity 0.3s ease-out;
            opacity: 0;
        `;
        document.body.appendChild(bar);
        this.element = bar;
    }

    start() {
        if (!this.element) this.init();
        clearInterval(this.timer);
        this.width = 15;
        this.element.style.opacity = '1';
        this.element.style.width = '15%';

        this.timer = setInterval(() => {
            if (this.width < 80) {
                this.width += Math.random() * 12;
                if (this.width > 80) this.width = 80;
                this.element.style.width = `${this.width}%`;
            }
        }, 120);
    }

    done() {
        clearInterval(this.timer);
        if (!this.element) return;
        this.element.style.width = '100%';
        setTimeout(() => {
            this.element.style.opacity = '0';
            setTimeout(() => {
                this.element.style.width = '0%';
            }, 300);
        }, 150);
    }
}

class RoboMathSPA {
    constructor() {
        this.progress = new RoboMathProgressBar();
        this.isNavigating = false;
        this.pageCache = new Map();
        this.init();
    }

    init() {
        // Intercept semua klik link <a>
        document.addEventListener('click', (e) => this.handleLinkClick(e));

        // Intercept form GET (filter/pencarian)
        document.addEventListener('submit', (e) => this.handleFormSubmit(e));

        // Tangani navigasi History Back / Forward
        window.addEventListener('popstate', (e) => {
            const url = window.location.href;
            this.navigate(url, false);
        });

        console.log('⚡ RoboMath SPA Navigation Activated!');
    }

    handleLinkClick(e) {
        const link = e.target.closest('a');
        if (!link) return;

        // Validasi link apakah layak di-SPA kan
        if (
            e.defaultPrevented ||
            e.metaKey ||
            e.ctrlKey ||
            e.shiftKey ||
            e.altKey ||
            link.target === '_blank' ||
            link.hasAttribute('download') ||
            link.hasAttribute('data-no-spa') ||
            link.getAttribute('rel') === 'external'
        ) {
            return;
        }

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
            return;
        }

        try {
            const targetUrl = new URL(link.href, window.location.origin);

            // Beda domain -> biarkan navigasi normal
            if (targetUrl.origin !== window.location.origin) return;

            // Route logout, unduh CSV, ekspor file -> lewati SPA
            if (
                targetUrl.pathname.includes('/logout') ||
                targetUrl.pathname.includes('/download') ||
                targetUrl.pathname.includes('/export')
            ) {
                return;
            }

            // Jika link mengarah ke URL persis yang sedang aktif
            if (targetUrl.href === window.location.href) {
                e.preventDefault();
                return;
            }

            e.preventDefault();
            this.navigate(targetUrl.href, true);
        } catch (err) {
            // URL tidak valid, biarkan browser menangani
        }
    }

    handleFormSubmit(e) {
        const form = e.target;
        if (!form || (form.method && form.method.toUpperCase() !== 'GET')) return;
        if (form.hasAttribute('data-no-spa') || form.target === '_blank') return;

        try {
            const action = form.action || window.location.href;
            const targetUrl = new URL(action, window.location.origin);
            if (targetUrl.origin !== window.location.origin) return;

            e.preventDefault();
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (const [key, value] of formData.entries()) {
                if (value !== '') {
                    params.append(key, value);
                }
            }

            const finalUrl = targetUrl.pathname + (params.toString() ? '?' + params.toString() : '');
            this.navigate(finalUrl, true);
        } catch (err) {
            // Abaikan dan submit biasa jika error
        }
    }

    async navigate(url, push = true) {
        if (this.isNavigating) return;
        this.isNavigating = true;
        this.progress.start();

        try {
            let htmlText = '';

            // Cek cache memori jika sudah pernah dimuat dalam 30 detik terakhir
            const cached = this.pageCache.get(url);
            const now = Date.now();
            if (cached && (now - cached.time < 30000)) {
                htmlText = cached.html;
            } else {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-RoboMath-SPA': 'true'
                    }
                });

                // Jika diarahkan ke redirect luar atau error server
                if (!response.ok || response.redirected && (new URL(response.url).origin !== window.location.origin)) {
                    window.location.href = response.url || url;
                    return;
                }

                htmlText = await response.text();
                this.pageCache.set(url, { html: htmlText, time: now });
            }

            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');

            const currentMain = document.getElementById('main-content') || document.querySelector('main');
            const newMain = doc.getElementById('main-content') || doc.querySelector('main');

            // Jika halaman tujuan bukan layout standar (misal auth / landing page)
            if (!currentMain || !newMain) {
                window.location.href = url;
                return;
            }

            // 1. Update Judul Halaman
            document.title = doc.title;

            // 2. Transisi halus konten utama
            currentMain.style.transition = 'opacity 0.15s ease-out';
            currentMain.style.opacity = '0.4';

            setTimeout(() => {
                currentMain.innerHTML = newMain.innerHTML;
                currentMain.style.opacity = '1';

                // 3. Update Sidebar (untuk indikator menu aktif dan notifikasi badge)
                const currentSidebar = document.getElementById('app-sidebar');
                const newSidebar = doc.getElementById('app-sidebar');
                if (currentSidebar && newSidebar) {
                    currentSidebar.innerHTML = newSidebar.innerHTML;
                }

                // 4. Update Header Navbar (jika ada update skor/nama user)
                const currentNavbar = document.getElementById('app-navbar');
                const newNavbar = doc.getElementById('app-navbar');
                if (currentNavbar && newNavbar) {
                    currentNavbar.innerHTML = newNavbar.innerHTML;
                }

                // 5. Eksekusi script khusus di dalam halaman baru
                this.executeScripts(currentMain);

                // 6. Inisialisasi ulang Alpine.js pada DOM baru
                if (window.Alpine) {
                    window.Alpine.initTree(currentMain);
                }

                // 7. Push History State
                if (push) {
                    window.history.pushState({ url }, doc.title, url);
                }

                // 8. Scroll ke atas
                window.scrollTo({ top: 0, left: 0, behavior: 'instant' });

                this.progress.done();
                this.isNavigating = false;

                // Trigger custom event untuk modul lain jika memerlukan
                document.dispatchEvent(new CustomEvent('robomath:spa-loaded', { detail: { url } }));
            }, 100);

        } catch (error) {
            console.warn('SPA Navigation fallback to normal load:', error);
            window.location.href = url;
        }
    }

    executeScripts(container) {
        const scripts = container.querySelectorAll('script');
        scripts.forEach((oldScript) => {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach((attr) => {
                newScript.setAttribute(attr.name, attr.value);
            });
            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
    }
}

// Inisialisasi SPA saat DOM siap
if (typeof window !== 'undefined') {
    window.RoboMathSPA = new RoboMathSPA();
}

export default RoboMathSPA;
