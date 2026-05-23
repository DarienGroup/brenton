import '../scss/main.scss';
import { ready } from './parts/utils';
import { initNavigation } from './parts/navigation';
import { initFooter } from './parts/footer';
import { initScrollReveal } from './parts/animations';
import { initPortfolioReadMore, initTeamReadMore } from './parts/read-more';
import { initHomePage } from './pages/home';
import { initPage } from './pages/page';
import { initPortfolioTabs } from './pages/portfolio';

ready(() => {
  initNavigation();
  initFooter();
  initScrollReveal();
  initPortfolioReadMore();
  initTeamReadMore();
  initPortfolioTabs();
  initHomePage();
  initPage();
});
