import React from 'react';
import { compose } from 'redux';
import { inject } from 'lib/Injector';
import PropTypes from 'prop-types';
import { v4 as uuidv4 } from 'uuid';
import AnyFieldData from '../../types/AnyFieldData';
import AbstractAnyField, { anyFieldPropTypes } from '../AbstractAnyField/AbstractAnyField';
import anyFieldHOC from '../AbstractAnyField/anyFieldHOC';
import { arrayMoveImmutable as arrayMove } from 'array-move';

/**
 * Helper that matches dataobjects to their descriptions
 */
function mergeAnyFieldDataWithDescription(datalist, descriptions, allowedDataObjectClasses) {
  return datalist.map(dataobject => {
    const description = descriptions.find(({ id }) => id.toString() === dataobject.ID.toString());
    return {
      ...dataobject,
      ...description,
      dataObjectClass: allowedDataObjectClasses[dataobject.dataObjectClassKey]
    };
  });
}

/**
 * Renders a AnyField allowing the selection of multiple links.
 */
const ManyAnyField = ({ sortable, ...props }) => {
  const staticProps = {
    buildProps: () => ({
      dataobjects: mergeAnyFieldDataWithDescription(props.data, props.anyFieldDescriptions, props.allowedDataObjectClasses),
      onSort: ({ oldIndex, newIndex }, event) => {
        props.onChange(event, { id: props.id, value: JSON.stringify(arrayMove(props.data, oldIndex, newIndex)) });
      },
      sortable
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
    selectData: (editingId) => {
      if (props.data) {
        return props.data.find(({ ID }) => ID === editingId);
      }
      return undefined;
    }
  };

  return <AbstractAnyField {...props} {...staticProps} />;
};

ManyAnyField.propTypes = {
  ...anyFieldPropTypes,
  data: PropTypes.arrayOf(AnyFieldData),
  sortable: PropTypes.bool,
};

export { ManyAnyField as Component };

export default compose(
  inject(
    ['ManyAnyPicker', 'Loading'],
    (ManyAnyPicker, Loading) => ({ Picker: ManyAnyPicker, Loading })
  ),
  anyFieldHOC
)(ManyAnyField);
