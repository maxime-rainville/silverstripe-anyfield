import React, { Fragment, useState } from 'react';
import { loadComponent } from 'lib/Injector';
import PropTypes from 'prop-types';
import AllowedDataObjectClass from '../../types/AllowedDataObjectClass';
import AnyFieldSummary from '../../types/AnyFieldSummary';

/**
 * Underlying implementation of the AnyField. This is used for both the Single AnyField
 * and MultiAnyField. It should not be used directly.
 */
const AbstractAnyField = ({
  id, loading, Loading, Picker, onChange, allowedDataObjectClasses,
  clearData, buildProps, updateData, selectData
}) => {
  // Render a loading indicator if we're still fetching some data from the server
  if (loading) {
    return <Loading />;
  }

  // When editing is true, we display a modal to let the user edit the link data
  const [editingId, setEditingId] = useState(false);
  // newTypeKey define what link type we are using for brand new links
  const [newDataObjectClassKey, setNewDataObjectClassKey] = useState('');

  const selectedData = selectData(editingId);
  const modalDataObjectClass = allowedDataObjectClasses[(selectedData && selectedData.ClassName) || newDataObjectClassKey];

  // When the use clears the link data, we call onchange with an empty object
  const onClear = (event, recordId) => {
    if (typeof onChange === 'function') {
      onChange(event, { id, value: clearData(recordId) });
    }
  };

  const pickerProps = {
    ...buildProps(),
    id,
    onEdit: (recordId) => { setEditingId(recordId); },
    onClear,
    onSelect: (key) => {
      setNewDataObjectClassKey(key);
      setEditingId(true);
    },
    allowedDataObjectClasses: Object.values(allowedDataObjectClasses)
  };

  const onModalSubmit = (submittedData) => {
    // Remove unneeded keys from submitted data
    // eslint-disable-next-line camelcase
    const { SecurityID, action_insert, ...newDataObject } = submittedData;
    if (typeof onChange === 'function') {
      // onChange expect an event object which we don't have
      onChange(undefined, { id, value: updateData(newDataObject) });
    }
    // Close the modal
    setEditingId(false);
    setNewDataObjectClassKey('');
    return Promise.resolve();
  };

  const modalProps = {
    dataObjectClass: modalDataObjectClass,
    editing: editingId !== false,
    onSubmit: onModalSubmit,
    onClosed: () => {
      setEditingId(false);
      return Promise.resolve();
    },
    data: selectedData
  };

  // Different link types might have different AnyModal
  const modalHandler = modalDataObjectClass && modalDataObjectClass.modalHandler ? modalDataObjectClass.modalHandler : 'FormBuilderModal';
  const Modal = loadComponent(`AnyModal.${modalHandler}`);

  return (
    <Fragment>
      <Picker {...pickerProps} />
      <Modal {...modalProps} />
    </Fragment>
  );
};

/**
 * These props are expected to be passthrough from tho parent component.
 */
export const anyFieldPropTypes = {
  id: PropTypes.string.isRequired,
  loading: PropTypes.bool,
  Loading: PropTypes.elementType,
  data: PropTypes.any,
  Picker: PropTypes.elementType,
  onChange: PropTypes.func,
  allowedDataObjectClasses: PropTypes.objectOf(AllowedDataObjectClass),
  dataobjectDescriptions: PropTypes.arrayOf(AnyFieldSummary),
};

AbstractAnyField.propTypes = {
  ...anyFieldPropTypes,
  // These props need to be provided by the specific implementation
  clearData: PropTypes.func.isRequired,
  buildProps: PropTypes.func.isRequired,
  updateData: PropTypes.func.isRequired,
  selectData: PropTypes.func.isRequired,
};

export default AbstractAnyField;
