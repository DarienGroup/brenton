/**
 * "Hear From Us" tiles — click-to-play.
 *
 * On click we replace the cover/play overlay with the actual player:
 *   - youtube : an autoplaying YouTube iframe (using youtube-nocookie domain)
 *   - upload  : a native <video controls autoplay>
 *
 * Only the clicked tile is swapped; other tiles keep their cover state.
 */

function buildYouTubeFrame(id) {
  const iframe = document.createElement('iframe');
  iframe.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(id)}?autoplay=1&rel=0&modestbranding=1&playsinline=1`;
  iframe.title = 'YouTube video player';
  iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
  iframe.allowFullscreen = true;
  iframe.loading = 'eager';
  return iframe;
}

function buildVideoElement(url, mime) {
  const video = document.createElement('video');
  video.controls = true;
  video.autoplay = true;
  video.playsInline = true;
  video.preload = 'auto';

  const source = document.createElement('source');
  source.src = url;
  source.type = mime || 'video/mp4';
  video.appendChild(source);

  return video;
}

function activate(button) {
  if (button.dataset.activated === '1') return;
  button.dataset.activated = '1';

  const card = button.closest('.hear-from-us-card') || button;
  const type = button.dataset.videoType;

  let player = null;
  if (type === 'youtube') {
    const id = button.dataset.youtubeId;
    if (!id) return;
    player = buildYouTubeFrame(id);
  } else if (type === 'upload') {
    const url = button.dataset.videoUrl;
    if (!url) return;
    player = buildVideoElement(url, button.dataset.videoMime);
  }

  if (!player) return;

  const media = document.createElement('div');
  media.className = 'hear-from-us-card__media';
  media.appendChild(player);
  button.appendChild(media);

  card.classList.add('is-playing');

  if (player.tagName === 'VIDEO') {
    player.play().catch(() => {});
  }
}

export function initHearFromUs() {
  const buttons = document.querySelectorAll('[data-hear-from-us]');
  buttons.forEach((button) => {
    button.addEventListener('click', () => activate(button));
  });
}
