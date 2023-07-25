import React from 'react';
import PropTypes from 'prop-types';
import classnames from 'classnames';

/**
 * Wraps children in a bok with rounder corners and a form control style.
 */
const AnyFieldBox = ({ className, children, id }) => (
  <div className={classnames('any-field-box', 'form-control', className)} data-anyfield-id={id}>
    { children }
  </div>
);

AnyFieldBox.propTypes = {
  className: PropTypes.string,
  id: PropTypes.string,
};

export default AnyFieldBox;
