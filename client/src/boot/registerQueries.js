/* eslint-disable */
import Injector from 'lib/Injector';
import readAnyFieldDescription from 'state/anyFieldDescription/readAnyFieldDescription';

const registerQueries = () => {
  Injector.query.register('readAnyFieldDescription', readAnyFieldDescription);
};
export default registerQueries;
