import React from 'react';
import PropTypes from 'prop-types';
import AnyPickerMenu from '../AnyPicker/AnyPickerMenu';
import AnyPickerTitle from '../AnyPicker/AnyPickerTitle';
import AnyFieldBox from '../AnyFieldBox/AnyFieldBox';

const ManyAnyPicker = ({
  onSelect, allowedDataObjectClasses, dataobjects, onEdit, onClear,
  baseDataObjectName, baseDataObjectIcon, id
}) => (
  <div className="multi-any-picker" data-manyanyfield-id={id}>
    <AnyFieldBox className="multi-any-picker__picker">
      <AnyPickerMenu allowedDataObjectClasses={allowedDataObjectClasses} onSelect={onSelect} baseDataObjectName={baseDataObjectName} baseDataObjectIcon={baseDataObjectIcon} />
    </AnyFieldBox>
    { dataobjects.length > 0 && <AnyFieldBox className="multi-any-picker__list">
      { dataobjects.map(({ ID, ...dataobject }) => (
        <AnyPickerTitle
          {...dataobject}
          className="multi-any-picker__dataobject"
          key={`${ID} ${dataobject.description}`}
          onClear={(event) => onClear(event, ID)}
          onClick={() => onEdit(ID)}
        />
      )) }
    </AnyFieldBox> }
  </div>
);


ManyAnyPicker.propTypes = {
  ...AnyPickerMenu.propTypes,
  dataobjects: PropTypes.arrayOf(PropTypes.shape(AnyPickerTitle.propTypes)),
  onEdit: PropTypes.func,
  onClear: PropTypes.func,
  id: PropTypes.string.isRequired,
};


export { ManyAnyPicker as Component };

export default ManyAnyPicker;
