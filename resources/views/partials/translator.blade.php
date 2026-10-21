@php
    $currentLocale = app()->getLocale();
@endphp

<style>
    /* ==========================================================================
       Google Translate Native Seamless Styling
       Menyembunyikan banner, popup, toolbar, dan efek pergeseran body
       ========================================================================== */
    .goog-te-banner-frame,
    .goog-te-banner-frame.skiptranslate,
    iframe.goog-te-banner-frame,
    #goog-gt-tt,
    .goog-te-balloon-frame,
    .goog-tooltip,
    .goog-tooltip:hover {
        display: none !important;
        visibility: hidden !important;
    }

    body {
        top: 0px !important;
        position: static !important;
    }

    .skiptranslate:not(.goog-te-gadget) {
        display: none !important;
    }

    .VIpgJd-ZVi9od-ORHb-OEVmcd,
    .VIpgJd-ZVi9od-l4eHX-hSRGPd,
    .VIpgJd-ZVi9od-aZ2wEe-wOHMyf,
    .VIpgJd-ZVi9od-xl07Ob-OEVmcd,
    .VIpgJd-y6EKec,
    .VIpgJd-ZVi9od-vH1Gmf {
        display: none !important;
    }

    /* Mencegah highlight warna kuning bawaan Google Translate */
    font {
        background-color: transparent !important;
        box-shadow: none !important;
        color: inherit !important;
    }
</style>

<div id="google_translate_element" style="display:none;" translate="no" class="notranslate"></div>

<script translate="no" class="notranslate">
    (function() {
        const targetLocale = "{{ $currentLocale }}";

        function setGoogleTransCookie(val) {
            const domain = window.location.hostname;
            const expires = "; expires=" + new Date(Date.now() + 365 * 864e5).toUTCString();
            document.cookie = "googtrans=" + val + expires + "; path=/;";
            if (domain && domain !== 'localhost' && !domain.match(/^\d+\.\d+\.\d+\.\d+$/)) {
                document.cookie = "googtrans=" + val + expires + "; path=/; domain=." + domain + ";";
            }
        }

        function clearGoogleTransCookie() {
            const domain = window.location.hostname;
            const past = "; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "googtrans=" + past;
            if (domain && domain !== 'localhost' && !domain.match(/^\d+\.\d+\.\d+\.\d+$/)) {
                document.cookie = "googtrans=" + past + " domain=." + domain + ";";
            }
        }

        if (targetLocale === 'en') {
            setGoogleTransCookie('/id/en');
        } else {
            clearGoogleTransCookie();
        }

        // Cegah Google Translate menggeser posisi body ke bawah (top: 40px)
        const observer = new MutationObserver(function() {
            if (document.body && document.body.style.top && document.body.style.top !== '0px') {
                document.body.style.top = '0px';
            }
        });

        if (document.body) {
            observer.observe(document.body, { attributes: true, attributeFilter: ['style'] });
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                observer.observe(document.body, { attributes: true, attributeFilter: ['style'] });
            });
        }
    })();

    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'id',
            includedLanguages: 'id,en',
            autoDisplay: false,
            multilanguagePage: false
        }, 'google_translate_element');

        // Pastikan terjemahan aktif jika target adalah 'en'
        if ("{{ $currentLocale }}" === 'en') {
            let attempts = 0;
            const interval = setInterval(function() {
                attempts++;
                const select = document.querySelector('.goog-te-combo');
                if (select) {
                    if (select.value !== 'en') {
                        select.value = 'en';
                        select.dispatchEvent(new Event('change'));
                    }
                    clearInterval(interval);
                }
                if (attempts > 50) {
                    clearInterval(interval);
                }
            }, 100);
        }
    }
</script>

@if($currentLocale === 'en')
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer></script>
@endif
