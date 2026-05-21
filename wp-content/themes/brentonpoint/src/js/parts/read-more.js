import { qsa } from './utils';

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
