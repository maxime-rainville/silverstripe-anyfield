/* eslint-disable */
import PropTypes from 'prop-types';

const LinkType = PropTypes.shape({
  key: PropTypes.string.isRequired,
  icon: PropTypes.string,
  title: PropTypes.string.isRequired,
});

export default LinkType;
