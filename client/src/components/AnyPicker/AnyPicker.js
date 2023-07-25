import React from 'react';
import PropTypes from 'prop-types';
import classnames from 'classnames';
import AnyPickerMenu from './AnyPickerMenu';
import AnyPickerTitle from './AnyPickerTitle';
import AnyFieldBox from '../AnyFieldBox/AnyFieldBox';
import AllowedDataObjectClass from '../../types/AllowedDataObjectClass';

const AnyPicker = ({ id, types, onSelect, title, description, type, onEdit, onClear }) => (
  <AnyFieldBox className={classnames('any-picker', { 'any-picker--selected': type })} id={id} >
    { type ?
      <AnyPickerTitle
        description={description}
        title={title}
        type={type}
        onClear={onClear}
        onClick={() => onEdit && onEdit()}
      /> :
      <AnyPickerMenu types={types} onSelect={onSelect} />
    }
  </AnyFieldBox>
);

AnyPicker.propTypes = {
  ...AnyPickerMenu.propTypes,
  onEdit: PropTypes.func,
  onClear: PropTypes.func,
  title: PropTypes.string,
  description: PropTypes.string,
  allowedDataObjectClass: AllowedDataObjectClass,
  id: PropTypes.string.isRequired,
};


export { AnyPicker as Component };

export default AnyPicker;
