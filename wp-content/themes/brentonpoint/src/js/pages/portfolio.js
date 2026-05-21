import { qsa } from '../parts/utils';

/**
 * Portfolio tab filter: All / Active / Realized
 *
 * Expects:
 *  - .portfolio-tabs button[data-block="All|Active|Realized"]
 *  - .portfolio_block article.category_portfolio-active / .category_portfolio-realized
 */
export function initPortfolioTabs() {
  const buttons  = qsa( '.portfolio-tabs button' );
  const articles = qsa( '.portfolio_block article' );

  if ( ! buttons.length ) return;

  buttons.forEach( ( button ) => {
    button.addEventListener( 'click', () => {
      const block = button.getAttribute( 'data-block' );

      // Update active state on buttons
      buttons.forEach( ( btn ) => btn.classList.remove( 'active' ) );
      button.classList.add( 'active' );

      // Show / hide articles
      articles.forEach( ( article ) => {
        if ( block === 'All' ) {
          article.classList.remove( 'hide' );
          return;
        }

        const isActive   = block === 'Active'   && article.classList.contains( 'category_portfolio-active' );
        const isRealized = block === 'Realized' && article.classList.contains( 'category_portfolio-realized' );

        article.classList.toggle( 'hide', ! isActive && ! isRealized );
      } );
    } );
  } );
}
