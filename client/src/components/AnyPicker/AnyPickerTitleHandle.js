/* eslint-disable */
import i18n from 'i18n';
import React, { Fragment } from 'react';
import PropTypes from 'prop-types';
import { SortableHandle } from 'react-sortable-hoc';
import classnames from 'classnames';

const AnyPickerTitleHandle = SortableHandle(({ className }) => (
  <span
    className={classnames('any-picker-title__handle font-icon-drag-handle', className)}
    aria-label='Reorder element' />
));

AnyPickerTitleHandle.propTypes = {
  className: PropTypes.string,
};

AnyPickerTitleHandle.defaultProps = { };

export default AnyPickerTitleHandle;
