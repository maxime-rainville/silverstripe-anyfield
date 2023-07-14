import React, { Fragment, useState } from 'react';
import { loadComponent } from 'lib/Injector';
import PropTypes from 'prop-types';
import LinkType from '../../types/LinkType';
import LinkSummary from '../../types/LinkSummary';

/**
 * Underlying implementation of the LinkField. This is used for both the Single LinkField
 * and MultiLinkField. It should not be used directly.
 */
const AbstractLinkField = ({
  id, loading, Loading, Picker, onChange, types,
  clearLinkData, buildLinkProps, updateLinkData, selectLinkData
}) => {
  // Render a loading indicator if we're still fetching some data from the server
  if (loading) {
    return <Loading />;
  }

  // When editing is true, wu display a modal to let the user edit the link data
  const [editingId, setEditingId] = useState(false);
  // newTypeKey define what link type we are using for brand new links
  const [newTypeKey, setNewTypeKey] = useState('');

  const selectedLinkData = selectLinkData(editingId);
  const modalType = types[(selectedLinkData && selectedLinkData.typeKey) || newTypeKey];

  // When the use clears the link data, we call onchange with an empty object
  const onClear = (event, linkId) => {
    if (typeof onChange === 'function') {
      onChange(event, { id, value: clearLinkData(linkId) });
    }
  };

  const linkProps = {
    ...buildLinkProps(),
    id,
    onEdit: (linkId) => { setEditingId(linkId); },
    onClear,
    onSelect: (key) => {
      setNewTypeKey(key);
      setEditingId(true);
    },
    types: Object.values(types)
  };

  const onModalSubmit = (submittedData) => {
    // Remove unneeded keys from submitted data
    // eslint-disable-next-line camelcase
    const { SecurityID, action_insert, ...newLinkData } = submittedData;
    if (typeof onChange === 'function') {
      // onChange expect an event object which we don't have
      onChange(undefined, { id, value: updateLinkData(newLinkData) });
    }
    // Close the modal
    setEditingId(false);
    setNewTypeKey('');
    return Promise.resolve();
  };

  const modalProps = {
    type: modalType,
    editing: editingId !== false,
    onSubmit: onModalSubmit,
    onClosed: () => {
      setEditingId(false);
      return Promise.resolve();
    },
    data: selectedLinkData
  };

  // Different link types might have different  Link modal
  const handlerName = modalType ? modalType.handlerName : 'FormBuilderModal';
  const LinkModal = loadComponent(`LinkModal.${handlerName}`);

  return (
    <Fragment>
      <Picker {...linkProps} />
      <LinkModal {...modalProps} />
    </Fragment>
  );
};

/**
 * These props are expected to be passthrough from tho parent component.
 */
export const linkFieldPropTypes = {
  id: PropTypes.string.isRequired,
  loading: PropTypes.bool,
  Loading: PropTypes.elementType,
  data: PropTypes.any,
  Picker: PropTypes.elementType,
  onChange: PropTypes.func,
  types: PropTypes.objectOf(LinkType),
  linkDescriptions: PropTypes.arrayOf(LinkSummary),
};

AbstractLinkField.propTypes = {
  ...linkFieldPropTypes,
  // These props need to be provided by the specific implementation
  clearLinkData: PropTypes.func.isRequired,
  buildLinkProps: PropTypes.func.isRequired,
  updateLinkData: PropTypes.func.isRequired,
  selectLinkData: PropTypes.func.isRequired,
};

export default AbstractLinkField;
