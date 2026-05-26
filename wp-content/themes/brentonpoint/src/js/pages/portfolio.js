import { qsa } from '../parts/utils';

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
