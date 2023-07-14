/* eslint-disable */
import i18n from 'i18n';
import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { Dropdown, DropdownToggle, DropdownMenu, DropdownItem } from 'reactstrap';
import LinkType from 'types/LinkType';

/**
 * Displays a dropdown menu allowing the user to pick a link type.
 */
const LinkPickerMenu = ({ types, onSelect, id }) => {
  const [isOpen, setIsOpen] = useState(false);
  const toggle = () => setIsOpen(prevState => !prevState);

  return (
    <Dropdown
      isOpen={isOpen}
      toggle={toggle}
      className="link-menu"
    >
      <DropdownToggle className="link-menu__toggle font-icon-link" caret>{i18n._t('Link.ADD_LINK', 'Add Link')}</DropdownToggle>
      <DropdownMenu>
        {types.map(({ key, title, icon }) =>
          <DropdownItem className={`font-icon-${icon || 'link'}`} key={key} onClick={() => onSelect(key)}>{title}</DropdownItem>
        )}
      </DropdownMenu>
    </Dropdown>
  );
};

LinkPickerMenu.propTypes = {
  types: PropTypes.arrayOf(LinkType).isRequired,
  onSelect: PropTypes.func.isRequired
};

export default LinkPickerMenu;
