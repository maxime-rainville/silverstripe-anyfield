/* eslint-disable */
import i18n from 'i18n';
import React, { Fragment } from 'react';
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

const AnyPickerTitle = ({ title, dataObjectClass, description, onClear, onClick, className, id }) => (
  <Button
    className={classnames('any-picker-title', `font-icon-${dataObjectClass.icon || 'link'}`, className)}
    color="secondary"
    onClick={stopPropagation(onClick)}
    id={id}
  >
    <div className="any-picker-title__detail">
      <div className="any-picker-title__title">{title}</div>
      <small className="any-picker-title__type">
        {dataObjectClass.title}
        {
          description &&
          <Fragment>
            :&nbsp;
            <span className="any-picker-title__url">{description}</span>
          </Fragment>
        }

      </small>
    </div>
    <Button tag="a" className="any-picker-title__clear" color="link" onClick={stopPropagation(onClear)}>{i18n._t('AnyField.CLEAR', 'Clear')}</Button>
  </Button>
);

AnyPickerTitle.propTypes = {
  title: PropTypes.string.isRequired,
  dataObjectClass: AllowedDataObjectClass.isRequired,
  description: PropTypes.string,
  onClear: PropTypes.func,
  onClick: PropTypes.func
};

AnyPickerTitle.defaultProps = {
  dataObjectClass: {}
};

export default AnyPickerTitle;
