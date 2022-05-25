import React from 'react';
import { compose } from 'redux';
import { inject } from 'lib/Injector';
import PropTypes from 'prop-types';
import LinkData from '../../types/LinkData';
import AbstractLinkField, { linkFieldPropTypes } from '../AbstractLinkField/AbstractLinkField';
import linkFieldHOC from '../AbstractLinkField/linkFieldHOC';

/**
 * Renders a Field allowing the selection of a single link.
 */
const LinkField = (props) => {
  const staticProps = {
    buildLinkProps: () => {
      const { data, linkDescriptions, types } = props;

      // Try to read the link type from the link data or use newTypeKey
      const { typeKey } = data;
      const type = types[typeKey];

      // Read link title and description
      const linkDescription = linkDescriptions.length > 0 ? linkDescriptions[0] : {};
      const { title, description } = linkDescription;
      return {
        title,
        description,
        type: type || undefined,
      };
    },
    clearLinkData: () => ({}),
    updateLinkData: newLinkData => newLinkData,
    selectLinkData: () => (props.data),
  };

  return <AbstractLinkField {...props} {...staticProps} />;
};

LinkField.propTypes = {
  ...linkFieldPropTypes,
  data: LinkData
};

export { LinkField as Component };

export default compose(
  inject(
    ['LinkPicker', 'Loading'],
    (LinkPicker, Loading) => ({ Picker: LinkPicker, Loading })
  ),
  linkFieldHOC
)(LinkField);
