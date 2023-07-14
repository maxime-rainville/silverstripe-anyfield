import React from 'react';
import PropTypes from 'prop-types';
import classnames from 'classnames';
import LinkPickerMenu from './LinkPickerMenu';
import LinkPickerTitle from './LinkPickerTitle';
import LinkBox from '../LinkBox/LinkBox';
import LinkType from '../../types/LinkType';

const LinkPicker = ({ id, types, onSelect, title, description, type, onEdit, onClear }) => (
  <LinkBox className={classnames('link-picker', { 'link-picker--selected': type })} id={id} >
    { type ?
      <LinkPickerTitle
        description={description}
        title={title}
        type={type}
        onClear={onClear}
        onClick={() => onEdit && onEdit()}
      /> :
      <LinkPickerMenu types={types} onSelect={onSelect} />
    }
  </LinkBox>
);

LinkPicker.propTypes = {
  ...LinkPickerMenu.propTypes,
  onEdit: PropTypes.func,
  onClear: PropTypes.func,
  title: PropTypes.string,
  description: PropTypes.string,
  type: LinkType,
  id: PropTypes.string.isRequired,
};


export { LinkPicker as Component };

export default LinkPicker;
