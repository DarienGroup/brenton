import '../scss/main.scss';
import { ready } from './parts/utils';
import { initNavigation } from './parts/navigation';
import { initFooter } from './parts/footer';
import { initScrollReveal } from './parts/animations';
import { initPortfolioReadMore, initTeamReadMore } from './parts/read-more';
import { initContactForm } from './parts/contact-form';
import { initAboutTabs } from './parts/about-tabs';
import { initInvestmentVideo } from './parts/investment-video';
import { initHearFromUs } from './parts/hear-from-us';
import { initHomePage } from './pages/home';
import { initPage } from './pages/page';
import { initPortfolioTabs } from './pages/portfolio';

ready(() => {
  initNavigation();
  initFooter();
  initScrollReveal();
  initPortfolioReadMore();
  initTeamReadMore();
  initContactForm();
  initAboutTabs();
  initInvestmentVideo();
  initHearFromUs();
  initPortfolioTabs();
  initHomePage();
  initPage();
});
