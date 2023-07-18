import React from 'react';
import { compose } from 'redux';
import { withApollo } from '@apollo/client/react/hoc';
import { injectGraphql } from 'lib/Injector';
import fieldHolder from 'components/FieldHolder/FieldHolder';

/**
 * When getting data from entwine, we might get it in a plain JSON string.
 * This method rewrites the data to a normalise format.
 */
export const stringifyData = (Component) => (({ data, value, ...props }) => {
  let dataValue = value || data;
  if (typeof dataValue === 'string') {
    dataValue = JSON.parse(dataValue);
  }
  return <Component dataStr={JSON.stringify(dataValue)} {...props} data={dataValue} />;
});


/**
 * Wires a Link field into GraphQL normalise the initial data to a proper objects
 */
const linkFieldHOC = compose(
  stringifyData,
  injectGraphql('readLinkTypes'),
  injectGraphql('readLinkDescription'),
  withApollo,
  fieldHolder
);

export default linkFieldHOC;
