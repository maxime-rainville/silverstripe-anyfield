import React from 'react';
import PropTypes from 'prop-types';
import classnames from 'classnames';
import AnyPickerMenu from './AnyPickerMenu';
import AnyPickerTitle from './AnyPickerTitle';
import AnyFieldBox from '../AnyFieldBox/AnyFieldBox';
import AllowedDataObjectClass from '../../types/AllowedDataObjectClass';

const AnyPicker = ({ id, allowedDataObjectClasses, onSelect, title, description, dataObjectClass, onEdit, onClear, baseDataObjectName, baseDataObjectIcon }) => (
  <AnyFieldBox
    className={classnames('any-picker', { 'any-picker--selected': dataObjectClass })}
    data-anyfield-id={id}
  >
    { dataObjectClass ?
      <AnyPickerTitle
        description={description}
        title={title}
        dataObjectClass={dataObjectClass}
        onClear={onClear}
        onClick={() => onEdit && onEdit()}
      /> :
      <AnyPickerMenu allowedDataObjectClasses={allowedDataObjectClasses} onSelect={onSelect} baseDataObjectName={baseDataObjectName} baseDataObjectIcon={baseDataObjectIcon} />
    }
  </AnyFieldBox>
);

AnyPicker.propTypes = {
  ...AnyPickerMenu.propTypes,
  onEdit: PropTypes.func,
  onClear: PropTypes.func,
  title: PropTypes.string,
  description: PropTypes.string,
  dataObjectClass: AllowedDataObjectClass,
  id: PropTypes.string.isRequired,
};


export { AnyPicker as Component };

export default AnyPicker;
