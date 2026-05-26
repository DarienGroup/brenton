import { qsa } from '../parts/utils';
import { bindModal, openModal } from '../parts/modal';

export function initPortfolioTabs() {
  const buttons    = qsa( '.portfolio-tabs button' );
  const articles   = qsa( '.portfolio_block article' );
  const emptyBlocks = qsa( '.portfolio-section__empty' );

  if ( ! buttons.length ) return;

  // Show/hide the per-filter empty-state block. The grid itself stays mounted
  // so the existing layout stays intact — only the empty-state sibling toggles.
  const applyEmptyState = ( filter, visibleCount ) => {
    emptyBlocks.forEach( ( block ) => {
      const matches = block.dataset.emptyFilter === filter;
      block.hidden  = ! ( matches && visibleCount === 0 );
    } );
  };

  buttons.forEach( ( button ) => {
    button.addEventListener( 'click', () => {
      const block = button.getAttribute( 'data-block' );

      buttons.forEach( ( btn ) => btn.classList.remove( 'active' ) );
      button.classList.add( 'active' );

      let visibleCount = 0;

      articles.forEach( ( article ) => {
        let visible;
        if ( block === 'All' ) {
          visible = true;
        } else if ( block === 'Active' ) {
          visible = article.classList.contains( 'category_portfolio-active' );
        } else if ( block === 'Realized' ) {
          visible = article.classList.contains( 'category_portfolio-realized' );
        } else {
          visible = false;
        }

        article.classList.toggle( 'hide', ! visible );
        if ( visible ) visibleCount++;
      } );

      applyEmptyState( block, visibleCount );
    } );
  } );
}

// Portfolio detail popup. Each card embeds a sibling <dialog>; the shared
// modal helper handles backdrop click + close-button wiring, and the document
// delegate below pairs each open trigger with its matching dialog.
export function initPortfolioPopups() {
  const dialogs = qsa( '.portfolio-popup' );
  if ( ! dialogs.length ) return;

  dialogs.forEach( ( dialog ) => bindModal( dialog ) );

  document.addEventListener( 'click', ( event ) => {
    const opener = event.target.closest( '[data-portfolio-popup-open]' );
    if ( ! opener ) return;
    const card   = opener.closest( '[data-portfolio-card]' );
    const dialog = card && card.querySelector( '.portfolio-popup' );
    if ( dialog ) {
      event.preventDefault();
      openModal( dialog );
    }
  } );
}
