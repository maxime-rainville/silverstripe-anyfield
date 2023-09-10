import React from 'react';
import PropTypes from 'prop-types';
import AnyPickerMenu from '../AnyPicker/AnyPickerMenu';
import AnyPickerTitle from '../AnyPicker/AnyPickerTitle';
import AnyFieldBox from '../AnyFieldBox/AnyFieldBox';
import ManyAnyList from './ManyAnyList';
import { SortableContainer, SortableElement, SortableHandle } from 'react-sortable-hoc';


const ManyAnyPicker = ({
  onSelect, allowedDataObjectClasses, dataobjects, onEdit, onClear,
  baseDataObjectName, baseDataObjectIcon, id, onSort, sortable
}) => (
  <div className="multi-any-picker" data-manyanyfield-id={id}>
    <AnyFieldBox className="multi-any-picker__picker">
      <AnyPickerMenu allowedDataObjectClasses={allowedDataObjectClasses} onSelect={onSelect} baseDataObjectName={baseDataObjectName} baseDataObjectIcon={baseDataObjectIcon} />
    </AnyFieldBox>
    { dataobjects.length > 0 &&
      <ManyAnyList
        dataobjects={dataobjects}
        onClear={onClear}
        onEdit={onEdit}
        useDragHandle
        helperClass="sortableHelper"
        onSortEnd={onSort}
        sortable={sortable}
      />
    }
  </div>
);


ManyAnyPicker.propTypes = {
  ...AnyPickerMenu.propTypes,
  dataobjects: PropTypes.arrayOf(PropTypes.shape(AnyPickerTitle.propTypes)),
  onEdit: PropTypes.func,
  onClear: PropTypes.func,
  onSort: PropTypes.func,
  id: PropTypes.string.isRequired,
  sortable: PropTypes.bool,
};


export { ManyAnyPicker as Component };

export default ManyAnyPicker;
