import React from 'react';
import { compose } from 'redux';
import { inject } from 'lib/Injector';
import PropTypes from 'prop-types';
import { v4 as uuidv4 } from 'uuid';
import LinkData from '../../types/LinkData';
import AbstractLinkField, { linkFieldPropTypes } from '../AbstractLinkField/AbstractLinkField';
import linkFieldHOC from '../AbstractLinkField/linkFieldHOC';

/**
 * Helper that matches links to their descriptions
 */
function mergeLinkDataWithDescription(links, descriptions) {
  return links.map(link => {
    const description = descriptions.find(({ id }) => id.toString() === link.ID.toString());
    return { ...link, ...description };
  });
}

/**
 * Renders a LinkField allowing the selection of multiple links.
 */
const MultiLinkField = (props) => {
  const staticProps = {
    buildLinkProps: () => ({
      links: mergeLinkDataWithDescription(props.data, props.linkDescriptions),
    }),
    clearLinkData: linkId => (
      props.data.filter(({ ID }) => ID !== linkId)
    ),
    updateLinkData: newLinkData => {
      const { data } = props;
      return newLinkData.ID ?
        data.map(oldLink => (oldLink.ID === newLinkData.ID ? newLinkData : oldLink)) :
        [...data, { ...newLinkData, ID: uuidv4(), isNew: true }];
    },
    selectLinkData: (editingId) => (
      props.data.find(({ ID }) => ID === editingId) || undefined
    )
  };

  return <AbstractLinkField {...props} {...staticProps} />;
};

MultiLinkField.propTypes = {
  ...linkFieldPropTypes,
  data: PropTypes.arrayOf(LinkData),
};

export { MultiLinkField as Component };

export default compose(
  inject(
    ['MultiLinkPicker', 'Loading'],
    (MultiLinkPicker, Loading) => ({ Picker: MultiLinkPicker, Loading })
  ),
  linkFieldHOC
)(MultiLinkField);
