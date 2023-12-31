import React from 'react';
import { compose } from 'redux';
import { inject } from 'lib/Injector';
import PropTypes from 'prop-types';
import AnyFieldData from '../../types/AnyFieldData';
import AbstractAnyField, { anyFieldPropTypes } from '../AbstractAnyField/AbstractAnyField';
import anyFieldHOC from '../AbstractAnyField/anyFieldHOC';

/**
 * Renders a Field allowing the selection of a single link.
 */
const AnyField = (props) => {
  const staticProps = {
    buildProps: () => {
      const { data, anyFieldDescriptions, allowedDataObjectClasses } = props;

      // Try to read the link type from the link data or use newTypeKey
      const { dataObjectClassKey } = data;
      const dataObjectClass = allowedDataObjectClasses[dataObjectClassKey];

      // Read DataObject title and description
      const anyDescription = anyFieldDescriptions.length > 0 ? anyFieldDescriptions[0] : {};
      const { title, description } = anyDescription;
      return {
        title,
        description,
        dataObjectClass: dataObjectClass || undefined,
      };
    },
    clearData: () => ({}),
    updateData: newAnyFieldData => newAnyFieldData,
    selectData: () => (props.data),
  };

  // If we are getting an empty array, we need to convert it to an empty object
  if (props.data === []) {
    props.data = {};
  }

  return <AbstractAnyField {...props} {...staticProps} />;
};

AnyField.propTypes = {
  ...anyFieldPropTypes,
  data: PropTypes.oneOfType([AnyFieldData, PropTypes.array]),
};

export { AnyField as Component };

export default compose(
  inject(
    ['AnyPicker', 'Loading'],
    (AnyPicker, Loading) => ({ Picker: AnyPicker, Loading })
  ),
  anyFieldHOC
)(AnyField);
