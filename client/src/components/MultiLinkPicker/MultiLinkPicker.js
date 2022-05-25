import React from 'react';
import PropTypes from 'prop-types';
import LinkPickerMenu from '../LinkPicker/LinkPickerMenu';
import LinkPickerTitle from '../LinkPicker/LinkPickerTitle';
import LinkBox from '../LinkBox/LinkBox';

const LinkPicker = ({ types, onSelect, links, onEdit, onClear }) => (
  <div className="multi-link-picker">
    <LinkBox className="multi-link-picker__picker">
      <LinkPickerMenu types={types} onSelect={onSelect} />
    </LinkBox>
    { links.length > 0 && <LinkBox className="multi-link-picker__list">
      { links.map(({ ID, ...link }) => (
        <LinkPickerTitle
          {...link}
          className="multi-link-picker__link"
          type={types.find(type => type.key === link.typeKey)}
          key={`${ID} ${link.description}`}
          onClear={(event) => onClear(event, ID)}
          onClick={() => onEdit(ID)}
        />
      )) }
    </LinkBox> }
  </div>
);

LinkPicker.propTypes = {
  ...LinkPickerMenu.propTypes,
  links: PropTypes.arrayOf(PropTypes.shape(LinkPickerTitle.propTypes)),
  onEdit: PropTypes.func,
  onClear: PropTypes.func,
};


export { LinkPicker as Component };

export default LinkPicker;
