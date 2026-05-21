import { qsa } from './utils';

/**
 * Inject a "Read More / Read Less" toggle button into blocks whose combined
 * paragraph character count exceeds maxChars.
 *
 * @param {string} blockSelector  CSS selector for the repeating block wrapper
 * @param {number} maxChars       Character threshold above which the button appears
 */
function initReadMore( blockSelector, maxChars ) {
  const blocks = qsa( blockSelector );

  blocks.forEach( ( block ) => {
    const textBlock = block.querySelector( '.entry-content' );
    if ( ! textBlock ) return;

    const totalChars = Array.from( textBlock.querySelectorAll( 'p' ) )
      .reduce( ( sum, p ) => sum + p.textContent.length, 0 );

    if ( totalChars <= maxChars ) return;

    const btn = document.createElement( 'button' );
    btn.textContent = 'Read More';
    btn.className   = 'read-more-btn show';

    btn.addEventListener( 'click', () => {
      const expanded  = textBlock.classList.toggle( 'active' );
      btn.textContent = expanded ? 'Read Less' : 'Read More';
    } );

    textBlock.after( btn );
  } );
}

export function initPortfolioReadMore() {
  initReadMore( '.portfolio_block article', 417 );
}

export function initTeamReadMore() {
  initReadMore( '.team_block article', 20000 );
}
