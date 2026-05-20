import '../scss/main.scss';
import { ready } from './parts/utils';
import { initNavigation } from './parts/navigation';
import { initScrollReveal } from './parts/animations';
import { initHomePage } from './pages/home';
import { initPage } from './pages/page';

ready(() => {
  initNavigation();
  initScrollReveal();
  initHomePage();
  initPage();
});
