import React from 'react';
import PropTypes from 'prop-types';
import AnyPickerMenu from '../AnyPicker/AnyPickerMenu';
import AnyPickerTitle from '../AnyPicker/AnyPickerTitle';
import AnyFieldBox from '../AnyFieldBox/AnyFieldBox';
import { SortableContainer, SortableElement } from 'react-sortable-hoc';


const SortablePicker = SortableElement((props) => (
  <AnyPickerTitle {...props} sortable />
));

const ManyAnyList = SortableContainer(({
  dataobjects, onEdit, onClear, sortable
}) => {
  const Picker = sortable ? SortablePicker : AnyPickerTitle;
  return (
    <AnyFieldBox className="multi-any-picker__list">
      { dataobjects.map(({ ID, ...dataobject }, index) => (
        <Picker
          {...dataobject}
          className="multi-any-picker__dataobject"
          key={`${ID} ${dataobject.description}`}
          index={index}
          onClear={(event) => onClear(event, ID)}
          onClick={() => onEdit(ID)}
        />
      )) }
    </AnyFieldBox>
  );
});


ManyAnyList.propTypes = {
  ...AnyPickerMenu.propTypes,
  dataobjects: PropTypes.arrayOf(PropTypes.shape(AnyPickerTitle.propTypes)),
  onEdit: PropTypes.func,
  onClear: PropTypes.func,
  sortable: PropTypes.func
};


export { ManyAnyList as Component };

export default ManyAnyList;
