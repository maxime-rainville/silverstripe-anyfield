/* eslint-disable */
import i18n from 'i18n';
import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { Dropdown, DropdownToggle, DropdownMenu, DropdownItem } from 'reactstrap';
import AllowedDataObjectClass from 'types/AllowedDataObjectClass';

/**
 * Displays a dropdown menu allowing the user to pick a DataObject type.
 */
const AnyPickerMenu = ({ allowedDataObjectClasses, onSelect, baseDataObjectName, baseDataObjectIcon }) => {
  const [isOpen, setIsOpen] = useState(false);
  const toggle = () => setIsOpen(prevState => !prevState);

  return (
    <Dropdown
      isOpen={isOpen}
      toggle={toggle}
      className="any-picker-menu"
    >
      <DropdownToggle className={`any-picker-menu__toggle ${baseDataObjectIcon || 'plus-1'}`} caret>
        {
          i18n.sprintf(
            i18n._t('AnyField.ADD_DATAOBJECT', 'Add %s'),
            baseDataObjectName
          )
        }
        </DropdownToggle>
      <DropdownMenu>
        {allowedDataObjectClasses.map(({ key, title, icon }) =>
          <DropdownItem className={`${icon || 'link'}`} key={key} onClick={() => onSelect(key)}>{title}</DropdownItem>
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
