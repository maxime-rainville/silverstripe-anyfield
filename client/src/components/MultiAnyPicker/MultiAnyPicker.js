import React from 'react';
import PropTypes from 'prop-types';
import AnyPickerMenu from '../AnyPicker/AnyPickerMenu';
import AnyPickerTitle from '../AnyPicker/AnyPickerTitle';
import AnyFieldBox from '../AnyFieldBox/AnyFieldBox';

const AnyPicker = ({ types, onSelect, dataobjects, onEdit, onClear }) => (
  <div className="multi-any-picker">
    <AnyFieldBox className="multi-any-picker__picker">
      <AnyPickerMenu types={types} onSelect={onSelect} />
    </AnyFieldBox>
    { dataobjects.length > 0 && <AnyFieldBox className="multi-any-picker__list">
      { dataobjects.map(({ ID, ...dataobject }) => (
        <AnyPickerTitle
          {...dataobject}
          className="multi-any-picker__dataobject"
          type={types.find(type => type.key === dataobject.typeKey)}
          key={`${ID} ${dataobject.description}`}
          onClear={(event) => onClear(event, ID)}
          onClick={() => onEdit(ID)}
        />
      )) }
    </AnyFieldBox> }
  </div>
);

AnyPicker.propTypes = {
  ...AnyPickerMenu.propTypes,
  dataobjects: PropTypes.arrayOf(PropTypes.shape(AnyPickerTitle.propTypes)),
  onEdit: PropTypes.func,
  onClear: PropTypes.func,
};


export { AnyPicker as Component };

export default AnyPicker;
