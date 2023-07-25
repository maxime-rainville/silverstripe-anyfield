/* eslint-disable */
import i18n from 'i18n';
import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { Dropdown, DropdownToggle, DropdownMenu, DropdownItem } from 'reactstrap';
import AllowedDataObjectClass from 'types/AllowedDataObjectClass';

/**
 * Displays a dropdown menu allowing the user to pick a DataObject type.
 */
const AnyPickerMenu = ({ types, onSelect, id }) => {
  const [isOpen, setIsOpen] = useState(false);
  const toggle = () => setIsOpen(prevState => !prevState);

  return (
    <Dropdown
      isOpen={isOpen}
      toggle={toggle}
      className="any-menu"
    >
      <DropdownToggle className="any-menu__toggle font-icon-link" caret>{i18n._t('AnyField.ADD_DATAOBJECT', 'Add Data Object')}</DropdownToggle>
      <DropdownMenu>
        {types.map(({ key, title, icon }) =>
          <DropdownItem className={`font-icon-${icon || 'link'}`} key={key} onClick={() => onSelect(key)}>{title}</DropdownItem>
        )}
      </DropdownMenu>
    </Dropdown>
  );
};

AnyPickerMenu.propTypes = {
  allowedDataObjectClasses: PropTypes.arrayOf(AllowedDataObjectClass).isRequired,
  onSelect: PropTypes.func.isRequired
};

export default AnyPickerMenu;
