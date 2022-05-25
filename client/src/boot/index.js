/* global document */
import registerReducers from './registerReducers';
import registerComponents from './registerComponents';
import registerQueries from './registerQueries';

document.addEventListener('DOMContentLoaded', () => {
  registerComponents();

  registerQueries();

  registerReducers();
});
