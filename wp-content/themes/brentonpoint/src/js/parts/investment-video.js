/**
 * Investment-criteria background video.
 *
 * Tries the Vimeo iframe first. If Vimeo doesn't start playing within
 * READY_TIMEOUT_MS (slow CDN, blocked embed, ad-blocker, etc.), the iframe is
 * removed and an mp4 <video> fallback is injected from `data-mp4-url`. The mp4
 * is never requested on the happy path — it only enters the DOM on failure.
 */

const VIMEO_SDK_URL    = 'https://player.vimeo.com/api/player.js';
const READY_TIMEOUT_MS = 6000;

let sdkPromise = null;

function loadVimeoSdk() {
  if (window.Vimeo && window.Vimeo.Player) {
    return Promise.resolve(window.Vimeo.Player);
  }
  if (sdkPromise) return sdkPromise;

  sdkPromise = new Promise((resolve, reject) => {
    const script = document.createElement('script');
    script.src = VIMEO_SDK_URL;
    script.async = true;
    script.onload = () => {
      if (window.Vimeo && window.Vimeo.Player) {
        resolve(window.Vimeo.Player);
      } else {
        reject(new Error('Vimeo SDK loaded but Player missing'));
      }
    };
    script.onerror = () => reject(new Error('Vimeo SDK failed to load'));
    document.head.appendChild(script);
  });

  return sdkPromise;
}

function injectFallback(section, iframe) {
  const mp4Url   = section.dataset.mp4Url;
  const mp4Mime  = section.dataset.mp4Mime || 'video/mp4';
  const poster   = section.dataset.posterUrl || '';
  const posterAlt = section.dataset.posterAlt || '';

  iframe.remove();

  if (mp4Url) {
    const video = document.createElement('video');
    video.className = 'investment-section__video';
    video.autoplay = true;
    video.muted = true;
    video.loop = true;
    video.playsInline = true;
    video.preload = 'auto';
    if (poster) video.poster = poster;

    const source = document.createElement('source');
    source.src = mp4Url;
    source.type = mp4Mime;
    video.appendChild(source);

    const media = section.querySelector('.investment-section__media');
    if (media) media.appendChild(video);
    video.addEventListener('playing', () => {
      section.classList.add('is-video-playing');
    }, { once: true });
    video.play().catch(() => {});
  } else if (poster) {
    const img = document.createElement('img');
    img.className = 'investment-section__video';
    img.src = poster;
    img.alt = posterAlt;
    const media = section.querySelector('.investment-section__media');
    if (media) media.insertBefore(img, media.firstChild);
  }
}

function initOne(section) {
  const iframe = section.querySelector('.investment-section__iframe');

  // Server-side <video> path (no Vimeo URL): just fade it in once it plays.
  if (!iframe) {
    const video = section.querySelector('.investment-section__video');
    if (video && video.tagName === 'VIDEO') {
      video.addEventListener('playing', () => {
        section.classList.add('is-video-playing');
      }, { once: true });
    } else {
      // No video at all — keep the poster visible.
      section.classList.add('is-video-playing');
    }
    return;
  }

  let resolved = false;
  const timeoutId = setTimeout(() => {
    if (resolved) return;
    resolved = true;
    injectFallback(section, iframe);
  }, READY_TIMEOUT_MS);

  loadVimeoSdk()
    .then((Player) => {
      if (resolved) return;
      const player = new Player(iframe);

      player.on('playing', () => {
        if (resolved) return;
        resolved = true;
        clearTimeout(timeoutId);
        section.classList.add('is-video-playing');
      });

      // ready() resolving doesn't guarantee playback — autoplay may still be
      // blocked. We try play() explicitly and fall back on failure.
      player.ready()
        .then(() => player.play())
        .catch(() => {
          if (resolved) return;
          resolved = true;
          clearTimeout(timeoutId);
          injectFallback(section, iframe);
        });
    })
    .catch(() => {
      if (resolved) return;
      resolved = true;
      clearTimeout(timeoutId);
      injectFallback(section, iframe);
    });
}

export function initInvestmentVideo() {
  const sections = document.querySelectorAll('[data-investment-video]');
  sections.forEach(initOne);
}
