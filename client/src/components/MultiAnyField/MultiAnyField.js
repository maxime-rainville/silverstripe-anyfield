import React from 'react';
import { compose } from 'redux';
import { inject } from 'lib/Injector';
import PropTypes from 'prop-types';
import { v4 as uuidv4 } from 'uuid';
import AnyFieldData from '../../types/AnyFieldData';
import AbstractAnyField, { anyFieldPropTypes } from '../AbstractAnyField/AbstractAnyField';
import anyFieldHOC from '../AbstractAnyField/anyFieldHOC';

/**
 * Helper that matches dataobjects to their descriptions
 */
function mergeAnyFieldDataWithDescription(links, descriptions) {
  return links.map(link => {
    const description = descriptions.find(({ id }) => id.toString() === link.ID.toString());
    return { ...link, ...description };
  });
}

/**
 * Renders a AnyField allowing the selection of multiple links.
 */
const MultiAnyField = (props) => {
  const staticProps = {
    buildProps: () => ({
      links: mergeAnyFieldDataWithDescription(props.data, props.linkDescriptions),
    }),
    clearData: linkId => (
      props.data.filter(({ ID }) => ID !== linkId)
    ),
    updateData: newDataObject => {
      const { data } = props;
      return newDataObject.ID ?
        data.map(oldDataObject => (oldDataObject.ID === newDataObject.ID ? newDataObject : oldDataObject)) :
        [...data, { ...newDataObject, ID: uuidv4(), isNew: true }];
    },
    selectData: (editingId) => (
      props.data.find(({ ID }) => ID === editingId) || undefined
    )
  };

  return <AbstractAnyField {...props} {...staticProps} />;
};

MultiAnyField.propTypes = {
  ...anyFieldPropTypes,
  data: PropTypes.arrayOf(AnyFieldData),
};

export { MultiAnyField as Component };

export default compose(
  inject(
    ['MultiAnyPicker', 'Loading'],
    (MultiAnyPicker, Loading) => ({ Picker: MultiAnyPicker, Loading })
  ),
  anyFieldHOC
)(MultiAnyField);
