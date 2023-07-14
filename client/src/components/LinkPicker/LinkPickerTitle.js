/* eslint-disable */
import i18n from 'i18n';
import React from 'react';
import PropTypes from 'prop-types';
import LinkType from 'types/LinkType';
import { Button } from 'reactstrap';

const stopPropagation = (fn) => (e) => {
  e.nativeEvent.stopImmediatePropagation();
  e.preventDefault();
  e.nativeEvent.preventDefault();
  e.stopPropagation();
  if (fn) {
    fn();
  }
};

const LinkPickerTitle = ({ title, type, description, onClear, onClick, className, id }) => (
  <Button
    className={classnames('link-title', `font-icon-${type.icon || 'link'}`, className)}
    color="secondary"
    onClick={stopPropagation(onClick)}
    id={id}
  >
    <div className="link-title__detail">
      <div className="link-title__title">{title}</div>
      <small className="link-title__type">
        {type.title}:&nbsp;
        <span className="link-title__url">{description}</span>
      </small>
    </div>
    <Button tag="a" className="link-title__clear" color="link" onClick={stopPropagation(onClear)}>{i18n._t('Link.CLEAR', 'Clear')}</Button>
  </Button>
);

LinkPickerTitle.propTypes = {
  title: PropTypes.string.isRequired,
  type: LinkType,
  description: PropTypes.string,
  onClear: PropTypes.func,
  onClick: PropTypes.func
};

LinkPickerTitle.defaultProps = {
  type: {}
};

export default LinkPickerTitle;
