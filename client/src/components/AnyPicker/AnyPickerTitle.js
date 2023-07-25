/* eslint-disable */
import i18n from 'i18n';
import React from 'react';
import PropTypes from 'prop-types';
import AllowedDataObjectClass from 'types/AllowedDataObjectClass';
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

const AnyPickerTitle = ({ title, type, description, onClear, onClick, className, id }) => (
  <Button
    className={classnames('any-field-title', `font-icon-${type.icon || 'link'}`, className)}
    color="secondary"
    onClick={stopPropagation(onClick)}
    id={id}
  >
    <div className="any-field-title__detail">
      <div className="any-field-title__title">{title}</div>
      <small className="any-field-title__type">
        {type.title}:&nbsp;
        <span className="any-field-title__url">{description}</span>
      </small>
    </div>
    <Button tag="a" className="any-field-title__clear" color="link" onClick={stopPropagation(onClear)}>{i18n._t('AnyField.CLEAR', 'Clear')}</Button>
  </Button>
);

AnyPickerTitle.propTypes = {
  title: PropTypes.string.isRequired,
  allowedDataObjectClass: AllowedDataObjectClass,
  description: PropTypes.string,
  onClear: PropTypes.func,
  onClick: PropTypes.func
};

AnyPickerTitle.defaultProps = {
  type: {}
};

export default AnyPickerTitle;
