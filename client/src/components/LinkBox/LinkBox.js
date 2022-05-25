import React from 'react';
import PropTypes from 'prop-types';
import classnames from 'classnames';

/**
 * Wraps children in a bok with rounder corners and a form control style.
 */
const LinkBox = ({ className, children }) => (
  <div className={classnames('link-box', 'form-control', className)}>
    { children }
  </div>
);

LinkBox.propTypes = {
  className: PropTypes.string,
};

export default LinkBox;
